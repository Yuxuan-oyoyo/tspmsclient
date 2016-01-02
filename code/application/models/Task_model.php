<?php

class Task_model extends CI_Model{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }
    public function retrieve_by_id($task_id){
        if(isset($task_id)){
            $query = $this->db->get_where("task",["task_id"=>$task_id]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }

    public function retrieve_all_by_project_id($project_id){
        if(isset($project_id)){
            $query = $this->db->query("select * from project p,task t where p.project_id=t.project_id and p.project_id=?",[$project_id]);
            return $query->result_array();
        }
        return null;
    }

    public function retrieve_all_uncompleted_by_project_id($project_id){
        if(isset($project_id)){
            $query = $this->db->query("select * from project p,task t where p.project_id=t.project_id and t.if_completed=0 and p.project_id=?",[$project_id]);
            return $query->result_array();
        }
        return null;
    }

    public function retrieve_all_completed_by_project_id($project_id){
        if(isset($project_id)){
            $query = $this->db->query("select * from project p,task t where p.project_id=t.project_id and t.if_completed=1 and p.project_id=?",[$project_id]);
            return $query->result_array();
        }
        return null;
    }

    public function insert($insert_array){
        $insert_array['if_completed'] = 0;
        return $this->db->insert('task', $insert_array);
    }

    public function update($update_array){
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $update_array['last_updated'] = $date->format('c');
        $query = $this->db->update('task', $update_array, array('task_id' => $update_array['task_id']));
        //print_r($query);
        return $this->db->affected_rows();
    }

    public function start($task_id){
        $update_array = $this->retrieve_by_id($task_id);
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $update_array['last_updated'] = $date->format('c');
        $update_array['start_datetime'] = $date->format('c');
        $query = $this->db->update('task', $update_array, array('task_id' => $update_array['task_id']));
        return $this->db->affected_rows();
    }

    public function complete($task_id){
        $update_array = $this->retrieve_by_id($task_id);
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $update_array['last_updated'] = $date->format('c');
        $update_array['end_datetime'] = $date->format('c');
        $update_array['if_completed'] = 1;
        $query = $this->db->update('task', $update_array, array('task_id' => $update_array['task_id']));
        return $this->db->affected_rows();
    }
}