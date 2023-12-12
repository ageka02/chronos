<?php 
// require '../lib/connection.php';
require '../lib/Database.php';
require '../lib/VD_D.php';
date_default_timezone_set('Asia/Jakarta');
session_start();
if (!isset($_SESSION['user'])) {
   header('location: .');
}
$db = new Database();
$vd = new Vd_d();
 ?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Chart OPH | Chronos</title>
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
                    <select name="building" id="select" class="form-control" required>
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
                $dept = '121-AS1';
                $date_scan = date('Y-m-d');
                // $date_scan = date('2020-04-15');
            }
            

            ?>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Chart Output Per Hours</h1>
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
            $cek_target = $db->cek_value($date_scan,$building);

            $j = 1;
             if ($cek_target != 0) {
                 $sql_line = $db->select_line_scan($date_scan,$building,$dept);
             }else{
                 //GET LAST INSERT LINE TARGET
                 $buildinglast = "Line1";
                 $last_target = $db->last_target($date_scan,$buildinglast,$dept);
                 $sql_line = $db->select_line_scan($last_target['date'],$building,$dept);
             }

             foreach ($sql_line as $data_line) {
                if ($data_line['dept_code'] == '121-ST1') {
                    $deptaing = "Stitching";
                }elseif ($data_line['dept_code'] == '121-CP1') {
                    $deptaing = "Cutting";
                }elseif ($data_line['dept_code'] == '121-SC0') {
                    $deptaing = "Subcon Out";
                }elseif ($data_line['dept_code'] == '121-SC1') {
                    $deptaing = "Subcon In(receipt)";
                }elseif ($data_line['dept_code'] == '121-PT1') {
                    $deptaing = "Rubber";
                }elseif ($data_line['dept_code'] == '121-DS1') {
                    $deptaing = "Stockfit";
                }elseif ($data_line['dept_code'] == '121-PRE') {
                    $deptaing = "Supermarket Central";
                }elseif ($data_line['dept_code'] == '121-FGD') {
                    $deptaing = "Finish Good";
                }elseif ($data_line['dept_code'] == '121-AS1') {
                    $deptaing = "Assembly";
                }
             ?>
            <div class="col-lg-12">
                <div class="card" >
                    <!-- <div class="card-header"><h4>PPH</h4></div> -->
                    <div class="card-body">
                        <!-- <span class="badge badge-info"> ASSY</span> -->
                        <h4 class="text-center">Output Per Hours : <?php echo $data_line['line_code'].' '.$deptaing; ?></h4>
                        <canvas id="<?php echo 'oph'.$data_line['line_code'].$data_line['dept_code']; ?>" ></canvas>
                    </div>
                </div>
            </div>
            <?php 
                $j++;
                $data_id[] = $data_line['line_code'].$data_line['dept_code'];
                $data_line_arr[] = $data_line['line_code'];
                $data_dept[] = $data_line['dept_code'];
                $data_target[] = $data_line['target'];
             } 
            ?>
                    <!-- /# column -->
                <!-- </div> -->
        </div><!-- .content -->
        <div class="text-center"><p class="refreshaing"></p></div>
    </div>
    <!-- Right Panel -->
    <div id="backtop" class="backtop">&#9650;</div>
</body>
     <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../vendors/float-panel/float-panel.js"></script>
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
while ($k < count($data_id)) {
  

    $data_oph = $db->get_oph($date_scan,$data_line_arr[$k],$data_dept[$k]);
    //DATA OPH OT
    $data_ot = $db->get_overtime($date_scan,$data_line_arr[$k],$data_dept[$k]);
    $array_label = array();
    $array_ot = array();
    foreach ($data_ot[1] as $key => $value) {
        $array_label[] = $key;
        $array_ot[] = round($value);
    }
    
    //UNSET ARRAY OVERTIME
    $checked = $array_ot[count($array_ot)-1];
    $must_unset = false;
    for ($y = count($array_ot)-1; $y >= 0; $y--) {
        if ($array_ot[$y] == 0) {
            if ($checked == 0) {
                $must_unset = true;
            }
            if ($must_unset) {
                unset($array_label[$y]);
                unset($array_ot[$y]);
                $must_unset = false;
            }
        }else{
            $checked = $array_ot[$y];
        }
    }


    $label_temp = array();
    $oph_temp = array();


    //DATA OPH TEMP
    foreach ($data_oph[1] as $key => $value) {
        $label_temp[] = $key;
        $oph_temp[] = round($value);
    }
    //DATA OT LABEL
    foreach ($array_label as $value) {
        $label_temp[] = $value;
    }
    //DATA OT
    foreach ($array_ot as $value) {
        $oph_temp[] = $value;
    }


    //DATA OPH FINAL
    $oph_i = 0;
    $label = array();
    $oph = array();
    while ($oph_i < count($label_temp)) {
        if ($label_temp[$oph_i] == '10:30' && $label_temp[$oph_i+1] == '11:00') {
            $label[] = $label_temp[$oph_i];
            $oph[] = $oph_temp[$oph_i] + $oph_temp[$oph_i+1];
        }elseif($label_temp[$oph_i] != '11:30'){
            $label[] = $label_temp[$oph_i];
            $oph[] = $oph_temp[$oph_i];
        }

        $oph_i++;
    }
 ?>
<script>
var ctx = document.getElementById( "<?php echo 'oph'.$data_id[$k]; ?>" );
ctx.height = 65;
var myChart = new Chart( ctx, {
    plugins: [ChartDataLabels],
    type: 'bar',
    data: {
        labels: [ <?php foreach ($label as $jam) { echo "'".$jam." - ".date('H:i', strtotime('+1 hours', strtotime($jam)))."',"; } ?> ],
        datasets: [
            {
                type: 'line',
                label: "TARGET",
                data:[
                <?php 
                for ($i=0; $i < count($label) ; $i++) { 
                    echo $data_target[$k].",";
                }                  
                 ?>
                ],
                datalabels: {
                        display:false
                    }, 
                borderColor: "rgba(255, 10, 90, 0.9)",
                borderWidth: 2,
                backgroundColor: "rgba(255, 10, 90, 0)",
                pointRadius: 1,
                pointStyle: 'line'
            },
            {
                label: "OPH",
                data:[
                <?php 
                foreach ($oph as $value) {
                    echo $value.",";
                }                    
                 ?>
                ],
                datalabels: {
                        align: 'end',
                        anchor: 'end',
                        backgroundColor: 'rgba(0,105,255,0.7)',
                        borderRadius: 4,
                        color: 'white',
                        font: {
                            weight: 'bold'
                        }
                    }, 
                borderColor: "rgba(0, 123, 255, 0.9)",
                borderWidth: 2,
                backgroundColor: "rgba(0, 123, 255, 0.5)",
                pointStyle: 'rect'
            }                    
            ]
    },
    options: {
        responsive: true,
        tooltips: {
            mode: 'index',
            titleFontSize: 12,
            cornerRadius: 3,
            intersect: false
        },
        scales: {
            xAxes: [ {
                scaleLabel: {
                    display: true,
                    labelString: 'Time'
                }
                    } ],
            yAxes: [ {
                scaleLabel: {
                    display: true,
                    labelString: 'Pairs'
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
 } ?>
</html>
