<?php
/**
 * Created by PhpStorm.
 * User: yuanyuxuan
 * Date: 27/1/16
 * Time: 10:33 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">


<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="<?= base_url() . 'js/google_chart.js' ?>"></script>
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
    ?>

    <aside class="sidebar-left">
        <div class="sidebar-links">
            <a class="link-blue" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i>Project Overview</a>
            <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i>Update & Milestone</a>
            <?php
            if($project['bitbucket_repo_name']==null){
                ?>
                <a class="link-grey"><i class="fa fa-wrench"></i>Issues</a>
                <?php
            }else {
                ?>
                <a class="link-blue " href="<?= base_url() . 'Issues/list_all/' . $project["bitbucket_repo_name"] ?>"><i class="fa fa-wrench"></i>Issues</a>
                <?php
            }
            ?>
            <a class="link-blue" href="<?=base_url().'Usecases/list_all/'.$project["project_id"]?>"><i class="fa fa-list"></i>Use Case List</a>
            <a class="link-blue  selected" href="#"><i class="fa fa-bar-chart"></i>Analytics</a>
            <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
        </div>
    </aside>

    <div class="col-lg-offset-1 content">
    <!-- Page Content -->
        <div class="col-lg-12">
            <h1 class="page-header">
                <?='#'.$project['project_id'].'. '.$project['project_title']?>&nbsp;
            </h1>
        </div>

        <!-- /#page-content-wrapper -->
        <div class="col-lg-12 col-md-offset-7 col-md-4" align="right">

            <div class="col-sm-4" id="chart_div" style="width: 120px; height: 45px;" align="right"></div>
            <div class="col-sm-4" id="chart_div2" style="width: 120px; height: 35px;" ></div>
            <div class="col-sm=4"></div>
        </div>

        <div class="col-lg-12 col-sm-8" id="chart_div3" style="width: 600px; height: 350px;"></div>
        <div>&nbsp;<br/><br/><br/><br/><br/>

            &nbsp;&nbsp; <select name="select" id="select">
                <option value="a" selected>All phases</option>
                <option value="b" >Lead</option>
                <option value="c" >Requirement</option>
                <option value="d" >Build</option>
                <option value="e" >Testing</option>
                <option value="f" >Deploying</option>
            </select>
            &nbsp;
            <select name="select" id="select2">
                <option value="a" selected>All kinds</option>
                <option value="b" >kind1</option>
                <option value="c" >kind2</option>
                <option value="d" >kind3</option>
                <option value="e" >kind4</option>
                <option value="f" >kind5</option>
            </select>
            &nbsp;
            <select name="select" id="select3">
                <option value="a" selected>All priorities</option>
                <option value="b" >priority1</option>
                <option value="c" >priority2</option>
                <option value="d" >priority3</option>
                <option value="e" >priority4</option>
                <option value="f" >priority5</option>
            </select>

            &nbsp;
            <input type="submit" value="Filter">
            <br/>
            <br/>
        </div>



        <div class="col-lg-12 col-sm-3" id="chart_div5" style="width: 400px; height: 200px;"></div>
        <div class="col-lg-12" id="chart_div4" style="height: 150px;"></div>




    </div>

</body>