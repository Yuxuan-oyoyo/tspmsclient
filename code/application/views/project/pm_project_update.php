
<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $(document).ready(function(){
            $("#Lead" ).click(function() {
                // this change title
                $(".phase").each(function(){
                    $(this).text("Lead");
                });
                var project_phase_id=($(this).data().id);
                // this changes updates
                refillUpdates(project_phase_id,"Lead");
                refillMilestones(project_phase_id,"Lead");
            });
            $("#Requirement" ).click(function() {
                // this change title
                $(".phase").each(function(){
                    $(this).text("Requirement");
                });
                var project_phase_id=($(this).data().id);
                // this changes updates
                refillUpdates(project_phase_id,"Requirement");
                refillMilestones(project_phase_id,"Requirement");
            });
            $("#Build" ).click(function() {
                // this change title
                $(".phase").each(function(){
                    $(this).text("Build");
                });
                var project_phase_id=($(this).data().id);
                // this changes updates
                refillUpdates(project_phase_id,"Build");
                refillMilestones(project_phase_id,"Build");
            });
            $("#Testing" ).click(function() {
                // this change title
                $(".phase").each(function(){
                    $(this).text("Testing");
                });
                var project_phase_id=($(this).data().id);
                // this changes updates
                refillUpdates(project_phase_id,"Testing");
                refillMilestones(project_phase_id,"Testing");
            });
            $("#Deploy" ).click(function() {
                // this change title
                $(".phase").each(function(){
                    $(this).text("Deploy");
                });
                var project_phase_id=($(this).data().id);
                // this changes updates
                refillUpdates(project_phase_id,"Deploy");
                refillMilestones(project_phase_id,"Deploy");
            });

            $('#deadlinePicker').datepicker({
                dateFormat: 'dd-mm-yy',
                minDate: '+5d',
                changeMonth: true,
                changeYear: true,
                altFormat: "yy-mm-dd"
            });
            $('#estimated_end_time').datepicker({
                dateFormat: 'dd-mm-yy',
                minDate: '+5d',
                changeMonth: true,
                changeYear: true,
                altFormat: "yy-mm-dd"
            });

        });

        function refillUpdates(project_phase_id,phase_name){
            if(project_phase_id!=<?=$project['current_project_phase_id']?>){
                $(".add_button").hide();
            }else{
                $(".add_button").show();
            }
            $("#timeline").text('');
            $.get("<?=base_url('Updates/get_update_by_project_phase/')?>"+'/'+project_phase_id,function(data,status){
                $('#updates-phase').replaceWith('<small>'+phase_name+'</small>');
                var updates = jQuery.parseJSON(data);
                updates.forEach(function(element){
                    var htmlText =
                        '<li>'+
                        '<div class="timeline-badge  neutral"><i class="fa fa-navicon"></i></div>'+
                        '<div class="timeline-panel"> <div class="timeline-heading"> <h4 class="timeline-title">'+element.header+'</h4> </div>'+
                        '<div class="timeline-body"> <p>'+element.body+'</p> <div class="pull-right timeline-info">'+
                        '<i class="fa fa-user"></i>&nbsp;'+element.posted_by+' &nbsp;'+
                        '<i class="fa fa-calendar-check-o"></i>&nbsp;'+element.last_updated+'</div>'+
                        ' </div> </div> </li>';
                    $('#timeline').append( htmlText );
                });
            });
        }

        function refillMilestones(project_phase_id,phase_name){
            $("#milestone").text('');
            var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];
            $.get("<?=base_url('Milestones/get_by_project_phase_id/')?>"+'/'+project_phase_id, function(data, status){
                $('#milestones-phase').replaceWith('<small>'+phase_name+'</small>');
                var updates = jQuery.parseJSON(data);
                updates.forEach(function(element){
                    var append;
                    if (element.if_completed==0){
                        append=' <div class="checkbox"> <label> <input type="checkbox" id="done" onchange="completeMilestoneButtonClicked('+ element.milestone_id+')" > Complete </label> </div>';
                    }else{
                        append='  <br><span class="badge success" style="background-color: limegreen">Completed</span>';
                    }
                    var ddl=new Date(element.deadline);
                    var day = ddl.getDate();
                    var month=monthNames[ddl.getMonth()];
                    var year=ddl.getFullYear();
                    var htmlText = ' <div class="row"> <div class="col-lg-4"> <div class="panel panel-default calendar"> ' +
                        '<div class="panel-heading calendar-month" style="text-align:center;background:#EA9089;color:white"><strong>'+month+'-'+year+'</strong></div>'+
                        '<div class="panel-body"> <div class="thumbnail calendar-date" >'+day+' </div> </div> </div> </div> <div class="col-lg-7">'+
                        '<strong>'+element.header+'</strong>'+
                        '<i class="fa fa-close pull-right" style="cursor: pointer;color:darkgray" onclick="deleteMilestoneButtonClicked('+element.milestone_id+')"></i>'+
                        '<br>'+element.body+append+
                        ' </div> </div>';

                    $('#milestone').append( htmlText );
                });
            });
        }

        function deleteMilestoneButtonClicked(milestone_id) {
            $('#milestoneDeleteModal').data('milestone_id', milestone_id).modal('show');

        }
        function completeMilestoneButtonClicked(milestone_id) {
            if(document.getElementById('done').checked) {
                $('#milestoneCompletionModal').data('milestone_id', milestone_id).modal('show');
            }
        }
        function deleteUpdateButtonClicked(update_id) {
                $('#updateDeleteModal').data('update_id', update_id).modal('show');
        }
        function confirmUpdateDelete() {
            // handle deletion here
            var uid = $('#updateDeleteModal').data('update_id');
            //to be change to delete update controller
            var delete_u_url = "<?= base_url().'Updates/delete_update/'.$project['project_id'].'/' ?>" + uid;
            window.location.href = delete_u_url;
        }
        function confirmMilestoneComplete() {
            var mid = $('#milestoneCompletionModal').data('milestone_id');
            var complete_m_url = "<?= base_url() . 'Milestones/completionConfirmation/' . $project['project_id'] . '/' ?>" + mid;
           window.location.href = complete_m_url;
        }

        function confirmMilestoneDelete() {
            // handle deletion here
            var mid = $('#milestoneDeleteModal').data('milestone_id');
            //to be change to delete milestone controller
            var delete_m_url = "<?= base_url().'Milestones/delete_milestone/'.$project['project_id'].'/' ?>" + mid;
            window.location.href = delete_m_url;
        }

    </script>
