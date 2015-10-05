<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controller_path = base_url().'Issues/';
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

        <a href="<?=$controller_path."add"?>" class="btn btn-success">Add</a>


        <div class="col-xs-10 col-xs-offset-1">
            <table class="table table-bordered">
                <tr>
                    <th>id</th><th>Status</th><th>Priority</th><th>Title</th><th>Reported by</th>
                    <th>Last updated</th><th>Responsible</th><th>Milestone</th><th>Content</th>
                </tr>
                <?php if($issues==false):?>
                    <tr><td colspan="5">Weird thing happens.</td></tr>
                <?php else:?>
                    <?php foreach($issues as $i):?>
                        <tr><td><?=$i['local_id']?></td>
                            <td><?=$i['username']?></td>
                            <td><?=$i['bb_username']?></td>
                            <td><?=$i['type']==1?"PM":"Dev"?></td>
                            <td><a href="<?=base_url().'Internal_users/user/'.$i["u_id"]?>">More..</a></td>
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
