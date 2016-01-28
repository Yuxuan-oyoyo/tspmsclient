<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 1/27/2016
 * Time: 9:48 PM
 */
class Issue_report_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        $this->load->helper("date");
    }
    public function insert($issue_list,$project_id){

        $data_cleaning_period = "1 day";

        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $data_cleaning_threshold = strtotime($date->format('c')." -".$data_cleaning_period);

        if($this->get_time_last_updated() <= $data_cleaning_threshold){
            $this->db->query("DELETE from issue_report");
        }else{
            $this->db->query(
                "DELETE from issue_report WHERE project_id=?",
                [$project_id]
            );
        }
        //var_dump($issue_list);
        //var_dump($project_id);
        foreach($issue_list as $issue){
            $issue["date_loaded"] = $date->format('c');
            $this->db->insert('issue_report', $issue);
            var_dump($this->db->error());
        }
    }
    private function get_time_last_updated(){
        $last_updated = $this->db->query("SELECT max(date_loaded) as last_time FROM issue_report");
        return $last_updated->row_array()["last_time"];

    }
    public function get_num_of_issues_per_phase($project_id){
        $record = $this->db->query("SELECT count(*) AS num, phase_name FROM issue_report i, phase p WHERE i.phase =p.phase_id AND  project_id=? GROUP BY phase",[$project_id]);
        return $record->result_array();
    }

    /**
     * @param $project_id
     * @param $categories ["phase"=>1/2/3.., "kind"=>"bug"/"enhancement".., "priority"=>1/2/3/4...]
     */
    public function get_sum_time_spent_per_category($project_id, $categories){
        $condition_clause = "";
        foreach($categories as $key=>$value){
            if(in_array($key,["phase","kind","priority"])){
                $value_clean = $value;//do some cleaning here
                $condition_clause .=" $key='$value_clean' AND ";
            }
        }
        $sql = "SELECT sum(duration_1) AS du1, sum(duration_2) AS du2, ".
            "sum(duration_3) AS du3, sum(duration_4) AS du4, sum(duration_1) AS du5, ".
            "phase_name FROM issue_report i, phase p WHERE i.phase =p.phase_id ".
            "  AND ".$condition_clause." project_id=? GROUP BY phase";
        $query = $this->db->query($sql,[$project_id]);
        var_dump($this->db->error());
        var_dump($sql);
        return $query->result_array();
    }
    public function get_per_issue_data($project_id){
        $query=$this->db->query("SELECT local_id, title, date_created, date_resolved, date_due, ".
            " phase, actual_duration/expected_duration as time_ratio ".
            " FROM issue_report WHERE project_id=? ",[$project_id]);
        return $query->result_array();
    }
}