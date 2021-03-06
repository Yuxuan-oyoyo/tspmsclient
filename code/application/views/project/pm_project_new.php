
<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('common/common_header');?>
    <script>
        function cus_option(){
            if($("#customer_option").val()=="from-existing"){
                $("#new_customer").text('');
                var htmlText1 ='<div class="form-group"> <label >Choose Customer:</label> ' +
                    '<select class="form-control" name="c_id">'+
                    <?php foreach($customers as $c) {if($c["is_active"]==1) {?>
                    '<option value="<?= $c["c_id"] ?>"><?= $c["first_name"] ?>'+
                    '&nbsp;<?= $c["last_name"] ?></option>'+
                    <?php }}?>' </select> </div>';
                $('#existing_customer').append(htmlText1);
            }else{
                $('#existing_customer').text('');
                var htmlText2 ='<div class="col-md-4"> <div class="form-group"> <label for="title">Title</label> ' +
                    '<select class="form-control" id="title" name="title">'+
                    '<option value="Mr.">Mr.</option>'+
                    '<option value="Mrs.">Mrs.</option>'+
                    '<option value="Ms.">Ms.</option>'+
                    '<option value="Dr.">Dr.</option>'+
                    '</select>'+
                    '</div> </div> <div class="col-md-4"> <div class="form-group "> <label for="first_name">First name*</label>'+
                    '<input type="text" class="form-control" name="first_name" id="first_name" value="<?=set_value("first_name")?>" data-parsley-required>'+
                    '</div> </div> <div class="col-md-4"> <div class="form-group"> <label for="last_name">Last name*</label>'+
                    '<input type="text" class="form-control"  name="last_name" id="last_name"value="<?=set_value("last_name")?>" data-parsley-required> </div>'+
                    '</div> <div class="col-md-6"> <div class="form-group"> <label for="company_name">Company name*</label>'+
                    '<input type="text" class="form-control" name="company_name" id="company_name" value="<?=set_value("company_name")?>" data-parsley-required>'+
                    '</div> </div> <div class="col-md-6"> <div class="form-group"> <label for="email">Email*</label>'+
                    '<input type="email" class="form-control" name="email" id="email" data-parsley-type="email" value="<?=set_value("email")?>" data-parsley-email data-parsley-required>'+
                    '</div> </div> <div class="col-md-6"> <div class="form-group"> <label for="hp_number">Contact Number*</label>'+
                    '<input type="text" class="form-control" name="hp_number" id="hp_number" value="<?=set_value("hp_number")?>" data-parsley-required>'+
                    '</div> </div> <div class="col-md-6"> <div class="form-group"> <label for="other_number">Other Number</label>'+
                    '<input type="text" class="form-control" name="other_number" value="<?=set_value("other_number")?>" id="other_number">'+
                    '</div> </div> <div class="col-md-6"> <div class="form-group"> <label for="c_username">Username*</label>'+
                    '<input type="text" class="form-control" name="c_username" id="c_username" value="<?=set_value("c_username")?>" data-parsley-required>'+
                    '</div> </div> <div class="col-md-6"> <div class="form-group"> <label for="password">Password*</label>'+
                    '<input type="password" class="form-control" name="password" id="password" value="<?=DEFAULT_PASSWORD?>" data-parsley-required>'+
                    '</div> </div>';
                $('#new_customer').append(htmlText2);
            }
        };
    </script>
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <style>.tag{    border-radius: 4px;
            border: 1px black;
            padding: 2px 8px;
            background-color: #c5e5ed;
            margin-right: 3px;
            cursor:pointer;
            margin-top: 3px;
            display:inline-block
        }.tag:hover{background-color: #b7dfed;}
    </style>

</head>
<body onload="cus_option()">
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
<div class="container content">

    <form class="form-horizontal" data-parsley-validate id="form" action="<?=base_url().'Projects/create_new_project'?>" method="post">

        <div class="col-md-12">
            <h1 class="page-header">
                New Project&nbsp;
                <a href="<?=base_url().'Projects/list_all'?>" class="btn btn-default">Cancel</a>&nbsp;
                <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit">
            </h1>
            <div class="row">
                <?php if($this->session->userdata('message')):?>
                    <div class="form-group">
                        <div class="alert alert-info " role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                            <?=$this->session->userdata('message')?>
                        </div>
                    </div>
                    <?php $this->session->unset_userdata('message') ?>
                <?php endif;?>
                <?php if (validation_errors()): ?>
                    <div class="alert alert-info" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                        </button>
                        <?= validation_errors(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6 project-info">
            <h3>Project Information</h3>
            <hr>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="project_title">Title*</label>
                    <input class="form-control" id="project_title" name="project_title" value="<?=set_value("project_title")?>" data-parsley-required>
                </div>
            </div>
            <div class="col-md-offset-1 col-md-3">
                <div class="form-group">
                    <label for="project_code">Code*</label>
                    <input class="form-control" id="project_code" name="project_code" value="<?=set_value("project_code")?>" data-parsley-required data-parsley-maxlength="8">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="project_description">Description</label>
                    <textarea class="form-control" id="project_description" name="project_description" ></textarea>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="file_repo_name">File repo name</label>
                    <input class="form-control" name="file_repo_name" value="<?=set_value("file_repo_name")?>">
                </div>
            </div>
            <div class="col-md-offset-1 col-md-6">
                <div class="form-group">
                    <label for="bitbucket_repo_name">Bitbucket repo name</label>
                    <input class="form-control" name="bitbucket_repo_name" value="<?=set_value("bitbucket_repo_name")?>">
                </div>
            </div>
            <script>
                /*
                 $("#bitbucket_repo_name").on("focusout",function(){
                 var field = $(this); field.attr("disabled",true);
                 var value = field.val();
                 $.ajax({
                 url:"<?=base_url()."Projects/bb_repo_name_ajax"?>",
                 data:{repo_name:value,repo_id:<?=$p['project_id']?>},
                 success: function (result){
                 if(result=="true"){
                 $("#bitbucket_repo_name_group").removeClass("has-error");
                 }else{
                 $("#bitbucket_repo_name_group").addClass("has-error");
                 alert("The input bitbucket repository name is invalid for issue retrieval.");
                 }
                 }, complete: function(){field.removeAttr("disabled");}
                 });

                 });
                 */
            </script>

            <div class="col-md-5">
                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select class="form-control" id="priority" name="priority">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
            </div>
            <div class="col-md-offset-1 col-md-6">
                <div class="form-group">
                    <label for="project_value">Project value (S$)</label>
                    <input class="form-control" name="project_value" value="<?=set_value("project_value")?>" data-parsley-type="number" min="0">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input class="form-control tokenfield" name="tags" id="tags-input" value="<?=set_value("tags")?>">
                    <div id="tag-outer"></div>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                    var tagOuterDiv = $("#tag-outer");
                    $.ajax({
                        url:"<?=base_url()."Projects/ajax_retrieve_all_tags"?>",
                        success: function (result){
                            var tags = jQuery.parseJSON(result);
                            for(var i=0;i<tags.length;i++){
                                var tag = tags[i];
                                if(i==15) break;
                                if(tag.length>40) continue;
                                tagOuterDiv.append("<div class='tag'>"+tag+"</div>");
                            }
                        }
                    });
                });
                $("#tag-outer").on("click",".tag",function() {
                    var inputField = $("#tags-input");
                    var original = inputField.val();
                    var tag = $(this).text();
                    if ($.inArray(tag, original.split(";"))==-1) {
                        if (original.length > 0)original = original + ";";
                        inputField.val(original +tag);
                    }
                });
            </script>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="staging_link">Staging Link</label>
                    <input class="form-control" name="staging_link" value="<?=set_value("staging_link")?>" data-parsley-type="url">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="production_link">Production Link</label>
                    <input class="form-control" name="production_link" value="<?=set_value("staging_link")?>" data-parsley-type="url">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="customer_preview_link">Customer Preview Link</label>
                    <input class="form-control" name="customer_preview_link" value="<?=set_value("customer_preview_link")?>" data-parsley-type="url">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <input class="form-control" name="remarks" value="<?=set_value("remarks")?>">
                </div>
            </div>
            <hr>
        </div>
        <div class="col-md-5 customer-info">
            <h3>Customer Information</h3>
            <hr>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="customer_option"> Customer</label>
                    <select class="form-control" id="customer_option" name="customer_option" onchange="cus_option()">
                        <option value="create-new">Create new</option>
                        <option value="from-existing">From existing</option>
                    </select>
                </div>
                <div id="existing_customer">
                </div>
            </div>

            <div id="new_customer">
            </div>

            <div class="pm-info">
                <hr>
                <h3>PM Information</h3>
                <hr>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="pm_option"> PM in charge*</label>
                        <table class="table table-condensed">
                            <?php foreach($pms as $pm):?>
                                <tr>
                                    <td><label><input type="radio" name="pm_id"  value="<?=$pm['u_id']?>" required></label></td>
                                    <td><?=$pm['name']?></td>
                                </tr>
                            <?php endforeach?>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!--will generate components based on the selection input above-->

<!-- /#page-content-wrapper -->
<!-- jQuery -->
</body>
</html>