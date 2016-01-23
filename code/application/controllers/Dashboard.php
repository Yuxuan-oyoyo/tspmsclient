<?php
/**
 * Created by PhpStorm.
 * User: 123
 * Date: 2016-01-11
 * Time: 1:26 PM
 */
class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("Task_model");
        // $this->load->model('User_log_model');
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard(){
        $tasks_ui=$this->Task_model->retrieve_for_esenhower(7, -1000,5,3);
        $tasks_i=$this->Task_model->retrieve_for_esenhower(1000, 7,5,3);
        $tasks_u=$this->Task_model->retrieve_for_esenhower(7, -1000,3,0);
        $tasks_none=$this->Task_model->retrieve_for_esenhower(7, 1000,3,0);
        $data["tasks_ui"]= $tasks_ui;
        $data["tasks_i"]= $tasks_i;
        $data["tasks_u"]= $tasks_u;
        $data["tasks_none"]= $tasks_none;
        $this->load->view('dashboard/dashboard',$data);
    }
}