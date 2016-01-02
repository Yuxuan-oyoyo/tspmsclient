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
        $insert_task_array['header']=$this->input->post("header");
        $insert_task_array['body']=$this->input->post("body");
        $insert_task_array['importance']=$this->input->post("importance");

        $targeted_start_datetime=$this->input->post("targetedStartTimePicker");
        $targeted_start_datetime = new DateTime($targeted_start_datetime);
        $insert_task_array['targeted_start_datetime'] = $targeted_start_datetime->format('c');
        $targeted_end_datetime=$this->input->post("targetedEndTimePicker");
        $targeted_end_datetime = new DateTime($targeted_end_datetime);
        $insert_task_array['targeted_end_datetime'] = $targeted_end_datetime->format('c');
        if($this->Task_model->insert($insert_task_array)==1){
            redirect('projects/view_dashboard/'.$project_id);
        }
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
    //  Urgency = 1/ days left
    public function get_urgency($task_id){
        $t = $this->Task_model->retrieve_by_id($task_id);
        $targeted_end_datetime = $t['targeted_end_datetime'];
        $current_datetime = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $diff = $current_datetime->diff($targeted_end_datetime);
        $days_left = $diff->format('%R%a');
        $urgency = 1/ $days_left;
        return $urgency;
    }
}