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



    var options = {
    title: 'Total Urgency Score Over Time',
    legend: 'none',
    chartArea: {
    'width': '78%',
    'height': '75%'
    },
    width: '100%',
    hAxis: {
    title: 'Week',
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
    chartArea: {
    'width': '78%',
    'height': '75%'
    },
    vAxis: {},
    hAxis: {},
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



    var options5 = {
    title: 'Phase Percentile Analysis',
    isStacked: true,
        legend:'top',
    hAxis: {

    },
    vAxis: {

    },
    chartArea: {
    'width': '78%'
    },
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
        <div id="chart_div" style="height: 300px;"></div>
        <div><br/><br/></div>
        <div class="col-lg-9">
            <br/>
            <br/>
            <div class="row">
                <div class="col-lg-12">
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
                        <th>Project ID</th>
                        <th>Project Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Time Spent</th>
                        <th>Number of Task</th>
                        <th>Number of Issue</th>
                        <th>Average Issue Metric</th>
                        <th></th>
                        </thead>

                        <?php if(!false == $projects):?>
                            <?php foreach($projects as $c):?>
                                <tr><td><?=$c['project_id']?></td>
                                    <td><?=$c['project_title']?></td>
                                    <td><?=$c['start_time']?></td>
                                    <td>*pending*</td>
                                    <td>*pending*</td>
                                    <td>*pending*</td>
                                    <td>*pending*</td>
                                    <td>*pending*</td>
                                    <td><a href="<?=base_url().'Projects/view_dashboard/'.$c["project_id"]?>"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            <?php endforeach?>
                        <?php endif?>
                    </table>
                </div>
            </div>
        </div>



        <div class="col-lg-3">
            <br/>
            <br/>
            <br/>
            <br/>

            <table class="table table-striped">
                <thead>
                <th>
                    Measure Name

                </th>
                </thead>
                <thead>
                <th>
                    Measure Name
                </th>
                <th>
                    Statistics
                </th>
                </thead>
                <tbody>
                <tr>
                    <td style="font-style: italic">Total Number of Project</td>
                    <td>*pending*</td>
                </tr>
                <tr>
                    <td style="font-style: italic">Average Time Spent</td>
                    <td>*pending*</td>
                </tr>
                <tr>
                    <td style="font-style: italic">Average Number of Task</td>
                    <td>*pending*</td>
                </tr>
                <tr>
                    <td style="font-style: italic">Average Number of Issue</td>
                    <td>*pending*</td>
                </tr>
                <tr>
                    <td style="font-style: italic">Total Number of Project</td>
                    <td>*pending*</td>
                </tr>
                <tr>
                    <td style="font-style: italic">Average avg Issue Metric</td>
                    <td>*pending*</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-12" id="chart_div3" style="height: 300px;"></div>
<div class="col-lg-12">
    <br/><br/>
</div>
        <div class="col-lg-12" id="chart_div5" style="height: 300px;"></div>
    </div>
    </div>


</body>