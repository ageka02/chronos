<?php 
require '../lib/Database.php';
date_default_timezone_set('Asia/Jakarta');
session_start();
if (!isset($_SESSION['user'])) {
   header('location: .');
}
 ?>
<?php
header('Cache-Control: no-cache, must-revalidate, max-age=0');
// if( sqlsrv_fetch( $stmt ) === false) {
//      die( print_r( sqlsrv_errors(), true));
// }
// $name = sqlsrv_get_field( $stmt, 4);

// $target = sqlsrv_get_field( $stmt, 8);

// while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
//       echo $row[0]."<br />";
// }
// sqlsrv_free_stmt( $stmt);
$db = new Database();
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
<style type="text/css">
.outeraing {
    /*white-space: nowrap;*/
    position: relative;
    overflow-x: auto;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    height: 640px;
}
::-webkit-scrollbar {
  width: 11px;
}
/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1; 
} 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #888; 
}
::-webkit-scrollbar-thumb:hover {
  background: #17a2b8; 
}
.adiver{
    transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
}
.adiver:hover{
     transform: translateY(-6px);
    -moz-transform: translateY(-6px);
    -webkit-transform: translateY(-6px);
}
.adiver div.card-header:active{
    /*background-color: #dc3545 !important; */
     transform: translateY(-6px);
    -moz-transform: translateY(-6px);
    -webkit-transform: translateY(-6px);
}
.adiver div.card-header:hover{
    border-left: 5px solid #dc3545;
    box-shadow : 0 0 15px rgba(220, 53, 69, 0.9);
}    
</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Summary Output Per Hours | Chronos</title>
    <!-- <meta name="description" content="Sufee Admin - HTML5 Admin Template"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="../apple-icon.png">
    <link rel="shortcut icon" href="../images/faviconku.png">

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/selectFX/css/cs-skin-elastic.css">
    <!-- <link rel="stylesheet" href="../vendors/jqvmap/dist/jqvmap.min.css"> -->
    <!-- <link rel="stylesheet" href="../vendors/bootstrap/dist/jqvmap.min.css"> -->    
    <link rel="stylesheet" href="../vendors/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"  media="screen">

<link rel="stylesheet" href="../assets/css/style.css">
    <!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'> -->
</head>

<body>
    <!-- Left Panel -->
    <?php include '../template/leftpanel.php'; ?>
    <!-- Left Panel -->
    <!-- Right Panel -->
    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <?php include '../template/header.php'; ?>
        <!-- Header-->
        <div class="card-body card-block collapse" id="tampilaku">
        <form action="" method="post" class="form-inline needs-validation" >   
        <div class=" form-group has-success px-2">
           <select name="building" id="select" class="form-control " required >
            <option value="Line%" > --Select Line --</option>
            <?php 
                $gedung = $db->get_building();
                foreach ($gedung as $data_gedung) {
             ?>
             <option value="<?php echo $data_gedung['gedung']; ?>"><?php echo $data_gedung['gedung']; ?></option>
         <?php } ?>
        </select>
         </div>         
            <div class=" form-group has-success">
           <select name="dept" id="select" class=" form-control" required >
            <option value="" > --Select Type --</option>
            <option value="121-AS1" required>Assembly</option>
            <option value="121-ST1" required>Stitching</option>
            <option value="121-CP1" required>Cutting</option>
            <!-- <option value="121-SC0" required>Subcon</option>
            <option value="121-PT1" required>Rubber</option>
            <option value="121-DS1" required>Stockfit</option>
            <option value="121-PRE" required>Supermarket Central</option>
            <option value="121-FGD" required>Finish Good</option> -->
            <!-- <option value="alldept" required>All</option> -->
        </select>
        <!-- <div class="invalid-feedback">Example </div> -->
         </div>
         <!-- <fieldset> -->
            <div class="form-group has-success">
                  <label class="control-label px-1">Date :</label>
                  <div class="input-group input-append date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                     <input type='text' class="form-control" required placeholder="select date here ...">
                     <span class="add-on input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Clear date"> <i class="fa fa-remove" style="color: red;"></i></span>
                     <span class="input-group-addon add-on"> <i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="hidden" id="dtp_input2" name="tgl" value="" >
                  <!-- <span class="error"> <?php //echo $tglErr; ?></span> -->
               </div>               
        <!-- </fieldset>  -->
         <div class="form-group px-2">
        <button type="submit" class="btn btn-info " >            
            <i class="fa fa-dot-circle-o"></i> Submit
        </button>
        </div>
        </form>
        </div>

<?php
$building = '';
$dept = '';
$date_scan = '';
if(isset($_POST["dept"]) and isset($_POST["tgl"])) {
    $building = $_POST['building'];
    $dept = $_POST['dept'];
    $date_scan = $_POST['tgl'];
    $cek_target = $db->cek_value($date_scan,$building);
}else{
    $building = 'Line%';
    $dept = '121-AS1';
    $date_scan = date('Y-m-d');
    $cek_target = $db->cek_value($date_scan,$building);
}

if ($building == 'Line%' or $building == '%' ) {
    $buildingaing = "All Line";
}else{
    $buildingaing = $building;
}
?>
        <div class="breadcrumbs">
            <div class="col-sm-8">   
                <i class="fa fa-list-alt bg-primary p-3 float-left text-light"></i>
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Building <?php echo $buildingaing; ?> </h1>
                    </div>                    
                </div>
            </div>
            <div class="col-sm-4">
                <div class="page-header float-right">
                    <div class="page-title">
                        <h1>
                            <?php 
                                $tgl_scan = strtotime($date_scan);
                                echo date('l, d F Y',$tgl_scan); 
                            ?>
                        </h1>
                    </div>                    
                </div>
            </div>
        </div>
