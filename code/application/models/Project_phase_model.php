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
/*
    public function retrieve_phase_by_id($current_project_phase_id){
        $query = $this->db->query("select phase_id from project_phase where project_phase_id=?",[$current_project_phase_id]);
        return $query->result_array();
    }
*/
    public function retrieve_by_id($current_project_phase_id){
        if(isset($current_project_phase_id)){
            $query = $this->db->get_where("project_phase",["project_phase_id"=>$current_project_phase_id]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }

    public function retrieve_by_project_id($project_id){
        $sql = 'SELECT * from phase left join project_phase on project_phase.phase_id = phase.phase_id and project_phase.project_id=? order by phase.phase_id ';
        $query=$this->db->query($sql,array($project_id));
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


    public function retrieve_last_project_phase_id(){
        $sql = 'select project_phase_id from project_phase order by project_phase_id desc LIMIT 1';
        $query=$this->db->query($sql);
        return $query->result_array();
    }

    public function update($update_array){
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $update_array['last_updated'] = $date->format('c');
        $update_array['end_time'] = $date->format('c');
        $affected_rows1 = $this->db->update('project_phase', $update_array, array('project_phase_id' => $update_array['project_phase_id']));

        $next_project_phase_id =$this->retrieve_last_project_phase_id()+1;
        $start_next_phase_array = $this->retrieve__by_id($next_project_phase_id);
        $start_next_phase_array['start_time'] = $date->format('c');
        $start_next_phase_array['last_updated'] = $date->format('c');
        $start_next_phase_array['estimated_end_time'] = $update_array['estimated_end_time'];
        $affected_rows2 = $this->db->update('project_phase', $start_next_phase_array, array('project_phase_id' => $next_project_phase_id));
        echo $affected_rows1;
        echo $affected_rows2;
    }


    public function create_phase_upon_new_project($project_id){
        $new_project_phase["project_id"]=$project_id;
        $new_project_phase["phase_id"]=0;
        $new_project_phase["end_time"]=null;
        $new_project_phase["estimated_end_time"]=null;
        $current_project_phase_id = $this->insert($new_project_phase);
        return $current_project_phase_id;
    }

    public function insert($insert_array){
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $insert_array['last_updated'] = $date->format('c');
        $insert_array['start_time'] = $date->format('c');
        $this->db->insert('project_phase',$insert_array);
        return $this->db->insert_id();
    }
    public function retrievePhaseDef(){
        $query = $this->db->query("select phase_name from phase order by phase_id");
        return $query->result_array();
    }
}