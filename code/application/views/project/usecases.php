
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
            $('#usecaseTable').dataTable();
        });

        function deleteButtonClicked(uc_id) {
            $('#deleteModal').data('uc_id', uc_id).modal('show');

        }
        function confirmDelete() {
            // handle deletion here
            var uc_id = $('#deleteModal').data('uc_id');
            //to be change to delete milestone controller
            var delete_url = "<?= base_url().'Usecases/delete_usecase/'?>" + uc_id;
            window.location.href = delete_url;
        }
    </script>
</head>
<body>
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
    <h1 class="page-header">
        Use Case List&nbsp;
        <a href="<?= base_url() . 'Usecases/new_use_case/'.$project_id ?>"  class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add</a>&nbsp;
    </h1>
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
    <table class="table table-responsive" id="usecaseTable">
        <thead>
        <th>ID</th>
        <th>Title</th>
        <th>Issue</th>
        <th>Importance</th>
        <th>Type</th>
        <th>Last Updated</th>
        <th></th>
        </thead>
        <?php if(isset($usecases)):?>
            <?php foreach($usecases as $u):?>
                <tr><td><?=$u['sub_id']?></td>
                    <td><?=$u['title']?></td>
                    <td><?=$u['issue_id']?></td>
                    <td><?=$u['importance']?></td>
                    <td><?=$u['type']?></td>
                    <td><?=$u['last_updated']?></td>
                    <td><button class="btn btn-default" type="button" ><i class="fa fa-pencil-square-o"></i></button>
                        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#detailModal<?=$u['usecase_id']?>" ><i class="fa fa-eye"></i></button>
                        <button class="btn btn-default" type="button" onclick="deleteButtonClicked(<?=$u['usecase_id']?>)" ><i class="fa fa-trash" ></i></button>
                    </td>
                </tr>
            <?php endforeach?>
        <?php endif?>
    </table>
</div>
<!-- Delete Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong>Delete Use Case</strong>
            </div>
            <div class="modal-body">
                This action cannot be undone, do you wish to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="btnYes" onclick="confirmDelete()"> Delete </button>
            </div>
        </div>
    </div>
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
                        <p><strong>Title: </strong> <?=$u['title']?></p>
                        <p><strong>Stakeholders: </strong> <?=$u['stakeholders']?></p>
                        <p><strong>Flow: </strong> <?=$u['flow']?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Done</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach?>
<?php endif?>
</body>
</html>