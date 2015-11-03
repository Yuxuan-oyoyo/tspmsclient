<?php
/**
 * Created by PhpStorm.
 * User: WANG Tiantong
 * Date: 10/6/2015
 * Time: 5:52 PM
 */

//namespace model;


class Milestone_model extends CI_Model{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }
    public function retrieve_by_id($milestone_id){
        if(isset($milestone_id)){
            $query = $this->db->get_where("milestone",["milestone_id"=>$milestone_id]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }
    /*
    public function retrieve_by_project($project_id){

    }
    */
    public function retrieve_by_project_phase_id($project_phase_id){
        if(isset($project_phase_id)){
            $query = $this->db->query("select * from post p,milestone m where p.post_id=m.post_id and p.project_phase_id=?",[$project_phase_id]);
            return $query->result_array();
        }
        return null;
    }
    public function insert($insert_array){
        $insert_array['if_completed'] = 0;
        return $this->db->insert('milestone', $insert_array);
    }
    public function complete($milestone_id){
        $update_array = $this->retrieve_by_id($milestone_id);
        $update_array['if_completed'] = 1;
        return $this->db->update('milestone', $update_array, array('milestone_id' => $update_array['milestone_id']));
    }
    public function delete_($milestone_id){
        if(isset($milestone_id)){
            $this->db->delete('milestone', array('milestone_id' => $milestone_id));
        }
        return null;
    }
    /*
    public function mark_miss($milestone_id){
        $update_array = $this->retrieve_by_milestone_id($milestone_id);
        $update_array['if_missed'] = 1;
        return $this->db->update('milestone', $update_array, array('milestone_id' => $update_array['milestone_id']));
    }
    */

}