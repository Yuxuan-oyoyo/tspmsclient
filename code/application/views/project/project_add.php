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
        .project-name{margin:0;font-size:4rem}
        i{margin-top:0.4rem}
        .add-new:hover{cursor:hand}
        .new-card{display: flex; align-items: center;justify-content: center;}

    </style>
</head>

<body>
<div class="container-fluid">

    <div class="row">

            <div id="info-container">
                <div class="col-xs-10 col-xs-offset-1">
                    <form>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" project-attr="project_title">Project Title</span>
                            <input type="text" name="project_title" class="form-control" aria-describedby="basic-addon1" required="true">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" project-attr="customer_id">Customer ID</span>
                            <input type="text" name="customer_id" class="form-control" aria-describedby="basic-addon1" required placeholder="need dropdown for customer name">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" project-attr="project_description">Project Description</span>
                            <textarea name="project_description" rows="3" cols="50" value="" class="form-control" aria-describedby="basic-addon1"></textarea>
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" project-attr="project_value">Project Value</span>
                            <input type="text" name="project_value" class="form-control" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" project-attr="tags">Tag</span>
                            <input type="text" name="tags" class="form-control" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" project-attr="file_repo_name">File Repo Name</span>
                            <input type="text" name="file_repo_name" class="form-control" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" project-attr="no_of_use_cases">No.of Use Cases</span>
                            <input type="number" name="no_of_use_cases" class="form-control" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" project-attr="bitbucket_repo_name">Bitbucket Repo Name</span>
                            <input type="text" name="bitbucket_repo_name" class="form-control" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1" project-attr="remarks">Remarks</span>
                            <input type="text" name="remarks" class="form-control" aria-describedby="basic-addon1">
                        </div>
                    </p>
                        <a id='submit' href='#' class='btn btn-success'>Submit</a>
                        <a href="<?= base_url() . 'Projects' ?>" class='btn btn-warning'>Cancel</a>
                    </form>
                </div>
            </div>


    </div>
    <a href="<?= base_url() . 'Projects' ?>">back..</a>
</div>
</body>
<script>
    $("#info-container").on("click","#cancel",function(e){
        e.preventDefault();
        location.reload();
    });
    $("#info-container").on("click","#submit",function(e){
        e.preventDefault();
        $.ajax({
            url:"<?=base_url().'Projects/create_new_project'?>",
            data:$(this).closest("form").serialize(),
            async:false,
            success(response){
               //<? $project_id = $this->db->select('project_id')->order_by('project_id','desc')->limit(1)->get('project')->row('project_id');?>
               // if(response==0){
                    //location.href = "www.google.com";
                   
                //}
            }
        });
    });
</script>
</html>
