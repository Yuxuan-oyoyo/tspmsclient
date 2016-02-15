
<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <script>
        $(document).ready(function(){
            $('#targeted_start_datetime').datepicker({
                dateFormat: 'yy-mm-dd 00:00:00',
                minDate: '+0d',
                changeMonth: true,
                changeYear: true,
                altFormat: "yy-mm-dd"
            });
            $('#targeted_end_datetime').datepicker({
                dateFormat: 'yy-mm-dd 00:00:00',
                minDate: '+0d',
                changeMonth: true,
                changeYear: true,
                altFormat: "yy-mm-dd"
            });
        });
        function startTaskButtonClicked(task_id) {
            $('#taskStartModal').data('task_id', task_id).modal('show');
        }
        function completeTaskButtonClicked(task_id) {
            $('#taskCompletionModal').data('task_id', task_id).modal('show');
        }
        function deleteTaskButtonClicked(task_id) {
            $('#taskDeleteModal').data('task_id', task_id).modal('show');
        }
        function confirmTaskStart() {
            var tid = $('#taskStartModal').data('task_id');
            var start_t_url = "<?= base_url() . 'Tasks/start_task_confirmation/' . $project['project_id'] . '/' ?>" + tid;
            window.location.href = start_t_url;
        }
        function confirmTaskComplete() {
            var tid = $('#taskCompletionModal').data('task_id');
            var complete_t_url = "<?= base_url() . 'Tasks/complete_task_confirmation/' . $project['project_id'] . '/' ?>" + tid;
            window.location.href = complete_t_url;
        }
        function confirmTaskDelete() {
            var tid = $('#taskDeleteModal').data('task_id');
            var delete_t_url = "<?= base_url() . 'Tasks/delete_task_confirmation/' . $project['project_id'] . '/' ?>" + tid;
            window.location.href = delete_t_url;
        }
    </script>
    <style>
        .stat-td{
            vertical-align: middle;
            text-align: center;
            border: solid 2px #1abc9c;
            width:120px;height:120px;
            padding: 4px;
        }
        .stat-table{
            -webkit-box-shadow: 0 1px 12px rgba(0, 0, 0, 0.175);
            box-shadow: 0 1px 12px rgba(0, 0, 0, 0.175);
        }
        .collapsed-field{
            display: none;
        }
    </style>
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
$this->load->view('common/pm_nav', $class);
function _ago($tm,$rcs = 0) {
    $cur_tm = time(); $dif = $cur_tm-$tm;
    $pds = array('second','minute','hour','day','week','month','year','decade');
    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

    $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
    if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
    return $x;
}
function sortTasksByDaysLeft($a, $b) {
    return $a['days_left'] - $b['days_left'];
}

?>
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue selected" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i><span class="nav-text">Project Overview</span></a>
        <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i><span class="nav-text">Update & Milestone</span></a>
        <?php if($project['bitbucket_repo_name']==null):?>
            <a class="link-grey"><i class="fa fa-wrench"></i><span class="nav-text">Issues</span></a>
        <?php else :?>
            <a class="link-blue " href="<?= base_url() . 'Issues/list_all/' . $project["bitbucket_repo_name"] ?>"><i class="fa fa-wrench"></i><span class="nav-text">Issues</span></a>
        <?php endif;?>
        <a class="link-blue" href="<?=base_url().'Usecases/list_all/'.$project["project_id"]?>"><i class="fa fa-list"></i><span class="nav-text">Use Case List</span></a>
        <a class="link-blue" href="<?=base_url().'Projects/view_report/'.$project["project_id"]?>"><i class="fa fa-bar-chart"></i>Analytics</a>
        <a class="link-blue " href="<?=base_url().'upload/upload/'.$project['project_id']?>"><i class="fa fa-folder"></i><span class="nav-text">File Repository</span></a>
    </div>
</aside>


<div class="col-xs-11 content">
    <!-- Page Content -->
    <div class="col-xs-12">
        <h1 class="page-header">
            <?=$project['project_title']?>&nbsp;
            <?php
            if($project['staging_link']):?>
                <a href="<?=$project['staging_link']?>" class="btn btn-info" target="_blank"><i class="fa fa-external-link"></i>&nbsp;Staging</a>
            <?php endif?>
            <?php
            if($project['production_link']):?>
                <a href="<?=$project['production_link']?>" class="btn btn-info" target="_blank"><i class="fa fa-external-link"></i>&nbsp;Production</a>
            <?php endif?>
            <?php
            if($project['customer_preview_link']):?>
                <a href="<?=$project['customer_preview_link']?>" class="btn btn-info" target="_blank"><i class="fa fa-external-link" ></i>&nbsp;Customer View</a>
            <?php endif?>
        </h1>
    </div>

    <!-- /.row -->
    <div class="row">
        <div class="col-xs-offset-1 no-gutter">
            <?php
            foreach($phases as $phase){
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
                        }
                        echo'<div data-id="'.$phase['project_phase_id'].'" id="'.$phase['phase_name'].'" class="test col-sm-2" align="center" data-toggle="tooltip"
                data-placement="bottom" title="'.$phase['start_time'].' to '.$phase_end_time.'">'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
                    }
                }else{
                    echo' <div  class="test col-xs-2" align="center" >'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
                }
            }
            ?>

        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <?php if($this->session->userdata('message')):?>
                <div class="form-group">
                    <div class="alert alert-info " role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <?=$this->session->userdata('message')?>
                    </div>
                </div>
                <?php $this->session->unset_userdata('message') ?>
            <?php endif;?>

