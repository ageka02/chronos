<?php 
require '../lib/connection.php';
session_start();
if (!isset($_SESSION['user'])) {
   header('location: index.php');
}
 ?>
<?php
$getline = $_GET["line"];
$getdepat = isset($_GET["dept"]);
$gettgl = isset($_GET["tgl"]);
$building = substr($getline, 0,1)."%";


// $stmt = sqlsrv_query( $conn, $sql);
// if( $stmt === false ) {
//      die( print_r( sqlsrv_errors(), true));
// }

// if( sqlsrv_fetch( $stmt ) === false) {
//      die( print_r( sqlsrv_errors(), true));
// }
// $name = sqlsrv_get_field( $stmt, 4);

// $target = sqlsrv_get_field( $stmt, 8);

// while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
//       echo $row[0]."<br />";
// }
// sqlsrv_free_stmt( $stmt);
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
    height: 500px;
    margin-bottom: 9px;
}
    </style>
<!--     <script type="text/javascript">
// $(document).ready(function() {

    // setTimeout(function(){window.location='charts-chartjs.html';},30000);

// }); //END $(document).ready()
        </script> -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Chronos</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="../apple-icon.png">
    <link rel="shortcut icon" href="../images/icon.png">

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/selectFX/css/cs-skin-elastic.css">  
    <link rel="stylesheet" href="../vendors/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"  media="screen">


    <!-- <link rel="stylesheet" href="../vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css"> -->
    <link rel="stylesheet" href="../vendors/DataTables/datatables.min.css">
 

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
        <form action="" method="post" class="form-inline needs-validation" novalidate>            
            <div class="form-group">
           <select name="dept" id="select" class="form-control" required="required" >
            <option value="0"> --Select Type --</option>
            <option value="121-AS1">Assembly</option>
            <option value="121-ST1">Stitching</option>
            <option value="121-CP1">Cutting</option>
            <option value="121-SC0">Subcon</option>
            <option value="121-PT1">Rubber</option>
            <option value="121-DS1">Stockfit</option>
            <option value="121-PRE">Supermarket Central</option>
            <option value="121-FGD">Finish Good</option>
        </select>
         </div>
         <!-- <fieldset> -->
            <div class="form-group">
                  <label class="control-label px-1">Date :</label>
                  <div class="input-group input-append date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                     <input type='text' class="form-control" required placeholder="select date here ...">
                     <span class="add-on input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Clear date"> <i class="fa fa-remove" style="color: red;"></i></span>
                     <span class="input-group-addon add-on"> <i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="hidden" id="dtp_input2" name="tgl" value="" /><br/>
               </div>               
        <!-- </fieldset>  -->
         <div class="form-group px-2">
        <button type="submit" class="btn btn-primary ">            
            <i class="fa fa-dot-circle-o"></i> Submit
        </button>
        </div>
        </form>
        </div>

<?php
if(isset($_POST["dept"]) and isset($_POST["tgl"]) )
{ 
    $dept = $_POST["dept"];
    $tgl = $_POST["tgl"];
    // } 
    $sql = "select l.[line code] [lineku], l.[line target],  bs.[department code] , convert(varchar,bs.[date scan],23)[tgl]
        from [barcode scan]bs right join [line]l on l.[line code] = bs.[line code scan] where bs.[scan type] = 'OUT' and
        bs.[department code] = '".$dept."' and convert(varchar,bs.[date scan],23) = '".$tgl."' and l.[line code] like '".$building."' 
        group by l.[line code], l.[line target],  bs.[department code] ,convert(varchar,bs.[date scan],23)
        order by l.[line code]";
        $mydate = $_POST["tgl"];
}elseif (isset($_GET["dept"]) and isset($_GET["tgl"]) ) {
    $dept = $_GET["dept"];
    $tgl = $_GET["tgl"];
    // $line = $_GET["line"];
    $sql = "select l.[line code] [lineku], l.[line target],  bs.[department code] , convert(varchar,bs.[date scan],23)[tgl], sum(bs.[qty])
        from [barcode scan]bs right join [line]l on l.[line code] = bs.[line code scan] where bs.[scan type] = 'OUT' and
        bs.[department code] = '".$dept."' and convert(varchar,bs.[date scan],23) = '".$tgl."' and l.[line code] like '".$building."' 
        group by l.[line code], l.[line target],  bs.[department code] ,convert(varchar,bs.[date scan],23)
        order by l.[line code]";
        $mydate = $_GET["tgl"];
}else{
    $sql = "select l.[line code] [lineku], l.[line target],  bs.[department code], convert(varchar,bs.[date scan],23)[tgl]
        from [barcode scan]bs right join [line]l on l.[line code] = bs.[line code scan] 
        where bs.[scan type] = 'OUT' and l.[line code] like '".$building."' and convert(varchar,bs.[date scan],23) = getDate()
        group by l.[line code], l.[line target],  bs.[department code], convert(varchar,bs.[date scan],23)  
        order by l.[line code]";
    $mydate0=getdate(date("U"));
    $mydate = "$mydate0[mday] $mydate0[month] $mydate0[year]";
}

$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}

?>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <h4 style=""> <i class="fa fa-pencil-square-o bg-warning p-3"></i> Data Actual</h4>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="">
                        <h1> <?php $ainglieur = strtotime($mydate);
                            echo date('l, d F Y', $ainglieur);  ?> </h1>
                    </div>                    
                </div>
            </div>            
        </div>

<!-- SINI OYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYy-->


        <div class="content mt-3 ">  
