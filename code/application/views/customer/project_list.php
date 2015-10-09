<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <?php $this->load->view('common/common_header');?>
    <meta charset="UTF-8">

    <!-- Custom CSS -->
    <link href="<?=base_url().'css/sb-admin.css'?>" rel="stylesheet">
    <link href="<?=base_url().'css/timeline.css'?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Francois+One" />
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <!-- Custom Fonts -->
    <link href="<?=base_url().'css/font-awesome.min.css'?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="<?=base_url().'js/jquery.js'?>"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url().'js/bootstrap.min.js'?>"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $(document).ready(function(){
            $('#customerTable').dataTable();
        });
    </script>
</head>
<body>
<?php $this->load->view('common/customer_nav');?>
<div class="col-lg-offset-1 content">
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