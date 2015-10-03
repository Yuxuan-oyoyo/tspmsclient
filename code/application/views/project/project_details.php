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
        .cus-name{margin:0;font-size:4rem}
        i{margin-top:0.4rem}
        .add-new:hover{cursor:hand}
        .new-card{display: flex; align-items: center;justify-content: center;}

    </style>
</head>

<body>
<div class="container-fluid">

    <div class="row">
        <?php  $p = $project?>
        <?php if(!isset($p)):?>
            <div>The project your are looking for does not exist</div>
        <?php else:?>
            <div id="info-container" project-id="<?=$p['project_id']?>">
                <div class="col-xs-10 col-xs-offset-1">
                    <p class="well-body cus-name">
                        Project Title:
                        <span project-attr="project_title"><?=$p['project_title']?></span>
                    </p>
                    <p class="well-body">
                        Customer ID:
                        <span project-attr="customer_id"><?=$p['customer_id']?></span></br>
                        Project Description:
                        <span project-attr="project_description"><?=$p['project_description']?></span></br>
                        Project Value:
                        <span project-attr="project_value"><?=$p['project_value']?></span></br>
                        Tags:
                        <span project-attr="tags"><?=$p['tags']?></span></br>
                        Start Time:
                        <span project-attr="start_time"><?=$p['start_time']?></span></br>
                        Current Phase:
                        <span project-attr="current_project_phase_id"><?=$p['current_project_phase_id']?></span></br>
                        File Repo Name:
                        <span project-attr="file_repo_name"><?=$p['file_repo_name']?></span></br>
                        No. of Use Cases:
                        <span project-attr="no_of_use_cases"><?=$p['no_of_use_cases']?></span></br>
                        Bitbucket Repo Name:
                        <span project-attr="bitbucket_repo_name"><?=$p['bitbucket_repo_name']?></span></br>
                        Last Updated:
                        <span project-attr="last_updated"><?=$p['last_updated']?></span></br>
                        Remarks:
                        <span project-attr="remarks"><?=$p['remarks']?></span></br>
                        Status:
                        <span project-attr="is_ongoing"><?=$p['is_ongoing']==1?"Ongoing":"Closed"?></span>
                     </p>
                    <a id="edit" href="#" class="btn btn-info">Edit..</a>
                </div>
            </div>
        <?php endif?>

    </div>
    <a href="<?= base_url() . 'Projects' ?>">back..</a>
</div>
</body>
<script>
    $("#info-container").on("click","#edit",function(e){
        e.preventDefault();
        $("#info-container").wrap("<form" + ">");
        $('span[project-attr]').each(function(){
            var $original = $(this).text();
            var $length = 20;
            var $attr = $(this).attr("project-attr");
            if($attr=="project_title"){
                $(this).closest("p").removeClass("cus-name");
                $length = 10;
            }
            if($attr=="is_ongoing"){
                if($(this).text()=="Ongoing"){
                    $(this).html('<input type="radio" name="'+$attr+'" value="1" checked>Ongoing'
                        +'<input type="radio" name="'+$attr+'" value="0">Closed');
                }else{
                    $(this).html('<input type="radio" name="'+$attr+'" value="1" >Ongoing'
                        +'<input type="radio" name="'+$attr+'" value="0" checked>Closed');
                }

            }else{
                $(this).html('<input name="' + $attr + '" value="' + $original
                    + '" size="' + $length + '" placeholder="'+$attr.replace('_',' ')+'">');
            }

        })
        $(this).hide();
        $(this).after("<a id='cancel' href='#' class='btn btn-warning'>Cancel</a>")
            .after("<a id='submit' href='#' class='btn btn-success'>Submit</a>");
    })
    $("#info-container").on("click","#cancel",function(e){
        e.preventDefault();
        location.reload();
    });
    $("#info-container").on("click","#submit",function(e){
        e.preventDefault();
        $.ajax({
            url:"<?=base_url().'Projects/edit/'.(isset($p['project_id'])?$p['project_id']:null)?>",
            data:$(this).closest("form").serialize(),
            async:false,
            success($response){
            if($response!=0){location.reload();}
        }
    });
    })


</script>
</html>
