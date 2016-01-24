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
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $users = $this->Internal_user_model->retrieveAll();

            $data["users"] = $users;

            $this->load->view('internal_user/all_users', $data);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }
    public function insert(){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
        //$this->load->library('input');
            $this->form_validation->set_rules('username','Username','trim|required|max_length[512]|is_unique[internal_user.username]');

            if ($this->form_validation->run()) {
                $update_array["name"] = $this->input->post("name");
                $update_array["username"] = $this->input->post("username");
                $update_array["bb_username"] = $this->input->post("bb_username");
                $update_array["bb_oauth_key"] = $this->input->post("bb_oauth_key");
                $update_array["bb_oauth_secret"] = $this->input->post("bb_oauth_secret");
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
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }

    }
    public function add(){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $this->load->view('internal_user/new_user');
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }

    public function update_password($cid){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
        //$this->load->library('encrypt');
            $update_array["c_id"] = $cid;
            $update_array["password_hash"]=password_hash($this->input->post("password"),PASSWORD_DEFAULT);
        $affected_rows = $this->Internal_user_model->update($update_array);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }
    public function edit($id){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $update_array=$this->Internal_user_model->retrieve($id);
                if ($this->input->post('submit')) {
                    $update_array["name"]=$this->input->post("name");
                    $update_array["bb_username"]=$this->input->post("bb_username");
                    $update_array["bb_oauth_key"] = $this->input->post("bb_oauth_key");
                    $update_array["bb_oauth_secret"] = $this->input->post("bb_oauth_secret");
                    $update_array["type"]=$this->input->post("type");
                    $update_array["is_active"]=$this->input->post("is_active");
                    if ($this->Internal_user_model->update($update_array)==1) {
                        $this->session->set_userdata('message', 'User profile has been updated.');
                        redirect("internal_users/list_all");
                    } else {
                        $this->session->set_userdata('message', 'Unable to update profile.');
                    }

                }
                $data = array(
                    'user' => $update_array
                );
                $this->load->view('internal_user/edit_user', $data);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }

    }
    public function user($id){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $data["user"]=$this->Internal_user_model->retrieve($id);
            //echo var_dump($id);

            $this->load->view('internal_user/details',$data);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }
    public function delete($cid){
        $this->Internal_user_model->delete($cid);
    }
    public function retrieve_all_pm(){
        return $this->Internal_user_model->retrieve_all_pm();
    }
}