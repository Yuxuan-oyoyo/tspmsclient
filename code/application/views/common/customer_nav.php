<?php?>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <a class="navbar-brand" href="index.html">The Shipyard </a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-nav">
        <li  class="active">
            <a href="<?=base_url()?>projects/customer_overview/<?=$this->session->userdata('Customer_cid')?>"><i class="fa fa-fw fa-tasks"></i>My Projects</a>
        </li>
        <li>
            <a href="chat.html"><i class="fa fa-fw fa-comment"></i>Message</a>
        </li>
    </ul>
    <ul class="nav navbar-right top-nav">

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>&nbsp;<?=$this->session->userdata('Customer_username')?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="<?=base_url('customers/edit_profile')?>"><i class="fa fa-fw fa-user"></i> Profile</a>
                </li>
                <li>
                    <a href="<?=base_url('customer_authentication/change_password')?>"><i class="fa fa-fw fa-gear"></i> Reset Password</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="<?=base_url('customer_authentication/logout')?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>