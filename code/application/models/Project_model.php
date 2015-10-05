<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Project_model
 *
 * @author WANG Tiantong
 */
class Project_model extends CI_Model {
    //put your code here
    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }
    public function retrieve_by_id($input_p_id){
        if(isset($input_p_id)){
            $query = $this->db->get_where("project",["project_id"=>$input_p_id]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }
    public function retrieve_by_title($input_p_title){
        if(isset($input_p_title)){
            $query = $this->db->get_where("project",["project_title"=>$input_p_title]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }
    public function retrieveAll($only_active=true,$limit=0,$offset=0){
        $where = [];
        if(isset($input_p_id)){
            if($only_active){
                $where["is_active"]=1;
            }
        }
        $query = $this->db->get_where("project",$where,$limit,$offset);
        return $query->result_array();
    }

    public function update($update_array){
        $this->load->helper('date');
        $date = date('Y-m-d H:i:s'); 
        $this->db->set('last_updated', $date);
        $this->db->update('project', $update_array, array('project_id' => $update_array['project_id']));
        return $this->db->affected_rows();
    }
    public function insert($insert_array){
        //$this->load->helper('date');
        //$date = date('Y-m-d H:i:s'); 
        //$this->db->set('last_updated', $date);
        $this->db->set('last_updated', mdate());
        return $this->db->insert('project', $insert_array);
    }
    //not in use
    public function deactivate($input_p_id){
        $this->db->update('project', ["is_active"=>0], array('p_id' => $input_p_id));
        return $this->db->affected_rows();
    }
    /*
    private function field_check($project_array){
        $fields=['c_id','title','first_name','last_name','company_name','password_hash'
            ,'hp_number','email','is_active'];
        foreach( $fields as $field){
            if(!array_key_exists($field,$project_array)){
                return false;
            }
        }
        return true;
    }*/
}
