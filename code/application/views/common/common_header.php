
<?php
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<!-- Custom CSS -->

<link href="<?=base_url().'css/timeline.css'?>" rel="stylesheet">
<link rel="stylesheet" href="<?=base_url() . 'css/bootstrap.min.css'?>" >

<link href="<?=base_url().'css/jquery-ui.css'?>" rel="stylesheet">
<link href="<?=base_url().'css/sb-admin.css'?>" rel="stylesheet">

<link rel="stylesheet" href="<?=base_url() . 'css/font-awesome.min.css'?>">
<link href="<?=base_url().'css/parsley.css'?>" rel="stylesheet" type="text/css">
<script src="<?= base_url() . 'js/jquery.min.js' ?>"></script>
<script src="<?= base_url() . 'js/bootstrap.min.js' ?>"></script>
<script src="<?= base_url() . 'js/parsley.min.js' ?>"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    $(function () {

        var links = $('.sidebar-links > a');

        links.on('click', function () {

            links.removeClass('selected');
            $(this).addClass('selected');
        })
    });
</script>
