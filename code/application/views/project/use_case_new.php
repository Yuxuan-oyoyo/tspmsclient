<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <script src="<?= base_url() . 'js/plugins/ckeditor/ckeditor.js' ?>"></script>
</head>
<body >
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
?>
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i><span class="nav-text">Project Overview</span></a>
        <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i><span class="nav-text">Update & Milestone</span></a>
        <?php
        if($project['bitbucket_repo_name']==null){
            ?>
            <a class="link-grey"><i class="fa fa-wrench"></i><span class="nav-text">Issues</span></a>
        <?php
        }else {
            ?>
            <a class="link-blue " href="<?= base_url() . 'Issues/list_all/' . $project["bitbucket_repo_name"] ?>"><i class="fa fa-wrench"></i><span class="nav-text">Issues</span></a>
        <?php
        }
        ?>
        <a class="link-blue  selected" href="<?=base_url().'Usecases/list_all/'.$project["project_id"]?>"><i class="fa fa-list"></i><span class="nav-text">Use Case List</span></a>
        <a class="link-blue " href="<?=base_url().'upload/upload/'.$project['project_id']?>"><i class="fa fa-folder"></i><span class="nav-text">File Repository</span></a>
        <a class="link-blue" href="<?=base_url().'Projects/view_report/'.$project["project_id"]?>"><i class="fa fa-bar-chart"></i>Analytics</a>
        <a class="link-blue " href="<?=base_url().'upload/upload/'.$project['project_id']?>"><i class="fa fa-folder"></i>File Repository</a>
    </div>
</aside>
<div class="container content">
        <h1 class="page-header">
            New Use Case&nbsp;
            <a href="<?= base_url() . 'Usecases/list_all/'.$project['project_id'] ?>" class="btn btn-primary"><i class="fa fa-backward"></i>&nbsp;Back</a>
        </h1>
        <form  data-parsley-validate role="form" action="<?=base_url().'Usecases/new_use_case/'.$project['project_id']?>" method="post">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="title">Title*:</label>
                    <input name="title" id="title"  type="text" class="form-control" data-parsley-required>
                </div>
                <div class="form-group">
                    <label for="stakeholders">Stakeholders:</label>
                    <textarea class="form-control" name="stakeholders" id="stakeholders" rows="2" ></textarea>
                </div>
                <div class="form-group">
                    <label for="flow">Flow:</label>
                       <textarea name="flow" id="flow" rows="3" ></textarea>
                    <script>
                        CKEDITOR.replace( 'flow' );
                    </script>
                </div>

            </div>
                <div class="col-md-6">
                <div class="form-group">
                    <label for="importance">Importance:</label>
                    <select class="form-control" id="importance" name="importance" data-parsley-required>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    <label for="type">Type:</label>
                    <select class="form-control" id="type" name="type" data-parsley-required>
                        <option value="Internal">Internal</option>
                        <option value="External">External</option>
                    </select>
                </div>
                </div>

                <div class="col-md-12 pull-right">
                    <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit">
                    <!--<a href="//?base_url().'Customers/edit/'.$c["c_id"]?" class="btn btn-primary">Submit</a>-->
                    <a href="<?= base_url() . 'Usecases/list_all/'.$project['project_id'] ?>" class="btn btn-default">Cancel</a>
                </div>


        </form>

    </div>



</body>
</html>