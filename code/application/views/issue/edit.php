

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
$ci =&get_instance();
$ci->load->model("Project_model");
$project = $ci->Project_model->retrieve_by_repo_slug($repo_slug);

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
if($this->session->userdata('internal_type')=='Developer') {
    $this->load->view('common/dev_nav', $class);
}else {
    $this->load->view('common/pm_nav', $class);
    ?>
    <aside class="sidebar-left">
        <div class="sidebar-links">
            <a class="link-blue" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i>Project Overview</a>
            <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i>Update & Milestone</a>
            <a class="link-blue selected" href="<?=base_url()?>Issues/list_all/<?=$repo_slug?>"><i class="fa fa-wrench"></i>Issues</a>
            <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
        </div>

    </aside>
    <?php
}
?>

<script>
    $(document).ready(function(){
        $(".cmpl").text("*").css("color","red");
    });
</script>
<div class="col-sm-offset-1 content" style="margin-top: 75px;margin-left:15%">
    <div style="display:inline">
        <h2 style="max-width: 50%">
            <span style="color: #777777;padding-right: 10px">#<?=$i["local_id"]?></span>
            <?=$i["title"]?>
        </h2>
    <form class="form-horizontal col-sm-6" data-parsley-validate action="<?=base_url()."Issues/process_edit/".$repo_slug."/".$i["local_id"]?>">

        <div style="display: table;width:95%">
        <div class="form-part">
            <div class="form-label">Title <span class="cmpl"></span></div>
            <div class="form-input">
                <input name="title"  required class="form-control" value="<?=$i["title"]?>">
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
                <div class="form-label">Use case</div>
                <div class="form-input">
                    <?php //get all use cases!!!?>
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
                <div class="form-label">Milestone</div>
                <div class="form-input">
                <?php
                    $ci->load->library('BB_milestones');
                    $ci->load->model('Milestone_model');
                    $milestones = $ci->bb_milestones->getAllMilestones($repo_slug);
                ?>
                    <select name="milestone" class="form-control">
                        <option selected value="0">NIL</option>
                        <?php foreach($milestones as $m):?>
                            <?php if($i["metadata"]["milestone"]==$m["id"]):?>
                                <option selected value="<?=$m["id"]?>"><?=print_r($ci->Milestone_model->retrieve_by_id($m["name"]))?></option>
                            <?php else:?>
                                <option value="<?=$m["id"]?>"><?=print_r($ci->Milestone_model->retrieve_by_id($m["name"]))?></option>
                            <?php endif?>
                        <?php endforeach?>
                    </select>
                </div>
            </div>
            <div class="form-part">
                <div class="form-label">Comment</div>
                <div class="form-input">
                    <textarea class="form-control" id="new-comment" name="comment" style="width: 100%;height: 96px;overflow: hidden;padding: 7px;"
                              placeholder="What do you want to say?"></textarea>
                    <div class="preview-container"><!-- loaded via ajax --></div>
                </div>
            </div>
        <div class="form-part">
            <div style="display: table-cell"></div>
            <div class="form-input" style="vertical-align: middle">
                <button class="btn btn-primary" style="margin-right: 5pt">Update issue</button>
                <a href="javascript:history.back(-1)">Cancel</a>
            </div>
        </div>

        </div>
    </form>
        <div class="issue-tool-bar" style="padding:7pt">
            <script>
                $("div").on("mousedown",".update-btn",function(e){
                    e.preventDefault();
                    var param = $(this).attr("param");
                    var value = $(this).attr("value");
                    window.location.replace("<?=base_url()."Issues/update/".$repo_slug."/".$i["local_id"]."?"?>"+"param="+param+"&value="+value);
                });
            </script>
            <div class="btn-group">
                <?php if($i["status"]=="resolved"):?>
                    <a href="#" class="btn btn-primary update-btn" param="status" value="new">Open</a>
                <?php else:?>
                    <a href="#" class="btn btn-primary update-btn" param="status" value="resolved">Resolve</a>


                    <a href="<?=base_url()."/Issues/update/".$repo_slug."/".$i["local_id"]."?status=resolved"?>"
                       class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" class="update-btn" param="status" value="new">new</a></li>
                        <li><a href="#" class="update-btn" param="status" value="to develop">to develop</a></li>
                        <li><a href="#" class="update-btn" param="status" value="to test">to test</a></li>
                        <li><a href="#" class="update-btn" param="status" value="to deploy">to deploy</a></li>
                        <li><a href="#" class="update-btn" param="status" value="invalid">invalid</a></li>
                        <li><a href="#" class="update-btn" param="status" value="wontfix">wontfix</a></li>
                        <li><a href="#" class="update-btn" param="status" value="resolved">resolved</a></li>
                    </ul>
                <?php endif?>
            </div>
        </div>
    </div>
</div>

</body>
</html>