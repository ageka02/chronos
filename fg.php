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
$date_scan = '2020-05-18';
// $date_scan = date('Y-m-d');

$db = new Database();
$vd = new Qms_DB();
$cek_line = $db->cek_value($date_scan,$building,$dept);
     
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
        $fg_data = $db->fg_data($date_scan);
        $data = array();
        foreach ($fg_data as $value) {
            $data[] = [
                "current_in" => $value['current_in'],
                "stok" => $value['stok'],
                "last_out" => $value['last_out']
            ];
        }
         ?>
    <div id="korselku" class="carousel slide" data-ride="carousel" data-interval="20000">
        <div class="content mt-3 "> 
            <div class="carousel-inner"> 
                
                <div class="carousel-item active">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="text-sm-center">FINISH GOOD DATA</h2>
                            </div>
                           
                        </div>
                    </div>
                        <div class="col-lg-4">
                            <div class="card" style="margin-top: 70px;">
                                <div class="card-header bg-dark">
                                    <h2 class="text-sm-center text-white">Current IN</h2>
                                </div>
                                <div class="card-body text-sm-center">
                                    <!-- <h2 style=" font-size: 170px;"><?php echo $data[0]['current_in']; ?></h2> -->
                                    <h2 style=" font-size: 170px;">87</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header bg-dark">
                                    <h2 class="text-sm-center text-white">Onhand Qty</h2>
                                </div>
                                <div class="card-body bg-success text-sm-center text-white" style="height: 500px;">
                                    <!-- <h2 style=" font-size: 170px; margin-top: 50px;"><?php echo $data[0]['stok']; ?></h2> -->
                                    <h2 style=" font-size: 170px; margin-top: 50px;">753</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card" style="margin-top: 70px;">
                                <div class="card-header bg-dark">
                                    <h2 class="text-sm-center text-white">Last Out</h2>
                                </div>
                                <div class="card-body text-sm-center">
                                    <!-- <h2 style=" font-size: 170px;"><?php echo $data[0]['last_out']; ?></h2> -->
                                    <h2 style=" font-size: 170px;">359</h2>
                                </div>
                            </div>
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

        setTimeout(function(){window.location='perf_chart.php';},60000);

        });
    </script>
</html>
