

<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="diy-nav">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=base_url()?>projects/customer_overview/<?=$this->session->userdata('Customer_cid')?>">The Shipyard </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li  class="<?=$projects_class?>">
                    <a href="<?=base_url()?>projects/customer_overview/<?=$this->session->userdata('Customer_cid')?>"><i class="fa fa-fw fa-tasks"></i>My Projects</a>
                </li>
                <li class="<?=$message_class?>">
                    <a href="<?=base_url().'chat'?>"><i class="fa fa-fw fa-comment"></i>Message&nbsp;<span id="msg" class="badge" style="background-color:red"></span></a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>&nbsp;<?=$this->session->userdata('Customer_username')?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?=base_url('customers/edit_profile')?>"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="<?=base_url('customer_authentication/change_password')?>"><i class="fa fa-fw fa-gear"></i>Change Password</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?=base_url('customer_authentication/logout')?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
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
                document.getElementById("msg").innerHTML = data
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
    /* commented out on 25th Jan by ln
     function showNotification() {
     var n_message = new XMLHttpRequest();
     var n_umber = new XMLHttpRequest();
     n_message.onreadystatechange = function() {
     if (n_message.readyState == 4 && n_message.status == 200) {
     document.getElementById("n_message").innerHTML = n_message.responseText;

     }
     resource_timer = setTimeout(showNotification, 20000);
     };
     n_umber.onreadystatechange = function() {
     if (n_umber.readyState == 4 && n_umber.status == 200) {
     document.getElementById("n_number").innerHTML = n_umber.responseText;

     }
     resource_timer = setTimeout(showNotification, 20000);
     };
     n_message.open("GET", "<?=base_url().'Notifications/check_unread_notification/'.$this->session->userdata('internal_uid')?>", true);
     n_umber.open("GET", "<?=base_url().'Notifications/get_notification_number/'.$this->session->userdata('internal_uid')?>", true);
     n_message.send();
     n_umber.send();
     }
     */

    //$(document).ready(showNotification());
</script>