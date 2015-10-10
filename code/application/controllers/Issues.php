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
    public function index() {
        $this->list_all();
    }
    public function list_all($repo_slug=null){
        $this->load->library('BB_issues');

        $opt_params = ["search","sort","limit","start"];
        $para_input = $this->input->get($opt_params,true);
        foreach($para_input as $key=>$value){
            if(!empty($value)){
                $para[$key] = $value;
            }
        }
        if(empty($repo_slug)){
            die("repo_slug is unset");
            //TODO:may need to implement global selection
        }else{
            $para['repo_slug'] = $repo_slug;
        }
        $repo_slug="tspms";
        //TODO:validate parameters
        $issues = $this->bb_issues->retrieveIssues($repo_slug, $para);
        echo var_dump($issues);

    }
    public function test(){
        $this->load->library("BB_shared");
        $this->load->library("BB_issues");
        echo var_dump($this->bb_issues->retrieveIssues('tspms'));
        //echo var_dump($this->bb_shared->getDefaultOauthToken());
    }
}