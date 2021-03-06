
<body>

<?php ?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?=base_url().'projects/list_all'?>">The Shipyard </a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-nav">
        <li  class="<?=$dashboard_class?>">
            <a href="<?=base_url().'dashboard'?>"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
        </li>
        <li  class="<?=$projects_class?>">
            <a href="<?=base_url().'projects/list_all'?>"><i class="fa fa-fw fa-bar-chart-o"></i> Projects</a>
        </li>
        <li class="<?=$message_class?>">
            <a href="<?=base_url().'chat'?>"><i class="fa fa-fw fa-comment"></i> Message&nbsp;<span id="msg" class="badge" style="background-color:red"></span></a>
        </li>
        <li class="<?=$customers_class?>">
            <a href="<?=base_url().'customers/list_all'?>"><i class="fa fa-fw fa-users"></i>Customers</a>
        </li>
        <li class="<?=$internal_user_class?>">
            <a href="<?=base_url().'internal_users/list_all'?>"><i class="fa fa-fw fa-users"></i>Internal Users</a>
        </li>
        <li class="<?=$analytics_class?>">
            <a href="<?=base_url().'historical'?>"><i class="fa fa-fw fa-line-chart"></i>Historical Statistics</a>
        </li>

        <!---
        <li>
            <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Issue Tracker</a>
        </li>
        <li>
            <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> File Repo</a>
        </li>
        <li>
            <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Tasks</a>
        </li>
        --->
    </ul>
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html" onclick="showNotification()">
                <i class="fa fa-fw fa-bell"></i>Notifications&nbsp;<span class="badge" style="background-color:red" id="n_number"></span>
            </a>

            <ul class="dropdown-menu notifications" role="menu" aria-labelledby="dLabel" id="n_message">


            </ul>

        </li>
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
</nav>

<script>
    var url = "<?=base_url()."chat/notifications/"?>";
    function notification_polling()
    {
        $.ajax({
            type: "GET",
            url: url,
            async: true,
            cache: false,
            timeout: 10000,
            success: function (data) {
                console.log("success")
                console.log(data)
                if(data==0){
                    $('#msg').hide();
                }else{
                    $('#msg').show();
                    document.getElementById("msg").innerHTML = data

                }

                setTimeout(this.notification_polling, 3000)
            }.bind(this),
            error: function(e)
            {
                console.log("errback");
                setTimeout(this.notification_polling, 3000)
            }.bind(this)
        })
    }

    notification_polling()

    function getNotificationNumber() {
        var n_number = new XMLHttpRequest();
        n_number.onreadystatechange = function() {
            if (n_number.readyState == 4 && n_number.status == 200) {
                document.getElementById("n_number").innerHTML = n_number.responseText;

            }
            resource_timer = setTimeout(showNotification, 10000);
        };
        n_number.open("GET", "<?=base_url().'Notifications/get_notification_number/'.$this->session->userdata('internal_uid')?>", true);
        n_number.send();
    }

    function showNotification(){
        var n_message = new XMLHttpRequest();
        n_message.onreadystatechange = function() {
            if (n_message.readyState == 4 && n_message.status == 200) {
                document.getElementById("n_message").innerHTML = n_message.responseText;
            }
        }
        n_message.open("GET", "<?=base_url().'Notifications/check_unread_notification/'.$this->session->userdata('internal_uid')?>", true);
        n_message.send();
    }

    function clear_all_notification(){
        var n_nil = new XMLHttpRequest();
        n_nil.onreadystatechange = function() {
            if (n_nil.readyState == 4 && n_nil.status == 200) {
                document.getElementById("n_message").innerHTML = n_nil.responseText;
                document.getElementById("n_number").innerHTML = null;
            }
        }
        n_nil.open("GET", "<?=base_url().'Notifications/clear_all_notification/'.$this->session->userdata('internal_uid')?>", true);
        n_nil.send();
    }

    $(document).ready(getNotificationNumber());
</script>