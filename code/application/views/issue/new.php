

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
$ci =&get_instance();
$ci->load->model("Project_model");
$project = $ci->Project_model->retrieve_by_repo_slug($repo_slug);
?>
<!DOCTYPE html>
<html>
<?php
//$repo_slug = $repo_slug;
//$filter="status[]=new&status[]=open";
?>
<head lang="en">
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?= base_url() . 'css/sidebar-left.css' ?>">
    <link rel="stylesheet" href="<?= base_url() . 'css/issues.css' ?>">

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
        <a class="link-blue" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i>Dashboard</a>
        <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i>Update & Milestone</a>
        <a class="link-blue selected" href="<?= base_url() . 'Issues/list_all/'.$repo_slug ?>"><i class="fa fa-wrench"></i>Issues</a>
        <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
    </div>

</aside>
<script>
    $(document).ready(function(){
        $(".cmpl").text("*").css("color","red");
    });
</script>
<div class="col-sm-offset-1 content" style="margin-top: 100px">
    <form class="form-horizontal" action="<?=base_url()."Issues/process_create/".$repo_slug?>">
        <h2 style="max-width: 40%">
            New Issue
        </h2>
        <div style="display: table">
            <div class="form-part">
                <div class="form-label">Title <span class="cmpl"></span></div>
                <div class="form-input">
                    <input name="title" class="form-control" value="">
                </div>
            </div>
            <div class="form-part">
                <div class="form-label">Description</div>
                <div class="form-input">
                    <textarea name="content" class="form-control" ></textarea>
                </div>
            </div>
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
                <div class="form-label">Status<span class="cmpl"></span></div>
                <div class="form-input">
                    <?php $status=[
                        "new","to develop","to test","to deploy"
                    ]?>
                    <select name="status" class="form-control">
                        <?php foreach($status as $k):?>
                            <option value="<?=$k?>"><?=$k?></option>
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
                            <option value="<?=$k?>"><?=$k?></option>
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
                            <option value="<?=$k?>"><?=$k?></option>
                        <?php endforeach?>
                    </select>
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