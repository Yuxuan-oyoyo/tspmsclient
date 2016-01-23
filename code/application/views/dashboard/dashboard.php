<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <?php $this->load->view('common/common_header');?>

    </head>
    <body>
<?php
$class = [
    'dashboard_class'=>'active',
    'projects_class'=>'',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>