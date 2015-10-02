<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/25/2015
 * Time: 10:00 AM
 */
class Internal_users extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("Internal_user_model");
        // $this->load->model('User_log_model');
    }

    public function index() {
        $this->list_all();
    }

    public function list_all($include_hidden=false){
        if($include_hidden=="include_hidden"){
            $users = $this->Internal_user_model->retrieveAll(false);
        }else {
            $users = $this->Internal_user_model->retrieveAll();
        }
        $data = array("users"=> $users);

        $this->load->view('internal_user/all',$data);
    }
    public function insert(){
        //$this->load->library('input');
        $update_array["name"]=$this->input->get("name");
        $update_array["username"]=$this->input->get("username");
        $update_array["bb_username"]=$this->input->get("bb_username");
        $update_array["type"]=$this->input->get("type");
        //echo var_dump($update_array);
        $affected_rows = $this->Internal_user_model->insert($update_array);
        echo $affected_rows;
    }
    public function add(){
        $this->load->view('internal_user/add');
    }
    public function edit($cid){
        //$this->load->library('input');
        $update_array["c_id"]=$cid;
        $update_array["first_name"]=$this->input->get("first_name");
        $update_array["last_name"]=$this->input->get("last_name");
        $update_array["company_name"]=$this->input->get("company_name");
        $update_array["email"]=$this->input->get("email");
        $update_array["hp_number"]=$this->input->get("hp_number");
        $update_array["other_number"]=(trim($this->input->get("other_number"))!="-")?
            ($this->input->get("other_number")):null;
        //echo var_dump($update_array);
        $affected_rows = $this->Internal_user_model->update($update_array);
        echo $affected_rows;
    }
    public function user($id){
        $this->load->view('internal_user/details',$data=["user"=>$this->Internal_user_model->retrieve($id)]);
    }
    public function delete($cid){
        $this->Internal_user_model->delete($cid);
    }
}