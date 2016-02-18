<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <?php $this->load->view('common/common_header');?>
        <style>
            .no-gap[class*="-4"] {
                padding-left: 1px;
                padding-right: 0;
            }
            .panel-heading-eh{
                font-weight: 700;
                text-align: center;
                text-transform: uppercase;
            }
        </style>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script>

            /**
             * Created by yuanyuxuan on 27/1/16.
             */
            /**
             * Created by yuanyuxuan on 25/1/16.
             */
            google.charts.load('current', {
                'packages': ['corechart', 'gauge']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
/**
                var data3 = google.visualization.arrayToDataTable([
                    ['Project', '#Task', '#Issue'],
                    ['P1', 20, 3],
                    ['P2', 15, 14],
                    ['P3', 40, 73],
                    ['P4', 30, 47],
                    ['P5', 14, 11],
                    ['P6', 15, 14],
                    ['P7', 40, 73],
                    ['P8', 30, 47],
                    ['P9', 14, 100]
                ]);

 **/
                var jsonData3 = $.ajax({
                    url: "<?=base_url().'dashboard/num_of_tasks_issue_onging_projects'?>",
                    //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
                    dataType: "json",
                    async: false
                }).responseText;

                var data3 = new google.visualization.DataTable(jsonData3);
                var jsonData5 = $.ajax({
                    url: "<?=base_url().'dashboard/phase_percentage'?>",
                    //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
                    dataType: "json",
                    async: false
                }).responseText;




                var data5 = new google.visualization.DataTable(jsonData5);
/**
                var data5 = new google.visualization.DataTable();
                data5.addColumn('string', 'Phase');
                data5.addColumn('number', 'time_spent');
                data5.addRows([
                    ['Lead', 334],
                    ['Requirement', 142],
                    ['Build', 1623],
                    ['Testing', 553],
                    ['Deploy', 314]
                ]);
 **/

                var options3 = {
                    title: 'Task/Issue Analysis',
                    'legend':'bottom',
                    //legend:'bottom',
                    chartArea: { width: '90%',left: "5%" , height: '70%'},
                    vAxis: {
                    },
                    hAxis: {
                    },
                    seriesType: 'bars',
                    colors: ['#1b9e77', '#d95f02']

                };



                // Set chart options
                var options5 = {
                    'title': 'Phase percentile analysis',
                    'legend':'right',
                    chartArea: { width: '100%',left: "15%",height: "80%" },
                };
                // Instantiate and draw our chart, passing in some options.



                var chart3 = new google.visualization.ComboChart(document.getElementById('chart_div3'));
                chart3.draw(data3, options3);




                var chart5 = new google.visualization.PieChart(document.getElementById('chart_div5'));
                chart5.draw(data5, options5);


            }


        </script>




    </head>
    <body>
<?php
$class = [
    'dashboard_class'=>'active',
    'projects_class'=>'',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>
<div class="col-md-offset-1 col-md-10">
    <!-- Page Content -->
    <div class="col-md-12">
        <h1 class="page-header">
            TSPMS-Dashboard
        </h1>
    </div>



    <div class="col-md-7">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-danger">
                    <div class="panel-heading-eh panel-heading">Important and Urgent</div>
                    <div class="panel-body" style="height: 200px;overflow-y: auto;">
                        <table class="table table-condensed ">
                            <?php if(isset($tasks_ui)):
                                foreach($tasks_ui as $t):?>
                                    <tr>
                                        <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><?=$t['content']?></a></td>
                                        <td> <?php
                                            $day = " days";
                                            if($t['days']==0 || $t['days']==1 || $t['days']==-1){
                                                $day = " day";
                                            }
                                            if($t['days']<0){
                                                $t['days'] = 0-$t['days'];
                                                echo '<span class="badge" style="background-color: indianred">Overdue  <br>'.$t['days'].$day.'</span>';

                                            }else{
                                                echo '<span class="badge" style="background-color: darkorange">'.$t['days'].$day.'</span>';
                                            }?></td>
                                    </tr>
                                <?php endforeach; endif?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading-eh panel-heading">Urgent but not Important</div>
                    <div class="panel-body" style="height: 200px;overflow-y:auto;">
                        <table class="table table-condensed ">
                            <?php if(isset($tasks_u)):
                                foreach($tasks_u as $t):?>
                                    <tr>
                                        <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><?=$t['content']?></a></td>
                                        <td> <?php
                                            $day = " days";
                                            if($t['days']==0 || $t['days']==1 || $t['days']==-1){
                                                $day = " day";
                                            }
                                            if($t['days']<0){
                                                $t['days'] = 0-$t['days'];
                                                echo '<span class="badge" style="background-color: indianred">Overdue  <br>'.$t['days'].$day.'</span>';

                                            }else{
                                                echo '<span class="badge" style="background-color: darkorange">'.$t['days'].$day.'</span>';
                                            }?></td>
                                    </tr>
                                <?php endforeach; endif?>
                        </table>

                    </div>
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-md-6 ">
                <div class="panel panel-info">
                    <div class="panel-heading-eh panel-heading">Important but not urgent</div>
                    <div class="panel-body" style="height: 200px;overflow-y: auto;">
                        <table class="table table-condensed " >
                            <?php if(isset($tasks_i)):
                                foreach($tasks_i as $t):?>
                                    <tr>
                                        <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><?=$t['content']?></a></td>
                                        <td> <?php
                                            $day = " days";
                                            if($t['days']==0 || $t['days']==1 || $t['days']==-1){
                                                $day = " day";
                                            }
                                            if($t['days']<0){
                                                $t['days'] = 0-$t['days'];
                                                echo '<span class="badge" style="background-color: indianred">Overdue  <br>'.$t['days'].$day.'</span>';

                                            }else{
                                                echo '<span class="badge" style="background-color: green">'.$t['days'].$day.'</span>';
                                            }?></td>
                                    </tr>
                                <?php endforeach; endif?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 ">
                <div class="panel panel-success">
                    <div class="panel-heading-eh panel-heading">Not Important or Urgent</div>
                    <div class="panel-body" style="height: 200px;overflow-y: auto;">
                        <table class="table table-condensed " >
                            <?php if(isset($tasks_none)):
                                foreach($tasks_none as $t):?>
                                    <tr>
                                        <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><?=$t['content']?></a></td>
                                        <td> <?php
                                            $day = " days";
                                            if($t['days']==0 || $t['days']==1 || $t['days']==-1){
                                                $day = " day";
                                            }
                                            if($t['days']<0){
                                                $t['days'] = 0-$t['days'];
                                                echo '<span class="badge" style="background-color: indianred">Overdue <br>'.$t['days'].$day.'</span>';

                                            }else{
                                                echo '<span class="badge" style="background-color: green">'.$t['days'].$day.'</span>';
                                            }?></td>
                                    </tr>
                                <?php endforeach; endif?>
                        </table>

                    </div>
                </div>
            </div>

        </div>

        <br/>
        <div class="col-md-1" id="chart_div5" style="width: 600px; height: 200px;"></div>
    </div>

    <div class="col-md-5" align="center">
        <div class="panel panel-default" style="width: 200px;height: 150px">
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
                Urgency Level: <span id = "bage" class="badge" ></span>
            </div>

            <script>
                console.log(total_urgency);
                if(total_urgency >60 ){
                    document.getElementById("bage").style.backgroundColor = "rgba(200,50,50, 0.7)";
                    $('#bage').append("High");
                }else if(total_urgency >15 && total_urgency<= 60){
                    document.getElementById("bage").style.backgroundColor = "rgba(250,120,0,0.7)";
                    $('#bage').append("Medium");
                }else{
                    document.getElementById("bage").style.backgroundColor = "rgba(44,74,215,0.7)";
                    $('#bage').append("Low");
                }

            </script>
        </div>
    </div>
<div><br/>
    <br/></div>

    <div class="col-md-5 tableContainer" align="center" >
        <table class="table table-striped">
            <thead>
            <th>Project Code</th>
            <th>Project Name</th>
            <th>Current Phase</th>
            <th>Urgency Score</th>
            </thead>
            <tbody>

            <?php if(!false == $projects):?>
                <?php foreach($projects as $c):?>
                    <script>
                        var urgency = $.ajax({
                            url: "<?=base_url().'issues/get_issue_urgency_score/'.$c["project_id"]?>",
                            //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
                            dataType: "float",
                            async: false
                        }).responseText;
                    </script>
                    <tr><td><?=$c['project_code']?></td>
                        <td><a href="<?=base_url().'Projects/view_dashboard/'.$c["project_id"]?>"><?=$c['project_title']?></a></td>
                        <td><?=$c['phase_name']?></td>
                        <td><script>
                                document.write(urgency);
                            </script></td>
                    </tr>
                <?php endforeach?>
            <?php endif?>


            </tbody>
        </table>
    </div>



    <div class="col-md-12" id="chart_div3" style="height: 350px;"></div>

</div>
    </body>
</html>