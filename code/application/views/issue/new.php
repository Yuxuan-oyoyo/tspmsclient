

<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/27/2015
 * Time: 5:37 PM
 */
//echo var_dump($issue_details);
$repo_slug = $repo_slug;
$ci =&get_instance();
$ci->load->model("Internal_user_model");
$users = $ci->Internal_user_model->retrieveAll(false);
$ci->load->model("Project_model");
$project = $ci->Project_model->retrieve_by_repo_slug($repo_slug);
$user = $user;
?>
<!DOCTYPE html>
<html>

<head lang="en">
    <?php $this->load->view('common/common_header');?>
    <!--link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"-->
    <script src="<?= base_url() . 'js/markdown/markdown.js' ?>"></script>
    <link rel="stylesheet" href="<?= base_url() . 'css/sidebar-left.css' ?>">
    <link rel="stylesheet" href="<?= base_url() . 'css/issues.css' ?>">

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
if($this->session->userdata('internal_type')=='Developer') {
    $this->load->view('common/dev_nav', $class);
}else {
    $this->load->view('common/pm_nav', $class);
    ?>
    <aside class="sidebar-left">
        <div class="sidebar-links">
            <a class="link-blue" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i><span class="nav-text">Project Overview</span></a>
            <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i><span class="nav-text">Update & Milestone</span></a>
            <a class="link-blue selected" href="<?=base_url()?>Issues/list_all/<?=$repo_slug?>"><i class="fa fa-wrench"></i><span class="nav-text">Issues</span></a>
            <a class="link-blue" href="<?=base_url().'Usecases/list_all/'.$project["project_id"]?>"><i class="fa fa-list"></i><span class="nav-text">Use Case List</span></a>
            <a class="link-blue " href="<?=base_url().'upload/upload/'.$project['project_id']?>"><i class="fa fa-folder"></i><span class="nav-text">File Repository</span></a>
        </div>

    </aside>
    <?php
}
?>

<script>
    $(document).ready(function(){
        $(".cmpl").text("*").css("color","red");
    });
