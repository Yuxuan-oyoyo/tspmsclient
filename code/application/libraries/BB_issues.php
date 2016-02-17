<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/4/2015
 * Time: 2:00 PM
 */
class BB_issues {
    //public  $oauth_endpoint = 'https://bitbucket.org/site/oauth2/access_token';

    public $_print_err = false;
    public $server_status = [
        "new","open","resolved","on hold","invalid","duplicate","wontfix","closed"
    ];
    public $workflow_status = [
        'to develop', 'to verify', 'to test', 'ready for deployment', 'to deploy'
    ];

    private function setEndpoint($repo_slug){
        return "https://api.bitbucket.org/1.0/repositories/".BB_ACCOUNT_NAME."/".$repo_slug."/issues";
    }

    private function encode_attr_into_content($issue_array){
        /*use xml to encode deadline, usecase, content (description) into content*/
        if(isset($issue_array["content"])){
            $text = "<content>".$issue_array["content"]."</content>";
            if(isset($issue_array["deadline"])){
                $text .="<deadline>".$issue_array["deadline"]."</deadline>";
                unset($issue_array["deadline"]);
            }
            if(isset($issue_array["usecase"])){
                $text .="<usecase>".$issue_array["usecase"]."</usecase>";
                unset($issue_array["usecase"]);
            }
            $issue_array["content"] = $text;
        }
        return $issue_array;
    }


