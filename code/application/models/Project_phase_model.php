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

    public function retrieve_phase_by_id($current_project_phase_id){
        $query = $this->db->query("select phase_id from project_phase where project_phase_id=?",[$current_project_phase_id]);
        return $query->result_array();
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
        $date = date('Y-m-d H:i:s');
        $update_array['last_updated'] = $date;
        $update_array['end_time'] = $date;
        $this->db->update('project_phase', $update_array, array('project_phase_id' => $update_array['current_project_phase_id']));
        $next_project_phase_id =$update_array['current_project_phase_id']+1;
        $start_next_phase_array['start_time'] = $date;
        $start_next_phase_array['last_update'] = $date;
        $this->db->update('project_phase', $start_next_phase_array, array('project_phase_id' => $update_array['next_project_phase_id']));
    }

    public function insert($insert_array){
        $date = date('Y-m-d H:i:s');
        $insert_array['last_updated'] = $date;
        $this->db->insert('project_phase', $insert_array);
        if($insert_array['phase_id'] = 1){
            $prohject_phase_id = $this->db->insert_id();
            $start_first = array(
                "start_time" => $date,
                "prohject_phase_id" =>$prohject_phase_id
            );
            $this->update($start_first);
        }
    }
    public function retrievePhaseDef(){
        $query = $this->db->query("select phase_name from phase order by phase_id");
        return $query->result_array();
    }
}