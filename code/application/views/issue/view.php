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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.3.0/showdown.min.js"></script>
    <script src="<?= base_url() . 'js/profile_factory.js' ?>"></script>
    <script src="<?= base_url() . 'js/markdown/markdown.js' ?>"></script>
    <link rel="stylesheet" href="<?= base_url() . 'css/sidebar-left.css' ?>">
    <link rel="stylesheet" href="<?= base_url() . 'css/issues.css' ?>">


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
$status_color=[
    "new"=>"#34495e","open"=>"#AD5E19 ","resolved"=>"#1D7D46",
    "on hold"=>"#015B96","invalid"=>"#B31A0C","duplicate"=>"#613873",
    "wontfix"=>"#95a5a6","closed"=>"#7f8c8d"
];
if($this->session->userdata('internal_type')=='Developer') {
    $this->load->view('common/dev_nav', $class);
}else {
    $this->load->view('common/pm_nav', $class);
    ?>
    <aside class="sidebar-left">
        <div class="sidebar-links">
            <a class="link-blue" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i><span class="nav-text">Project Overview</span></a>
            <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i><span class="nav-text">Update & Milestone</span></a>
            <a class="link-blue selected" href="<?=base_url()?>Issues/list_all/<?=$repo_slug?>"><i class="fa fa-wrench"></i><span class="nav-text">Issues</span></a>
            <a class="link-blue" href="<?=base_url().'Usecases/list_all/'.$project["project_id"]?>"><i class="fa fa-list"></i><span class="nav-text">Use Case List</span></a>
            <a class="link-blue" href="<?=base_url().'Projects/view_report/'.$project["project_id"]?>"><i class="fa fa-bar-chart"></i><span class="nav-text">Analytics</span></a>
            <a class="link-blue " href="<?=base_url().'upload/upload/'.$project['project_id']?>"><i class="fa fa-folder"></i><span class="nav-text">File Repository</span></a>
        </div>

    </aside>
    <?php
}
?>


