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
        $this->load->helper("date");
        $this->load->model("Milestone_model");
        $this->load->model("Project_model");
        $this->load->model("Post_model");
        $this->load->model("Update_model");
        $this->load->model("Project_phase_model");
    }

    public function add_new_milestone($project_id,$current_project_phase_id){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $insert_post_array['header']=$this->input->post("header");
            $insert_post_array['body']=$this->input->post("body");
            $insert_post_array['project_phase_id']=$current_project_phase_id;
            $post_id = $this->Post_model->insert($insert_post_array,'milestone');
            $phases = $this->Project_phase_model->retrieve_by_project_id($project_id);

            $deadline=$this->input->post("deadlinePicker");
            $deadline = new DateTime($deadline);
            $insert_milestone_array['deadline'] = $deadline->format('c');
            $insert_milestone_array['post_id'] =$post_id;
            /*****02 Jan 2016, Lu Ning********/
            /*Get the milestone id for the inserted milestone*/
            $insert_id = $this->Milestone_model->insert($insert_milestone_array);
            if(isset($insert_id)){
                /*need bb repo slug for the current project*/
                $project = $this->Project_model->retrieve_by_id($project_id);
                /*post it to bb server*/
                $this->load->library("BB_Milestones");
                $this->bb_milestones->postMilestone($project["bitbucket_repo_name"],$insert_id);
                $this->session->set_userdata('message', 'New milestone created successfully.');
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

    public function all_milestone_in_current_phase($current_project_phase_id){
        $affected_rows = $this->Milestone_model->retrieve_by_project_phase_id($current_project_phase_id);
        return $affected_rows;
    }

    public function get_by_project_phase_id($project_phase_id){
        if(($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM")||$this->session->userdata('Customer_cid')) {
            $affected_rows = $this->Milestone_model->retrieve_by_project_phase_id($project_phase_id);
            echo json_encode($affected_rows);
        }else{
           echo('invalid access');
        }
    }
    public function completionConfirmation($project_id,$milestone_id){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $affected_rows1 = $this->Milestone_model->complete($milestone_id);
            $m = $this->Milestone_model->retrieve_by_id($milestone_id);
            $p = $this->Post_model->retrieve_by_id($m['post_id']);

            $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));

            $new_post_array['header']="Milestone Completion";
            $new_post_array['body']="Milestone '".$p['header']."' - ".$p['body']." has been completed.";
            $new_post_array['project_phase_id']=$p['project_phase_id'];
            $post_id = $this->Post_model->insert($new_post_array,'update');

            $new_update_array['posted_by']=$this->session->userdata('internal_username');
            $new_update_array['post_id'] =$post_id;
            if($this->Update_model->insert($new_update_array)==1){
                redirect('projects/view_updates/'.$project_id);
            }
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }

    public function delete_milestone($project_id,$milestone_id){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $m = $this->Milestone_model->retrieve_by_id($milestone_id);
            $post_id = $m['post_id'];
            $this->Milestone_model->delete_($milestone_id);
            if($this->Post_model->delete_($post_id)==null){
                $this->session->set_userdata('message', 'Milestone deleted successfully.');
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
}