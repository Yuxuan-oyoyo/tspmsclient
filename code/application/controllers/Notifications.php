<?php

/**
 * Created by PhpStorm.
 * User: WANG Tiantong
 * Date: 1/14/2016
 * Time: 5:03 PM
 */
class Notifications extends CI_Controller{
    public function __construct(){
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper("date");
        $this->load->model("Notification_model");
        $this->load->model("Internal_user_model");
    }

    public function check_unread_notification($internal_uid){
        return $this->Notification_model->retrieve_unread_notification($internal_uid);
    }

    public function add_new_post_notifications(){
        $users = $this->Internal_user_model->retrieve_all_pm();
        $this->Notification_model->add_new_post_notifications(84,"test",$users);
    }
/*
    public function add_new_project_notifications($project_id,$change_type){
        $insert_array['change_type']=$change_type;
        $insert_array['project_id']=$project_id;
        $user_ids = $this->Internal_user_model->retrieve_all_pm();

        foreach($user_ids as $u_id){
            $insert_array['user_id']=$u_id;
            if($this->Notification_model->insert($insert_array)==1){
                echo "successful!";
            }
        }
    }

    public function add_new_post_notifications($post_id,$change_type){
        $insert_array['change_type']=$change_type;
        $insert_array['post_id']=$post_id;
        $user_ids = $this->Internal_user_model->retrieve_all_pm();

        foreach($user_ids as $u_id){
            $insert_array['user_id']=$u_id;
            $this->Notification_model->insert($insert_array);
        }
    }

    public function mark_as_read($notification_id){
        if(isset($notification_id)){
            $update_array = $this->Notification_model->retrieve_by_id($notification_id);
            if($this->Notification_model->update($update_array)==1){
                $this->session->set_userdata('message', 'Task updated successfully.');
            }
            echo "successful";
        }
    }
*/

}