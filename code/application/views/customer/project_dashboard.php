<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <?php $this->load->view('common/common_header');?>
    <meta charset="UTF-8">

    <!-- Custom CSS -->
    <link href="<?=base_url().'css/sb-admin.css'?>" rel="stylesheet">
    <link href="<?=base_url().'css/timeline.css'?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Francois+One" />
    <link rel="stylesheet" href="<?=base_url().'css/sidebar-left.css'?>">
    <!-- Custom Fonts -->
    <link href="<?=base_url().'css/font-awesome.min.css'?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="<?=base_url().'js/jquery.js'?>"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url().'js/bootstrap.min.js'?>"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $(document).ready(function(){
            $('#customerTable').dataTable();
        });
    </script>
</head>
<body>
<?php $this->load->view('common/customer_nav');?>
<aside class="sidebar-left">
    <div class="sidebar-links">
        <a class="link-blue selected" href="customerMainpage.html"><i class="fa fa-flag"></i>Update & Milestone</a>
        <a class="link-blue" href="#"><i class="fa fa-folder"></i>File Repository</a>
    </div>

</aside>
<div class="col-lg-offset-1 content">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
           <?='#'.$project['project_id'].'. '.strtoupper($project['project_title'])?>
        </h1>
        <h4 style="color:darkgrey">Click each phase on timeline to check updates for each phase.</h4>
    </div>

    <!-- /.row -->
    <div class="row">
    <div class="col-lg-offset-1 no-gutter">
        <?php foreach($phases as $phase){
            $img_tag='img/future.png';
                if(isset($phase['project_phase_id'])){
                    $img_tag = 'img/done.png';
                    if ($phase['project_phase_id'] == $project['current_project_phase_id']){
                        $img_tag = 'img/current.png';
                    }

                echo'<div class="test col-sm-2 " align="center" data-toggle="tooltip"
                data-placement="bottom" title="'.$phase['start_time'].' to '.$phase['end_time'].'">'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
      }else{
                    echo' <div  class="test col-sm-2" align="center" >'.$phase['phase_name'].'<br><img src="'.base_url().$img_tag.'" class="img-responsive"></div>';
     } }?>

    </div>
    </div>
    <hr>
    <div class="row">

        <div class="col-lg-12">
            <div class="col-lg-7">
                <h3>Recent Updates - <small>Build</small></h3><hr>
                <ul class="timeline">
                    <li><!---Time Line Element--->
                        <div class="timeline-badge  neutral"><i class="fa fa-navicon"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">Customer Update 1</h4>
                            </div>
                            <div class="timeline-body"><!---Time Line Body&Content--->
                                <p>Update content is placed here...</p>
                                <div class="pull-right timeline-info">
                                    <i class="fa fa-user"></i>&nbsp;Andrew &nbsp;
                                    <i class="fa fa-calendar-check-o"></i>&nbsp;Sep,19,2015</div>
                            </div>
                        </div>
                    </li>
                    <li><!---Time Line Element--->
                        <div class="timeline-badge  neutral"><i class="fa fa-navicon"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">Customer Update #2</h4>
                            </div>
                            <div class="timeline-body"><!---Time Line Body&Content--->
                                <p>Time line content is placed here...</p>
                                <p>And some more Time line content </p>
                                <div class="pull-right timeline-info">
                                    <i class="fa fa-user"></i>&nbsp;Andrew &nbsp;
                                    <i class="fa fa-calendar-check-o"></i>&nbsp;Oct,19,2015
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><!---Time Line Element--->
                        <div class="timeline-badge neutral"><i class="fa fa-navicon"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">Customer Update #3</h4>
                            </div>
                            <div class="timeline-body"><!---Time Line Body&Content--->
                                <p>Time line content is placed here...</p>
                                <p>This appears to be a neutral time line enty...</p>
                                <div class="pull-right timeline-info">
                                    <i class="fa fa-user"></i>&nbsp;Andrew&nbsp;
                                    <i class="fa fa-calendar-check-o"></i>&nbsp;Oct,19,2015
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><!---Time Line Element--->
                        <div class="timeline-badge neutral"><i class="fa fa-navicon"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">Customer Update #3</h4>
                            </div>
                            <div class="timeline-body"><!---Time Line Body&Content--->
                                <p>Time line content is placed here...</p>
                                <p>This appears to be a neutral time line enty...</p>
                                <div class="pull-right timeline-info">
                                    <i class="fa fa-user"></i>&nbsp;Andrew&nbsp;
                                    <i class="fa fa-calendar-check-o"></i>&nbsp;Oct,19,2015
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-lg-4">

                <h3>Milestones - <small>Build</small></h3><hr>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="panel panel-default calendar">
                            <div class="panel-heading calendar-month" style="text-align:center;background:#EA9089;color:white"><strong>October</strong></div>
                            <div class="panel-body">
                                <div class="thumbnail calendar-date" >
                                    03
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <strong>Title Of Milestone</strong><br>
                        This is  a short description of milestone.
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="panel panel-default calendar">
                            <div class="panel-heading calendar-month" style="text-align:center;background:#EA9089;color:white"><strong>October</strong></div>
                            <div class="panel-body">
                                <div class="thumbnail calendar-date" >
                                    24
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <strong>Title Of Milestone</strong><br>
                        This is  a short description of milestone.
                        This is  a short description of milestone.
                        This is  a short description of milestone.
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

</body>
</html>