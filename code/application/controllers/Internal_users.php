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
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->model("Internal_user_model");
        // $this->load->model('User_log_model');
    }

    public function index() {
        $this->list_all();
    }

    public function list_all(){

        $users = $this->Internal_user_model->retrieveAll();

        $data["users"] = $users;

        $this->load->view('internal_user/all_users',$data);
    }
    public function insert(){
        //$this->load->library('input');
        $this->form_validation->set_rules('username','Username','trim|required|max_length[512]|is_unique[internal_user.username]');

        if ($this->form_validation->run()) {
            $update_array["name"] = $this->input->post("name");
            $update_array["username"] = $this->input->post("username");
            $update_array["bb_username"] = $this->input->post("bb_username");
            $update_array["type"] = $this->input->post("type");
            $update_array["is_active"] = 1;
            $update_array["password_hash"] = password_hash($this->input->post("password"), PASSWORD_DEFAULT);
            //echo var_dump($update_array);
            $affected_rows = $this->Internal_user_model->insert($update_array);
            if($affected_rows==1){
                $this->session->userdata('message','new user added successfully');
                redirect('internal_users/list_all');
            }else{
                $this->session->userdata('message','cannot create new user.');
                $this->load->view('internal_user/new_user');
            }
            //echo $affected_rows;
        }else{
            $this->load->view('internal_user/new_user');
        }

    }
    public function add(){
        $this->load->view('internal_user/new_user');
    }

    public function update_password($cid){
        //$this->load->library('encrypt');
        $update_array["c_id"] = $cid;
        $update_array["password_hash"]=password_hash($this->input->post("password"),PASSWORD_DEFAULT);
        $affected_rows = $this->Internal_user_model->update($update_array);
        echo $affected_rows;
    }
    public function edit($id){
        //$this->load->library('input');
        $update_array["name"]=$this->input->post("name");
        $update_array["username"]=$this->input->post("username");
        $update_array["bb_username"]=$this->input->post("bb_username");
        $update_array["type"]=$this->input->post("type");
        $update_array["is_active"]=$this->input->post("is_active");
        $update_array["password_hash"]=password_hash($this->input->post("password"),PASSWORD_DEFAULT);
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