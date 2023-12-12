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
// $date_scan = '2020-04-17';
$date_scan = date('Y-m-d');

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
    <div id="korselku" class="carousel slide" data-ride="carousel" data-interval="10000">

        <div class="content mt-3 "> 
            <div class="carousel-inner"> 
                <?php 
                // CEK JIKA ADA RETURN DARI DATABASE 
                if ($cek_line != 0) {
                   
                $sql_line = $db->select_line($date_scan,$building,$dept);
                $j = 1; 
                 foreach($sql_line as $data_line)
                 {
        
                 ?>
                <div class="carousel-item <?php if($j<=1){echo "active";} ?>">

            <div class="col-4" style="width: 420px;"> 
                <div class="card" style="height: auto; " >
                    <table width="100%">
                        <tr>
                            <th colspan="2" class="bg-dark">
                                <h1 class="text-sm-center text-white" style=" font-size: 70px;"><?php echo $data_line['line_code']; ?></h1>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" class="bg-dark">
                                <h2 class="text-sm-center text-white">
                                     <?php 
                                        if ($data_line['dept_code'] == '121-ST1') {
                                            echo "STITCHING";
                                        }elseif ($data_line['dept_code'] == '121-CP1') {
                                            echo "CUTTING";
                                        }elseif ($data_line['dept_code'] == '121-SC0') {
                                            echo "SUBCONT";
                                        }elseif ($data_line['dept_code'] == '121-PT1') {
                                            echo "RUBBER";
                                        }elseif ($data_line['dept_code'] == '121-AS1') {
                                            echo "ASSEMBLY";
                                        }elseif ($data_line['dept_code'] == '121-DS1') {
                                            echo "STOCKFIT";
                                        }elseif ($data_line['dept_code'] == '121-PRE') {
                                            echo "SUPERMARKET CENTRAL";
                                        }elseif ($data_line['dept_code'] == '121-FGD') {
                                            echo "FINISH GOOD";
                                        }
                                    ?>
                                </h2>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <h3>ACTUAL</h3>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <h1 class="text-sm-center" style=" font-size: 70px;">
                                    <?php 
                                        $qty = $db->get_actual($date_scan,$data_line['line_code'],$data_line['dept_code']);
                                        if($qty != ''){
                                            $actual = round($qty['qty']);
                                        }else{
                                            $actual = 0;
                                        }
                                        echo $actual;
                                    ?> 
                                </h1>
                            </th>
                            
                        </tr>
                        <tr>
                            <th colspan="2">
                                <h3>PERCENTAGE</h3>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <h1 class="text-sm-center text-white bg-primary" style=" font-size: 70px;">
                                    <?php 
                                        $target_daily = $data_line['target']*8;
                                        $percentage = 0;
                                        $percentage = ($actual/$target_daily)*100;
                                        echo round($percentage,1)."%";
                                    ?>
                                </h1>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <h3>TARGET</h3>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <h4 class="text-sm-center">Daily</h4>
                            </th>
                            <th>
                                <h4 class="text-sm-center">Hours</h4>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <h1 class="text-sm-center" style=" font-size: 50px;">
                                    <?php echo $target_daily; ?>
                                </h1>
                            </th>
                            <th>
                                <h1 class="text-sm-center" style=" font-size: 50px;">
                                    <?php echo $data_line['target']; ?>
                                </h1>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <h3>STATUS</h3>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <h4 class="text-sm-center">Balance</h4>
                            </th>
                            <th>
                                <h4 class="text-sm-center">Average/Hours</h4>
                            </th>
                        </tr>
                        <!-- BG balance -->
                         <?php 
                            $balance = $actual-$target_daily;
                            if ($balance <= 0) {
                                $bg = "bg-danger";
                            }else{
                                $bg = "bg-success";
                            }
                            
                            //AVERAGE/HOURS
                            $ave = 0;
                            $day = date('l', strtotime($date_scan));
                            $sql_last_scan = $db->get_last_scan($date_scan,$data_line['line_code'],$data_line['dept_code']);
                            $get_last = $sql_last_scan['last'];
                            // CEK NAMA HARI
                            if ($day != 'Friday') {
                                if (strtotime(date('H:i')) < strtotime('12:00')) {
                                    $diff = strtotime(date('H:i')) - strtotime('07:00');
                                }elseif (strtotime(date('H:i')) >= strtotime('12:00') && strtotime(date('H:i')) <= strtotime('17:00')) {
                                    $diff = strtotime(date('H:i')) - strtotime('08:00');
                                }else{
                                    $diff = strtotime($get_last) - strtotime('08:00');
                                }
                                $selisih = floor($diff/(60*60));
                                $ave = round($actual/$selisih);
                            }else{
                                // KONDISIKAN UNTUK HARI JUM'AT
                                if (strtotime(date('H:i')) < strtotime('12:00')) {
                                    $diff = strtotime(date('H:i')) - strtotime('07:00');
                                }elseif (strtotime(date('H:i')) >= strtotime('12:00') && strtotime(date('H:i')) <= strtotime('17:00')) {
                                    $diff = strtotime(date('H:i')) - strtotime('08:00');
                                }else{
                                    $diff = strtotime($get_last) - strtotime('09:00');
                                }
                                $selisih = floor($diff/(60*60));
                                $ave = round($actual/$selisih);
                            }
                         ?>
                         <tr>
                             <th class="<?php echo $bg; ?>">
                                 <h1 class="text-sm-center text-white" style=" font-size: 50px;"><?php echo $balance; ?></h1>
                             </th>
                             <th>
                                 <h1 class="text-sm-center" style=" font-size: 50px;"><?php echo $ave; ?></h1>
                             </th>
                         </tr>
                         <tr>
                             <th colspan="2">
                                 <h3>QMS DATA</h3>
                             </th>
                         </tr>
                         <?php 
                         $qms_data = $vd->qms_data($date_scan,$data_line['line_code'], $data_line['dept_code']);
                         //CEK JIKA TIDAK ADA DATA QMS
                         if (count($qms_data) == '') {
                            $qms_total = 0; 
                            $qms_pass = 0;
                            $qms_rework = 0;
                         }else{
                         foreach ($qms_data as $qms) {
                            $qms_total = $qms['pass']+$qms['rework']; 
                            $qms_pass = $qms['pass']; 
                            $qms_rework = $qms['rework']; 
                            }
                        }
                          ?>
                         <tr>
                             <th>
                                 <h4 class="text-sm-center">QC</h4>
                             </th>
                             <th>
                                 <h4 class="text-sm-center">Pass</h4>
                             </th>
                         </tr>
                         <tr>
                            <th rowspan="4">
                                <h1 class="text-sm-center" style=" font-size: 60px;"><?php echo $qms_total; ?></h1>
                            </th>
                            <th>
                                <h1 class="text-sm-center" style=" font-size: 50px;"><?php echo $qms_pass; ?></h1>
                            </th> 
                         </tr>
                         <tr>
                             <th>
                                 <h4 class="text-sm-center">REWORK</h4>
                             </th>
                         </tr>
                         <tr>
                             <th>
                                 <h1 class="text-sm-center" style=" font-size: 50px;"><?php echo $qms_rework; ?></h1>
                             </th>
                         </tr>
                    </table>
                </div>
            </div>
        <!-- Outer data Real database -->
        <div class="outeraing">
            <?php 
            if ($day == 'Friday') {
                $query_get_hours = $db->get_jumat_pagi($date_scan,$data_line['line_code'],$data_line['dept_code']);
                $query_friday = $db->get_jumat_siang($date_scan,$data_line['line_code'],$data_line['dept_code']);
                $ot_query = $db->get_overtime_jumat($date_scan,$data_line['line_code'],$data_line['dept_code']); 
            }else{
                $query_get_hours = $db->get_oph($date_scan,$data_line['line_code'],$data_line['dept_code']);
                $ot_query = $db->get_overtime($date_scan,$data_line['line_code'],$data_line['dept_code']);
            }

            $cek_oph = $db->cek_data_oph($date_scan,$data_line['line_code'],$data_line['dept_code']);
            if ($cek_oph != 0) {
                
                // GET JAM NORMAL
                $row_oph = 0;
                $jam = array();
                $qty_in = array();
                $qty_out = array();
                while ($row_oph < count($query_get_hours)) {
                    foreach ($query_get_hours[$row_oph] as $key => $value) {
                        if ($row_oph == 0) {
                            $jam[] = $key;
                            $qty_in[] = round($value);
                        }else{
                            $qty_out[] = round($value);
                        }
                    }
                    $row_oph++;
                }
                
                $jml_jam = count($jam);
                $n = 0;
                while ($n < $jml_jam) {
                    if ($jam[$n] == '11:00' && $jam[$n+1] == '12:00') {
                        $qty_rest_in = $qty_in[$n] + $qty_in[$n+1];
                        $qty_rest_out = $qty_out[$n] + $qty_out[$n+1];
                        ?>
                            <div class="col-3 ">
                                <div class="card text-white <?php if($qty_out[$n] < $data_line['target'] ){
                                      ?> bg-danger 
                                      <?php }elseif($qty_out[$n] > $data_line['target'] ){ ?> bg-success <?php }else{ ?> bg-flat-color-5 <?php } ?>">
                                    <div class="card-header">   
                                        <h4 class="text-sm-center text-white" style="font-size: 30px;"><?php echo $jam[$n]." - ".date('H:i',strtotime('+1 hours',strtotime($jam[$n]))); ?></h4>
                                    </div>
                                    <div class="card-body text-sm-center" style="height: 190px; background-image: url('images/bg-card-body.png'); background-repeat: no-repeat;">
                                            <h2 class="count" style="font-size: 50px;">
                                                <?php 
                                                    if ($qty_rest_in != '') {
                                                        echo $qty_rest_in;
                                                    }else{
                                                        echo "0";
                                                    }
                                                 ?>
                                            </h2>
                                            <hr>
                                            <h1 class="count" style="font-size: 50px;">
                                                <?php 
                                                    if ($qty_rest_out != '') {
                                                        echo $qty_rest_out;
                                                    }else{
                                                        echo "0";
                                                    }
                                                 ?>
                                            </h1>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }elseif ($jam[$n] == '12:00') {
                        echo "<input type='hidden'>";
                    }else{
                        ?>
                            <div class="col-3 ">
                                <div class="card text-white <?php if($qty_out[$n] < $data_line['target'] ){
                                      ?> bg-danger 
                                      <?php }elseif($qty_out[$n] > $data_line['target'] ){ ?> bg-success <?php }else{ ?> bg-flat-color-5 <?php } ?>">
                                    <div class="card-header">   
                                        <h4 class="text-sm-center text-white" style="font-size: 30px;"><?php echo $jam[$n]." - ".date('H:i',strtotime('+1 hours',strtotime($jam[$n]))); ?></h4>
                                    </div>
                                    <div class="card-body text-sm-center" style="height: 190px; background-image: url('images/bg-card-body.png'); background-repeat: no-repeat;">
                                            <h2 class="count" style="font-size: 50px;">
                                                <?php 
                                                    if ($qty_in[$n] != '') {
                                                        echo $qty_in[$n];
                                                    }else{
                                                        echo "0";
                                                    }
                                                ?>
                                            </h2>
                                            <hr>
                                            <h1 class="count " style="font-size: 50px;">
                                                <?php 
                                                    if ($qty_out[$n] != '') {
                                                        echo $qty_out[$n];
                                                    }else{
                                                        echo "0";
                                                    }
                                                 ?>
                                            </h1>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                    $n++;
                }
                
                // GET JAM SIANG HARI JUM'AT

                if ($day == 'Friday') {
                    $row_jumat_siang = 0;
                    $jam_siang = array();
                    $siang_in = array();
                    $siang_out = array();

                    while ($row_jumat_siang < count($query_friday)) {
                        foreach ($query_friday[$row_jumat_siang] as $key => $value) {
                            if ($row_jumat_siang == 0) {
                                $jam_siang[] = date('H:i',strtotime($key))." - ".date('H:i',strtotime('+1 hours',strtotime($key)));
                                $siang_in[] = round($value);
                            }else{
                                $siang_out[] = round($value);
                            }
                        }
                        $row_jumat_siang++;
                    }
                    $k = 0;
                    while ($k < count($jam_siang)) {
                        ?>
                            <div class="col-3 ">
                                <div class="card text-white <?php if($siang_out[$k] < $data_line['target'] ){
                                      ?> bg-danger 
                                      <?php }elseif($siang_out[$k] > $data_line['target'] ){ ?> bg-success <?php }else{ ?> bg-flat-color-5 <?php } ?>">
                                    <div class="card-header">   
                                        <h4 class="text-sm-center text-white" style="font-size: 30px;"><?php echo $jam_siang[$k]; ?></h4>
                                    </div>
                                    <div class="card-body text-sm-center" style="height: 190px; background-image: url('images/bg-card-body.png'); background-repeat: no-repeat;">
                                        <h2 class="count" style="font-size: 50px;">
                                        <?php 
                                            if ($siang_in[$k] != '') {
                                                echo $siang_in[$k];
                                            }else{
                                                echo "0";
                                            }
                                         ?>
                                        </h2>
                                        <hr>
                                        <h1 class="count" style="font-size: 50px;">
                                        <?php 
                                            if ($siang_out[$k] != '') {
                                                echo $siang_out[$k];
                                            }else{
                                                echo "0";
                                            }
                                         ?>
                                         </h1>
                                    </div>
                                </div>
                            </div>
                        <?php
                        $k++;
                    }
                }
                

                // GET JAM OVERTIME
                $row_ot = 0;
                $array_judul = array();
                $array_ot_in = array();
                $array_ot_out = array();
                while ($row_ot < count($ot_query)) {
                    foreach ($ot_query[$row_ot] as $jam_ot => $qty_ot) {
                        if ($row_ot == 0) {
                            $array_judul[] = date('H:i',strtotime($jam_ot))." - ".date('H:i',strtotime('+1 hours',strtotime($jam_ot)));
                            if ($qty_ot == '') {
                                $array_ot_in[] = 0;
                            }else{
                                $array_ot_in[] = round($qty_ot);
                            }
                        }else{
                            if ($qty_ot == '') {
                                $array_ot_out[] = 0;
                            }else{
                                $array_ot_out[] = round($qty_ot);
                            }
                        }
                    }
                    $row_ot++;
                }
                
                //GET LAST OVERTIME WHEN VALUE IS NOT NULL
                $checked = $array_ot_out[count($array_ot_out)-1];
                $must_unset = false;
                for ($y = count($array_ot_out)-1; $y >= 0 ; $y--) { 
                    if ($array_ot_out[$y] == 0) {
                        if($checked == 0){
                            $must_unset = true;
                        }
                        if ($must_unset) {
                            unset($array_ot_out[$y]);
                            unset($array_ot_in[$y]);
                            unset($array_judul[$y]);
                            $must_unset = false;
                        }
                    }else{
                        $checked = $array_ot_out[$y];
                    }
                }

                $l = 0;
                $jml_ot = count($array_judul);
                while ( $l < $jml_ot) {
                        ?>
                        <div class="col-3 ">
                            <div class="card text-white <?php if($array_ot_out[$l] < $data_line['target'] ){
                                  ?> bg-danger 
                                  <?php }elseif($array_ot_out[$l] > $data_line['target'] ){ ?> bg-success <?php }else{ ?> bg-flat-color-5 <?php } ?>">
                                <div class="card-header">   
                                    <h4 class="text-sm-center text-white" style="font-size: 30px;"><?php echo $array_judul[$l]; ?></h4>
                                </div>
                                <div class="card-body text-sm-center" style="height: 190px; background-image: url('images/bg-card-body.png'); background-repeat: no-repeat;">
                                    <h2 class="count" style="font-size: 50px;"><?php echo $array_ot_in[$l]; ?></h2>
                                    <hr>
                                    <h1 class="count" style="font-size: 50px;"><?php echo $array_ot_out[$l]; ?></h1>
                                </div>
                            </div>
                        </div>
                        <?php
                    $l++;
                }
                
                
            }else{
                $i = 1;
                $start = strtotime('08:00');
                while ($i <= 8) {
                    ?>
                    <div class="col-3 ">
                        <div class="card text-white bg-danger">
                            <div class="card-header">   
                                <h4 class="text-sm-center text-white" style="font-size: 30px;"><?php echo date('H:i', $start)." - ".date('H:i', strtotime('+1 hours', $start)); ?></h4>
                            </div>
                            <div class="card-body" style="height: 190px; background-image: url('images/bg-card-body.png'); background-repeat: no-repeat;">
                                <h2 class="text-sm-center" style="font-size: 50px;">0</h2>
                                <hr>
                                <h1 class="text-sm-center count" style="font-size: 50px;">0</h1>
                            </div>
                        </div>
                    </div>
                    <?php
                    
                    $start = strtotime('+1 hours', $start);
                    if ($start == strtotime('12:00')) {
                        $start = strtotime('13:00');
                    }
                    $i++;
                }
            }
            
             ?>
            
        </div> <!-- end outeraing -->
                </div> <!-- end carousel item -->
                <?php
                $j++;

                }
                //CEK JIKA TIDAK ADA RETRUN DARI DATABASE
            }else{
                include('empty/oph.php');
            }
             ?>
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

        setTimeout(function(){window.location='pph.php';},60000);

        });
    </script>
</html>
