
<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <style>
        .glyphicon-refresh{cursor: pointer;cursor: hand;}
        .glyphicon-refresh-animate {
            -animation: spin .7s infinite linear;
            -webkit-animation: spin2 .7s infinite linear;
        }

        @-webkit-keyframes spin2 {
            from { -webkit-transform: rotate(0deg);}
            to { -webkit-transform: rotate(360deg);}
        }
        @keyframes spin {
            from { transform: scale(1) rotate(0deg);}
            to { transform: scale(1) rotate(360deg);}
        }
    </style>
</head>
<body>
<?php
$class = [
    'dashboard_class'=>'',
    'projects_class'=>'active',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>

<div class="col-md-offset-1 content">
    <!-- Page Content -->
    <div class="col-md-12">
        <h1 class="page-header">
            Projects
            <a href="<?=base_url().'projects/create_new_project'?>" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;New Project</a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  src="<?=base_url().'img/Legend.png'?>" alt ="legend">
        </h1>
        <?php if($this->session->userdata('message')):?>
            <div class="form-group">
                <div class="alert alert-info " role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <?=$this->session->userdata('message')?>
                </div>
            </div>
            <?php $this->session->unset_userdata('message') ?>
        <?php endif;?>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#">Ongoing Projects</a></li>
            <li><a href="<?=base_url().'Projects/list_past_projects'?>">Past Projects</a></li>
        </ul>
        <br>
        <div class="col-md-12">

            <?php if($this->session->userdata('message')):?>
                <div class="alert alert-info " role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <?=$this->session->userdata('message')?>
                </div>
                <?php $this->session->unset_userdata('message') ?>
            <?php endif;?>
        </div>
        <?php
        foreach($projects as $p){
            ?>
            <script>
                var urgency = $.ajax({
                    url: "<?=base_url().'issues/get_issue_urgency_score/'.$p["project_id"]?>",
                    //url: "http://localhost/tspms/code/dashboard/get_per_issue_data/1",
                    dataType: "float",
                    async: false
                }).responseText;
                var id = "project_header"+<?=$p["project_id"]?>;
            </script>
            <div class=" col-md-4">
                <div class="panel ongoing-panel  " >
                    <div id="project_header<?=$p['project_id']?>" class="panel-heading" style="text-align:center" ><strong>&nbsp;<?=$p['project_title']?></strong>&nbsp;&nbsp;<br><sub>[<?=$p['project_code']?>]</sub></div>
                    <div class="panel-body" style="font-size:15px " >
                        <table class="table table-condensed">
                            <tr>
                                <td><i class="fa fa-calendar-check-o"></i>&nbsp;<strong>Current Stage </strong></td>
                                <td><?php
                                    if($p['phase_name']){
                                        echo $p['phase_name'];
                                    }else{
                                        echo "not started";
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <td> <i class="fa fa-link"></i>&nbsp;<strong>Staging link </strong></td>
                                <td> <a href="<?=$p['staging_link']?>" target="_blank">Click here</a></td>
                            </tr>
                            <tr>
                                <td>  <i class="fa fa-clock-o"></i>&nbsp;<strong>Number Of Issues </strong></td>
                                <?php
                                if(empty($p['bitbucket_repo_name'])) $issue_count = "BB repo not set";
                                elseif($p['repo_name_valid']!=1) $issue_count = "Invalid BB repo";
                                else $issue_count = $p['issue_count'];

                                ?>
                                <td>
                                    <span class="issue-count-<?=$p["project_id"]?>"><?=$issue_count?></span>
                                    <span class="update-issue-count glyphicon glyphicon-refresh" style="margin-left:8px"></span></td>
                            </tr>
                            <tr>
                                <td>  <span class="glyphicon glyphicon-fire"></span>&nbsp;<strong>Urgency Score </strong></td>
                                <td> <script>
                                        document.write(urgency);
                                    </script></td>
                            </tr>
                        </table>


                        <a href="<?=base_url().'Projects/view_dashboard/'.$p["project_id"]?>" class="btn pull-right btn-info"><i class="fa fa-eye"></i> &nbsp;View</a>

                    </div>
                </div>
            </div>

            <script>
                console.log(id);
                if(urgency >20 ){
                    document.getElementById(id).style.backgroundColor = "rgba(200,50,50, 0.7)";
                }else if(urgency >5 && urgency<= 20){
                    document.getElementById(id).style.backgroundColor = "rgba(250,120,0,0.7)";
                }else{
                    document.getElementById(id).style.backgroundColor = "rgba(44,74,215,0.7)";
                }

            </script>
        <?php
        }
        ?>
    </div>
    <script>
        $(document).ready(function(){
            var refreshIssues = function (){
                var button = $('.update-issue-count');
                button.addClass("glyphicon-refresh-animate");
                $.ajax({
                    url:'<?=base_url()."Scheduled_tasks/fetch_issue_counts"?>',
                    success: function(response){
                        var data = jQuery.parseJSON(response);
                        for(var i=0;i<data.length;i++){
                            var id = data[i].id;
                            var count = data[i].count;
                            $(".issue-count-"+id).html(count);
                        }
                        button.removeClass("glyphicon-refresh-animate");
                    }
                });
            };
            if(Math.random()<0.05){refreshIssues()}
            $('.update-issue-count').click(function(){refreshIssues();});
        })
    </script>

    <!-- /#page-content-wrapper -->

</div>
</body>
</html>