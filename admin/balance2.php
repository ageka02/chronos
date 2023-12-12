<?php 
require '../lib/Database.php';
date_default_timezone_set('Asia/Jakarta');

session_start();
if (!isset($_SESSION['user'])) {
   header('location: index.php');
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
    <meta http-equiv="refresh" content="120">
    <title><?php echo "Balance_".$_GET['line']."_".$_GET['dept']."_".date('Y_md',strtotime($_GET['tgl'])); ?></title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="../apple-icon.png">
    <link rel="shortcut icon" href="../images/icon.png">

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
        <!-- end header -->
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <h4 style=""> <i class="fa fa-pencil-square-o bg-warning p-3"></i> Data Actual & Balance</h4>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="">
                        <?php 
                        $mydate = date('l, d M Y');
                         ?>
                        <h1> <?php echo $mydate; ?> </h1>
                    </div>                    
                </div>
            </div>            
        </div> 
        <!-- end breadcrumbs -->
        <div class="content mt-3 ">  
            <?php 
            $date = $_GET['tgl'];
            $line = $_GET['line'];
            $dept = $_GET['dept'];
            $db = new Database();
            $data_table = $db->get_balance_table($date,$line,$dept);
             ?>
             <div class="card">
                <div class="card-body">
                    <table id="balance_table" width="100%" class="table table-bordered table-hover display compact ">
                        <thead>
                            <tr>
                                <th rowspan="2">PO Number</th>
                                <th rowspan="2">PO Item</th>
                                <th rowspan="2">Bucket</th>
                                <th rowspan="2">OGAC Date</th>
                                <th rowspan="2">Style</th>
                                <th rowspan="2">Descriptions</th>
                                <th rowspan="2">Size</th>
                                <th rowspan="2">Qty Order</th>  
                                <th colspan="2" class="text-center">Current</th>
                                <th colspan="2" class="text-center">Summarize</th>
                            </tr>
                            <tr>
                                
                                <th class="text-center">IN</th>
                                <th class="text-center">OUT</th>
                                <th class="text-center">IN</th>
                                <th class="text-center">OUT</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php 
                            $total_order = $total_in = $total_out = $total_sumarize_in = $total_sumarize_out = 0;
                            foreach ($data_table as $value) {
                                ?>
                                <tr>
                                    <td><?php echo $value['po_no']; ?></td>
                                    <td><?php echo $value['po_item']; ?></td>
                                    <td><?php echo $value['bucket']; ?></td>
                                    <td><?php echo date('d-M-Y',strtotime($value['ogac'])); ?></td>
                                    <td><?php echo $value['style']; ?></td>
                                    <td><?php echo $value['desc']; ?></td>
                                    <td><?php echo $value['size']; ?></td>
                                    <td><?php echo round($value['qty_order']); ?></td>
                                    <td><?php echo round($value['qty_in']); ?></td>
                                    <td><?php echo round($value['qty_out']); ?></td>
                                    <td><?php echo round($value['total_in']); ?></td>
                                    <td><?php echo round($value['total_out']); ?></td>
                                </tr>
                                <?php
                                $total_order += round($value['qty_order']);
                                $total_in += round($value['qty_in']);
                                $total_out += round($value['qty_out']);
                                $total_sumarize_in += round($value['total_in']);
                                $total_sumarize_out += round($value['total_out']);
                            }
                             ?>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" style="text-align: right;">Total</th>
                                <th><?php echo $total_order; ?></th>
                                <th><?php echo $total_in; ?></th>
                                <th><?php echo $total_out; ?></th>
                                <th><?php echo $total_sumarize_in; ?></th>
                                <th><?php echo $total_sumarize_out; ?></th>
                            </tr>
                        </tfoot>
                     </table>
                </div>
             </div>
             
        </div>
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
        jQuery(document).ready(function() {
            jQuery('#balance_table').DataTable({
                responsive: true,
                select : true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel'
                ]
            });

            // jQuery('#balance_table thead tr').clone(true).appendTo( '#balance_table thead' );
            // jQuery('#balance_table thead tr:eq(1) th').each( function (i) {
            //     var title = jQuery(this).text();
            //     jQuery(this).html( '<input type="text" placeholder="Search '+title+'" size="10"/>' );
         
            //     jQuery( 'input', this ).on( 'keyup change', function () {
            //         if ( table.column(i).search() !== this.value ) {
            //             table
            //                 .column(i)
            //                 .search( this.value )
            //                 .draw();
            //         }
            //     } );
            // } );
         
            // var table = jQuery('#balance_table').DataTable( {
            //     orderCellsTop: true,
            //     fixedHeader: true,
            //     responsive: true,
            //     dom: 'Bfrtip',
            //     buttons: [
            //         'copy', 'csv', 'excel'
            //     ]
            // } );
        } );
    </script>
</html>