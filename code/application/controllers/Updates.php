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
    }

    public function add_new_update($project_id,$current_project_phase_id,$current_phase){
        $insert_post_array['header']=$this->input->post("update_header");
        $insert_post_array['body']=$this->input->post("update_body");
        $insert_post_array['project_phase_id']=$current_project_phase_id;
        $post_id = $this->Post_model->insert($insert_post_array,'update');

        //post_by will be changed to user name useing session data
        $insert_update_array['posted_by']='TT';
        $insert_update_array['post_id'] =$post_id;
        $this->Update_model->insert($insert_update_array);
        $this->load->view('project/project_update',$data=["project"=>$this->Project_model->retrieve_by_id($project_id),"current_phase"=>$current_phase,"current_project_phase_id"=>$current_project_phase_id]);
    }

}