    /**
     * This function extracts deadline, usecase from xml encoded issue content field
     * into separate attributes
     * @param $issue_array
     * @return $issue_array with deadline, usecase fields
     */
    private function decode_attr_from_content($issue_array){
        $new_array = $issue_array;
        $display1 = $display2 = $display3  = [];
        if(isset($issue_array["content"])){
            if(preg_match("/\<deadline\>(.*?)\<\/deadline\>/",$issue_array["content"],$display1)){
                $new_array["deadline"] = $display1[1];
            }
            if(preg_match("/\<usecase\>(.*?)\<\/usecase\>/",$issue_array["content"],$display2)){
                $new_array["usecase"] = $display2[1];
            }
            if(preg_match("/\<content\>((.|\n)*?)\<\/content\>/",$issue_array["content"],$display3)){
                $new_array["content"] = $display3[1];
            }
            return $new_array;
        }
        return $issue_array;
    }
    /**
     * This function extracts workflow statuses from issue title.
     * The status must be marked up with square brackets at the beginning of the title text
     * @param $issue_array
     */
    private function decode_workflow_status_from_title($issue_array){
        $new_array = $issue_array;
        $new_array["workflow"] = "";
        if(isset($issue_array["title"])){
            if(preg_match("/^\s*\[(.*?)\]/",$issue_array["title"],$display)){
                $new_array["workflow"] = $display[1];
                $new_array["title"] = explode($display[0], $issue_array["title"])[1];
            }
            return $new_array;
        }
        return $issue_array;
    }
    private function encode_workflow_status_into_title($issue_array){
        //use in_array here instead to bypass issue title is empty
        if(isset($issue_array["title"])&& isset($issue_array["workflow"])){
            if($issue_array["workflow"]!="default workflow"){
                $issue_array["title"] = "[".$issue_array["workflow"]."]".$issue_array["title"];
            }
            unset($issue_array["workflow"]);
        }
        return $issue_array;
    }
    /**
     * @param $repo_slug
     * @param $id
     * @param array|null $parameters
     * @param boolean $try_twice
     * @return array|null
     */
    public function retrieveIssues($repo_slug, $id, array $parameters=null, $try_twice = true){
        $CI =& get_instance();
        $CI->load->library('BB_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug);
        if(isset($id)) $endpoint.="/".$id;
        $parameters["access_token"] = $token;
        $parameters["timestamp"] = time();
        $url = $endpoint.'?'.$this->construct_paras($parameters);
        $result =  $this->sendGetRequest($url);
        if($result===null && $try_twice){
            $parameters["access_token"] = $CI->bb_shared->requestFromServer();
            $url = $endpoint.'?'.$this->construct_paras($parameters);
            $result = $this->sendGetRequest($url);
        }
        if(isset($result)) {
            if (isset($result['issues'])) {
                /*when server replies issue list*/
                foreach ($result["issues"] as $key => $issue) {
                    $result["issues"][$key] = $this->decode_attr_from_content($issue);
                    $result["issues"][$key] = $this->decode_workflow_status_from_title($result["issues"][$key]);
                }
                return $result;
            } else {
                /*when server replies single issue*/
                $result_decoded = $this->decode_attr_from_content($result);
                $result_decoded = $this->decode_workflow_status_from_title($result_decoded);
                return $result_decoded;
            }
        }else{
            return null;
        }
    }

    /*
     * [deprecated] This function two-way convert server-defined statuses
     * with customized statuses in status field
     * @param $status
     * @param bool|true $fromLocal
     * @return bool|string
     */
    /*
    private function map_status($status, $fromLocal = true){
        $negate = false;
        $result = false;
        if(substr($status,0,1)=="!"){
            /*temporarily detach the negate sign if any/
            $status = substr($status,1);$negate = true;
        }
        if($fromLocal) {
            $index = array_search($status, $this->defined_status);
            /*return index or false/
            if($index!==false) {/*make sure it's indeed false, not 0/
                $result = $this->server_status[$index];
            }
        }else{/*from server/
            $index = array_search($status, $this->server_status);
            if($index!==false) {
                $result = $this->defined_status[$index];
            }
        }
        if($negate && $result) return "!".$result;
        else return $result;
    }
    */


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
            //echo ($response);
            if(isset($reply_array['error'])){
                if($this->_print_err) var_dump($reply_array);
            }else{
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
            /*
            if(isset($parameters["status"])){
                $parameters["status"] = $this->map_status($parameters["status"], true);
            }
            */

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
        if($result===null){
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
        /*debug-------------------------------------------
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $verbose = fopen('php://temp', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);
            $response = curl_exec($ch);
        var_dump($response);
            rewind($verbose);
            $verboseLog = stream_get_contents($verbose);
            echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
            /debug --------------------------------------------*/
        /*process response*/
        curl_close($ch);
        //die();
    }
    private function sendIssueRequest($repo_slug,$id, $issue_array, $_flag='POST'){
        $CI =& get_instance();
        $CI->load->library('BB_shared');
        $token = $CI->bb_shared->getDefaultOauthToken();
        $endpoint = $this->setEndpoint($repo_slug);
        if(isset($id)){
            $endpoint =$endpoint."/".$id;
        }
        $issue_array = $this->encode_attr_into_content($issue_array);
        if(isset($issue_array["workflow"]) && isset($issue_array["title"])){
            $issue_array = $this->encode_workflow_status_into_title($issue_array);
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
        if(isset($response)&&($reply_array = json_decode($response,true))!==null){
            if(isset($reply_array['error'])){
                if($this->_print_err) var_dump($reply_array);
            }else{
                $reply_array = $this->decode_attr_from_content($reply_array);
                $reply_array = $this->decode_workflow_status_from_title($reply_array);
                /*process log  =====*/
                $this->log($reply_array,$repo_slug);
                /*process log  =====*/
                //die();
                return $reply_array;
            }
        }
        return null;

    }
    public function are_statuses_same_type($status1, $status2){

    }
    public function log($issue_array, $repo_slug){
        $ci =&get_instance();
        $ci->load->library('session');
        $ci->load->model('logs/Issue_log_model');
        $ci->load->model("Internal_user_model");
        $workflow = !empty($issue_array["workflow"])?$issue_array["workflow"]:"default";
        $keep_workflow = false;
        $keep_status = false;
        $past_workflow = $ci->Issue_log_model->last_record_workflow($issue_array["local_id"], $repo_slug);
        $past_status = $ci->Issue_log_model->last_record_status($issue_array["local_id"], $repo_slug);
        if(!isset($past_status["status"]) || empty($past_status["status"])
                || $past_status["status"]!=$issue_array["status"]){
            $keep_status = true;
        }
        if(!isset($past_workflow["workflow"]) || empty($past_workflow["workflow"])
                || $past_workflow["workflow"]!=$workflow){
            $keep_workflow = true;
        }
        if($keep_workflow || $keep_status){//there is a need to update db at all
            $user_id = $ci->session->userdata('internal_uid');
            $log_array=[
                "issue_id"=>$issue_array["local_id"],
                "repo_slug"=>$repo_slug,
                "updated_by"=>$user_id,
                "title"=>$issue_array["title"],
                "date_created"=>$issue_array["utc_created_on"]
            ];
            //convert assignee from bb username to id
            $assignee_record = $ci->Internal_user_model->retrieve_by_bb_username($issue_array["responsible"]["username"]);
            if($assignee_record!==null){
                $log_array["assignee"] = $assignee_record["u_id"];
            }
            //write in new status
            if($keep_status){
                $log_array["status"] = $issue_array["status"];
            }
            //write in new workflow
            if($keep_workflow){
                $log_array["workflow"] = $workflow;
            }
            $ci->Issue_log_model->insert($log_array);
        }

    }

}