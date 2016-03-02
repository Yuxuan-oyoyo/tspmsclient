<!DOCTYPE html>
<html>

<head lang="en">
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?= base_url() . 'css/sidebar-left.css' ?>">
    <link rel="stylesheet" href="<?= base_url() . 'css/issues.css' ?>">
    <script>
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
if($this->session->userdata('internal_type')=='Developer') {
    $this->load->view('common/dev_nav', $class);
    ?>
    <aside class="sidebar-left">
        <div class="sidebar-links">
            <a class="link-blue selected" href="<?=base_url()?>Issues/list_all/<?=$repo_slug?>"><i class="fa fa-wrench"></i><span class="nav-text">Issues</span></a>
            <a class="link-blue" href="<?=base_url().'Usecases/list_all/'.$project["project_id"]?>"><i class="fa fa-list"></i><span class="nav-text">Use Case List</span></a>
        </div>

    </aside>
    <?php
}else {
    $this->load->view('common/pm_nav', $class);
    ?>
    <aside class="sidebar-left">
        <div class="sidebar-links">
            <a class="link-blue" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i><span class="nav-text">Project Overview</span></a>
            <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i><span class="nav-text">Update & Milestone</span></a>
            <a class="link-blue selected" href="<?=base_url()?>Issues/list_all/<?=$repo_slug?>"><i class="fa fa-wrench"></i><span class="nav-text">Issues</span></a>
            <a class="link-blue" href="<?=base_url().'Usecases/list_all/'.$project["project_id"]?>"><i class="fa fa-list"></i><span class="nav-text">Use Case List</span></a>
            <a class="link-blue" href="<?=base_url().'Projects/view_report/'.$project["project_id"]?>"><i class="fa fa-bar-chart"></i><span class="nav-text">Analytics</span></a>
            <a class="link-blue " href="<?=base_url().'upload/upload/'.$project['project_id']?>"><i class="fa fa-folder"></i><span class="nav-text">File Repository</span></a>
        </div>

    </aside>
    <?php
}
?>


<div class="col-xs-offset-1 content">
    <!-- Page Content -->
    <div class="col-xs-12">
        <h1 class="page-header inner-action" href="#jkljlsfd">
            <span><?=$project["project_title"]?></span>
            <small> - Issues</small>
        </h1>
    </div>


    <hr>
    <div class="row" style="margin-right: auto">
        <div class="col-xs-11" style="padding-left:30px">
            <div id="ajax-container" style="width: 100%;height: 100%">
            <div style="width: 100%;height:34px">
                <div style="float: left">
                    <div class="btn-group">
                        <a class="btn btn-default" href="./<?=$repo_slug?>">All</a>
                        <a class="btn btn-default" href="./<?=$repo_slug?>?status=!resolved">Unresolved</a>
                        <div class="btn-group">
                            <button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown">
                                Workflow <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                            </ul>
                        </div>
                        <a class="btn btn-default" style="margin-right:15px" href="#">My Issues</a>
                    </div>
                </div>
                <div style="float: right">
                    <input id="search-box" class="form-control" style="width: auto;display: inline;margin-right:50px" placeholder="Find issue">
                    <a href="../create/<?=$repo_slug?>" class="btn btn-primary" style="margin-top: -3px"><i class="fa fa-plus"></i>&nbsp;Create Issue</a>
                </div>
            </div>
            <div style="width: 100%;font-size: 1.2em;margin: 7px 5px">

                <b>Showing issues ...</b>

            </div>
            <table class="table table-striped" data-sort-by="updated_on" data-modules="components/follow-list">
                <?php
                $headers = [
                    "local_id"=>["display"=>"Title"     ,"sort"=>"local_id"],//"sm" for sort method
                    "kind"=>["display"=>"Type"  ,"sort"=>"kind" ],
                    "priority"=>["display"=>"Priority" ,"sort"=>"priority"],
                    "status"=>["display"=>"Status"   ,"sort"=>"status" ],
                    "milestone"=>["display"=>"Milestone" ,"sort"=>"milestone"],
                    "responsible"=>["display"=>"Assignee" ,"sort"=>"responsible"],
                    "utc_created_on"=>["display"=>"Created" ,"sort"=>"utc_created_on"],
                    "utc_last_updated"=>["display"=>"Updated" ,"sort"=>"utc_last_updated"],
                ];?>
                <thead>
                <tr>
                    <th class="text sorter-false" style="padding: 8px 4px"></th>
                    <?php foreach($headers as $h):?>
                        <th class="text sorter-false tablesorter-header" style="padding: 8px 4px">
                            <a href="#"><?=$h["display"]?></a>
                        </th>
                    <?php endforeach?>
                </tr>
                </thead>
                <tbody id="tbody">
                <?php for($index = 0;$index<20;$index++):?>
                    <tr><?php for ($inner = 0;$inner<=8;$inner++):?><td style="height: 40px"> </td><?php endfor?></tr>

                <?php endfor?>
                </tbody>
            </table>
            </div>
            <div id="overlay" style="width: 100%; height: 100%;position: absolute;top: 0;left: 0;text-align:center;z-index: 1;background-color: rgba(255,255,255,0.6);">
                <div style="margin:100px auto;text-align:center;width: 30px;"><img src="http://media.giphy.com/media/JBeu9q9LC1Kve/giphy.gif" style="max-width: 100px"></div>
            </div>
        </div>
        <script>
            function reload(url){
                $("#overlay").fadeIn("slow");
                $.ajax({
                    url: url,
                    success: function (response) {
                        if (response) {
                            $("#ajax-container").empty().html(response);
                        }
                    },
                    complete: function (){
                        $("#overlay").fadeOut("slow");
                    }
                });
            }

            //$(".inner-action").on("click", function(){alert("in");});
            $(document).ready(function(){
                $(document).on("click","a.inner-action",function(e){
                    e.preventDefault(e);
                    var href = $(this).attr("href");
                    console.log(href);
                    reload(href); return false;
                });
                reload("<?=base_url().'Issues/list_all_inner/'.$repo_slug;?>")
            });


        </script>
    </div>
</div>
</body>
</html>