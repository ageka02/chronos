<?php 
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
td {
        font-size: 12px;
}
th {
    font-size: 14px;
}
.view_modol{
    cursor:pointer;
}
    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>WIP | Chronos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../images/faviconku.png">

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
                $gedung = $db->get_building();
                foreach ($gedung as $data_gedung) {
             ?>
             <option value="<?php echo $data_gedung['gedung']; ?>" > <?php echo $data_gedung['gedung']; ?> </option>
         <?php } ?>
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
$building = '';
$dept = '';
$date_scan = '';

if(isset($_POST["dept"]) and isset($_POST["tgl"])) { 
    $building = $_POST['building'];
    $dept = $_POST['dept'];
    $date_scan = $_POST['tgl'];
}else{
     $building = 'A1';
    $dept = '121-ST1';
    $date_scan = date('Y-m-d');
    // $date_scan = date('2020-04-07');
}
?>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <h4 style=""> <i class="fa fa-pencil-square-o bg-warning p-3"></i> Data WIP </h4>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <h1> <?php echo date('l, d F Y',strtotime($date_scan)); ?> </h1>               
                </div>
            </div>            
        </div>
        <div class="content mt-3 ">  
        <?php 
        // echo $cek_target;
        // if ($cek_target != 0) {

        $sql_line = $db->select_line_onhand2($date_scan,$building,$dept);
        foreach ($sql_line as $data_line) {
        ?>           
            
            <div class="col-sm-12 col-md-12 col-lg-12 "> 
                <div class="card adicardver-head" style="">
                    <div class="weather-category-head " style="height: 55px;">  
                    <a target="_blank" href="wip_detail.php?line=<?php echo $data_line['line_code']; ?>&dept=<?php echo $data_line['dept_code']; ?>&tgl=<?php echo $date_scan; ?>">
                    <ul>
                        <li>
                            <div class="text-white">
                                <h3 id="<?php echo $data_line['line_code']; ?>"><?php echo $data_line['line_code']; ?></h3>
                                <h6><?php 
                                if ($data_line['dept_code'] == '121-ST1') {
                                    echo "Stitching";
                                }elseif ($data_line['dept_code'] == '121-CP1') {
                                    echo "Cutting";
                                }elseif ($data_line['dept_code'] == '121-SC0') {
                                    echo "Subcon Out";
                                }elseif ($data_line['dept_code'] == '121-SC1') {
                                    echo "Subcon In(receipt)";
                                }elseif ($data_line['dept_code'] == '121-PT1') {
                                    echo "Rubber";
                                }elseif ($data_line['dept_code'] == '121-DS1') {
                                    echo "Stockfit";
                                }elseif ($data_line['dept_code'] == '121-PRE') {
                                    echo "Supermarket Central";
                                }elseif ($data_line['dept_code'] == '121-FGD') {
                                    echo "Finish Good";
                                }elseif ($data_line['dept_code'] == '121-AS1') {
                                    echo "Assembly";
                                }
                                ?></h6>
                            </div>
                        </li>
                        <li>
                            <span class="text-white">
                                <h3><?php 
                                    $total_actual = 0;
                                    $total_qty =  $db->get_balance_table($date_scan,$data_line['line_code'],$data_line['dept_code']);
                                    $total_onhand = 0;
                                    foreach ($total_qty as $value) {
                                        $total_onhand += round($value['total_in']-$value['total_out']);
                                    }
                                    echo $total_onhand;
                                    ?></h3>
                                <h6 class="">Total Actual </h6>
                            </span>                            
                        </li>                        
                        <!-- <li  class=""> -->
                            <!-- <button class="btn btn-link float-right" style="background-color: #333333 !important; " data-toggle="collapse" data-target="#tampildeh<?php //echo $data_line['line_code']; ?>" ><i class="fa fa-caret-square-o-down " style="font-size: 40px;"></i></button> -->
                        <!-- </li> -->
                    </ul>  
                    </a>                
                    </div>
                </div>
            </div>
    <!-- </div> -->
    <div class="outeraing collapse show" id="tampildeh<?php echo $data_line['line_code']; ?>" >
<?php
$sql_wip = $db->get_data_wip($date_scan,$data_line['line_code'],$data_line['dept_code']);

foreach ($sql_wip as $data_wip) {

            ?>
            <div class="col-sm-6 col-lg-2 col-md-4 ">
                <div class="card">
                    <span id="<?php echo $date_scan.$data_line['dept_code'].$data_line['line_code'].$data_wip['size']; ?>" class="view_modol " >
                    <div class="card-header" style="padding: .4rem 1.25rem !important;">
                        <h6 class="text-sm-center" style="">SIZE <span class="badge badge-primary"><?php echo $data_wip['size']; ?></span></h6>
                    </div>
                    </span>
                    <div class="card-body text-sm-center" style="height:60px; padding-top: 10px;">
                        <h3 >
                            <?php //echo round($data_wip2['total_in']-$data_wip2['total_out']); ?>
                            <?php 
                            $sql_wip2 = $db->get_onhand_table($date_scan,$data_line['line_code'],$data_line['dept_code'],$data_wip['size']);
                            $total_onhand = 0;
                            foreach ($sql_wip2 as $data_wipku) {
                                $total_onhand += round($data_wipku['total_in']-$data_wipku['total_out']);                               
                            }
                            echo $total_onhand;
                            // echo $data_wip['qty']; ?>
                        </h3>
                    </div>
                </div>
            </div>
            <?php
}
            // tutup foreach sqlwip
            ?>
    </div> 
<!-- div outeraing -->

    <?php 
    } 
    //tutup foreach  ^
   // }else{ //tutup if cek target
  //  echo "tidak ada data";
    //}
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
<div id="ModalView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelv" aria-hidden="true" data-backdrop="static">

 </div>

<script type="text/javascript">
    jQuery(document).ready(function () {
    jQuery(".view_modol").click(function(e) {
       var ih = jQuery(this).attr("id");
        jQuery.ajax({
              url: "lihatsize.php",
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