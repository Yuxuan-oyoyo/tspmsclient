<?php

class Use_case_model extends CI_Model{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }

    public function insert($insert_array){
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $insert_array['last_updated'] = $date->format('c');
        $this->db->insert('use_case', $insert_array);
        $uc_id = $this->db->insert_id();
        return $uc_id;
    }

    public function retrieveAll(){
        $query = $this->db->query("select * from use_case");
        return $query->result_array();
    }

    public function get_sub_id($project_id){
        $query = $this->db->query("select max(sub_id)as id from use_case where project_id=".$project_id);
        return $query->row_array()['id'];
    }

    public function get_no_of_usecase_by_project($project_id){
        $query = $this->db->query("select count(*)as number from use_case where project_id=".$project_id);
        return $query->row_array()['number'];
    }

    public function retrieve_by_id($uc_id){
        if(isset($uc_id)){
            $query = $this->db->get_where("use_case",["usecase_id"=>$uc_id]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }
    public function retrieve_by_project_id($p_id){
        if(isset($p_id)){
            $query = $this->db->get_where("use_case",["project_id"=>$p_id]);
            //$this->db->order_by("sub_id", "asc");
            return $query->result_array();

        }
        return null;
    }

    public function retrieve_external_by_project_id($p_id){
        if(isset($p_id)){
            $query = $this->db->get_where("use_case",["project_id"=>$p_id,"type"=>"External"]);
            //$this->db->order_by("sub_id", "asc");
            return $query->result_array();

        }
        return null;
    }
    public function retrieve_by_project_repo_slug($repo_slug){
        if(isset($repo_slug)){
            $query = $this->db->query("SELECT usecase_id, sub_id,title,importance FROM use_case u, project p
                      WHERE u.project_id =p.project_id AND bitbucket_repo_name=? ORDER BY importance DESC ", [$repo_slug]);
            //$this->db->order_by("sub_id", "asc");
            return $query->result_array();
        }
        return null;
    }

    public function update($update_array){
        //$date = date('Y-m-d H:i:s');
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $update_array['last_updated'] =  $date->format('c');
        $this->db->update('use_case', $update_array, array('usecase_id' => $update_array['usecase_id']));
        return $this->db->affected_rows();
    }

    public function delete_usecase($uc_id){
        if(isset($uc_id)){
            $this->db->delete('use_case', array('usecase_id' => $uc_id));
        }
        return null;
    }
}