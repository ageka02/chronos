<?php 
require 'lib/Database_D.php'; 
date_default_timezone_set('Asia/Jakarta');
session_start();
if ($_SESSION['line'] == '') {
    header('location: index.php');
}
?>

<html class="no-js" lang="en">
<head>

   <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta http-equiv="refresh" content="5"> -->
    <title>CHRONOS</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="/apple-icon.png">
    <link rel="shortcut icon" href="images/faviconku.png">
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style type="text/css">
        /*canvas{
            width: 100% !important;
            height: 100% !important;
        }*/
    </style>
</head>
<body class="bg-dark">
<?php 
$date_now = date('l, d-M-Y');
 ?>
    <div id="right-panel" class="right-panel">
        <div class="breadcrumbs bg-dark">
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
                        <h1 class="text-white">J2 - PRODUCTION STATUS CHART</h1>
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
        </div> <!-- end breadcrumbs -->
    <div id="korselku" class="carousel slide" data-ride="carousel" data-interval="20000">
        <div class="content mt-3">
            <div class="carousel_inner ">
                <?php 
                $j = 1;
                $building = $_SESSION['line'];
                $dept = $_SESSION['proses'];
                $date_scan = '2020-04-30';
                // $date_scan = date('Y-m-d');
                $db = new Database();
                $cek_line = $db->cek_value($date_scan,$building,$dept);
                
                if ($cek_line != 0) {
                    $line = $db->select_line($date_scan,$building,$dept);
                }else{
                    //GET LAST INSERT LINE TARGET
                    $last_target = $db->last_target($building,$dept);
                    $line = $db->select_line($last_target['date'],$building,$dept);
                }
                    $j  = 1;
                    foreach ($line as $key => $data) {
                        ?>
                            <div class="carousel-item <?php if($j<=1){echo "active";} ?>">
                                <div class="animated fadeIn">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4><?php echo $data['line_code'].' - '; 
                                                    if ($data['dept_code'] == '121-ST1') {
                                                        echo "STITCHING";
                                                    }elseif ($data['dept_code'] == '121-CP1') {
                                                        echo "CUTTING";
                                                    }elseif ($data['dept_code'] == '121-SC0') {
                                                        echo "SUBCONT";
                                                    }elseif ($data['dept_code'] == '121-PT1') {
                                                        echo "RUBBER";
                                                    }elseif ($data['dept_code'] == '121-AS1') {
                                                        echo "ASSEMBLY";
                                                    }elseif ($data['dept_code'] == '121-DS1') {
                                                        echo "STOCKFIT";
                                                    }elseif ($data['dept_code'] == '121-PRE') {
                                                        echo "SUPERMARKET CENTRAL";
                                                    }elseif ($data['dept_code'] == '121-FGD') {
                                                        echo "FINISH GOOD";
                                                    }?> 
                                                    ACTUAL OUTPUT
                                                    </h4>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="<?php echo "oph".$data['line_code'].'-'.$data['dept_code']; ?>"></canvas> 
                                                </div>
                                            </div>
                                        </div><!-- /# column -->
                                        
                                </div><!-- .animated -->
                            </div>

                        <?php
                            $j++;
                            $data_id[] = $data['line_code'].'-'.$data['dept_code'];
                            $data_line[] = $data['line_code'];
                            $data_dept[] = $data['dept_code'];
                            $data_target[] = $data['target'];
                    }
                
                ?>
            </div><!--  end carousel inner -->
        </div><!-- .content -->
    </div> <!-- end carousel -->
</div><!-- /#right-panel -->
</body>
<script src="vendors/jquery/dist/jquery.min.js"></script>
<script src="vendors/popper.js/dist/umd/popper.min.js"></script>
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
<script src="vendors/chartjs-plugin-datalabels.min.js"></script>

<!-- <script src="vendors/chartjs-plugin-datalabels"></script> -->
<!-- <script src="vendors/jquery-3.4.1.js"></script> -->
<script src="assets/js/main.js"></script>
<script type="text/javascript">
    Chart.plugins.unregister(ChartDataLabels);
    Chart.defaults.global.plugins.datalabels.display = false;
    
</script>
<script>
 
