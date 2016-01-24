<?php

/**
 * Created by PhpStorm.
 * User: WANG Tiantong
 * Date: 1/14/2016
 * Time: 5:03 PM
 */
class Notification_model extends CI_Model{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }

    public function retrieve_by_id($notification_id){
        if(isset($notification_id)){
            $query = $this->db->get_where("notification",["notification_id"=>$notification_id]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }

    public function retrieve_unread_notification($user_id){
        if(isset($user_id)){
            $query = $this->db->query(
                "SELECT * FROM notification where if_read = 0 AND user_id=?",
                [$user_id]
            );
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }

    public function mark_as_read($notification_id){
        if(isset($notification_id)){
            $update_array = $this->retrieve_by_id($notification_id);
            $update_array['if_read'] = 1;
            return $this->db->update('notification', $update_array, array('notification_id' => $update_array['notification_id']));
        }
    }

    public function add_new_project_notifications($project_id,$change_type,$users){
        $insert_array['change_type']=$change_type;
        $insert_array['project_id']=$project_id;
        $insert_array['if_read'] = 0;

        foreach($users as $u_id){
            $insert_array['user_id']=$u_id['u_id'];
            $this->db->insert('notification', $insert_array);
        }
        return $this->db->insert_id();
    }

    public function add_new_post_notifications($project_id,$post_id,$change_type,$users){
        $insert_array['change_type']=$change_type;
        $insert_array['post_id']=intval($post_id);
        $insert_array['project_id']=intval($project_id);
        $insert_array['if_read'] = 0;

        foreach($users as $u_id){
            $insert_array['user_id']=$u_id['u_id'];
            var_dump($insert_array);
            $this->db->insert('notification', $insert_array);
        }
        return $this->db->insert_id();
    }

}