</head>
<body>
<?php
$class = [
    'projects_class'=>'active',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>




<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue " href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i>Dashboard</a>
        <a class="link-blue selected" href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i>Update & Milestone</a>
        <a class="link-blue " href="projectIssues.html"><i class="fa fa-wrench"></i>Issues</a>
        <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
    </div>
</aside>

<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            <?='#'.$project['project_id'].'. '.strtoupper($project['project_title'])?>
            <?php
                $if_completed = 1;
                foreach ($milestones as $m) {
                    if($m['if_completed']==0){
                        $if_completed = 0;
                    }
                }
            $update_phase_button = 'Update Phase';
                if(is_null($next_phase_name)){
                    $update_phase_button = 'End Project';
                }
                if( $if_completed==0){
            ?>
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#update_phase_alert_modal"><i class="fa fa-pencil-square-o"></i>&nbsp;<?=$update_phase_button?></button>
             <?php
                }else{
             ?>
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#updatePhaseModal"><i class="fa fa-pencil-square-o"></i>&nbsp;<?=$update_phase_button?></button>
            <?php
            }
            ?>
        </h1>
        <h4 style="color:darkgrey">Click each phase on timeline to check updates for each phase.</h4>
    </div>

    <!-- /.row -->
    <div class="row">
        <div class="col-lg-offset-1 no-gutter">

            <?php
            $current_phase;
            foreach($phases as $phase){
                $past_project_phase = $phase;
                $phase_end_time = $phase['end_time'];
                if(!isset($phase_end_time)){
                    $phase_end_time = "now";
                }
                $img_tag='img/future.png';
                if(isset($phase['project_phase_id'])){
                    if(!$phase['phase_id']==0) {
                        $img_tag = 'img/done.png';

                        if ($phase['project_phase_id'] == $project['current_project_phase_id']) {
                            $img_tag = 'img/current.png';
                            $current_phase=$phase;
                        }
                        echo'<div data-id="'.$phase['project_phase_id'].'" id="'.$phase['phase_name'].'" class="test col-sm-2 " align="center" data-toggle="tooltip"
                data-placement="bottom" title="'.$phase['start_time'].' to '.$phase_end_time.'">'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
                    }else{
                        $current_phase=$phase;
                    }
                }else{
                    echo' <div  class="test col-sm-2" align="center" >'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
                }
            }
            if($project['current_project_phase_id']==-1){
                $current_phase = $past_project_phase;
            }
            ?>

        </div>
    </div>
    <hr>
    <?php
        if(!isset($phases[0]['project_phase_id'])){
    ?>
    <div class="alert alert-warning" role="alert"><strong>This project hasn't been started. Please update phase to start the project.</strong></div>
    <?php
        }else{
    ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-7">

                <h3>Client Updates -
                    <small class="phase"><?= $current_phase['phase_name'] ?></small>
                    <button class="add_button btn btn-primary pull-right" data-toggle="modal"
                            data-target="#newUpdateModal"><i class="fa fa-plus"></i>&nbsp; Add
                    </button>
                </h3>
                <hr>
                <?php
                foreach ($updates as $u){
                ?>
                <ul class="timeline" id="timeline">
                    <li><!---Time Line Element--->
                        <div class="timeline-badge  neutral"><i class="fa fa-navicon"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title"><?= $u['header'] ?>
                                    <i class="fa fa-close pull-right small "  style="cursor: pointer;color:darkgray" onclick="deleteUpdateButtonClicked(<?=$u['update_id']?>)"></i>
                                </h4>
                            </div>
                            <div class="timeline-body"><!---Time Line Body&Content--->
                                <p><?= $u['body'] ?></p>

                                <div class="pull-right timeline-info">
                                    <i class="fa fa-user"></i>&nbsp;<?= $u['posted_by'] ?> &nbsp;
                                    <i class="fa fa-calendar-check-o"></i>&nbsp;<?= $u['last_updated'] ?></div>
                            </div>
                        </div>
                    </li>
                    <?php
                    }
                    ?>

                </ul>
            </div>
            <div class="col-lg-4">

                <h3>Milestones -
                    <small class="phase"><?= $current_phase['phase_name'] ?></small>
                    <button class="add_button btn btn-primary pull-right" data-toggle="modal"
                            data-target="#newMilestoneModal"><i class="fa fa-plus"></i>&nbsp; Add
                    </button>
                </h3>
                <hr>
                <div id="milestone">
                    <?php
                    foreach ($milestones as $m) {
                        $monthName = date('M', strtotime($m['deadline']));
                        $dateNumber = date('j', strtotime($m['deadline']));
                        $year = date('Y', strtotime($m['deadline']));
                        ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="panel panel-default calendar">
                                    <div class="panel-heading calendar-month">
                                        <strong><?= $monthName . "-" . $year ?></strong></div>
                                    <div class="panel-body">
                                        <div class="thumbnail calendar-date">
                                            <?= $dateNumber ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <strong><?= $m['header'] ?></strong>
                                <i class="fa fa-close pull-right"  style="cursor: pointer;color:darkgray" onclick="deleteMilestoneButtonClicked(<?=$m['milestone_id']?>)"></i>
                                <br>
                                <?= $m['body'] ?>
                                <?php
                                if ($m['if_completed'] == 0) {
                                    ?>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="done" onchange="completeMilestoneButtonClicked(<?=$m['milestone_id']?>)" > Complete
                                        </label>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <br><span class="badge success" style="background-color:limegreen">Completed</span>
                                    <?php
                                }
                                ?>

                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    </div>
    <?php
    }
    ?>
    <!-- /#page-content-wrapper -->

</div>

<!--new update modal-->
<div class="modal fade" id="newUpdateModal" data-parsley-validate tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" >New Update</h4>
            </div>

            <form role="form" action="<?=base_url().'Updates/add_new_update/'.$project['project_id'].'/'.$project['current_project_phase_id']?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input name="update_header" type="text" class="form-control"  id="update_title" data-parsley-required>
                    </div>
                    <div class="form-group">
                        <label for="description">Content:</label>
                        <textarea name="update_body" class="form-control" rows="4" id="description" data-parsley-required></textarea>
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

            <form id="newMilestone" data-parsley-validate role="form" action="<?=base_url().'Milestones/add_new_milestone/'.$project['project_id'].'/'.$project['current_project_phase_id']?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input name="header" type="text" class="form-control"  id="milestone_title" data-parsley-required>
                    </div>
                    <div class="form-group">
                        <label for="title">Deadline:</label>
                        <input type="text" name="deadlinePicker" id="deadlinePicker" class="form-control clsDatePicker" data-parsley-required>
                     </div>
                    <div class="form-group">
                        <label for="milestone_description">Description:</label>
                        <textarea name="body" class="form-control" rows="4" id="milestone_description" data-parsley-required></textarea>
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
            <?php
            if($current_phase['phase_id']==5){
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">End Project</h4>
            </div>

            <form role="form" action="<?=base_url().'Project_phase/end_project/'.$project['project_id'].'/'.$project['current_project_phase_id']?>" method="post">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">This project will be ended. You can still find it in "Past Projects".</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="submit" name="submit" id="submit" class="btn btn-primary" value="End Project">
                    </div>
                <?php
                }else {
                    ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Next Phase</h4>
                </div>

                <form role="form" data-parsley-validate action="<?=base_url().'Project_phase/update_phase/'.$project["project_id"].'/'.$project['current_project_phase_id']?>" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" disabled value="<?= $next_phase_name ?>" id="title">
                        </div>
                        <div class="form-group">
                            <label for="estimated_end_time">Estimated End Date:</label>
                            <input type="text" name="estimated_end_time" id="estimated_end_time"
                                   class="form-control clsDatePicker" data-parsley-required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Update">
                    </div>
                    <?php
                        }
                    ?>
            </form>
        </div>
    </div>
</div>
<!--update phase alert modal-->
<div class="modal fade" id="update_phase_alert_modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Unable to update phase!</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="title">You haven't completed all milestones of current phase.</label>
                        <br>
                    <p> Plase make sure that you have completed all the milestones of current phase before updating.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--Milestone Completion Modal-->


    <div class="modal fade" id="milestoneCompletionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Complete Milestone</strong>
                </div>
                    <div class="modal-body">
                       Do you wish to complete this milestone?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cancelComplete()">Cancel</button>
                        <input type="submit" name="submit" id="submit" class="btn btn-success" onclick="confirmMilestoneComplete()" value="Complete">
                    </div>
            </div>
        </div>
    </div>
<!--Milestone Delete Modal-->
    <div class="modal fade" id="milestoneDeleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Delete Milestone</strong>
                </div>
                    <div class="modal-body">
                        This action cannot be undone, do you wish to proceed?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="btnYes" onclick="confirmMilestoneDelete()"> Delete </button>
                    </div>
            </div>
        </div>
    </div>
<!--Update Delete Modal-->
<div class="modal fade" id="updateDeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong>Delete Update</strong>
            </div>
            <div class="modal-body">
                This action cannot be undone, do you wish to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="btnYes" onclick="confirmUpdateDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>



</body>
</html>