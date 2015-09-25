<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <style>
        i.glyphicon{
            margin-right: 1rem;}
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
            <a href="<?=base_url().'Customers/add'?>" class="btn btn-success">Add</a>
            <a href="<?=base_url().'Customers/list_all/include_hidden'?>" class="btn btn-primary">View All</a>
            <div class="col-xs-10 col-xs-offset-1">
                <table class="table table-bordered">
                    <tr><th>Name</th><th>Company</th><th>Number</th><th>Email</th><th></th></tr>
                    <?php if($customers==false):?>
                            <tr><td colspan="5">There is no customer yet. Click "Add" to add your first customer.</td></tr>
                    <?php else:?>
                        <?php foreach($customers as $c):?>
                            <tr><td><?=$c['first_name']?> <?=$c['last_name']?></td>
                                <td><?=$c['company_name']?></td>
                                <td><?=$c['hp_number']?></td>
                                <td><?=$c['email']?></td>
                                <td><a href="<?=base_url().'Customers/customer/'.$c["c_id"]?>">More..</a></td>
                            </tr>
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