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
    public  $_save_in_file = true;

    /**
     * default method to retrieve token
     * @return string oauth token
     */
    public function getDefaultOauthToken(){
        if(fopen($this->file_path, "r")!=false && $this->_save_in_file){
            $contents = file_get_contents($this->file_path);
            $ctts_a = explode("\t",$contents);
            if(isset($ctts_a[1])&&$ctts_a[1]>time())  return explode("\t",$contents)[0];
        }
        return $this->requestFromServer();
    }

    /**
     * False refresh token, if called from outside
     * @return string token
     */
    public function requestFromServer(){
        /*open connection*/
        $data = ['grant_type'=>'client_credentials'];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$this->oauth_endpoint);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_USERPWD, BB_OAUTH_KEY.':'.BB_OAUTH_SECRET);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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
            echo curl_error($ch);
            return null;
        }

        /*process response*/
        if (($response_array = json_decode($response, true)) != null) {
           // echo var_dump($response_array);
            if (isset($response_array['error'])) {
            }
            if (isset($response_array["access_token"])) {
                $token = $response_array["access_token"];
                $ttl = $response_array["expires_in"];
                if($this->_save_in_file) $this->writeToFile($token, $ttl);
                return $token;
            }
        }
        return null;
    }
    private  function writeToFile($token, $ttl){
        //TODO: this may not be thread-safe
        $buffer_time = 60;
        file_put_contents($this->file_path, $token."\t".(time()+$ttl-$buffer_time), LOCK_EX);
        flush();
    }

}