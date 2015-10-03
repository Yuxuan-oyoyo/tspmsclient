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
        <a href="<?=base_url().'Project/add'?>" class="btn btn-success">Add</a>
        <a href="<?=base_url().'Project/list_all/include_hidden'?>" class="btn btn-primary">View All</a>
        <div class="col-xs-10 col-xs-offset-1">
            <table class="table table-bordered">
                <tr>
                    <th>Project ID</th>
                    <th>Project Title</th>
                    <th>Customer ID</th>
                    <th>Project Description</th>
                    <th>Project Value</th>
                    <th>Tags</th>
                    <th>Start Time</th>
                    <th>Current Phase</th>
                    <th>File Repo</th>
                    <th>No.of Use Cases</th>
                    <th>Bitbucket Repo</th>
                    <th>Last Updated</th>
                    <th>Remarks</th>
                    <!--<th>Project Duration</th>-->
                    <th></th>
                </tr>
                <?php if($projects==false):?>
                    <tr><td colspan="5">Weird thing happens.</td></tr>
                <?php else:?>
                    <?php foreach($projects as $p):?>
                        <tr><td><?=$p['project_id']?></td>
                            <td><?=$p['project_title']?></td>
                            <td><?=$p['customer_id']?></td>
                            <td><?=$p['project_description']?></td>
                            <td><?=$p['project_value']?></td>
                            <td><?=$p['tags']?></td>
                            <td><?=$p['start_time']?></td>
                            <td><?=$p['current_project_phase_id']?></td>
                            <td><?=$p['file_repo_name']?></td>
                            <td><?=$p['no_of_use_cases']?></td>
                            <td><?=$p['bitbucket_repo_name']?></td>
                            <td><?=$p['last_updated']?></td>
                            <td><?=$p['remarks']?></td>
                            <td><a href="<?=base_url().'Customers/customer/'.$c["c_id"]?>">More..</a></td>
                        <a href="<?=base_url().'Project/delete'?>" class="btn btn-danger">Delete</a>
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