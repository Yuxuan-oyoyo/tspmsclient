<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/4/2015
 * Time: 2:00 PM
 */
class BB_Shared {
    public  $oauth_endpoint = 'https://bitbucket.org/site/oauth2/access_token';
    public  $file_path = "application/libraries/oauth_token.txt";
    //public  $_save_in_file = true;

    /**
     * @param int|null $user_id
     * @return string oauth_key
     */

    public function getDefaultOauthToken($user_id=null){
        $CI =& get_instance();
        $CI->load->model('Oauth_token_model');
        if($user_id===null){
            $user_id = $CI->session->userdata('internal_uid');
        }
        //var_dump($user_id);
        $CI->load->library('session');
        $key_array = $CI->Oauth_token_model->retrieve($user_id);
        if(isset($key_array) && $key_array["ttl"]>time()) return $key_array["token"];
        $fresh_token = $this->requestFromServer($user_id);
        return $fresh_token;
    }

    /**
     * @param string|null $user_id
     * @return null
     */
    public function requestFromServer($user_id = null){
        /*get user's bb_oauth_key and secret*/
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->load->model('Oauth_token_model');
        $CI->load->model('Internal_user_model');
        if($user_id==null) {
            $user_id = $CI->session->userdata('internal_uid');
        }
        $user = $CI->Internal_user_model->retrieve($user_id);
        if(!isset($user['bb_oauth_key'])|| !isset($user['bb_oauth_secret'])){
            die("User's Bitbucket authentication key or secret is not set yet.".
                PHP_EOL."Please set it up on user management page");
        }
        /*open connection*/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$this->oauth_endpoint);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_USERPWD, $user['bb_oauth_key'].':'.$user['bb_oauth_secret']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['grant_type'=>'client_credentials']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        /*debug-------------------------------------------
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);
        $response = curl_exec($ch);
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
        debug --------------------------------------------*/
        $response = curl_exec($ch);
        if($response ==false){
            die( curl_error($ch));
        }

        /*process response*/
        if (($response_array = json_decode($response, true)) != null) {
           // echo var_dump($response_array);
            if (isset($response_array["access_token"])) {
                $token = $response_array["access_token"];
                $ttl = $response_array["expires_in"];
                $CI->Oauth_token_model->update($user_id,$token, time()+$ttl-60);
                return $token;
            }else{
                die($response."</br>"."Please make sure to provide valid OAuth key and secret.<br>
            <i>To check it, please go to the setting penal of the internal user on BitBucket.
            Find Access Management -> OAuth -> OAuth consumer-> Add consumer -> give it full permission.
            Update the key and secret in internal user panel of TSPMS</i><br>
            Make sure the member himself/herself has right to access issues");
            }
        }else{
            die("Error in bitbucket authentication ".$response);
        }
    }
    public function validate_repo_name_with_bb($repo_name=null){
        if(!isset($repo_name)) return false;
        $CI =& get_instance();
        $CI->load->library('BB_issues');
        $issue = $CI->bb_issues->retrieveIssues($repo_name, null, null, false);
        return isset($issue)? true:false;
    }

}