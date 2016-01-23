<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 1/15/2016
 * Time: 2:26 PM
 */
class Scheduled_tasks extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('BB_issues');
    }
    function calibrate_bb_milestones(){
        $this->load->model("Milestone_model");
        $this->load->library("BB_milestones");
        $db_milestones = $this->Milestone_model->retrieve_all();
        $m_ids = [];/*reponame=>mid*/
        foreach($db_milestones as $m) {
            if(isset($m["bitbucket_repo_name"])) {//make sure there is a repo name
                if (isset($m_ids[$m["bitbucket_repo_name"]])) {
                    array_push($m_ids[$m["bitbucket_repo_name"]], $m["milestone_id"]);
                } else {
                    $m_ids[$m["bitbucket_repo_name"]] = [];
                }
            }
        }
        foreach($m_ids as $repo_slug=>$id_arr){
            $bb_milestones = $this->BB_milestones->getAllMilestones($repo_slug);
            foreach($bb_milestones as $id=>$name){//search for extra and remove
                if(!in_array($name, $id_arr)){
                    $delete_outcome = $this->BB_milestone->deleteMilestone($repo_slug, $id);
                }
            }
            $new_milestones = array_diff($id_arr, array_values($bb_milestones));
            foreach($new_milestones as $m){

            }

        }


    }
    /**
     * List all issues of given repo page
     * @param null $repo_slug
     */
    public function list_all($repo_slug=null){

        $user_id = $this->session->userdata('internal_uid');
        if(isset($user_id)) {

            if(isset($repo_slug)) {
                /*define constants*/
                $num_per_page = 25;
                /*params expected*/
                $opt_params = [
                    "search","sort","limit","start","status","kind","responsible","page",
                    "milestone","reported_by","priority","utc_created_on","utc_last_updated","title","content"
                ];
                $para_input = $this->input->get($opt_params,true);
                /***cannot sort by responsible*/
                if($para_input["sort"]=="responsible") $para_input["sort"] = null;
                /***initialize page if not given or not valid numerical value*/
                if(empty($para_input["page"]) ||!ctype_digit($para_input["page"])) $para_input["page"] = 1;
                $para =[];
                $para_raw = [];
                /*transfer $para_input to $para. Only keep non-null key value pairs.*/
                /*this prevents bad request*/
                foreach($para_input as $key=>$value){
                    if(!empty($value)){
                        /*Specially process "search" input. replace whitespace to +, as required in api*/
                        if($key=="search") {
                            if (strpos($value, "=") !== false
                                && in_array($filed = strtolower(trim(explode("=", $value)[0])), ["title", "content"])
                            ) {
                                $para_raw[$filed] = $para[$filed] = str_replace(" ", "+", trim(explode("=", $value)[1]));
                            } else {
                                $para_raw[$key] = $para[$key] = str_replace(" ", "+", $value);
                            }
                        }elseif($key=="page"){
                            $para_raw["page"] = $value;
                            $para["start"] = ($value-1) * $num_per_page;
                            $para["limit"] = $num_per_page;
                        }else{
                            $para_raw[$key] = $para[$key] = $value;
                        }
                    }
                }
                //TODO:validate parameters
                /*Get all issues*/
                $response = $this->bb_issues->retrieveIssues($repo_slug,null, $para);

                /*Get user bb_username to pass on to the page*/
                $this->load->model("Internal_user_model");
                $data= [
                    "issues"=>$response["issues"],
                    "count" => $response["count"],
                    "num_per_page" =>$num_per_page,
                    "repo_slug"=>$repo_slug,
                    "para_raw"=>$para_raw,
                    "user" =>$this->Internal_user_model->retrieve($user_id)
                ];
                //var_dump($data);
                $this->session->set_userdata('issue_list'.$repo_slug, $response["issues"]);
                $this->load->view("issue/all_2", $data);
            }else{
                //TODO: take user to 404 page
            }
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/internal_authentication/login/');
        }

    }

    /**
     * Create issue page
     * @param $repo_slug
     */
    public function create($repo_slug){
        $user_id = $this->session->userdata('internal_uid');
        if(isset($user_id)) {
            $this->load->model("Internal_user_model");
            $user = $this->Internal_user_model->retrieve($user_id);
            $this->load->view("issue/new", [
                "repo_slug"=>$repo_slug,
                "user"=>$user
            ]);
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/internal_authentication/login/');
        }
    }

    /**
     * Process user inputs from create issue page
     * @param $repo_slug
     */
    public function process_create($repo_slug){
        if($this->session->userdata('internal_uid')) {
            $field_params = ["status","priority","title","responsible","content","kind","milestone","deadline","usecase"];
            $para_input = $this->input->get($field_params,true);
            $param=[];
            foreach($para_input as $key=>$value){
                if(!empty($value)){
                    if(in_array($key,["usecase","milestone"]) && $value==0){
                        continue;
                    }
                    $param[$key] = $value;
                }
            }
            $issue = $this->bb_issues->postNewIssue($repo_slug, $param);
            $this->session->set_flashdata("issue_last_updated",$issue);
            /*Brings user back to this issue*/
            redirect(base_url()."Issues/detail/".$repo_slug."/".$issue["local_id"]);
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/internal_authentication/login/');
        }
    }

    /**
     * Issue detail page
     * @param null $repo_slug
     * @param null $issue_id
     */
    public function detail($repo_slug=null, $issue_id=null) {
        if($this->session->userdata('internal_uid')) {
            if (isset($repo_slug) && isset($issue_id)) {
                $comments = $this->bb_issues->getCommentsForIssue($repo_slug, $issue_id);
                /*if(!isset($comments)) $comments = [];

                foreach($comments as $key=>$value){

                }
                $comments = $this->parsedown->text();
                 */
                $data =[
                    "issue_details"=>$this->retrie_by_id($repo_slug,$issue_id),
                    "repo_slug"=>$repo_slug,
                    "comments"=>$comments
                ];
                $this->load->view("issue/view", $data);
            }else{
                //TODO: take user to 404 page
                die("repo slug is not set");
            }
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/internal_authentication/login/');
        }
    }
    private function retrie_by_id($repo_slug=null, $id=null){
        $issue_details = $this->session->flashdata("issue_last_updated");
        if(!isset($issue_details)){/*if issue list is not in session, or cannot find*/
            $issue_details = $this->bb_issues->retrieveIssues($repo_slug, $id);
        }
        return $issue_details;
    }
    public function input_comment($repo_slug, $issue_id){
        $comment_id = $this->input->post("comment-id");
        $comment = $this->input->post("content");
        if($comment_id=="new"){
            $comment_id = null;
        }
        $issue_details = $this->bb_issues->postCommentForIssue($repo_slug, $issue_id, $comment, $comment_id);
        redirect(base_url()."Issues/detail/".$repo_slug."/".$issue_id);
    }
    public function delete_comment($repo_slug, $issue_id) {
        $comment_id = $this->input->post("comment-id");
        $this->bb_issues->deleteCommentForIssue($repo_slug, $issue_id, $comment_id);
        //redirect(base_url()."Issues/detail/".$repo_slug."/".$issue_id);
    }

    /**
     * Processes Ajax request. Updates a single field
     * @param null $repo_slug
     */
    public function update($repo_slug=null,$issue_id){
        if($this->session->userdata('internal_uid')) {
            $param = $this->input->get("param",true);
            $value = $this->input->get("value",true);
            /*retrieve from session for performance*/
            if(($issue_list =$this->session->userdata("issue_list" . $repo_slug))!=null){
                foreach($issue_list as $issue){
                    if($issue["local_id"]==$issue_id) {
                        $issue[$param] = $value;
                        break;
                    }
                }
            }
            $issue = $this->bb_issues->updateIssue($repo_slug,$issue_id, [$param=>$value]);
            $this->session->set_flashdata("issue_last_updated",$issue);
            redirect(base_url()."Issues/detail/".$repo_slug."/".$issue_id);
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/internal_authentication/login/');
        }
    }

    /**
     * Edit issue page
     * @param null $repo_slug
     * @param $issue_id
     */
    public function edit($repo_slug=null, $issue_id){
        if($this->session->userdata('internal_uid')) {
            //$issue_id = $this->input->get("local_id");
            if (isset($repo_slug)&&isset($issue_id)) {
                $data =[
                    "issue_details"=>$this->retrie_by_id($repo_slug,$issue_id),
                    "repo_slug"=>$repo_slug
                ];
                $this->load->view("issue/edit", $data);
            }else{
                //TODO: take user to 404 page
                die("repo slug/issue id is not set");
            }
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/internal_authentication/login/');
        }

    }

    /**
     * Processes user input from edit page
     * @param null $repo_slug
     * @param $issue_id
     */
    public function process_edit($repo_slug=null, $issue_id){

        if($this->session->userdata('internal_uid')) {
            /*params expected*/
            $field_params = [
                "status","priority","title","responsible","content",
                "kind","milestone","comment","usecase","deadline"
            ];
            $para_input = $this->input->get($field_params,true);
            $param=[];
            foreach($para_input as $key=>$value){
                if(!empty($value)){
                    $param[$key] = $value;
                    if($key=="milestone" && !is_int($value)){
                        $this->load->library('BB_milestones');
                        $this->bb_milestones->postMilestone($repo_slug,"nil");
                    }
                }
            }
            //var_dump($param);
            $issue = $this->bb_issues->updateIssue($repo_slug,$issue_id, $param);
            $this->session->set_flashdata("issue_last_updated",$issue);
            /*brings user back to this issue*/

            header("Location: ".base_url()."issues/detail/".$repo_slug."/".$issue_id);
            //redirect(base_url()."issues/detail/".$repo_slug."/".$issue_id);
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/internal_authentication/login/');
        }
    }

    /*
    public function ajax_get_milestone_name($milestone_id=null){
        if(isset($milestone_id)){
            $this->load->model('Milestone_model');
            $milestone = $this->Milestone_model->retrieve_milestone_by_id($milestone_id);
            if(isset($milestone)){
                return $milestone["header"];
            }
        }
        return "null";
    }
    */
    public function ajax_verify_account_name($repo_slug=null){
        if(isset($milestone_id)){
            $this->load->library('BB_issues');
            $issues = $this->Milestone_model->retrieve_milestone_by_id($milestone_id);
            if(isset($milestone)){
                return $milestone["header"];
            }
        }
        return "false";
    }

}