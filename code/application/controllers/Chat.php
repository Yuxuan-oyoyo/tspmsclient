<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/28/2015
 * Time: 1:21 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->model('Customer_model');
    }

    public function json_chat(){
        $var = $this->input->get("hhh",true);
        $arr = [];
        $json = json_encode($arr);
        echo $json;
    }
}