<!-- SINI OYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYy-->
        <div class="content mt-3">   
            
    <!-- </div> -->
     <!-- <div class="outeraing"> -->
<?php 
$day = date('l', strtotime($date_scan));
if ($cek_target != 0) {
    $sql_line = $db->select_line_scan($date_scan,$building,$dept);
}else{
    //GET LAST INSERT LINE TARGET
    $buildinglast = 'Line1';
    $last_target = $db->last_target($date_scan,$buildinglast,$dept);
    $sql_line = $db->select_line_scan($last_target['date'],$building,$dept);
}


foreach ($sql_line as $data_line) {
    $daily_target = $data_line['target']*8;
    //hitung actual
    $qty = $db->get_actual($date_scan,$data_line['line_code'],$data_line['dept_code']);
    if($qty != ''){
        $actual = round($qty['qty']);
        $percentage = ($actual/$daily_target)*100;
    }else{
        $percentage = 0;
    }

//hitung average
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
    // echo round($actual/$selisih);
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
}
$averageperhours = round($actual/$selisih);

 ?>
            <div class="col-sm-6 col-lg-2 ">                
                <div class="card adiver "  style="height:320px;">
                    <a href="oph.php?dept=<?php echo $data_line['dept_code']; ?>&building=<?php echo substr($building, 0,1); ?>&tgl=<?php echo $date_scan; ?>#<?php echo $data_line['line_code']; ?>" style="color: black;" target="_BLANK">                    
                    <div class="card-header text-white <?php if($averageperhours < $data_line['target']){ ?>bg-flat-color-4<?php }elseif($averageperhours >= $data_line['target']){ ?> bg-flat-color-5 <?php } ?>" style="">
                        <h3 class=""> <?php echo $data_line['line_code']; ?> </h3>    
                         <span>
                        <?php 
                        if ($data_line['dept_code'] == '121-ST1') {
                            echo " Stitching";
                        }elseif ($data_line['dept_code'] == '121-CP1') {
                            echo " Cutting";
                        }elseif ($data_line['dept_code'] == '121-SC0') {
                            echo " Subcon Out";
                        }elseif ($data_line['dept_code'] == '121-SC1') {
                            echo " Subcon In(receipt)";
                        }elseif ($data_line['dept_code'] == '121-PT1') {
                            echo " Rubber";
                        }elseif ($data_line['dept_code'] == '121-DS1') {
                            echo " Stockfit";
                        }elseif ($data_line['dept_code'] == '121-PRE') {
                            echo " Supermarket Central";
                        }elseif ($data_line['dept_code'] == '121-FGD') {
                            echo " Finish Good";
                        }elseif ($data_line['dept_code'] == '121-AS1') {
                            echo " Assembly";
                        }
                        ?>                            
                        </span>
                    <span class="badge badge-light float-right" style=" font-size: 15px;">
                        <?php echo round($percentage,2) ."%"; ?>
                    </span>
                    </div>
                    </a>
                    <ul class="list-group list-group-flush ">
                        <li class="list-group-item  ">
                            Target/Hours
                            <span class=" pull-right" > 
                            <h5><?php echo $data_line['target']; ?></h5>
                            </span> 
                        </li>
                        <li class="list-group-item  ">
                            Target/Day
                            <span class=" pull-right" > 
                            <h5><?php echo $daily_target; ?></h5>
                            </span> 
                        </li>
                        <div class="text-sm-center">
                                <strong>X</strong>
                            </div>
                        <li class="list-group-item ">
                            Average/Hours
                            <span class="pull-right" > 
                            <h5 class="count"><?php  echo $averageperhours; ?></h5>
                            </span> 
                        </li>
                        <li class="list-group-item  ">
                            Actual/Day
                            <span class=" pull-right" > 
                            <h5 class="count"><?php echo $actual; ?></h5>
                            </span> 
                        </li>
                    </ul>
                </div>
            </div>
<?php 
} 
//end foreach
?>
    <!-- outeraing -->
        </div> <!-- .content -->
        <div class="text-center"><p class="refreshaing"></p></div>
    </div>
    <!-- Right Panel -->

</body>
    <script type="text/javascript" src="../vendors/jquery/dist/jquery.min.js" ></script>
    <!-- <script type="text/javascript" src="../vendors/jquery/dist/jquery.min.js"></script> -->
    <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    
    <script type="text/javascript" src="../vendors/bootstrap-datetimepicker/js/bootstrap-datetimepicker1.min.js" charset="UTF-8"></script>
    <!-- <script src="../vendors/chart.js/dist/Chart.bundle.min.js"></script> -->
    <!-- <script src="../vendors/jquery-3.4.1.js"></script> -->
    <!-- <script src="../assets/js/dashboard.js"></script> -->
    <!-- <script src="../assets/js/widgets.js"></script> -->
    <script src="../assets/js/main.js"></script>
    <script>
        // script copy dari file oph
    function addZero(i) {
      if (i < 10) { i = "0" + i; }
      return i;
    }
    var d = new Date();
    var h = addZero(d.getHours());
    var m = addZero(d.getMinutes());
    var s = addZero(d.getSeconds());

     setTimeout(function(){
        window.location.reload(1);
     }, 300000);
     jQuery( ".refreshaing" ).append( "Last Update :"+h + ":" + m + ":" + s );
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

</html>
