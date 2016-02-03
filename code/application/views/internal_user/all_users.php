
<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#userTable').dataTable();

        });
    </script>
</head>
<body>
<?php
$class = [
    'dashboard_class'=>'',
    'projects_class'=>'',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'active',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>

<div class="col-lg-offset-1 col-lg-10">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            Internal Users&nbsp;
            <a href="<?=base_url('internal_users/add')?>" class=" btn btn-primary"><i class="fa fa-plus"></i>&nbsp; New User</a>
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
            <table class="table table-striped" id="userTable">
                <thead>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>BB username</th>
                <th>Type</th>
                <th>Status</th>
                <th></th>
                </thead>

                <?php if(!false == $users):?>
                    <?php foreach($users as $user):?>
                        <tr><td><?=$user['name']?></td>
                            <td><?=$user['username']?></td>
                            <td><?=$user['email']?></td>
                            <td><?=$user['bb_username']?></td>
                            <td><?=$user['type']?></td>
                            <td>
                                <?php if($user['is_active']==1):?>
                                    Active
                                <?php else:?>
                                    Deactivated
                                <?php endif?>
                            </td>
                            <td><a href="<?=base_url().'internal_users/edit/'.$user["u_id"]?>" class="btn btn-primary" type="button" ><i class="fa fa-pencil-square-o"></i></a></td>
                        </tr>
                    <?php endforeach?>
                <?php endif?>
            </table>
        </div>
    </div>
</div>
</body>
</html>