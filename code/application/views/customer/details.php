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
        <?php  $c = $customer?>
        <?php if(!isset($c)):?>
            <div>The customer your are looking for does not exist</div>
        <?php else:?>
        <div id="info-container" cus-id="<?=$c['c_id']?>">
            <div class="col-xs-10 col-xs-offset-1">
                <p class="well-body cus-name"><span cus-attr="first_name"><?=$c['first_name']?></span>
                    <span cus-attr="last_name"><?=$c['last_name']?></span>
                </p>
                <p class="well-body">
                    Company: <span cus-attr="company_name"><?=$c['company_name']?></span></br>
                    <i class="glyphicon glyphicon-envelope"></i>
                    <span cus-attr="email"><a href="mailto:<?=$c['email']?>"><?=$c['email']?></a></span></br>
                    <i class="glyphicon glyphicon-phone"></i>
                    <span cus-attr="hp_number"><?=$c['hp_number']?></span></br>
                    <i class="glyphicon glyphicon-phone-alt"></i>
                    <span cus-attr="other_number"><?=isset($c['other_number'])?$c['other_number']:'-'?></span></br>
                    <span cus-attr="is_active"><?=$c['is_active']==1?"Active":"Inactive"?></span>

                </p>
                <a id="edit" href="#">Edit..</a>
                <a id="delete" href="#">Delete..</a>
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
    <a href="<?= base_url() . 'Customers' ?>">back..</a>
</div>
</body>
<script>
    $("#info-container").on("click","#edit",function(e){
        e.preventDefault();
        $("#info-container").wrap("<form" + ">");
        $('span[cus-attr]').each(function(){
            var $original = $(this).text();
            var $length = 20;
            var $attr = $(this).attr("cus-attr");
            if($attr=="first_name" ||$attr=="last_name"){
                $(this).closest("p").removeClass("cus-name");
                $length = 10;
            }
            if($attr=="is_active"){
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
            url:"<?=base_url().'Customers/edit/'.(isset($c['c_id'])?$c['c_id']:null)?>",
            data:$(this).closest("form").serialize(),
            async:false,
            success(response){
                if(response!=0){
                    location.reload();
                }
            }
        });
    })
    $("#info-container").on("click","#delete", function(e){
        e.preventDefault();
        if(confirm("Are you sure to delete this customer from system")){
            $.ajax({
                url:"<?=base_url().'Customers/delete/'.(isset($c['c_id'])?$c['c_id']:null)?>",
                async:false,
                success(response){
                    if(response==1){
                        window.location.replace("<?=base_url().'Customers/'?>");
                    }
                }
            });
        }
    })


</script>
</html>
