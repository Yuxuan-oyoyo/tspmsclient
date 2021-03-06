<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link href="<?=base_url().'css/timeline.css'?>" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">

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
$this->load->view('common/customer_nav', $class);
?>
<div class="container content">
    <!-- Page Content -->
    <div class="col-md-12">
        <h1 class="page-header">
            MY PROJECTS
        </h1>
        <h4 style="color:darkgrey">Click on 'view' button to check the latest updates of each project .</h4>
    </div>

    <!-- /.row -->
    <div class="row">
       <?php foreach($projects as $p):?>
        <div class=" col-md-4">
            <?php
            if($p['is_ongoing']==1){
            ?>
                <div class="panel ongoing-panel">
            <?php
            }else{
            ?>
                <div class="panel past-panel">
            <?php
            }
            ?>
                <div class="panel-heading" style="text-align:center" ><strong><?=$p['project_title']?></strong></div>
                <div class="panel-body">
                    <table class="table table-condensed">
                        <tr>
                            <td><i class="fa fa-calendar-check-o"></i>&nbsp;<strong>Current Stage </strong></td>
                            <td><?php

                                if($p['phase_name']){
                                    echo $p['phase_name'];
                                }else{
                                    echo "not started";
                                }

                                ?></td>
                        </tr>
                        <?php if($p['customer_preview_link']){?>
                        <tr>
                            <td> <i class="fa fa-link"></i>&nbsp;<strong>Preview </strong></td>
                            <td> <a href="<?=$p['customer_preview_link']?>" target="_blank">Click here</a></td>
                        </tr>
                        <?php }else{?>
                        <tr>
                            <td> <i class="fa fa-link"></i>&nbsp;<strong>Preview </strong></td>
                            <td> Not Set Yet</td>
                        </tr>
                        <?php }?>

                    </table>
                    <a href="<?=base_url("projects/customer_view/".$p['project_id'])?>" class="btn pull-right btn-info"><i class="fa fa-eye"></i> &nbsp;View</a>

                </div>
            </div>
        </div>
    <?php endforeach?>





    </div>
</div>

</body>
</html>