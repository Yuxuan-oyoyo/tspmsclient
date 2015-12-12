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
    public $server_status = [
        "new","open","resolved","on hold","invalid","duplicate","wontfix","closed"
    ];
    public $defined_status=[
        "new","to develop","resolved","to test","invalid","to deploy","wontfix","closed"
    ];

    private function setEndpoint($repo_slug){
        return "https://api.bitbucket.org/1.0/repositories/".BB_ACCOUNT_NAME."/".$repo_slug."/issues";
    }

    /**
     * @param $repo_slug
     * @param $id
     * @param array|null $parameters
     * @return array|null
     */
    public function retrieveIssues($repo_slug, $id, array $parameters=null){
        $CI =& get_instance();
        $CI->load->library('BB_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug);
        if(isset($id)) $endpoint.="/".$id;
        $parameters["access_token"] = $token;
        $parameters["timestamp"] = time();
        $url = $endpoint.'?'.$this->construct_paras($parameters);
        $result =  $this->sendGetRequest($url);
        if(is_null($result)){
            $parameters["access_token"] = $CI->bb_shared->requestFromServer();
            $url = $endpoint.'?'.$this->construct_paras($parameters);
            $result = $this->sendGetRequest($url);
        }
        if(isset($result['issues'])){
            /*when server replies issue list*/
            foreach($result["issues"] as $key=>$issue){
                $result["issues"][$key]["status"] = $this->map_status($issue["status"], false);
            }
            return $result;
        }else{
            /*when server replies single issue*/
            $result["status"] = $this->map_status($result["status"], false);
            return $result;
        }
    }
    private function map_status($status, $fromDefined = true){
        $negate = false;
        $result = false;
        if(substr($status,0,1)=="!"){
            /*temporarily detach the negate sign if any*/
            $status = substr($status,1);$negate = true;
        }
        if($fromDefined) {
            $index = array_search($status, $this->defined_status);
            /*return index or false*/
            if($index!==false) {/*make sure it's indeed false, not 0*/
                $result = $this->server_status[$index];
            }
        }else{/*from server*/
            $index = array_search($status, $this->server_status);
            if($index!==false) {
                $result = $this->defined_status[$index];
            }
        }
        if($negate && $result) return "!".$result;
        else return $result;
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
     * Convert status, return query string
     * @param $parameters
     * @return string query string
     */
    private function construct_paras($parameters){
        if($parameters==false){/*when param is empty*/
            return '';
        }else{
            if(isset($parameters["status"])){
                $parameters["status"] = $this->map_status($parameters["status"], true);
            }
            return http_build_query($parameters);
        }
    }

    /**
     * @param $repo_slug
     * @param array $issue_array
     * @return array reply_issue_array
     */
    public function postNewIssue($repo_slug, array $issue_array){
        return $this->sendIssueRequest($repo_slug,null,$issue_array,"POST");
        //TODO: may need a confirmation
    }
    /**
     * @param $repo_slug
     * @param $id
     * @param array $issue_array this can be the same as array in post new
     *        issue, and can also be incomplete
     * @return array reply_issue_array
     */
    public function updateIssue($repo_slug, $id, array $issue_array){
        return $this->sendIssueRequest($repo_slug,$id,$issue_array,"PUT");
    }
    public function getCommentsForIssue($repo_slug, $issue_id){
        /*settle the access token and etc*/
        $CI =& get_instance();
        $CI->load->library('BB_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug).'/'.$issue_id.'/comments';
        $parameters["access_token"] = $token;
        $parameters["timestamp"] = time();
        /*construct endpoint*/
        $url = $endpoint.'?'.$this->construct_paras($parameters);
        $result =  $this->sendGetRequest($url);
        if(is_null($result)){
            $parameters["access_token"] = $CI->bb_shared->requestFromServer();
            $url = $endpoint.'?'.$this->construct_paras($parameters);
            $result = $this->sendGetRequest($url);
        }
        //var_dump($result);
        return $result;
    }
    public function postCommentForIssue($repo_slug, $issue_id, $comment, $comment_id=null){
        $method = isset($comment_id)? "PUT":"POST";
        $CI =& get_instance();
        $CI->load->library('BB_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug).'/'.$issue_id.'/comments';
        if($method=="PUT") $endpoint .="/".$comment_id;
        $_trial = 2;
        $data = [
            'access_token'=>$token,
            'content'=>$comment
        ];
        while($_trial>=0) {
            $_trial -= 1;/*IMPORTANT*/
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);

            if ($method == 'POST')
                curl_setopt($ch, CURLOPT_POST, TRUE);
            else
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
            $response = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($code==200) break;/*IMPORTANT*/
            else{
                $issue_array['access_token'] = $CI->bb_shared->requestFromServer();
            }
        }
        //var_dump($response);
        if(($reply_array = json_decode($response,true))!=null){
            if(isset($reply_array['error'])){
                if($this->_print_err) echo var_dump($reply_array);
            }else{
                return $reply_array;
            }
        }
        return null;

    }
    public function deleteCommentForIssue($repo_slug, $issue_id,  $comment_id){
        $CI =& get_instance();
        $CI->load->library('BB_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug).'/'.$issue_id.'/comments/'.$comment_id;
        $data = [
            'access_token'=>$token
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_exec($ch);
        curl_close($ch);
    }
    private function sendIssueRequest($repo_slug,$id, $issue_array, $_flag='POST'){
        $CI =& get_instance();
        $CI->load->library('BB_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug);
        if(isset($id)){
            $endpoint =$endpoint."/".$id;
        }
        if(isset($issue_array["status"])){
            $issue_array["status"] = $this->map_status($issue_array["status"], true);
        }
        $_trial = 2;
        $issue_array['access_token'] = $token;

        while($_trial>=0) {
            $_trial -= 1;/*IMPORTANT*/
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);

            if ($_flag == 'POST')
                curl_setopt($ch, CURLOPT_POST, TRUE);
            else if ($_flag == 'PUT')
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($issue_array));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
            /*debug-------------------------------------------
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $verbose = fopen('php://temp', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);
            $response = curl_exec($ch);
            rewind($verbose);
            $verboseLog = stream_get_contents($verbose);
            echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
            debug --------------------------------------------*/
            /*process response*/
            $response = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($code==200) break;/*IMPORTANT*/
            else{
                $issue_array['access_token'] = $CI->bb_shared->requestFromServer();
            }
        }
        //var_dump($response);
        if(($reply_array = json_decode($response,true))!=null){
            if(isset($reply_array['error'])){
                if($this->_print_err) echo var_dump($reply_array);
            }else{
                $reply_array["status"] = $this->map_status($reply_array["status"], false);
                return $reply_array;
            }
        }
        return null;

    }

}