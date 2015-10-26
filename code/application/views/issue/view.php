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
    <link href="<?= base_url() . 'css/sb-admin.css' ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Francois+One" />
    <link rel="stylesheet" href="<?= base_url() . 'css/sidebar-left.css' ?>">
    <link rel="stylesheet" href="<?= base_url() . 'css/issues.css' ?>">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->
    <script>
    </script>
</head>
<body>
<div class="row">
    <div class="col-sm-6" >
        <h2>
            <span style="color: #777777;padding-right: 10px">#<?=$i["local_id"]?></span>
            <?=$i["title"]?>
            <div style="height: 100%;display: inline;padding-top:0;position:relative">
                <div class="aui-lozenge" style="background-color: #fcf8e3;position:absolute;top:15px;left: 15px" title="Filter by status: <?=$i["status"]?>">
                    <?=$i["status"]?>
                </div>
            </div>
        </h2>
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
            <h3>Comments <span style="color: #777777;padding-left: 10px">#<?=$i["comment_count"]?></span></h3>
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
                $(".update-btn").on("click",function(e){
                    e.preventDefault();
                    var param = $(this).attr("param");
                    var value = $(this).attr("value");
                    $.ajax({
                        url:"<?=base_url()."Issues/update/".$repo_slug?>?param="+ param+"&value="+value+"&local_id="+<?=$i["local_id"]?>,
                        success: function(response){
                            window.location.reload();
                        }
                    });
                });
            </script>
            <div class="btn-group">
                <?php if($i["status"]=="resolved"):?>
                    <a href="#" class="btn btn-default update-btn" param="status" value="Open">Open</a>
                <?php else:?>
                    <a href="#" class="btn btn-default update-btn" param="status" value="resolved">Resolve</a>

                <?php endif?>
                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
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
            <a class="btn btn-default" href="<?= base_url()."Issues/edit/".$repo_slug."?local_id=".$i["local_id"]?>">Edit</a>
        </div>
        <div class="well" style="background-color: white;width: 250px;margin-top:30px">
            <table>
                <tr>
                    <td style="text-align: right;padding-right: 20px">Assignee</td><td><?=$i["responsible"]["display_name"]?></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 20px">Priority</td><td><?=$i["priority"]?></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 20px">Status</td><td><?=$i["status"]?></td>
                </tr>
            </table>

        </div>
    </div>
</div>

</body>
</html>