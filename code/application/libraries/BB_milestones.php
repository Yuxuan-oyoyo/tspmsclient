<?php

/**
 *
 * Normal milestone {"id":bb_m_id,"name":db_m_id}
 * Unused milestone {"id":bb_m_id,"name":"-".db_m_id}
 *
 *
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

        if($result===null){
            $parameters["access_token"] = $CI->bb_shared->requestFromServer();
            $url = $endpoint.'?'.http_build_query($parameters);
            $result = $this->sendGetRequest($url);
        }
        //$result is now set
        if($result!==null) {
            //this means to only keep milestones that have not been deleted
            $valid_milestones = [];
            foreach ($result as $m) {
                if (isset($m["name"][0]) && $m["name"][0] != "-") {
                    array_push($valid_milestones, $m);
                }
            }
            return $valid_milestones;
        }
        return null;
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
        if($result===null){
            $parameters["access_token"] = $CI->bb_shared->requestFromServer();
            $result = $this->sendGetRequest($endpoint);
        }
        //this means the milestone has been deleted
        if(isset($result["name"][0]) && $result["name"][0]=="-"){
            return null;
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
        if($code==200 && ($reply_array = json_decode($response,true))!==null){
            if(!isset($reply_array['error'])){
                return $reply_array;
            }else{
                print_r($reply_array);
            }
        }else{
            //die(print_r($response));
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
                $data['access_token'] = $CI->bb_shared->requestFromServer();
            }
        }
        if (isset($response)) {
            $reply_array = json_decode($response, true);
            if (!$reply_array===null) {
                if (isset($reply_array['id'])) {
                    return $reply_array["id"];
                }
            }else{
                //die("Repository \"$repo_slug\" is not accessible on BitBucket.
                //Please make sure it's a valid repository name");
            }
        }
        return null;
    }
    public function deleteMilestone($repo_slug, $id){
        $CI =& get_instance();
        $CI->load->library('BB_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug)."/".$id;
        $parameters["access_token"] = $token;
        $parameters["name"]="-".substr(base64_encode(rand ( 0 , 1000000)),0,10);
        $_trial = 1;
        while ($_trial > 0) {
            $_trial -= 1;/*IMPORTANT*/
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
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
            if ($code <300) return true;/*IMPORTANT*/
            elseif($code >400 && $code< 404) {
                $data['access_token'] = $CI->bb_shared->requestFromServer();
            }
        }
        return null;
    }

}