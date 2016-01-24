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
    public function insert($issue_array){
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $insert_array['last_updated'] = $date->format('c');
        $past_query = $this->db->query(
            "SELECT status FROM issue_log WHERE issue_id =? ORDER BY date_updated DESC LIMIT 1",
            [$issue_array["issue_id"]]
        );
        $past_status = null;
        if( $past_query->num_rows()>0){
            $past_status =  $past_query->row_array()["status"];
        }
        //check if the issue has been created before
        if(is_null($past_status)|| $past_status!=$issue_array["status"]){
            $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
            $issue_array['date_updated'] = $date->format('c');
            $this->db->insert('issue_log', $issue_array);
        }
    }
}