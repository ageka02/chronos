<?php 
require '../lib/Database.php';
// require '../lib/VD_D.php';

date_default_timezone_set('Asia/Jakarta');

session_start();
if (!isset($_SESSION['user'])) {
   header('location: .');
}

$db = new Database();

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
    // $date_scan = date('2020-05-18');
}

?>

<!DOCTYPE html>
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
    td {
        font-size: 12px;
    }
    th {
        font-size: 14px;
    }
    </style>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Finish Good | Chronos </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="../apple-icon.png">
    <link rel="shortcut icon" href="../images/faviconku.png">

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/DataTables/datatables.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../vendors/selectFX/css/cs-skin-elastic.css">  
    <link rel="stylesheet" href="../vendors/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"  media="screen">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <!-- Left Panel -->
    <?php include '../template/leftpanel.php'; ?>
    <!-- Left Panel -->
    <!-- Right Panel -->
    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <?php include '../template/header.php'; ?>
        <!-- end header -->
        <div class="card-body card-block collapse" id="tampilaku">
            <form action="" method="post" class="form-inline needs-validation">
                
                <!-- <div class="form-group has-success">
                    <select name="dept" id="select" class="form-control" required>
                        <option value="121-%">-- Select Process --</option>
                        <option value="121-CP1" required>Cutting</option>
                        <option value="121-ST1" required>Stitching</option>
                        <option value="121-AS1" required>Assembly</option>
                    </select>
                </div> -->
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
            
            ?>
        <div class="breadcrumbs">            
            <div class="col-sm-4">
                <h4 style=""> 
                    <i class="fa fa-pencil-square-o bg-warning p-3"></i>
                 Data Finish Good (UCC)
                </h4>
            </div>
            <div class="col-sm-4">
                <div class="nav nav-pills" id="nav-tab" role="tablist" style="padding-top: 6px;">
                    <a class="nav-item nav-link active show" id="nav-data-tab" data-toggle="tab" href="#nav-summary" role="tab" aria-controls="nav-data" aria-selected="true">Summary</a>
                    <a class="nav-item nav-link" id="nav-chart-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-chart" aria-selected="false">Detail</a>                    
                </div>
            </div>
            <div class="col-sm-4">
                <div class="page-header float-right">
                    <div class="">                      
                        <h1> <?php echo date('l, d F Y',strtotime($date_scan)); ?> </h1>
                    </div>                    
                </div>
            </div>            
        </div> 
        <!-- end breadcrumbs -->
        <div class="content mt-2">  
            <div class="tab-content " id="nav-tabContent">
                <div class="tab-pane fade active show" id="nav-summary" role="tabpanel" aria-labelledby="nav-data-tab">
            <?php
            $data_table_fg_summary = $db->select_fg_summary($date_scan); 
            $data_table_fg = $db->select_fg($date_scan);
             ?>
             <div class="card">
                <div class="card-body">
                    <table id="pph_table_summary" width="100%" class="table table-bordered table-hover display compact ">
                        <thead>
                            <tr>
                                <th>PO NO</th>
                                <th>PO Item</th>
                                <th >Style Name</th>
                                <th>Style Code</th> 
                                <th>Total In</th>
                                <th>Total Out</th>
                                <th>Current In</th>
                                <th>Current Out</th>
                                <th style="background-color: rgba(115, 252, 83,0.5);"> On Hand</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_in = $total_out = $current_in = $current_out = $total_stock =0;                          
                            foreach ($data_table_fg_summary as $value) {                                
                                ?>
                                <tr>
                                    <td><?php echo $value['po_no']; ?></td>
                                    <td><?php echo $value['po_item']; ?></td>
                                    <td><?php echo $value['fg_name']; ?></td>
                                    <td><?php echo $value['fg_code']; ?></td>
                                    <td><?php echo round($value['total_qty_in'],2); ?></td>
                                    <td><?php echo round($value['total_qty_out'],2); ?></td>
                                    <td><?php echo round($value['qty_in_current'],2); ?></td>
                                    <td><?php echo round($value['qty_out_current'],2); ?></td>
                                    <td style="background-color: rgba(115, 252, 83,0.5);"><?php echo $value['stock']; ?></td>
                                </tr>
                                <?php
                                $total_in += round($value['total_qty_in']);
                                $total_out += round($value['total_qty_out']);
                                $current_in += round($value['qty_in_current']);
                                $current_out += round($value['qty_out_current']);
                                $total_stock += round($value['stock']);
                            }
                             ?>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" > &nbsp;</th>
                                <th style="text-align: right;">Grand Total</th>
                                <th><?php echo $total_in; ?></th>
                                <th><?php echo $total_out; ?></th>
                                <th><?php echo $current_in; ?></th>
                                <th><?php echo $current_out; ?></th>
                                <th><?php echo $total_stock; ?></th>
                                
                            </tr>
                        </tfoot>
                     </table>
                </div>
             </div>
             </div>
             <!-- end tab-pane pindahpindah -->
             <div class="tab-pane fade " id="nav-data" role="tabpanel" aria-labelledby="nav-chart-tab">
                 <div class="card">
                     <div class="card-body">
                         <table id="pph_table_detail" width="100%" class="table table-bordered table-hover display compact ">
                             <thead>
                                 <tr>
                                     <th>PO NO</th>
                                     <th>PO Item</th>
                                     <th >Style Name</th>
                                     <th>Style Code</th> 
                                     <th>Size</th>
                                     <th>Carton </th>
                                     <th>Total In</th>
                                     <th>Total Out</th>
                                     <th>Current In</th>
                                     <th>Current Out</th>
                                     <th style="background-color: rgba(115, 252, 83,0.5);"> On Hand</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php        
                                 $total_in = $total_out = $current_in = $current_out = $total_stock = $total_carton =0;                    
                                 foreach ($data_table_fg as $value) {                                
                                     ?>
                                     <tr>
                                         <td><?php echo $value['po_no']; ?></td>
                                         <td><?php echo $value['po_item']; ?></td>
                                         <td><?php echo $value['fg_name']; ?></td>
                                         <!-- <td><?php //echo $value['gender']; //$total_mp_actual;?></td> -->
                                         <td><?php echo $value['fg_code']; ?></td>
                                         <td><?php echo $value['size']; ?></td>
                                         <td><?php echo round($value['carton_qty'],2); ?></td>
                                         <td><?php echo round($value['total_qty_in'],2); ?></td>
                                         <td><?php echo round($value['total_qty_out'],2); ?></td>
                                         <td><?php echo round($value['qty_in_current'],2); ?></td>
                                         <td><?php echo round($value['qty_out_current'],2); ?></td>
                                         <td style="background-color: rgba(115, 252, 83,0.5);"><?php echo $value['stock']; ?></td>
                                         
                                     </tr>
                                     <?php
                                     $total_carton += round($value['carton_qty']);
                                     $total_in += round($value['total_qty_in']);
                                     $total_out += round($value['total_qty_out']);
                                     $current_in += round($value['qty_in_current']);
                                     $current_out += round($value['qty_out_current']);
                                     $total_stock += round($value['stock']);
                                 }
                                  ?>
                                 
                             </tbody>
                             <tfoot>
                                 <tr>
                                    <th colspan="4" > &nbsp;</th>
                                    <th style="text-align: right;">Grand Total</th>
                                    <th><?php echo $total_carton; ?></th>
                                    <th><?php echo $total_in; ?></th>
                                    <th><?php echo $total_out; ?></th>
                                    <th><?php echo $current_in; ?></th>
                                    <th><?php echo $current_out; ?></th>
                                    <th><?php echo $total_stock; ?></th>
                                     
                                 </tr>
                             </tfoot>
                          </table>
                     </div>
                  </div>
             </div>
         </div>
         <!-- end tab content -->
        </div>
        <div class="text-center"><p style="font-size: 13px;" class="refreshaing"></p></div>
    </div>
