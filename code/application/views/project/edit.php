<!DOCTYPE html>
<html>
<?php
$p=$project;
//                    echo var_dump($p);
?>
<head lang="en">
    <meta charset="UTF-8">
    <!-- Bootstrap Core CSS -->
    <?php $this->load->view('common/common_header');?>
    <link href="<?=base_url()?>css/plugins/bootstrap-tokenfield.min.css" rel="stylesheet">
    <link href="<?=base_url()?>css/plugins/tokenfield-typeahead.min.css" rel="stylesheet">
    <link href="<?=base_url()?>css/sb-admin.css" rel="stylesheet">
    <script src="<?= base_url() . 'js/jquery-ui.min.js' ?>"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Francois+One" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.1/modernizr.min.js"></script>
    <script src="<?=base_url()?>js/plugins/bootstrap-tokenfield.min.js"></script>

    <script>
        $(document).ready(function(){
            $("#customer-option").on("change",function(){
                cus_option();
            });
        });
        function cus_option(){
            if($("#customer-option").val()=="from-existing"){
                $('#existing_customer').show();
                $('#new_customer').hide();
            }else{
                $('#existing_customer').hide();
                $('#new_customer').show();
            }
        };

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
        $('#tokenfield').tokenfield({
            autocomplete: {source: <?=$tags ?>,delay: 100},
            showAutocompleteOnFocus: true
        });

        $(document).ready(function(){
            $("body").on("click","#cancel",function(e){
                e.preventDefault();
                location.reload();
            });
            $("body").on('click',"#submit",function(e){
                e.preventDefault();
                $("#form").submit();
            })
        })
    </script>
</head>
<body onload="cus_option()">

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

    <!-- Page Content -->

    <div class="container">
        <div class="col-lg-12">
            <h1 class="page-header">
                Edit Project&nbsp;
                <a href="#" class="btn btn-default" id="cancel">Cancel</a>&nbsp;
                <a href="#" class="btn btn-primary"id="submit">Submit</a>
            </h1>
        </div>
        <form class="form-horizontal" id="form" method="POST" action="<?=base_url()."Projects/process_edit/".$p["project_id"]?>">
            <div class="col-lg-6 project-info">
                <h3>Project Information</h3>
                <hr>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="project_title">Title*</label>
                        <input class="form-control" id="project_title" name="project_title" value="<?=$p['project_title']?>">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="project_description">Description</label>
                        <textarea class="form-control" id="project_description" name="project_description" ><?=$p['project_description']?></textarea>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label for="file_repo_name">File repo name</label>
                        <input class="form-control" name="file_repo_name" value="<?=$p['file_repo_name']?>">
                    </div>
                </div>
                <div class="col-lg-offset-1 col-lg-6">
                    <div class="form-group">
                        <label for="bitbucket_repo_name">Bitbucket repo name</label>
                        <input class="form-control" name="bitbucket_repo_name" value="<?=$p['bitbucket_repo_name']?>">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label for="no_of_use_cases">Number of usecases</label>
                        <input class="form-control" name="no_of_use_cases" value="<?=$p['no_of_use_cases']?>">
                    </div>
                </div>
                <div class="col-lg-offset-1 col-lg-6">
                    <div class="form-group">
                        <label for="project_value">Project value</label>
                        <input class="form-control" name="project_value" value="<?=$p['project_value']?>">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input class="form-control " id="tokenfield" name="tags" value="<?=$p['tags']?>">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <input class="form-control" name="remarks" value="<?=$p['remarks']?>">
                    </div>
                </div>
                <hr>
            </div>
            <div class="col-lg-5 customer-info">
                <h3>Customer Information</h3>
                <hr>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="customer-option"> Customer</label>
                        <select class="form-control" id="customer-option" name="customer-option" onchange="cus_option()">
                            <option value="create-new">Create new</option>
                            <option value="from-existing" selected>From existing</option>
                        </select>
                    </div>
                    <div id="existing_customer">
                        <div class="form-group">
                            <label >Choose Customer:</label>
                            <select class="form-control" name="c_id">
                                <?php foreach($customers as $c):?>
                                    <?php if($c['c_id']==$p['c_id']):?>
                                        <option value="<?=$c['c_id']?>" selected><?=$c['first_name']?>&nbsp;<?=$c['last_name']?></option>
                                    <?php else:?>
                                        <option value="<?=$c['c_id']?>"><?=$c['first_name']?>&nbsp;<?=$c['last_name']?></option>
                                    <?php endif?>
                                <?php endforeach?>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="new_customer">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <label for="first_name">First name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="last_name">Last name</label>
                            <input type="text" class="form-control"  name="last_name" id="last_name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="company_name">Company name</label>
                            <input type="text" class="form-control" name="company_name" id="company_name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="hp_number">HP Number</label>
                            <input type="text" class="form-control" name="hp_number" id="hp_number">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="other_number">Other Number</label>
                            <input type="text" class="form-control" name="other_number" id="other_number" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="username" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input class="form-control" name="password" id="password" value="<?=DEFAULT_PASSWORD?>">
                        </div>
                    </div>

                    <hl></hl>
                </div>
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
                </form>
    </div>


</body>
</html>