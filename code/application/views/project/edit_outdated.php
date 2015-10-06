<!DOCTYPE html>
<html>
<head lang="en">


    <?php $this->load->view('common/common_header');?>
    <link href="<?=base_url()?>css/plugins/bootstrap-tokenfield.min.css" rel="stylesheet">
    <link href="<?=base_url()?>css/plugins/tokenfield-typeahead.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?=base_url()?>css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Francois+One" />
    <link rel="stylesheet" href="<?=base_url()?>css/sidebar-left.css">
    <!-- Custom Fonts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.1/modernizr.min.js"></script>
    <script src="<?=base_url()?>js/plugins/bootstrap-tokenfield.min.js"></script>


    <script>


        $("#customer-option").on("change",function(){
            if($(this).value()=="from-existing"){
                $('#existing_customer').css("display","inherit");
                $('#new_customer').css("display","none");
            }else{
                $('#existing_customer').css("display","none");
                $('#new_customer').css("display","inherit");
            }
        });
        $(".date-start").on("blur",function(){
            var prev_id = $(this).attr("id").split("-")[2] - 1;
            var value= $(this).val();
            $("phase-end-"+prev_id).val(value);
        });
        $(".date-end").on("blur",function(){
            var next_id = $(this).attr("id").split("-")[2] + 1;
            var value= $(this).val();
            $("phase-end-"+next_id).val(value);
        });
        $('.tokenfield').tokenfield({
                    autocomplete: {
                        source: <?= $tags ?>
                    },
                    showAutocompleteOnFocus: false
        })
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
            <a href="customers.html"><i class="fa fa-fw fa-users"></i>Customer</a>
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
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue selected" href="projectDashboard.html"><i class="fa fa-tasks"></i>Dashboard</a>
        <a class="link-blue " href="projectUpdate.html"><i class="fa fa-flag"></i>Update & Milestone</a>
        <a class="link-blue " href="projectIssues.html"><i class="fa fa-wrench"></i>Issues</a>
        <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
    </div>

</aside>
<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            #1. Inventory Management System <small>- Edit Detail</small>
        </h1>
    </div>
    <?php $p=$project;
    echo var_dump($p);
    ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-8 col-lg-offset-1">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="project_title">Title</label>
                        <input class="form-control" id="project_title" name="project_title" value="<?=$p['project_title']?>">
                    </div>
                    <div class="form-group">
                        <label for="project_description">Description</label>
                        <textarea class="form-control" id="project_description" name="project_description" ><?=$p['project_description']?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="file_repo_name">File repo name</label>
                        <input class="form-control" name="file_repo_name" value="<?=$p['file_repo_name']?>">
                    </div>
                    <hl></hl>
                    <div class="form-group">
                        <label for="customer-option"> Customer</label>
                        <select class="form-control" id="customer-option" name="customer-option">
                            <option value="from-existing">From existing</option>
                            <option value="create-new">Create new</option>
                        </select>
                    </div>
                    <div id="existing_customer">
                        <div class="form-group">
                            <label >Choose Customer:</label>
                            <select class="form-control">
                                <?php foreach($customers as $c):?>
                                    <?php if($c['c_id']==$p['c_id']):?>
                                        <option <?=$c['c_id']?> selected><?=$c['first_name']?>&nbsp;<?=$c['last_name']?></option>
                                    <?php else:?>
                                        <option <?=$c['c_id']?>><?=$c['first_name']?>&nbsp;<?=$c['last_name']?></option>
                                    <?php endif?>
                                <?php endforeach?>
                            </select>
                        </div>
                    </div>
                    <div id="new_customer"  style="display:none">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title">
                        </div>
                        <div class="form-group">
                            <label for="first_name">First name</label>
                            <input type="text" name="first_name" id="first_name">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last name</label>
                            <input type="text" name="last_name" id="last_name">
                        </div>
                        <div class="form-group">
                            <label for="company_name">Company name</label>
                            <input type="text" name="company_name" id="company_name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email">
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username">
                        </div>
                        <div class="form-group">
                            <label for="hp_number">HP Number</label>
                            <input type="text" name="hp_number" id="hp_number">
                        </div>
                        <div class="form-group">
                            <label for="other_number">Other Number</label>
                            <input type="text" name="other_number" id="other_number">
                        </div>
                    </div>
                    <!--will generate components based on the selection input above-->
                    <hl></hl>
                    <div class="form-group">
                        <label for="no_of_use_cases">Number of usecases</label>
                        <input class="form-control" name="no_of_use_cases" value="<?=$p['no_of_use_cases']?>">
                    </div>
                    <div class="form-group">
                        <label for="bitbucket_repo_name">Bitbucket repo name</label>
                        <input class="form-control" name="bitbucket_repo_name" value="<?=$p['bitbucket_repo_name']?>">
                    </div>
                    <div class="form-group">
                        <label for="project_value">Project value</label>
                        <input class="form-control" name="project_value" value="<?=$p['project_value']?>">
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input class="form-control tokenfield" name="tags" value="<?=$p['tags']?>">
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <input class="form-control" name="remarks" value="<?=$p['remarks']?>">
                    </div>
                    <hl></hl>
                    <?php foreach($phases as $phs):?>
                        <?=$phs["name"]?>
                        <?php $start='phase-start-'.$phs["id"]?>
                        <?php $end='phase-end-'.$phs["id"]?>
                        <div class="form-group">
                            <label for="<?=$start?>">Start date for <?=$phs["name"]?></label>
                            <input class="form-control date-start datetimepicker" id="<?=$start?>" name='<?=$start?>' value="<?=$p['remarks']?>">
                        </div>
                        <div class="form-group">
                            <label for="<?=$end?>">End date for <?=$phs["name"]?></label>
                            <input class="form-control date-end datetimepicker" id="<?=$end?>" name='<?=$end?>' value="<?=$p['remarks']?>">
                        </div>
                    <?php endforeach?>
                    <div class="pull-right">
                        <a href="projectDashboard.html" class="btn btn-default">Cancel</a>&nbsp;
                        <a href="projectDashboard.html" class="btn btn-primary">Submit</a>&nbsp;
                    </div>
                </form>
            </div>
        </div>
        </div>


    </div>

    <!-- /#page-content-wrapper -->

</div>
</body>
</html>