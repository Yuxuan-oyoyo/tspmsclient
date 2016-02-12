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
        //define status group
        $status_group = [
            "new"=>1,"open"=>1, "on hold"=>2, "resolved"=>3, "invalid"=>2,
            "duplicate"=>2,"wontfix"=>2,"closed"=>2
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
                //var_dump($phase["end_time"]);
                //var_dump($issue["date_created"]);
                //var_dump($phase["start_time"]);
                if($phase["end_time"]) {
                    if ($phase["start_time"] <= $issue["date_created"]
                         && $phase["end_time"] > $issue["date_created"]) {
                        $issue["phase"] = $phase["phase_id"];
                        break;
                    }
                }elseif($phase["start_time"] <= $issue["date_created"]){//deals with current phase
                    $issue["phase"] = $phase["phase_id"];
                    break;
                }
            }
            //if($issue["phase"]==-1)var_dump("Here's a minus one!!!!!!!!!");
            //initialize duration 1 to 5 as 0
            for ($i=1;$i<=5;$i++){
                $issue["duration_".$i] = 0;
            }
            //initialize data_resolved as utc_last_updated from bb. By right this should be overwritten
            $issue["date_resolved"] = $issue["utc_last_updated"];
            //retrieve all relevant issue logs for this issue. ilr is for Issue Log Records
            $ilr = $CI->Issue_log_model->retrieve($repo_slug, $issue["local_id"]);
            if($ilr!==null) {
                for ($i = 0; $i < count($ilr) - 1; $i++) {
                    $start_date = strtotime($ilr[$i]["date_updated"]);
                    $curr_status = $ilr[$i]["status"];
                    //if the current status is resolved or abnormal, this is not a start of stage
                    if(isset($curr_status)&&$status_group[$curr_status]>1) continue;
                    $curr_workflow = $ilr[$i]["status"]=="default"?"status":$ilr[$i]["workflow"];
                    $has_changed_stage = false;
                    //TODO: this may cause error due to diff timezone
                    //initialize time
                    $diff = strtotime($issue["date_resolved"])-$start_date;
                    //find the record that actually changes the stage
                    $j = $i+1;
                    while(!$has_changed_stage && $i<count($ilr)){
                        $next_workflow = $ilr[$j]["workflow"]=="default"?"status":$ilr[$j]["workflow"];
                        if (!isset($ilr[$j]["status"])||!isset($curr_status)
                                ||$status_group[$ilr[$j]["status"]]!=$status_group[$curr_status]
                                ||$curr_workflow!=$next_workflow){
                            $has_changed_stage = true;
                            $diff =strtotime($ilr[$j]["date_updated"]) - $start_date;
                        }
                        $j++;
                    }
                    if(isset($curr_status)&&$status_group[$curr_status]==3){
                        $issue["date_resolved"] = $ilr[$j]["date_updated"];
                    }

                    if($curr_workflow=="to develop" || $curr_workflow=="default") {
                        $issue["duration_1"] += $diff;
                    }elseif($curr_workflow=="to test") {
                        $issue["duration_2"] += $diff;
                    }elseif($curr_workflow=="ready for deployment") {
                        $issue["duration_3"] += $diff;
                    }elseif($curr_workflow=="to deploy"){
                        $issue["duration_4"]+=$diff;
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
    function calibrate_bb_milestones(){
        $CI =& get_instance();
        $CI->load->model("Milestone_model");
        $CI->load->library("BB_milestones");
        $db_milestones = $CI->Milestone_model->retrieve_all();
        $m_ids = [];/*reponame=>mid*/
        $result = ["added"=>[],"deleted"=>[],"time-taken"=>0];
        foreach($db_milestones as $m) {
            if(isset($m["bitbucket_repo_name"])) {//make sure there is a repo name
                if (isset($m_ids[$m["bitbucket_repo_name"]])) {
                    array_push($m_ids[$m["bitbucket_repo_name"]], $m["milestone_id"]);
                } else {
                    $m_ids[$m["bitbucket_repo_name"]] = [];
                }
            }
        }
        foreach($m_ids as $repo_slug=>$id_arr){// $id_arr=[id1, id2, id3]
            //get all ms from BB for current repo
            $bb_milestones = $CI->bb_milestones->getAllMilestones($repo_slug);
            if ($bb_milestones===null) break;
            //extract names from the returned result
            $bb_milestone_names = [];
            foreach($bb_milestones as $bb_m){//search for extra and remove
                if(!in_array($bb_m["name"], $id_arr)){
                    //echo "deleting milestone from bitbucket    ".$bb_m["name"]."<br>";
                    $delete_outcome = $CI->bb_milestones->deleteMilestone($repo_slug, $bb_m["id"]);
                    if($delete_outcome) array_push($result["deleted"],$bb_m["name"]);
                }else{
                    array_push($bb_milestone_names,$bb_m["name"]);
                }
            }
            //send new milestone ids
            $new_milestones = array_diff($id_arr, $bb_milestone_names);
            foreach($new_milestones as $m){
                //echo "adding milestone to bitbucket   ".$m."<br>";
                $post_outcome = $CI->bb_milestones->postMilestone($repo_slug,$m);
                if($post_outcome) array_push($result["added"],$m);
            }
        }
        return $result;
    }
    public function fetch_issue_counts(){
        $CI =& get_instance();
        $CI->load->library("BB_issues");
        $CI->load->model("Project_model");

        $project_records = $CI->Project_model->retrieveAll();
        $result = [];
        foreach($project_records as $p){
            $repo_slug = $p["bitbucket_repo_name"];
            if(!empty($repo_slug) && $p["repo_name_valid"]){
                $bb_reply = $CI->bb_issues->retrieveIssues($repo_slug, null,["limit"=>1]);
                if($bb_reply!==null && isset($bb_reply["count"])){
                    $CI->Project_model->set_issue_count($p["project_id"],$bb_reply["count"] );
                    array_push($result, [
                        "id"=>$p["project_id"],
                        "count"=>$bb_reply["count"]
                    ]);

                }
            }
        }
        return $result;
    }
}