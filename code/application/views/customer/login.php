<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <link href="<?=base_url()?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url()?>css/login.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="<?=base_url()?>js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.forgot-pass').click(function(event) {
                $(".pr-wrap").toggleClass("show-pass-reset");
            });

            $('.pass-reset-submit').click(function(event) {
                $(".pr-wrap").removeClass("show-pass-reset");
            });
        });
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="pr-wrap">
                <div class="pass-reset">
                    <label>
                        Enter the email you signed up with</label>
                    <form role="form" action= "<?=base_url()."Customer_authentication/reset_password"?>" method="post">
                        <input type="email" placeholder="Email" id="email" name="email" />
                        <input type="submit" value="Submit" id="submit" name="submit" class="pass-reset-submit btn btn-primary btn-sm" />
                    </form>
                </div>
            </div>
            <div class="wrap">
                <div class="row">
                    <?php if($this->session->userdata('message')):?>
                        <div class="alert alert-info col-md-offset-4 col-md-4" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                            <?=$this->session->userdata('message')?>
                        </div>
                        <?php $this->session->unset_userdata('message') ?>
                    <?php endif;?>
                </div>
                <p class="form-title">
                    Sign In
                </p>


                <form class="login" method="post">
                    <input type="text" placeholder="Username" id="username" name="username" required>
                    <input type="password" placeholder="Password" id="password" name="password" required>
                    <input type="submit" value="Sign In" class="btn btn-primary btn-sm">
                    <div class="remember-forgot">
                        <div class="row">
                            <div class="col-md-6 forgot-pass-content">
                                <a href="#" class="forgot-pass">Forgot Password</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class=" col-md-12">
<img src="<?=base_url()?>img/Shipyardlogo1.png" style="margin-left:15px;margin-top:15px" width="357" height="88" alt=""/>
</div>

</body>
</html>