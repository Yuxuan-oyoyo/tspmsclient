<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 1/30/2016
 * Time: 8:03 PM
 */
class Oauth_token_model extends CI_Model{
    public function update ($user_id, $token, $ttl){

        $sql = "INSERT INTO oauth_token (user_id, token, ttl) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, ttl = ?";
        $this->db->query($sql, [$user_id, $token, $ttl, $token, $ttl]);
        //var_dump($this->db->error());

    }
    public function retrieve($user_id){
        $result = $this->db->get_where("oauth_token",["user_id"=>$user_id]);
        //var_dump($this->db->error());
        if($result->num_rows()>0){
            return $result->row_array();
        }else{
            return null;
        }

    }

}