<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 11/6/2015
 * Time: 12:18 AM
 */
class Issue_log_model extends CI_Model{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
        $this->load->library("session");

    }
    public function last_record_workflow($issue_id, $repo_slug){
        $past_query = $this->db->query(
            "SELECT status, workflow FROM issue_log WHERE workflow is not null AND issue_id =? AND repo_slug=? ORDER BY date_updated DESC LIMIT 1",
            [$issue_id, $repo_slug]
        );
        return $past_query->row_array();
    }
    public function last_record_status($issue_id, $repo_slug){
        $past_query = $this->db->query(
            "SELECT status, workflow FROM issue_log WHERE status is not null AND issue_id =? AND repo_slug=? ORDER BY date_updated DESC LIMIT 1",
            [$issue_id, $repo_slug]
        );
        return $past_query->row_array();
    }
    public function insert($issue_array){
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $issue_array['date_updated'] = $date->format('c');
        $this->db->insert('issue_log', $issue_array);
    }
    public function retrieve($repo_slug, $issue_id){
        $query=$this->db->query("SELECT * FROM issue_log WHERE issue_id=? AND repo_slug=? ORDER BY date_updated",
            [$issue_id, $repo_slug]);
        if( $query->num_rows()>0) {
            return $query->result_array();
        }
        return null;
    }
}