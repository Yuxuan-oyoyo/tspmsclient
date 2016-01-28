<?php
/**
 * Created by PhpStorm.
 * User: yuanyuxuan
 * Date: 28/1/16
 * Time: 3:59 PM
 */

class Historical extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("Project_model");
        // $this->load->model('User_log_model');
    }

    public function index()
    {
        $this->historical();
    }

    public function historical(){

        $projects = $this->Project_model->retrieve_all_past();

        $data["projects"]= $projects;

        $this->load->view('Historical/Historical_Report',$data);
    }
}