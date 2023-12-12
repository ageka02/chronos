<?php 
require '../lib/connection.php';
date_default_timezone_set('Asia/Jakarta');

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
    <link rel="stylesheet" href="../vendors/DataTables/datatables.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Left Panel -->
    <?php include '../template/leftpanel.php'; ?>
    <!-- Left Panel -->

    <!-- Right Panel -->
    <div id="right-panel" class="right-panel">
        <header id="header" class="header ">
            <div class="header-menu">
                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                   
                </div>
                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="../images/user1.png" alt="User Avatar">
                        </a>
                        <div class="user-menu dropdown-menu">
                            <li class="" ><i class="fa fa-user"></i> <?php echo $_SESSION['name']; ?></li>
                            <a class="nav-link" href="../logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
      
       

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
    // $col_count = sqlsrv_num_fields($queryactualku)-1;
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
                    </div>
                </div>
            </div>

            <?php
        }

    }
    
?>
</div> 
<!-- div outeraing -->
<hr>
<?php 

}
 ?>
      
        <!-- .content -->
    </div>
</div>

</body>

    <script type="text/javascript" src="../vendors/jquery/dist/jquery.min.js" ></script>
    <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../vendors/bootstrap-datetimepicker/js/bootstrap-datetimepicker1.min.js" charset="UTF-8"></script>
    <script src="../vendors/DataTables/datatables.min.js"></script>
    <script src="../assets/js/main.js"></script>


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
</html>