<?php 

 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {

?>           
            <!-- <div class="col">  -->
            <div class="col-sm-4 col-md-3 col-lg-3 "> 
                <div class="card" id="stitching_A1" style="height: 500px">
                    <div class="card-header user-header alt bg-dark">                       
                        <div class=" text-sm-center text-white">
                            <label class=""><h1 id="<?php echo $row[0]; ?>" ><?php echo $row[0]; ?></h1></label>
                        </div>
                        
                        <div class="text-sm-center text-white card-title">
                        <strong class="mb-3">
                            <?php 
                        if ($row[2] == '121-ST1') {
                            echo "Stitching";
                        }elseif ($row[2] == '121-CP1') {
                            echo "Cutting";
                        }elseif ($row[2] == '121-SC0') {
                            echo "Subcon Out";
                        }elseif ($row[2] == '121-SC1') {
                            echo "Subcon In(receipt)";
                        }elseif ($row[2] == '121-PT1') {
                            echo "Rubber";
                        }elseif ($row[2] == '121-DS1') {
                            echo "Stockfit";
                        }elseif ($row[2] == '121-PRE') {
                            echo "Supermarket Central";
                        }elseif ($row[2] == '121-FGD') {
                            echo "Finish Good";
                        }elseif ($row[2] == '121-AS1') {
                            echo "Assembly";
                        }
                        ?>  
                        </strong>
                        <!--  data line -->
                        </div>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> 
                            <span class="text-sm-center" > 
                            <h1><?php echo $row[1]; ?></h1>
                            <h5 class="">Target per Hours </h5> 
                            </span> 
                        </li>
                        <li class="list-group-item"> 
                            <span class="text-sm-center" > 
                            <h1><?php $dtarget= $row[1]*8; echo $dtarget; ?></h1>
                            <h5 class="">Target per Day </h5> 
                            </span> 
                        </li>
                        <li class="list-group-item ">
                            <span class="text-sm-center" >
                            <h1><?php echo $row[4]; ?></h1>
                            <h5 class="">Total Actual </h5> 
                            </span>                             
                        </li>
                    </ul>
                </div>
            </div>
    <!-- </div> -->
     <div class="outeraing">
<?php 
// $sqlgetstyle = "select jo.[output product name]
//                      from [barcode scan] bs
//                      left join [job order] jo
//                      on jo.[jo id] = bs.[jo id]
//                      left join [line] l
//                      on l.[line code] = bs.[line code scan]
//                     where bs.[scan type] = 'OUT' and bs.[line code scan] like '".$row[0]."' and convert(varchar,bs.[date scan],23) = '".$row[3]."' and bs.[department code] = '".$row[2]."'";
// $sql_get_style = sqlsrv_query($conn, $sqlgetstyle);
//     if( sqlsrv_fetch( $sql_get_style ) === false) {
//      die( print_r( sqlsrv_errors(), true));
//     }
//     $styleku = sqlsrv_get_field( $sql_get_style, 0);
    
?>
<!-- <div class="col-lg-12"> -->
    <?php //echo $styleku; ?>
<!-- </div> -->
<?php
$sqlactualku = "select * from(select
                      bs.[qty]
                     , bs.[size]
                     from [barcode scan] bs
                     left join [job order] jo
                     on jo.[jo id] = bs.[jo id]
                     left join [line] l
                     on l.[line code] = bs.[line code scan]
                      where 1=1
                     and bs.[scan type] = 'OUT' and bs.[line code scan] like '".$row[0]."' and convert(varchar,bs.[date scan],23) = '".$row[3]."' and bs.[department code] = '".$row[2]."'
                     )t
                     pivot(
                     sum(t.[qty])
                     for t.[size] in(
      [001],[002],[003],[004],[005],[006],[007],[008],[009],[010],[011],[012],[013],[014],[015],[01T],[02T],[03T],[04T],[05T],[06T],[07T],[08T],[09T],[10T],[11T],[12T],[13T]
                     )
                     )as pivot_table";
$queryactualku = sqlsrv_query( $conn, $sqlactualku);
    if( $queryactualku === false ) {
        die( print_r( sqlsrv_errors(), true));
    }
    $col_count = sqlsrv_num_fields($queryactualku)-1;
    $data_actual = sqlsrv_fetch_array($queryactualku, SQLSRV_FETCH_ASSOC);

    foreach ($data_actual as $key => $qty_actual) {
        if ($qty_actual != ''){

            ?>
            <div class="col-sm-6 col-lg-2 col-md-4 ">
                <div class="card">
                    <div class="card-header" style="height: 45px; ">
                        <h6 class="text-sm-center" style="">SIZE <span class="badge badge-primary"><?php echo $key; ?></span></h6>
                    </div>
                    <div class="card-body text-sm-center" style="height:60px; padding-top: 10px;">
                        <h3 >
                            <?php echo $qty_actual;?>
                        </h3>
                        <?php 
                        // echo $rowotp[5]/$row[1]*100;
                         ?>
                        
                        <!-- <br><h1> <?php //$belence = $row[1] - $rowotp[4]; echo $belence;  ?> </h1>  balance per jam-->
                    </div>
                </div>
            </div>

            <?php
        }

    }
    
// sqlsrv_free_stmt( $rowotp);
?>
</div> 
<!-- div outeraing -->
<hr>
<?php 

}
// sqlsrv_free_stmt( $rowotp);
 ?>
      
        <!-- .content -->
    </div>
</div>

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

    <script src="../vendors/DataTables/datatables.min.js"></script>

    <script src="../assets/js/main.js"></script>

    <!-- <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script> -->


<script type="text/javascript">
    jQuery(document).ready( function () {
    jQuery('#tableaing').DataTable({
    responsive: true
});
});
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

   <!-- <script type="text/javascript">
        jQuery(document).ready(function() {
        jQuery('#tesjq1').addClass('bg-flat-color-3');
        jQuery('#tesjq1').css({'color':'green'});
});
    </script> -->


</html>
