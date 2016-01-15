<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Internal_authentication extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->model('Internal_user_model');
    }

    public function index()
    {
        $this->login();
    }

    public function login()
    {
//        first, check to see if there is an existing session.
        if($this->session->userdata('internal_uid')||$this->session->userdata('internal_username')||$this->session->userdata('internal_type')){
            //if there is, log the user out first.
            $this->session->unset_userdata('internal_uid');
            $this->session->unset_userdata('internal_username');
            $this->session->unset_userdata('internal_type');
            $this->session->sess_destroy();
            $this->session->set_userdata('message','You have been logged out.');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run()) {
            //first, get the staff user object
            if ($user = $this->Internal_user_model->retrieve_by_username($this->input->post('username'))) {
                //next, match the password hashes
                if ($user['is_active'] == 1) {
                    $this->load->library('encrypt');
                    if (password_verify($this->input->post('password'), $user['password_hash'])) {
                        //set session data
                        $this->session->set_userdata('internal_uid', $user['u_id']);
                        $this->session->set_userdata('internal_username', $user['username']);
                        $this->session->set_userdata('internal_type', $user['type']);
                        //redirect to successpage
                        if($user['type']=='PM') {
                            redirect('/projects/list_all/');
                        }else{
                            redirect('/projects/dev_page');
                        }

                    } else {
                        $this->session->set_userdata('message', 'Username/password mismatch.');
                    }
                } else {
                    $this->session->set_userdata('message', 'Your account has been deactivated, please contact admin.');
                }
            } else {
                $this->session->set_userdata('message', 'Invalid Username/password.');
            }
            $this->load->view('internal_user/login');

        }else
        {
            $this->load->view('internal_user/login');
        }

    }



    public function change_password(){
        if($this->session->userdata('internal_uid')) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('existing_password', 'Existing password', 'required');
            $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm New Password', 'required|matches[new_password]|min_length[6]');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('internal_user/change_password');
            } else {
                $user = $this->Internal_user_model->retrieve_by_username($this->session->userdata('internal_username'));
                $this->load->library('encrypt');
                if (password_verify($this->input->post('existing_password'),$user['password_hash'])) {
                    $new_hash = password_hash($this->input->post('new_password'),PASSWORD_DEFAULT);
                    $user['password_hash'] = $new_hash;
                    if ($this->Internal_user_model->update($user) == 1) {
                        $this->session->set_userdata('message', 'Your password has been changed successfully.');
                        //logMessage
                        //$this->User_log_model->log_message('Admin password has been changed successfully.');
                    } else {
                        $this->session->set_userdata('message', 'An error occurred, please try to use a different password set or contact administrator.');
                        //logMessage
                        // $this->User_log_model->log_message('An error occurred, please try to use a different password set or contact administrator.');
                    }
                } else {
                    $this->session->set_userdata('message', 'Existing password entered incorrectly');
                }
                redirect('internal_authentication/change_password');
            }
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/internal_authentication/login/');
        }
    }

    public function reset_password(){

            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required');
            if ($this->form_validation->run() == FALSE) {
               $this->load->view('internal_user/login');
            } else {
                $email = $this->input->post('email');
                if($this->Internal_user_model->retrieve_by_email($email)!=null){
                    $user = $this->Internal_user_model->retrieve_by_email($email);
                    $this->load->library('encrypt');
                    $this->load->library('email');
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $randomString = '';
                    for ($i = 0; $i < 7; $i++) {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }
                    $this->email->from('donotreply@tspms.com', 'TSPMS');
                    $this->email->to($email);

                    $this->email->subject('Password Reset Email form The Shipyard Project Management System');
                    $this->email->message('Hi. This is your new password for The Shipyard Project Management System: <strong>'.$randomString.'</strong>');

                    if($this->email->send()){
                        $new_hash = password_hash($randomString,PASSWORD_DEFAULT);
                        $user['password_hash'] = $new_hash;
                        if ($this->Internal_user_model->update($user) == 1) {
                            $this->session->set_userdata('message', 'Your new password has been sent to the following email: '.$email.' . Please login with the new password');
                            //logMessage
                            //$this->User_log_model->log_message('Admin password has been changed successfully.');
                        } else {
                            $this->session->set_userdata('message', 'An error occurred, please try to use a different password set or contact administrator.');
                            //logMessage
                            // $this->User_log_model->log_message('An error occurred, please try to use a different password set or contact administrator.');
                        }
                    }else{
                        show_error($this->email->print_debugger());
                        $this->session->set_userdata('message', 'Unable to send email.');
                    }
                }else{
                    $this->session->set_userdata('message','Email does not exist');
                }
                redirect('internal_authentication/login');
            }

    }


        public function logout()
        {
            redirect('/internal_authentication/login/');
        }



}