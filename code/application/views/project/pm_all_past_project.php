
<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>

</head>
<body>
<?php
$class = [
    'projects_class'=>'active',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>

<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            Projects
            <a href="<?=base_url().'Projects/create_new_project'?>" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;New Project</a>
        </h1>
        <ul class="nav nav-tabs">
            <li><a href="<?=base_url().'Projects/list_all'?>">Ongoing Projects</a></li>
            <li class="active"><a href="#">Past Projects</a></li>
        </ul>
        <?php
        if(sizeof($projects)==0){
        ?>
            <div class="alert alert-warning" role="alert"><strong>There is no past project yet.</strong></div>
        <?php
        }else{
            foreach($projects as $p){
                ?>
                <div class=" col-lg-4">
                    <div class="panel past-panel">
                        <div class="panel-heading" style="text-align:center" ><strong>#<?=$p['project_id']?>&nbsp;<?=$p['project_title']?></strong></div>
                        <div class="panel-body" style="font-size:15px" >
                            <table class="table table-condensed">
                                <tr>
                                    <td> <i class="fa fa-link"></i>&nbsp;<strong>Production link </strong></td>
                                    <td> <a href="<?=$p['production_link']?>" target="_blank">Click here</a></td>
                                </tr>
                            </table>

                            <a href="<?=base_url().'Projects/view_dashboard/'.$p["project_id"]?>" class="btn pull-right btn-info"><i class="fa fa-eye"></i> &nbsp;View</a>

                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>


    <!-- /#page-content-wrapper -->

</div>
</body>
</html>