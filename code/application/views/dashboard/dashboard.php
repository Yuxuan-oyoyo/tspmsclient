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
        </style>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="<?= base_url() . 'js/google_chart_dashboard.js' ?>"></script>




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
<div class="col-lg-offset-1 col-lg-10">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            TSPMS-Dashboard
        </h1>
    </div>



    <div class="col-lg-7">
    <div class="row">
        <div class="col-lg-6 ">
            <div class="panel panel-info">
                <div class="panel-heading">Important but not urgent</div>
                <div class="panel-body" style="height: 200px;overflow-y: auto;">
                    <table class="table table-condensed " >
                        <?php if(isset($tasks_i)):
                            foreach($tasks_i as $t):?>
                        <tr>
                            <td><?=$t['content']?></td>
                            <td> <?php if($t['days']<0){
                                    $t['days'] = 0-$t['days'];
                                    echo '<span class="badge" style="background-color: indianred">Overdue'.$t['days'].' days</span>';

                                }else{
                                    echo '<span class="badge" style="background-color: green">'.$t['days'].' days</span>';
                                }?></td>
                            <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><i class="fa fa-eye"></i></a></td>
                        </tr>
                        <?php endforeach; endif?>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel panel-danger">
                <div class="panel-heading">Important and Urgent</div>
                <div class="panel-body" style="height: 200px;overflow-y: auto;">
                    <table class="table table-condensed ">
                        <?php if(isset($tasks_ui)):
                            foreach($tasks_ui as $t):?>
                                <tr>
                                    <td><?=$t['content']?></td>
                                    <td> <?php if($t['days']<0){
                                            $t['days'] = 0-$t['days'];
                                            echo '<span class="badge" style="background-color: indianred">Overdue'.$t['days'].' days</span>';

                                        }else{
                                            echo '<span class="badge" style="background-color: darkorange">'.$t['days'].' days</span>';
                                        }?></td>
                                    <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            <?php endforeach; endif?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 ">
            <div class="panel panel-success">
                <div class="panel-heading">NOT Important OR Urgent</div>
                <div class="panel-body" style="height: 200px;overflow-y: auto;">
                    <table class="table table-condensed " >
                        <?php if(isset($tasks_none)):
                            foreach($tasks_none as $t):?>
                                <tr>
                                    <td><?=$t['content']?></td>
                                    <td> <?php if($t['days']<0){
                                            $t['days'] = 0-$t['days'];
                                            echo '<span class="badge" style="background-color: indianred">Overdue '.$t['days'].' days</span>';

                                        }else{
                                            echo '<span class="badge" style="background-color: green">'.$t['days'].' days</span>';
                                        }?></td>
                                      <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            <?php endforeach; endif?>
                    </table>

                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel panel-warning">
                <div class="panel-heading">Urgent but not Important</div>
                <div class="panel-body" style="height: 200px;overflow-y:auto;">
                    <table class="table table-condensed ">
                        <?php if(isset($tasks_u)):
                            foreach($tasks_u as $t):?>
                                <tr>
                                    <td><?=$t['content']?></td>
                                    <td> <?php if($t['days']<0){
                                            $t['days'] = 0-$t['days'];
                                            echo '<span class="badge" style="background-color: indianred">Overdue '.$t['days'].' days</span>';

                                        }else{
                                            echo '<span class="badge" style="background-color: darkorange">'.$t['days'].' days</span>';
                                        }?></td>
                                    <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            <?php endforeach; endif?>
                    </table>

                </div>
            </div>
        </div>
    </div>
        <br/>
        <div class="col-lg-1" id="chart_div5" style="width: 600px; height: 200px;"></div>
    </div>

    <div class="col-lg-5" align="center">
        <div class="panel panel-default" style="width: 200px;height: 150px">
            <div class="panel-heading" style="background: #e0e2e5"><Strong>Total Urgency Score</Strong></div>
            <div class="panel-body" style="height: 200px;">
                <div class="thumbnail calendar-date" >
                    420
                </div>
                Intension Level: <span class="badge" style="background: #2e9ad0">Low</span>
            </div>
        </div>
    </div>
<div><br/>
    <br/></div>

    <div class="col-lg-5 tableContainer" align="center" >
        <table class="table table-striped">
            <thead>
            <th>Project ID</th>
            <th>Project Name</th>
            <th>Current Phase</th>
            <th>Urgency Score</th>
            <th>Next Milestone</th>
            <th></th>
            </thead>
            <tbody>

            <?php if(!false == $projects):?>
                <?php foreach($projects as $c):?>
                    <tr><td><?=$c['project_id']?></td>
                        <td><?=$c['project_title']?></td>
                        <td>*pending*</td>
                        <td>*pending*</td>
                        <td>*pending*</td>
                        <td><a href="<?=base_url().'Projects/view_dashboard/'.$c["project_id"]?>"><i class="fa fa-eye"></i></a></td>
                    </tr>
                <?php endforeach?>
            <?php endif?>


            </tbody>
        </table>
    </div>



    <div class="col-lg-12" id="chart_div3" style="height: 350px;"></div>

</div>