<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/5/2015
 * Time: 11:19 AM
 */
class BB_milestones {

    private function setEndpoint($repo_slug){
        return "https://api.bitbucket.org/1.0/repositories/".BB_ACCOUNT_NAME."/".$repo_slug."/issues";
    }
    public function getAllMilestones($repo_slug){
        //TODO: get all milestones
    }
    public function getMilestone($repo_slug, $milestone_id){
        //TODO: get milestone or return null when not found
    }
    public function postMilestone($repo_slug, $name){
        //TODO: return milestone id and save in db
    }

}