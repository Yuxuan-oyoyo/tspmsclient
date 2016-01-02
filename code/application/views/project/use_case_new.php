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
    'projects_class'=>'active',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>
<div class="container content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
           New Use Case
        </h1>
    </div>


    <div class="col-lg-offset-2 col-lg-8">
        <form  data-parsley-validate role="form" action="<?=base_url().'Usecases/new_use_cases/'.$project['project_id']?>" method="post">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input name="title" id="title"  type="text" class="form-control" data-parsley-required>
                </div>
                <div class="form-group">
                    <label for="issue_id">Issue:</label>
                    <select class="form-control" id="issue_id" name="issue_id" data-parsley-required>
                        <option value="1">1</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="flow">Flow:</label>
                       <textarea name="flow" id="flow" rows="3" data-parsley-required ></textarea>
                    <script>
                        CKEDITOR.replace( 'flow' );
                    </script>
                </div>
                <div class="form-group">
                    <label for="stakeholders">Stakeholders:</label>
                    <textarea class="form-control" name="stakeholders" id="stakeholders" rows="2" ></textarea>
                </div>
            </div>
                <div class="col-lg-6">
                <div class="form-group">
                    <label for="importance">Importance:</label>
                    <select class="form-control" id="importance" name="importance" data-parsley-required>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                </div>
                <div class="col-lg-6">
                <div class="form-group">
                    <label for="type">Type:</label>
                    <select class="form-control" id="type" name="type" data-parsley-required>
                        <option value="Internal">Internal</option>
                        <option value="External">External</option>
                    </select>
                </div>
                </div>

                <div class="col-lg-12 pull-right">
                    <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit">
                    <!--<a href="//?base_url().'Customers/edit/'.$c["c_id"]?" class="btn btn-primary">Submit</a>-->
                    <a href="<?= base_url() . 'Usecases/list_all/'.$project['project_id'] ?>" class="btn btn-default">Cancel</a>
                </div>


        </form>

    </div>



</body>
</html>