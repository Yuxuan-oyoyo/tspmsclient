<?php

/**
 * Created by PhpStorm.
 * User: WANG Tiantong
 * Date: 10/7/2015
 * Time: 2:53 PM
 */
class Updates extends CI_Controller{
    public function __construct(){
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("Update_model");
        $this->load->model("Project_model");
        $this->load->model("Post_model");
        $this->load->model("Milestone_model");
        $this->load->model("Project_phase_model");
    }

    public function add_new_update($project_id,$current_project_phase_id){
        $insert_post_array['header']=$this->input->post("update_header");
        $insert_post_array['body']=$this->input->post("update_body");
        $insert_post_array['project_phase_id']=$current_project_phase_id;
        $post_id = $this->Post_model->insert($insert_post_array,'update');
        $phases = $this->Project_phase_model->retrieve_by_project_id($project_id);

        //post_by will be changed to user name useing session data
        $insert_update_array['posted_by']=$this->session->userdata('internal_username');
        $insert_update_array['post_id'] =$post_id;
        if($this->Update_model->insert($insert_update_array)==1){
            redirect('projects/view_updates/'.$project_id);
        }


    }

    public function get_update_by_project_phase($project_phase_id){
       $updates = $this->Update_model->retrieve_by_project_phase_id($project_phase_id);
       echo (json_encode($updates));
       return json_encode($updates);

    }
}