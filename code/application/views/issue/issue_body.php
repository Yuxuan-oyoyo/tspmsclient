<?php
$issues = $issues_response["issues"];
?>
<?php foreach($issues as $d):?>
    <?php //echo var_dump($d)?>
    <tr class="" data-state="open">
        <td class="">
            <a class="execute" href="<?=base_url()."issues/retrieve_by_id/".$repo_slug."?".$d["local_id"]?>" title="View Details">#<?=$d["local_id"]?>: <?=$d["title"]?></a>
        </td>
        <td class="icon-col">
            <a href="<?=base_url()."issues/list_all/".$repo_slug."?".$filter_str."kind=".$d["metadata"]["kind"]?>"
               class="icon-bug" title="Filter by type:<?=$d["metadata"]["kind"]?>">
                <?=$d["metadata"]["kind"]?>
            </a>
        </td>
        <td class="icon-col">
            <a href="<?=base_url()."issues/list_all/".$repo_slug."?".$filter_str."priority=".$d["priority"]?>"
               class=" icon-major" title="Filter by priority:"<?=$d["priority"]?>>
                <?=$d["priority"]?>
            </a>
        </td>
        <td class="state">
            <a class="aui-lozenge" href="<?=base_url()."issues/list_all/".$repo_slug."?".$filter_str."status=".$d["status"]?>"
               title="Filter by status: <?=$d["status"]?>">
                <?=$d["status"]?>
            </a>
        </td>
        <td></td>
        <td class="user">
            <div>
                <a href="<?=base_url()."issues/list_all/".$repo_slug."?".$filter_str."responsible=".$d["responsible"]["username"]?>"
                   title="Filter issues assigned to: <?=$d["responsible"]["display_name"]?>">
                    <div class="aui-avatar aui-avatar-xsmall">
                        <div class="aui-avatar-inner">
                            <!--img src="https://bitbucket.org/account/czyang_jessie/avatar/32/?ts=1443338247" alt="" /-->
                        </div>
                    </div>
                    <span title="<?=$d["responsible"]["username"]?>"><?=$d["responsible"]["display_name"]?></span>
                </a>
            </div>
        </td>
        <td class="date">
            <div>
                <time datetime="2015-10-15T11:43:49.635488+00:00" data-title="true">2015-10-15</time>
            </div>
        </td>
        <td class="date">
            <div>
                <time datetime="2015-10-15T12:06:49.753899+00:00" data-title="true">2015-10-15</time>
            </div>
        </td>
    </tr>
<?php endforeach?>