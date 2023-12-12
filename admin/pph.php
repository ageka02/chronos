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
}else{
    $deptaing = "All Process";
}

if ($building == 'Line%' or $building == '%' ) {
    $buildingaing = "All Line";
}else{
    $buildingaing = $building;
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
    <title>PPH | Chronos <?php echo $buildingaing.'_'.$deptaing.'_'.date('mdy',strtotime($date_scan))."_".date('H:i'); ?></title>
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
                <div class="form-group has-success px-2">
                    <select name="building" id="building" class="form-control" required>
                        <option value="Line%">-- Select Building --</option>
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
            
            ?>
        <div class="breadcrumbs">
            <div class="col-sm-8">
                <h4 style=""> <i class="fa fa-pencil-square-o bg-warning p-3"></i>
                 Data Productivity Per Hours <?php 
                    if ($building == 'Line%') {
                    echo 'All Line '.$deptaing;
                    }else{
                        echo ''.$building.' '.$deptaing;
                    }  
                    ?>
                </h4>
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
            <?php 
            $data_table_pph = $db->get_pph($date_scan,$building,$dept);
             ?>
	    	 <div class="card">
	    	 	<div class="card-body">
			 		<table id="pph_table" width="100%" class="table table-bordered table-hover display compact ">
			    	 	<thead>
                            <tr>
                                <th rowspan="2" hidden="hidden">Line ID</th>
                                <th rowspan="2">Line</th>
                                <th rowspan="2">Process</th>
                                <th colspan="3" class="text-center">Man Power</th>
                                <th colspan="2" class="text-center">Production Line/Day</th>
                                <th colspan="3" class="text-center">Man Hours Actual</th> 
                                <th colspan="3" class="text-center bg-warning">PPH</th>
                            </tr>
			    	 		<tr>
			    	 			
			    	 			<th class="text-center">Standard</th>
			    	 			<th class="text-center">Actual</th>
                                <th class="text-center">Balance</th>
                                <th class="text-center">Target</th>
                                <th class="text-center">Actual</th>
                                <th class="text-center">Normal</th>
                                <th class="text-center">Overtime</th>
                                <th class="text-center">Total</th>                                
                                <th class="text-center bg-warning">Standard</th>
                                <th class="text-center bg-warning">Actual</th>
                                <th class="text-center bg-warning">%</th>
			    	 		</tr>
			    	 	</thead>
			    	 	<tbody>
		    	 			<?php                          
                            $mp_balance = $overtime = $mh_normal = $mh_total = $pph_actual = $pph_persen = $total_mp_actual = 0;
		    	 			foreach ($data_table_pph as $value) {
                                if ($value['dept_code'] == '121-ST1') {
                                    $deptaings = "Stitching";
                                }elseif ($value['dept_code'] == '121-CP1') {
                                    $deptaings = "Cutting";
                                }elseif ($value['dept_code'] == '121-SC0') {
                                    $deptaings = "Subcon Out";
                                }elseif ($value['dept_code'] == '121-SC1') {
                                    $deptaings = "Subcon In(receipt)";
                                }elseif ($value['dept_code'] == '121-PT1') {
                                    $deptaings = "Rubber";
                                }elseif ($value['dept_code'] == '121-DS1') {
                                    $deptaings = "Stockfit";
                                }elseif ($value['dept_code'] == '121-PRE') {
                                    $deptaings = "Supermarket Central";
                                }elseif ($value['dept_code'] == '121-FGD') {
                                    $deptaings = "Finish Good";
                                }elseif ($value['dept_code'] == '121-AS1') {
                                    $deptaings = "Assembly";
                                }
                                // $mp_balance = $value['mp_actual'] - $value['mp_standard'];
                                // $mh_normal = $value['mp_actual']*8;
                                $day = date('l', strtotime($date_scan));
                                $today = date('Y-m-d');
                                $sql_last_scan = $db->get_last_scan($date_scan,$value['line_code'],$value['dept_code']);
                                $get_last = $sql_last_scan['last'];
                                // CEK NAMA HARI
                                if ($day != 'Friday') {
                                    if ($date_scan != $today) {
                                        if (strtotime($get_last) >= strtotime('17:00')) {
                                            $diff = strtotime('17:00') - strtotime('09:00');
                                        }else{
                                            $diff = strtotime('17:00') - strtotime('09:00');
                                        }
                                    }else{
                                         if (strtotime(date('H:i')) < strtotime('12:00')) {
                                            $diff = strtotime(date('H:i')) - strtotime('07:00');
                                        }elseif (strtotime(date('H:i')) >= strtotime('12:00') && strtotime(date('H:i')) <= strtotime('17:00')) 
                                        {
                                            $diff = strtotime(date('H:i')) - strtotime('08:00');
                                        }else{
                                        $diff = strtotime('17:00') - strtotime('09:00');
                                        }
                                    }                                    
                                    $selisih = floor($diff/(60*60));
                                }else{
                                    // KONDISIKAN UNTUK HARI JUM'AT
                                    if ($date_scan != $today) {
                                        if (strtotime($get_last) <= strtotime('17:30')) {
                                            $diff = strtotime('17:30') - strtotime('09:30');
                                        }else{
                                            $diff = strtotime('17:30') - strtotime('09:30');
                                        }
                                    }else{
                                        if (strtotime(date('H:i')) < strtotime('12:00')) {
                                            $diff = strtotime(date('H:i')) - strtotime('07:00');
                                        }elseif (strtotime(date('H:i')) >= strtotime('12:00') && strtotime(date('H:i')) <= strtotime('17:30')) {
                                            $diff = strtotime(date('H:i')) - strtotime('08:30');
                                        }else{
                                            $diff = strtotime('17:30') - strtotime('09:30');
                                        }                        
                                    }
                                    $selisih = floor($diff/(60*60));                                                                
                                }

                                // $mp_actual = $vd->mp_actual($date_scan,$value['line_code'],$value['dept_code']);
                                // foreach ($mp_actual as $actual_mp) {
                                //     $total_mp_actual = $actual_mp['actual'];
                                // }
                                // $mh_normal = ($total_mp_actual*$selisih);
                                // $mh_total = $mh_normal + $value['overtime']; 
                                // if ($mh_total == 0 ) {
                                //     $mh_totalku = 1;
                                // }else{
                                //     $mh_totalku = $mh_total;
                                // }
                                $overtime = $value['mp_overtime'] * $value['hours_overtime'];
                                $mh_normal = ($value['mp_actual']*$selisih);
                                $mh_total = $mh_normal + $overtime;
                                if ($mh_total == 0 ) {
                                    $mh_totalku = 1;
                                }else{
                                    $mh_totalku = $mh_total;
                                }
                                $pph_actual = round($value['actual'])/$mh_totalku;
                                $pph_persen = (round($pph_actual,2)/round($value['pph_standard'],2))*100;
		    	 				?>
		    	 				<tr>
                                    <td hidden="hidden"><?php echo $value['line_id']; ?></td>
				    	 			<td><?php echo $value['line_code']; ?></td>
                                    <td><?php echo $deptaings; //$value['dept_code']; ?></td>
				    	 			<td><?php echo round($value['mp_standard'],2); ?></td>
				    	 			<td><?php echo $value['mp_actual']; //$total_mp_actual;?></td>
				    	 			<td><?php echo $value['mp_balance']; ?></td>
				    	 			<td><?php echo $value['target']*8; ?></td>
				    	 			<td><?php echo round($value['actual']); ?></td>
				    	 			<td><?php echo $mh_normal; //$value['mh_normal']; ?></td>
                                    <td><?php echo $value['overtime'];//$overtime; ?></td>
                                    <td><?php echo $mh_total; ?></td>
				    	 			<td><?php echo round($value['pph_standard'],2); ?></td>
				    	 			<td><?php echo round($pph_actual,2); ?></td>
                                    <td><?php echo round($pph_persen,2)."%"; ?></td>
			    	 			</tr>
		    	 				<?php
                                // $total_order += round($value['qty_order']);
                                // $total_in += round($value['qty_in']);
                                // $total_out += round($value['qty_out']);
                                // $total_sumarize_in += round($value['total_in']);
                                // $total_sumarize_out += round($value['total_out']);
		    	 			}
		    	 			 ?>
			    	 		
			    	 	</tbody>
                        <!-- <tfoot>
                            <tr>
                                <th colspan="7" style="text-align: right;">Total</th>
                                
                            </tr>
                        </tfoot> -->
			    	 </table>
	    	 	</div>
	    	 </div>
	    	 
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
          jQuery('#pph_table').DataTable({
             responsive: true,
             select : true,
             dom: 'Bfrtip',
             buttons: [
              { extend: 'copyHtml5', footer: true },
              { extend: 'csvHtml5', footer: true },
             { extend: 'excelHtml5', footer: true,
             customize: function( xlsx ) {
                             var sheet = xlsx.xl.worksheets['sheet1.xml'];
                             jQuery('row:first c', sheet).attr( 's', '2' );
                             jQuery('c[r=K2] ', sheet).attr( 's', '22' );
                             jQuery('c[r=L2] ', sheet).attr( 's', '22' );
                             jQuery('c[r=M2] ', sheet).attr( 's', '22' );
                             jQuery('c[r=C2] t', sheet).text( 'MP Standard' );
                             jQuery('c[r=D2] t', sheet).text( 'MP Actual' );
                             jQuery('c[r=E2] t', sheet).text( 'MP Balance' );
                             jQuery('c[r=F2] t', sheet).text( 'Target Daily Production' );
                             jQuery('c[r=G2] t', sheet).text( 'Actual Daily Production' );
                             jQuery('c[r=H2] t', sheet).text( 'MH Actual' );
                             jQuery('c[r=I2] t', sheet).text( 'MH Overtime' );
                             jQuery('c[r=J2] t', sheet).text( 'MH Total' );
                             jQuery('c[r=K2] t', sheet).text( 'PPH Standard' );
                             jQuery('c[r=L2] t', sheet).text( 'PPH Actual' );
                             jQuery('c[r=M2] t', sheet).text( 'PPH Percentage' );                             
                         }
              }             
             ]
         });
            
		} );
    </script>
</html>