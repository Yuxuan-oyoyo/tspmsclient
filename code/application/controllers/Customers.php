<?php

class Customers extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("Customer_model");
       // $this->load->model('User_log_model');
    }

    public function index()
    {
        $this->list_all();
    }

    public function list_all($include_hidden=false){
        if($include_hidden=="include_hidden"){
            $customers = $this->Customer_model->retrieveAll(false);
            $data["show_all"] = false;
        }else {
            $customers = $this->Customer_model->retrieveAll();
            $data["show_all"] = true;
        }
        $data["customers"]= $customers;
        $this->load->view('customer/customer_all',$data);
    }

    public function list_one($c_id){
            $customers = $this->Customer_model->retrieve($c_id);
            $data["show_all"] = true;
        $data["customers"]= $customers;

        $this->load->view('customer/customer_all',$data);
    }
    public function insert(){
        //$this->load->library('input');
        //$this->load->library('encrypt');

        $update_array["first_name"]=$this->input->get("first_name");
        $update_array["last_name"]=$this->input->get("last_name");
        $update_array["company_name"]=$this->input->get("company_name");
        $update_array["email"]=$this->input->get("email");
        $update_array["hp_number"]=$this->input->get("hp_number");
        $update_array["other_number"]=(trim($this->input->get("other_number"))!="-")?
            ($this->input->get("other_number")):null;
        $update_array["username"]=$this->input->get("username");
        $update_array["password_hash"]=password_hash($this->input->get("password"),PASSWORD_DEFAULT);


        $affected_rows = $this->Customer_model->insert($update_array);
        echo $affected_rows;
    }
    public function add(){
        $this->load->view('customer/add');
    }
    public function update_password($cid){
        //$this->load->library('encrypt');
        $update_array["c_id"] = $cid;
        $update_array["password_hash"]=password_hash($this->input->get("password"),PASSWORD_DEFAULT);
        $affected_rows = $this->Customer_model->update($update_array);
        echo $affected_rows;
    }

    public function update_customer($c_id){
        $this->load->view('customer/customer_edit',$data=["customer"=>$this->Customer_model->retrieve($c_id)]);
    }

    public function update_customer_fproject($c_id, $project_id){
        $this->load->view('customer/customer_edit',$data=["customer"=>$this->Customer_model->retrieve($c_id),"project_id"=>$project_id]);
    }

    public function edit($cid){
        //TODO: edit title and username/password
        $update_array = $this->Customer_model->retrieve($cid);
        $update_array["c_id"]=(int)$cid;
        $update_array["title"]=$this->input->post("title");
        $update_array["first_name"]=$this->input->post("first_name");
        $update_array["last_name"]=$this->input->post("last_name");
        $update_array["company_name"]=$this->input->post("company_name");
        $update_array["email"]=$this->input->post("email");
        $update_array["hp_number"]=$this->input->post("hp_number");
        $update_array["other_number"]=(trim($this->input->post("other_number"))!="-")?
            ($this->input->post("other_number")):null;
        $update_array["is_active"]=(int)$this->input->post("status");
        if($this->Customer_model->update($update_array)){
            $this->session->set_userdata('message', 'Customer updated successfully.');
        }else{
            $this->session->set_userdata('message', 'An error occurred, please contact administrator.');
        }
        $this->list_all();
        //echo $affected_rows;
    }
    public function customer($id){
        //TODO: edit title and username/password
        $this->load->view('customer/details',$data=["customer"=>$this->Customer_model->retrieve($id)]);
    }

    //allow customer to edit their own profile
    public function edit_profile(){
        if($this->session->userdata('Customer_cid')) {
            $customer = $this->Customer_model->retrieve($this->session->userdata('Customer_cid'));

            if ($this->input->post('submit')) {
                $customer=$this->_prepare_edit_customer_record_array($customer);
                $this->session->set_userdata('message', 'kk');
                if ($this->Customer_model->update($customer)==1) {
                    $this->session->set_userdata('message', 'Your profile has been updated.');
                    //$this->User_log_model->log_message("Course Participant Record updated|cpid:".$cpid);
                } else {
                    $this->session->set_userdata('message', 'Unable to update profile.');
                }

            }
            $data = array(
                'customer' => $customer
            );
            $this->load->view('customer/edit_profile', $data);
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/customer_authentication/login/');
        }
    }
    public function _prepare_edit_customer_record_array($customer){
        $customer['first_name']=$this->input->post('first_name');
        $customer['last_name']=$this->input->post('last_name');
        $customer['hp_number']=$this->input->post('hp_number');
        $customer['other_number']=$this->input->post('other_number');
        $customer['company_name']=$this->input->post('company_name');
        $customer['email']=$this->input->post('email');
        return $customer;
    }



    public function delete($cid){
        echo $this->Customer_model->delete($cid);
    }

}