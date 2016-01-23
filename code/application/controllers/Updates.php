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
        $this->load->model("Internal_user_model");
        $this->load->model("Notification_model");
    }

    public function add_new_update($project_id,$current_project_phase_id){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $insert_post_array['header']=$this->input->post("update_header");
            $insert_post_array['body']=$this->input->post("update_body");
            $insert_post_array['project_phase_id']=$current_project_phase_id;
            $post_id = $this->Post_model->insert($insert_post_array,'update');
            $phases = $this->Project_phase_model->retrieve_by_project_id($project_id);

            //post_by will be changed to user name useing session data
            $insert_update_array['posted_by']=$this->session->userdata('internal_username');
            $insert_update_array['post_id'] =$post_id;
            if($this->Update_model->insert($insert_update_array)==1){
                $this->session->set_userdata('message', 'New update created successfully.');
                $change_type = "new update";
                $users = $this->Internal_user_model->retrieve_all_pm();
                $this->Notification_model->add_new_post_notifications($project_id,$post_id,$change_type,$users);
                redirect('projects/view_updates/'.$project_id);
            }else{
                $this->session->set_userdata('message', 'An error occurred, please contact administrator.');
                redirect('projects/view_updates/'.$project_id);
            }
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }

    public function get_update_by_project_phase($project_phase_id){
           $updates = $this->Update_model->retrieve_by_project_phase_id($project_phase_id);
           echo (json_encode($updates));
           return json_encode($updates);

    }

    public function delete_update($project_id,$update_id){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $u = $this->Update_model->retrieve_by_id($update_id);
            $post_id = $u['post_id'];
            if($this->Update_model->delete_($update_id)){
                $this->session->set_userdata('message', 'Update deleted successfully.');
                $change_type = "delete update";
                $users = $this->Internal_user_model->retrieve_all_pm();
                $this->Notification_model->add_new_post_notifications($project_id,$post_id,$change_type,$users);
                redirect('projects/view_updates/'.$project_id);
            }else{
                $this->session->set_userdata('message', 'An error occurred, please contact administrator.');
                redirect('projects/view_updates/'.$project_id);
            }
            redirect('projects/view_updates/'.$project_id);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }
}