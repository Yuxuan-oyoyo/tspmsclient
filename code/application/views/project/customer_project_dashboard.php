<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <!-- Custom CSS -->
    <link href="<?=base_url().'css/sb-admin.css'?>" rel="stylesheet">
    <link href="<?=base_url().'css/timeline.css'?>" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <!-- jQuery -->
    <script src="<?= base_url() . 'js/jquery.min.js' ?>"></script>
    <script src="<?= base_url() . 'js/bootstrap.min.js' ?>"></script>


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
                refillUpdates(project_phase_id);
                refillMilestones(project_phase_id);
            });
            $("#Requirement" ).click(function() {
                // this change title
                $(".phase").each(function(){
                    $(this).text("Requirement");
                });
                var project_phase_id=($(this).data().id);
                // this changes updates
                refillUpdates(project_phase_id);
                refillMilestones(project_phase_id);
            });
            $("#Build" ).click(function() {
                // this change title
                $(".phase").each(function(){
                    $(this).text("Build");
                });
                var project_phase_id=($(this).data().id);
                // this changes updates
                refillUpdates(project_phase_id);
                refillMilestones(project_phase_id);
            });
            $("#Testing" ).click(function() {
                // this change title
                $(".phase").each(function(){
                    $(this).text("Testing");
                });
                var project_phase_id=($(this).data().id);
                // this changes updates
                refillUpdates(project_phase_id);
                refillMilestones(project_phase_id);
            });
            $("#Deploy" ).click(function() {
                // this change title
                $(".phase").each(function(){
                    $(this).text("Deploy");
                });
                var project_phase_id=($(this).data().id);
                // this changes updates
                refillUpdates(project_phase_id);
                refillMilestones(project_phase_id);
            });

        });

        function refillUpdates(project_phase_id){
            $("#timeline").text('');
            $.get("<?=base_url('updates/get_update_by_project_phase/')?>"+'/'+project_phase_id, function(data, status){
                var updates = jQuery.parseJSON(data);
                updates.forEach(function(element){
                    var htmlText = '<li>'+
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

        function refillMilestones(project_phase_id){
            $("#milestone").text('');
            var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];
            $.get("<?=base_url('milestones/get_by_project_phase_id/')?>"+'/'+project_phase_id, function(data, status){
                var updates = jQuery.parseJSON(data);
                updates.forEach(function(element){
                    var ddl=new Date(element.deadline);
                    var day = ddl.getDate();
                    var month=monthNames[ddl.getMonth()];
                    var year=ddl.getFullYear();
                    var htmlText = ' <div class="row"> <div class="col-lg-4"> <div class="panel panel-default calendar"> ' +
                        '<div class="panel-heading calendar-month" style="text-align:center;background:#EA9089;color:white"><strong>'+month+'-'+year+'</strong></div>'+
                    '<div class="panel-body"> <div class="thumbnail calendar-date" >'+day+' </div> </div> </div> </div> <div class="col-lg-7">'+
                    '<strong>'+element.header+'</strong><br>'+element.body+
                   ' </div> </div>';

                    $('#milestone').append( htmlText );
                });
            });
        }

    </script>
</head>
<body>
<?php $this->load->view('common/customer_nav');?>
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue selected" href="customerMainpage.html"><i class="fa fa-flag"></i>Update & Milestone</a>
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
        <?php foreach($phases as $phase){
            $img_tag='img/future.png';
                if(isset($phase['project_phase_id'])){
                    $img_tag = 'img/done.png';
                    if ($phase['project_phase_id'] == $project['current_project_phase_id']){
                        $img_tag = 'img/current.png';
                    }

                echo'<div data-id="'.$phase['project_phase_id'].'" id="'.$phase['phase_name'].'" class="test col-sm-2 " align="center" data-toggle="tooltip"
                data-placement="bottom" title="'.$phase['start_time'].' to '.$phase['end_time'].'">'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
      }else{
                    echo' <div data-id="'.$phase['project_phase_id'].'" id="'.$phase['phase_name'].'" class="test col-sm-2" align="center" >'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
     } }?>

    </div>
    </div>
    <hr>
    <div class="row">

        <div class="col-lg-12">
            <div class="col-lg-7">
                <h3>Recent Updates - <small class="phase">Build</small></h3><hr>
                <ul class="timeline" id="timeline">
                    <?php foreach($updates as $update):?>
                    <li><!---Time Line Element--->
                        <div class="timeline-badge  neutral"><i class="fa fa-navicon"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title"><?=$update['header']?></h4>
                            </div>
                            <div class="timeline-body"><!---Time Line Body&Content--->
                                <p><?=$update['body']?></p>
                                <div class="pull-right timeline-info">
                                    <i class="fa fa-user"></i>&nbsp;<?=$update['posted_by']?> &nbsp;
                                    <i class="fa fa-calendar-check-o"></i>&nbsp;<?=$update['last_updated']?></div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach?>
                </ul>
            </div>
            <div class="col-lg-4">

                <h3>Milestones - <small class="phase">Build</small></h3><hr>
                <div id="milestone">
                    <?php foreach($milestones as $milestone):
                        $month = date('M',strtotime($milestone['deadline']));
                        $date =  date('d',strtotime($milestone['deadline']));
                        $year =  date('Y',strtotime($milestone['deadline']));
                    ?>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="panel panel-default calendar">
                            <div class="panel-heading calendar-month"><strong><?=$month."-".$year?></strong></div>
                            <div class="panel-body">
                                <div class="thumbnail calendar-date" >
                                    <?=$date?>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <strong><?=$milestone['header']?></strong><br>
                        <?=$milestone['body']?>
                    </div>
                </div>
                    <?php endforeach?>
            </div>
                </div>
        </div>


    </div>
</div>

</body>
</html>