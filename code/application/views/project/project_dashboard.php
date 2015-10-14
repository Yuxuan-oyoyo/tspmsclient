<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url().'css/bootstrap.min.css'?>" rel="stylesheet">
    <link href="<?=base_url().'css/bootstrap.min.css'?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=base_url().'css/sb-admin.css'?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Francois+One" />
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <!-- Custom Fonts -->
    <link href="<?=base_url().'css/font-awesome.min.css'?>" rel="stylesheet" type="text/css">
    <link href="<?=base_url().'css/timeline.css'?>" media="all" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.1/modernizr.min.js"></script>
    <!-- jQuery -->
    <script src="<?=base_url().'js/jquery.js'?>"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url().'js/bootstrap.min.js'?>"></script>

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
    </script>
</head>
<body>

<?php $this->load->view('common/pm_nav');?>
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue selected" href="projectDashboard.html"><i class="fa fa-tasks"></i>Dashboard</a>
        <a class="link-blue " href="<?=base_url().'Projects/view_upadtes/'.$project["project_id"]?>"><i class="fa fa-flag"></i>Update & Milestone</a>
        <a class="link-blue " href="projectIssues.html"><i class="fa fa-wrench"></i>Issues</a>
        <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
    </div>

</aside>
<?php
$p = $project;
$src1 = "/tspms/ui/img/current.png";
$src2 = "/tspms/ui/img/future.png";
$src3 = "/tspms/ui/img/future.png";
$src4 = "/tspms/ui/img/future.png";
$src5 = "/tspms/ui/img/future.png";
$current_phase_name = "Lead";
$next_phase = "Requirement";
if($current_phase==2){
    $src1 = "/tspms/ui/img/done.png";
    $src2 = "/tspms/ui/img/current.png";
    $current_phase_name = "Requirement";
    $next_phase = "Build";
}elseif($current_phase==3){
    $src1 = "/tspms/ui/img/done.png";
    $src2 = "/tspms/ui/img/done.png";
    $src3 = "/tspms/ui/img/current.png";
    $current_phase_name = "Build";
    $next_phase = "Testing";
}elseif($current_phase==4){
    $src1 = "/tspms/ui/img/done.png";
    $src2 = "/tspms/ui/img/done.png";
    $src3 = "/tspms/ui/img/done.png";
    $src4 = "/tspms/ui/img/current.png";
    $current_phase_name = "Testing";
    $next_phase = "Deploy";
}elseif($current_phase==5){
    $src1 = "/tspms/ui/img/done.png";
    $src2 = "/tspms/ui/img/done.png";
    $src3 = "/tspms/ui/img/done.png";
    $src4 = "/tspms/ui/img/done.png";
    $src5 = "/tspms/ui/img/current.png";
    $current_phase_name = "Deploy";
}
?>

<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            #<?=$p['project_id']?>.&nbsp; <?=$p['project_title']?>
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil-square-o"></i>&nbsp;Update phase</button>
        </h1>
    </div>

    <!-- /.row -->
    <div class="row no-gutter">
        <div class="test col-sm-2 col-sm-offset-1" align="center" data-toggle="tooltip" data-placement="bottom" title="02-15,2015 to 03-03,2015 ">Lead<br><img src="<?=$src1?>" class="img-responsive"></div>
        <div  class="test col-sm-2" align="center" data-toggle="tooltip" data-placement="bottom" title="03-04,2015 to 04-20,2015 ">Requirement<br><img src="<?=$src2?>" class="img-responsive"></div>
        <div class="test col-sm-2" align="center" data-toggle="tooltip" data-placement="bottom" title="04-21,2015 to now " >Build<br><img src="<?=$src3?>" class="img-responsive"></div>
        <div class="test col-sm-2" align="center">Testing<br><img src="<?=$src4?>" class="img-responsive"></div>
        <div  class="test col-sm-2" align="center">Deploy<br><img src="<?=$src5?>" class="img-responsive"></div>

    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-offset-7 col-lg-4">
                <div class="panel info-panel">
                    <div class="panel-heading">Project Detail</div>
                    <div class="panel-body" style="font-size:15px" >
                        <table class="table table-condensed">
                            <tr>
                                <td><strong>No. of use case</strong></td>
                                <td><?=$p['no_of_use_cases']?></td>
                            </tr>
                            <tr>
                                <td><strong>Project Value</strong></td>
                                <td><?=$p['project_value']?></td>
                            </tr>
                            <tr>
                                <td><strong>Staging link </strong></td>
                                <td> <a href="http://fortawesome.github.io/Font-Awesome/icon/link/">Click here</a></td>
                            </tr>
                            <tr>
                                <td><strong>Customer </strong></td>
                                <td> <a href="#"><?=$customer_name?></a> (Click to edit)</td>
                            </tr>
                            <tr>
                                <td><strong>Bitbucket Repo Name </strong></td>
                                <td><?=$p['bitbucket_repo_name']?></td>
                            </tr>
                            <tr>
                                <td><strong>File Repo Name </strong></td>
                                <td><?=$p['file_repo_name']?></td>
                            </tr>
                            <tr>
                                <td><strong>Status </strong></td>
                                <td><?php
                                if($p['is_ongoing']==1){
                                    ?>
                                    Ongoing
                                    <?php
                                }else{
                                    ?>
                                    Closed
                                    <?php
                                }
                                    ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tags </strong></td>
                                <td><?=$p['tags']?></td>
                            </tr>
                            <tr>
                                <td><strong>Description </strong></td>
                                <td><?=$p['project_description']?></td>
                            </tr>
                            <tr>
                                <td><strong>Remarks </strong></td>
                                <td><?=$p['remarks']?></td>
                            </tr>
                        </table>

                        <a href="<?=base_url().'Projects/edit/'.$p["project_id"]?>" class="btn pull-right btn-primary"><i class="fa fa-pencil-square-o"></i> &nbsp;Edit</a>

                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- /#page-content-wrapper -->

</div>
</body>
</html>