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
            $data["show_all"] = false;
        }else {
            $users = $this->Internal_user_model->retrieveAll();
            $data["show_all"] = true;
        }
        $data["users"] = $users;

        $this->load->view('internal_user/all',$data);
    }
    public function insert(){
        //$this->load->library('input');
        $update_array["name"]=$this->input->post("name");
        $update_array["username"]=$this->input->post("username");
        $update_array["bb_username"]=$this->input->post("bb_username");
        $update_array["type"]=$this->input->post("type");
        $update_array["is_active"]=$this->input->post("is_active");
        $update_array["password_hash"]=password_hash($this->input->post("password"),PASSWORD_DEFAULT);
        //echo var_dump($update_array);
        $affected_rows = $this->Internal_user_model->insert($update_array);
        echo $affected_rows;
    }
    public function add(){
        $this->load->view('internal_user/add');
    }

    public function update_password($cid){
        //$this->load->library('encrypt');
        $update_array["c_id"] = $cid;
        $update_array["password_hash"]=password_hash($this->input->post("password"),PASSWORD_DEFAULT);
        $affected_rows = $this->Internal_user_model->update($update_array);
        echo $affected_rows;
    }
    public function edit($cid){
        //$this->load->library('input');
        $update_array["c_id"]=$cid;
        $update_array["first_name"]=$this->input->post("first_name");
        $update_array["last_name"]=$this->input->post("last_name");
        $update_array["company_name"]=$this->input->post("company_name");
        $update_array["email"]=$this->input->post("email");
        $update_array["hp_number"]=$this->input->post("hp_number");
        $update_array["other_number"]=(trim($this->input->post("other_number"))!="-")?
            ($this->input->post("other_number")):null;
        //echo var_dump($update_array);
        $affected_rows = $this->Internal_user_model->update($update_array);
        echo $affected_rows;
    }
    public function user($id){
        $data["user"]=$this->Internal_user_model->retrieve($id);
        //echo var_dump($id);

        $this->load->view('internal_user/details',$data);
    }
    public function delete($cid){
        $this->Internal_user_model->delete($cid);
    }
}