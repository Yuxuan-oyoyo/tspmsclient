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
                    <input type="email" placeholder="Email" />
                    <input type="submit" value="Submit" class="pass-reset-submit btn btn-primary btn-sm" />
                </div>
            </div>
            <div class="wrap">
                    <?php if($this->session->userdata('message')):?>

                        <div class="alert alert-info" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                            <?=$this->session->userdata('message')?>
                        </div>
                        <?php $this->session->unset_userdata('message') ?>
                    <?php endif;?>
                <?php if($this->session->userdata('message')):?>

                    <div class="alert alert-info" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <?=$this->session->userdata('message')?>
                    </div>
                    <?php $this->session->unset_userdata('message') ?>
                <?php endif;?>
                <p class="form-title">
                    Sign In
                </p>

                <form class="login" method="post">
                    <input type="text" placeholder="Username" id="username" name="username" />
                    <input type="password" placeholder="Password" id="password" name="password" />
                    <input type="submit" value="Sign In" class="btn btn-primary btn-sm" />
                    <div class="remember-forgot">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" />
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 forgot-pass-content">
                                <a href="javascription:void(0)" class="forgot-pass">Forgot Password</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>