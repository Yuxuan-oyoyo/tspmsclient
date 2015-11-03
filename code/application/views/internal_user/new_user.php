
<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <link href="<?=base_url().'css/parsley.css'?>" rel="stylesheet" type="text/css">
    <script src="<?= base_url() . 'js/parsley.min.js' ?>"></script>
    <script type="text/javascript">
        $('#form').parsley();
    </script>
</head>
<body>
<?php
$class = [
    'projects_class'=>'',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'active',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>
<div class="container content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
           New User
            <small style="color:darkgrey; font-size: 18px">Enter your old and new password to proceed. </small>
        </h1>
    </div>
    <div class="col-lg-offset-1 col-lg-6">

        <form class="form-horizontal" id="form" method="post" data-parsley-validate action="<?=base_url('internal_users/insert')?>">
            <?php if($this->session->userdata('message')):?>
                <div class="form-group">
                    <div class="alert alert-info " role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <?=$this->session->userdata('message')?>
                    </div>
                </div>
                <?php $this->session->unset_userdata('message') ?>
            <?php endif;?>
            <?php if(validation_errors()):?>
                <div class="form-group">
                    <div class="alert alert-info" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <?=validation_errors();?>
                    </div>
                </div>
            <?php endif;?>
            <div class=" customer-info">
                <div class="form-group">
                    <label for="existing_password">Username*</label>
                    <input class="form-control" type="text" id="username" name="username" data-parsley-required >
                </div>
                <div class="form-group">
                    <label for="existing_password">Name*</label>
                    <input class="form-control" type="text" id="name" name="name" data-parsley-required >
                </div>
                <div class="form-group">
                    <label for="existing_password">BB username</label>
                    <input class="form-control" type="text" id="bb_username" name="bb_username" >
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select class="form-control" id="type" name="type" >
                        <option vlaue="PM" >PM</option>
                        <option vlaue="Developer" > Developer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Password*</label>
                    <input class="form-control" type="password" id="password" placeholder="minimum length:6" name="password" value="<?=DEFAULT_PASSWORD?>" data-parsley-required data-parsley-minlength="6">
                </div>
            </div>
            <div class="form-group">
                <a href="<?=base_url().'internal_users/list_all/'?>" class="btn btn-default" id="cancel">Cancel</a>&nbsp;
                <button type="submit"  class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>




</body>
</html>