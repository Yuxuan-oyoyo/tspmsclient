<?php

/**
 * Created by PhpStorm.
 * User: WANG Tiantong
 * Date: 10/7/2015
 * Time: 1:45 PM
 */
class Milestones extends CI_Controller{

    public function __construct(){
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("Milestone_model");
        $this->load->model("Project_model");
        $this->load->model("Post_model");
        $this->load->model("Update_model");
    }

    public function add_new_milestone($project_id,$current_project_phase_id,$current_phase){
        $insert_post_array['header']=$this->input->post("header");
        $insert_post_array['body']=$this->input->post("body");
        $insert_post_array['project_phase_id']=$current_project_phase_id;
        $post_id = $this->Post_model->insert($insert_post_array,'milestone');

        $insert_milestone_array['deadline']=$this->input->post("deadline");
        $insert_milestone_array['post_id'] =$post_id;
        $affected_rows2 = $this->Milestone_model->insert($insert_milestone_array);

        //milestones
        $milestones = $this->Milestone_model-> retrieve_by_project_phase_id($current_project_phase_id);

        //updates
        $updates = $this->Update_model-> retrieve_by_project_phase_id($current_project_phase_id);

        $data = [
            "project"=>$this->Project_model->retrieve_by_id($project_id),
            "current_phase"=>$current_phase,
            "current_project_phase_id"=>$current_project_phase_id,
            "milestones"=>$milestones,
            "updates"=>$updates
        ];

        $this->load->view('project/project_update',$data);
    }

    public function all_milestone_in_current_phase($current_project_phase_id){
        $affected_rows = $this->Milestone_model->retrieve_by_project_phase_id($current_project_phase_id);
        return $affected_rows;
    }
}