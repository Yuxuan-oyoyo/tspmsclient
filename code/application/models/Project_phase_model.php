<?php

/**
 * Created by PhpStorm.
 * User: WANG Tiantong
 * Date: 10/3/2015
 * Time: 4:58 PM
 */
class Project_phase_model extends CI_Model {
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }

    public function retrieve_by_ids($input_project_id, $input_phase_id){
        if(isset($input_project_id)&&isset($input_phase_id)){
            $query = $this->db->get_where("project",["project_id"=>$input_project_id]&& array("phase_id"=>$input_phase_id));
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }

    public function retrieveAll($is_ongoing){
        //$is_ongoing: 0-closed projects, 1-ongoing projects, 2-all projects
        if($is_ongoing!=2) {
            $this->db->select('project_phase.*')
                ->from('project')
                ->join('project_phase', 'project_phase.project_id = project.project_id','inner')
                ->where(array('is_ongoing'=>$is_ongoing), NULL, FALSE);
            $result = $this->db->get();
        }else{
            $this->db->select('project_phase*')
                ->from('project')
                ->join('project_phase', 'project_phase.project_id = project.project_id','inner');
            $result = $this->db->get();
        }
        return $result;
    }

    public function update($update_array){
        $this->load->helper('date');
        $date = date('Y-m-d H:i:s');
        $this->db->set('last_updated', $date);
        $this->db->update('project_phase', $update_array, array('project_phase_id' => $update_array['project_phase_id']));
        return $this->db->affected_rows();
    }

    public function insert($insert_array){
        //$this->load->helper('date');
        //$date = date('Y-m-d H:i:s');
        //$this->db->set('last_updated', $date);
        $this->db->set('last_updated', mdate());
        return $this->db->insert('project_phase', $insert_array);
    }
}