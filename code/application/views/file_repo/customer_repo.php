<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>File Repository</title>
    <?php $this->load->view('common/common_header');?>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.0.9/themes/default/style.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.0.9/jstree.min.js"></script>

    <script>
        function init(){
            initialize_tree();
        }


        function disable_file_delete_modal(){
            $("#fileDeleteModal").modal('hide');
        }

        function refreshTree(){
            $.post( "<?=site_url().'upload/get_all_files/'.$project['project_id']?>", function( data ) {
                var new_data = [
                    {
                        "text": "<?=$project['project_title']?>",
                        "state": {"opened": true},
                        "children": data
                    }
                ]
                $('#tree').jstree(true).settings.core.data = new_data;
                $('#tree').jstree(true).refresh();
            });
        }

        function initialize_tree() {
            $.post( "<?=site_url().'upload/get_all_files/'.$project['project_id']?>", function( data ) {

                $(function () {
                    $('#tree').jstree({
                        'core': {
                            'check_callback': true,
                            'data': [
                                {
                                    "text": "<?=$project['project_title']?>",
                                    "state": {"opened": true},
                                    "children": data
                                }
                            ]
                        },
                        'plugins': [
                            "search", "state", "types", "wholerow"
                        ]
                    }).on("dblclick", ".jstree-anchor", function(e) {
                        var selectedNode = $('#tree').jstree(true).get_selected('full',true)[0];
                        var link = selectedNode['original']['a_attr']['href'];

                        if(link != '#'){
                            window.open(link);
                        }
                    })
                });

            });
        }

        var selectedNode = null;



        function open_file() {
            selectedNode = $('#tree').jstree(true).get_selected('full',true)[0];
            var link = selectedNode['original']['a_attr']['href'];

            if(link != '#'){
                window.open(link);
            }
        }

        function rename_file() {
            var ref = $('#tree').jstree(true),
                sel = ref.get_selected();
            if(!sel.length) { return false; }
            sel = sel[0];
            ref.edit(sel);
        };

    </script>
</head>

<body onload="init()">
<?php
$class = [
    'dashboard_class'=>'',
    'projects_class'=>'active',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/customer_nav', $class);
?>
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue " href="<?=base_url().'projects/customer_view/'.$project['project_id']?>"><i class="fa fa-flag"></i><span class="nav-text">Update & Milestone</span></a>
        <a class="link-blue" href="<?=base_url().'Usecases/customer_usecases/'.$project["project_id"]?>"><i class="fa fa-list"></i><span class="nav-text">Use Case List</span></a>
        <a class="link-blue selected " href="<?=base_url().'Upload/customer_repo/'.$project["project_id"]?>"><i class="fa fa-folder"></i><span class="nav-text">File Repository</span></a>
    </div>

</aside>


<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header"> File Repository - <?=$project['project_title']?></h1>
    </div>



    <form id="search">
        <div class="col-lg-3">
            <input class="form-control" type="search" id="query"/>
        </div>
        <div class="col-lg-9">
            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i>&nbsp;Search</button>
        </div>

    </form>

    <div class="col-lg-9" style="margin-top:10px;">
        <div style ="margin-left=5px">
            <button type="button" class="btn btn-success btn-sm"  onclick="open_file();"><i class="fa fa-folder"></i>&nbsp;Open</button>
            <!-- <button type="button"  onclick="rename_file();">Rename</button> -->
        </div>
        <div id="tree" style="background-color: #f5f5f5"></div>
    </div>


    <script>
        $("#search").submit(function(e) {
            e.preventDefault();
            $("#tree").jstree(true).search($("#query").val());
        });
    </script>
</div>

</body>

</html>