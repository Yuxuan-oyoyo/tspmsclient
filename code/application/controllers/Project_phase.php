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
    public function create_phases_upon_new_project($project_id){
        for ($p = 1; $p <= 5; $p++) {
            $new_project_phase["project_id"]=$project_id;
            $new_project_phase["phase_id"]=$p;
            $affected_rows = $this->Project_phase_model->insert($new_project_phase);
            echo $affected_rows;
        }
    }

    public function update_phase($data){
        $project_id = $data['project_id'];
        $current_project_phase_id = $data['current_project_phase_id'];
        $update_array['estimated_end_time']=$this->input->get("estimated_end_time");
        $update_array['current_project_phase_id'] = $current_project_phase_id;
        $this->Project_phase_model->update($update_array);
        $this->Project_model->update_current_project_phase();
    }
}