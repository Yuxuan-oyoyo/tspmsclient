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
        $this->load->model("Milestone_model");
        $this->load->model("Update_model");
        $this->load->model("Phase_model");
    }

    public function index()
    {
        //This is for testing project_update, please comment the next line and uncomment next next line
        //$this->view_upadtes(2);
        $project = $this->list_all($include_hidden=false);
        /*
        $projects = array();
        foreach($project as $p){
            //phase_name
            $current_project_phase_id = $p['current_project_phase_id'];
            $project_phase = $this->Project_phase_model->retrieve_by_id($current_project_phase_id);
            $phase_id = $project_phase['phase_id'];
            $phase = $this->Phase_model-> retrieve_phase_by_id($phase_id);
            $phase_name = $phase[0]['phase_name'];
            $p["phase_name"] = $phase_name;

            //customer_name
            $c_id = $p['c_id'];
            $customer = $this->Customer_model->retrieve($c_id);
            $first_name = $customer['first_name'];
            $last_name = $customer['last_name'];
            $p["customer_name"] = $first_name.' '.$last_name;

            array_push($projects,$p);
        }
        $data = [
            "projects"=>$projects
        ];
        $this->load->view('project/projects',$data);
        */
        //$this->list_all();
    }

    public function list_all($include_hidden=false){
        $projects = $this->Project_model->retrieve_all_ongoing();
        $this->load->view('project/all_ongoing_projects',$data=array('projects'=>$projects));
    }
    public function list_past_projects($include_hidden=false){
        $projects = $this->Project_model->retrieve_all_past();
        $this->load->view('project/all_past_project',$data=array('projects'=>$projects));
    }
    public function insert($insert_array){
        /*
        $data = array(
            'phase_id' =>$this->input->get("customer_id"),
            'project_name' => $this->input->get("project_title")
        );
        */
        //echo var_dump($update_array);
        $this->Project_model->insert($insert_array);
        return $this->db->insert_id();
        //$this->Project_phase_model->create_phases_upon_new_project($project_id);

    }

    public function create_new_project(){
        $customer_option = $this->input->post("customer_option");
        $c_id = '';
        if($customer_option=="from-existing"){
            $c_id = $this->input->post("c_id");
        }else{
            $new_customer = array(
                'title'=>$this->input->post("title"),
                'first_name'=>$this->input->post("first_name"),
                'last_name'=>$this->input->post("last_name"),
                'company_name'=>$this->input->post("company_name"),
                'email'=>$this->input->post("email"),
                'hp_number'=>$this->input->post("hp_number"),
                'other_number'=>$this->input->post("other_number"),
                'username'=>$this->input->post("username"),
                'password_hash'=> password_hash($this->input->post('password'),PASSWORD_DEFAULT)
            );
            $c_id = $this->Customer_model->insert($new_customer);
        }
        //$current_project_phase_id = $this->Project_phase_model->retrieve_last_project_phase_id()+1;
        $insert_array = array(
            'c_id' =>$c_id,
            'project_title' => $this->input->post("project_title"),
            'project_description' => $this->input->post("project_description"),
            'tags' => $this->input->post("tags"),
            'remarks' => $this->input->post("remarks"),
            'file_repo_name' => $this->input->post("file_repo_name"),
            'staging_link' =>$this->input->post("staging_link"),
            'production_link' =>$this->input->post("production_link"),
            'no_of_use_cases' =>$this->input->post("no_of_use_cases"),
            'bitbucket_repo_name' => $this->input->post("bitbucket_repo_name"),
            'project_value' => $this->input->post("project_value"),
            'current_project_phase_id' => 0
        );
        $project_id = $this->insert($insert_array);
        //echo $project_id;
        //$current_project_phase_id = $this->Project_phase_model->create_phase_upon_new_project($project_id);
        //$this->Project_model->update_new_project_phase_id($project_id, $current_project_phase_id);
        $this->list_all();
    }
    public function add(){
        $this->load->view('project/new_project', $data = ["customers"=>$this->Customer_model->retrieveAll()]);
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
        $this->load->view('project/project_edit',
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
            ,"bitbucket_repo_name","project_value","staging_link","production_link"];
        $input = $this->input->post($name_array,true);
        var_dump($input);
        $customer_option =  $this->input->post('customer-option');
        if($customer_option=='from-existing'){
            $input['c_id'] = $this->input->post('c_id');
        }else{
            $customer_name_array=["title","first_name"
                ,"last_name","company_name","hp_number"
                ,"other_number","email","username","password_hash"];

            $new_customer_input = $this->input->post($customer_name_array,true);
            $new_customer_input['password_hash'] = password_hash($this->input->post('password'),PASSWORD_DEFAULT);
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
        var_dump($original_array);
        if($this->Project_model->update($original_array)==0){
            $this->view_dashboard($project_id);
        }
    }
    public function project_by_id($project_id){
        //TODO: edit title and username/password
        $this->load->view('project/project_details',$data=["project"=>$this->Project_model->retrieve_by_id($project_id),
        ]);
    }
/*
    public function project_update($project_id){
        $this->load->view('project/project_update',$data=["project"=>$this->Project_model->retrieve_by_id($project_id),
        ]);
    }
*/
    public function retrieveDataForProjectUpdatePage($project_id){
        //phase
        $project = $this->Project_model->retrieve_by_id($project_id);
        $current_project_phase_id = $project['current_project_phase_id'];
        $current_project_phase = $this->Project_phase_model->retrieve_by_id($current_project_phase_id);
        $next_phase_id = $current_project_phase['phase_id']+1;
        $next_phase =  $this->Phase_model->retrieve_phase_by_id($next_phase_id);
        $next_phase_name = $next_phase['phase_name'];
        $phases=$this->Project_phase_model->retrieve_by_project_id($project_id);

        //milestones
        $milestones = $this->Milestone_model-> retrieve_by_project_phase_id($project['current_project_phase_id']);

        //updates
        $updates = $this->Update_model-> retrieve_by_project_phase_id($project['current_project_phase_id']);

        $data = [
            "project"=>$project,
            "phases"=>$phases,
            "milestones"=>$milestones,
            "updates"=>$updates,
            "next_phase_name"=>$next_phase_name
        ];
        return $data;
    }

    public function view_dashboard($project_id){
        //phase
        $project = $this->Project_model->retrieve_by_id($project_id);
        $phases=$this->Project_phase_model->retrieve_by_project_id($project_id);
        //customer_name
        $c_id = $project['c_id'];
        $customer = $this->Customer_model->retrieve($c_id);
        $first_name = $customer['first_name'];
        $last_name = $customer['last_name'];
        $customer_name = $first_name.' '.$last_name;
        $data = [
            "project"=>$project,
            "phases"=>$phases,
            "customer"=>$customer
        ];
        $this->load->view('project/project_dashboard',$data);
        //$this->load->view('project/project_update',$data=["project"=>$project,"current_phase"=>$current_phase,"current_project_phase_id"=>$current_project_phase_id]);
    }

    public function view_updates($project_id){
        $data = $this->retrieveDataForProjectUpdatePage($project_id);
        $this->load->view('project/project_update',$data);
        //$this->load->view('project/project_update',$data=["project"=>$project,"current_phase"=>$current_phase,"current_project_phase_id"=>$current_project_phase_id]);
    }

    public function customer_overview($c_id){
        $customer_project = $this->Project_model->retrieve_by_c_id($c_id);
        if($customer_project){
            //customer has more than one project
            if(sizeof($customer_project)>1){
                $this->load->view('project/customer_project_list',$data=["projects"=>$customer_project]);
            }else{
                $this->customer_view($customer_project[0]['project_id']);
            }
        }
    }

    public function customer_view($project_id){
        $project = $this->Project_model->retrieve_by_id($project_id);
        if($project){
            $data=array("project"=>$project,
                    "phases"=>$this->Project_phase_model->retrieve_by_project_id($project['project_id']),
                    "updates"=>$this->Update_model->retrieve_by_project_phase_id($project['current_project_phase_id']),
                    "milestones"=>$this->Milestone_model->retrieve_by_project_phase_id($project['current_project_phase_id'])
            );
            $this->load->view('project/customer_project_dashboard',$data);
        }
    }
}
