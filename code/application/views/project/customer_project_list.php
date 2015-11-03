<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <!-- Custom CSS -->
    <link href="<?=base_url().'css/sb-admin.css'?>" rel="stylesheet">
    <link href="<?=base_url().'css/timeline.css'?>" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <!-- jQuery -->
    <script src="<?= base_url() . 'js/jquery.min.js' ?>"></script>
    <script src="<?= base_url() . 'js/bootstrap.min.js' ?>"></script>

</head>
<body>
<?php $this->load->view('common/customer_nav');?>
<div class="container">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            MY PROJECTS
        </h1>
        <h4 style="color:darkgrey">Click on 'view' button to check the latest updates of each project .</h4>
    </div>

    <!-- /.row -->
    <div class="row">
       <?php foreach($projects as $project):?>
        <div class=" col-lg-4">
            <div class="panel ongoing-panel">
                <div class="panel-heading" style="text-align:center" ><strong><?=$project['project_title']?></strong></div>
                <div class="panel-body">

                    <a href="<?=base_url("projects/customer_view/".$project['project_id'])?>" class="btn pull-right btn-info"><i class="fa fa-eye"></i> &nbsp;View</a>

                </div>
            </div>
        </div>
    <?php endforeach?>





    </div>
</div>

</body>
</html>