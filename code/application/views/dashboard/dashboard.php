<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <?php $this->load->view('common/common_header');?>
        <style>
            .no-gap[class*="-4"] {
                padding-left: 1px;
                padding-right: 0;
            }
        </style>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="<?= base_url() . 'js/google_chart_dashboard.js' ?>"></script>

            <style type="text/css">
            <!--
            /* Terence Ordona, portal[AT]imaputz[DOT]com         */
            /* http://creativecommons.org/licenses/by-sa/2.0/    */

            /* begin some basic styling here                     */


            /* define height and width of scrollable area. Add 16px to width for scrollbar          */
            /*
            div.tableContainer {
                clear: both;
                border: 1px solid #963;
                height: 285px;
                overflow: auto;
                width: 756px
            }
            */

            /* Reset overflow value to hidden for all non-IE browsers. */
            html>body div.tableContainer {
                overflow: hidden;

            }

            /* define width of table. IE browsers only                 */
            div.tableContainer table {
                float: left;

            }

            /* define width of table. Add 16px to width for scrollbar.           */
            /* All other non-IE browsers.                                        */
            html>body div.tableContainer table {

            }

            /* set table header to a fixed position. WinIE 6.x only                                       */
            /* In WinIE 6.x, any element with a position property set to relative and is a child of       */
            /* an element that has an overflow property set, the relative value translates into fixed.    */
            /* Ex: parent element DIV with a class of tableContainer has an overflow property set to auto */
            thead.fixedHeader tr {
                position: relative
            }

            /* set THEAD element to have block level attributes. All other non-IE browsers            */
            /* this enables overflow to work on TBODY element. All other non-IE, non-Mozilla browsers */
            html>body thead.fixedHeader tr {
                display: block
            }

            /* make the TH elements pretty */
            thead.fixedHeader th {
                background: #C96;
                border-left: 1px solid #EB8;
                border-right: 1px solid #B74;
                border-top: 1px solid #EB8;
                font-weight: normal;
                padding: 4px 3px;
                text-align: left
            }

            /* make the A elements pretty. makes for nice clickable headers                */
            thead.fixedHeader a, thead.fixedHeader a:link, thead.fixedHeader a:visited {
                color: #FFF;
                display: block;
                text-decoration: none;
                width: 100%
            }

            /* make the A elements pretty. makes for nice clickable headers                */
            /* WARNING: swapping the background on hover may cause problems in WinIE 6.x   */
            thead.fixedHeader a:hover {
                color: #FFF;
                display: block;
                text-decoration: underline;
                width: 100%
            }

            /* define the table content to be scrollable                                              */
            /* set TBODY element to have block level attributes. All other non-IE browsers            */
            /* this enables overflow to work on TBODY element. All other non-IE, non-Mozilla browsers */
            /* induced side effect is that child TDs no longer accept width: auto                     */
            html>body tbody.scrollContent {
                display: block;
                height: 500px;
                overflow: auto;
                width: 100%
            }

            /* make TD elements pretty. Provide alternating classes for striping the table */
            /* http://www.alistapart.com/articles/zebratables/                             */
            tbody.scrollContent td, tbody.scrollContent tr.normalRow td {
                background: #FFF;
                border-bottom: none;
                border-left: none;
                border-right: 1px solid #CCC;
                border-top: 1px solid #DDD;
                padding: 2px 3px 3px 4px
            }

            tbody.scrollContent tr.alternateRow td {
                background: #EEE;
                border-bottom: none;
                border-left: none;
                border-right: 1px solid #CCC;
                border-top: 1px solid #DDD;
                padding: 2px 3px 3px 4px
            }

            /* define width of TH elements: 1st, 2nd, and 3rd respectively.          */
            /* Add 16px to last TH for scrollbar padding. All other non-IE browsers. */


            /* define width of TD elements: 1st, 2nd, and 3rd respectively.          */
            /* All other non-IE browsers.                                            */
            /* http://www.w3.org/TR/REC-CSS2/selector.html#adjacent-selectors        */

            -->
        </style>



    </head>
    <body>
