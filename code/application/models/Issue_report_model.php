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
    public function delete($project_id){
        $data_cleaning_period = "1 day";
        $data_cleaning_threshold = strtotime((new DateTime())->format("c")." -".$data_cleaning_period);
        //var_dump($data_cleaning_threshold);
        $time_last_updated = $this->get_time_last_updated();
        //var_dump(strtotime($time_last_updated));
        if(isset($time_last_updated) && strtotime($time_last_updated) <= $data_cleaning_threshold){
            /*
             * Temporarily disable this to keep data
             */
            //$this->db->query("DELETE from issue_report");
        }elseif(isset($time_last_updated)){
            //var_dump("deleting".$project_id);

            $this->db->query(
                "DELETE from issue_report WHERE project_id=?",
                [$project_id]
            );

        }
    }
    public function insert($issue_list,$project_id){
        $this->delete($project_id);
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        //var_dump($issue_list);
        //var_dump($project_id);
        foreach($issue_list as $issue){
            $issue["date_loaded"] = $date->format('c');
            $this->db->insert('issue_report', $issue);
            //var_dump($id);
        }
    }
    private function get_time_last_updated(){
        $last_updated = $this->db->query("SELECT max(date_loaded) as last_time FROM issue_report");
        return $last_updated->row_array()["last_time"];

    }
    public function get_num_of_issues_per_phase($project_id){
        $record = $this->db->query("SELECT count(*) AS num, phase_name FROM issue_report i, phase p WHERE status!='resolved' AND i.phase =p.phase_id AND  project_id=? GROUP BY phase",[$project_id]);
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
            "phase_name FROM issue_report i, phase p WHERE status='resolved' AND i.phase =p.phase_id ".
            "  AND ".$condition_clause." project_id=?";
        //var_dump($sql);
        $query = $this->db->query($sql,[$project_id]);
        return $query->row_array();
    }
    public function get_per_issue_data($project_id){
        $query=$this->db->query("SELECT local_id, title, date_created, date_resolved, date_due, ".
            " phase, actual_duration/expected_duration as time_ratio ".
            " FROM issue_report WHERE status='resolved' AND project_id=? ",[$project_id]);
        return $query->result_array();
    }

    public function get_num_of_issue_onging_projects(){
        $query=$this->db->query("select count(local_id) as count, project.project_id from issue_report right join project on project.project_id = issue_report.project_id where is_ongoing = 1 group by project_id;");
        return $query->result_array();
    }

    public function get_num_of_issue_past_projects(){
        $query=$this->db->query("select count(local_id) as count, project.project_id from issue_report right join project on project.project_id = issue_report.project_id where is_ongoing = 0 group by project_id;");
        return $query->result_array();
    }

    public function get_ongoing_issue_per_project($project_id){
        $query=$this->db->query("select local_id, project.project_id, title, date_due, project.priority as proj_pr, issue_report.priority as issue_pr,
DATEDIFF(date_due ,NOW()) as days_to_go from issue_report join project
    on project.project_id = issue_report.project_id
       and status != 'resolved' and is_ongoing = 1 and project.project_id = ?",[$project_id]);
        return $query->result_array();
    }

    public function get_ongoing_issue_across_projects(){
        $query=$this->db->query("select local_id, project.project_id, title, date_due, project.priority as proj_pr, issue_report.priority as issue_pr,
DATEDIFF(date_due ,NOW()) as days_to_go from issue_report join project
    on project.project_id = issue_report.project_id
       and status != 'resolved' and is_ongoing = 1;");
        return $query->result_array();
    }

    public function insert_total_urgency_score($score){
        $query=$this->db->query("INSERT INTO urgency_score_record (score) VALUES (?)",[$score]);
    }

    public function retrieve_report_model(){
        $query=$this->db->query("select * from urgency_score_record;");
        return $query->result_array();
    }
}