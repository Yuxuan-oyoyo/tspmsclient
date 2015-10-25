<?php

/**
 * Created by PhpStorm.
 * User: WANG Tiantong
 * Date: 10/6/2015
 * Time: 10:23 PM
 */
class Project_phase extends CI_Controller{

    public function __construct(){
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("Project_phase_model");
        $this->load->model("Project_model");
    }
/*
    public function index(){
        $this->list_all();
    }

    public function list_all(){
        $project_phases = $this->Project_phase_model->retrieveAll();
        $data["show_all"] = true;
        $data["project_phases"]= $project_phases;

        $this->load->view('customer/customer_all',$data);
    }
*/

    public function update_phase($project_id,$current_project_phase_id){
        //echo $project_id;
        //echo $current_project_phase_id;
        if(!$current_project_phase_id==0) {
            $update_array = $this->Project_phase_model->retrieve__by_id($current_project_phase_id);
            $current_phase_id = $update_array['phase_id'];
            $next_phase = $current_phase_id + 1;
            $update_array['estimated_end_time'] = $this->input->post("estimated_end_time");
            $this->Project_phase_model->update($update_array);

            $update_array_project = $this->Project_model->retrieve_by_id($project_id);
            $next_project_phase_id = $current_project_phase_id + 1;
            $update_array_project['current_project_phase_id'] = $next_project_phase_id;
            $this->Project_model->update($update_array_project);
            //$this->load->view('project/project_update', $data = ["project" => $this->Project_model->retrieve_by_id($project_id), "current_phase" => $next_phase, "current_project_phase_id" => $next_project_phase_id]);
        }else{
            $this->Project_phase_model->insert($project_id);
           // $this->load->view('project/project_update', $data = ["project" => $this->Project_model->retrieve_by_id($project_id), "current_phase" => $next_phase, "current_project_phase_id" => $next_project_phase_id]);
        }
    }
}