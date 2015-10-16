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

<?php $this->load->view('common/pm_nav');?>
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
            <?='#'.$project['project_id'].'. '.strtoupper($project['project_title'])?>
            <a href="<?=$project['staging_link']?>" class="btn btn-primary"><i class="fa fa-external-link"></i>&nbsp;Project Preview</a>
        </h1>
        <h4 style="color:darkgrey">Click each phase on timeline to check updates for each phase.</h4>
    </div>

    <!-- /.row -->
    <div class="row">
        <div class="col-lg-offset-1 no-gutter">

            <?php
            $current_phase;
            foreach($phases as $phase){
                $img_tag='img/future.png';
                if(isset($phase['project_phase_id'])){
                    $img_tag = 'img/done.png';
                    if ($phase['phase_id'] == $project['current_project_phase_id']){
                        $img_tag = 'img/current.png';
                        $current_phase=$phase;
                    }

                    echo'<div id="<?=$phase[\'phase_name\']?>" class="test col-sm-2 " align="center" data-toggle="tooltip"
                data-placement="bottom" title="'.$phase['start_time'].' to '.$phase['end_time'].'">'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
                }else{
                    echo' <div  class="test col-sm-2" align="center" >'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
                } }?>

        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-7">
                <h3>Client Updates - <small id="phase"><?=$current_phase['phase_name']?></small><button class="btn btn-primary pull-right" data-toggle="modal" data-target="#newUpdateModal"><i class="fa fa-plus"></i>&nbsp; Add</button></h3><hr>
                <ul class="timeline">
                    <?php
                        foreach($updates as $u){
                    ?>
                            <li><!---Time Line Element--->
                                <div class="timeline-badge  neutral"><i class="fa fa-navicon"></i></div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title"><?=$u['header']?></h4>
                                    </div>
                                    <div class="timeline-body"><!---Time Line Body&Content--->
                                        <p><?=$u['body']?></p>
                                        <div class="pull-right timeline-info">
                                            <i class="fa fa-user"></i>&nbsp;<?=$u['posted_by']?> &nbsp;
                                            <i class="fa fa-calendar-check-o"></i>&nbsp;<?=$u['last_updated']?></div>
                                    </div>
                                </div>
                            </li>
                    <?php
                        }
                    ?>

                </ul>
            </div>
            <div class="col-lg-4">

                <h3>Milestones - <small><?=$current_phase['phase_name']?></small><button class="btn btn-primary pull-right" data-toggle="modal" data-target="#newMilestoneModal"><i class="fa fa-plus"></i>&nbsp; Add</button></h3><hr>
                <?php
                    foreach($milestones as $m){
                        $monthName = date('F', strtotime($m['deadline']));
                        $dateNumber = date('j', strtotime($m['deadline']));
                ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="panel panel-default calendar">
                                    <div class="panel-heading calendar-month"><strong><?=$monthName?></strong></div>
                                    <div class="panel-body">
                                        <div class="thumbnail calendar-date" >
                                            <?=$dateNumber?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <strong><?=$m['header']?></strong><br>
                                <?=$m['body']?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="done" onchange="showModal()"> Done
                                    </label>
                                </div>
                            </div>
                         </div>
                <?php
                    }
                ?>

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
            <form role="form" action="<?=base_url().'Updates/add_new_update/'.$project['project_id'].'/'.$project['current_project_phase_id'].'/'.$current_phase?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input name="update_header" type="text" class="form-control"  id="update_title" >
                    </div>
                    <div class="form-group">
                        <label for="description">Content:</label>
                        <textarea name="update_body" class="form-control" rows="4" id="description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>

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

            <form role="form" action="<?=base_url().'Milestones/add_new_milestone/'.$project['project_id'].'/'.$project['current_project_phase_id'].'/'.$current_phase?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input name="header" type="text" class="form-control"  id="milestone_title" >
                    </div>
                    <div class="form-group">
                        <label for="title">Deadline:</label>
                        <input name="deadline" type="text" class="form-control"  id="deadline" >
                    </div>
                    <div class="form-group">
                        <label for="milestone_description">Description:</label>
                        <textarea name="body" class="form-control" rows="4" id="milestone_description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>
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