</body>
    <script type="text/javascript" src="../vendors/jquery/dist/jquery.min.js" ></script>
    <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../vendors/DataTables/datatables.js"></script>
    <script type="text/javascript" src="../vendors/bootstrap-datetimepicker/js/bootstrap-datetimepicker1.min.js" charset="UTF-8"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../vendors/DataTables/Buttons-1.6.1/js/buttons.flash.min.js"></script>
    <script src="../vendors/DataTables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
    <script src="../vendors/DataTables/JSZip-2.5.0/jszip.min.js"></script>
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

    <script>
        jQuery(document).ready(function() {
          jQuery('#pph_table_summary').DataTable({
             responsive: true,
             select : true,
             dom: 'Bfrtip',
             buttons: [
              { extend: 'copyHtml5', footer: true },
              { extend: 'csvHtml5', footer: true },
             { extend: 'excelHtml5', footer: true, }             
             ]
         });
            
        } );
    </script>

    <script>
        jQuery(document).ready(function() {
          jQuery('#pph_table_detail').DataTable({
             responsive: true,
             select : true,
             dom: 'Bfrtip',
             buttons: [
              { extend: 'copyHtml5', footer: true },
              { extend: 'csvHtml5', footer: true },
             { extend: 'excelHtml5', footer: true, }             
             ]
         });
            
        } );
    </script>
</html>