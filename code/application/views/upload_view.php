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
            reset_upload();
            initialize_tree();
        }

        function upload_file(){
            $.ajax({
                url: '<?=base_url().'upload/file_upload/'.$project['project_id']?>',
                type: 'POST',
                data: new FormData($('#upload_file_form')[0]),
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data)
                {
                    console.log(data);
                    if(data.status==="success"){
                        reset_upload();
                        refreshTree();
                    }else{
                        display_upload_form_error(data.message)
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    display_upload_form_error('Ajax Error:'+textStatus);
                    console.log('ERRORS: ' + textStatus);
                }
            });
            disable_upload_form();
        }

        function reset_upload(){
            $("#upload_file_modal").modal('hide');
            $("#upload_message_alert").empty();
            $("#upload_message_alert").hide();
            $("#upload_progress_bar").hide();
            $("#upload_button").show();
            $("#upload_cancel_button").show();
        }

        function disable_upload_form(){
            $("#upload_button").hide();
            $("#upload_progress_bar").show();
            $("#upload_cancel_button").hide();
        }

        function display_upload_form_error(errorMessage){
            $("#upload_message_alert").html(errorMessage);
            $("#upload_message_alert").show();
            $("#upload_progress_bar").hide();
            $("#upload_button").show();
            $("#upload_cancel_button").show();
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

        function deleteFileButtonClicked() {
            selectedNode = $('#tree').jstree(true).get_selected('full',true)[0];
            $('#fileDeleteModal').modal('show');
        };

        function confirmFileDeletion() {
            $.post( "<?=site_url().'upload/delete_by_fid/'?>"+selectedNode.id, function( data ) {
                if(data.status=='error'){
                    alert(data.message);
                }
                $('#tree').jstree(true).delete_node(selectedNode);
                selectedNode = null;
            });

            disable_file_delete_modal()
        }

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
$this->load->view('common/pm_nav', $class);
?>
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue" href="<?=base_url().'Projects/view_dashboard/'.$project["project_id"]?>"><i class="fa fa-tasks"></i>Project Overview</a>
        <a class="link-blue " href="<?=base_url().'Projects/view_updates/'.$project["project_id"]?>"><i class="fa fa-flag"></i>Update & Milestone</a>
        <?php
        if($project['bitbucket_repo_name']==null){
            ?>
            <a class="link-grey"><i class="fa fa-wrench"></i>Issues</a>
            <?php
        }else {
            ?>
            <a class="link-blue " href="<?= base_url() . 'Issues/list_all/' . $project["bitbucket_repo_name"] ?>"><i class="fa fa-wrench"></i>Issues</a>
            <?php
        }
        ?>
        <a class="link-blue" href="<?=base_url().'Usecases/list_all/'.$project["project_id"]?>"><i class="fa fa-list"></i>Use Case List</a>
        <a class="link-blue" href="<?=base_url().'Projects/view_report/'.$project["project_id"]?>"><i class="fa fa-bar-chart"></i>Analytics</a>
        <a class="link-blue selected" href="#"><i class="fa fa-folder"></i>File Repository</a>
    </div>
</aside>


<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header"> File Repository - <?=$project['project_title']?></h1>
    </div>

    <div class="modal fade" id="upload_file_modal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Upload File</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info alert-dismissible" role="alert" id="upload_message_alert">
                    </div>
                    <form name="upload_file_form" id="upload_file_form" method="post" enctype="multipart/form-data" action="<?=base_url().'upload/file_upload'?>">
                        <div class="form-group">
                            <label for="file_input">Select file</label>
                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
                            <input type="file" id="file_to_upload" name="file_to_upload">
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <div class="progress" name="upload_progress_bar" id="upload_progress_bar">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" style="width:100%" >
                            Uploading... Please wait
                        </div>
                    </div>
                    <button type="button" class="btn btn-default pull-left" onclick="reset_upload()" id="upload_cancel_button">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="upload_file()" id="upload_button">Upload</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="fileDeleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Delete File</strong>
                </div>
                <div class="modal-body">
                    This action cannot be undone, do you wish to proceed?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="btnYes" onclick="confirmFileDeletion()"> Delete </button>
                </div>
            </div>
        </div>
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
            <button class="btn btn-sm btn-primary" onclick="$('#upload_file_modal').modal('show')"><i class="fa fa-plus"></i>&nbsp;Upload</button>
            <button type="button" class="btn btn-success btn-sm"  onclick="open_file();"><i class="fa fa-folder"></i>&nbsp;Open</button>
            <!-- <button type="button"  onclick="rename_file();">Rename</button> -->
            <button type="button" class="btn btn-warning btn-sm" onclick="deleteFileButtonClicked();"><i class="fa fa-trash"></i>&nbsp;Delete</button>
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