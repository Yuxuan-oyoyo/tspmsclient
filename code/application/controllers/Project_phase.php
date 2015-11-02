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
        $this->load->model("Phase_model");
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
        if(!$current_project_phase_id==0) {
            $update_array = $this->Project_phase_model->retrieve_by_id($current_project_phase_id);
            $this->Project_phase_model->update($update_array);

            $current_phase_id = $update_array['phase_id'];
            $next_phase_id = $current_phase_id + 1;
            $insert_array['project_id'] = $project_id;
            $insert_array['phase_id'] = $next_phase_id;
            $insert_array['end_time'] = null;

            $estimated_end_time = $this->input->post("estimated_end_time");
            $estimated_end_time = new DateTime($estimated_end_time);
            $insert_array['estimated_end_time'] = $estimated_end_time->format('c');

            $next_project_phase_id = $this->Project_phase_model->insert($insert_array);
            $update_array_project = $this->Project_model->retrieve_by_id($project_id);
            $update_array_project['current_project_phase_id'] = $next_project_phase_id;

            if($this->Project_model->update($update_array_project)==1){
                redirect('projects/view_updates/'.$project_id);
            }

        }else{
            $insert_array['project_id'] = $project_id;
            $insert_array['phase_id'] = 1;
            $insert_array['end_time'] = null;
            $insert_array['estimated_end_time'] = $this->input->post("estimated_end_time");
            $next_project_phase_id = $this->Project_phase_model->insert($insert_array);
            $update_array_project = $this->Project_model->retrieve_by_id($project_id);
            $update_array_project['current_project_phase_id'] = $next_project_phase_id;
            if($this->Project_model->update($update_array_project)==1){
                redirect('projects/view_updates/'.$project_id);
            }
         }
    }

    public function end_project($project_id,$current_project_phase_id){
            $update_array = $this->Project_phase_model->retrieve_by_id($current_project_phase_id);
            $this->Project_phase_model->update($update_array);

            $update_array_project = $this->Project_model->retrieve_by_id($project_id);
            $update_array_project['current_project_phase_id'] = -1;
            $update_array_project['is_ongoing'] = 0;

            if($this->Project_model->update($update_array_project)==1){
                redirect('projects/list_past_projects');
            }
    }


}