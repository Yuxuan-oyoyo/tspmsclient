<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/26/2015
 * Time: 9:13 AM
 */
    //echo var_dump($issue_details);
$i = $issue_details;
$repo_slug = $repo_slug;
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
$ci =&get_instance();
$ci->load->model("Project_model");
$project = $ci->Project_model->retrieve_by_repo_slug($repo_slug);
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
    $this->load->view('common/side_bar');
}
?>

<div class="col-sm-offset-1 content">
    <div class="row">
        <div class="col-sm-6">
            <h2>
                <span style="color: #777777;padding-right: 10px">#<?=$i["local_id"]?></span>
                <?=$i["title"]?>
                <div style="height: 100%;display: inline;padding-top:0;position:relative">
                    <div class="aui-lozenge" style="background-color: #fcf8e3;position:absolute;top:15px;left: 15px" title="Filter by status: <?=$i["status"]?>">
                        <?=$i["status"]?>
                    </div>
                </div>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6" >
            <div id="issue-main-content">
                <div class="issue-author">
                    <a href="#" title="View <?=$i["reported_by"]["display_name"]?>'s profile">
                        <div class="avatar avatar-medium">
                            <div class="avatar-inner">
                                <img src="https://bitbucket.org/account/<?=$i["reported_by"]["username"]?>/avatar/32/?ts=0"class=""  alt="">
                            </div>
                        </div>
                        <?=$i["reported_by"]["display_name"]?>
                    </a>
                    <span>
                        Created an Issue <?=_ago(strtotime($i["created_on"]))?> ago
                    </span>

                </div>
                <div class="issue-description">
                    <span>
                        <?=htmlspecialchars($i["content"])?>
                    </span>
                </div>
            </div>
            <div>
                <h3>Comments <span style="color: #777777;padding-left: 10px">#<?=isset($i["comment_count"])?$i["comment_count"]:0?></span></h3>
                <div>
                    <ol id="issues-comments" class="comments-list commentable">
                        <li class="new-comment">
                            <div class="user">
                                <div class="avatar avatar-medium" style="position: absolute;left: 0;">
                                    <div class="avatar-inner">
                                        <img src="https://bitbucket.org/account/luning1994/avatar/32/?ts=0" alt="">
                                    </div>
                                </div>
                            </div>
                            <form class="aui top-label editor" action="#">
                                <input type="hidden" class="parent_id">
                                <input type="hidden" class="comment_id">
                                <div class="field-group">
                                    <textarea id="id_new_comment" class="bb-mention-input" placeholder="What do you want to say?"></textarea>
                                    <div class="preview-container wiki-content"><!-- loaded via ajax --></div>
                                    <div class="error"></div>
                                </div>
                                <div class="buttons">
                                    <button class="btn btn-primary" type="submit" resolved="">Comment</button>
                                    <a href="#" class="cancel">Cancel</a>
                                </div>
                                <div class="mask"></div>
                            </form>
                        </li></ol>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="issue-tool-bar">
                <script>
                    $("div").on("click",".update-btn",function(e){
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

                    <?php endif?>
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
                </div>

                <a class="btn btn-default" style="margin-left:30px" href="<?= base_url()."Issues/edit/".$repo_slug."/".$i["local_id"]?>">Edit</a>
            </div>
            <div class="well" style="background-color: white;width: 250px;margin-top:10px">
                <table>
                    <tr>
                        <td style="text-align: right;padding-right: 20px">Assignee</td><td><?=isset($i["responsible"])?$i["responsible"]["display_name"]:"-"?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;padding-right: 20px">Priority</td><td><?=$i["priority"]?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;padding-right: 20px">Type</td><td><?=$i["metadata"]["kind"]?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;padding-right: 20px">Status</td><td><?=$i["status"]?></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>
</body>
</html>