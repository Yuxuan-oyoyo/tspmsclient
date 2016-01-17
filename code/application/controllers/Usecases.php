<?php
/**
 * Created by PhpStorm.
 * User: 123
 * Date: 2016-01-01
 * Time: 9:40 PM
 */
class Usecases extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper("date");
        $this->load->model("Use_case_model");
        $this->load->model("Project_model");

    }

    public function list_all($project_id){
        if($this->session->userdata('internal_uid')) {
           $usecases = $this->Use_case_model->retrieve_by_project_id($project_id);

            $this->load->view('project/usecases', $data=[
                "project" => $this->Project_model->retrieve_by_id($project_id),
                "usecases"=>$usecases,

            ]);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }

    public function customer_usecases($project_id){
        if($this->session->userdata('Customer_cid')) {
            $usecases = $this->Use_case_model->retrieve_external_by_project_id($project_id);

            $this->load->view('project/customer_usecases', $data=[
                "project" => $this->Project_model->retrieve_by_id($project_id),
                "usecases"=>$usecases,

            ]);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/customer_authentication/login/');
        }
    }

    public function delete_usecase($uc_id){
        if($this->session->userdata('internal_uid')) {
            $usecase = $this->Use_case_model->retrieve_by_id($uc_id);
            $this->Use_case_model->delete_usecase($uc_id);
            $this->session->set_userdata('message', 'Use Case deleted successfully.');
            redirect('Usecases/list_all/'.$usecase['project_id']);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }

    public function new_use_case($project_id){
        if($this->session->userdata('internal_uid')) {
            if($this->input->post("submit")) {
                $insert_array = array(
                    'project_id' => $project_id,
                    'sub_id' => ($this->Use_case_model->get_sub_id($project_id) + 1),
                    'title' => $this->input->post("title"),
                    'flow' => $this->input->post("flow"),
                    'importance' => $this->input->post("importance"),
                    'stakeholders' => $this->input->post("stakeholders"),
                    'type' => $this->input->post("type"),
                );
                if ($this->Use_case_model->insert($insert_array) > 0) {
                    $this->session->set_userdata('message', 'New use case has been created successfully.');
                    redirect('usecases/list_all/' . $project_id);
                } else {
                    $this->session->set_userdata('message', 'Cannot create new usecase,please contact administrator.');
                    $this->load->view('project/use_case_new', $data = ["project" => $this->Project_model->retrieve_by_id($project_id)]);
                }
            }else{
                $this->load->view('project/use_case_new', $data = ["project" => $this->Project_model->retrieve_by_id($project_id)]);
            }

        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }

    public function edit_use_case($uc_id){
        if($this->session->userdata('internal_uid')) {
            $usecase = $this->Use_case_model->retrieve_by_id($uc_id);
                if ($this->input->post("submit")) {
                    $usecase['title'] = $this->input->post("title");
                    $usecase['flow'] = $this->input->post("flow");
                    $usecase['importance'] = $this->input->post("importance");
                    $usecase['stakeholders'] = $this->input->post("stakeholders");
                    $usecase['type'] = $this->input->post("type");

                    if ($this->Use_case_model->update($usecase) > 0) {
                        $this->session->set_userdata('message', 'Use case has been changed successfully.');
                        redirect('usecases/list_all/' . $usecase['project_id']);
                    } else {
                        $this->session->set_userdata('message', 'Cannot edit usecase,please contact administrator.');
                        $this->load->view('project/use_case_edit', $data = ["usecase" => $usecase,
                            "project" => $this->Project_model->retrieve_by_id($usecase['project_id'])
                        ]);
                    }
                } else {
                    $this->load->view('project/use_case_edit',$data = ["usecase" => $usecase,
                        "project" => $this->Project_model->retrieve_by_id($usecase['project_id'])
                    ]);
                }

        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }
}