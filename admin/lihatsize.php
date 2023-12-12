<?php 
require '../lib/Database.php';
date_default_timezone_set('Asia/Jakarta');

session_start();
if (!isset($_SESSION['user'])) {
   header('location: .');
}
$db = new Database();

$date = substr($_GET['id'], 0,10);
$dept = substr($_GET['id'], 10,7);
$line = substr($_GET['id'], 17,5);
$size = substr($_GET['id'], 22,3);
$data_table = $db->get_onhand_table($date,$line,$dept,$size);
 ?>
 <div class="modal-dialog modal-lg">
 	<div class="modal-content">
 		<div class="modal-header">
 			<h4 class="modal-title" id="largeModalLabel"> Detail data size <?php echo $size; ?></h4>
 			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
 			
 		</div>
 		<div class="modal-body">
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
 		<div class="modal-footer">
 			<button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">tutup</button>
 		</div>
 	</div>
 </div>
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
		});
    </script>