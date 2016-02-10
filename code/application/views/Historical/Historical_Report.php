<?php
/**
 * Created by PhpStorm.
 * User: yuanyuxuan
 * Date: 28/1/16
 * Time: 12:57 PM
 */



defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">


<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
    google.charts.load('current', {
    'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {


        var jsonData = $.ajax({
            url: "<?=base_url().'issues/retrieve_urgency_score'?>",
            dataType: "json",
            async: false
        }).responseText;

        var data = new google.visualization.DataTable(jsonData);
        /**
    var data = google.visualization.arrayToDataTable([
    ['Week', 'TUS'],
    ['1', 1000],
    ['2', 1170],
    ['3', 660],
    ['4', 1030],
    ['5', 1170],
    ['6', 660],
    ['7', 1030],
    ['8', 242],
    ['9', 1170],
    ['10', 660],
    ['11', 1030],
    ['12', 24],
    ['13', 660],
    ['14', 2361]
    ]);
**/
/**
    var data3 = google.visualization.arrayToDataTable([
    ['Project', '#Task', '#Issue', 'Avg Issue Metrics'],
    ['P1', 20, 3, 1],
    ['P2', 15, 14, 0.7],
    ['P3', 40, 73, 1.5],
    ['P4', 30, 47, 0.6],
    ['P5', 14, 11, 1.1],
    ['P6', 40, 73, 1.5],
    ['P7', 30, 47, 0.6],
    ['P8', 14, 11, 1.1],
    ['P9', 40, 73, 1.5],
    ['P10', 30, 47, 0.6],
    ['P11', 14, 11, 1.1]
    ]);
**/
    var jsonData3 = $.ajax({
        url: "<?=base_url().'dashboard/num_of_tasks_issue_past_projects'?>",
        //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
        dataType: "json",
        async: false
    }).responseText;

    var data3 = new google.visualization.DataTable(jsonData3);



    var options = {
    title: 'Total Urgency Score Over Time',
    legend: 'none',
    width: '100%',
    hAxis: {
    titleTextStyle: {
    color: '#333'
    }
    },
    vAxis: {
    minValue: 0
    }
    };

    var options3 = {
    title: 'Issue/Task Analysis Chart',
    legend:'top',
    seriesType: 'bars',
    series: {
    0: {
    type: "bars",
    targetAxisIndex: 0
    },
    2: {
    type: "line",
    targetAxisIndex: 1
    }
    },
    colors: ['#1b9e77', '#d95f02', '#7570b3']

    };

    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    chart.draw(data, options);


    var chart3 = new google.visualization.ComboChart(document.getElementById('chart_div3'));
    chart3.draw(data3, options3);

/**
    var data5 = new google.visualization.DataTable();
    data5.addColumn('string', 'Project Name');
    data5.addColumn('number', 'Lead');
    data5.addColumn('number', 'Requirement');
    data5.addColumn('number', 'Build');
    data5.addColumn('number', 'Testing');
    data5.addColumn('number', 'Deploy');

    data5.addRows([
    ["Project 1", 13, 23,124, 34,6],
    ["Project 2", 12, 11,142, 23,5],
    ["Project 3", 5, 3,321, 54,3],
    ["Project 4", 3, 5,34, 3,45],
    ["Project 5", 20, 10,125, 35,3],
    ["Project 6", 15, 4,124, 72,21],
    ["Project 7", 3, 12,152, 14,64],
    ["Project 8", 5, 3,321, 54,3],
    ["Project 9", 3, 5,34, 3,45],
    ["Project 10", 20, 10,125, 35,3],
    ["Project 11", 20, 10,125, 35,3],
    ]);
**/
        var jsonData5 = $.ajax({
            url: "<?=base_url().'dashboard/phase_past_projects'?>",
            //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
            dataType: "json",
            async: false
        }).responseText;

        var data5 = new google.visualization.DataTable(jsonData5);

    var options5 = {
    title: 'Phase Percentile Analysis',
    isStacked: true,
        legend:'top',
    };

    var chart5 = new google.visualization.ColumnChart(document.getElementById('chart_div5'));
    chart5.draw(data5, options5);

    }
</script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#customerTable').dataTable();
        });
    </script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!--
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
-->
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">

<script>
$(function() {
    $( "#datepicker1" ).datepicker({ maxDate: "-1M" });
});
</script>
<script>
$(function() {
    $( "#datepicker2" ).datepicker({ maxDate: -0 });
});
</script>


</head>

<body>
<?php
$class = [
    'dashboard_class'=>'',
    'projects_class'=>'',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>'active'
];
$this->load->view('common/pm_nav', $class);
?>


<div class="col-lg-offset-1 col-lg-10 content">
    <div class="col-lg-12">
        <h1 class="page-header">
            TSPMS-Historical Statistics
        </h1>
