<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <style>
        i.glyphicon{margin-right: 1rem;}
        p.well-body{font-size: 1.5rem;}
        div.out-well-col{height: 20em;}
        div.card{height:100%;padding: 10%}
        div.card>.cus-name{margin:0;font-size:4rem}
        div.card>p>i{margin-top:0.4rem}
        .add-new:hover{cursor:hand}
        .new-card{display: flex; align-items: center;justify-content: center;}
    </style>
</head>

<body>
<div class="container-fluid">

    <div class="row">
        <h1>Customers</h1>
        <a href="<?=base_url().'Internal_users/add'?>" class="btn btn-success">Add</a>
        <a href="<?=base_url().'Internal_users/list_all/include_hidden'?>" class="btn btn-primary">View All</a>
        <div class="col-xs-10 col-xs-offset-1">
            <table class="table table-bordered">
                <tr><th>Name</th><th>Company</th><th>Number</th><th>Email</th><th></th></tr>
                <?php if($users==false):?>
                    <tr><td colspan="5">Weird thing happens.</td></tr>
                <?php else:?>
                    <?php foreach($users as $u):?>
                        <?php if($u['type']==1):?>
                            <tr><td><?=$u['name']?></td>
                                <td><?=$u['username']?></td>
                                <td><?=$u['bb_username']?></td>
                                <td><?=$u['type']?></td>
                                <td><a href="<?=base_url().'Internal_users/user/'.$u["u_id"]?>">More..</a></td>
                            </tr>
                        <?php endif?>
                    <?php endforeach?>
                    <?php foreach($users as $u):?>
                        <?php if($u['type']==0):?>
                            <tr><td><?=$u['name']?></td>
                                <td><?=$u['username']?></td>
                                <td><?=$u['bb_username']?></td>
                                <td><?=$u['type']?></td>
                                <td><a href="<?=base_url().'Internal_users/user/'.$u["u_id"]?>">More..</a></td>
                            </tr>
                        <?php endif?>
                    <?php endforeach?>
                <?php endif?>
            </table>
        </div>
    </div>
</div>

</body>
<script>

</script>
</html>