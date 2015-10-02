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

            <div id="info-container">
                <div class="col-xs-10 col-xs-offset-1">
                    <form>
                    <p class="well-body cus-name">
                        <span cus-attr="first_name">
                            <input name="first_name" value="" size="10" placeholder="first name">
                        </span>
                        <span cus-attr="last_name">
                            <input name="last_name" value="" size="10" placeholder="last name">
                        </span>
                    </p>
                    <p class="well-body">
                        Company: <span cus-attr="company_name">
                            <input name="company_name" value="" size="20" placeholder="company name">
                        </span></br>
                        <i class="glyphicon glyphicon-envelope"></i>
                        <span cus-attr="email">
                            <input name="email" value="" size="20" placeholder="email">
                        </span></br>
                        <i class="glyphicon glyphicon-phone"></i>
                        <span cus-attr="hp_number">
                            <input name="hp_number" value="" size="20" placeholder="hp number">
                        </span></br>
                        <i class="glyphicon glyphicon-phone-alt"></i>
                        <span cus-attr="other_number">
                            <input name="other_number" value="" size="20" placeholder="other number">
                        </span></br>
                        <span cus-attr="is_active">
                            <input type="radio" name="is_active" value="1" checked>Active
                            <input type="radio" name="is_active" value="0">Inactive
                        </span>

                    </p>
                        <a href="#" id="submit">submit</a>
                    </form>
                </div>
            </div>


    </div>
    <a href="<?= base_url() . 'Customers' ?>">back..</a>
</div>
</body>
<script>

    $("#info-container").on("click","#submit",function(e){
        e.preventDefault();
        $.ajax({
            url:"<?=base_url().'Customers/insert'?>",
            data:$(this).closest("form").serialize(),
            async:false,
            success(response){
            //if(response!=0){location.reload();}
        }
    });
    })


</script>
</html>
