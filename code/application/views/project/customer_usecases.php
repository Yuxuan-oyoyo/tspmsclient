<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#usecaseTable').dataTable();
        });
    </script>
</head>
<body>
<?php
$class = [
    'dashboard_class'=>'',
    'projects_class'=>'active',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/customer_nav', $class);
?>
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue" href="<?=base_url().'projects/customer_view/'.$project['project_id']?>"><i class="fa fa-flag"></i>Update & Milestone</a>
        <a class="link-blue selected" href="#"><i class="fa fa-list"></i>Use Case List</a>
        <a class="link-blue " href="<?=base_url().'Upload/customer_repo/'.$project["project_id"]?>"><i class="fa fa-folder"></i>File Repository</a>
    </div>

</aside>
<div class="container content">
    <h1 class="page-header">
        Use Case List&nbsp;
    </h1>
<table class="table table-responsive" id="usecaseTable">
    <thead>
    <th>ID</th>
    <th>Title</th>
    <th>Importance</th>
    <th>Last Updated</th>
    <th>View Detail</th>
    </thead>
    <?php if(isset($usecases)):?>
        <?php foreach($usecases as $u):?>
            <tr><td><?=$u['sub_id']?></td>
                <td><?=$u['title']?></td>
                <td><?=$u['importance']?></td>
                <td><?=$u['last_updated']?></td>
                <td>
                    <button class="btn btn-default" type="button" data-toggle="modal" data-target="#detailModal<?=$u['usecase_id']?>" ><i class="fa fa-eye"></i></button>
                </td>
            </tr>
        <?php endforeach?>
    <?php endif?>
</table>
    </div>



<?php if(isset($usecases)):?>
    <?php foreach($usecases as $u):?>
        <div class="modal fade" id="detailModal<?=$u['usecase_id']?>" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Use Case Detail</h4>
                    </div>
                    <div class="modal-body">
                        <p><strong>Title: </strong> </p>
                        <?=$u['title']?>
                        <hr>
                        <p><strong>Stakeholders: </strong> </p>
                        <?=$u['stakeholders']?>
                        <hr>
                        <p><strong>Flow: </strong> </p>
                        <?=$u['flow']?>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach?>
<?php endif?>

</body>
</html>