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
        var options3 = {
            title: 'Phase Analysis Chart',
            //legend:'bottom',
            //chartArea: { width: '90%',left: "5%" , height: '70%'},
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
            //chartArea: { left: "5%"},
            //'height': 300,
            tooltip: {
                isHtml: true
            },
            legend: 'none',
            vAxis: {
                'max': 3
            }
        };

        var options5 = {
            'title': 'Stage percentile analysis',
            chartArea: {height: "80%"},
            legend: 'left'
        };




        function drawChart() {
            var jsonData3 = $.ajax({
                url: "<?=base_url().'dashboard/get_num_issues_tasks_metrics_per_phase/'.$project["project_id"]?>",
                //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
                dataType: "json",
                async: false
            }).responseText;


            var data3 = new google.visualization.DataTable(jsonData3);

            // Get JSON table

            var jsonData4 = $.ajax({
                url: "<?=base_url().'dashboard/get_per_issue_data/'.$project["project_id"]?>",
                //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
                dataType: "json",
                async: false
            }).responseText;
            var data4 = new google.visualization.DataTable(jsonData4);

            var jsonData5 = $.ajax({
                url: "<?=base_url().'dashboard/get_sum_time_spent_per_category/'.$project["project_id"]?>",
                //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
                dataType: "json",
                async: false
            }).responseText;
            var data5 = new google.visualization.DataTable(jsonData5);
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

        <div class="col-lg-offset-1 content">&nbsp;<br/>
            &nbsp;&nbsp; <select name="phase" id="phase-selection" class="filter-criterion">
                <option value="" selected>All phases</option>
                <option value="1" >Lead</option>
                <option value="2" >Requirement</option>
                <option value="3" >Build</option>
                <option value="4" >Testing</option>
                <option value="5" >Deploying</option>
            </select>
            &nbsp;
            <select name="kind" id="kind-selection" class="filter-criterion">
                <option value="" selected>All kinds</option>
                <option value="bug" >Bug</option>
                <option value="enhancement" >Enhancement</option>
                <option value="proposal" >Proposal</option>
                <option value="task" >Task</option>
            </select>
            &nbsp;
            <select name="priority" id="priority-selection" class="filter-criterion">
                <option value="" selected>All priorities</option>
                <option value="1" >trivial</option>
                <option value="2" >minor</option>
                <option value="3" >major</option>
                <option value="4" >critical</option>
                <option value="5" >blocker</option>
            </select>
            &nbsp;
            <br/>
            <br/>
            <script>
                $(".filter-criterion").on("change",function(){
                    $.ajax({
                        url: "<?=base_url().'dashboard/get_sum_time_spent_per_category/'.$project["project_id"]?>",
                        dataType: "json",
                        data: {
                            priority:$("#priority-selection").val(),
                            phase:$("#phase-selection").val(),
                            kind: $("#kind-selection").val()
                        },
                        success: function(res){
                            var data5 = new google.visualization.DataTable(res);
                            var chart5 = new google.visualization.PieChart(document.getElementById('chart_div5'));
                            chart5.draw(data5, options5);
                        }
                    });
                });
            </script>
        </div>
        <div class="col-sm-4" id="chart_div5" style="width: 60%; height: 250px;"></div>
        <div class="col-sm-4" style="width: 40%; height: 250px;">
            <div>
                <br/><br/>
            </div>
            <div class="panel panel-default" style="width:60%;height: 150px" align="center">
                <div class="panel-heading" style="background: #e0e2e5"><Strong>Total Urgency Score</Strong></div>
                <div class="panel-body" style="height: 200px;">
                    <div class="thumbnail calendar-date" >
                        <script>
                            var total_urgency = $.ajax({
                                url: "<?=base_url().'issues/get_issue_urgency_score_across_projects'?>",
                                //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
                                dataType: "float",
                                async: false
                            }).responseText;
                            //window.alert(urgency);
                            document.write(total_urgency);
                        </script>
                    </div>
                    Urgency Level: <span class="badge" style="background: #2e9ad0">Low</span>
                </div>
            </div>
        </div>
        <div class="col-lg-12" id="chart_div4" style="width: 100%; height: 300px"></div>
        <div class="col-lg-12" id="chart_div3" style="width: 100%; height: 300px"></div>



</div>
    </div>

</body>