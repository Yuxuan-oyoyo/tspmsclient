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
            $('#start_datetime').datepicker({
                dateFormat: 'yy-mm-dd 00:00:00',
                minDate: '+0d',
                changeMonth: true,
                changeYear: true,
                altFormat: "yy-mm-dd"
            });
        });
    </script>
</head>
<body onload="onLoad()">
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
<div class="col-lg-offset-1 col-lg-10 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            Edit Task
        </h1>
    </div>

    <?php $t=$task;?>
    <div class="container">
        <form  role="form" action="<?=base_url().'Tasks/edit/'.$project_id.'/'.$t["task_id"]?>" method="post">
            <div class="col-lg-10 customer-info">
                <div class="form-group">
                    <label for="content">Content:</label>
                    <input name="content" id="content" type="text" class="form-control" value="<?=$t['content']?>" data-parsley-required>
                </div>
                <div class="form-group">
                    <label for="importance">Importance:</label>
                    <input type="number" name="importance" id="importance" class="form-control" value="<?=$t['importance']?>">
                </div>
                <div class="form-group">
                    <label for="targeted_start_datetime">Targeted Start Datetime:</label>
                    <input type="text" name="targeted_start_datetime" id="targeted_start_datetime" class="form-control clsDatePicker" value="<?=$t['targeted_start_datetime']?>" data-parsley-required>
                </div>
                <div class="form-group">
                    <label for="targeted_end_datetime">Targeted End Datetime:</label>
                    <input type="text" name="targeted_end_datetime" id="targeted_end_datetime" class="form-control clsDatePicker" value="<?=$t['targeted_end_datetime']?>" data-parsley-required>
                </div>
                <div class="form-group">
                    <label for="start_datetime">Start Datetime:</label>
                    <input type="text" name="start_datetime" id="start_datetime" class="form-control clsDatePicker" value="<?=$t['start_datetime']?>">
                </div>

                <div class="pull-right">
                    <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit">
                    <!--<a href="//?base_url().'Customers/edit/'.$c["c_id"]?" class="btn btn-primary">Submit</a>-->
                    <?php
                    if(isset($project_id)){
                        ?>
                        <a href="<?=base_url().'Projects/view_dashboard/'.$project_id?>" class="btn btn-default">Cancel</a>
                        <?php
                    }else {
                        ?>
                        <a href="<?= base_url() . 'Customers/list_all' ?>" class="btn btn-default">Cancel</a>
                        <?php
                    }
                    ?>
                </div>

            </div>

        </form>
    </div>

    </div>
</body>
</html>