<?php     
    $k = 0;  
    while ($k < count($data_id)) {
      
        //DATA OPH NORMAL
        $data_oph = $db->get_oph($date_scan,$data_line[$k],$data_dept[$k]);
        //DATA OPH OT
        $data_ot = $db->get_overtime($date_scan,$data_line[$k],$data_dept[$k]);
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
        
        //ARRAY TEMP
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
            if ($label_temp[$oph_i] == '10:30' && $label_temp[$oph_i+1] == '11:30') {
                $label[] = $label_temp[$oph_i];
                $oph[] = $oph_temp[$oph_i] + $oph_temp[$oph_i+1];
            }elseif($label_temp[$oph_i] != '11:30'){
                $label[] = $label_temp[$oph_i];
                $oph[] = $oph_temp[$oph_i];
            }
            $oph_i++;
        }

        //GET DATA PPH_STANDARD
        $pph_standard = 0;
        $mp_actual = 0;
        $data_pph = $db->get_pph_standard($date_scan,$data_line[$k],$data_dept[$k]);
        foreach ($data_pph as $value) {
            $pph_standard = $value['pph_standard'];
            $mp_actual = $value['mp_actual'];
        }

        //GET MP ACTUAL
        $l = 0;
        $pph_actual = 0;
        $pph_actual_percentage = array();
        while ($l < count($oph)) {
            $pph_actual = round($oph[$l]/$mp_actual);
            $pph_actual_percentage[] = round(($pph_actual/$pph_standard)*100,2);
            $l++;
            $pph_actual = 0;
        }

        ?>

        //CHART OPH
        var ctx = document.getElementById("<?php echo "oph".$data_id[$k]; ?>");
        ctx.height = 120;
        var myChart = new Chart( ctx, {
            plugins: [ChartDataLabels],
            type: 'line',
            data: {
                labels: [
                <?php 
                foreach ($label as $jam) {
                    // $tgl = date('d F Y', strtotime($value['tgl']));
                    echo "'".$jam." - ".date('H:i', strtotime('+1 hours', strtotime($jam)))."',";
                }
                 ?>
                ],
                type: 'bar',
                defaultFontFamily: 'Montserrat',
                defaultFontSize: 20,
                datasets: [{
                    type: 'line',
                    datalabels: {
                    align: 'end',
                    anchor: 'end',
                    display: false
                                },
                        label: "Target",
                        data: [ 
                            <?php 
                                for ($i=0; $i < count($oph); $i++) { 
                                    echo $data_target[0].",";
                                }
                             ?>
                         ],
                        borderColor: "rgba(0, 123, 255, 0.9)",
                        borderWidth: "2",
                        backgroundColor: "rgba(0, 123, 255, 0.2)",
                    }
                    ,
                    {
                    datalabels: {
                    align: 'end',
                    anchor: 'end',
                    display: true
                                },
                        label: "Actual",
                        data: [ 
                            <?php 
                                foreach ($oph as $value) {
                                    echo $value.",";
                                }                    
                             ?>
                         ],
                        borderColor: "rgba(40,167,69,0.75)",
                        borderWidth: "2",
                        backgroundColor: "rgba(40,167,69,0.30)"
                    }]
            },
            options: {
                plugins: {
                    datalabels: {
                        backgroundColor: 'rgba(40,167,69,0.75)',
                        borderRadius: 0,
                        color: 'white',
                        formatter: Math.round
                    }
                },
                responsive: true,
                tooltips: {
                    mode: 'index',
                    titleFontSize: 12,
                    titleFontColor: '#000',
                    bodyFontColor: '#000',
                    backgroundColor: '#fff',
                    titleFontFamily: 'Montserrat',
                    bodyFontFamily: 'Montserrat',
                    cornerRadius: 3,
                    intersect: false,
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        fontSize: 15

                        // fontFamily: 'Montserrat',
                    },


                },
                scales: {
                    xAxes: [ {
                        display: true,
                        gridLines: {
                            display: true,
                            drawBorder: false
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Hours'
                        }
                            } ],
                    yAxes: [ {
                        display: true,
                        gridLines: {
                            display: true,
                            drawBorder: false
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Output'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                            } ]
                },
               
            }
        } );

        
<?php 
    $k++;
    }

 ?>
</script>
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
    jQuery(document).ready(function(){
        // jQuery('#korselku').carousel({interval: 60000});

        setTimeout(function(){window.location='pph.php';},60000);

    });
</script>
</html>
