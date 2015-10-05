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
        <h1>Projects</h1>
        <a href="<?=base_url().'Projects/add'?>" class="btn btn-success">Create New Project</a>
        <a href="<?=base_url().'Projects/list_all/include_hidden'?>" class="btn btn-primary">View All</a>
        <div class="col-xs-10 col-xs-offset-1">
            <table class="table table-bordered">
                <tr>
                    <th>Project Title</th>
                    <th>Customer ID</th>
                    <th>Project Value</th>
                    <th>Start Time</th>
                    <th>Current Phase</th>
                    <!--<th>Project Duration</th>-->
                    <th></th>
                </tr>
                <?php if($projects==false):?>
                    <tr><td colspan="5">No project created yet.</td></tr>
                <?php else:?>
                    <?php foreach($projects as $p):?>
                        <tr>
                            <td><?=$p['project_title']?></td>
                            <td><?=$p['c_id']?></td>
                            <td><?=$p['project_value']?></td>
                            <td><?=$p['start_time']?></td>
                            <td><?=$p['current_project_phase_id']?></td>
                            <td><a href="<?=base_url().'Projects/project_by_id/'.$p["project_id"]?>">More..</a></td>
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