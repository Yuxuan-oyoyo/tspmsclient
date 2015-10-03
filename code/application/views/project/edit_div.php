<!DOCTYPE html>
<html lang="en">
<head>

    <?php $this->load->view('common/common_header');?>
    <!-- Custom CSS -->
    <!-- Morris Charts JavaScript -->
    <link href="css/plugins/bootstrap-tokenfield.min.css" rel="stylesheet">
    <link href="css/plugins/tokenfield-typeahead.min.css" rel="stylesheet">
    <script src="js/plugins/bootstrap-tokenfield.min.js"></script>


</head>
<body>
<div class="row">
    <!--project-->
    <?php
    //$project  db array
    //$tags_json_array a json array of existing tags in db
    //$phases db array
    //$customers db array
    ?>
    <?php $p=$project?>
    <div class="col-xs-8 col-xs-offset-2">
    <form class="form-horizontal">
        <div class="form-group">
            <label for="project_title">Title</label>
            <input class="form-control" id="project_title" name="project_title" value="<?=$p['project_title']?>">
        </div>
        <div class="form-group">
            <label for="project_description">Description</label>
            <textarea class="form-control" id="project_description" name="project_description" ><?=$p['project_description']?></textarea>
        </div>
        <div class="form-group">
            <label for="file_repo_name">File repo name</label>
            <input class="form-control" name="file_repo_name" value="<?=$p['file_repo_name']?>">
        </div>
        <hl></hl>
        <div class="form-group">
            <label for="customer-option"> Customer</label>
            <select class="form-control" id="customer-option" name="customer-option">
                <option value="from-existing">From existing</option>
                <option value="create-new">Create new</option>
            </select>
        </div>
        <div class="existing_customer">
            <div class="form-group">
                <label >Choose Customer:</label>
                <select class="form-control">
                    <?php foreach($customers as $c):?>
                    <option <?=$c['id']?>><?=$c['first_name']?>&nbsp;<?=$c['last_name']?></option>
                    <?php endforeach?>
                </select>
            </div>
        </div>
        <div class="new_customer"  style="display:none">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title">
            </div>
            <div class="form-group">
                <label for="first_name">First name</label>
                <input type="text" name="first_name" id="first_name">
            </div>
            <div class="form-group">
                <label for="last_name">Last name</label>
                <input type="text" name="last_name" id="last_name">
            </div>
            <div class="form-group">
                <label for="company_name">Company name</label>
                <input type="text" name="company_name" id="company_name">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username">
            </div>
            <div class="form-group">
                <label for="hp_number">HP Number</label>
                <input type="text" name="hp_number" id="hp_number">
            </div>
            <div class="form-group">
                <label for="other_number">Other Number</label>
                <input type="text" name="other_number" id="other_number">
            </div>
        </div>
        <!--will generate components based on the selection input above-->
        <hl></hl>
        <div class="form-group">
            <label for="no_of_use_cases">Number of usecases</label>
            <input class="form-control" name="no_of_use_cases" value="<?=$p['no_of_use_cases']?>">
        </div>
        <div class="form-group">
            <label for="bitbucket_repo_name">Bitbucket repo name</label>
            <input class="form-control" name="bitbucket_repo_name" value="<?=$p['bitbucket_repo_name']?>">
        </div>
        <div class="form-group">
            <label for="project_value">Project value</label>
            <input class="form-control" name="project_value" value="<?=$p['project_value']?>">
        </div>
        <div class="form-group">
            <label for="tags">Tags</label>
            <input class="form-control tokenfield" name="tags" value="<?=$p['project_description']?>">
        </div>
        <div class="form-group">
            <label for="remarks">Remarks</label>
            <input class="form-control" name="remarks" value="<?=$p['remarks']?>">
        </div>
        <hl></hl>
        <?php foreach($phases as $phs):?>
            <?=$phs["name"]?>
            <?php $start='phase-start-'.$phs["id"]?>
            <?php $end='phase-end-'.$phs["id"]?>
            <div class="form-group">
                <label for="<?=$start?>">Start date for <?=$phs["name"]?></label>
                <input class="form-control date-start datetimepicker" id="<?=$start?>" name='<?=$start?>' value="<?=$p['remarks']?>">
            </div>
            <div class="form-group">
                <label for="<?=$end?>">End date for <?=$phs["name"]?></label>
                <input class="form-control date-end datetimepicker" id="<?=$end?>" name='<?=$end?>' value="<?=$p['remarks']?>">
            </div>
        <?php endforeach?>
    </form>
    </div>
</div>
</body>
<script>
    $("#customer-option").on("change",function(){
        if($(this).value()=="from-existing"){
            $('#existing_customer').css("display","inherit");
            $('#new_customer').css("display","none");
        }else{
            $('#existing_customer').css("display","none");
            $('#new_customer').css("display","inherit");
        }
    });
    $(".date-start").on("blur",function(){
        var prev_id = $(this).attr("id").split("-")[2] - 1;
        var value= $(this).val();
        $("phase-end-"+prev_id).val(value);
    });
    $(".date-end").on("blur",function(){
        var next_id = $(this).attr("id").split("-")[2] + 1;
        var value= $(this).val();
        $("phase-end-"+next_id).val(value);
    });
    $('.tokenfield').tokenfield({
        autocomplete: {
            source: <?=$tags_json_array?>,
            delay: 100
        },
        showAutocompleteOnFocus: false
    })
</script>
</html>