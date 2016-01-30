<?php

/**
 * Interface for scheduler
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
        $this->load->model('Internal_user_model');
        $this->session->userdata('internal_uid',1);
    }
    /* Calibrates bb milestones with database
     * prints {"added":num_added,"removed":num_removed,"time":time_taken}
     */
    function calibrate_bb_milestones(){
        $authenticated = false;
        $data = [];
        if($this->session->userdata('internal_uid')
                &&$this->session->userdata('internal_type')=="PM"){
            $authenticated = true;
        }else{
            $all_pm_records = $this->Internal_user_model->retrieve_all_pm();
            if(isset($all_pm_records)&&isset($all_pm_records[0])&&!empty($all_pm_records[0]["bb_username"])){
                $pm_id = $all_pm_records[0]["u_id"];
                $this->session->set_userdata('internal_uid',$pm_id);
                $authenticated = true;
            }else{
                die("No project manager found");
            }
        }
        if($authenticated){
            $this->load->library("BB_scheduled_tasks");
            $result = $this->bb_scheduled_tasks->calibrate_bb_milestones();
            $data["result"] = $result;
        }else{
            $data["error"] = "Authentication error";
            $this->output->set_status_header('401');
        }
        echo json_encode($data);

    }

    /**
     * This method can be called by scheduler and project list page(through ajax call)
     */
    public function fetch_issue_counts(){
        $this->load->library("BB_scheduled_tasks");
        $result = $this->bb_scheduled_tasks->fetch_issue_counts();
        echo json_encode($result);
    }

}