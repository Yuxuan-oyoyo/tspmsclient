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

    public function retrieve_by_ids($project_id, $phase_id){
        if(isset($project_id)&&isset($phase_id)){
            $query = $this->db->get_where("task",["project_id"=>$project_id]&& array("phase_id"=>$phase_id));
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }

    public function retrieve_all_by_project_id($project_id){
        if(isset($project_id)){
            $query = $this->db->query("select * from task t where t.project_id and t.project_id=?",[$project_id]);
            return $query->result_array();
        }
        return null;
    }

    public function retrieve_all_uncompleted_by_project_id($project_id){
        if(isset($project_id)){
            $query = $this->db->query("select * from task t where t.if_completed=0 and t.project_id=?",[$project_id]);
            return $query->result_array();
        }
        return null;
    }

    public function retrieve_all_completed_by_project_id($project_id){
        if(isset($project_id)){
            $query = $this->db->query("select * from task t where t.if_completed=1 and t.project_id=?",[$project_id]);
            return $query->result_array();
        }
        return null;
    }

    public function insert($insert_array){
        $insert_array['if_completed'] = 0;
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $insert_array['last_updated'] = $date->format('c');
        $insert_array['datetime_created'] = $date->format('c');
        return $this->db->insert('task', $insert_array);
    }

    public function update($update_array){
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $update_array['last_updated'] = $date->format('c');
        $query = $this->db->update('task', $update_array, array('task_id' => $update_array['task_id']));
        //var_dump($update_array);
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
        $query = $this->db->update('task', $update_array, array('task_id' => $task_id));
        return $this->db->affected_rows();
    }
    public function delete($task_id){
        if(isset($task_id)){
            $this->db->delete('task', array('task_id' => $task_id));
        }
        return $this->db->affected_rows();
    }
    //  Urgency = 1/ days left
    public function get_urgency($task_id){
        $t = $this->retrieve_by_id($task_id);
        $targeted_end_datetime = $t['targeted_end_datetime'];
        $current_datetime = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $diff = $current_datetime->diff($targeted_end_datetime);
        $days_left = $diff->format('%R%a');
        $urgency = 1/ $days_left;
        return $urgency;
    }
    public function get_days_left($task_id){
        $t = $this->retrieve_by_id($task_id);
        $targeted_end_datetime = new DateTime($t['targeted_end_datetime']);
        $current_datetime = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $diff = $current_datetime->diff($targeted_end_datetime);
        $days_left = $diff->format('%R%a');
        return $days_left;
    }
    public function retrieve_for_esenhower($urgencey_upper, $urgency_lower,$importance_upper,$importance_lower){

            $query = $this->db->query("SELECT task.* ,DATEDIFF(targeted_end_datetime,NOW()) AS days from task where if_completed=0
                                      and DATEDIFF(targeted_end_datetime,NOW())<=?
                                      and DATEDIFF(targeted_end_datetime,NOW())>?
                                       and importance<=?
                                       and importance>?
                                       order by days ASC
                                       ;",[$urgencey_upper, $urgency_lower,$importance_upper,$importance_lower]);
            return $query->result_array();

    }
    public function get_num_of_tasks_per_phase($project_id){
        $query= $this->db->query(
            "SELECT count(*) as num, phase_name FROM task t, phase p WHERE p.phase_id=t.phase_id AND project_id = ? GROUP BY phase_name",
            [$project_id]
        );
        return $query->result_array();
    }

}