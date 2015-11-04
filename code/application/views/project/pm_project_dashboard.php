
<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
</head>
<body>
<?php
$class = [
    'projects_class'=>'active',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>

<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue selected" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i>Dashboard</a>
        <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i>Update & Milestone</a>
        <a class="link-blue " href="<?=base_url().'Issues/list_all/'.$project["bitbucket_repo_name"]?>"><i class="fa fa-wrench"></i>Issues</a>
        <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
    </div>
</aside>

<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            <?='#'.$project['project_id'].'. '.$project['project_title']?>&nbsp;
            <?php
            if($project['staging_link']):?>
                <a href="<?=$project['staging_link']?>" class="btn btn-info" target="_blank"><i class="fa fa-external-link"></i>&nbsp;Staging</a>
            <?php endif?>
            <?php
            if($project['production_link']):?>
                <a href="<?=$project['staging_link']?>" class="btn btn-info" target="_blank"><i class="fa fa-external-link"></i>&nbsp;Production</a>
            <?php endif?>
            <?php
            if($project['customer_preview_link']):?>
                <a href="<?=$project['customer_preview_link']?>" class="btn btn-info" target="_blank"><i class="fa fa-external-link" ></i>&nbsp;Customer View</a>
            <?php endif?>
        </h1>
    </div>

    <!-- /.row -->
    <div class="row">
        <div class="col-lg-offset-1 no-gutter">
            <?php
            foreach($phases as $phase){
                $phase_end_time = $phase['end_time'];
                if(!isset($phase_end_time)){
                    $phase_end_time = "now";
                }
                $img_tag='img/future.png';
                if(isset($phase['project_phase_id'])){
                    if(!$phase['phase_id']==0) {
                        $img_tag = 'img/done.png';

                        if ($phase['project_phase_id'] == $project['current_project_phase_id']) {
                            $img_tag = 'img/current.png';
                        }
                        echo'<div data-id="'.$phase['project_phase_id'].'" id="'.$phase['phase_name'].'" class="test col-sm-2" align="center" data-toggle="tooltip"
                data-placement="bottom" title="'.$phase['start_time'].' to '.$phase_end_time.'">'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
                    }
                }else{
                    echo' <div  class="test col-sm-2" align="center" >'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
                }
            }
            ?>

        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12">
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
            <div class="col-lg-offset-7 col-lg-4">
                <div class="panel info-panel">
                    <div class="panel-heading">Project Detail</div>
                    <div class="panel-body" style="font-size:15px" >
                        <table class="table table-condensed">
                            <tr>
                                <td><strong>No. of use case</strong></td>
                                <td><?=$project['no_of_use_cases']?></td>
                            </tr>
                            <tr>
                                <td><strong>Project Value</strong></td>
                                <td><?=$project['project_value']?></td>
                            </tr>
                            <tr>
                                <td><strong>Customer </strong></td>
                                <td> <a href="<?=base_url().'Customers/update_customer/'.$customer["c_id"]?>"><?=$customer['last_name'].' '.$customer['first_name']?></a> (Click to edit)</td>
                            </tr>
                            <tr>
                                <td><strong>Bitbucket Repo Name </strong></td>
                                <td><?=$project['bitbucket_repo_name']?></td>
                            </tr>
                            <tr>
                                <td><strong>File Repo Name </strong></td>
                                <td><?=$project['file_repo_name']?></td>
                            </tr>
                            <tr>
                                <td><strong>Status </strong></td>
                                <td><?php
                                    if($project['is_ongoing']==1){
                                        ?>
                                        Ongoing
                                        <?php
                                    }else{
                                        ?>
                                        Closed
                                        <?php
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tags </strong></td>
                                <td><?=$project['tags']?></td>
                            </tr>
                            <tr>
                                <td><strong>Description </strong></td>
                                <td><?=$project['project_description']?></td>
                            </tr>
                            <tr>
                                <td><strong>Remarks </strong></td>
                                <td><?=$project['remarks']?></td>
                            </tr>
                        </table>

                        <a href="<?=base_url().'Projects/edit/'.$project["project_id"]?>" class="btn pull-right btn-primary"><i class="fa fa-pencil-square-o"></i> &nbsp;Edit</a>

                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- /#page-content-wrapper -->

</div>
</body>
</html>