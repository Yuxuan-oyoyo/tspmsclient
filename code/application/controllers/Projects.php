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
        $this->load->model("Chat_model");
        $this->load->model("Customer_model");
        $this->load->model("Internal_user_model");
        $this->load->model("Project_phase_model");
        $this->load->model("Milestone_model");
        $this->load->model("Update_model");
        $this->load->model("Phase_model");
        $this->load->model("Task_model");
        $this->load->model("Use_case_model");
        $this->load->model("Notification_model");
        $this->load->model("Internal_user_model");

        $this->load->library('BB_issues');
    }

    public function index()
    {

    }

    public function list_all($include_hidden=false){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $projects = $this->Project_model->retrieve_all_ongoing();
            $no_of_issues=[];
            foreach($projects as $p){
                if($p['bitbucket_repo_name']!= null) {
                    if(isset($this->bb_issues->retrieveIssues($p['bitbucket_repo_name'],null)['count'])) {
                        $no_of_issues[$p['project_id']] =$this->bb_issues->retrieveIssues($p['bitbucket_repo_name'],null)['count'] ;
                    }
                }
            }
            $this->load->view('project/pm_all_ongoing_projects',$data=array('projects'=>$projects,'no_of_issues'=>$no_of_issues));
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }
    public function list_past_projects($include_hidden=false){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $projects = $this->Project_model->retrieve_all_past();
            $this->load->view('project/pm_all_past_project',$data=array('projects'=>$projects));
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }
    public function insert($insert_array){
        $this->Project_model->insert($insert_array);
        $new_project_id = $this->db->insert_id();
        $change_type = "New Project Created";
        $redirect = "view_dashboard";
        $users = $this->Internal_user_model->retrieve_all_pm();
        $this->Notification_model->add_new_project_notifications($new_project_id,$change_type,$redirect,$users);
        return $new_project_id;
        //$this->Project_phase_model->create_phases_upon_new_project($project_id);
    }

    public function create_new_project()
    {
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('c_username', 'Customer Username', 'is_unique[customer.username]');
            if ($this->form_validation->run()) {
                $customer_option = $this->input->post("customer_option");
                $c_id = '';
                if ($customer_option == "from-existing") {
                    $c_id = $this->input->post("c_id");
                } else {
                    $new_customer = array(
                        'title' => $this->input->post("title"),
                        'first_name' => $this->input->post("first_name"),
                        'last_name' => $this->input->post("last_name"),
                        'company_name' => $this->input->post("company_name"),
                        'email' => $this->input->post("email"),
                        'hp_number' => $this->input->post("hp_number"),
                        'other_number' => $this->input->post("other_number"),
                        'username' => $this->input->post("c_username"),
                        'password_hash' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
                    );
                    $c_id = $this->Customer_model->insert($new_customer);
                    if ($c_id < 0) {
                        $this->session->set_userdata('message', 'Cannot create new project,please contact administrator.');
                        $this->load->view('project/pm_project_new', $data = ["customers" => $this->Customer_model->retrieveAll()]);
                    }
                }
                //$current_project_phase_id = $this->Project_phase_model->retrieve_last_project_phase_id()+1;
                $insert_array = array(
                    'c_id' => $c_id,
                    'project_title' => $this->input->post("project_title"),
                    'project_description' => $this->input->post("project_description"),
                    'tags' => $this->input->post("tags"),
                    'remarks' => $this->input->post("remarks"),
                    'file_repo_name' => $this->input->post("file_repo_name"),
                    'staging_link' => $this->input->post("staging_link"),
                    'production_link' => $this->input->post("production_link"),
                    'customer_preview_link' => $this->input->post("customer_preview_link"),
                    'bitbucket_repo_name' => $this->input->post("bitbucket_repo_name"),
                    'project_value' => $this->input->post("project_value"),
                    'priority' => $this->input->post("priority"),
                    'current_project_phase_id' => 0,
                    'pm_id'=>$this->input->post("pm_id")
                );
                $initial_chat = [
                    "customer_id" => $c_id,
                    "pm_id" => $this->input->post("pm_id"),
                    "body" => "Hi, I am the project manager for your project [".$this->input->post("project_title")."]. Please contact me if you have any problem."
                ];

                if ($this->insert($insert_array)) {
                    if($this->Chat_model->initialize_new($initial_chat)>0) {
                        $this->session->set_userdata('message', 'New project and chat thread has been created successfully.');
                    }else{
                        $this->session->set_userdata('message', 'New project and  has been created successfully.Error when creating chat thread.');
                    }
                    redirect('projects/list_all');
                } else {
                    $this->session->set_userdata('message', 'Cannot create new project,please contact administrator.');
                    $this->load->view('project/pm_project_new', $data = ["customers" => $this->Customer_model->retrieveAll(),
                        "pms"=>$this->Internal_user_model->retrieve_by_type("PM")

                    ]);
                }

            } else {
                $this->load->view('project/pm_project_new', $data = ["customers" => $this->Customer_model->retrieveAll(),
                    "pms"=>$this->Internal_user_model->retrieve_by_type("PM")
                ]);
            }
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
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
    public function edit($project_id=null){
        if(!isset($project_id)) {show_404();die();}
        if ($this->session->userdata('internal_uid') && $this->session->userdata('internal_type') == "PM") {
            $this->load->library('form_validation');
            $this->load->view('project/pm_project_edit',
                $data = ["project" => $this->Project_model->retrieve_by_id($project_id),
                    "customers" => $this->Customer_model->retrieveAll(),
                    "tags" => json_encode($this->Project_model->getTags()),
                    "phases" => $this->Project_phase_model->retrievePhaseDef(),
                    "pms" => $this->Internal_user_model->retrieve_by_type("PM")
                ]);
        } else {
            $this->session->set_userdata('message', 'You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }
    /*changed function name to process_edit*/
    public function process_edit($project_id=null){
        if(!isset($project_id)) {show_404();die();}
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Customer Username', 'is_unique[customer.username]');
            if ($this->form_validation->run()) {
                $original_array = $this->Project_model->retrieve_by_id($project_id);
                $name_array = ["c_id", "project_title"
                    , "project_description", "tags", "remarks"
                    , "file_repo_name", "priority"
                    , "bitbucket_repo_name", "project_value", "staging_link", "production_link", "customer_preview_link","pm_id"];
                $input = $this->input->post($name_array, true);
                $customer_option = $this->input->post('customer_option');

                if ($customer_option == 'from-existing') {
                    $input['c_id'] = $this->input->post('c_id');
                    if($original_array['c_id']!== $this->input->post('c_id')){
                        $initial_chat = [
                            "customer_id" => $this->input->post('c_id'),
                            "pm_id" => $this->input->post("pm_id"),
                            "body" => "Hi, I am the project manager for your project [".$this->input->post("project_title")."]. Please contact me if you have any problem."
                        ];
                        $this->Chat_model->initialize_new($initial_chat);
                    }
                } else {
                    $customer_name_array = ["title", "first_name"
                        , "last_name", "company_name", "hp_number"
                        , "other_number", "email", "username", "password_hash"];

                    $new_customer_input = $this->input->post($customer_name_array, true);
                    $new_customer_input['password_hash'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                    $new_customer_id = $this->Customer_model->insert($new_customer_input);
                    if ($new_customer_id == false) {
                        echo "something wrong happen when creating customer";
                    } else {
                        $input['c_id'] = $new_customer_id;
                        $initial_chat = [
                            "customer_id" => $new_customer_id,
                            "pm_id" => $this->input->post("pm_id"),
                            "body" => "Hi, I am the project manager for your project [".$this->input->post("project_title")."]. Please contact me if you have any problem."
                        ];
                        $this->Chat_model->initialize_new($initial_chat);
                    }

                }

                foreach ($input as $key => $value) {
                    if ($value != null) {
                        $original_array[$key] = $value;
                    }
                }
                if ($this->Project_model->update($original_array) == 1) {
                    $this->session->set_userdata('message', 'Project has been edited successfully.');
                    $change_type = "Project Details Edited";
                    $redirect = "view_dashboard";
                    $users = $this->Internal_user_model->retrieve_all_pm();
                    $this->Notification_model->add_new_project_notifications($project_id,$change_type,$redirect,$users);
                    redirect('projects/view_dashboard/'.$project_id);
                }else{
                    $this->session->set_userdata('message', 'Cannot edit project,please contact administrator.');
                    $this->edit($project_id);
                }
            }
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }
    public function project_by_id($project_id=null){
        if(!isset($project_id)) {show_404();die();}
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            //TODO: edit title and username/password
            $this->load->view('project/project_details',$data=["project"=>$this->Project_model->retrieve_by_id($project_id),
            ]);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }
/*
    public function project_update($project_id){
        $this->load->view('project/project_update',$data=["project"=>$this->Project_model->retrieve_by_id($project_id),
        ]);
    }
*/
    public function retrieveDataForProjectUpdatePage($project_id=null){
        if(!isset($project_id)) {show_404();die();}
        //phase
        $project = $this->Project_model->retrieve_by_id($project_id);
        $current_project_phase_id = $project['current_project_phase_id'];
        $current_project_phase = $this->Project_phase_model->retrieve_by_id($current_project_phase_id);
        $next_phase_id = $current_project_phase['phase_id']+1;
        $next_phase =  $this->Phase_model->retrieve_phase_by_id($next_phase_id);
        $next_phase_name = $next_phase['phase_name'];
        if($current_project_phase_id==0){
            $next_phase_name = "Lead";
        }
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

    public function view_dashboard($project_id=null){
        if(!isset($project_id)) {show_404();die();}
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            //phase
            $project = $this->Project_model->retrieve_by_id($project_id);
            $phases=$this->Project_phase_model->retrieve_by_project_id($project_id);
            $tasks = $this->Task_model->retrieve_all_uncompleted_by_project_id($project_id);
            if(intval($project['current_project_phase_id'])===0){
                $current_phase_name = "Lead";
            }else{
                if(intval($project['current_project_phase_id'])===-1){
                    $current_phase_name = 'Ended';
                }else{
                    $current_phase_name = $this->Project_phase_model->retrieve_phase_name_by_id($project['current_project_phase_id']);
                    $current_phase_name = $current_phase_name[0]['phase_name'];
                }
            }
            $newTasks = array();
            foreach($tasks as $t){
                $days_left = substr($this->Task_model->get_days_left($t['task_id']),1);
                $t['days_left'] = $days_left;
                array_push($newTasks,$t);
            }
            //customer_name
            $c_id = $project['c_id'];
            $customer = $this->Customer_model->retrieve($c_id);
           $no_of_usecases = $this->Use_case_model->get_no_of_usecase_by_project($project_id);

            $data = [
                "project"=>$project,
                "phases"=>$phases,
                "customer"=>$customer,
                "tasks"=>$newTasks,
                "current_phase_name"=>$current_phase_name,
                "no_of_usecases"=>$no_of_usecases
            ];
            $this->load->view('project/pm_project_dashboard',$data);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
        //$this->load->view('project/project_update',$data=["project"=>$project,"current_phase"=>$current_phase,"current_project_phase_id"=>$current_project_phase_id]);
    }

    public function view_updates($project_id=null){
        if(!isset($project_id)) {show_404();die();}
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $data = $this->retrieveDataForProjectUpdatePage($project_id);
            $this->load->view('project/pm_project_update',$data);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
        //$this->load->view('project/pm_project_update',$data=["project"=>$project,"current_phase"=>$current_phase,"current_project_phase_id"=>$current_project_phase_id]);
    }

    public function customer_overview($c_id){
        if($this->session->userdata('Customer_cid')) {
            $customer_project = $this->Project_model->retrieve_by_c_id($c_id);
            if($customer_project){
                //customer has more than one project
                if(sizeof($customer_project)>1){
                    $this->load->view('project/customer_project_list',$data=["projects"=>$customer_project]);
                }else{
                    $this->customer_view($customer_project[0]['project_id']);
                }
            }
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/customer_authentication/login/');
        }
    }

    public function customer_view($project_id){
        if(!isset($project_id)) {$this->load->view("errors/html/error_404");die();}
        if($this->session->userdata('Customer_cid')) {
            $project = $this->Project_model->retrieve_by_id($project_id);
            if($project){
                $data=array("project"=>$project,
                        "phases"=>$this->Project_phase_model->retrieve_by_project_id($project['project_id']),
                        "updates"=>$this->Update_model->retrieve_by_project_phase_id($project['current_project_phase_id']),
                        "milestones"=>$this->Milestone_model->retrieve_by_project_phase_id($project['current_project_phase_id'])
                );
                $this->load->view('project/customer_project_dashboard',$data);
            }
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/customer_authentication/login/');
        }
    }

    public function dev_page(){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="Developer") {
            $projects=$this->Project_model->retrieve_all_ongoing();
            $no_of_issues=[];
            foreach($projects as $p){
                if($p['bitbucket_repo_name']!= null) {
                    if(isset($this->bb_issues->retrieveIssues($p['bitbucket_repo_name'],null)['count'])) {
                        $no_of_issues[$p['project_id']] =$this->bb_issues->retrieveIssues($p['bitbucket_repo_name'],null)['count'] ;
                    }
                }
            }
            $data["projects"]=$projects;
            $data["no_of_issues"]=$no_of_issues;
            $this->load->view('project/developer_dashboard',$data);//to add developer page
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }

}