<?php
$class = [
    'dashboard_class'=>'active',
    'projects_class'=>'',
    'message_class'=>'',
    'customers_class'=>'',
    'internal_user_class'=>'',
    'analytics_class'=>''
];
$this->load->view('common/pm_nav', $class);
?>
<div class="col-lg-offset-1 col-lg-10">
    <!-- Page Content -->
    <div class="col-lg-12">
        <h1 class="page-header">
            TSPMS-Dashboard
        </h1>
    </div>



    <div class="col-lg-7">
    <div class="row">
        <div class="col-lg-6 ">
            <div class="panel panel-info">
                <div class="panel-heading">Important but not urgent</div>
                <div class="panel-body" style="height: 200px;overflow-y: scroll;">
                    <table class="table table-condensed " id="task_table">
                        <?php if(isset($tasks_i)):
                            foreach($tasks_i as $t):?>
                        <tr>
                            <td><?=$t['content']?></td>
                            <td> <?php if($t['days']<0){
                                    $t['days'] = 0-$t['days'];
                                    echo '<span class="badge" style="background-color: indianred">Overdue'.$t['days'].' days</span>';

                                }else{
                                    echo '<span class="badge" style="background-color: green">'.$t['days'].' days</span>';
                                }?></td>
                            <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><i class="fa fa-eye"></i></a></td>
                        </tr>
                        <?php endforeach; endif?>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel panel-danger">
                <div class="panel-heading">Important and Urgent</div>
                <div class="panel-body" style="height: 200px;overflow-y: scroll;">
                    <table class="table table-condensed " id="task_table">
                        <?php if(isset($tasks_ui)):
                            foreach($tasks_ui as $t):?>
                                <tr>
                                    <td><?=$t['content']?></td>
                                    <td> <?php if($t['days']<0){
                                            $t['days'] = 0-$t['days'];
                                            echo '<span class="badge" style="background-color: indianred">Overdue'.$t['days'].' days</span>';

                                        }else{
                                            echo '<span class="badge" style="background-color: darkorange">'.$t['days'].' days</span>';
                                        }?></td>
                                    <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            <?php endforeach; endif?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 ">
            <div class="panel panel-success">
                <div class="panel-heading">NOT Important OR Urgent</div>
                <div class="panel-body" style="height: 200px;overflow-y: scroll;">
                    <table class="table table-condensed " id="task_table">
                        <?php if(isset($tasks_none)):
                            foreach($tasks_none as $t):?>
                                <tr>
                                    <td><?=$t['content']?></td>
                                    <td> <?php if($t['days']<0){
                                            $t['days'] = 0-$t['days'];
                                            echo '<span class="badge" style="background-color: indianred">Overdue '.$t['days'].' days</span>';

                                        }else{
                                            echo '<span class="badge" style="background-color: green">'.$t['days'].' days</span>';
                                        }?></td>
                                      <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            <?php endforeach; endif?>
                    </table>

                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel panel-warning">
                <div class="panel-heading">Urgent but not Important</div>
                <div class="panel-body" style="height: 200px;overflow-y: scroll;">
                    <table class="table table-condensed " id="task_table">
                        <?php if(isset($tasks_u)):
                            foreach($tasks_u as $t):?>
                                <tr>
                                    <td><?=$t['content']?></td>
                                    <td> <?php if($t['days']<0){
                                            $t['days'] = 0-$t['days'];
                                            echo '<span class="badge" style="background-color: indianred">Overdue '.$t['days'].' days</span>';

                                        }else{
                                            echo '<span class="badge" style="background-color: darkorange">'.$t['days'].' days</span>';
                                        }?></td>
                                    <td><a href="<?=base_url().'projects/view_dashboard/'.$t['project_id']?>"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            <?php endforeach; endif?>
                    </table>

                </div>
            </div>
        </div>
    </div>
        <br/>
        <div class="col-lg-1" id="chart_div5" style="width: 600px; height: 200px;"></div>
    </div>

    <div class="col-lg-5" align="center">
        <div class="panel panel-default" style="width: 200px;height: 150px">
            <div class="panel-heading" style="background: #e0e2e5"><Strong>Total Urgency Score</Strong></div>
            <div class="panel-body" style="height: 200px;">
                <div class="thumbnail calendar-date" >
                    420
                </div>
                Intension Level: <span class="badge" style="background: #2e9ad0">Low</span>
            </div>
        </div>
    </div>
<div><br/>
    <br/></div>

    <div class="col-lg-5 tableContainer" align="center" >
        <table  id="customerTable" class="scrollTable" align="center">
            <thead class="fixedHeader">
            <th style="width: 30%">Project Name</th>
            <th style="width:20%">Current Phase</th>
            <th style="width: 15%">Urgency Score</th>
            <th style="width:30%;">Next Milestone</th>
            <th style="width:5%;"></th>
            </thead>
            <tbody class="scrollContent">
                    <?php
for ($x = 1; $x <= 9; $x++) {
    ?>
    <tr><td style="width: 30%">The Shipyard Project Management System</td>
        <td style="width: 20%">Build</td>
        <td style="width: 15%">132</td>
        <td style="width:30%">Midterm</td>
        <td style="width:5%;"><a href="#" ><i class="fa fa-eye"></i></a></td>
    </tr>
    <?php ;
}
?>




            </tbody>
        </table>
    </div>



    <div class="col-lg-12" id="chart_div3" style="height: 350px;"></div>

</div>