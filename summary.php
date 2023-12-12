<?php 
require 'lib/Database_D.php';
require 'lib/Qms_D.php';

date_default_timezone_set('Asia/Jakarta');
session_start();

if ($_SESSION['line'] == '') {
    header('location: index.php');
}

 ?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <style type="text/css">
    .outeraing {
        /*white-space: nowrap;*/
        position: relative;
        overflow-x: auto;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        height: auto;
        margin-bottom: 7px;
    }
    table, tr, th{
        border: 1px solid #D2D2D2;
    }
    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CHRONOS</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="/apple-icon.png">
    <link rel="shortcut icon" href="images/faviconku.png">
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-dark">
    <div id="right-panel" class="right-panel">
<?php

$building = $_SESSION['line'];
$dept = $_SESSION['proses'];
$date_now = date('l, d-M-Y');
$date_scan = '2020-04-30';
// $date_scan = date('Y-m-d');

$db = new Database();
$qms = new Qms_DB();
// $cek_line = $db->cek_value($date_scan,$building,$dept);
     
 ?>            
    
        <div class="breadcrumbs bg-dark" style="border-bottom: currentColor; ">
            <div class="col-sm-4">
                <div class="page-header float-left bg-dark">
                    <div class="page-title">
                        <a href="logout.php">
                            <img src="images/chronos_logo.png" style="width: 120px; height: 40px; margin-top: 5px;" alt="CHRONOS">
                        </a>
                    </div>                    
                </div>
            </div>
            <div class="col-sm-4">
                <div class="page-header bg-dark text-sm-center">
                    <div class="page-title">
                        <h1 class="text-white">J2 - PRODUCTION OUTPUT</h1>
                    </div>                    
                </div>
            </div>
            <div class="col-sm-4">
                <div class="page-header float-right bg-dark">
                    <div class="page-title">
                        <h1 class="text-white"><?php echo $date_now; ?> <span id="timestamp"></span></h1>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            $sum = $db->get_summary($date_scan);
            $data = array();
            foreach ($sum as $value) {
                $pass_qty = 0;
                $rework_qty = 0;
                $qc = $qms->qms_asy($value['line'], $date_scan);
                if (count($qc) == null) {
                    $pass_qty = 0;
                    $rework_qty = 0;
                }else{
                    foreach ($qc as $data_qc) {
                        $pass_qty = $data_qc['pass'];
                        $rework_qty = $data_qc['rework'];
                    }
                }
                $data[] = [
                    "line" => $value['line'],
                    "target" => $value['target'],
                    "asy" => $value['qty_asy'],
                    "percent" => $value['percentage'],
                    "stc" => $value['qty_stc'],
                    "pass" => $pass_qty,
                    "rework" => $rework_qty,
                ];
            }
         ?>
    <div id="korselku" class="carousel slide" data-ride="carousel" data-interval="20000">
        <div class="content mt-3 "> 
            <div class="carousel-inner"> 
                <div class="carousel-item active">
                    <div class="card">
                        <table width="100%">
                            <tr>
                                <th>Dept</th>
                                <th>Target Daily</th>
                                <th>Assembly Out</th>
                                <th>%</th>
                                <th>Stitching Out</th>
                                <th>QC Pass</th>
                                <th>Rework</th>
                            </tr>
                            <?php 
                            foreach ($data as $value) {
                                ?>
                                <tr>
                                    <th><?php echo $value['line']; ?></th>
                                    <th><?php echo $value['target']; ?></th>
                                    <th><?php echo $value['asy']; ?></th>
                                    <th><?php echo $value['percent']; ?></th>
                                    <th><?php echo $value['stc']; ?></th>
                                    <th><?php echo $value['pass']; ?></th>
                                    <th><?php echo $value['rework']; ?></th>
                                </tr>

                                <?php
                            }

                             ?>
                        </table>
                    </div>
                </div> <!-- end carousel item -->
            </div> <!-- end carousel inner -->
        </div> <!-- end content mt-3 -->
    </div> <!-- end carousel slide -->
    </div> <!-- end right panel -->
</body>

<script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script> -->
    <!-- <script src="vendors/jquery-3.4.1.js"></script> -->
    <script type="text/javascript" src="assets/js/carousel.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
    jQuery(function(){
        setInterval(timestamp, 1000);
    });
    
    function timestamp(){
        jQuery.ajax({
            url: 'lib/clock.php',
            success: function(data){
                jQuery('#timestamp').html(data);
            },
        });
    }
    </script>
    <script type="text/javascript">
     jQuery('.count').each(function () {
        jQuery(this).prop('Counter',0).animate({
            Counter: jQuery(this).text()
        }, {
            duration: 450,
            easing: 'swing',
            step: function (now) {
                jQuery(this).text(Math.ceil(now));
            }
        });
    });
    </script>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            // jQuery('#korselku').carousel({interval: 60000});

        // setTimeout(function(){window.location='pph.php';},60000);

        });
    </script>
</html>
