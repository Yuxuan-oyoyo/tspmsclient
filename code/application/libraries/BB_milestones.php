<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/5/2015
 * Time: 11:19 AM
 */
class BB_milestones {

    private function setEndpoint($repo_slug){
        return "https://api.bitbucket.org/1.0/repositories/".BB_ACCOUNT_NAME."/".$repo_slug."/issues/milestones";
    }
    public function getAllMilestones($repo_slug){
        /*settle the access token and etc*/
        $CI =& get_instance();
        $CI->load->library('BB_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug);
        $parameters["access_token"] = $token;
        $parameters["timestamp"] = time();
        $url = $endpoint.'?'.http_build_query($parameters);
        /*construct endpoint*/
        $result =  $this->sendGetRequest($url);
        if(is_null($result)){
            $parameters["access_token"] = $CI->bb_shared->requestFromServer();
            $url = $endpoint.'?'.http_build_query($parameters);
            $result = $this->sendGetRequest($url);
        }
        //var_dump($result);
        return $result;
    }

    public function getMilestone($repo_slug, $milestone_id){
        /*settle the access token and etc*/
        $CI =& get_instance();
        $CI->load->library('BB_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug)."/".$milestone_id;
        $parameters["access_token"] = $token;
        $parameters["timestamp"] = time();
        /*construct endpoint*/
        $result =  $this->sendGetRequest($endpoint);
        if(is_null($result)){
            $parameters["access_token"] = $CI->bb_shared->requestFromServer();
            $result = $this->sendGetRequest($endpoint);
        }
        return $result;
    }
    private function sendGetRequest($url){
        /*open connection*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($code==200 && ($reply_array = json_decode($response,true))!=null){
            //echo ($response);
            if(isset($reply_array['error'])){
                if($this->_print_err) var_dump($reply_array);
            }else{
                /*this is expected*/
                return $reply_array;
            }
        }
        return null;
    }

    /**
     * @param $repo_slug
     * @param $name
     * @return null|int the assigned bb_id of the posted milestone
     */
    public function postMilestone($repo_slug, $name) {
        $CI =& get_instance();
        $CI->load->library('BB_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug);
        $_trial = 2;
        $data = [
            'access_token' => $token,
            'name' => $name
        ];
        while ($_trial > 0) {
            $_trial -= 1;/*IMPORTANT*/
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
            $response = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            /*debug-------------------------------------------
           curl_setopt($ch, CURLOPT_VERBOSE, true);

           $verbose = fopen('php://temp', 'w+');
           curl_setopt($ch, CURLOPT_STDERR, $verbose);
           $response = curl_exec($ch);
           rewind($verbose);
           $verboseLog = stream_get_contents($verbose);
           echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
           debug --------------------------------------------*/
            if ($code == 200 || $code== 400) break;/*IMPORTANT*/
            else {
                $issue_array['access_token'] = $CI->bb_shared->requestFromServer();
            }
        }
        if (isset($response)) {
            if (($reply_array = json_decode($response, true)) != null) {
                if (!isset($reply_array['error'])) {
                    return $reply_array["id"];
                }
            }
        }
        return null;
    }

}