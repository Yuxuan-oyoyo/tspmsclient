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

        $data["repo_slug"] = $repo_slug;
        $this->load->view("issue/all_2",$data);
        //echo var_dump($issues);

    }
    public function list_all_json($repo_slug=null){
        $this->load->library('BB_issues');

        $opt_params = ["search","sort","limit","start"];
        $para_input = $this->input->get($opt_params,true);
        foreach($para_input as $key=>$value){
            if(!empty($value)){
                if($key=="search") $value = $value['value'];
                $para[$key] = $value;
            }
        }
        //echo var_dump($para);
        if(empty($repo_slug)){
            die("repo_slug is unset");
            //TODO:may need to implement global selection
        }else{
            $para['repo_slug'] = $repo_slug;
        }
        $repo_slug="tspms";
        //TODO:validate parameters
        /*
         *                     <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Milestone</th>
                    <th>Reported by</th>
                    <th>utc_last_updated</th>
                    <th>Responsible</th>
         */
        $issues = $this->bb_issues->retrieveIssues($repo_slug, $para);
        $reformatted = ['data'=>[]];
        foreach($issues['issues'] as $i){
            $row = [$i['title']
                ,$i['status']
                ,$i['priority']
                ,$i['metadata']['milestone']
                ,$i['reported_by']['display_name']
                ,$i['utc_last_updated']
                ,$i['responsible']['display_name']];
            array_push($reformatted["data"], $row);
        }
        foreach($reformatted['data'] as $k=>$v){
            if(is_null($v)) $reformatted['data'][$k]="uuuu";
        }
        echo json_encode($reformatted,true);
    }
    public function test(){
        $this->load->library("BB_shared");
        $this->load->library("BB_issues");
        echo var_dump($this->bb_issues->retrieveIssues('tspms'));
        //echo var_dump($this->bb_shared->getDefaultOauthToken());
    }
}