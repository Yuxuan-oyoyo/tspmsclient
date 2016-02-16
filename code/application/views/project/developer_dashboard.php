
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
    'projects_class'=>'active',
    'message_class'=>'',
];
$this->load->view('common/dev_nav', $class);
?>

<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            Projects
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
        <br>
        <div class="col-lg-12">

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
            <div class=" col-lg-4">
                <div class="panel ongoing-panel">
                    <div class="panel-heading" style="text-align:center" ><strong><?=$p['project_title']?></strong></div>
                    <div class="panel-body" style="font-size:15px" >
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
                                <td> <i class="fa fa-link"></i>&nbsp;<strong>Production link</strong></td>
                                <td> <a href="<?=$p['production_link']?>" target="_blank">Click here</a></td>
                            </tr>
                            <tr>
                                <td>  <i class="fa fa-clock-o"></i>&nbsp;<strong>Number Of Issues </strong></td>
                                <td> <span class="issue-count-<?=$p["project_id"]?>"><?=isset($p['issue_count'])?$p['issue_count']:"BB repo not set yet"?></span><span class="update-issue-count glyphicon glyphicon-refresh" style="margin-left:8px"></span></td>
                            </tr>
                        </table>

                        <a href="<?=base_url().'Issues/list_all/'.$p["bitbucket_repo_name"]?>" class="btn pull-right btn-info"><i class="fa fa-eye"></i> &nbsp;View</a>

                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <script>
        $(document).ready(function(){
            function refreshIssues(){
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
                return false;
            }
            if(Math.random()<0.05){refreshIssues();}
            $('.update-issue-count').on('click',function(){refreshIssues();});
        })
    </script>

    <!-- /#page-content-wrapper -->

</div>
</body>
</html>