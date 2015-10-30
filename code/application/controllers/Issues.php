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
    }
    public function list_all($repo_slug=null){
        if(isset($repo_slug)) {
            $data["repo_slug"] = $repo_slug;
            $this->load->library('BB_issues');
            $opt_params = ["search","sort","limit","start"];
            $para_input = $this->input->get($opt_params,true);
            //Status should be array. if status not set, return an empty array for later processing
            $status_filter = ($this->input->get("status")!=null)? $this->input->get("status"):[];
            $para =[];
            //transfer $para_input to $para. Only keep non-null key value pairs
            foreach($para_input as $key=>$value){
                if(!empty($value)){
                    //what does this mean?
                    if($key=="search") $value = $value['value'];
                    $para[$key] = $value;
                }
            }
            //$para['repo_slug'] = $repo_slug;
            //TODO:validate parameters
            $issues_response = $this->bb_issues->retrieveIssues($repo_slug, $para, time());
            $data= ["issues_response"=>$issues_response,"repo_slug"=>$repo_slug,"filter_str"=>$this->getParamStr($status_filter, "status")];
            $this->session->set_userdata('issue_list'.$repo_slug, $issues_response["issues"]);
            //$this->load->view("issue/all_2", $data);
        }else{
            //TODO: take user to 404 page
        }
    }
    private function getParamStr($value_arr,$name){
        $str = "";
        if(is_array($value_arr)) {
            foreach ($value_arr as $value) {
                $str .= $name . "=" . $value . "&";
            }
        }else{
            $str .= $name . "=" . $value_arr . "&";
        }
        return rtrim($str, "&");
    }
    public function test(){
    }
    public function retrieve_by_id($repo_slug=null) {
        $issue_id = $this->input->get("local_id");
        //echo var_dump($issue_id);
        if (isset($repo_slug)) {
            $issue_list = $this->session->userdata("issue_list" . $repo_slug);
            if (isset($issue_list)){
                /*if issue list is in session, find the one with this local id*/
                foreach($issue_list as $issue){
                    if($issue["local_id"]==$issue_id) {$issue_details= $issue;break;}
                }
            }
            if(!isset($issue_details)){/*if issue list is not in session, or cannot find*/
                $issue_details = $this->bb_issues->retrieveIssues($repo_slug, $issue_id);
            }
            $data =["issue_details"=>$issue_details, "repo_slug"=>$repo_slug];
            $this->load->view("issue/view", $data);
        }else{
            //TODO: take user to 404 page
            die("repo slug is not set");
        }
    }

    /**
     * Processes Ajax request
     * @param null $repo_slug
     */
    public function update($repo_slug=null){
        $issue_id = $this->input->get("issue_id",true);
        $param = $this->input->get("param",true);
        $value = $this->input->get("value",true);
        if(($issue_list =$this->session->userdata("issue_list" . $repo_slug))!=null){
            foreach($issue_list as $issue){
                if($issue["local_id"]==$issue_id) {
                    $issue[$param] = $value;
                    break;
                }
            }
        }
        $this->bb_issues->updateIssue($repo_slug, [$param=>$value]);
    }
    public function process_edit($repo_slug=null){
        $field_params = ["status","priority","title","responsible","content","kind","milestone"];
        $para_input = $this->input->get($field_params,true);
        //only keep pairs with set value
        foreach($para_input as $key=>$value){
            if(!empty($value)){
                //what does this mean?
                //if($key=="search") $value = $value['value'];
                $para[$key] = $value;
            }
        }


    }
}