<?php

/**
 * Interface for scheduler
 * Created by PhpStorm.
 * User: Alex
 * Date: 1/15/2016
 * Time: 2:26 PM
 */
class Scheduled_tasks extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('BB_issues');
        $this->session->userdata('internal_uid',1);
    }
    /* Calibrates bb milestones with database
     * prints {"added":num_added,"removed":num_removed,"time":time_taken}
     */
    function calibrate_bb_milestones(){
        $start_time = time();
        $this->load->model("Milestone_model");
        $this->load->library("BB_milestones");
        $db_milestones = $this->Milestone_model->retrieve_all();
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
            $bb_milestones = $this->bb_milestones->getAllMilestones($repo_slug);
            if ($bb_milestones===null) break;
            //extract names from the returned result
            $bb_milestone_names = [];
            foreach($bb_milestones as $bb_m){//search for extra and remove
                if(!in_array($bb_m["name"], $id_arr)){
                    //echo "deleting milestone from bitbucket    ".$bb_m["name"]."<br>";
                    $delete_outcome = $this->bb_milestones->deleteMilestone($repo_slug, $bb_m["id"]);
                    if($delete_outcome) array_push($result["deleted"],$bb_m["name"]);
                }else{
                    array_push($bb_milestone_names,$bb_m["name"]);
                }
            }
            //send new milestone ids
            $new_milestones = array_diff($id_arr, $bb_milestone_names);
            foreach($new_milestones as $m){
                //echo "adding milestone to bitbucket   ".$m."<br>";
                $post_outcome = $this->bb_milestones->postMilestone($repo_slug,$m);
                if($post_outcome) array_push($result["added"],$m);
            }
        }
        $end_time = time();
        $result["time-taken"] = $end_time-$start_time;
        echo json_encode($result);
    }

    /**
     * This method can be called by scheduler and project list page(through ajax call)
     */
    public function fetch_issue_counts(){
        $this->load->library("BB_scheduled_tasks");
        $result = $this->bb_scheduled_tasks->fetch_issue_counts();
        echo json_encode($result);
    }





}