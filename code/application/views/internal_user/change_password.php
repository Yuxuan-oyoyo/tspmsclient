
<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
</head>
<body>
<?php
$class = [
    'projects_class'=>'',
    'message_class'=>'',
    'customers_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>
<div class="container content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
           Change Password
            <small style="color:darkgrey; font-size: 18px">Enter your old and new password to proceed. </small>
        </h1>
    </div>
    <div class="col-lg-offset-1 col-lg-6">

        <form class="form-horizontal" id="form" method="post" data-parsley-validate action="<?=base_url('customer_authentication/change_password')?>">
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
                <label for="existing_password">Existing Password*</label>
                <input class="form-control" type="password" id="existing_password" name="existing_password" data-parsley-required >
            </div>
            <div class="form-group">
                <label for="new_password">New Password*</label>
                <input class="form-control" type="password" id="new_password" placeholder="minimum length:6" name="new_password" data-parsley-required data-parsley-minlength="6">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password*</label>
                <input class="form-control" type="password" id="confirm_password" placeholder="minimum length:6" name="confirm_password" data-parsley-required data-parsley-minlength="6" data-parsley-equalto="#new_password">
            </div>
        </div>
        <div class="form-group">
        <a href="<?=base_url().'projects/list_all/'?>" class="btn btn-default" id="cancel">Cancel</a>&nbsp;
        <button type="submit"  class="btn btn-primary">Submit</button>
        </div>
    </form>
    </div>
    </div>




    </body>
</html>