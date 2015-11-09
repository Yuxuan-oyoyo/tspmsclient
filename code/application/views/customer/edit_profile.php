<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link href="<?=base_url().'css/parsley.css'?>" rel="stylesheet" type="text/css">
    <script src="<?= base_url() . 'js/parsley.min.js' ?>"></script>
</head>
<body>
<?php
$class = [
    'projects_class'=>'',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/customer_nav', $class);
?>
<div class="container content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            Edit Profile
            <small style="color:darkgrey; font-size: 18px">Fill in all the compulsary fields(*) to proceed. </small>
        </h1>
    </div>
    <div class="col-lg-offset-1 col-lg-6">

        <form role="form" id="form" data-parsley-validate class="form-horizontal" method="post"  action="<?=base_url('customers/edit_profile')?>">
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
            <div class="customer-info">
                <div class="form-group">
                    <label for="first_name">First Name*</label>
                    <input class="form-control" type="text" id="first_name" name="first_name" value="<?=$customer['first_name']?>" data-parsley-required >
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name*</label>
                    <input class="form-control" type="text" id="last_name" name="last_name" value="<?=$customer['last_name']?>" data-parsley-required >
                </div>
                <div class="form-group">
                    <label for="company_name">Company</label>
                    <input class="form-control" type="text" id="company_name" name="company_name" value="<?=$customer['company_name']?>"  >
                </div>
                <div class="form-group">
                    <label for="hp_number">HP Number*</label>
                    <input class="form-control" type="text" id="hp_number" name="hp_number" value="<?=$customer['hp_number']?>" data-parsley-required >
                </div>
                <div class="form-group">
                    <label for="other_number">Other Number</label>
                    <input class="form-control" type="text" id="other_number" name="other_number" value="<?=$customer['other_number']?>" >
                </div>
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input class="form-control" type="text" id="email" name="email" value="<?=$customer['email']?>" data-parsley-required data-parsley-type="email" >
                </div>
            </div>
            <div class="form-group">
                <a href="<?=base_url().'projects/customer_overview/'.$this->session->userdata('Customer_cid')?>" class="btn btn-default" id="cancel">Cancel</a>&nbsp;
                <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>

    </div>
</div>




</body>
</html>