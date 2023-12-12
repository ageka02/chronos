<?php 
// require '../lib/connection.php';
require '../lib/Database.php';
date_default_timezone_set('Asia/Jakarta');
session_start();
if (!isset($_SESSION['user'])) {
   header('location: .');
}
$db = new Database();
 ?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="refresh" content="10"/> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Chart PPH | Chronos</title>
    <!-- <meta name="description" content="Sufee Admin - HTML5 Admin Template"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="../images/faviconku.png">

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendors/selectFX/css/cs-skin-elastic.css">
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
            <form action="" method="post" class="form-inline needs-validation">
                <div class="form-group has-success px-2">
                    <select name="building" id="select" class="form-control" required hidden="">
                        <option value="Line%">-- Select Building --</option>
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
                    <select name="dept" id="select" class="form-control" required hidden="">
                        <option value="121-%">-- Select Process --</option>
                        <option value="121-CP1" required>Cutting</option>
                        <option value="121-ST1" required>Stitching</option>
                        <option value="121-AS1" required>Assembly</option>
                    </select>
                </div>
                <div class="form-group has-success">
                    <label class="control-label px-1">Date :</label>
                    <div class="input-group input-append date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field = "dtp_input2" data-link-format="yyyy-mm-dd">
                        <input type="text" class="form-control" required placeholder="Select date here">
                        <span class="add-on input-group-addon" data-toggle="tooltip" data-placemnet="bottom" title="Clear date">
                            <i class="fa fa-remove" style="color: red;"></i>
                        </span>
                        <span class="input-group-addon add-on">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                    <input type="hidden" id="dtp_input2" name="tgl" value="">
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
            }else{
                $building = 'Line%';
                $dept = '121-%';
                $date_scan = date('Y-m-d');
                // $date_scan = date('2020-04-15');
            }
            if ($dept == '121-ST1') {
                $deptaing = "Stitching";
            }elseif ($dept == '121-CP1') {
                $deptaing = "Cutting";
            }elseif ($dept == '121-SC0') {
                $deptaing = "Subcon Out";
            }elseif ($dept == '121-SC1') {
                $deptaing = "Subcon In(receipt)";
            }elseif ($dept == '121-PT1') {
                $deptaing = "Rubber";
            }elseif ($dept == '121-DS1') {
                $deptaing = "Stockfit";
            }elseif ($dept == '121-PRE') {
                $deptaing = "Supermarket Central";
            }elseif ($dept == '121-FGD') {
                $deptaing = "Finish Good";
            }elseif ($dept == '121-AS1') {
                $deptaing = "Assembly";
            }else{
                $deptaing = "All Process";
            }

            ?>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Chart PPH</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <h1><?php echo date('l, d F Y',strtotime($date_scan)); ?></h1>
                </div>
            </div>
        </div>

        <div class="content mt-2">
            <!-- <div class="row"> -->
            <?php
            
            $query_pph = $db->canvas_pph($date_scan,$building,$dept);

            foreach ($query_pph as $canvas_pph) {
                if ($canvas_pph['dept_code'] == '121-ST1') {
                    $deptaings = "Stitching";
                }elseif ($canvas_pph['dept_code'] == '121-CP1') {
                    $deptaings = "Cutting";
                }elseif ($canvas_pph['dept_code'] == '121-SC0') {
                    $deptaings = "Subcon Out";
                }elseif ($canvas_pph['dept_code'] == '121-SC1') {
                    $deptaings = "Subcon In(receipt)";
                }elseif ($canvas_pph['dept_code'] == '121-PT1') {
                    $deptaings = "Rubber";
                }elseif ($canvas_pph['dept_code'] == '121-DS1') {
                    $deptaings = "Stockfit";
                }elseif ($canvas_pph['dept_code'] == '121-PRE') {
                    $deptaings = "Supermarket Central";
                }elseif ($canvas_pph['dept_code'] == '121-FGD') {
                    $deptaings = "Finish Good";
                }elseif ($canvas_pph['dept_code'] == '121-AS1') {
                    $deptaings = "Assembly";
                }
            ?>
            <div class="col-lg-12">
                <div class="card">
                    <!-- <div class="card-header"><h4>PPH</h4></div> -->
                    <div class="card-body">
                        <h4 class="text-center">Productivity Per Hours : <?php echo $deptaings; ?></h4>
                        <canvas id="<?php echo 'pph'.$canvas_pph['line_code'].$canvas_pph['dept_code']; ?>" ></canvas>
                    </div>
                </div>
            </div>
            <?php 
                $data_id[] = $canvas_pph['line_code'].$canvas_pph['dept_code'];
                $data_line_arr[] = $canvas_pph['line_code'];
                $data_dept[] = $canvas_pph['dept_code'];
             } 
            ?>
                    <!-- /# column -->
                <!-- </div> -->
        </div><!-- .content -->
        <div class="text-center"><p class="refreshaing"></p></div>
    </div><!-- /#right-panel -->
