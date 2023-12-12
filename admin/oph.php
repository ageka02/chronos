<?php 
require '../lib/Database.php';
require '../lib/Qms_D.php';
date_default_timezone_set('Asia/Jakarta');

session_start();
if (!isset($_SESSION['user'])) {
   header('location: .'); 
}
$db = new Database();
$vd = new Qms_DB();
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Detail output per Hours | Chronos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../images/faviconku.png">

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../vendors/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"  media="screen">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style type="text/css">
        .outer_oph {
            /*white-space: nowrap;*/
            position: relative !important;
            overflow-x: auto;
            overflow-y: auto;
            /*-webkit-overflow-scrolling: touch;*/
            height: 518px;
            margin-bottom: 5px;    
        }
        .view_modol{
            cursor:pointer;
        }
    </style>
</head>
<body>
    <!-- left panel -->
    <?php include '../template/leftpanel.php'; ?>
    <!-- end left panel -->
    <!-- right panel -->
    <div id="right-panel" class="right-panel">
        <?php include '../template/header.php'; ?>
        <div class="card-body card-block collapse" id="tampilaku">
            <form action="" method="post" class="form-inline needs-validation">
                <div class="form-group has-success px-2">
                    <select name="building" id="selectbuilding" class="form-control" required>
                       <!--  <option value="3001">Building A</option>
                        <option value="3009">Building B</option>
                        <option value="3015">Building C</option> -->
                        <option value="Line%">-- Select Line --</option>
                        <?php                             
                           $gedung = $db->get_building();
                            foreach ($gedung as $data_gedung) {
                                ?>
                                <option value="<?php echo $data_gedung['gedung']; ?>"><?php echo $data_gedung['gedung']; ?></option>
                                <?php
                            }
                         ?>
                    </select>
                </div>
                <div class="form-group has-success">
                    <select name="dept" id="select" class="form-control" required>
                        <option value="121-%">-- Select Process --</option>
                        <option value="121-CP1" required>Cutting</option>
                        <option value="121-ST1" required>Stitching</option>
                        <option value="121-AS1" required>Assembly</option>
                    </select>
                </div>
                <div class="form-group has-success">
                    <label class="control-label px-1">Date :</label>
                    <div class="input-group input-append date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field = "dtp_input2" data-link-format="yyyy-mm-dd">
                        <input type="text" class="form-control" required placeholder="Select date here" value="<?php echo date('d F Y'); ?>">
                        <span class="add-on input-group-addon" data-toggle="tooltip" data-placemnet="bottom" title="Clear date">
                            <i class="fa fa-remove" style="color: red;"></i>
                        </span>
                        <span class="input-group-addon add-on">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                    <input type="hidden" id="dtp_input2" name="tgl" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group px-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fa fa-dot-circle-o"></i> Submit
                    </button>
                </div>
            </form>
        </div>
        <?php 
            $building = '';
            $dept = '';
            $date_scan = '';
            if (isset($_POST['dept']) AND isset($_POST['tgl'])) {
                $building = $_POST['building'];
                $dept = $_POST['dept'];
                $date_scan = $_POST['tgl'];
                $topku = '';
                $cek_target = $db->cek_value($date_scan,$building);
            }elseif(isset($_GET['dept']) AND isset($_GET['tgl'])){
                $building = $_GET['building'];
                $dept = $_GET['dept'];
                $date_scan = $_GET['tgl'];
                $topku = '';
                $cek_target = $db->cek_value($date_scan,$building);
            }else{
                $building = 'Line%';                
                $dept = '121-ST1';
                $date_scan = date('Y-m-d');
                $topku = 'TOP 6';
                $cek_target = $db->cek_value($date_scan,$building);
            }

            //rename line
            if ($building == 'Line%' or $building == '%' ) {
                $buildingaing = "All Line";
            }else{
                $buildingaing = $building;
            }
            ?>
                    <div class="breadcrumbs">
                        <div class="col-sm-8">
                            <h4 style=""> <i class="fa fa-list bg-primary p-3 text-light"></i> Output Per Hours Status <?php echo $buildingaing; ?></h4>
                        </div>
                        <div class="col-sm-4">
                            <div class="page-header float-right">
                                <h1><?php echo date('l, d F Y',strtotime($date_scan)); ?></h1>
                            </div>
                        </div>
                    </div>

                    <div class="content mt-3"> 
                        <?php 
                        if ($cek_target != 0) {
                            $sql_line = $db->select_line_scan($date_scan,$building,$dept);
                        }else{
                            //GET LAST INSERT LINE TARGET
                            
                            // $buildinglast = $building;
                            $buildinglast = 'Line1';
                            $last_target = $db->last_target($date_scan,$buildinglast,$dept);
                            $sql_line = $db->select_line_scan($last_target['date'],$building,$dept);
                        }
                            // echo $last_target;
                            // echo $sql_line;
                        
                        foreach ($sql_line as $data_line) {
                            //rename dept
                            if ($data_line['dept_code'] == '121-ST1') {
                                $deptaing = "Stitching";
                            }elseif ($data_line['dept_code'] == '121-CP1') {
                                $deptaing = "Cutting";
                            }elseif ($data_line['dept_code'] == '121-AS1') {
                                $deptaing = "Assembly";
                            }elseif ($data_line['dept_code'] == '121-SC0') {
                                $deptaing = "SUBCONT";
                            }
                            ?>
                                <div class="col-sm-4 col-sm-3 col-lg-3">
                                    <div class="card" style="height: 500px;">
                                        <div class="card-header user-header alt bg-dark">
                                            <div class="text-sm-center text-white">
                                                <h1 id="<?php echo $data_line['line_code']; ?>"><?php echo $data_line['line_code']; ?></h1>
                                            </div>
                                            <div class="text-sm-center text-white card-title">
                                                <strong class="mb-3">
                                                    <?php echo $deptaing; ?>
                                                </strong>
                                            </div>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <span class="text-sm-center">
                                                    <h1><?php echo $data_line['target']; ?></h1>
                                                    <h5>Target Per Hours</h5>
                                                </span>
                                            </li>
                                            <li class="list-group-item">
                                                <span class="text-sm-center">
                                                    <h1><?php $daily_target = $data_line['target']*8; echo $daily_target; ?></h1>
                                                    <h5>Daily Target</h5>
                                                </span>
                                            </li>
                                            <li class="list-group-item">
                                                <span class="text-sm-center">
                                                    <h1>
                                                        <?php 
                                                        $qty = $db->get_actual($date_scan,$data_line['line_code'],$data_line['dept_code']);
                                                        if($qty != ''){
                                                            $actual = round($qty['qty']);
                                                            $percentage = ($actual/$daily_target)*100;
                                                        }else{
                                                            $percentage = 0;
                                                        }
                                                        echo round($percentage,2)."%";
                                                        ?>
                                                    </h1>
                                                    <h5>Actual Output</h5>
                                                </span>
                                            </li>
                                            <?php 
                                                $qms_data = $vd->qms_data($date_scan,$data_line['line_code'], $data_line['dept_code']); 
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
                                                <li class="list-group-item view_modol" style="background-color: #d8dfeb;" id="<?php echo $date_scan.$data_line['dept_code'].$data_line['line_code']; ?>" >
                                                    <span class="text-sm-center">
                                                        <h1><?php echo $qms_total; ?></h1>
                                                        <h5>QC</h5>
                                                    </span>
                                                    <hr>
                                                    <div class="col-sm-6">
                                                        <span class="text-sm-center">
                                                            <h1><?php echo $qms_pass; ?></h1>
                                                            <h5>Pass</h5>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <span class="text-sm-center">
                                                            <h1><?php echo $qms_rework; ?></h1>
                                                            <h5>Rework</h5>
                                                    </div>
                                                </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="outer_oph">
                                    <?php 
                                        $day = date('l', strtotime($date_scan));
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
                                            $row_data = 0;
                                            $jam = array();
                                            $qty_in = array();
                                            $qty_out = array();
                                            while ($row_data < count($query_get_hours)) {
                                                foreach ($query_get_hours[$row_data] as $key => $value) {
                                                    if ($row_data == 0) {
                                                        $jam[] = $key;
                                                        $qty_in[] = round($value);
                                                    }else{
                                                        $qty_out[] = round($value);
                                                    }
                                                }
                                                $row_data++;
                                            }
                                             
                                            $jml_jam = count($jam);
                                            $n = 0;
                                            while ($n < $jml_jam) {
                                                if ($jam[$n] == '10:30' && $jam[$n+1] == '11:30') {
                                                    $qty_rest_in = $qty_in[$n] + $qty_in[$n+1];
                                                    $qty_rest_out = $qty_out[$n] + $qty_out[$n+1];
                                                    ?>
                                                        <div class="col-sm-6 col-lg-3 col-md-3">
                                                            <div class="card text-white <?php if($qty_out[$n] < $data_line['target'] ){ ?> bg-flat-color-4 <?php }
                                                                elseif($qty_out[$n] > $data_line['target'] ){ ?>bg-flat-color-1<?php }else{ ?> bg-flat-color-5 <?php } ?>" >

                                                                <div class="card-header card-header-adayed">   
                                                                    <h6 class="text-sm-center text-white">
                                                                        <?php echo $jam[$n]." - ".date('H:i',strtotime('+1 hours',strtotime($jam[$n]))); ?>
                                                                    </h6>
                                                                </div>
                                                                <div class="card-body card-body-adayed text-sm-center" >
                                                                    <h2 class="count">
                                                                        <?php 
                                                                            if ($qty_rest_in != '') {
                                                                                echo $qty_rest_in;
                                                                            }else{
                                                                                echo "0";
                                                                            }
                                                                         ?>                    
                                                                    </h2>
                                                                    <hr>
                                                                    <h2 class="count">
                                                                        <?php 
                                                                            if ($qty_rest_out != '') {
                                                                                echo $qty_rest_out;
                                                                            }else{
                                                                                echo "0";
                                                                            }
                                                                         ?>
                                                                     </h2>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                }elseif ($jam[$n] == '11:30') {
                                                    echo "<input type='hidden'>";
                                                }else{
                                                    ?>
                                                        <div class="col-sm-6 col-lg-3 col-md-3" >
                                                            <div class="card text-white <?php if($qty_out[$n] < $data_line['target'] ){ ?> bg-flat-color-4 <?php }
                                                                elseif($qty_out[$n] > $data_line['target'] ){ ?>bg-flat-color-1<?php }else{ ?> bg-flat-color-5 <?php } ?>" >

                                                                <div class="card-header card-header-adayed">   
                                                                    <h6 class="text-sm-center text-white">
                                                                        <?php echo $jam[$n]." - ".date('H:i',strtotime('+1 hours',strtotime($jam[$n]))); ?>
                                                                        <!-- <span class="badge badge-success float-right"> <i class="fa fa-clock-o"></i> </span> -->
                                                                    </h6>
                                                                </div>
                                                                <div class="card-body card-body-adayed text-sm-center" >
                                                                    <h2 class="count">
                                                                       <?php 
                                                                           if ($qty_in[$n] != '') {
                                                                               echo $qty_in[$n];
                                                                           }else{
                                                                               echo "0";
                                                                           }
                                                                       ?>                    
                                                                    </h2>
                                                                    <hr>
                                                                    <h2 class="count">
                                                                        <?php 
                                                                            if ($qty_out[$n] != '') {
                                                                                echo $qty_out[$n];
                                                                            }else{
                                                                                echo "0";
                                                                            }
                                                                         ?>
                                                                    </h2>
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
                                                        <div class="col-sm-6 col-lg-3 col-md-3">
                                                            <div class="card text-white <?php if($siang_out[$k] < $data_line['target'] ){ ?>bg-flat-color-4<?php }
                                                                elseif($siang_out[$k] > $data_line['target'] ){ ?>bg-flat-color-1<?php }else{ ?>bg-flat-color-5 <?php } ?>">

                                                                <div class="card-header card-header-adayed">   
                                                                    <h6 class="text-sm-center text-white">
                                                                        <?php echo $jam_siang[$k]; ?>
                                                                    </h6>
                                                                </div>
                                                                <div class="card-body card-body-adayed text-white text-sm-center">
                                                                    <h2 class=" count">
                                                                    <?php 
                                                                        if ($siang_in != '') {
                                                                            echo $siang_in[$k];
                                                                        }else{
                                                                            echo "0";
                                                                        }
                                                                     ?>
                                                                     </h2>
                                                                     <hr>
                                                                     <h2 class=" count">
                                                                    <?php 
                                                                        if ($siang_out != '') {
                                                                            echo $siang_out[$k];
                                                                        }else{
                                                                            echo "0";
                                                                        }
                                                                     ?>
                                                                     </h2>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    $k++;
                                                }
                                            }
                                            //tutup if jumat ^^

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
                                                    <div class="col-sm-6 col-lg-3 col-md-3">
                                                        <div class="card text-white <?php if($array_ot_out[$l] < $data_line['target'] ){
                                                              ?> bg-flat-color-4
                                                              <?php }elseif($array_ot_out[$l] > $data_line['target'] ){ ?> bg-flat-color-1 <?php }else{ ?> bg-flat-color-5 <?php } ?>">

                                                            <div class="card-header card-header-adayed">   
                                                                <h6 class="text-sm-center"><?php echo $array_judul[$l]; ?></h6>
                                                            </div>
                                                            <div class="card-body card-body-adayed text-white text-sm-center">
                                                                <h2 class=" count"><?php echo $array_ot_in[$l]; ?></h2>
                                                                <hr>
                                                                <h2 class=" count"><?php echo $array_ot_out[$l]; ?></h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php                                            
                                            $l++;
                                        } 
                                            //JIKA TIDAK ADA RETURN OPH DARI DATABASE
                                        }else{
                                            $i = 1;
                                            $start = strtotime('07:30');
                                            while ($i <= 8) {
                                            ?>
                                            <div class="col-sm-6 col-lg-3 col-md-3 ">
                                                            <div class="card text-white bg-flat-color-4 ">
                                                                <div class="card-header card-header-adayed">
                                                                    <h6 class="text-sm-center text-white" class=" "><?php echo date('H:i', $start)." - ".date('H:i', strtotime('+1 hours', $start)); ?></h6>
                                                                </div>
                                                                <div class="card-body card-body-adayed text-white text-sm-center">
                                                                    <h2 class="count">0</h2>
                                                                    <hr>
                                                                    <h2 class="count">0</h2>
                                                                </div>
                                                            </div>
                                                        </div>
                                            <?php 
                                             $start = strtotime('+1 hours', $start);
                                                                if ($start == strtotime('11:30')) {
                                                                    $start = strtotime('12:30');
                                                                }
                                                                $i++;
                                                            }
                                        }
                                     ?>
                                </div>
                                <!-- End Outer_OPH -->
                                <div class="col">
                                    <div class="card adicardver">
                                        <div class="weather-category" title="Detail Actual & Balance">
                                            <a target="_blank" href="balance.php?line=<?php echo $data_line['line_code']; ?>&dept=<?php echo $data_line['dept_code']; ?>&tgl=<?php echo $date_scan; ?>">
                                                <ul>
                                                    <li>
                                                        <h3><?php echo $actual; ?></h3>
                                                        Actual Output
                                                    </li>
                                                    <li>
                                                        <h3>
                                                            <?php 
                                                            $today = date('Y-m-d');
                                                            $sql_last_scan = $db->get_last_scan($date_scan,$data_line['line_code'],$data_line['dept_code']);
                                                            $get_last = $sql_last_scan['last'];
                                                            // CEK NAMA HARI
                                                            if ($day != 'Friday') {
                                                                if ($date_scan != $today) {
                                                                    if (strtotime($get_last) <= strtotime('12:30')) {
                                                                        $diff = strtotime($get_last) - strtotime('06:30');
                                                                    }else{
                                                                        $diff = strtotime($get_last) - strtotime('07:30');
                                                                    }
                                                                }else{
                                                                     if (strtotime(date('H:i')) < strtotime('11:30')) {
                                                                        $diff = strtotime(date('H:i')) - strtotime('06:30');
                                                                    }elseif (strtotime(date('H:i')) >= strtotime('11:30') && strtotime(date('H:i')) <= strtotime('16:30')) 
                                                                    {
                                                                        $diff = strtotime(date('H:i')) - strtotime('07:30');
                                                                    }else{
                                                                    $diff = strtotime($get_last) - strtotime('07:30');
                                                                    }
                                                                }
                                                                $selisih = floor($diff/(60*60));
                                                                if ($selisih == 0) {
                                                                    echo round($actual/1);
                                                                }else{
                                                                    echo round($actual/$selisih);
                                                                }
                                                            }else{
                                                                // KONDISIKAN UNTUK HARI JUM'AT
                                                                if ($date_scan != $today) {
                                                                    if (strtotime($get_last) <= strtotime('12:00')) {
                                                                        $diff = strtotime($get_last) - strtotime('06:30');
                                                                    }else{
                                                                        $diff = strtotime($get_last) - strtotime('08:00');
                                                                    }
                                                                }else{
                                                                    if (strtotime(date('H:i')) < strtotime('11:30')) {
                                                                        $diff = strtotime(date('H:i')) - strtotime('06:30');
                                                                    }elseif (strtotime(date('H:i')) >= strtotime('11:30') && strtotime(date('H:i')) <= strtotime('17:00')) {
                                                                        $diff = strtotime(date('H:i')) - strtotime('08:00');
                                                                    }else{
                                                                        $diff = strtotime($get_last) - strtotime('08:30');
                                                                    }                        
                                                                }
                                                                $selisih = floor($diff/(60*60));                                                                
                                                                if ($selisih == 0) {
                                                                    echo round($actual/1);
                                                                }else{
                                                                    echo round($actual/$selisih);
                                                                }
                                                                // echo "sel : ".$selisih."<br>";
                                                            }
                                                             ?>
                                                        </h3>
                                                        Average Per Hours
                                                    </li>
                                                    <li>
                                                        <h3><?php echo $actual-$daily_target; ?></h3>
                                                        Balance
                                                    </li>
                                                </ul>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                         ?>
                     
                    </div>
                    <!-- End content mt-3 -->
        <div class="text-center"><p class="refreshaing" style=""></p></div>
    </div>
    <!-- end right panel -->
    <div id="backtop" class="backtop">&#9650;</div>
    <!-- <div class=" btn-ngapung">
        <span>
        &nbsp;&nbsp;<a href="<?php //echo '#'.$building; ?>"><?php //echo $buildingaing; ?></a> | 
    </span>
    </div> -->
