<?php

/**
 * Created by PhpStorm.
 * User: WANG Tiantong
 * Date: 10/7/2015
 * Time: 2:02 PM
 */
class Post_model extends CI_Model{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }

    public function insert($insert_array,$type){
        $date = date('Y-m-d H:i:s');
        $insert_array['last_updated'] = $date;
        $insert_array['datetime_created'] = $date;
        $insert_array['type'] = $type;
        $this->db->insert('post', $insert_array);
        $post_id = $this->db->insert_id();
        return $post_id;
    }

    public function retrieveAll(){
        $query = $this->db->query("select * from post");
        return $query->result_array();
    }

    public function update($update_array){
        $date = date('Y-m-d H:i:s');
        $update_array['last_updated'] = $date;
        $this->db->update('post', $update_array, array('post_id' => $update_array['post_id']));
        return $this->db->affected_rows();
    }
}