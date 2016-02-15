<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue selected" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i>Dashboard</a>
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
        <a class="link-blue " href="<?=base_url().'upload/upload/'.$project['project_id']?>"><i class="fa fa-folder"></i><span class="nav-text">File Repository</span></a>
    </div>
</aside>