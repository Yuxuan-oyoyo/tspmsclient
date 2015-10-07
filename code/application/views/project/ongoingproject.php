<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../code/css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .no-gutter [class*="-2"] {
            padding-left: 0;
            padding-right: 0;
        }
        .clickable{
            cursor: pointer;
        }
        .panel-heading span {
            margin-top: -20px;
            font-size: 15px;
        }
        .thumbnail {
            background: #FFFFFF;
            height:70px;
        }
        .panel >.panel-heading {
            color: #FFFFFF;
            height:72px;
            font-size:18px;

        }
        </style>
    <script>
        function updateCustomer(){
            if($('#customer_type').val()=="existing"){
                $('.new_group').hide();
                $('.existing_group').show();
            }else{
                $('.new_group').show();
                $('.existing_group').hide();
            }
        }
    </script>

</head>

<body onload="updateCustomer()">

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html">The Shipyard</a>
        </div>
        <!-- Top Menu Items -->
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
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="active">
                    <a href="index.html"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                </li>
                <li>
                    <a href="allprojects.html"><i class="fa fa-fw fa-bar-chart-o"></i> Projects</a>
                </li>
                <li>
                    <a href="chat.html"><i class="fa fa-fw fa-comment"></i> Message</a>
                </li>
                <li>
                    <a href="customers.html"><i class="fa fa-fw fa-users"></i>Customer</a>
                </li>
                <li>
                    <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Issue Tracker</a>
                </li>
                <li>
                    <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> File Repo</a>
                </li>
                <li>
                    <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Tasks</a>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Analytics Report <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo" class="collapse">
                        <li>
                            <a href="#">Report 1</a>
                        </li>
                        <li>
                            <a href="#">Report 2</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Ongoing Projects&nbsp;&nbsp;
                        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus">&nbsp;</i>New Project</button>
                        <div class="pull-right col-lg-3 input-group ">
                            <input type="text" class="form-control">
                            <span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-search"></i></button></span>
                        </div>
                    </h1>
                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i> <a href="index.html">Dashboard</a>
                        </li>
                        <li>
                            <a href="allprojects.html">All Projects</a>
                        </li>
                        <li class="active">
                            Ongoing Projects
                        </li>

                    </ol>
                </div>
            </div>

            <!-- /.row -->

            <div class="row">
                <div class=" col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading" style="text-align:center" ><strong>#1 Project Inventory Management System</strong></div>
                        <div class="panel-body" style="font-size:15px" >
                            <table class="table table-condensed">
                                <tr>
                                    <td><i class="fa fa-calendar-check-o"></i>&nbsp;<strong>Current Stage </strong></td>
                                    <td>Build</td>
                                </tr>
                                <tr>
                                    <td> <i class="fa fa-link"></i>&nbsp;<strong>Staging link </strong></td>
                                    <td> <a href="http://fortawesome.github.io/Font-Awesome/icon/link/">Click here</a></td>
                                </tr>
                                <tr>
                                    <td>  <i class="fa fa-user"></i>&nbsp;<strong>Customer </strong></td>
                                    <td> <a href="#">Gary Lee</a></td>
                                </tr>
                                <tr>
                                    <td>  <i class="fa fa-clock-o"></i>&nbsp;<strong>Number Of Issues </strong></td>
                                    <td> 3</td>
                                </tr>
                            </table>

                            <a href="issueTracker.html" class="btn pull-right btn-success"><i class="fa fa-wrench"></i> &nbsp;Issue Tracker</a>
                            <a href="projectTimeline.html" class="btn btn-info" role="button"><i class=" fa fa-tasks"></i> &nbsp;Progress Tracker</a>

                        </div>
                    </div>
                </div>
                <div class=" col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading" style="text-align:center" ><strong>#2 Taxi Booking System</strong></div>
                        <div class="panel-body" style="font-size:15px" >
                            <table class="table table-condensed">
                                <tr>
                                    <td><i class="fa fa-calendar-check-o"></i>&nbsp;<strong>Current Stage </strong></td>
                                    <td>Build</td>
                                </tr>
                                <tr>
                                    <td> <i class="fa fa-link"></i>&nbsp;<strong>Staging link </strong></td>
                                    <td> <a href="http://fortawesome.github.io/Font-Awesome/icon/link/">Click here</a></td>
                                </tr>
                                <tr>
                                    <td>  <i class="fa fa-user"></i>&nbsp;<strong>Customer </strong></td>
                                    <td> <a href="#">Gary Lee</a></td>
                                </tr>
                                <tr>
                                    <td>  <i class="fa fa-clock-o"></i>&nbsp;<strong>Number Of Issues </strong></td>
                                    <td> 20</td>
                                </tr>
                            </table>

                            <a href="issueTracker.html" class="btn pull-right btn-success"><i class="fa fa-wrench"></i> &nbsp;Issue Tracker</a>
                            <a href="projectTimeline.html" class="btn btn-info" role="button"><i class=" fa fa-tasks"></i> &nbsp;Progress Tracker</a>
                        </div>
                    </div>
                </div>

                <div class=" col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading" style="text-align:center" ><strong>#3 Account Management System at Liniwd Sjff</strong></div>
                        <div class="panel-body" style="font-size:15px" >
                            <table class="table table-condensed">
                                <tr>
                                    <td><i class="fa fa-calendar-check-o"></i>&nbsp;<strong>Current Stage </strong></td>
                                    <td>Build</td>
                                </tr>
                                <tr>
                                    <td> <i class="fa fa-link"></i>&nbsp;<strong>Staging link </strong></td>
                                    <td> <a href="http://fortawesome.github.io/Font-Awesome/icon/link/">Click here</a></td>
                                </tr>
                                <tr>
                                    <td>  <i class="fa fa-user"></i>&nbsp;<strong>Customer </strong></td>
                                    <td> <a href="#">Gary Lee</a></td>
                                </tr>
                                <tr>
                                    <td>  <i class="fa fa-clock-o"></i>&nbsp;<strong>Number Of Issues </strong></td>
                                    <td> 76</td>
                                </tr>
                            </table>

                            <a href="issueTracker.html" class="btn pull-right btn-success"><i class="fa fa-wrench"></i> &nbsp;Issue Tracker</a>
                            <a href="projectTimeline.html" class="btn btn-info" role="button"><i class=" fa fa-tasks"></i> &nbsp;Progress Tracker</a>

                        </div>
                    </div>
                </div>

            </div>


        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">New Project</h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                    <label >Title:</label>
                    <input type="text" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label >Description:</label>
                        <textarea rows="3" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label >Project Value:</label>
                        <input type="email" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label >Tags:</label>
                        <input type="email" class="form-control" placeholder="please separate with #">
                    </div>
                    <div class="form-group">
                        <label >Remarks:</label>
                        <textarea rows="3" class="form-control"></textarea>
                    </div>
                    <hr>
                    <h4>Customer Info</h4>
                    <hr>
                    <div class="form-group">
                        <label >Customer type:</label>
                        <select class="form-control" id ="customer_type" onchange="updateCustomer()">
                            <option value="existing">Existing</option>
                            <option value="new">New Customer</option>
                        </select>
                    </div>

                    <div class="form-group existing_group">
                        <label >Choose Customer:</label>
                        <select class="form-control">
                            <option>Gary Lee - CoachReg</option>
                            <option>Amanda Ng- ABC pte.Ltd</option>
                            <option>Leo Tan- EEE pte.Ltd</option>
                        </select>
                    </div>
                    <div class="new_group">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text">
                            <label>Name</label>
                            <input type="text">
                        </div>
                        <div class="form-group">

                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text">
                            <label>Email</label>
                            <input type="text">
                        </div>
                        <div class="form-group">
                            <label>hp Number</label>
                            <input type="text">
                            <label>other Number</label>
                            <input type="text">
                        </div>
                    </div>
                </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Morris Charts JavaScript -->
<script src="js/plugins/morris/raphael.min.js"></script>
<script src="js/plugins/morris/morris.min.js"></script>
<script src="js/plugins/morris/morris-data.js"></script>

</body>

</html>