<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 11/6/2015
 * Time: 12:18 AM
 */
class Chat {
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }
    public function retrieve($input_c_id){
        $sql ="select chat_threads.chat_id
            as chatid, user1, user2, seen, lastMsgTimeStamp,
            lastMessage, msg_id, author_id, content, timeStamp
            from chat_messages right join chat_threads
            on chat_messages.chat_id = chat_threads.chat_id
            where user1 = 1 or user2 = 1";

        if(isset($input_c_id)){
            $query = $this->db->get_where("customer",["c_id"=>$input_c_id]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }
    public function write($input_c_id){
        if(isset($input_c_id)){
            $query = $this->db->get_where("customer",["c_id"=>$input_c_id]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }
}