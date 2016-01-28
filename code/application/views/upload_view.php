<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>File Repository</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-2.2.0.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.0.9/themes/default/style.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.0.9/jstree.min.js"></script>

    <script>
        function init(){
            reset_upload();
            initialize_tree();
        }

        function upload_file(){
            $.ajax({
                url: '<?=base_url().'upload/file_upload'?>',
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
            $.post( "<?=site_url().'upload/get_all_files/'?>", function( data ) {
                var new_data = [
                    {
                        "text": "Project Name", "type" : "root",
                        "state": {"opened": true},
                        "children": data
                    }
                ]
                $('#tree').jstree(true).settings.core.data = new_data;
                $('#tree').jstree(true).refresh();
            });
        }

        function initialize_tree() {
            $.post( "<?=site_url().'upload/get_all_files/'?>", function( data ) {

                $(function () {
                    $('#tree').jstree({
                        'core': {
                            'check_callback': true,
                            'data': [
                                {
                                    "text": "Project Name", "type" : "root",
                                    "state": {"opened": true},
                                    "children": data
                                }
                            ]
                        },
                        'plugins': [
                            "search", "state", "types", "wholerow", "checkbox"
                        ],
                        'checkbox': {
                            'keep_selected_style': false,
                            'two_state' : true
                        }
                    })
                });

            });
        }

        var selectedNode = null;

        function deleteFileButtonClicked() {
            selectedNode = $('#tree').jstree(true).get_selected('full',true);
            $('#fileDeleteModal').modal('show');
        };

        function confirmFileDeletion() {
            for(var i=0; i<selectedNode.length; i++) {
                $.post("<?=site_url().'upload/delete_by_fid/'?>" + selectedNode[i].id, function (data) {
                    if (data.status == 'error') {
                        alert(data.message);
                    }
                    $('#tree').jstree(true).delete_node(selectedNode);
                    selectedNode = null;
                });
            }
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
<div class="container">
    <a class="btn btn-default" onclick="$('#upload_file_modal').modal('show')">Upload File</a>
</div>

<div class="container">

    <div class="modal fade" id="upload_file_modal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">File Upload</h4>
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

</div>
<br><br><br>
<div class="container">
    <form id="search">
        <input type="search" id="query"/>
        <button type="submit">Search</button>
    </form>
    <br><br>
    <div class="row">
        <div class="col-md-4 col-sm-8 col-xs-8">
            <button type="button"  onclick="open_file();">Open</button>
            <!-- <button type="button"  onclick="rename_file();">Rename</button> -->
            <button type="button"  onclick="deleteFileButtonClicked();">Delete</button>
        </div>
    </div>
    <br>
    <div id="tree"></div>

    <script>
        $("#search").submit(function(e) {
            e.preventDefault();
            $("#tree").jstree(true).search($("#query").val());
        });
    </script>
</div>

</body>

</html>