</script>
<div class="col-sm-offset-1 content" style="margin-top: 75px;margin-left:15%">
    <form class="form-horizontal" data-parsley-validate action="<?=base_url()."Issues/process_create/".$repo_slug?>">
        <h2 style="max-width: 40%">
            New Issue
        </h2>
        <div style="display: table;width:45%">
            <div class="form-part">
                <div class="form-label">Title <span class="cmpl"></span></div>
                <div class="form-input">
                    <input name="title" required class="form-control" value="">
                </div>
            </div>
            <div class="form-part">
                <div class="form-label">Description</div>
                <div class="form-input">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#desc-input-pane" data-toggle="tab" aria-expanded="true">Input</a></li>
                        <li class=""><a href="#desc-preview-pane" data-toggle="tab" aria-expanded="false">Preview</a></li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active in" id="desc-input-pane">
                            <textarea name="content" id="input-description" class="form-control" ></textarea>
                        </div>
                        <div class="tab-pane fade" id="desc-preview-pane">
                            <div class="well" style="min-height:34px;padding:6px 12px" id="description-preview"></div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $("#input-description").on("input",function(){
                    this.editor.update();
                });
                function Editor(input, preview) {
                    this.update = function () {preview.html( markdown.toHTML(input.value));};
                    input.editor = this;
                    this.update();
                }
                new Editor(document.getElementById("input-description"), $("#description-preview"));
            </script>
            <div class="form-part">
                <div class="form-label">Assignee<span class="cmpl"></span></div>
                <div class="form-input">
                    <select name="responsible" class="form-control">
                        <?php foreach($users as $u):?>
                            <option value="<?=$u["bb_username"]?>"><?=$u["bb_username"]?></option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>

            <div class="form-part">
                <div class="form-label">Workflow</div>
                <div class="form-input">
                    <select name="workflow" class="form-control">
                        <option value="default workflow">NIL</option>
                        <?php $server_workflow = ["to develop","to test","ready for deployment","to deploy"];?>
                        <?php foreach($server_workflow as $s):?>
                            <?php if($s==$i["workflow"]):?>
                                <option selected value="<?=$s?>"><?=ucwords($s)?></option>
                            <?php else:?>
                                <option value="<?=$s?>"><?=ucwords($s)?></option>
                            <?php endif?>
                        <?php endforeach?>
                    </select>
                </div>
            </div>
            <div class="form-part">
                <div class="form-label">Kind</div>
                <div class="form-input">
                    <?php $kinds = ["bug","enhancement","proposal","task"]?>
                    <select name="kind" class="form-control">
                        <?php foreach($kinds as $k):?>
                            <option value="<?=$k?>"><?=ucwords($k)?></option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>
            <div class="form-part">
                <div class="form-label">Priority</div>
                <div class="form-input">
                    <?php $priorities = ["trivial","minor","major","critical","blocker"]?>
                    <select name="priority" class="form-control">
                        <?php foreach($priorities as $k):?>
                            <option value="<?=$k?>"><?=ucwords($k)?></option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>
            <div class="form-part">
                <div class="form-label">Use case</div>
                <div class="form-input">
                    <?php
                    $ci->load->model('Use_case_model');
                    $usecases = $ci->Use_case_model->retrieve_by_project_repo_slug($repo_slug);
                    ?>
                    <?php if (empty($usecases)):?>
                        <div><i>No use case defined. Please add new use cases in project management page</i></div>
                    <?php else:?>
                        <select name="usecase" class="form-control">
                            <option value="nil">NIL</option>
                            <?php foreach($usecases as $uc):?>
                                <option value="<?=$uc["usecase_id"]?>"><?=$uc["title"]?></option>
                            <?php endforeach?>
                        </select>
                    <?php endif?>
                </div>
            </div>
            <div class="form-part">
                <div class="form-label">Milestone</div>
                <div class="form-input">
                    <?php
                    $ci->load->library('BB_milestones');
                    $ci->load->model('Milestone_model');
                    $bb_milestone_names = [];
                    $bb_milestones = $ci->bb_milestones->getAllMilestones($repo_slug);
                    foreach($bb_milestones as $key=>$value){
                        array_push($bb_milestone_names, $value["name"]);
                    }
                    $local_milestones= $ci->Milestone_model->retrieve_by_project_repo_slug($repo_slug);
                    $milestone_options = [];
                    foreach($local_milestones as $key=>$value){
                        $bb_milestone_name = $value["milestone_id"];
                        if(in_array($bb_milestone_name, $bb_milestone_names)){
                            $milestone_options[$bb_milestone_name] = $value["header"];
                        }
                    }
                    ?>
                    <?php if (empty($milestone_options)):?>
                        <div><i>No milestone defined. Please add new use cases in project management page</i></div>
                    <?php else:?>
                    <select name="milestone" class="form-control">
                        <option value="nil">NIL</option>
                        <?php foreach($milestone_options as $key=>$value):?>
                           <option value="<?=$key?>"><?=$value?></option>
                        <?php endforeach?>
                    </select>
                    <?php endif;?>
                </div>
            </div>
            <div class="form-part">
                <div class="form-label">Deadline <span class="cmpl"></span></div>
                <script>
                    $(document).ready(function() {
                        $('.datepicker').datepicker({
                            dateFormat: 'yy-mm-dd',
                            minDate: '+0d',
                            changeYear: true,
                            changeMonth: true
                        });
                    });
                </script>
                <div class="form-input">
                    <input name="deadline" required class="form-control datepicker" data-parsley-pattern="/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/"
                           placeholder="yy-mm-dd" >
                </div>
            </div>
            <div class="form-part">
                <div style="display: table-cell"></div>
                <div class="form-input" style="vertical-align: middle">
                    <button class="btn btn-primary" style="margin-right: 5pt">Create issue</button>
                    <a href="#" id="cancel">Cancel</a>
                </div>
            </div>


        </div>
    </form>
<script>
$("#cancel").on("click",function(e){e.preventDefault();window.history.back();})
</script>
</div>
</body>
</html>