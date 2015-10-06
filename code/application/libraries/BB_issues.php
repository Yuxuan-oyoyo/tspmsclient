<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/4/2015
 * Time: 2:00 PM
 */
class BB_issues {
    //TODO: count hourly count
    //TODO: no re auth before it expires
    public  $oauth_endpoint = 'https://bitbucket.org/site/oauth2/access_token';

    public function getDefaultOauthToken(){
        $r = new \HttpRequest($this->oauth_endpoint, \HttpRequest::METH_POST);
        $r->addPostFields(['grant_type' => 'client_credentials']);
        try {
            $reply = $r->send()->getBody();
            if($reply_array = json_decode($reply)!=null){
                if(isset($reply_array['error'])){
                    echo var_dump($reply_array);
                }else if(isset($reply_array["access_token"])){
                    return $reply_array["access_token"];
                }
            }
        } catch (\HttpException $ex) {
            echo $ex;
        }
        return null;
    }

    private function setEndpoint($repo_slug){
        return "https://api.bitbucket.org/1.0/repositories/".BB_ACCOUNT_NAME."/".$repo_slug."/issues";
    }

    public function retrieveIssues($repo_slug, array $parameters=null){
        $token = $this->getDefaultOauthToken();

        $endpoint = $this->setEndpoint($repo_slug);

        $r = new \HttpRequest($endpoint, \HttpRequest::METH_GET);
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

        } catch (\HttpException $ex) {
            echo $ex;
        }
        return null;

    }
    public function retrieveIssue($repo_slug, $issue_id){
        $token = $this->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug);

        $r = new HttpRequest($endpoint, HttpRequest::METH_GET);
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
    public function postNewIssue($repo_slug, array $issue_array){
        $this->sendIssueRequest($issue_array,HttpRequest::METH_POST);
    }
    public function updateIssue($repo_slug, array $issue_array){
        $this->sendIssueRequest($issue_array, HttpRequest::METH_PUT);
    }
    public function getCommentsForAnIssue($repo_slug, $issue_id){
        //TODO:retrieve comments
    }
    public function postCommentsForAnIssue($repo_slug, $issue_id, $comment){
        //TODO: post comment
        //question: $comment string only? how do we know who posts it?
    }
    private function sendIssueRequest($repo_slug, $issue_array, $flag=HttpRequest::METH_POST){
        $token = $this->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug);

        $r = new HttpRequest($endpoint, HttpRequest::METH_POST);
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
        return null;
    }

}