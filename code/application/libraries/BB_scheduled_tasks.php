<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 1/28/2016
 * Time: 2:27 PM
 */
class BB_scheduled_tasks {
    /**
     * @param $project_id
     * @param null $repo_slug
     */
    public function fetch_project_issues($project_id,$repo_slug=null){
        //get reposlug if it's null
        $CI =& get_instance();
        if($repo_slug ===null){
            $CI->load->model("Project_model");
            $project_record = $CI->Project_model->retrieve_by_id($project_id);
            $repo_slug = $project_record["bitbucket_repo_name"];
        }
        //retrieve all phase date of this project
        $CI->load->model("Project_phase_model");
        $project_phase_record = $CI->Project_phase_model->retrieve_by_project_id($project_id);
        //convert time to date format
        foreach($project_phase_record as $key=>$phase){
            $phase["start_time"] = strtotime($phase["start_time"]);
            $phase["end_time"] = strtotime($phase["end_time"]);
            $project_phase_record[$key] = $phase;
        }
        //load other entities
        $CI->load->model("logs/Issue_log_model");
        $CI->load->library("BB_issues");
        $CI->load->model("Issue_report_model");

        //define mappings for priority (to numeric)
        $priority_mapping =[
            "trivial"=>1, "minor"=>2,"major"=>3, "critical"=>4, "blocker"=>5
        ];
        //define offsets
        $offset = 0;
        $limit = 50;//a value between 0-50, as specified by bb api
        $count = 10000; //total number of issues to be returned
        $issue_list_interim = [];//stores concatenated issues from bb
        while($offset<$count){
            $issue_list_raw = $CI->bb_issues->retrieveIssues(
                $repo_slug,
                null,
                ["start"=>$offset,"limit"=>50],
                $try_twice = true
            );
            $count = $issue_list_raw["count"];
            $issue_list_partial = isset($issue_list_raw["issues"])?$issue_list_raw["issues"]:[];
            $offset = $offset +$limit;
            $issue_list_interim = array_merge($issue_list_interim,$issue_list_partial);
        }
        $issue_list =[];// stores issues after cleaning
        //do transformations here
        foreach($issue_list_interim as $key =>$issue){
            //only keep resolved issues
            //if($issue["status"]!="resolved") continue;
            //transform priority
            $issue["priority"] = $priority_mapping[$issue["priority"]];
            //get the phase id
            $issue["date_created"] = strtotime($issue["utc_created_on"]);
            $issue["phase"] = -1;
            foreach($project_phase_record as $phase){
                if(isset($phase["end_time"])) {
                    if ($phase["start_time"] <= $issue["date_created"]
                        && $phase["end_time"] > $issue["date_created"]
                    ) {
                        $issue["phase"] = $phase["phase_id"];
                        break;
                    }
                }elseif($phase["start_time"] <= $issue["date_created"]){//deals with current phase
                    $issue["phase"] = $phase["phase_id"];
                    break;
                }
            }
            var_dump($issue["phase"]);
            //retrieve logs for this project
            $issue["duration_1"] = 0;
            $issue["duration_2"] = 0;
            $issue["duration_3"] = 0;
            $issue["duration_4"] = 0;
            $issue["duration_5"] = 0;
            $issue["date_resolved"] = $issue["utc_last_updated"];
            //retrieve all relevant issue logs
            $issue_log_records = $CI->Issue_log_model->retrieve($repo_slug, $issue["local_id"]);
            if($issue_log_records!=null) {
                for ($i = 0; $i < count($issue_log_records) - 1; $i++) {
                    $start_date = strtotime($issue_log_records[$i]["date_updated"]);
                    $end_date   = strtotime($issue_log_records[$i+1]["date_updated"]);
                    $diff = $end_date-$start_date;
                    $status = $issue_log_records[$i]["status"];
                    switch($status){
                        case "to develop":
                            $issue["duration_1"]+=$diff;
                            break;
                        case "to test":
                            $issue["duration_2"]+=$diff;
                            break;
                        case "ready for deployment":
                            $issue["duration_3"]+=$diff;
                            break;
                        case "to deploy":
                            $issue["duration_4"]+=$diff;
                            break;
                    }
                    if($issue_log_records[$i+1]["status"]=="resolved"){
                        $issue["date_resolved"] = $issue_log_records[$i+1]["date_updated"];
                    }
                }
            }
            $unneeded = [
                "content","utc_created_on","created_on","reported_by","utc_last_updated",
                "responsible","follower_count","resource_uri","is_spam","metadata",
                "comment_count","deadline","usecase"
            ];
            $issue["expected_duration"] = -1;
            if(isset($issue["deadline"]) && $date_due = strtotime($issue["deadline"])){
                $issue["expected_duration"] = $date_due - $issue["date_created"];
            }
            $issue["actual_duration"] = strtotime($issue["date_resolved"]) - $issue["date_created"];
            $issue["date_created"] = date('c',$issue["date_created"]);
            $issue["kind"]=$issue["metadata"]["kind"];
            $issue["project_id"]=$project_id;
            $issue["date_due"]=isset($issue["deadline"])?$issue["deadline"]:null;
            foreach($unneeded as $a){
                unset($issue[$a]);
            }
            $issue_list[$issue["local_id"]] = $issue;
        }
        $CI->Issue_report_model->insert($issue_list,$project_id);
    }
}