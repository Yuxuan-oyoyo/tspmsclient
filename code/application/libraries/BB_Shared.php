<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/4/2015
 * Time: 2:00 PM
 */
class BB_Shared {
    public  $oauth_endpoint = 'https://bitbucket.org/site/oauth2/access_token';
    public function getDefaultOauthToken(){
        $r = new HttpRequest($this->oauth_endpoint, HttpRequest::METH_POST);
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
        } catch (HttpException $ex) {
            echo $ex;
        }
        return null;
    }

}