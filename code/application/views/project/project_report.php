<?php
/**
 * Created by PhpStorm.
 * User: yuanyuxuan
 * Date: 27/1/16
 * Time: 10:33 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">


<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>




    <script>

        /**
         * Created by yuanyuxuan on 25/1/16.
         */
        google.charts.load('current', {
            'packages': ['corechart', 'gauge']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['Urgency', 50]
            ]);
// Urgency = SUM(pending_issue_priority/days_left)*project_priority
//Range to be set by admin panel

            var data2 = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['Importancy', 30]
            ]);
//5 Level of priority: {1,2,3,4,5} --> {10, 30, 50, 70, 90}
/**
            var data3 = google.visualization.arrayToDataTable([
                ['Phase', '#Task', '#Issue', 'Metrics'],
                ['Lead', 20, 3, 1],
                ['Requirement', 15, 14, 0.7],
                ['Build', 40, 73, 1.5],
                ['Testing', 30, 47, 0.6],
                ['Deployment', 14, 11, 1.1]
            ]);
 **/

            var jsonData3 = $.ajax({
                url: "<?=base_url().'dashboard/get_num_issues_tasks_metrics_per_phase/'.$project["project_id"]?>",
                //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
                dataType: "json",
                async: false
            }).responseText;



            var data3 = new google.visualization.DataTable(jsonData4);

            // Get JSON table

            var jsonData4 = $.ajax({
                url: "<?=base_url().'dashboard/get_per_issue_data/'.$project["project_id"]?>",
                //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
                dataType: "json",
                async: false
            }).responseText;



            var data4 = new google.visualization.DataTable(jsonData4);



/**
 *
 *          var data4 = new google.visualization.DataTable();
            data4.addColumn('string', 'issue#');
            data4.addColumn('number', 'schedule metric');
// A column for custom tooltip content
            data4.addColumn({
                type: 'string',
                role: 'tooltip'
            });
//tooltip will show , issue titile, start date, the days planned, days actually spent, metric
            data4.addRows([
                ['1', 0.8, 'tooltip pending'],
                ['3', 1.6, 'tooltip pending'],
                ['4', 0.5, 'tooltip pending'],
                ['5', 0.8, 'tooltip pending'],
                ['11', 0.8, 'tooltip pending'],
                ['13', 1.6, 'tooltip pending'],
                ['14', 0.5, 'tooltip pending'],
                ['15', 0.8, 'tooltip pending'],
                ['17', 1.5, 'tooltip pending'],
                ['23', 1.6, 'tooltip pending'],
                ['24', 0.5, 'tooltip pending'],
                ['25', 0.8, 'tooltip pending'],
                ['31', 0.8, 'tooltip pending'],
                ['33', 1.6, 'tooltip pending'],
                ['34', 0.5, 'tooltip pending'],
                ['35', 0.8, 'tooltip pending'],
                ['37', 1.5, 'tooltip pending']
            ]);

**/

            var data5 = new google.visualization.DataTable();
            data5.addColumn('string', 'Stage');
            data5.addColumn('number', 'time_spent');
            data5.addRows([
                ['to develop', 3],
                ['to test', 1],
                ['ready for deploy', 1],
                ['to deploy', 1]
            ]);




            var options = {
                width: 500,
                height: 120,
                redFrom: 80,
                redTo: 100,
                yellowFrom: 60,
                yellowTo: 80,
                minorTicks: 5
            };

            var options2 = {
                width: 100,
                height: 120,
                redFrom: 80,
                redTo: 100,
                yellowFrom: 60,
                yellowTo: 80,
                minorTicks: 5
            };

            var options3 = {
                title: 'Phase Analysis Chart',
                //legend:'bottom',
                chartArea: { width: '90%',left: "5%" , height: '70%'},
                vAxis: {
                },
                hAxis: {
                },
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



            var options4 = {
                'title': 'Issue Metrics Chart',
                chartArea: { left: "5%"},
                //'height': 300,
                tooltip: {
                    isHtml: true
                },
                legend: 'none',
                vAxis: {
                    'max': 3
                }
            };



            // Set chart options
            var options5 = {
                'title': 'Stage percentile analysis',
                chartArea: { width: '100%',left: "15%",height: "80%" },
            };
            // Instantiate and draw our chart, passing in some options.



            var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

            chart.draw(data, options);


            var chart2 = new google.visualization.Gauge(document.getElementById('chart_div2'));

            chart2.draw(data2, options2);

            var chart3 = new google.visualization.ComboChart(document.getElementById('chart_div3'));
            chart3.draw(data3, options3);


            var chart4 = new google.visualization.LineChart(document.getElementById('chart_div4'));
            chart4.draw(data4, options4);

            var chart5 = new google.visualization.PieChart(document.getElementById('chart_div5'));
            chart5.draw(data5, options5);


        }


    </script>
