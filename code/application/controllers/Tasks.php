<?php

class Tasks extends CI_Controller{

    public function __construct(){
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper("date");
        $this->load->model("Task_model");;
    }
    public function add_new_task($project_id){
        $insert_task_array['content']=$this->input->post("content");
        $insert_task_array['importance']=$this->input->post("importance");
        $insert_task_array['project_id']=$project_id;

        $targeted_start_datetime=$this->input->post("targeted_start_datetime");
        $targeted_start_datetime = new DateTime($targeted_start_datetime);
        $insert_task_array['targeted_start_datetime'] = $targeted_start_datetime->format('c');
        $targeted_end_datetime=$this->input->post("targeted_end_datetime");
        $targeted_end_datetime = new DateTime($targeted_end_datetime);
        $insert_task_array['targeted_end_datetime'] = $targeted_end_datetime->format('c');
       // var_dump($insert_task_array);
        if($this->Task_model->insert($insert_task_array)==1){
            redirect('projects/view_dashboard/'.$project_id);
        }
    }

    public function edit_task($project_id,$task_id){
        $this->load->library('form_validation');
        $this->load->view('project/edit_task',
            $data=[
                "task"=>$this->Task_model->retrieve_by_id($task_id),
                "project_id"=>$project_id
            ]);
    }

    public function edit($project_id,$task_id){
        $update_array = $this->Task_model->retrieve_by_id($task_id);
        $update_array["content"]=$this->input->post("content");
        $update_array["importance"]=$this->input->post("importance");
        $update_array["targeted_start_datetime"]=$this->input->post("targeted_start_datetime");
        $update_array["targeted_end_datetime"]=$this->input->post("targeted_end_datetime");
        $update_array["start_datetime"]=$this->input->post("start_datetime");
        if($this->Task_model->update($update_array)==1){
            $this->session->set_userdata('message', 'Task updated successfully.');
        }else{
            $this->session->set_userdata('message', 'An error occurred, please contact administrator.');
        }
        redirect('projects/view_dashboard/'.$project_id);
    }

    public function all_tasks_by_project_id($project_id, $only_uncompleted){
        if($only_uncompleted){
            $affected_rows = $this->Task_model->retrieve_all_uncompleted_by_project_id($project_id);
        }else{
            $affected_rows = $this->Task_model->retrieve_all_by_project_id($project_id);
        }
        return $affected_rows;
    }

    public function start_task_confirmation($project_id,$task_id){
        if($this->Task_model->start($task_id)){
            redirect('projects/view_dashboard/'.$project_id);
        }
    }

    public function complete_task_confirmation($project_id,$task_id){
        if($this->Task_model->complete($task_id)){
            redirect('projects/view_dashboard/'.$project_id);
        }
    }

}