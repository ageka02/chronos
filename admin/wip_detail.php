<?php 
require '../lib/Database.php';
date_default_timezone_set('Asia/Jakarta');

session_start();
if (!isset($_SESSION['user'])) {
   header('location: .');
}
$db = new Database();
$line = '';
$dept = '';
$date_scan = '';
if(isset($_POST["dept"]) and isset($_POST["tgl"])) {
    $date_scan = $_POST['tgl'];
    $line = $_POST['line'];
    $dept = $_POST['dept'];
}elseif(isset($_GET['dept']) AND isset($_GET['tgl'])){
    $line = $_GET['line'];
    $dept = $_GET['dept'];
    $date_scan = $_GET['tgl'];  
}else{
    $date_scan = date('Y-m-d');
    $line = 'A1';
    $dept = '121-AS1';
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
    <title>WIP | chronos <?php echo $line."_".$deptaing."_".date('mdy',strtotime($date_scan)); ?></title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="../apple-icon.png">
    <link rel="shortcut icon" href="../images/faviconku.png">

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/DataTables/datatables.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
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
                    <div class="header-left">                         
                        <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#tampilaku">
                            <i class="fa fa-search"></i> Filter
                        </button>
                    </div>
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
        <div class="card-body card-block collapse" id="tampilaku">
        <form action="" method="post" class="form-inline needs-validation" >   
        <div class=" form-group has-success px-2">
           <select name="line" id="line" class="form-control " required >
            <option value="" > --Select Line --</option>
            <?php 
                $gedung = $db->get_line();
                foreach ($gedung as $data_gedung) {
             ?>
             <option value="<?php echo $data_gedung['line']; ?>"><?php echo $data_gedung['line']; ?></option>
         <?php } ?>
        </select>
         </div>         
            <div class=" form-group has-success">
           <select name="dept" id="dept" class=" form-control" required >
            <option value="" > --Select Type --</option>
            <option value="121-AS1" required>Assembly</option>
            <option value="121-ST1" required>Stitching</option>
            <option value="121-CP1" required>Cutting</option>
            <option value="121-SC0" required>Subcon</option>
            <option value="121-PT1" required>Rubber</option>
            <option value="121-DS1" required>Stockfit</option>
            <option value="121-PRE" required>Supermarket Central</option>
            <option value="121-FGD" required>Finish Good</option>
            <!-- <option value="alldept" required>All</option> -->
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
        <button type="submit" class="btn btn-info " >            
            <i class="fa fa-dot-circle-o"></i> Submit
        </button>
        </div>
        </form>
        </div>
        <?php 
        
         ?>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <h4 style=""> <i class="fa fa-pencil-square-o bg-warning p-3"></i> Detail WIP <?php echo $line." ".$deptaing; ?></h4>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="">                    	
                        <h1> <?php echo date('l, d F Y',strtotime($date_scan)); ?> </h1>
                    </div>                    
                </div>
            </div>            
        </div> 
        <!-- end breadcrumbs -->
	    <div class="content mt-3 ">  
	    	<?php 
            
	    	$data_table = $db->get_balance_table($date_scan,$line,$dept);
	    	 ?>
	    	 <div class="card">
	    	 	<div class="card-body">
			 		<table id="onhand_table" width="100%" class="table table-bordered table-hover display compact ">
			    	 	<thead>
                            <tr>
                                <th rowspan="2">PO Number</th>
                                <th rowspan="2">PO Item</th>
                                <th rowspan="2">Bucket</th>
                                <th rowspan="2">Style</th>
                                <th rowspan="2">Descriptions</th>
                                <th rowspan="2">Size</th>
                                <th rowspan="2">Qty Order</th>  
                                <th colspan="2" class="text-center">Current</th>
                                <th colspan="2" class="text-center">Summarize</th>
                                <th rowspan="2" class="text-center">WIP</th>
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
                            $total_order = $total_in = $total_out = $total_sumarize_in = $total_sumarize_out = $total_onhand = 0;
		    	 			foreach ($data_table as $value) {
		    	 				?>
		    	 				<tr>
				    	 			<td><?php echo $value['po_no']; ?></td>
                                    <td><?php echo $value['po_item']; ?></td>
				    	 			<td><?php echo $value['bucket']; ?></td>
				    	 			<td><?php echo $value['style']; ?></td>
				    	 			<td><?php echo $value['desc']; ?></td>
				    	 			<td><?php echo $value['size']; ?></td>
				    	 			<td><?php echo round($value['qty_order']); ?></td>
                                    <td><?php echo round($value['qty_in']); ?></td>
                                    <td><?php echo round($value['qty_out']); ?></td>
				    	 			<td><?php echo round($value['total_in']); ?></td>
				    	 			<td><?php echo round($value['total_out']); ?></td>
                                    <td><?php echo round($value['total_in']-$value['total_out']); ?></td>
			    	 			</tr>
		    	 				<?php
                                $total_order += round($value['qty_order']);
                                $total_in += round($value['qty_in']);
                                $total_out += round($value['qty_out']);
                                $total_sumarize_in += round($value['total_in']);
                                $total_sumarize_out += round($value['total_out']);
                                $total_onhand += round($value['total_in']-$value['total_out']);
		    	 			}
		    	 			 ?>			    	 		
			    	 	</tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align: right;">Total</th>
                                <th><?php echo $total_order; ?></th>
                                <th><?php echo $total_in; ?></th>
                                <th><?php echo $total_out; ?></th>
                                <th><?php echo $total_sumarize_in; ?></th>
                                <th><?php echo $total_sumarize_out; ?></th>
                                <th><?php echo $total_onhand; ?></th>
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
          jQuery('#onhand_table').DataTable({
             responsive: true,
             select : true,
             dom: 'Bfrtip',
             buttons: [
             'copy', 'csv', 'excel'
             ]
         });
		} );
    </script>
</html>