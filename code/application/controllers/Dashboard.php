<?php
/**
 * Created by PhpStorm.
 * User: 123
 * Date: 2016-01-11
 * Time: 1:26 PM
 */
class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("Task_model");
        $this->load->library('BB_scheduled_tasks');
        // $this->load->model('User_log_model');
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard(){
        $tasks_ui=$this->Task_model->retrieve_for_esenhower(7, -1000,5,3);
        $tasks_i=$this->Task_model->retrieve_for_esenhower(1000, 7,5,3);
        $tasks_u=$this->Task_model->retrieve_for_esenhower(7, -1000,3,0);
        $tasks_none=$this->Task_model->retrieve_for_esenhower(1000, 7,3,0);
        $data["tasks_ui"]= $tasks_ui;
        $data["tasks_i"]= $tasks_i;
        $data["tasks_u"]= $tasks_u;
        $data["tasks_none"]= $tasks_none;
        $this->load->view('dashboard/dashboard',$data);
    }

    public function fetch_issues($project_id){
        $this->BB_scheduled_tasks->fetch_project_issues($project_id);
    }
    public function fetch_all_issues(){
        $this->load->model("Project_model");
        $project_records = $this->Project_model->retrieveAll();
        foreach($project_records as $p){
            $this->BB_scheduled_tasks->fetch_project_issues($p["project_id"],$p["bitbucket_repo_name"]);
        }
    }

    /**
     * @param $project_id
     */
    public function get_num_issues_tasks_metrics_per_phase($project_id){
        $this->load->model("Issue_report_model");
        $this->load->model("Phase_model");
        $this->load->model("Task_model");
        $this->load->model("Project_phase_model");
        $phases= $this->Phase_model->retrieve_all_phases();
        $container = [];
        //initialize phases
        foreach($phases as $value){
            $container[$value["phase_name"]] = ["num_tasks"=>0,"num_issues"=>0, "metric"=>0];
        }
        //get issues
        $count_list_issue = $this->Issue_report_model->get_num_of_issues_per_phase($project_id);
        foreach($count_list_issue as $value){
            $container[$value["phase_name"]]["num_issues"] = $value["num"];
        }
        //get tasks
        $count_list_task = $this->Task_model->get_num_of_tasks_per_phase($project_id);
        foreach($count_list_task as $value){
            $container[$value["phase_name"]]["num_tasks"] = $value["num"];
        }
        //get metric
        $count_list_phase = $this->Project_phase_model->get_times_per_phase($project_id);
        foreach($count_list_phase as $value){
            if(isset($value["start_time"]) && isset($value["end_time"])
                && isset($value["estimated_end_time"]) && $value["estimated_end_time"]!=$value["start_time"]){
                $estimated_duration = strtotime($value["estimated_end_time"])-strtotime($value["start_time"]);
                $actual_duration = strtotime($value["end_time"]) - strtotime($value["start_time"]);
                $container[$value["phase_name"]]["metric"] = $actual_duration / $estimated_duration;
            }
        }

        //ajax:
        echo json_encode($container);
    }

    /**
     * input->get:
     * ["phase"=>1/2/3.., "kind"=>"bug"/"enhancement".., "priority"=>1/2/3/4...]
     * @param $project_id
     */
    public function get_sum_time_spent_per_category($project_id){
        $categories = ["priority","kind","phase"];
        $input_raw = $this->input->get($categories, true);
        $input_clean = [];
        foreach($input_raw as $key=>$value){
            if(isset($value)){
                //verify the inputs
                $input_clean[$key] = $value;
            }
        }
        $this->load->model("Issue_report_model");
        $issue_time_list = $this->Issue_report_model->get_sum_time_spent_per_category($project_id,$input_clean);
        $result = [];
        foreach($issue_time_list as $i){
            array_push($result, ["to develop", $i["du1"]]);
            array_push($result, ["to test", $i["du2"]]);
            array_push($result, ["ready for deployment", $i["du3"]]);
            array_push($result, ["to deploy", $i["du4"]]);
            /*
            $result[$i["phase_name"]] = [
                "to develop" =>$i["du1"],
                "to test" =>$i["du2"],
                "ready for deployment" =>$i["du3"],
                "to deploy" =>$i["du4"]
            ];
            */
        }
        //ajax:
        echo json_encode($result);
    }

    /**
     * For issue metrics
     * @param $project_id
     */
    public function get_per_issue_data($project_id){

        $issue_list = $this->Issue_report_model->get_per_issue_data($project_id);
        $result = [];
        foreach($issue_list as $v){
            $metric = 0;
            if(isset($v["date_created"]) && isset($v["date_resolved"]) &&isset($v["date_due"])
                && $v["date_due"]!=$v["date_created"]){
                $actual = strtotime($v["date_resolved"])- strtotime($v["date_created"]);
                $expected = strtotime($v["date_due"])- strtotime($v["date_created"]);
                $metric = $actual/ $expected;
                array_push($result,[$v["local_id"],$metric, $v["title55"]])
            }
        }
        //ajax:
        echo json_encode($count_list);
    }
}