</body>
    <script type="text/javascript" src="../vendors/jquery/dist/jquery.min.js" ></script>
    <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../vendors/float-panel/float-panel.js"></script>
    <script type="text/javascript" src="../vendors/bootstrap-datetimepicker/js/bootstrap-datetimepicker1.min.js" charset="UTF-8"></script>
    <!-- <script src="../vendors/chart.js/dist/Chart.bundle.min.js"></script> -->
    <!-- <script src="../vendors/chartjs-plugin-datalabels.min.js"></script> -->
    <script src="../assets/js/main.js"></script>
    
    <script>
        <?php //CATATAN BIAR GA LUPA  ::  60000 milliseconds = 60 seconds = 1 minute. ?>  
    function addZero(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    }
            
    // var time = new Date().getTime();
    var d = new Date();
    var h = addZero(d.getHours());
    var m = addZero(d.getMinutes());
    var s = addZero(d.getSeconds());
    
    // jQuery(document.body).bind("mousemove keypress", function(e) {
    //      time = new Date().getTime();        
    //  });

     // function refresh() {
     //     if(new Date().getTime() - time >= 60000) 
     //         window.location.reload(true);
     //     else 
     //         setTimeout(refresh, 10000);
     // }
     // setTimeout(refresh, 10000);
     setTimeout(function(){
        window.location.reload(1);
     }, 300000);
     jQuery( ".refreshaing" ).append( "Last Update :"+h + ":" + m + ":" + s );
     // jQuery('.testku').text(h);
    </script>

    <script type="text/javascript">
        jQuery('.form_date').datetimepicker({
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            pickerPosition: "bottom-left"         
        });
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
    // jQuery(document).ready(function(){
    //     jQuery(document).on('click','.show_more',function(){
    //         var building = '<?php //echo $building; ?>';
    //         var dept = '<?php //echo $dept; ?>';
    //         var date_scan = '<?php //echo $date_scan; ?>';
    //         jQuery('.show_more').hide(); 
    //         jQuery('.loding').show();           
    //         jQuery.ajax({
    //             type:'POST', 
    //             url:'chart_tes.php',
    //             data:'building='+building+'&dept='+dept+'&tgl='+date_scan,
    //             success:function(html){
    //                 jQuery('.loding').hide();
    //                 jQuery('.loadchart').append(html);
    //             }
    //         });

    //     });
    // });
    </script>

</html>

<div id="ModalView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelv" aria-hidden="true" data-backdrop="static">

</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
    jQuery(".view_modol").click(function(e) {
       var ih = jQuery(this).attr("id");
        jQuery.ajax({
              url: "reworkstatus.php",
              type: "GET",
              data : {id: ih}, 
              success: function (ajaxData){
                jQuery("#ModalView").html(ajaxData);
                jQuery("#ModalView").modal('show');

              }
            });
         });
    });
</script>