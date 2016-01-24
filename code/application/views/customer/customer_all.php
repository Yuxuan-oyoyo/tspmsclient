
<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#customerTable').dataTable();
        });
    </script>
</head>
<body>
<?php
$class = [
    'dashboard_class'=>'',
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
            Customers
        </h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
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
            <table class="table table-striped" id="customerTable">
                <thead>
                <th>Title</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
                <th>Company</th>
                <th>Contact number</th>
                <th>Other number</th>
                <th>Email</th>
                <th>Status</th>
                <th></th>
                </thead>

                <?php if(!false == $customers):?>
                    <?php foreach($customers as $c):?>
                        <tr><td><?=$c['title']?></td>
                            <td><?=$c['first_name']?></td>
                            <td><?=$c['last_name']?></td>
                            <td><?=$c['username']?></td>
                            <td><?=$c['company_name']?></td>
                            <td><?=$c['hp_number']?></td>
                            <td><?=$c['other_number']?></td>
                            <td><?=$c['email']?></td>
                            <td>
                                <?php if($c['is_active']==1):?>
                                    Active
                                <?php else:?>
                                    Deactivated
                                <?php endif?>
                            </td>
                            <td><a href="<?=base_url().'Customers/update_customer/'.$c["c_id"]?>" class="btn btn-primary" type="button" ><i class="fa fa-pencil-square-o"></i></a></td>
                        </tr>
                    <?php endforeach?>
                <?php endif?>
            </table>
        </div>
    </div>
</div>
</body>
</html>