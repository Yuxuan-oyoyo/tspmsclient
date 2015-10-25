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
            $this->load->view("issue/all_2", $data);
        }else{
            //TODO: take user to 404 page
        }
    }
    private function getParamStr($value_arr,$name){
        $str = "";
        foreach($value_arr as $value){
            $str .= $name."=".$value."&";
        }
        return rtrim($str, "&");
    }
    public function list_all_json($repo_slug=null){
        $this->load->library('BB_issues');
        $opt_params = ["search","sort","limit","start"];
        $para_input = $this->input->get($opt_params,true);
        $status_filter = $this->input->get("status");
        foreach($para_input as $key=>$value){
            if(!empty($value)){
                if($key=="search") $value = $value['value'];
                $para[$key] = $value;
            }
        }
        if(empty($repo_slug)){
            die("repo_slug is unset");
            //TODO:may need to implement global selection
        }else{
            $para['repo_slug'] = $repo_slug;
        }
        //TODO:validate parameters
        $issues_response = $this->bb_issues->retrieveIssues($repo_slug, $para);

        $data= ["issues_response"=>$issues_response,"repo_slug"=>$repo_slug,"filter_str"=>$this->getParamStr($status_filter, "status")];
        $this->load->view("issue/issue_body",$data);
    }
    public function test(){
        $this->load->library("BB_shared");
        $this->load->library("BB_issues");
        echo var_dump($this->bb_issues->retrieveIssues('tspms'));
    }
    public function retrieve_by_id($id=null){

    }
}