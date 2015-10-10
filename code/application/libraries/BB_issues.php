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
    //public  $oauth_endpoint = 'https://bitbucket.org/site/oauth2/access_token';

    public $_print_err = false;
    private function setEndpoint($repo_slug){
        return "https://api.bitbucket.org/1.0/repositories/".BB_ACCOUNT_NAME."/".$repo_slug."/issues";
    }

    /**
     * @param $repo_slug
     * @param array|null $parameters
     * @return array|null
     */
    public function retrieveIssues($repo_slug, array $parameters=null){
        $CI =& get_instance();
        $CI->load->library('bb_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug);
        $parameters["access_token"] = $token;
        $url = $endpoint.'?'.$this->construct_paras($parameters);
        return $this->sendGetRequest($url);
        //return $this->sendGetRequest($endpoint);
    }

    /**
     * @param $repo_slug
     * @param $issue_id
     * @return array|null
     */
    public function retrieveIssue($repo_slug, $issue_id){
        $CI =& get_instance();
        $CI->load->library('bb_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug);
        $parameters["access_token"] = $token;

        $url = $endpoint.'/'.$issue_id;

        return $this->sendGetRequest($url);
    }
    private function sendGetRequest($url){
        /*open connection*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_USERPWD, BB_OAUTH_KEY.':'.BB_OAUTH_SECRET);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        /*process response*/
        if($response==false){
            echo curl_error($ch);
        }
        //echo var_dump( curl_getinfo($ch));
        //echo "response",var_dump($response);
        if(($reply_array = json_decode($response,true))!=null){
            if(isset($reply_array['error'])){
                if($this->_print_err) echo var_dump($reply_array);
            }else{
                return $reply_array;
            }
        }
        return null;

    }
    private function construct_paras($parameters){
        if($parameters==false){
            return '';
        }else{
            $fields_string = '';
            foreach($parameters as $key=>$value){
                $fields_string .= $key.'='.$value.'&';
            }
            rtrim($fields_string, '&');
            //echo var_dump($fields_string);
            return $fields_string;
        }

    }

    /**
     * @param $repo_slug
     * @param array $issue_array
     */
    public function postNewIssue($repo_slug, array $issue_array){
        $this->sendIssueRequest($issue_array,$issue_array,"POST");
        //TODO: may need a confirmation
    }
    /**
     * @param $repo_slug
     * @param array $issue_array this can be the same as array in post new
     * issue, and can also be incomplete
     */
    public function updateIssue($repo_slug, array $issue_array){
        $this->sendIssueRequest($issue_array,$issue_array,"PUT");
    }
    public function getCommentsForAnIssue($repo_slug, $issue_id){
        //TODO:retrieve comments
    }
    public function postCommentsForAnIssue($repo_slug, $issue_id, $comment){
        //TODO: post comment
        //question: $comment string only? how do we know who posts it?
    }
    private function sendIssueRequest($repo_slug, $issue_array, $_flag='POST'){
        $CI =& get_instance();
        $CI->load->library('bb_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug);
        $issue_array['token'] = $token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);

        if($_flag=='POST')
            curl_setopt($ch, CURLOPT_POST, TRUE);
        else if($_flag=='PUT')
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        curl_setopt($ch, CURLOPT_POSTFIELDS, $issue_array);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        /*process response*/
        if($response==false){
            echo curl_error($ch);
        }
        if(($reply_array = json_decode($response,true))!=null){
            if(isset($reply_array['error'])){
                if($this->_print_err) echo var_dump($reply_array);
            }else{
                return $reply_array;
            }
        }
        return null;

    }

}