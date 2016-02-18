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
        $this->load->model("Project_model");
        // $this->load->model('User_log_model');
        set_time_limit(600);
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
        $projects = $this->Project_model->retrieve_all_ongoing();
        //$projects_task_issue_count = $this->num_of_tasks_issue_past_projects_php();


        $data["projects"]= $projects;
        $data["tasks_ui"]= $tasks_ui;
        $data["tasks_i"]= $tasks_i;
        $data["tasks_u"]= $tasks_u;
        $data["tasks_none"]= $tasks_none;
        //$data["projects_task_issue_count"]= $projects_task_issue_count;
        $this->load->view('dashboard/dashboard',$data);
    }

    /**
     * To get the needed user bb credential from db. IMPORTANT.
     * @return bool
     */
    function _is_authenticated(){
        $authenticated = false;
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM"){
            $authenticated = true;
        }else{
            $this->load->model("Internal_user_model");
            $all_pm_records = $this->Internal_user_model->retrieve_all_pm();
            if(isset($all_pm_records)&&isset($all_pm_records[0])&&!empty($all_pm_records[0]["bb_username"])){
                $pm_id = $all_pm_records[0]["u_id"];
                $this->session->set_userdata('internal_uid',$pm_id);
                $authenticated = true;
            }else{
                //die("No project manager found");
            }
        }
        return $authenticated;
    }
    public function fetch_issues($project_id){
        if($this->_is_authenticated()) {
            $this->bb_scheduled_tasks->fetch_project_issues($project_id);
        }
    }
    public function fetch_all_issues(){
        set_time_limit(600);
        if($this->_is_authenticated()) {
            $this->load->model("Project_model");
            $project_records = $this->Project_model->retrieveAll();
            foreach ($project_records as $p) {
                if (isset($p["bitbucket_repo_name"]) && !empty($p["bitbucket_repo_name"])) {
                    $this->bb_scheduled_tasks->fetch_project_issues($p["project_id"], $p["bitbucket_repo_name"]);
                }
            }
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
            $container[$value["phase_name"]] = ["pn"=>$value["phase_name"],"num_tasks"=>0,"num_issues"=>0, "metric"=>0];
        }
        //get issues
        $count_list_issue = $this->Issue_report_model->get_num_of_issues_per_phase($project_id);
        foreach($count_list_issue as $value){
            $container[$value["phase_name"]]["num_issues"] = $value["num"];
        }
        //var_dump($count_list_issue);
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
        //echo json_encode($container);



        $table = array();
        $table['cols'] = array(
            // Labels for your chart, these represent the column titles
            // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
            array('label' => 'Phase', 'type' => 'string'),
            array('label' => 'No. of Tasks', 'type' => 'number'),
            array('label' => 'No. of Issues', 'type' => 'number'),
            array('label' => 'metrics', 'type' => 'number')
        );


        $rows = array();
        foreach($container as $v){
            $temp = array();
            $count = 0;
            foreach($v as $value){
                if($count==2 or $count == 1){
                    $temp[] = array('v' => (int) $value);
                }else{
                    $temp[] = array('v' => $value);
                }
                $count+=1;
            }
            $rows[] = array('c' => $temp);
        }
        $table['rows'] = $rows;
        $jsonTable = json_encode($table);
        echo $jsonTable;



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
            if($key=="priority" && in_array($value,[1,2,3,4,5])){
                $input_clean[$key] = $value;
            }
            if($key=="kind" && in_array($value,["bug","enhancement","proposal","task"])){
                $input_clean[$key] = $value;
            }
            if($key=="phase" && in_array($value,[1,2,3,4,5])){
                $input_clean[$key] = $value;
            }
        }
        $this->load->model("Issue_report_model");
        $i = $this->Issue_report_model->get_sum_time_spent_per_category($project_id,$input_clean);
        //echo '{"cols":[{"label":"Stage","type":"string"},{"label":"Time Spent","type":"number"}],"rows":[{"c":[{"v":"to develop"},{"v":30}]},{"c":[{"v":"to test"},{"v":30}]},{"c":[{"v":"ready for deployment"},{"v":20}]},{"c":[{"v":"to deploy"},{"v":10}]}]}';
        $result = [];
        //var_dump($issue_time_list);
        array_push($result, ["to develop", (int)$i["du1"]]);
        array_push($result, ["to test", (int)$i["du2"]]);
        array_push($result, ["ready for deployment", (int)$i["du3"]]);
        array_push($result, ["to deploy", (int)$i["du4"]]);
        $table= [
            'cols'=>
                [
                    ['label' => 'Stage', 'type' => 'string'],
                    ['label' => 'Time Spent', 'type' => 'number']
                ]
        ];

        $rows = array();
        foreach($result as $v){
            $v_list = array();
            foreach($v as $detail){
                $v_list[] = ['v' => $detail];
            }
            $rows[] = ['c' => $v_list];
        }
        $table['rows'] = $rows;
        $jsonTable = json_encode($table);
        echo $jsonTable;
    }

    /**
     * For issue metrics
     * @param $project_id
     */
    public function get_per_issue_data($project_id){
        $this->load->model("Issue_report_model");
        $issue_list = $this->Issue_report_model->get_per_issue_data($project_id);


        $table = array();
        $table['cols'] = array(

            // Labels for your chart, these represent the column titles
            // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
            array('label' => 'Issue ID', 'type' => 'string'),
            array('label' => 'Metrics', 'type' => 'number'),
            array('label' => 'Details', 'type' => 'string',"role"=>'tooltip')

        );

        $rows = array();
        //sort array by local id
        usort($issue_list, function($a, $b){
            return $a["local_id"]- $b["local_id"];
        });
        $rows = [];
        foreach($issue_list as $v){
            $metric = 0;
            if(isset($v["date_created"]) && isset($v["date_resolved"]) &&isset($v["date_due"])
                && $v["date_due"]!=$v["date_created"]){
                $actual = strtotime($v["date_resolved"])- strtotime($v["date_created"]);
                $expected = strtotime($v["date_due"])- strtotime($v["date_created"]);
                $metric = $actual/ $expected;

            }
            $row = [
                "c"=>[
                    ['v' => (string) $v['local_id']],
                    ['v' =>  $metric],
                    ['v' => (string) $v['title']]
                ]
            ];
            array_push($rows, $row);
        }

        $table['rows'] = $rows;

        $table = [
            'cols'=>[
                // Labels for your chart, these represent the column titles
                ['label' => 'Issue ID', 'type' => 'string'],
                ['label' => 'Metrics', 'type' => 'number'],
                ['label' => 'Details', 'type' => 'string',"role"=>'tooltip']
            ],
            'rows'=>$rows
        ];

        $jsonTable = json_encode($table);
        echo $jsonTable;
    }

    public function phase_percentage(){
        $this->load->model("Phase_model");
        $this->load->model("Project_model");
        $phases= $this->Phase_model->retrieve_all_phases();
        $projects = $this->Project_model->retrieve_all_with_phase();
        $container = [];
        foreach($projects as $value){
            $phase = $value['phase_name'];
            if($phase == null){
                array_push($container,"Not Started");
            }else{
                array_push($container,$phase);
            }
        }




        $table = array();
        $table['cols'] = array(
            // Labels for your chart, these represent the column titles
            // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
            array('label' => 'Phase Name', 'type' => 'string'),
            array('label' => 'Count', 'type' => 'number')

        );
        $rows = array();



        $counts = array_count_values($container);
        //var_dump($counts);
        foreach($counts as $key => $value){
            $temp = array();
            //var_dump($key);
            //var_dump($value);
            $temp[] = array('v' => $key);
            $temp[] = array('v' => $value);
            $rows[] = array('c' => $temp);
        }
        $table['rows'] = $rows;
        $jsonTable = json_encode($table);
        echo $jsonTable;
    }

    public function num_of_tasks_issue_onging_projects(){
        $this->load->model("Task_model");
        $this->load->model("Issue_report_model");
        $tasknumbers = $this ->Task_model->get_num_of_tasks_onging_projects();
        //var_dump($tasknumbers);

        $numberissue = $this->Issue_report_model->get_num_of_issue_onging_projects();
        //var_dump($numberissue);


        $this->load->model("Project_model");

        $projects = $this->Project_model->retrieve_all_ongoing();

        //var_dump($projects);
        $container = [];
        foreach($projects as $value){
            $container[$value["project_id"]] = ["pn"=>$value["project_code"],"num_tasks"=>0,"num_issues"=>0];

        }

        foreach($tasknumbers as $value){
            $container[$value["project_id"]]["num_tasks"] = $value["count"];
        }

        foreach($numberissue as $value){
            $container[$value["project_id"]]["num_issues"] = $value["count"];
        }

        //echo json_encode($container);






        $table = array();
        $table['cols'] = array(
            // Labels for your chart, these represent the column titles
            // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
            array('label' => 'project', 'type' => 'string'),
            array('label' => 'No. of Tasks', 'type' => 'number'),
            array('label' => 'No. of Tasks', 'type' => 'number'),
        );


        $rows = array();
        foreach($container as $v){
            $temp = array();
            $count = 0;
            foreach($v as $value){
                if($count==2 or $count == 1){
                    $temp[] = array('v' => (int) $value);
                }else{
                    $temp[] = array('v' => $value);
                }
                $count+=1;
            }
            $rows[] = array('c' => $temp);
        }
        $table['rows'] = $rows;
        $jsonTable = json_encode($table);
        echo $jsonTable;

    }

    public function num_of_tasks_issue_past_projects(){
        $this->load->model("Task_model");
        $this->load->model("Issue_report_model");
        $this->load->model("Project_model");
        $tasknumbers = $this ->Task_model->get_num_of_tasks_past_projects();
        $numberissue = $this->Issue_report_model->get_num_of_issue_past_projects();
        $projects = $this->Project_model->retrieve_all_past();
        $container = [];
        foreach($projects as $value){
            $container[$value["project_id"]] = ["pn"=>$value["project_code"],"num_tasks"=>0,"num_issues"=>0, "metrics"=>0];
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
            }else{
                $container[$value["project_id"]]["metrics"] = 1;
            }
        }

        foreach($tasknumbers as $value){
            $container[$value["project_id"]]["num_tasks"] = (int)$value["count"];
        }

        foreach($numberissue as $value){
            $container[$value["project_id"]]["num_issues"] = (int)$value["count"];
        }



        //echo json_encode($container);


