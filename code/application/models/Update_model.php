<?php

/**
 * Created by PhpStorm.
 * User: WANG Tiantong
 * Date: 10/7/2015
 * Time: 3:01 PM
 */
class Update_model extends CI_Model{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }

    public function insert($insert_array){
        $insert_array['status']=0;
        return $this->db->insert('updates', $insert_array);
    }

    public function retrieve_by_project_phase_id($project_phase_id){
        if(isset($project_phase_id)){
            $query = $this->db->query("select * from post p,updates u where p.post_id=u.post_id and p.project_phase_id=? order by last_updated desc",[$project_phase_id]);
            return $query->result_array();
        }
        return null;
    }
    public function retrieve_by_id($update_id){
        if(isset($update_id)){
            $query = $this->db->get_where("updates",["update_id"=>$update_id]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }

    public function delete_($update_id){
        if(isset($update_id)){
            return $this->db->query("delete from updates where update_id = $update_id");
        }
        return false;
    }
}