<?php
/**
 * Created by PhpStorm.
 * User: yuanyuxuan
 * Date: 28/1/16
 * Time: 12:57 PM
 */



defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">


<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="<?= base_url() . 'js/google_chart_historical.js' ?>"></script>
</head>

<body>
<?php

?>


<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            Historical Reports
        </h1>
    </div>

    <!-- /#page-content-wrapper -->


    <div id="chart_div" style="width: 800px; height: 500px;"></div>
    <div id="chart_div3" style="width: 800px; height: 500px;"></div>

</div>

</body>