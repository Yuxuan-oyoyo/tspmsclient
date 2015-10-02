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
        .user-name{margin:0;font-size:4rem}
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
                    <p class="well-body user-name">
                        Name: <span cus-attr="name">
                            <input name="name" value="" size="10" placeholder="name">
                        </span>
                    </p>
                    <p class="well-body">
                        Username: <span cus-attr="username">
                            <input name="username" value="" size="20" placeholder="username">
                        </span></br>
                        Password: <span cus-attr="password">
                            <input name="password" value="<?= DEFAULT_PASSWORD?>" size="20" placeholder="username">
                        </span></br>
                        Bitbucket username
                        <span cus-attr="bb_username">
                            <input name="bb_username" value="" size="20" placeholder="Bitbucket username">
                        </span></br>
                        Type
                        <span cus-attr="type">
                            <input type="radio" name="type" value="0" size="20" checked>Dev
                            <input type="radio" name="type" value="1" size="20">PM
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
    <a href="<?= base_url() . 'Internal_users' ?>">back..</a>
</div>
</body>
<script>

    $("#info-container").on("click","#submit",function(e){
        e.preventDefault();
        $.ajax({
            url:"<?=base_url().'Internal_users/insert'?>",
            data:$(this).closest("form").serialize(),
            async:false,
            success(response){
               if(response!=0){window.location.replace("<?=base_url().'Internal_users'?>");}
            }
        });
    })


</script>
</html>
