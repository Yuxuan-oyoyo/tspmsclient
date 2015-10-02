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
        }else {
            $customers = $this->Customer_model->retrieveAll();
        }
        $data = array("customers"=> $customers);

        $this->load->view('customer/all',$data);
    }
    public function insert(){
        //$this->load->library('input');
        $update_array["first_name"]=$this->input->get("first_name");
        $update_array["last_name"]=$this->input->get("last_name");
        $update_array["company_name"]=$this->input->get("company_name");
        $update_array["email"]=$this->input->get("email");
        $update_array["hp_number"]=$this->input->get("hp_number");
        $update_array["other_number"]=(trim($this->input->get("other_number"))!="-")?
            ($this->input->get("other_number")):null;
        //echo var_dump($update_array);
        $affected_rows = $this->Customer_model->insert($update_array);
        echo $affected_rows;
    }
    public function add(){
        $this->load->view('customer/add');
    }
    public function edit($cid){
        //TODO: edit title and username/password
        $update_array["c_id"]=$cid;
        $update_array["first_name"]=$this->input->get("first_name");
        $update_array["last_name"]=$this->input->get("last_name");
        $update_array["company_name"]=$this->input->get("company_name");
        $update_array["email"]=$this->input->get("email");
        $update_array["hp_number"]=$this->input->get("hp_number");
        $update_array["other_number"]=(trim($this->input->get("other_number"))!="-")?
            ($this->input->get("other_number")):null;
        //echo var_dump($update_array);
        $affected_rows = $this->Customer_model->update($update_array);
        echo $affected_rows;
    }
    public function customer($id){
        //TODO: edit title and username/password
        $this->load->view('customer/details',$data=["customer"=>$this->Customer_model->retrieve($id)]);
    }

    public function delete($cid){
        echo $this->Customer_model->delete($cid);
    }

}