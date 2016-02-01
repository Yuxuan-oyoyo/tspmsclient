<?php
/**
 * Created by PhpStorm.
 * User: yuanyuxuan
 * Date: 28/1/16
 * Time: 3:59 PM
 */

class Historical extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("Project_model");

        // $this->load->model('User_log_model');
    }

    public function index()
    {
        $this->historical();
    }

    public function historical(){

        $projects = $this->Project_model->retrieve_all_past();
        $data["projects"]= $projects;
        $issue_task = $this->num_of_tasks_issue_past_projects_php();
        $data["issue_task"]=$issue_task;

        $this->load->view('Historical/Historical_Report',$data);
    }

    public function num_of_tasks_issue_past_projects_php(){
        $this->load->model("Task_model");
        $this->load->model("Issue_report_model");
        $this->load->model("Project_model");
        $tasknumbers = $this ->Task_model->get_num_of_tasks_past_projects();
        $numberissue = $this->Issue_report_model->get_num_of_issue_past_projects();
        $projects = $this->Project_model->retrieve_all_past();
        $container = [];
        foreach($projects as $value){
            $container[$value["project_id"]] = ["pn"=>$value["project_id"],"num_tasks"=>0,"num_issues"=>0, "metrics"=>0];
            $metricsissue = $this->Issue_report_model->get_per_issue_data($value["project_id"]);
            $matrics = 0;
            $count = 0;

            foreach($metricsissue as $issue){
                if(isset($issue["date_resolved"])&&isset($issue["date_due"])){
                    $matrics+=$issue["time_ratio"];
                    $count+=1;
                    //var_dump($count);
                }
            }
            if($count!=0){
                $container[$value["project_id"]]["metrics"] = $matrics/$count;
            }
        }
        foreach($tasknumbers as $value){
            $container[$value["project_id"]]["num_tasks"] = (int)$value["count"];
        }
        foreach($numberissue as $value){
            $container[$value["project_id"]]["num_issues"] = (int)$value["count"];
        }
        //var_dump($container) ;
        return $container;
    }
}