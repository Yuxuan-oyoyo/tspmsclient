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
    <div class="col-xs-8 col-xs-offset-2">
    <form class="form-horizontal">
        <div class="form-group">
            <label for="project_title">Title</label>
            <input class="form-control" id="project_title" name="project_title" value="">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" ></textarea>
        </div>
        <div class="form-group">
            <label for=""></label>
            <input class="form-control" name="file_repo_name" value="">
        </div>
        <div class="form-group">
            <label for=""></label>
            <input class="form-control" name="no_of_use_cases" value="">
        </div>
        <div class="form-group">
            <label for=""></label>
            <input class="form-control" name="bitbucket_repo_name" value="">
        </div>
        <div class="form-group">
            <label for=""></label>
            <input class="form-control" name="project_value" value="">
        </div>
        <div class="form-group">
            <label for=""></label>
            <input class="form-control" name="tags" value="">
        </div>
        <div class="form-group">
            <label for=""></label>
            <input class="form-control" name="remarks" value="">
        </div>
        <hl></hl>
        
    </form>
    </div>
</div>
</body>
</html>