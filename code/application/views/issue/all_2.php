<!DOCTYPE html>
<html>
<?php
    $repo_slug = $repo_slug;
    function _ago($tm,$rcs = 0) {
        $cur_tm = time(); $dif = $cur_tm-$tm;
        $pds = array('sec','min','hour','day','week','month','year','decade');
        $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
        for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

        $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
        if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
        return $x;
    }
?>
<head lang="en">
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <link href="<?= base_url() . 'css/sb-admin.css' ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() . 'css/sidebar-left.css' ?>">
    <link rel="stylesheet" href="<?= base_url() . 'css/issues.css' ?>">
    <!--script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script-->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->
    <script>
    </script>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html">The Shipyard </a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-nav">
        <li>
            <a href="index.html"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
        </li>
        <li  class="active">
            <a href="projects.html"><i class="fa fa-fw fa-bar-chart-o"></i> Projects</a>
        </li>
        <li>
            <a href="chat.html"><i class="fa fa-fw fa-comment"></i> Message</a>
        </li>
        <li>
            <a href="customers.html"><i class="fa fa-fw fa-users"></i>Customer</a>
        </li>
        <li>
            <a href="customers.html"><i class="fa fa-fw fa-line-chart"></i>Analytics</a>
        </li>
    </ul>
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
            <ul class="dropdown-menu message-dropdown">
                <li class="message-preview">
                    <a href="#">
                        <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                            <div class="media-body">
                                <h5 class="media-heading"><strong>John Smith</strong>
                                </h5>
                                <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                <p>Lorem ipsum dolor sit amet, consectetur...</p>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="message-footer">
                    <a href="#">Read All New Messages</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
            <ul class="dropdown-menu alert-dropdown">
                <li>
                    <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">View All</a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> John Smith <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue" href="projectDashboard.html"><i class="fa fa-tasks"></i>Dashboard</a>
        <a class="link-blue " href="projectUpdate.html"><i class="fa fa-flag"></i>Update & Milestone</a>
        <a class="link-blue selected" href="./<?=$repo_slug?>"><i class="fa fa-wrench"></i>Issues</a>
        <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
    </div>

</aside>

