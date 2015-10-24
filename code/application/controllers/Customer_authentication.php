<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_authentication extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->model('Customer_model');
    }

    public function index()
    {

    }

    public function login()
    {
//        first, check to see if there is an existing session.
        if($this->session->userdata('Customer_cid')){
            //if there is, log the user out first.
            $this->session->unset_userdata('Customer_cid');
            $this->session->unset_userdata('Customer_username');
            $this->session->sess_destroy();
            $this->session->set_userdata('message','You have been logged out.');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run()) {
            //first, get the staff user object
            if ($user = $this->Customer_model->retrieve_by_username($this->input->post('username'))) {
                //next, match the password hashes
                if ($user['is_active'] == 1) {
                    $this->load->library('encrypt');
                    if (password_verify($this->input->post('password'), $user['password_hash'])) {
                        //set session data
                        $this->session->set_userdata('Customer_cid', $user['c_id']);
                        //redirect to successpage
                         redirect('/projects/customer_overview/'.$user['c_id']);

                    } else {
                        $this->session->set_userdata('message', 'Username/password mismatch.');
                    }
                } else {
                    $this->session->set_userdata('message', 'Your account has been deactivated, please contact admin.');
                }
            } else {
                $this->session->set_userdata('message', 'hello' . $user['username']);
            }
            $this->load->view('customer/login');

        }else
        {
            $this->load->view('customer/login');
        }

    }

/*
    public function change_password(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('existing_password', 'Existing password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm New Password', 'required|matches[new_password]|min_length[6]');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/change_password_form');
        } else {
            $this->load->model('User_model');
            $user = $this->User_model->get_by_username($this->session->userdata('ADMusername'));
            $this->load->library('encrypt');
            if (password_verify($this->input->post('existing_password'),$user['password_hash'])) {
                $new_hash = password_hash($this->input->post('new_password'),PASSWORD_DEFAULT);
                $user['password_hash'] = $new_hash;
                if ($this->User_model->update($user) == 1) {
                    $this->session->set_userdata('message', 'Admin password has been changed successfully.');
                    //logMessage
                    $this->User_log_model->log_message('Admin password has been changed successfully.');

                } else {
                    $this->session->set_userdata('message', 'An error occurred, please try to use a different password set or contact administrator.');
                    //logMessage
                    $this->User_log_model->log_message('An error occurred, please try to use a different password set or contact administrator.');
                }
            } else {
                $this->session->set_userdata('message', 'Old password entered is incorrect');
            }
            redirect('admin/authenticate/change_password');
        }
    }


    public function logout()
    {
        redirect('/admin/authenticate/login/');
    }

    public function start()
    {
        if($this->User_log_model->validate_access("A",$this->session->userdata('ADMaccess'))){
            redirect('/admin/admin/start/');
        }else{

            $this->session->set_userdata('message','This user does not have any valid access rights.');
            redirect('/admin/authenticate/login/');
        }

    }
*/
}