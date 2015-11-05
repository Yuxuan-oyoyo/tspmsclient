<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/5/2015
 * Time: 11:57 AM
 */
class Issues extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('BB_issues');
    }
    public function list_all($repo_slug=null){
        if(isset($repo_slug)) {
            $data["repo_slug"] = $repo_slug;
            $opt_params = [
                "search","sort","limit","start","status","kind","responsible",
                "milestone","reported_by","priority","created_on","utc_last_updated"
            ];
            $para_input = $this->input->get($opt_params,true);
            if($para_input["sort"]=="responsible")$para_input["sort"] = null;
            $para =[];
            /*transfer $para_input to $para. Only keep non-null key value pairs.*/
            /*this prevents bad request*/
            foreach($para_input as $key=>$value){
                if(!empty($value)){
                    if($key=="search") $value = $value['value'];
                    $para[$key] = $value;
                }
            }
            //TODO:validate parameters
            $issues_response = $this->bb_issues->retrieveIssues($repo_slug,null, $para);
            $data= ["issues_response"=>$issues_response,"repo_slug"=>$repo_slug,"filter_arr"=>$para];
            $this->session->set_userdata('issue_list'.$repo_slug, $issues_response["issues"]);
            $this->load->view("issue/all_2", $data);
        }else{
            //TODO: take user to 404 page
        }
    }
    public function create($repo_slug){
        $this->load->view("issue/new", ["repo_slug"=>$repo_slug]);
    }
    public function process_create($repo_slug){
        $field_params = ["status","priority","title","responsible","content","kind","milestone"];
        $para_input = $this->input->get($field_params,true);
        $param=[];
        foreach($para_input as $key=>$value){
            if(!empty($value)){
                $param[$key] = $value;
            }
        }
        $issue = $this->bb_issues->postNewIssue($repo_slug, $param);
        $this->session->set_flashdata("issue_last_updated",$issue);
        /*brings user back to this issue*/
        redirect(base_url()."Issues/detail/".$repo_slug."/".$issue["local_id"]);
    }
    public function detail($repo_slug=null, $issue_id=null) {
        if (isset($repo_slug) && isset($issue_id)) {
            $data =["issue_details"=>$this->retrie_by_id($repo_slug,$issue_id), "repo_slug"=>$repo_slug];
            $this->load->view("issue/view", $data);
        }else{
            //TODO: take user to 404 page
            die("repo slug is not set");
        }
    }
    private function retrie_by_id($repo_slug=null, $id=null){
        //$issue_list = $this->session->userdata("issue_list" . $repo_slug);
        $issue_details = $this->session->flashdata("issue_last_updated");
//        if (isset($issue_list)){
//            /*if issue list is in session, find the one with this local id*/
//            foreach($issue_list as $issue){
//                if($issue["local_id"]==$id) {$issue_details= $issue;break;}
//            }
//        }
        if(!isset($issue_details)){/*if issue list is not in session, or cannot find*/
            $issue_details = $this->bb_issues->retrieveIssues($repo_slug, $id);
        }
        return $issue_details;
    }


    /**
     * Processes Ajax request. Updates a single field
     * @param null $repo_slug
     */
    public function update($repo_slug=null,$issue_id){
        $param = $this->input->get("param",true);
        $value = $this->input->get("value",true);
        echo $param;
        echo $value;
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
    }
    public function edit($repo_slug=null, $issue_id){
        //$issue_id = $this->input->get("local_id");
        if (isset($repo_slug)&&isset($issue_id)) {
            $data =["issue_details"=>$this->retrie_by_id($repo_slug,$issue_id), "repo_slug"=>$repo_slug];
            $this->load->view("issue/edit", $data);
        }else{
            //TODO: take user to 404 page
            die("repo slug/issue id is not set");
        }


    }
    public function process_edit($repo_slug=null, $issue_id){
        $field_params = ["status","priority","title","responsible","content","kind","milestone"];
        $para_input = $this->input->get($field_params,true);
        $param=[];
        foreach($para_input as $key=>$value){
            if(!empty($value)){
                $param[$key] = $value;
            }
        }
        $issue = $this->bb_issues->updateIssue($repo_slug,$issue_id, $param);
        $this->session->set_flashdata("issue_last_updated",$issue);
        /*brings user back to this issue*/
        redirect(base_url()."Issues/detail/".$repo_slug."/".$issue_id);
    }

}