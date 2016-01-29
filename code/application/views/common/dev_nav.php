
<body>

<?php ?>


<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="diy-nav">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=base_url().'projects/list_all'?>">TSPMS</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li  class="<?=$projects_class?>">
                    <a href="<?=base_url().'projects/dev_page'?>"><i class="fa fa-fw fa-bar-chart-o"></i> Projects</a>
                </li>
                <li class="<?=$message_class?>">
                    <a href="<?=base_url().'chat'?>"><i class="fa fa-fw fa-comment"></i> Message</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>&nbsp;<?=$this->session->userdata('internal_username')?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?=base_url('internal_authentication/change_password')?>"><i class="fa fa-fw fa-gear"></i>Change Password</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?=base_url('internal_authentication/logout')?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>