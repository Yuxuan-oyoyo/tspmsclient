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
    public function list_all(){
        $this->load->library('BB_issues');
        $search = $this->input->get("search");
        $sort   = $this->input->get("sort");
        $limit  = $this->input->get("limit");
        $start  = $this->input->get("start");
        $repo_slug = $this->input->get("repo_slug");
        $repo_slug="tspms";
        //TODO:add filters
        $para['search'] = isset($search)? $search:null;
        $para['sort'] = isset($sort)? $sort:null;
        $para['limit'] = isset($limit)? $limit:null;
        $para['start'] = isset($start)? $start:null;

        $issues = $this->bb_issues->retrieveIssues($repo_slug, $para);
        echo var_dump($issues);

    }
}