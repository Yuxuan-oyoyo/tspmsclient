

<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/27/2015
 * Time: 5:37 PM
 */
    //echo var_dump($issue_details);
$i = $issue_details;
$repo_slug = $repo_slug;
$ci =&get_instance();
$ci->load->model("Internal_user_model");
$users = $ci->Internal_user_model->retrieveAll(false);

function _ago($tm,$rcs = 0) {
    $cur_tm = time(); $dif = $cur_tm-$tm;
    $pds = array('second','minute','hour','day','week','month','year','decade');
    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

    $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
    if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
    return $x;
}
?>
<!DOCTYPE html>
<html>
<?php
//$repo_slug = $repo_slug;
//$filter="status[]=new&status[]=open";
?>
<head lang="en">
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?= base_url() . 'css/sidebar-left.css' ?>">
    <link rel="stylesheet" href="<?= base_url() . 'css/issues.css' ?>">

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
        <a class="link-blue" href="projectDashboard.html"><i class="fa fa-tasks"></i>Dashboard</a>
        <a class="link-blue " href="projectUpdate.html"><i class="fa fa-flag"></i>Update & Milestone</a>
        <a class="link-blue selected" href="<?= base_url() . 'Issues/list_all/'.$repo_slug ?>"><i class="fa fa-wrench"></i>Issues</a>
        <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
    </div>

</aside>
<script>
    $(document).ready(function(){
        $(".cmpl").text("*").css("color","red");
    });
</script>
<div class="col-sm-offset-1 content" style="margin-top: 100px">
    <form class="form-horizontal" action="<?=base_url()."Issues/process_edit/".$repo_slug."/".$i["local_id"]?>">
        <h2 style="max-width: 40%">
            <span style="color: #777777;padding-right: 10px">#<?=$i["local_id"]?></span>
            <?=$i["title"]?>
            <div style="height: 100%;display: inline;padding-top:0;position:relative">
                <div class="aui-lozenge" style="background-color: #fcf8e3;position:absolute;top:15px;left: 15px" title="Filter by status: <?=$i["status"]?>">
                    <?=$i["status"]?>
                </div>
            </div>
        </h2>
        <div style="display: table">
        <div class="form-part">
            <div class="form-label">Title <span class="cmpl"></span></div>
            <div class="form-input">
                <input name="title" class="form-control" value="<?=$i["title"]?>">
            </div>
        </div>
        <div class="form-part">
            <div class="form-label">Description</div>
            <div class="form-input">
                <textarea name="content" class="form-control" ><?=$i["content"]?></textarea>
            </div>
        </div>
        <div class="form-part">
            <div class="form-label">Reporter</div>
            <div class="form-input">
                <?=$i["reported_by"]["display_name"]?>
            </div>
        </div>
        <div class="form-part">
            <div class="form-label">Assignee</div>
            <div class="form-input">
                <select name="responsible" class="form-control">
                    <?php foreach($users as $u):?>
                        <?php if(similar_text($i["responsible"]["display_name"],$u["bb_username"])>1):?>
                            <option selected value="<?=$u["bb_username"]?>"><?=$u["bb_username"]?></option>
                        <?php else:?>
                            <option value="<?=$u["bb_username"]?>"><?=$u["bb_username"]?></option>
                        <?php endif?>
                    <?php endforeach?>
                </select>
            </div>
        </div>
            <div class="form-part">
                <div class="form-label">Status<span class="cmpl"></span></div>
                <div class="form-input">
                    <?php $status=[
                        "new","to develop","to test","to deploy"
                    ]?>
                    <select name="status" class="form-control">
                        <?php foreach($status as $k):?>
                            <option value="<?=$k?>"><?=$k?></option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>
        <div class="form-part">
            <div class="form-label">Kind</div>
            <div class="form-input">
                <?php $kinds = ["bug","enhancement","proposal","task"]?>
                <select name="kind" class="form-control">
                    <?php foreach($kinds as $k):?>
                        <?php if($i["metadata"]["kind"]==$k):?>
                            <option selected value="<?=$k?>"><?=$k?></option>
                        <?php else:?>
                            <option value="<?=$k?>"><?=$k?></option>
                        <?php endif?>
                    <?php endforeach?>
                </select>
            </div>
        </div>
        <div class="form-part">
            <div class="form-label">Priority</div>
            <div class="form-input">
                <?php $priorities = ["trivial","minor","major","critical","blocker"]?>
                <select name="priority" class="form-control">
                    <?php foreach($priorities as $k):?>
                        <?php if($i["priority"]==$k):?>
                            <option selected value="<?=$k?>"><?=$k?></option>
                        <?php else:?>
                            <option value="<?=$k?>"><?=$k?></option>
                        <?php endif?>
                    <?php endforeach?>
                </select>
            </div>
        </div>
        <div class="form-part">
            <div style="display: table-cell"></div>
            <div class="form-input" style="vertical-align: middle">
                <button class="btn btn-primary" style="margin-right: 5pt">Update issue</button>
                <a href="<?=base_url()."issues/detail/".$repo_slug."?local_id=".$i["local_id"]?>">Cancel</a>
            </div>
        </div>


        </div>
    </form>
</div>
</body>
</html>