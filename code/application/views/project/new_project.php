
<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <script>
        function cus_option(){
            if($("#customer_option").val()=="from-existing"){
                $('#existing_customer').show();
                $('#new_customer').hide();
            }else{
                $('#existing_customer').hide();
                $('#new_customer').show();
            }
        };
    </script>
    <script type="text/javascript">
        $('#form').parsley();
    </script>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">

</head>
<body onload="cus_option()">
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

    <form class="form-horizontal" action="<?=base_url().'Projects/create_new_project'?>" method="post"
          data-parsley-validate action="<?=base_url('project_validation/create_new')?>>

    <div class="col-lg-12">
        <h1 class="page-header">
            New Project&nbsp;
            <a href="<?=base_url().'Projects/list_all'?>" class="btn btn-default">Cancel</a>&nbsp;
            <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit">
        </h1>
    </div>

        <div class="col-lg-6 project-info">
            <h3>Project Information</h3>
            <hr>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="project_title">Title*</label>
                    <input class="form-control" id="project_title" name="project_title" value="" data-parsley-required>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="project_description">Description</label>
                    <textarea class="form-control" id="project_description" name="project_description" ></textarea>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="form-group">
                    <label for="file_repo_name">File repo name</label>
                    <input class="form-control" name="file_repo_name" value="">
                </div>
            </div>
            <div class="col-lg-offset-1 col-lg-6">
                <div class="form-group">
                    <label for="bitbucket_repo_name">Bitbucket repo name</label>
                    <input class="form-control" name="bitbucket_repo_name" value="">
                </div>
            </div>
            <div class="col-lg-5">
                <div class="form-group">
                    <label for="no_of_use_cases">Number of usecases</label>
                    <input class="form-control" name="no_of_use_cases" value="">
                </div>
            </div>
            <div class="col-lg-offset-1 col-lg-6">
                <div class="form-group">
                    <label for="project_value">Project value</label>
                    <input class="form-control" name="project_value" value="">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input class="form-control tokenfield" name="tags" value="" data-parsley-required>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="staging_link">Staging Link</label>
                    <input class="form-control" name="staging_link" value="">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="production_link">Production Link</label>
                    <input class="form-control" name="production_link" value="">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="customer_preview_link">Customer Preview Link</label>
                    <input class="form-control" name="customer_preview_link" value="">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <input class="form-control" name="remarks" value="">
                </div>
            </div>
            <hr>
        </div>
        <div class="col-lg-5 customer-info">
            <h3>Customer Information</h3>
            <hr>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="customer_option"> Customer</label>
                    <select class="form-control" id="customer_option" name="customer_option" onchange="cus_option()">
                        <option value="create-new">Create new</option>
                        <option value="from-existing">From existing</option>
                    </select>
                </div>
                <div id="existing_customer">
                    <div class="form-group">
                        <label >Choose Customer:</label>
                        <select class="form-control" name="c_id">
                            <?php foreach($customers as $c) {
                                    if($c['is_active']==1) {
                                        ?>
                                        <option value="<?= $c['c_id'] ?>"><?= $c['first_name'] ?>
                                            &nbsp;<?= $c['last_name'] ?></option>
                                        <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div id="new_customer">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group ">
                        <label for="first_name">First name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input type="text" class="form-control"  name="last_name" id="last_name">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="company_name">Company name</label>
                        <input type="text" class="form-control" name="company_name" id="company_name">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="hp_number">HP Number</label>
                        <input type="text" class="form-control" name="hp_number" id="hp_number">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="other_number">Other Number</label>
                        <input type="text" class="form-control" name="other_number" id="other_number">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="username">Password</label>
                        <input type="password" class="form-control" name="password" id="password" value="<?=DEFAULT_PASSWORD?>" >
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!--will generate components based on the selection input above-->

<!-- /#page-content-wrapper -->
<!-- jQuery -->
<script src="js/jquery.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>