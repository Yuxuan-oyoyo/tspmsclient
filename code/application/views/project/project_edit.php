<?php
$class = [
    'projects_class'=>'active',
    'message_class'=>'',
    'customers_class'=>'',
    'analytics_class'=>''
];
$p = $project;
$this->load->view('common/pm_nav', $class);
?>
<p></p>
    <!-- Page Content -->

    <div class="container content">
        <form class="form-horizontal" id="form" method="post" action="<?=base_url()."Projects/process_edit/".$p["project_id"]?>">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Edit Project&nbsp;
                    <a href="<?=base_url().'Projects/view_dashboard/'.$p["project_id"]?>" class="btn btn-default" id="cancel">Cancel</a>&nbsp;
                    <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit">
                </h1>
            </div>

            <div class="col-lg-6 project-info">
                <h3>Project Information</h3>
                <hr>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="project_title">Title*</label>
                        <input class="form-control" id="project_title" name="project_title" value="<?=$p['project_title']?>">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="project_description">Description</label>
                        <textarea class="form-control" id="project_description" name="project_description" ><?=$p['project_description']?></textarea>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label for="file_repo_name">File repo name</label>
                        <input class="form-control" name="file_repo_name" value="<?=$p['file_repo_name']?>">
                    </div>
                </div>
                <div class="col-lg-offset-1 col-lg-6">
                    <div class="form-group">
                        <label for="bitbucket_repo_name">Bitbucket repo name</label>
                        <input class="form-control" name="bitbucket_repo_name" value="<?=$p['bitbucket_repo_name']?>">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label for="no_of_use_cases">Number of usecases</label>
                        <input class="form-control" name="no_of_use_cases" value="<?=$p['no_of_use_cases']?>">
                    </div>
                </div>
                <div class="col-lg-offset-1 col-lg-6">
                    <div class="form-group">
                        <label for="project_value">Project value</label>
                        <input class="form-control" name="project_value" value="<?=$p['project_value']?>">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input class="form-control " id="tokenfield" name="tags" value="<?=$p['tags']?>">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <input class="form-control" name="remarks" value="<?=$p['remarks']?>">
                    </div>
                </div>
                <hr>
            </div>
            <div class="col-lg-5 customer-info">
            <h3>Customer Information</h3>
            <hr>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="customer-option"> Customer</label>
                    <select class="form-control" id="customer-option" name="customer-option" onchange="cus_option()">
                        <option value="create-new">Create new</option>
                        <option value="from-existing" selected>From existing</option>
                    </select>
                </div>
                <div id="existing_customer">
                    <div class="form-group">
                        <label >Choose Customer:</label>
                        <select class="form-control" name="c_id">
                            <?php foreach($customers as $c):?>
                                <?php if($c['c_id']==$p['c_id']):?>
                                    <option value="<?=$c['c_id']?>" selected><?=$c['first_name']?>&nbsp;<?=$c['last_name']?></option>
                                <?php else:?>
                                    <option value="<?=$c['c_id']?>"><?=$c['first_name']?>&nbsp;<?=$c['last_name']?></option>
                                <?php endif?>
                            <?php endforeach?>
                        </select>
                    </div>
                </div>
            </div>

            <div id="new_customer">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group ">
                        <label for="first_name">First name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input type="text" class="form-control"  name="last_name" id="last_name">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="company_name">Company name</label>
                        <input type="text" class="form-control" name="company_name" id="company_name">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="hp_number">HP Number</label>
                        <input type="text" class="form-control" name="hp_number" id="hp_number">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="other_number">Other Number</label>
                        <input type="text" class="form-control" name="other_number" id="other_number" >
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username" >
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="password_hash">Password</label>
                        <input class="form-control" name="password_hash" id="password_hash" value="<?=DEFAULT_PASSWORD?>">
                    </div>
                </div>

            </div>
        </div>
        </form>
    </div>


</body>
</html>