//var_dump($container);


        $table = array();
        $table['cols'] = array(
            // Labels for your chart, these represent the column titles
            // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
            array('label' => 'project', 'type' => 'string'),
            array('label' => 'No. of Tasks', 'type' => 'number'),
            array('label' => 'No. of Issues', 'type' => 'number'),

        );


        $rows = array();
        foreach($container as $v){

            $temp = array();
            $count = 0;
            foreach($v as $value){
                if($count==2 or $count == 1){
                    $temp[] = array('v' => (int) $value);
                }else{
                    $temp[] = array('v' => $value);

                }
                $count+=1;
            }
            //var_dump($v);
            $rows[] = array('c' => $temp);
        }

        $table['rows'] = $rows;
        $jsonTable = json_encode($table);
        echo $jsonTable;

    }



    public function phase_past_projects(){
    $this->load->model("Project_phase_model");
    $this->load->model("Project_model");
    $this->load->model("Phase_model");
    $phases= $this->Phase_model->retrieve_all_phases();
    $projects_phase = $this->Project_phase_model->get_phase_past_projects();
    $projects = $this->Project_model->retrieve_all_past();
    $container = [];

    foreach($projects as $value){
        $container[$value["project_id"]] = ["pn"=>$value["project_code"]];
        //var_dump($container);
        foreach($phases as $phase){

            $container[$value["project_id"]][$phase["phase_name"]] = 0;
        }
    }
    foreach($projects_phase as $value){
        $container[$value["project_id"]][$value["phase_name"]]=$value["time_spent"];
    }
    //var_dump($container);
    $table = array();
    $table['cols'] = array(
        // Labels for your chart, these represent the column titles
        // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
        array('label' => 'project', 'type' => 'string')
    );
    foreach($phases as $phase){
        array_push($table['cols'],array('label' => $phase["phase_name"], 'type' => 'number'));
    }
    $rows = array();
    foreach($container as $v){

        $temp = array();
        $count = 0;
        foreach($v as $value){
            if($count!=0){
                $temp[] = array('v' => (int) $value);
            }else{
                $temp[] = array('v' => $value);

            }
            $count+=1;
        }
        //var_dump($v);
        $rows[] = array('c' => $temp);
    }

    $table['rows'] = $rows;
    $jsonTable = json_encode($table);
    echo $jsonTable;
}


    public function get_total_urgency_score(){
        $this->load->model("Project_model");
        $projects = $this->Project_model->retrieve_all_ongoing();
        $container = [];
        foreach($projects as $value){

        }
    }

}