<?php 
require '../lib/connection.php';
date_default_timezone_set('Asia/Jakarta');

session_start();
if (!isset($_SESSION['user'])) {
   header('location: index.php');
}
 ?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <style type="text/css">
.outeraing {
    /*white-space: nowrap;*/
    /*position: relative;
    overflow-x: auto;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    height: 500px;
    margin-bottom: 9px;*/
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
        <?php include '../template/header.php'; ?>
      <div class="card-body card-block collapse" id="tampilaku">
        <form action="" method="post" class="form-inline needs-validation">
        <div class=" form-group has-success px-2">
           <select name="building" id="select" class="form-control " required >
            <option value="" > --Select Building --</option>
            <?php 
            $sqldatabilding = "select distinct SUBSTRING([line code],1,1) [lineaing] from line where [line code] not like '%NCVS%'";
            $querybilding = sqlsrv_query( $conn, $sqldatabilding);
                if( $querybilding === false ) {
                     die( print_r( sqlsrv_errors(), true));
                    }
            while( $rowbilding = sqlsrv_fetch_array( $querybilding, SQLSRV_FETCH_NUMERIC) ) {
             ?>
             <option value="<?php echo $rowbilding[0]."%"; ?>" > <?php echo "Building ".$rowbilding[0]; ?> </option>
         <?php } sqlsrv_free_stmt($rowbilding); ?>
        </select>
         </div>    

            <div class=" form-group has-success">
           <select name="dept" id="select" class=" form-control" required >
            <option value="" > --Select Type --</option>
            <option value="121-AS1" required>Assembly</option>
            <option value="121-ST1" required>Stitching</option>
            <option value="121-CP1" required>Cutting</option>
            <option value="121-SC0" required>Subcon Out</option>
            <option value="121-SC1" required>Subcon In(receipt)</option>
            <option value="121-PT1" required>Rubber</option>
            <option value="121-DS1" required>Stockfit</option>
            <option value="121-PRE" required>Supermarket Central</option>
            <option value="121-FGD" required>Finish Good</option>
        </select>
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
        <button type="submit" class="btn btn-outline-primary " >            
            <i class="fa fa-dot-circle-o"></i> Submit
        </button>
        </div>
        </form>
        </div>
       

<?php
$dept = '';
$tgl = '';
$bilding = '';

if(isset($_POST["dept"]) and isset($_POST["tgl"]) )
{ 
    $dept = $_POST["dept"];
    $tgl = $_POST["tgl"];
    $bilding = $_POST["building"];
    $sql = "select l.[line code] [lineku], l.[line target], bs.[department code], convert(varchar,bs.[date scan],23)[tgl], sum(bs.[qty]) [qty]
        from [barcode scan]bs right join [line]l on l.[line code] = bs.[line code scan] where
        bs.[department code] = '".$dept."' and convert(varchar,bs.[date scan],23) = '".$tgl."' and l.[line code] like '".$bilding."' 
        group by l.[line code], l.[line target],  bs.[department code] ,convert(varchar,bs.[date scan],23)
        order by l.[line code]"; 
        $mydate = $_POST["tgl"];
}else{
    $sql = "select l.[line code] [lineku], l.[line target], bs.[department code], convert(varchar,bs.[date scan],23)[tgl]
            from [barcode scan]bs right join [line]l on l.[line code] = bs.[line code scan]
            where l.[line code] like 'A%' and bs.[department code] in ('121-PRE','121-SC0','121-SC1','121-PT1','121-DS1') and convert(varchar,bs.[date scan],23) = convert(varchar,getdate(),23) GROUP BY l.[line code], l.[line target], bs.[department code], convert(varchar,bs.[date scan],23)
            order by l.[line code], bs.[department code]";
    // $mydate0=getdate(date("U"));
    // $mydate = "$mydate0[mday] $mydate0[month] $mydate0[year]";
        $mydate = date('Y-m-d');
}

$stmt = sqlsrv_query( $conn, $sql);

?>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <h4 style=""> <i class="fa fa-pencil-square-o bg-warning p-3"></i> Data Onhand </h4>
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
            <div class="col-sm-12 col-md-12 col-lg-12 "> 
                <div class="card" style="">
                    <div class="weather-category-head bg-dark" style="height: 80px;">  
                    <ul>
                        <li>
                            <!-- <div class="bg-dark"> -->
                            <div class="text-white">
                                <label id="<?php echo $row[0]; ?>" ><h2><?php echo $row[0]; ?></h2></label>
                            </div>
                            <div class="text-white card-title">
                                <strong class="">
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
                                <!-- </div> -->
                        </li>
                        <li>
                            <span class="text-white">
                                <h2 ><?php echo $row[4]; ?></h2>
                                <h5 class="">Total Actual </h5>
                            </span>                            
                        </li>
                        <li style="padding-top: : 10px; margin: 0; ">
                            <button class="btn btn-link float-right" data-toggle="collapse" data-target="#tampildeh<?php echo $row[0]; ?>" ><i class="fa fa-caret-square-o-down"></i></button>
                        </li>
                    </ul>                     
                    </div>
                </div>

            </div>
    <!-- </div> -->
     <div class="outeraing collapse show" id="tampildeh<?php echo $row[0]; ?>" >
<?php
$sqlactualku = "select [size], [qty in] - [qty out] [onhand] from(
                    select bs.[size]
                    ,SUM(case when bs.[scan type] = 'IN' then bs.[qty] else 0 end) [qty in]
                    ,SUM(case when bs.[scan type] = 'OUT' then bs.[qty] else 0 end) [qty out]
                     from [barcode scan] bs
                     left join [job order] jo
                     on jo.[jo id] = bs.[jo id]
                     left join [line] l
                     on l.[line code] = bs.[line code scan]
                      where 1=1
                     and bs.[line code scan] like '".$row[0]."' and convert(varchar,bs.[date scan],23) <= '".$row[3]."' and bs.[department code] = '".$row[2]."'
                     group by bs.[size] 
                     )t";
                      //                pivot(
      //                sum(t.[qty])
      //                for t.[size] in(
      // [001],[002],[003],[004],[005],[006],[007],[008],[009],[010],[011],[012],[013],[014],[015],[01T],[02T],[03T],[04T],[05T],[06T],[07T],[08T],[09T],[10T],[11T],[12T],[13T]
      //                )
      //                )as pivot_table
$queryactualku = sqlsrv_query( $conn, $sqlactualku);
    if( $queryactualku === false ) {
        die( print_r( sqlsrv_errors(), true));
    }
    // $col_count = sqlsrv_num_fields($queryactualku)-1;
   while( $data_actual = sqlsrv_fetch_array($queryactualku, SQLSRV_FETCH_NUMERIC) ){
    // echo $data_actual[0];
    // foreach ($data_actual as $key => $qty_actual) {
        // if ($qty_actual != ''){
    // $arracount[] = $data_actual[1];
    // print_r($arracount);
    
            ?>
            <div class="col-sm-6 col-lg-2 col-md-4 ">
                <div class="card">
                    <div class="card-header" style="height: 45px; ">
                        <h6 class="text-sm-center" style="">SIZE <span class="badge badge-primary"><?php echo $data_actual[0]; ?></span></h6>
                    </div>
                    <div class="card-body text-sm-center" style="height:60px; padding-top: 10px;">
                        <h3 >
                            <?php echo $data_actual[1];?>
                        </h3>
                    </div>
                </div>
            </div>

            <?php
        // }
            // tutup if
    }
    // tutup foreach
    
?>

</div> 
<!-- div outeraing -->

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
