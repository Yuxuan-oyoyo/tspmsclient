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
        $this->load->model("Post_model");
        $this->load->model("Project_model");
    }

    public function check_unread_notification($internal_uid){
        $notifications = $this->Notification_model->retrieve_unread_notification($internal_uid);
        //var_dump($notifications);
        if(sizeof($notifications)!==0){
            echo
            '<div class="notification-heading"><h4 class="menu-title">Notifications</h4>'
            .'</div>'
            .'<li class="divider"></li>'
            .'<div class="notifications-wrapper">';

            foreach($notifications as $n){
                $project_id = $n['project_id'];
                $p = $this->Project_model->retrieve_title($project_id);
                 echo
                '<a class="content" href="'
                .base_url()."Notifications/read_notification/".$n["notification_id"]
                .'">'
                .'<div class="notification-item-green">'
                .'<h4 class="item-info-large">'
                .$n['change_type']
                .'&nbsp <small>by&nbsp'
                .$n['created_by']
                .'</small>'
                .'</h4>'
                .'<p class="item-title">'
                .$p['project_title']
                .'&nbsp <small style="color:#808080;">'
                .$n['created_datetime']
                .'</small></p>'
                .'</div>'
                .'</a>';
            }
            echo
            '</div>'
            .'<li class="divider"></li>'
            .'<a onclick="clear_all_notification()"'
            .'>Clear All &nbsp'
            .'<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'
            .'</a>';
        }else{
            echo
            '<div class="notification-heading"><h4 class="menu-title">No new notifications</h4>';
        }

    }

    public function get_notification_number($internal_uid){
        $notifications = $this->Notification_model->retrieve_unread_notification($internal_uid);
        if(sizeof($notifications)!==0){
            echo sizeof($notifications);
        }else{
            return null;
        }
    }

    public function read_notification($n_id){
        $n = $this->Notification_model->retrieve_by_id($n_id);
        $project_id = $n['project_id'];
        $redirect = $n['redirect'];
        $change_type = $n['change_type'];
        if(strpos($change_type, 'Deleted') !== false){
            $post_id = $n['post_id'];
            $this->Post_model->delete_($post_id);
        }
        $this->Notification_model->delete_($n_id);
        redirect('projects/'.$redirect.'/'.$project_id);
    }

    public function clear_all_notification($u_id){
        $this->Notification_model->clear_all($u_id);
    }
}