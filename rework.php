<?php 
require 'lib/Database_D.php'; 
require 'lib/Qms_D.php'; 
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
                        <h1 class="text-white">J2 - PRODUCTION QC CHART</h1>
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
                                                    <h4><?php echo "QC ".$data['line_code'].' - '; 
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
                                                    REWORK
                                                    </h4>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="<?php echo $data['line_code'].'-'.$data['dept_code']; ?>"></canvas> 
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

</script>
<script>
    <?php 
    $qms = new Qms_DB();
        $k = 0;
        while ($k < count($data_id)) {
            
            $data_rework = $qms->rework_data($date_scan,$data_line[$k],$data_dept[$k]);
            $rework = array();
            $qty_rework = array();
            foreach ($data_rework as $value) {
                $rework[] = $value['desc'];
                $qty_rework[] = $value['jml_rework'];
            }

            ?>
                var ctx = document.getElementById("<?php echo $data_id[$k]; ?>");
                ctx.height = 120;
                var myChart = new Chart( ctx, {
                    plugins: [ChartDataLabels],
                    type: 'bar',
                    data: {
                        labels: [
                        <?php 
                        foreach ($rework as $label) {
                            // $tgl = date('d F Y', strtotime($value['tgl']));
                            echo "'".$label."',";
                        }
                         ?>
                        ],
                        type: 'bar',
                        defaultFontFamily: 'Montserrat',
                        datasets: [
                            {
                            datalabels: {
                            align: 'end',
                            anchor: 'end',
                            display: true
                                        },
                                    <?php
                                        $total_rework = 0;
                                        foreach ($qty_rework as $value) {
                                            $total_rework += $value;
                                        }                    
                                     ?>
                                label: "Rework <?php echo $total_rework; ?>",
                                data: [ 
                                    <?php 
                                        foreach ($qty_rework as $value) {
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
                            // labels: {
                            //     // usePointStyle: true,
                            //     // fontFamily: 'Montserrat',
                            // },


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
                                    labelString: 'Description'
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
                                    labelString: 'Qty'
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

        setTimeout(function(){window.location='fg.php';},60000);

    });
</script>
</html>
