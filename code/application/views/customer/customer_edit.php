<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <script>
        function showChangePassword(){
                $('#trigger').hide();
                $('.changePassword').show();
        }
        function onLoad(){
            $('.changePassword').hide();
        }
    </script>
</head>
<body onload="onLoad()">
<?php
$class = [
    'projects_class'=>'',
    'message_class'=>'',
    'customers_class'=>'active',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>
<div class="col-lg-offset-1 col-lg-10 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            Edit Customer
        </h1>
    </div>

    <?php $c=$customer;?>
    <div class="row">
        <form data-parsley-validate role="form" action="<?=base_url().'Customers/edit/'.$c["c_id"]?>" method="post">
        <div class="col-lg-5 customer-info">

                    <div class="form-group">
                    <label for="title">Title:</label>
                    <input name="title" id="title" type="text" class="form-control" value="<?=$c['title']?>" data-parsley-required>
                </div>
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input name="first_name" id="first_name" type="text" class="form-control" value="<?=$c['first_name']?>" data-parsley-required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input name="last_name" id="last_name" type="text" class="form-control" value="<?=$c['last_name']?>" data-parsley-required>
                </div>
                <div class="form-group">
                    <label for="company_name">Company:</label>
                    <input name="company_name" id="company_name" type="text" class="form-control" value="<?=$c['company_name']?>"  data-parsley-required>
                </div>
                <div class="form-group">
                    <label for="hp_number">hp number:</label>
                    <input name="hp_number" id="hp_number" type="text" class="form-control" value="<?=$c['hp_number']?>" data-parsley-required>
                </div>
                <div class="form-group">
                    <label for="other_number">other number:</label>
                    <input name="other_number" id="other_number" type="text" class="form-control" value=<?=$c['other_number']?>>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input name="email" id="email" type="email" class="form-control" value="<?=$c['email']?>" data-parsley-type="email" data-parsley-required>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status" class="form-control" name="status">
                        <?php if($c['is_active']==1):?>
                            <option value="1">Active</option>
                            <option value="0">Deactivated</option>
                        <?php else:?>
                            <option value="0">Deactivated</option>
                            <option value="1">Active</option>
                        <?php endif?>
                    </select>
                </div>
            </div>
            <div class="col-lg-offset-1 col-lg-5 customer-info">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input name="username" id="username" disabled type="text" class="form-control" value=<?=$c['username']?>>
                </div>
                <div class="form-group" id="trigger">
                    <label for="password">Password:</label><br>
                    <button class="btn btn-default" onclick="showChangePassword()">Click here to reset</button>
                </div>
                <div class="changePassword">
                    <div class="form-group">
                        <label for="new_password">New Password*</label>
                        <input class="form-control" type="password" id="new_password" placeholder="minimum length:6" name="new_password" data-parsley-minlength="6">
                    </div>
                </div>
                <div class="pull-right">
                    <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit">
                    <!--<a href="//?base_url().'Customers/edit/'.$c["c_id"]?" class="btn btn-primary">Submit</a>-->
                    <a href="<?=base_url().'Customers/list_all'?>" class="btn btn-default">Cancel</a>
                </div>
            </div>

            </form>
        </div>


    </div>



    <!-- /#page-content-wrapper -->

</div>
</body>
</html>