<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head lang="en">
    <?php $this->load->view('common/common_header');?>
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
        $(document).ready(function(){
            $('#customerTable').dataTable();
        });
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
        <li>
            <a href="projects.html"><i class="fa fa-fw fa-bar-chart-o"></i> Projects</a>
        </li>
        <li>
            <a href="chat.html"><i class="fa fa-fw fa-comment"></i> Message</a>
        </li>
        <li  class="active">
            <a href="<?=base_url().'Customers/list_all'?>"><i class="fa fa-fw fa-users"></i>Customer</a>
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
        -->
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

<div class="col-lg-offset-1 col-lg-10 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            Customers
        </h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped" id="customerTable">
                <thead>
                <th>Title</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Company</th>
                <th>hp number</th>
                <th>Other number</th>
                <th>Email</th>
                <th>Status</th>
                <th>Projects</th>
                <th></th>
                </thead>

                <?php if(!false == $customers):?>
                    <?php foreach($customers as $c):?>
                        <tr><td><?=$c['title']?></td>
                            <td><?=$c['first_name']?></td>
                            <td><?=$c['last_name']?></td>
                            <td><?=$c['company_name']?></td>
                            <td><?=$c['hp_number']?></td>
                            <td><?=$c['other_number']?></td>
                            <td><?=$c['email']?></td>
                            <td>
                                <?php if($c['is_active']==1):?>
                                    Active
                                <?php else:?>
                                    Deactivated
                                <?php endif?>
                            </td>
                            <td>Projects</td>
                            <td><a href="<?=base_url().'Customers/update_customer/'.$c["c_id"]?>" class="btn btn-primary" type="button" ><i class="fa fa-pencil-square-o"></i></a></td>
                        </tr>
                    <?php endforeach?>
                <?php endif?>
            </table>
        </div>
    </div>
</div>
</body>
</html>