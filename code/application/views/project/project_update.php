<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: WANG Tiantong
 * Date: 10/6/2015
 * Time: 6:53 PM
 */
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <?php $this->load->view('common/common_header');?>
    <meta charset="UTF-8">
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url().'css/bootstrap.min.css'?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Francois+One" />
    <link href="<?=base_url().'css/sb-admin.css'?>" rel="stylesheet">
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
        $('#done').on('change', function(e){
            if(e.target.checked){
                alert("Ss");
                $('#newUpdateModal').modal();
            }
        });
        function showModal(){
            if ($('#done').checked) {
                $('#newUpdateModal').modal();
            }
        }


    </script>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html">The Shipyard </a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-nav">
        <li>
            <a href="index.html"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
        </li>
        <li  class="active">
            <a href="projects.html"><i class="fa fa-fw fa-bar-chart-o"></i> Projects</a>
        </li>
        <li>
            <a href="chat.html"><i class="fa fa-fw fa-comment"></i> Message</a>
        </li>
        <li>
            <a href="customers.html"><i class="fa fa-fw fa-users"></i>Customers</a>
        </li>
        <li>
            <a href="customers.html"><i class="fa fa-fw fa-line-chart"></i>Analytics</a>
        </li>
        <!---
        <li>
            <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Issue Tracker</a>
        </li>
        <li>
            <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> File Repo</a>
        </li>
        <li>
            <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Tasks</a>
        </li>
        --->
    </ul>
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
            <ul class="dropdown-menu message-dropdown">
                <li class="message-preview">
                    <a href="#">
                        <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                            <div class="media-body">
                                <h5 class="media-heading"><strong>John Smith</strong>
                                </h5>
                                <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                <p>Lorem ipsum dolor sit amet, consectetur...</p>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="message-footer">
                    <a href="#">Read All New Messages</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
            <ul class="dropdown-menu alert-dropdown">
                <li>
                    <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">View All</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue" href="projectDashboard.html"><i class="fa fa-tasks"></i>Dashboard</a>
        <a class="link-blue selected" href="projectUpdate.html"><i class="fa fa-flag"></i>Update & Milestone</a>
        <a class="link-blue " href="projectIssues.html"><i class="fa fa-wrench"></i>Issues</a>
        <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
    </div>

</aside>

<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php $p = $project;?>
            <?='#'.$p['project_id'].' '.$p['project_title']?>
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#updatePhaseModal"><i class="fa fa-pencil-square-o"></i>&nbsp;Update phase</button>
        </h1>
    </div>

    <!-- /.row -->
    <?php
        $src1 = "/tspms/ui/img/current.png";
        $src2 = "/tspms/ui/img/future.png";
        $src3 = "/tspms/ui/img/future.png";
        $src4 = "/tspms/ui/img/future.png";
        $src5 = "/tspms/ui/img/future.png";
        $next_phase = "Requirement";
        if($current_phase==2){
            $src1 = "/tspms/ui/img/done.png";
            $src2 = "/tspms/ui/img/current.png";
            $next_phase = "Build";
        }elseif($current_phase==3){
            $src1 = "/tspms/ui/img/done.png";
            $src2 = "/tspms/ui/img/done.png";
            $src3 = "/tspms/ui/img/current.png";
            $next_phase = "Testing";
        }elseif($current_phase==4){
            $src1 = "/tspms/ui/img/done.png";
            $src2 = "/tspms/ui/img/done.png";
            $src3 = "/tspms/ui/img/done.png";
            $src4 = "/tspms/ui/img/current.png";
            $next_phase = "Deploy";
        }elseif($current_phase==5){
            $src1 = "/tspms/ui/img/done.png";
            $src2 = "/tspms/ui/img/done.png";
            $src3 = "/tspms/ui/img/done.png";
            $src4 = "/tspms/ui/img/done.png";
            $src5 = "/tspms/ui/img/current.png";
        }
    ?>
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
            <div class="col-lg-7">
                <h3>Client Updates - <small>Build</small><button class="btn btn-primary pull-right" data-toggle="modal" data-target="#newUpdateModal"><i class="fa fa-plus"></i>&nbsp; Add</button></h3><hr>
                <ul class="timeline">
                    <li><!---Time Line Element--->
                        <div class="timeline-badge  neutral"><i class="fa fa-navicon"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">Customer Update 1</h4>
                            </div>
                            <div class="timeline-body"><!---Time Line Body&Content--->
                                <p>Update content is placed here...</p>
                                <div class="pull-right timeline-info">
                                    <i class="fa fa-user"></i>&nbsp;Andrew &nbsp;
                                    <i class="fa fa-calendar-check-o"></i>&nbsp;Sep,19,2015</div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-lg-4">

                <h3>Milestones - <small>Build</small><button class="btn btn-primary pull-right" data-toggle="modal" data-target="#newMilestoneModal"><i class="fa fa-plus"></i>&nbsp; Add</button></h3><hr>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="panel panel-default calendar">
                            <div class="panel-heading calendar-month"><strong>October</strong></div>
                            <div class="panel-body">
                                <div class="thumbnail calendar-date" >
                                    03
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <strong>Title Of Milestone</strong><br>
                        This is  a short description of milestone.
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="done" onchange="showModal()"> Done
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- /#page-content-wrapper -->

</div>

<!--new update modal-->
<div class="modal fade" id="newUpdateModal" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" >New Update</h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control"  id="update_title" >
                    </div>
                    <div class="form-group">
                        <label for="description">Content:</label>
                        <textarea class="form-control" rows="4" id="description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
<!--new milestone modal-->
<div class="modal fade" id="newMilestoneModal" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" >New Milestone</h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control"  id="milestone_title" >
                    </div>
                    <div class="form-group">
                        <label for="title">Deadline:</label>
                        <input type="text" class="form-control"  id="deadline" >
                    </div>
                    <div class="form-group">
                        <label for="milestone_description">Description:</label>
                        <textarea class="form-control" rows="4" id="milestone_description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
<!--update phase modal-->
<div class="modal fade" id="updatePhaseModal" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Next Phase</h4>
            </div>

            <form role="form" action="<?=base_url().'Project_phase/update_phase/'.$p["project_id"].'/'.$current_project_phase_id?>" method="post">

            <div class="modal-body">
                <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" disabled value="<?=$next_phase?>" id="title" >
                    </div>
                    <div class="form-group">
                        <label for="estimated_end_time">Estimated End Date:</label>
                        <input name="estimated_end_time" type="text" class="form-control" id="estimated_end_time">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Update">
            </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>