<div><br/><div>
        <div class="col-lg-12" >
            <div class="row" style="width: 90%; margin-left: 5%">
                <div class="center" >
                    <?php if($this->session->userdata('message')):?>
                        <div class="form-group">
                            <div class="alert alert-info " role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                                </button>
                                <?=$this->session->userdata('message')?>
                            </div>
                        </div>
                        <?php $this->session->unset_userdata('message') ?>
                    <?php endif;?>
                    <table class="table table-striped" id="customerTable">
                        <thead>
                        <th>Project Code</th>
                        <th>Project Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Time Spent</th>
                        <th>Number of Task</th>
                        <th>Number of Issue</th>
                        <th>Average Issue Metric</th>
                        </thead>
                        <?php
                        $count = 0;
                        $total_duration=0;
                        $total_num_tasks=0;
                        $total_num_issues=0;
                        $total_issue_metrics=0;
                        ?>
                        <?php if(!false == $projects):?>
                            <?php foreach($projects as $c):?>
                                <?php
                                $count+=1;
                                $total_duration+=(float)$c['project_duration'];
                                $total_num_tasks+=(float)$issue_task[$c['project_id']]['num_tasks'];
                                $total_num_issues+=(float)$issue_task[$c['project_id']]['num_issues'];
                                $total_issue_metrics+=$issue_task[$c['project_id']]['metrics'];
                                ?>
                                <tr><td><?=$c['project_code']?></td>
                                    <td><a href="<?=base_url().'Projects/view_dashboard/'.$c["project_id"]?>"><?=$c['project_title']?></a></td>
                                    <td><?=$c['start_time']?></td>
                                    <td><?=substr($c['last_updated'], 0 ,10)?></td>
                                    <td><?=$c['project_duration']?></td>
                                    <td><?=$issue_task[$c['project_id']]['num_tasks']?></td>
                                    <td><?=$issue_task[$c['project_id']]['num_issues']?></td>
                                    <td><?=$issue_task[$c['project_id']]['metrics']?></td>
                                </tr>
                            <?php endforeach?>
                        <?php endif?>
                    </table>
                </div>
            </div>
        </div>



        <div class="col-sm-4"  style="width: 35%;margin-left: 10%">
            <br/>
            <table class="table table-bordered">
                <thead style="background: #e1e1e8;">
                <th colspan="2">
                    Statistics Summary
                </th>

                </thead>
                <tbody>
                <tr>
                    <td style="font-style: italic">Total Number of Project</td>
                    <td><?=$count?></td>
                </tr>
                <tr>
                    <td style="font-style: italic">Average Time Spent</td>
                    <td><?=number_format($total_duration/$count, 2)?></td>

                </tr>
                <tr>
                    <td style="font-style: italic">Average Number of Task</td>
                    <td><?=number_format($total_num_tasks/$count, 2)?></td>
                </tr>
                <tr>
                    <td style="font-style: italic">Average Number of Issue</td>
                    <td><?=number_format($total_num_issues/$count, 2)?></td>
                </tr>
                <tr>
                    <td style="font-style: italic">Average avg Issue Metric</td>
                    <td><?=number_format($total_issue_metrics/$count, 2)?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
        $date=strtotime(date('Y-m-d'));
        $newDate = date('m/d/Y',strtotime('-1 month',$date));
        ?>
        <div class="col-sm-4"  style="width: 35%;margin-top: 7%;margin-left: 8%">
            <form  role="form" action="#" method="post">
                <div class="form-group">
                    <label for="targeted_end_datetime">Targeted Start Datetime:</label>
                    <input class="form-control clsDatePicker" type="text" id="datepicker1" value="<?php echo $newDate;?>">

                </div>

                <div class="form-group">
                    <label for="targeted_end_datetime">Targeted End Datetime:</label>
                    <input class="form-control clsDatePicker" type="text" id="datepicker2" value="<?php echo date("m/d/Y");?>">
                </div>
                <div class="pull-right">
                    <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Change Time Slot">
                </div>
            </form>
            <script>
                if(Date.parse(datepicker1) > Date.parse(datepicker2)){
                    alert("Invalid Date Range");
                }
                else if(Date.parse(datepicker1) < Date.parse(datepicker2)){
                    alert("Valid date Range");
                }
            </script>
        </div>
        <div class="col-lg-12">
            <br/>
        </div>
        <div class="col-lg-12" id="chart_div3" style="width: 100%; height: 300px;"></div>
<div class="col-lg-12">
    <br/><br/>
</div>
        <div class="col-lg-12" id="chart_div5" style="width: 100%; height: 300px;"></div>
        <div class="col-lg-12" id="chart_div" style="width: 100%; height: 300px;"></div>

    </div>
    </div>

</body>