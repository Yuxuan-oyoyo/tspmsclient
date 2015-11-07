<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue selected" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i>Dashboard</a>
        <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i>Update & Milestone</a>
        <a class="link-blue " href="<?=base_url().'Issues/list_all/'.$project["bitbucket_repo_name"]?>"><i class="fa fa-wrench"></i>Issues</a>
        <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
    </div>
</aside>