<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            #1. Inventory Management System<small> - Issues</small>
        </h1>
    </div>


    <hr>
    <div class="row">
        <div class=" col-lg-10" style="padding-left:30px">
            <?php
                $filter_arr =$filter_arr;
                $headers = [
                    "local_id"=>["display"=>"Title"     ,"sort"=>"local_id"],//"sm" for sort method
                    "kind"=>["display"=>"Type"  ,"sort"=>"kind" ],
                    "priority"=>["display"=>"Priority" ,"sort"=>"priority"],
                    "status"=>["display"=>"Status"   ,"sort"=>"status" ],
                    "milestone"=>["display"=>"Milestone" ,"sort"=>"milestone"],
                    "responsible"=>["display"=>"Assignee" ,"sort"=>"responsible"],
                    "created_on"=>["display"=>"Created" ,"sort"=>"created_on"],
                    "utc_last_updated"=>["display"=>"Updated" ,"sort"=>"utc_last_updated"],
                ];
            $status_color=[
                "new"=>"white","to develop"=>"LightYellow ","resolved"=>"LightGreen","to test"=>"LightGoldenRodYellow","invalid"=>"Peru","to deploy"=>"Plum ","wontfix"=>"brown","closed"=>"LightGray "
            ];
            $issues = $issues_response["issues"];
            if(isset($filter_arr["sort"])){
                if(substr($filter_arr["sort"],0,1)!="-"){
                    $h = $filter_arr["sort"];
                    if(isset($headers[$h])){$headers[$h]["sort"]="-".$headers[$h]["sort"];}
                    unset($filter_arr["sort"]);
                }
            }
            $filter_str =http_build_query($filter_arr);
            $filter_str = empty($filter_str)? $filter_str: $filter_str."&";
            ?>
            <div style="width: 100%">
                <div style="float: left;margin-bottom: 10px">
                    <div class="btn-group">
                        <a class="btn btn-default" href="./<?=$repo_slug?>">All</a>
                        <a class="btn btn-default" href="./<?=$repo_slug?>?status=!resolved">Unresolved</a>
                        <div class="btn-group">
                            <button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown">
                                Workflow <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="./<?=$repo_slug?>?status=to develop">To develop</a></li>
                                <li><a href="./<?=$repo_slug?>?status=to test">To test</a></li>
                                <li><a href="./<?=$repo_slug?>?status=to deploy">To deploy</a></li>
                            </ul>
                        </div>
                        <a class="btn btn-default" style="margin-right:15px" href="./<?=$repo_slug?>?responsible=luning1994">My Issues</a>
                    </div>
                </div>
                <div style="float: right;margin-bottom: 10px">
                    <a href="../create/<?=$repo_slug?>" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Create Issue</a>
                </div>
            </div>
            <table class="table table-striped" data-sort-by="updated_on" data-modules="components/follow-list">
                <thead>
                <tr>
                    <?php foreach($headers as $h):?>
                        <th class="text sorter-false tablesorter-header">
                            <a href="<?=base_url()."Issues/list_all/".$repo_slug."?".$filter_str."sort=".$h["sort"]?>"><?=$h["display"]?></a>
                        </th>
                    <?php endforeach?>
                </tr>
                </thead>
                <tbody id="tbody">

                <?php foreach($issues as $d):?>
                    <tr class="" data-state="open">
                        <td class="" style="width: 35%">
                            <a class="execute" href="<?=base_url()."issues/detail/".$repo_slug."/".$d["local_id"]?>" title="View Details">#<?=$d["local_id"]?>: <?=$d["title"]?></a>
                        </td>
                        <td class="icon-col">
                            <a href="<?=base_url()."issues/list_all/".$repo_slug."?".$filter_str."kind=".$d["metadata"]["kind"]?>"
                               class="icon-bug" title="Filter by type:<?=$d["metadata"]["kind"]?>">
                                <?=$d["metadata"]["kind"]?>
                            </a>
                        </td>
                        <td class="icon-col">
                            <a href="<?=base_url()."issues/list_all/".$repo_slug."?".$filter_str."priority=".$d["priority"]?>"
                               class=" icon-major" title="Filter by priority:"<?=$d["priority"]?>>
                                <?=$d["priority"]?>
                            </a>
                        </td>
                        <td class="state">
                            <a class="aui-lozenge" href="<?=base_url()."issues/list_all/".$repo_slug."?".$filter_str."status=".$d["status"]?>"
                               title="Filter by status: <?=$d["status"]?>" style="background-color: <?=$status_color[$d["status"]]?>">
                                <?=$d["status"]=="to deploy"?"to dep":($d["status"]=="to develop"?"to dev":$d["status"])?>
                            </a>
                        </td>
                        <td></td>
                        <td class="user">
                            <div>
                                <?php if(isset($d["responsible"])):?>
                                <a href="<?=base_url()."issues/list_all/".$repo_slug."?".$filter_str."&responsible=".$d["responsible"]["username"]?>"
                                   title="Filter issues assigned to: <?=$d["responsible"]["display_name"]?>">
                                    <div class="aui-avatar aui-avatar-xsmall">
                                        <div class="aui-avatar-inner">
                                            <!--img src="https://bitbucket.org/account/czyang_jessie/avatar/32/?ts=1443338247" alt="" /-->
                                        </div>
                                    </div>
                                    <span title="<?=$d["responsible"]["username"]?>"><?=$d["responsible"]["display_name"]?></span>
                                </a>
                                <?php else:?>
                                -
                                <?php endif?>
                            </div>
                        </td>
                        <td class="date">
                            <div>
                                <time datetime="<?=$d["utc_created_on"]?>" data-title="true"><?=_ago(strtotime($d["created_on"]))?> ago</time>
                            </div>
                        </td>
                        <td class="date">
                            <div>
                                <time datetime="<?=$d["utc_last_updated"]?>" data-title="true"><?=_ago(strtotime($d["utc_last_updated"]))?> ago</time>
                            </div>
                        </td>
                    </tr>
                <?php endforeach?>
                </tbody>
            </table>

        </div>
    </div>
</div>
</body>
</html>