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
            /*$sql = "SELECT project_phase.*,phase.phase_name,project.* FROM `project_phase`,`phase`,`project` WHERE project_phase.project_id = project.project_id and phase.phase_id=project_phase.phase_id and project.project_id =?";
            $query = $this->db->query($sql, array($input_p_id));
            return $query->row_array();*/
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
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $update_array['last_updated'] = $date->format('c');
        $query = $this->db->update('project', $update_array, array('project_id' => $update_array['project_id']));
        print_r($query);
        return $this->db->affected_rows();
    }
    public function insert($insert_array){
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $insert_array['start_time'] = $date->format('c');
        $insert_array['last_updated'] = $date->format('c');
        $this->db->insert('project', $insert_array);
        return $this->db->insert_id();
    }
    //not in use
    public function deactivate($input_p_id){
        $this->db->update('project', ["is_active"=>0], array('p_id' => $input_p_id));
        return $this->db->affected_rows();
    }
    /*
     * returns [tag1, tag2, tag3...]
     */
    public function getTags($project_id=null){
        $tag_array = [];
        $delimiter = ",";
        if($project_id==null){
            $query = $this->db->query("select tags, last_updated from project order by last_updated desc");
        }else{
            $query = $this->db->query("select tags, last_updated from project where project_id=? order by last_updated desc",[$project_id]);
        }

        foreach ($query->result_array() as $row) {
            $tag_array = array_merge($tag_array, explode($delimiter,$row['tags']));
        }
        //echo var_dump($tag_array);
        return array_unique($tag_array);
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

    public function retrieve_all_with_phase(){
        $sql = 'SELECT project.*, phase.phase_name from  project left join project_phase
on project.current_project_phase_id=project_phase.project_phase_id
left join phase on project_phase.phase_id = phase.phase_id and project.is_ongoing = 1';
        $query=$this->db->query($sql);
        return $query->result_array();
    }

    public function retrieve_all_ongoing(){
     $sql = 'SELECT p.*, phase.phase_name from (select project.* from project where  project.is_ongoing = 1) as p left join project_phase
    on p.current_project_phase_id=project_phase.project_phase_id
      left join phase on project_phase.phase_id = phase.phase_id';
        $query=$this->db->query($sql);
        return $query->result_array();
    }

    public function retrieve_all_past(){
        $sql = 'SELECT project.* from  project where project.is_ongoing = 0';
        $query=$this->db->query($sql);
        return $query->result_array();
    }

    public function retrieve_by_c_id($input_c_id){
        if(isset($input_c_id)){
           $sql= 'SELECT project.*, phase.phase_name from  project left join project_phase
on project.current_project_phase_id=project_phase.project_phase_id
left join phase on project_phase.phase_id = phase.phase_id and project.c_id= ?';
            $query=$this->db->query($sql,array($input_c_id));
            if( $query->num_rows()>0){
                return $query->result_array();
            }
        }
        return null;
    }

    public function update_new_project_phase_id($project_id,$current_project_phase_id){
        $project = $this->retrieve_by_id($project_id);
        $project['project_id'] = $project_id;
        $project['current_project_phase_id'] = $current_project_phase_id;
        $this->update($project);
    }
}
