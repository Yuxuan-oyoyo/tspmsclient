
            <?php
            $repo_slug = $repo_slug;
            function _ago($tm,$rcs = 0) {
                $cur_tm = time(); $dif = $cur_tm+60-$tm;
                $pds = array('sec','min','hr','day','wk','mth','yr','decade');
                $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
                for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

                $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
                if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
                return $x;
            }
            $ci =&get_instance();
            $project = $project;
            $_URL = explode("?",$_SERVER['REQUEST_URI'])[0];
            $filter_arr =$para_raw;
            $issues = $issues;
            $count = $count;
            $num_per_page = $num_per_page;
            $current_page = $filter_arr["page"];
            $headers = [
                "local_id"=>["display"=>"Title"     ,"sort"=>"local_id"],//"sm" for sort method
                "kind"=>["display"=>"Type"  ,"sort"=>"kind" ],
                "priority"=>["display"=>"Priority" ,"sort"=>"priority"],
                "status"=>["display"=>"Status"   ,"sort"=>"status" ],
                "milestone"=>["display"=>"Milestone" ,"sort"=>"milestone"],
                "responsible"=>["display"=>"Assignee" ,"sort"=>"responsible"],
                "utc_created_on"=>["display"=>"Created" ,"sort"=>"utc_created_on"],
                "utc_last_updated"=>["display"=>"Updated" ,"sort"=>"utc_last_updated"],
            ];
            $status_color=[
                "new"=>"#34495e","open"=>"#AD5E19 ","resolved"=>"#1D7D46",
                "on hold"=>"#015B96","invalid"=>"#B31A0C","duplicate"=>"#613873",
                "wontfix"=>"#95a5a6","closed"=>"#7f8c8d"
            ];

            unset($filter_arr["page"]);
            $sorted_header=null;
            if(isset($filter_arr["sort"])){
                if(substr($filter_arr["sort"],0,1)!="-"){
                    $sorted_header = $filter_arr["sort"];
                    if(isset($headers[$sorted_header]))
                        $headers[$sorted_header]["sort"]="-".$headers[$sorted_header]["sort"];
                }else{
                    $sorted_header = substr($filter_arr["sort"],1);
                }
            }
            $filter_str =http_build_query($filter_arr);
            $filter_str = empty($filter_str)? "": $filter_str."&";
            unset($filter_arr["sort"]);
            $milestone_mapping = [];
            ?>
            <div style="width: 100%;height:34px">
                <div style="float: left">
                    <div class="btn-group">
                        <a class="btn btn-default inner-action" href="<?=$_URL?>">All</a>
                        <a class="btn btn-default inner-action" href="<?=$_URL?>?status=!resolved">Unresolved</a>
                        <div class="btn-group">
                            <button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown">
                                Workflow <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?=$_URL?>?title=~[to develop]" class="inner-action">To develop</a></li>
                                <li><a href="<?=$_URL?>?title=~[to test]" class="inner-action">To test</a></li>
                                <li><a href="<?=$_URL?>?title=~[to deploy]" class="inner-action">To deploy</a></li>
                                <li><a href="<?=$_URL?>?title=~[ready for deployment]" class="inner-action">Ready for Deployment</a></li>
                            </ul>
                        </div>
                        <?php
                            $user_id = $ci->session->userdata('internal_uid');
                            $logged_in_user = $ci->Internal_user_model->retrieve($user_id);
                        ?>
                        <a class="btn btn-default inner-action" style="margin-right:15px" href="<?=$_URL?>?responsible=<?=$logged_in_user["bb_username"]?>">My Issues</a>
                    </div>
                </div>
                <div style="float: right">
                    <input id="search-box" class="form-control" style="width: auto;display: inline;margin-right:50px" placeholder="Find issue">
                    <script>$('#search-box').keypress(function (e) {if (e.which == 13) {
                            if($('#search-box').length>0){
                                reload('<?=$_URL."?".$filter_str?>'+"search="+$('#search-box').val());
                            }
                            return false;}});
                    </script>
                    <a href="../create/<?=$repo_slug?>" class="btn btn-primary" style="margin-top: -3px"><i class="fa fa-plus"></i>&nbsp;Create Issue</a>
                </div>
            </div>
            <div style="width: 100%;font-size: 1.2em;margin: 7px 5px">

                <b>Showing issues (<?=$num_per_page * ($current_page -1)+1?>-<?=min($count,$num_per_page*$current_page)?> of <?=$count?>) </b>
                <?php if(!empty($filter_arr)):?>|
                    <?php foreach($filter_arr as $key=>$value):?>
                        <?php
                        if($key=='title') {
                            if(preg_match("/\[(.*?)\]/",$value,$displayrrr)){
                                $value = $displayrrr[1];
                                $key = 'workflow';
                            }
                        }elseif ($key=='kind'){$key='type';}elseif ($key=='responsible'){$key='assignee';}
                        if(substr($value,0,1)=="!")$value ="not ". substr($value,1);
                        ?>
                        <span style="color: grey"><b><?=$key?></b>: "<?=$value?>"</span>
                    <?php endforeach?>
                    <a href="<?=$_URL?>" class="inner-action">Clear</a>
                <?php endif?>
            </div>
            <?php if (!isset($issues)):?>
                <div class="alert alert-danger">
                    <strong>Oh snap!</strong> Bitbucket Issues are not accessible. This is probably caused by incorrect Bitbucket repository Slug.
                        Please go to <a href="<?=base_url()?>Projects/edit/<?=$project["project_id"]?>" class="alert-link">Project Overview page</a> to update the repository slug.

                </div>
            <?php else://issues are set?>
            <table class="table table-striped" data-sort-by="updated_on" data-modules="components/follow-list">
                <thead>
                <tr>
                    <th class="text sorter-false" style="padding: 8px 4px"></th>
                    <?php foreach($headers as $h):?>
                        <th class="text sorter-false tablesorter-header" style="padding: 8px 4px">
                            <a href="<?=$_URL."?".$filter_str."sort=".$h["sort"]?>"
                               title="Sort by: <?=$h["sort"]?>" class="inner-action"><?=$h["display"]?></a>
                            <?php if(isset($sorted_header) && "-".$sorted_header==$h["sort"]):?>
                                <i class="glyphicon glyphicon-triangle-top" style="color: grey"></i>
                            <?php elseif(isset($sorted_header) && $sorted_header==$h["sort"]):?>
                                <i class="glyphicon glyphicon-triangle-bottom" style="color: grey"></i>
                            <?php endif?>
                        </th>
                    <?php endforeach?>
                </tr>
                </thead>
                <tbody id="tbody">

                <?php foreach($issues as $d):?>
                    <tr class="" data-state="open">

                        <td style="padding-right: 0;padding-top:10px;vertical-align: middle">
                            <?php $workflow_color= [
                                "to develop"=>["#F30000","#FF5154"],"to test"=>["#FFFF45","#E8E05C"],
                                "ready for deployment"=>["#008400","#6ABB6B"],"to deploy"=>["#32A299","#6CBBB6"],
                            ];
                            ?>
                            <?php if(empty($d["workflow"])):?>
                                <div class="workflow-dot" style=""></div>
                            <?php else:?>
                                <?php if(isset($workflow_color[$d["workflow"]])):?>
                                    <a href="<?=$_URL?>?title=~[<?=$d["workflow"]?>]" class="inner-action" title="Filter by: <?=ucwords($d["workflow"])?>">
                                        <div class="workflow-dot"
                                             style="background:<?=$workflow_color[$d["workflow"]][0]?>;border: <?=$workflow_color[$d["workflow"]][1]?>"></div>
                                    </a>
                                <?php else:?>
                                    <a href="<?=$_URL?>?title=~[<?=$d["workflow"]?>]" class="inner-action" title="Filter by: <?=ucwords($d["workflow"])?>">
                                        <div class="workflow-dot" style="background:#808080;border: #B5B5B5"></div>
                                    </a>
                                <?php endif;?>
                            <?php endif;?>
                        </td>
                        <td class="" style="width: 40%">
                            <a href="<?=base_url()."Issues/detail/".$repo_slug."/".$d["local_id"]?>" title="View Details">#<?=$d["local_id"]?>: <?=$d["title"]?></a>
                        </td>
                        <td class="icon-col" style="text-align: center">
                            <a href="<?=$_URL."?".$filter_str."kind=".$d["metadata"]["kind"]?>"
                               class="icon icon-<?=$d["metadata"]["kind"]?> inner-action" title="Filter by type: <?=ucwords($d["metadata"]["kind"])?>">
                                <?=ucwords($d["metadata"]["kind"])?>
                            </a>
                        </td>
                        <td class="icon-col"  style="text-align: center">
                            <a href="<?=$_URL."?".$filter_str."priority=".$d["priority"]?>"
                               class="icon icon-<?=$d["priority"]?> inner-action" title="Filter by priority: <?=ucwords($d["priority"])?>">
                                <?=ucwords($d["priority"])?>
                            </a>
                        </td>
                        <td class="state" style="padding-right: 0px">
                            <a class="aui-lozenge inner-action" href="<?=$_URL."?".$filter_str."status=".$d["status"]?>"
                               title="Filter by status: <?=ucwords($d["status"])?>"
                               style="color: <?=$status_color[$d["status"]]?>;border-color: <?=$status_color[$d["status"]]?>">
                                <?php
                                    switch($d["status"]){case "duplicate": echo "dupl.";break; case "resolved" : echo "rslvd";break;
                                        default: echo $d["status"];
                                    }
                                ?>
                            </a>
                        </td>
                        <td class="milestone">
                            <?php
                                $ci->load->model("Milestone_model");

                                $milestone_id = isset($d['metadata']["milestone"])&&$d['metadata']["milestone"]!="nil"?$d['metadata']["milestone"]:null;
                                $milestone = "";
                                if(isset($milestone_id)){
                                    if(isset($milestone_mapping[$milestone_id]))$milestone = $milestone_mapping[$milestone_id];
                                    else {
                                        $milestone_array = $ci->Milestone_model->retrieve_milestone_by_id($milestone_id);
                                        if(isset($milestone_array)){
                                            $milestone = $milestone_array["header"];
                                            $milestone_mapping[$milestone_id] = $milestone;
                                        }
                                    }
                                }
                            ?>
                            <?php if(!empty($milestone)):?>
                            <a href="<?=$_URL."?".$filter_str."milestone=".$milestone?>"
                               class="milestone-link inner-action" title="Filter by milestone: <?=ucwords($milestone)?>"><?=$milestone?></a>
                            <?php else:?>-<?php endif;?>
                        </td>

                        <td class="user">
                            <div>
                                <?php if(isset($d["responsible"])):?>
                                <a href="<?=$_URL."?".$filter_str."responsible=".$d["responsible"]["username"]?>"
                                   title="Filter issues assigned to: <?=$d["responsible"]["display_name"]?>" class="inner-action">
                                    <div class="avatar avatar-xsmall">
                                        <div class="avatar-inner avatar-xsmall">
                                            <img src="https://bitbucket.org/account/<?=$d["responsible"]["username"]?>/avatar/32/?ts=1443338247" alt="" >
                                        </div>
                                    </div>
                                    <span>
                                        <?=strlen($d["responsible"]["display_name"]) > 10 ?
                                            substr($d["responsible"]["display_name"],0,10)."..." : $d["responsible"]["display_name"];?>
                                    </span>
                                </a>
                                <?php else:?>
                                -
                                <?php endif?>
                            </div>
                        </td>
                        <td class="date" style="min-width: 100px">
                            <div>
                                <time datetime="<?=$d["utc_created_on"]?>" data-title="true">
                                    <?=_ago(strtotime($d["utc_created_on"]))?> ago
                                </time>
                            </div>
                        </td>
                        <td class="date" style="min-width: 100px">
                            <div>
                                <time datetime="<?=$d["utc_last_updated"]?>" data-title="true">
                                    <?=_ago(strtotime($d["utc_last_updated"]))?> ago
                                </time>
                            </div>
                        </td>
                    </tr>
                <?php endforeach?>
                </tbody>
            </table>

            <div>
                <div align="center">
                <ul class="pagination">
                    <?php
                        $num_of_pages = floor($count/ $num_per_page) + 1;
                    ?>
                    <li class="<?=$current_page==1?'disabled':''?>">
                        <a href="<?=$_URL."?".$filter_str."page=".($current_page-1)?>" class="inner-action" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span></a>
                    </li>
                    <?php for($i=1;$i<=$num_of_pages;$i++):?>
                        <li class="<?=$i==$current_page?'active':''?>">
                            <a href="<?=$_URL."?".$filter_str."page=".$i?>" class="inner-action">
                                <?=$i?><?=$i==$current_page?'<span class="sr-only">(current)</span>':''?>
                            </a>
                        </li>
                    <?php endfor?>
                    <li class="<?=$current_page==$num_of_pages?'disabled':''?>">
                        <a href="<?=$_URL."?".$filter_str."page=".($current_page+1)?>" class="inner-action" aria-label="Previous">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
                </div>
            </div>
            <?php endif;//end checking if issues are valid?>