</head>

<body>
    <?php

    $class = [
        'dashboard_class'=>'',
        'projects_class'=>'active',
        'message_class'=>'',
        'customers_class'=>'',
        'internal_user_class'=>'',
        'analytics_class'=>''

    ];
    $this->load->view('common/pm_nav', $class);
    ?>

    <aside class="sidebar-left">
        <div class="sidebar-links">
            <a class="link-blue" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i>Project Overview</a>
            <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i>Update & Milestone</a>
            <?php
            if($project['bitbucket_repo_name']==null){
                ?>
                <a class="link-grey"><i class="fa fa-wrench"></i>Issues</a>
                <?php
            }else {
                ?>
                <a class="link-blue " href="<?= base_url() . 'Issues/list_all/' . $project["bitbucket_repo_name"] ?>"><i class="fa fa-wrench"></i>Issues</a>
                <?php
            }
            ?>
            <a class="link-blue" href="<?=base_url().'Usecases/list_all/'.$project["project_id"]?>"><i class="fa fa-list"></i>Use Case List</a>
            <a class="link-blue  selected" href="#"><i class="fa fa-bar-chart"></i>Analytics</a>
            <a class="link-blue " href="<?=base_url().'upload/upload/'.$project['project_id']?>"><i class="fa fa-folder"></i>File Repository</a>
        </div>
    </aside>

    <div class="col-lg-offset-1 content">
    <!-- Page Content -->
        <div class="col-lg-12">
            <h1 class="page-header">
                <?='#'.$project['project_id'].'. '.$project['project_title']?>&nbsp;
            </h1>
        </div>

        <!-- /#page-content-wrapper -->
        <div class="col-lg-12 col-md-offset-7 col-md-4" align="right">

            <div class="col-sm-4" id="chart_div" style="width: 120px; height: 45px;" align="right"></div>
            <div class="col-sm-4" id="chart_div2" style="width: 120px; height: 35px;" ></div>
            <div class="col-sm=4"></div>
        </div>

        <div class="col-lg-12 col-sm-8" id="chart_div3" style="width: 600px; height: 350px;"></div>
        <div>&nbsp;<br/><br/><br/><br/><br/>

            &nbsp;&nbsp; <select name="select" id="select">
                <option value="a" selected>All phases</option>
                <option value="b" >Lead</option>
                <option value="c" >Requirement</option>
                <option value="d" >Build</option>
                <option value="e" >Testing</option>
                <option value="f" >Deploying</option>
            </select>
            &nbsp;
            <select name="select" id="select2">
                <option value="a" selected>All kinds</option>
                <option value="b" >kind1</option>
                <option value="c" >kind2</option>
                <option value="d" >kind3</option>
                <option value="e" >kind4</option>
                <option value="f" >kind5</option>
            </select>
            &nbsp;
            <select name="select" id="select3">
                <option value="a" selected>All priorities</option>
                <option value="b" >priority1</option>
                <option value="c" >priority2</option>
                <option value="d" >priority3</option>
                <option value="e" >priority4</option>
                <option value="f" >priority5</option>
            </select>

            &nbsp;
            <input type="submit" value="Filter">
            <br/>
            <br/>
        </div>



        <div class="col-lg-12 col-sm-3" id="chart_div5" style="width: 400px; height: 200px;"></div>
        <div class="col-lg-12" id="chart_div4" style="height: 150px;"></div>




    </div>

</body>