<!--Task CRUD-->
            <div class="col-xs-offset-1 col-xs-4" style="width:40%;margin-left:6%;">
                <div class="panel info-panel" >
                    <div class="panel-heading">
                        Task List
                        <?php
                        if($current_phase_name!=='Ended'){
                        ?>
                            <button class="btn btn-default btn-sm pull-right" data-toggle="modal" data-target="#newTaskModal"><i class="fa fa-plus"></i></button>
                        <?php
                        }
                        ?>

                    </div>
                    <div class="panel-body" style="height: 360px;overflow-y: hidden;" >
                        <div style="overflow: auto;width: 100%;height: 100%;">
                    <table class="table table-condensed">
                        <?php
                            usort($tasks, 'sortTasksByDaysLeft');
                            $color =[""];
                            foreach ($tasks as $t){
                                if(substr($t['days_left'],0,1)==="+"){
                                    $days_left = (int)substr($t['days_left'],1)+1;
                                }else{
                                    $days_left = "Overdue <br>".substr($t['days_left'],1);
                                }
                        ?>
                            <tr id="1">
                            <?php
                                if(substr($t['days_left'],0,1)==="-"){
                                    $color = "indianred";
                                }elseif($days_left<=7){
                                    $color = "darkorange";
                                }else{
                                    $color = "green";
                                }
                            ?>
                                <!--td></td-->
                                <td><b><?=$t['content']?></b> <span class="badge" style="font-weight:400;background-color: <?=$color?>"><?=$days_left?> days</span></td>
                                <?php if(!isset($t['start_datetime'])):?>
                                    <td><button class="btn btn-sm" onclick="startTaskButtonClicked(<?=$t['task_id']?>)" title="Start task now"><i class="fa fa-play"></i></button>
                                    </td>
                                <?php endif;?>
                                <td><a href="<?=base_url().'Tasks/edit_task/'.$project['project_id'].'/'.$t["task_id"]?>"
                                       class="btn btn-sm  btn-primary" type="button" title="Edit this task" ><i class="fa fa-pencil-square-o"></i></a></td>
                                <?php if(isset($t['start_datetime'])):?>
                                    <td><button class="btn btn-sm btn-success" onclick="completeTaskButtonClicked(<?=$t['task_id']?>)" title="Task completed">
                                            <i class="fa fa-check"></i></button>
                                    </td>
                                <?php  endif;?>
                                <td><button class="btn btn-sm  btn-danger" title="Delete task" onclick="deleteTaskButtonClicked(<?=$t['task_id']?>)">
                                        <i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                    </table>
                        </div>
                        </div>
                </div>
            </div>

<!--End of Task Box-->

            <div class="col-xs-4" style="width:40%">
                <div class="panel info-panel">
                    <div class="panel-heading">Project Detail</div>
                    <div class="panel-body" id="right-panel-body" style="height: 360px;font-size:15px" >
                        <table class="table table-condensed">
                            <tr>
                                <td style="width: 45%"><strong>Customer </strong></td>
                                <td> <a href="<?=base_url().'Customers/update_customer_fproject/'.$customer["c_id"].'/'.$project['project_id']?>"><?=$customer['last_name'].' '.$customer['first_name']?></a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Project Code </strong></td>
                                <td><?=$project['project_code']?></td>
                            </tr>
                            <script>
                                var urgency = $.ajax({
                                    url: "<?=base_url().'issues/get_issue_urgency_score/'.$project["project_id"]?>",
                                    dataType: "float",async: false
                                }).responseText;
                            </script>
                            <tr>
                                <td><strong>Urgency Score</strong></td>
                                <td><script>document.write(urgency);</script></td>
                            </tr>
                            <tr>
                                <td><strong>BB. Repo Name </strong></td>
                                <td><?=$project['bitbucket_repo_name']?></td>
                            </tr>
                            <tr>
                                <td><strong>Project Value </strong></td>
                                <td>$<?=$project['project_value']?></td>
                            </tr>
                            <tr>
                                <td><strong>Priority </strong></td>
                                <td><?=$project['priority']?></td>
                            </tr>
                            <tr>
                                <td><strong>No. of Use Cases </strong></td>
                                <td><?=$no_of_usecases?></td>
                            </tr>
                            <tr class="collapsed-field">
                                <td><strong>Status </strong></td>
                                <td>
                                    <?=$project['is_ongoing']==1?"Ongoing":"Closed"?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tags </strong></td>
                                <td><?=$project['tags']?></td>
                            </tr>
                            <tr class="collapsed-field">
                                <td><strong>Description </strong></td>
                                <td><?=$project['project_description']?></td>
                            </tr>
                            <tr class="collapsed-field">
                                <td><strong>Remarks </strong></td>
                                <td><?=$project['remarks']?></td>
                            </tr>
                        </table>
                        <a href="#" id="collapse-toggle" is-hidden="1">Expand..</a>
                        <a href="<?=base_url().'Projects/edit/'.$project["project_id"]?>" class="btn pull-right btn-primary"><i class="fa fa-pencil-square-o"></i> &nbsp;Edit</a>

                    </div>
                </div>
            </div>
        </div>
        <script>
            $("#collapse-toggle").on("click",function(){
                var button = $(this);var isHidden = button.attr("is-hidden");
                if(isHidden=="1") {$(".collapsed-field").show("slow");button.text("Collapse..");button.attr("is-hidden","0");$("#right-panel-body").css("height","auto");}
                else{$(".collapsed-field").hide("slow",function(){$("#right-panel-body").css("height","360px");});button.text("Expand..");button.attr("is-hidden","1");}
                return false;
            });
        </script>

    </div>
<!--new task modal-->
    <div class="modal fade" id="newTaskModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" >New Task</h4>
                </div>

                <form id="newTask" data-parsley-validate role="form" action="<?=base_url().'Tasks/add_new_task/'.$project['project_id']?>" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="content">Task Content:</label>
                            <textarea name="content" rows="2" id="content" class="form-control" data-parsley-required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="importance">Task Importance:</label>
                            <input type="number" name="importance" id="importance" class="form-control" data-parsley-required min="1" max="5">
                        </div>
                        <div class="form-group">
                            <label for="phase_id">Corresponding Project Phase:</label>
                            <select name="phase_id" class="form-control">
                                <?php
                                    foreach($phases as $phase) {
                                        if ($phase['phase_name']===$current_phase_name) {
                                            ?>
                                            <option value="<?= $phase['phase_id'] ?>" selected><?= $phase['phase_name'] ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="<?= $phase['phase_id'] ?>"><?= $phase['phase_name'] ?></option>
                                            <?php
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="targeted_start_datetime">Targeted Start Datetime:</label>
                            <input type="text" name="targeted_start_datetime" id="targeted_start_datetime" class="form-control clsDatePicker" data-parsley-required>

                        </div>
                        <div class="form-group">
                            <label for="targeted_end_datetime">Targeted End Datetime:</label>
                            <input type="text" name="targeted_end_datetime" id="targeted_end_datetime" class="form-control clsDatePicker" data-parsley-required>
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
<!--End of New Task Modal-->
<!--Task Start Modal-->
<div class="modal fade" id="taskStartModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong>Start Task</strong>
            </div>
            <div class="modal-body">
                Do you wish to start this task?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cancelStart()">Cancel</button>
                <input type="submit" name="submit" id="submit" class="btn btn-success" onclick="confirmTaskStart()" value="Start">
            </div>
        </div>
    </div>
</div>
<!--End of Task Start Modal-->
<!--Task Completion Modal-->
    <div class="modal fade" id="taskCompletionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Complete Task</strong>
                </div>
                <div class="modal-body">
                    Do you wish to complete this task?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cancelComplete()">Cancel</button>
                    <input type="submit" name="submit" id="submit" class="btn btn-success" onclick="confirmTaskComplete()" value="Complete">
                </div>
            </div>
        </div>
    </div>
<!--End of Task Completion Modal-->
<!--Task Delete Modal-->
<div class="modal fade" id="taskDeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong>Delete Task</strong>
            </div>
            <div class="modal-body">
                Do you wish to delete this task?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cancelDelete()">Cancel</button>
                <input type="submit" name="submit" id="submit" class="btn btn-success" onclick="confirmTaskDelete()" value="Delete">
            </div>
        </div>
    </div>
</div>
<!--End of Task Delete Modal-->

</div>
</body>
</html>
<script type="text/javascript">
    var _mfq = _mfq || [];
    (function() {
        var mf = document.createElement("script");
        mf.type = "text/javascript"; mf.async = true;
        mf.src = "//cdn.mouseflow.com/projects/5e3cc2e8-d8e9-4dd1-a35a-8419f1b9aa45.js";
        document.getElementsByTagName("head")[0].appendChild(mf);
    })();
</script>
    <!-- /#page-content-wrapper -->
