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
        <?php  $u = $user?>
        <?php if(!isset($u)):?>
            <div>The user you are looking for does not exist</div>
        <?php else:?>
            <div id="info-container" user-id="<?=$u['u_id']?>">
                <div class="col-xs-10 col-xs-offset-1">
                    <p class="well-body cus-name">
                        <span user-attr="name"><?=$u['name']?></span>
                    </p>
                    <p class="well-body">
                        Username:
                        <span user-attr="username"><?=$u['username']?></span></br>
                        BitBucket Username:
                        <span user-attr="bb_username"><?=$u['bb_username']?></span></br>
                        Account Type
                        <span user-attr="type"><?=$u['type']==1?"PM":"Dev"?></span></br>
                        <span user-attr="is_active"><?=$u['is_active']==1?"Active":"Inactive"?></span>

                    </p>
                    <a id="edit" href="#">Edit..</a>
                    <p>Projects</p>
                    <ol>
                        <li>Project 1</li>
                        <li>Project 2</li>
                        <li>Project 3</li>
                    </ol>

                </div>
            </div>
        <?php endif?>

    </div>
    <a href="<?= base_url() . 'Internal_users' ?>">back..</a>
</div>
</body>
<script>
    $("#info-container").on("click","#edit",function(e){
        e.preventDefault();
        $("#info-container").wrap("<form" + ">");
        $('span[user-attr]').each(function(){
            var $original = $(this).text();
            var $length = 20;
            var $attr = $(this).attr("user-attr");
            if($attr=="type"){
                if($(this).text().toUpperCase()=="DEV"){
                    $(this).html('<input type="radio" name="'+$attr+'" value="0" checked>Dev'
                        +'<input type="radio" name="'+$attr+'" value="1">PM');
                }else{
                    $(this).html('<input type="radio" name="'+$attr+'" value="0" >Dev'
                        +'<input type="radio" name="'+$attr+'" value="1" checked>PM');
                }
            }else if($attr=="is_active"){
                if($(this).text().toUpperCase()=="ACTIVE"){
                    $(this).html('<input type="radio" name="'+$attr+'" value="1" checked>Active'
                        +'<input type="radio" name="'+$attr+'" value="0">Inactive');
                }else{
                    $(this).html('<input type="radio" name="'+$attr+'" value="1" >Active'
                        +'<input type="radio" name="'+$attr+'" value="0" checked>Inactive');
                }

            }else{
                $(this).html('<input name="' + $attr + '" value="' + $original
                    + '" size="' + $length + '" placeholder="'+$attr.replace('_',' ')+'">');
            }

        })
        $(this).hide();
        $(this).after("<a id='cancel' href='#'>cancel</a> ")
            .after("<a id='submit' href='#'>submit</a>");
    })
    $("#info-container").on("click","#cancel",function(e){
        e.preventDefault();
        location.reload();
    });
    $("#info-container").on("click","#submit",function(e){
        e.preventDefault();
        $.ajax({
            url:"<?=base_url().'Customers/edit/'.(isset($u['u_id'])?$u['u_id']:null)?>",
            data:$(this).closest("form").serialize(),
            async:false,
            success($response){
            if($response!=0){location.reload();}
        }
    });
    })


</script>
</html>
