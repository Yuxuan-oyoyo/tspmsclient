<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 1/26/2016
 * Time: 1:19 AM
 */
class Ajax_validate extends CI_Controller {
    public function __construct(){
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->model('Customer_model');
    }
    public function bb_repo_name_ajax(){
        $repo_name =  $this->input->get("repo_name");
        echo $this->bb_repo_name($repo_name)? "true":"false";
    }

    public function bb_repo_name($repo_name){
        $this->load->model('Project_model');
        $project_record = $this->Project_model->retrieve_by_repo_slug($repo_name);
        if(!isset($project_record)){
            $this->load->library("BB_shared");
            return $this->bb_shared->validate_repo_name_with_bb($repo_name);
        }
        return false;
    }
}