<div class="col-sm-offset-1 content" style="margin-left:10%">
    <div class="row">
        <div class="col-sm-6">
            <h2>
                <span style="color: #777777;padding-right: 10px">#<?=$i["local_id"]?></span>
                <?=$i["title"]?>
                <div style="height: 100%;display: inline;position:relative">
                    <div class="aui-lozenge" style="color: <?=$status_color[$i["status"]]?>;
                        border-color: <?=$status_color[$i["status"]]?>;vertical-align:middle;
                        font-size: 14px;padding:2px 4px;">
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
                        Created an Issue <?=_ago(strtotime($i["utc_created_on"]))?> ago
                    </span>
                </div>
                <div class="issue-description">
                    <?php if($i["content"]):?>
                        <span style="font: 16px serif" class="markdown-content"></spam><?=htmlspecialchars($i["content"])?></span>
                    <?php else:?>
                        <em>No description provided.</em>
                    <?php endif?>
                </div>
            </div>
            <div>
                <?php
                    $comments = $comments;
                    $c_counts = isset($comments)? sizeof($comments):0;

                ?>
                <h3>Comments <span style="color: #777777;padding-left: 10px">#<?=$c_counts?></span></h3>
                <div>
                    <ol id="issues-comments" class="comments-list commentable">
                        <li class="new-comment">
                            <div class="user">
                                <div class="avatar avatar-medium" style="position: absolute;left: 0;">
                                    <div class="avatar-inner">
                                        <?php
                                         $ci->load->model("Internal_user_model");
                                         $ci->load->library('session');
                                         $user_id = $ci->session->userdata('internal_uid');
                                         $user = $ci->Internal_user_model->retrieve($user_id);

                                        ?>
                                        <?php if(isset($user["bb_username"])):?>
                                            <img src="https://bitbucket.org/account/<?=$user["bb_username"]?>/avatar/32/?ts=0" alt="">
                                        <?php elseif(isset($user["name"])):?>

                                            <canvas id="avatar-initial" width="32" height="32" style="border-radius: 3px;"></canvas>
                                            <script>
                                                generateProfile("<?=ucwords($user["name"][0])?>","avatar-initial", 1);
                                            </script>
                                        <?php endif?>
                                    </div>
                                </div>
                            </div>
                            <form class="aui top-label editor" method="post" action="<?=base_url()."Issues/input_comment/".$repo_slug."/".$i["local_id"]?>">
                                <input hidden name="comment_id" value="new">
                                <div class="field-group">
                                    <textarea id="new-comment" name="content" class="bb-mention-input markdown-content input-comment-content" placeholder="What do you want to say?"></textarea>
                                    <div class="preview-container"><!-- loaded via ajax --></div>
                                    <div class="error"></div>
                                </div>
                                <div class="buttons" id="new-comment-btn" style="display:none">
                                    <button class="btn btn-primary btn-sm disabled submit-button" type="submit" disabled>Comment</button>
                                    <a href="#" class="cancel" id="cancel-input" >Cancel</a>
                                </div>
                                <div class="mask"></div>
                            </form>
                        </li>
                        <?php if(isset($comments)):?>
                        <?php foreach($comments as $index=>$c):?>
                        <li class=" issue-comment comment" comment-id="<?=$c["comment_id"]?>">
                            <div class="issue-author">
                                <a href="#" title="View <?=$c["author_info"]["display_name"]?>'s profile">
                                    <div class="avatar avatar-medium">
                                        <div class="avatar-inner">
                                            <img src="https://bitbucket.org/account/<?=$c["author_info"]["username"]?>/avatar/32/?ts=0"class=""  alt="">
                                        </div>
                                    </div>
                                    <?=$c["author_info"]["display_name"]?>
                                </a>
                                <span style="color:grey">
                                    commented <?=_ago(strtotime($c["utc_created_on"]))?> ago
                                    <?=$c["utc_created_on"]!=$c["utc_updated_on"]? ", updated "._ago(strtotime($c["utc_updated_on"])). " ago":" "?>
                                </span>
                                <?php if($c["author_info"]["username"]==$i["reported_by"]["username"]):?>
                                    <div style="height: 100%;display: inline;position:relative">
                                        <div class="aui-lozenge" style="color:#4a6785;border-color:#a5b3c2;vertical-align:middle">reporter</div>
                                    </div>
                                <?php endif?>
                            </div>

                            <div class="comment-body">
                            <div class="comment-content markdown-content"><?=$c["content"]?></div>
                            <?php if(true):?>
                                <ul class="comment-actions">
                                    <li style="float:left"><a href="#edit" class="edit-comment-link">Edit</a></li>
                                    <!--li style="float:left">
                                        <form style="display:inline" method="post" action="<?=base_url()."Issues/delete_comment/".$repo_slug."/".$i["local_id"]?>">
                                            <a href="#comment-delete" class="comment-delete">Delete</a>
                                            <input hidden name="comment_id" value="<?=$c["comment_id"]?>">
                                        </form>
                                    </li-->
                                </ul>
                            <?php endif?>
                            </div>
                        </li>
                        <?php endforeach?>
                        <?php endif?>
                    </ol>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                    var $comment_body_content = null;
                    var $comment_id = null;

                    function cancelNewInput() {
                        $('#new-comment').text('');
                        $('#new-comment-btn').hide();
                        $('textarea').css("height","34px");
                        return false;
                    }
                    var clean = function() {
                        cancelNewInput();
                        if ($comment_id != null && $comment_body_content != null) {
                            var $comment = $('.issue-comment[comment-id="' + $comment_id + '"]');
                            $comment.find('.comment-body').html($comment_body_content);
                            $comment_body_content = null;
                            $comment_id = null;
                        }
                    };
                    $('#issues-comments').on("mousedown","#cancel-input",function(){
                        clean();return false;
                    }).on('mousedown', '.edit-comment-link', function (e) {
                        e.preventDefault();
                        clean();
                        $comment_id = $(this).closest('.issue-comment').attr("comment-id");
                        var $comment_body = $(this).closest('.comment-body');
                        var $comment_content_original = $comment_body.find('.comment-content').text();
                        $comment_body_content = $comment_body.html();
                        $comment_body.html($('.input-form-proto').clone());
                        $comment_body.find('.input-form-proto').removeAttr("id");
                        $comment_body.find(".input-comment-content").text($comment_content_original);
                        $comment_body.find(".input-comment-id").val($comment_id);
                    }).on('click', '.input-cancel', function (e) {
                        e.preventDefault();
                        clean();
                    }).on('keyup', '.input-comment-content',function(e) {
                        var val = $.trim( this.value );
                        if(val.length == 0) {
                            $(this).closest("form").find(".submit-button").addClass("disabled");
                            $(this).closest("form").find(".submit-button").attr("disabled","disabled");
                        }else{
                            $(this).closest("form").find(".submit-button").removeClass("disabled");
                            $(this).closest("form").find(".submit-button").removeAttr("disabled");
                        }
                    }).on('focus', 'textarea', function () {
                        console.log("in focus");
                        $(this).css("height","96px");
                        if($(this).attr("id")=="new-comment"){
                            $('#new-comment-btn').css("display","block");
                        }
                    }).on('click', '.comment-delete', function () {
                        $(this).closest('form').submit();
                        return false;
                    });


                });
            </script>
        </div>
        <div class="col-sm-6">
            <div class="issue-tool-bar">
                <script>
                    $("div").on("click",".update-btn",function(e){
                        e.preventDefault();
                        var param = $(this).attr("param");
                        var value = $(this).attr("value");
                        if(param=="workflow"){
                            window.location.replace("<?=base_url()."Issues/update/".$repo_slug."/".$i["local_id"]."?"?>"
                                +"param="+param+"&value="+value+"&title=<?=$i["title"]?>");
                        }else{
                            window.location.replace("<?=base_url()."Issues/update/".$repo_slug."/".$i["local_id"]."?"?>"
                                +"param="+param+"&value="+value);
                        }

                    });
                </script>
                <div class="btn-group">
                    <?php if($i["status"]=="resolved"):?>
                        <a href="#" class="btn btn-primary update-btn" param="status" value="new">Open</a>
                    <?php else:?>
                        <a href="#" class="btn btn-primary update-btn" param="status" value="resolved">Resolve</a>
                    <?php endif?>
                    <a href="#"
                       class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        $server_status = [ "new","open","resolved","on hold","invalid","duplicate","wontfix","closed"];
                        ?>
                        <?php foreach ($server_status as $s):?>
                            <?php if($s!=$i["status"]):?>
                                <li><a href="#" class="update-btn" param="status" value="<?=$s?>"><?=ucwords($s)?></a></li>
                            <?php endif;?>
                        <?php endforeach?>
                    </ul>


                </div>
                <?php $workflow_next= [
                    "to develop"=>"to test","to test"=>"ready for deployment",
                    "ready for deployment"=>"to deploy","to deploy"=>"default workflow"
                ];?>
                <?php if($i["status"]!="resolved"):?>
                    <?php $next_workflow = isset($workflow_next[$i["workflow"]])?$workflow_next[$i["workflow"]]: "to develop";?>
                    <div class="btn-group">
                        <a href="#" class="btn btn-default update-btn" param="workflow" style="margin-left:7px"
                           value="<?=$next_workflow?>"><?=ucwords($next_workflow)?></a>
                        <a href="#"class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" class="update-btn" param="workflow" value="default workflow">-Default Workflow-</a></li>
                            <?php $workflows = ["to develop","to test","ready for deployment","to deploy"];?>
                            <?php foreach ($workflows as $s):?>
                                <?php if($s!=$i["workflow"]):?>
                                    <li><a href="#" class="update-btn" param="workflow" value="<?=$s?>"><?=ucwords($s)?></a></li>
                                <?php endif;?>
                            <?php endforeach?>
                        </ul>

                    </div>
                <?php endif;?>
                <a class="btn btn-default" style="margin-left:7px" href="<?= base_url()."Issues/edit/".$repo_slug."/".$i["local_id"]?>">Edit</a>
            </div>
            <div class="well" style="background-color: white;width: 250px;margin-top:25px">
                <table>
                    <?php
                    $ci->load->model('Use_case_model');
                    $ci->load->model('Milestone_model');
                    $usecase = null;
                    $milestone = null;
                    if(isset($i["usecase"])){
                        $usecase = $ci->Use_case_model->retrieve_by_id($i["usecase"]);
                    }
                    if(isset($i["metadata"]["milestone"])){
                        $milestone = $ci->Milestone_model->retrieve_milestone_by_id($i["metadata"]["milestone"]);
                    }
                    $workflow_color= [
                        "to develop"=>["#F30000","#FF5154"],"to test"=>["#FFFF45","#E8E05C"],
                        "ready for deployment"=>["#008400","#6ABB6B"],"to deploy"=>["#32A299","#6CBBB6"],
                    ];
                    //get workflow icon
                    if(!empty($i["workflow"])){
                        if(isset($workflow_color[$i["workflow"]])){
                            $workflow_icon = '<div class="workflow-dot attribute-icon"style="background:'
                                .$workflow_color[$i["workflow"]][0].';border:'.$workflow_color[$i["workflow"]][1].';margin:0 4px 0 2px"></div>';
                        }else{
                            $workflow_icon = '<div class="workflow-dot attribute-icon" style="background:#808080;border: #B5B5B5;margin:0 4px 0 2px"></div>';
                        }
                    }else{
                        $workflow_icon = '<div class="workflow-dot attribute-icon" style="margin:0 4px 0 2px"></div>';
                    }
                    $attr_array = [
                        "Assignee"=>isset($i["responsible"])?
                            '<div class="avatar avatar-xsmall attribute-icon"><div class="avatar-inner avatar-xsmall">'.
                            '<img src="https://bitbucket.org/account/'.
                            $i["responsible"]["username"].'/avatar/32/?ts=1443338247" alt="" class="attribute-icon" >'.
                            '</div></div>'.$i["responsible"]["display_name"]:"-",
                        "Priority"=>'<div class="icon icon-'.$i["priority"].' attribute-icon"></div>'.ucwords($i["priority"]),
                        "Type"=>'<div class="icon icon-'.$i["metadata"]["kind"].' attribute-icon"></div>'.ucwords($i["metadata"]["kind"]),
                        "Workflow"=>isset($i["workflow"])?$workflow_icon.ucwords($i["workflow"]):"-",
                        "Use case"=>isset($usecase)?$usecase["title"]:"-",
                        "Deadline"=>isset($i["deadline"])?$i["deadline"]:"-",
                        "Milestone"=>isset($milestone)?$milestone["header"]:"-"
                    ]
                    ?>
                    <?php foreach($attr_array as $key=>$value):?>
                        <tr>
                            <td class="attribute-name"><?=$key?></td>
                            <td><?=$value?></td>
                        </tr>
                    <?php endforeach?>
                </table>
            </div>
            <div >
                <a href="<?=base_url()?>Issues/list_all/<?=$repo_slug?>?status=!resolved" class="btn btn-default">Back</a>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(".markdown-content").each(function(){
            console.log(markdown.toHTML( $(this).text()));
            $(this).html(markdown.toHTML( $(this).text()));
        });
    });

</script>
<div style="display: none">
    <form class="input-form-proto" style="padding-left:42px;margin: 12px 0" method="post"
          action="<?=base_url()."Issues/input_comment/".$repo_slug."/".$i["local_id"]?>">
        <textarea class="input-comment-content" name="content"></textarea>
        <input hidden name="comment_id" class="input-comment-id">
        <div class="buttons" style="margin-top: 5px">
            <button class="btn btn-primary btn-sm disabled submit-button" type="submit" disabled="disabled">Update</button>
            <a href="#" style="margin: 0 5px" class="input-cancel">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>