
<body>

<?php ?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?=base_url().'projects/list_all'?>">The Shipyard </a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-nav">
        <li  class="<?=$dashboard_class?>">
            <a href="<?=base_url().'dashboard'?>"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
        </li>
        <li  class="<?=$projects_class?>">
            <a href="<?=base_url().'projects/list_all'?>"><i class="fa fa-fw fa-bar-chart-o"></i> Projects</a>
        </li>
        <li class="<?=$message_class?>">
            <a href="<?=base_url().'chat'?>"><i class="fa fa-fw fa-comment"></i> Message&nbsp;<span class="badge" style="background-color:red">4</span></a>
        </li>
        <li class="<?=$customers_class?>">
            <a href="<?=base_url().'customers/list_all'?>"><i class="fa fa-fw fa-users"></i>Customers</a>
        </li>
        <li class="<?=$internal_user_class?>">
            <a href="<?=base_url().'internal_users/list_all'?>"><i class="fa fa-fw fa-users"></i>Internal Users</a>
        </li>
        <li class="<?=$analytics_class?>">
            <a href="#"><i class="fa fa-fw fa-line-chart"></i>Analytics</a>
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
            <a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">
                <i class="fa fa-fw fa-bell"></i>Notifications&nbsp;<span class="badge" style="background-color:red">4</span>
            </a>

            <ul class="dropdown-menu notifications" role="menu" aria-labelledby="dLabel">

                <div class="notification-heading"><h4 class="menu-title">Notifications</h4>
                </div>
                <li class="divider"></li>
                <div class="notifications-wrapper">

                    <a class="content" href="#">
                        <div class="notification-item-green">
                            <h4 class="item-title">Evaluation Deadline 1 · day ago</h4>
                            <p class="item-info">Marketing 101, Video Assignment</p>
                        </div>
                    </a>
                    <a class="content" href="#">
                        <div class="notification-item-yellow">
                            <h4 class="item-title">Evaluation Deadline 1 • day ago</h4>
                            <p class="item-info">Marketing 101, Video Assignment</p>
                        </div>
                    </a>
                    <a class="content" href="#">
                        <div class="notification-item-pink">
                            <h4 class="item-title">Evaluation Deadline 1 • day ago</h4>
                            <p class="item-info">Marketing 101, Video Assignment</p>
                        </div>
                    </a>
                    <a class="content" href="#">
                        <div class="notification-item-green">
                            <h4 class="item-title">Evaluation Deadline 1 · day ago</h4>
                            <p class="item-info">Marketing 101, Video Assignment</p>
                        </div>
                    </a>
                    <a class="content" href="#">
                        <div class="notification-item-yellow">
                            <h4 class="item-title">Evaluation Deadline 1 • day ago</h4>
                            <p class="item-info">Marketing 101, Video Assignment</p>
                        </div>
                    </a>


                </div>
                <li class="divider"></li>
                <div class="notification-footer"><h4 class="menu-title">View all<i class="glyphicon glyphicon-circle-arrow-right"></i></h4></div>
            </ul>

        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>&nbsp;<?=$this->session->userdata('internal_username')?><b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="<?=base_url('internal_authentication/change_password')?>"><i class="fa fa-fw fa-gear"></i>Change Password</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="<?=base_url('internal_authentication/logout')?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>