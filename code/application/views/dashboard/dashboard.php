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
    <div class="col-lg-8">
    <div class="row">
        <div class="col-lg-6 ">
            <div class="panel panel-info">
                <div class="panel-heading">Important but not urgent</div>
                <div class="panel-body" style="height: 200px;overflow-y: scroll;">
                    <table class="table table-condensed " id="task_table">
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
                <div class="panel-body" style="height: 200px;overflow-y: scroll;">
                    <table class="table table-condensed " id="task_table">
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
                <div class="panel-body" style="height: 200px;overflow-y: scroll;">
                    <table class="table table-condensed " id="task_table">
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
                <div class="panel-body" style="height: 200px;overflow-y: scroll;">
                    <table class="table table-condensed " id="task_table">
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
        </div>
</div>