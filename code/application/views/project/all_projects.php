<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url().'css/bootstrap.min.css'?>" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">



    <!-- Custom CSS -->
    <link href="<?=base_url().'css/sb-admin.css'?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Francois+One" />
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <!-- Custom Fonts -->
    <link href="<?=base_url().'css/font-awesome.min.css'?>" rel="stylesheet" type="text/css">
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
        $(function () {

            var links = $('.sidebar-links > a');

            links.on('click', function () {

                links.removeClass('selected');
                $(this).addClass('selected');
            })
        });
        $(document).ready(function(){
            $('#issueTable').dataTable();
        });
    </script>
</head>
<body>

<?php $this->load->view('common/pm_nav');?>


<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            Projects
            <a href="<?=base_url().'Projects/add'?>" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;New Project</a>
        </h1>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#">Ongoing Projects</a></li>
            <li><a href="pastProjects.html">Past Projects</a></li>
        </ul>
        <?php
            foreach($projects as $p){
         ?>
                <div class=" col-lg-4">
                    <div class="panel ongoing-panel">
                        <div class="panel-heading" style="text-align:center" ><strong>#<?=$p['project_id']?>&nbsp;<?=$p['project_title']?></strong></div>
                        <div class="panel-body" style="font-size:15px" >
                            <table class="table table-condensed">
                                <tr>
                                    <td><i class="fa fa-calendar-check-o"></i>&nbsp;<strong>Current Stage </strong></td>
                                    <td><?=$p['phase_name']?></td>
                                </tr>
                                <tr>
                                    <td> <i class="fa fa-link"></i>&nbsp;<strong>Staging link </strong></td>
                                    <td> <a href="<?=$p['staging_link']?>">Click here</a></td>
                                </tr>
                                <tr>
                                    <td>  <i class="fa fa-user"></i>&nbsp;<strong>Customer </strong></td>
                                    <td> <a href="<?=base_url().'Customers/list_all'?>"><?=$p['first_name'].' '.$p['last_name']?></a></td>
                                </tr>
                                <tr>
                                    <td>  <i class="fa fa-clock-o"></i>&nbsp;<strong>Number Of Issues </strong></td>
                                    <td> 3</td>
                                </tr>
                            </table>

                            <a href="<?=base_url().'Projects/view_dashboard/'.$p["project_id"]?>" class="btn pull-right btn-info"><i class="fa fa-eye"></i> &nbsp;View</a>

                        </div>
                    </div>
                </div>
        <?php
            }
        ?>
    </div>


    <!-- /#page-content-wrapper -->

</div>
</body>
</html>