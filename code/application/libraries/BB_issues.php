<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/4/2015
 * Time: 2:00 PM
 */
class BB_issues {
    public $issues_endpoint = "https://api.bitbucket.org/1.0/repositories/smuremix/tspms/issues";
    //TODO: count hourly count
    //TODO: no re auth before it expires
    public function retrieveIssues(array $parameters=null){
        $this->CI->load->libraries("BB_shared");
        $token = $this->BB_shared->getDefaultOauthToken();

        $r = new HttpRequest($this->issues_endpoint, HttpRequest::METH_GET);
        $r->addQueryData(['access_token' => $token]);
        if($parameters!=null) {
            $r->addQueryData($parameters);
        }
        try {
            $reply = $r->send()->getBody();
            if($reply_array = json_decode($reply)!=null){
                if(isset($reply_array['error'])){
                    echo var_dump($reply_array);
                }else{
                    return $reply_array;
                }
            }

        } catch (HttpException $ex) {
            echo $ex;
        }
        return null;

    }
    public function retrieveIssue($issue_id){
        $this->CI->load->libraries("BB_shared");
        $token = $this->BB_shared->getDefaultOauthToken();

        $r = new HttpRequest($this->issues_endpoint, HttpRequest::METH_GET);
        $r->addQueryData(['access_token' => $token]);
        $r->addQueryData($issue_id);
        try {
            $reply = $r->send()->getBody();
            if($reply_array = json_decode($reply)!=null){
                if(isset($reply_array['error'])){
                    echo var_dump($reply_array);
                }else{
                    return $reply_array;
                }
            }
        } catch (HttpException $ex) {
            echo $ex;
        }
        return null;
    }
    public function postNewIssue(array $issue_array){
        $this->sendIssueRequest($issue_array,HttpRequest::METH_POST);
    }
    public function updateIssue(array $issue_array){
        $this->sendIssueRequest($issue_array, HttpRequest::METH_PUT);
    }
    public function getCommentsForAnIssue($issue_id){
        //
    }
    private function sendIssueRequest($issue_array, $flag=HttpRequest::METH_POST){
        $this->CI->load->libraries("BB_shared");
        $token = $this->BB_shared->getDefaultOauthToken();

        $r = new HttpRequest($this->oauth_endpoint, HttpRequest::METH_POST);
        $r->addPostFields($issue_array);
        $r->addPostFields(['access_token' => $token]);
        try {
            $reply = $r->send()->getBody();
            if($reply_array = json_decode($reply)!=null){
                if(isset($reply_array['error'])){
                    echo var_dump($reply_array);
                }else{
                    return $reply_array;
                }
            }
        } catch (HttpException $ex) {
            echo $ex;
        }
    }

}