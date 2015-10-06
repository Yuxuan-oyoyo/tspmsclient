<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Project
 *
 * @author WANG Tiantong
 */
class Projects extends CI_Controller {
    //put your code here
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("Project_model");
        $this->load->model("Customer_model");
        $this->load->model("Project_phase_model");
    }

    public function index()
    {
        //This is for testing project_update, please comment the next line and uncomment next next line
        //$this->view_upadtes(123);
        $this->list_all();
    }

    public function list_all($include_hidden=false){
        if($include_hidden=="include_hidden"){
            $projects = $this->Project_model->retrieveAll(false);
        }else {
            $projects = $this->Project_model->retrieveAll();
        }
        $data = array("projects"=> $projects);

        $this->load->view('project/project_all',$data);
    }
    public function insert(){
        //$this->load->library('input');
        //customer id?
        /*
        $data = array(
            'customer_id' =>$this->input->get("customer_id"),
            'project_title' => $this->input->get("project_title"),
            'project_description' => $this->input->get("project_description"),
            'tags' => $this->input->get("tags"),
            'remarks' => $this->input->get("remarks"),
            'file_repo_name' => $this->input->get("file_repo_name"),
            'no_of_use_cases' =>$this->input->get("no_of_use_cases"),
            'bitbucket_repo_name' => $this->input->get("bitbucket_repo_name"),
            'project_value' => $this->input->get("project_value"),
            'start_time' => (new \DateTime())->format('Y-m-d H:i:s'),
            'last_updated' => (new \DateTime())->format('Y-m-d H:i:s'),
            'current_project_phase_id' => 1
        );
        */
        $data = array(
            'phase_id' =>$this->input->get("customer_id"),
            'project_name' => $this->input->get("project_title")
        );
        /*
        $update_array["customer_id"]=$this->input->get("customer_id");
        $update_array["project_title"]=$this->input->get("project_title");
        $update_array["project_description"]=$this->input->get("project_description");
        $update_array["tags"]=$this->input->get("tags");
        $update_array["remarks"]=$this->input->get("remarks");
        $update_array["file_repo_name"]=$this->input->get("file_repo_name");
        $update_array["no_of_use_cases"]=$this->input->get("no_of_use_cases");
        $update_array["bitbucket_repo_name"]=$this->input->get("bitbucket_repo_name");
        $update_array["project_value"]=$this->input->get("project_value");

        $update_array["start_time"]=(new \DateTime())->format('Y-m-d H:i:s');
        $update_array["last_updated"]=(new \DateTime())->format('Y-m-d H:i:s');
        $update_array["current_project_phase_id"]=1;
        */
        //echo var_dump($update_array);
        $this->Project_model->insert($data);
        //$this->db->_error_message();
        //echo $affected_rows;
        return $this->db->insert_id();
    }

    public function create_new_project(){
        $project_id =$this->insert();
        for ($p = 1; $p <= 5; $p++) {
            $update_array["project_id"]=$project_id;
            $update_array["phase_id"]=$p;
            $update_array["last_updated"]=(new \DateTime())->format('Y-m-d H:i:s');
            $this->Project_phase_model->insert($update_array);
        }
    }
    public function add(){
        $this->load->view('project/project_add');
    }
    /*
    public function close($project_id){
        $update_array["project_id"]=$project_id;
        $update_array["is_ongoing"]=0;
        $affected_rows = $this->Project_model->update($update_array);
        $this->load->view('project/project_details',$data=["project"=>$this->Project_model->retrieve($project_id)]);
    }
     */
    /*changed function name to edit*/
    public function edit($project_id){
        $this->load->view('project/edit',
            $data=["project"=>$this->Project_model->retrieve_by_id($project_id),
                "customers"=>$this->Customer_model->retrieveAll(),
                "tags"=>json_encode($this->Project_model->getTags()),
                "phases"=>$this->Project_phase_model->retrievePhaseDef()
            ]);
    }
    /*changed function name to process_edit*/
    public function process_edit($project_id){
        //TODO: edit title and username/password
        $original_array = $this->Project_model->retrieve_by_id($project_id);
        $name_array = ["c_id","project_title"
            ,"project_description","tags","remarks"
            ,"file_repo_name","no_of_use_cases"
            ,"bitbucket_repo_name","project_value"];
        $input = $this->input->post($name_array,true);
        if($input['c_id']==null){
            $customer_name_array=["title","first_name"
                ,"last_name","company_name","hp_number"
                ,"other_number","email","username","password"];

            $new_customer_input = $this->input->post($customer_name_array,true);
            $new_customer_id = $this->Customer_model->insert($new_customer_input);
            if($new_customer_id==false){
                echo "something wrong happen when creating customer";
            }else{
                $input['c_id'] = $new_customer_id;
            }
        }

        foreach($input as $key=>$value){
            if($value!=null){
                $original_array[$key] = $value;
            }
        }

        $affected_rows = $this->Project_model->update($original_array);
        $this->edit($original_array["project_id"]);
        //TODO:input validation
        //TODO:prompt user on success/failure
    }
    public function project_by_id($project_id){
        //TODO: edit title and username/password
        $this->load->view('project/project_details',$data=["project"=>$this->Project_model->retrieve_by_id($project_id)]);
    }

    public function project_update($project_id){
        $this->load->view('project/project_update',$data=["project"=>$this->Project_model->retrieve_by_id($project_id)]);
    }

    public function _set_validation_rules_for_new_additional_video_form(){
        $this->form_validation->set_rules('title','Title','trim|required|max_length[512]');
        $this->form_validation->set_rules('embed_code','Embed Code','trim|required');
    }

    public function view_upadtes($project_id){
        $project = $this->Project_model->retrieve_by_id($project_id);
        $current_project_phase_id = $project['current_project_phase_id'];
        $current_phase_array = $this->Project_phase_model->retrieve_phase_by_id($current_project_phase_id);
        $current_phase = $current_phase_array['phase_id'];
        $this->load->view('project/project_update',$data=["project"=>$project,"current_phase"=>$current_phase,"current_project_phase_id"=>$current_project_phase_id]);
    }
}