</body>
     <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../vendors/bootstrap-datetimepicker/js/bootstrap-datetimepicker1.min.js" charset="UTF-8"></script>
    <script src="../vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="../vendors/chartjs-plugin-datalabels.min.js"></script>
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
<!-- new line ya -->
<?php 
$k = 0;
$mh_normal = $mh_total = $pph_actual = $pph_persen = $date_pph = 0;
if (isset($data_id)) {
while ($k < count($data_id)) {
    $labelcht_pph = $db->get_pph($date_scan,$data_line_arr[$k],$data_dept[$k]);
    $datacht_pph = $db->get_pph($date_scan,$data_line_arr[$k],$data_dept[$k]);
// }

 ?>
<script>
var ctx = document.getElementById( "<?php echo 'pph'.$data_id[$k]; ?>" );
ctx.height = 70;
var myChart = new Chart( ctx, {
    plugins: [ChartDataLabels],
    type: 'bar',
    data: {
        labels: [ <?php foreach($labelcht_pph as $lbl_val){ echo "'".$lbl_val['line_code']."'".",";} ?> ],
        datasets: [
            {
                datalabels: {
                         display: false
                    }, 
                type: 'line',
                label: "TARGET",
                data: [ <?php 
                    for ($i=0; $i < count($labelcht_pph) ; $i++) { 
                        echo '100'.",";
                    }
                        ?> 
                    ],                
                backgroundColor: 'rgba(200,10,90,0)',
                borderColor: 'rgba(200,10,90,0.8)',
                borderWidth: 2,
                pointStyle: 'circle',
                pointRadius: 1,
                pointStyle: 'line'
            },
            {
                label: "PPH",
                data:[
                <?php 
                foreach ($datacht_pph as $data_val) {
                    $day = date('l', strtotime($date_scan));
                    $today = date('Y-m-d');
                    $sql_last_scan = $db->get_last_scan($date_scan,$data_val['line_code'],$data_val['dept_code']);
                    $get_last = $sql_last_scan['last'];
                    // CEK NAMA HARI
                    if ($day != 'Friday') {
                        if ($date_scan != $today) {
                            if (strtotime($get_last) >= strtotime('17:00')) {
                                $diff = strtotime('17:00') - strtotime('09:00');
                            }else{
                                $diff = strtotime('17:00') - strtotime('09:00');
                            }
                        }else{
                             if (strtotime(date('H:i')) < strtotime('12:00')) {
                                $diff = strtotime(date('H:i')) - strtotime('07:00');
                            }elseif (strtotime(date('H:i')) >= strtotime('12:00') && strtotime(date('H:i')) <= strtotime('17:00')) 
                            {
                                $diff = strtotime(date('H:i')) - strtotime('08:00');
                            }else{
                            $diff = strtotime('17:00') - strtotime('09:00');
                            }
                        }                                    
                        $selisih = floor($diff/(60*60));
                    }else{
                        // KONDISIKAN UNTUK HARI JUM'AT
                        if ($date_scan != $today) {
                            if (strtotime($get_last) <= strtotime('17:30')) {
                                $diff = strtotime('17:30') - strtotime('09:30');
                            }else{
                                $diff = strtotime('17:30') - strtotime('09:30');
                            }
                        }else{
                            if (strtotime(date('H:i')) < strtotime('12:00')) {
                                $diff = strtotime(date('H:i')) - strtotime('07:00');
                            }elseif (strtotime(date('H:i')) >= strtotime('12:00') && strtotime(date('H:i')) <= strtotime('17:30')) {
                                $diff = strtotime(date('H:i')) - strtotime('08:30');
                            }else{
                                $diff = strtotime('17:30') - strtotime('09:30');
                            }                        
                        }
                        $selisih = floor($diff/(60*60));                                                                
                    }
                    $overtime = $data_val['mp_overtime'] * $data_val['hours_overtime'];
                    $mh_normal = ($data_val['mp_actual']*$selisih);
                    $mh_total = $mh_normal + $overtime;
                    if ($mh_total == 0 ) {
                        $mh_totalku = 1;
                    }else{
                        $mh_totalku = $mh_total;
                    }
                    $mh_total = $mh_normal + $overtime;
                    $pph_actual = round($data_val['actual'])/$mh_totalku;
                    $pph_persen = (round($pph_actual,2)/round($data_val['pph_standard'],2))*100;
                    echo "'".round($pph_persen,2)."',";
                }
                 ?>
                ],
                datalabels: {
                        align: 'start',
                        anchor: 'end',
                        backgroundColor: 'rgba(0, 173, 142,0.7)',
                        borderRadius: 4,
                        color: 'white',
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            return  value + '%';
                        }
                    }, 
                borderColor: "rgba(0, 173, 142, 0.9)",
                borderWidth: 2,
                backgroundColor: "rgba(0, 173, 142, 0.5)"
            }                    
            ]
    },
    options: {
        tooltips: {
            mode: 'index',
            titleFontSize: 12,
            cornerRadius: 3,
            intersect: false
        },
        scales: {
            xAxes: [ {
                barPercentage: 0.4,
                scaleLabel: {
                    display: true,
                    labelString: 'Line'
                }
                    } ],
            yAxes: [ {
                scaleLabel: {
                    display: true,
                    labelString: 'PPH Percentage'
                },
                ticks: {
                    beginAtZero: true
                }
                            } ]
        },
        legend: {
            display: true,
            position: 'top',
            labels: {
                usePointStyle: true,
                fontFamily: 'Montserrat'
            }
        }
    }
} );
</script>
<?php
$k++;
 }
}else{
 ?>

<script>
var ctx = document.getElementById( "<?php echo 'pph'.$building.$dept; ?>" );
ctx.height = 65;
var myChart = new Chart( ctx, {
    plugins: [ChartDataLabels],
    type: 'bar',
    data: {
        labels: [ <?php for ($i=0; $i < 6 ; $i++) { 
                        echo $building.$i.",";
                    } ?> ],
        datasets: [
            {
                datalabels: {
                         display: false
                    }, 
                type: 'line',
                label: "TARGET",
                data: [ <?php 
                    for ($i=0; $i < 6 ; $i++) { 
                        echo '100'.",";
                    }
                        ?> 
                    ],                
                backgroundColor: 'rgba(200,10,90,0)',
                borderColor: 'rgba(200,10,90,0.8)',
                borderWidth: 2,
                pointStyle: 'circle',
                pointRadius: 1,
                pointStyle: 'line'
            },
            {
                label: "PPH",
                data:[  0,0,0,0,0,0 ],
                datalabels: {
                        align: 'start',
                        anchor: 'end',
                        backgroundColor: 'rgba(0, 173, 142,0.7)',
                        borderRadius: 4,
                        color: 'white',
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            return  value + '%';
                        }
                    }, 
                borderColor: "rgba(0, 173, 142, 0.9)",
                borderWidth: 2,
                backgroundColor: "rgba(0, 173, 142, 0.5)"
            }                    
            ]
    },
    options: {
        tooltips: {
            mode: 'index',
            titleFontSize: 12,
            cornerRadius: 3,
            intersect: false
        },
        scales: {
            xAxes: [ {
                barPercentage: 0.4,
                scaleLabel: {
                    display: true,
                    labelString: 'Line'
                }
                    } ],
            yAxes: [ {
                scaleLabel: {
                    display: true,
                    labelString: 'PPH Percentage'
                },
                ticks: {
                    beginAtZero: true
                }
                            } ]
        },
        legend: {
            display: true,
            position: 'top',
            labels: {
                usePointStyle: true,
                fontFamily: 'Montserrat'
            }
        }
    }
} );
</script>

<?php } ?>
</html>
