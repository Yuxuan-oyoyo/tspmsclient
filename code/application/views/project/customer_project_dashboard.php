<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link href="<?=base_url().'css/timeline.css'?>" rel="stylesheet">
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
                    var append='';
                    if (element.if_completed!=0){
                        append='<br><span class="badge success" style="background-color: limegreen">Completed</span>';
                    }else{
                        append='';
                    }
                    var htmlText = ' <div class="row"> <div class="col-lg-4"> <div class="panel panel-default calendar"> ' +
                        '<div class="panel-heading calendar-month" style="text-align:center;background:#EA9089;color:white"><strong>'+month+'-'+year+'</strong></div>'+
                        '<div class="panel-body"> <div class="thumbnail calendar-date" >'+day+' </div> </div> </div> </div> <div class="col-lg-7">'+
                        '<strong>'+element.header+'</strong>'+
                        '<br>'+element.body+append+
                        ' </div> </div>';

                    $('#milestone').append( htmlText );
                });
            });
        }

    </script>
</head>
<body>
<?php
$class = [
    'dashboard_class'=>'',
    'projects_class'=>'active',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/customer_nav', $class);
?>
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue selected" href="#"><i class="fa fa-flag"></i><span class="nav-text">Update & Milestone</span></a>
        <a class="link-blue" href="<?=base_url().'Usecases/customer_usecases/'.$project["project_id"]?>"><i class="fa fa-list"></i><span class="nav-text">Use Case List</span></a>
        <a class="link-blue " href="<?=base_url().'Upload/customer_repo/'.$project["project_id"]?>"><i class="fa fa-folder"></i><span class="nav-text">File Repository</span></a>
    </div>

</aside>
<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
           <?=$project['project_title']?>&nbsp;
            <?php if($project['customer_preview_link']):?>
            <a href="<?=$project['customer_preview_link']?>" class="btn btn-info" target="_blank"><i class="fa fa-external-link"></i>&nbsp;Prototype</a>
            <?php endif?>
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
    if(!isset($current_phase)) {
        ?>
        <div class="alert alert-warning" role="alert"><strong>This project hasn't been started.</strong></div>
    <?php
    }else{
    ?>
    <div class="row">

        <div class="col-lg-12">
            <div class="col-lg-7">
                <h3>Recent Updates - <small class="phase"><?=$current_phase['phase_name']?></small></h3><hr>
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

                <h3>Milestones - <small class="phase"><?=$current_phase['phase_name']?></small></h3><hr>
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
                        <?php
                        if ($milestone['if_completed'] ==1 ) {
                            ?>
                            <br><span class="badge success" style="background-color:limegreen">Completed</span>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                    <?php endforeach?>
            </div>
                </div>
        </div>


    </div>
    <?php }?>
</div>

</body>
</html>