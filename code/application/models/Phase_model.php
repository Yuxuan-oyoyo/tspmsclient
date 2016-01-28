<?php

/**
 * Created by PhpStorm.
 * User: WANG Tiantong
 * Date: 10/11/2015
 * Time: 8:54 PM
 */
class Phase_model extends CI_Model{

    public function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }

    public function retrieve_phase_by_id($phase_id){
        if(isset($phase_id)){
            $query = $this->db->get_where("phase",["phase_id"=>$phase_id]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }
    public function retrieve_all_phases(){
        $query = $this->db->query("SELECT phase_name from phase");
        return $query->result_array();
    }
}