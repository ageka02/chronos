<?php 
require '../lib/Qms_D.php';
date_default_timezone_set('Asia/Jakarta');

session_start();
if (!isset($_SESSION['user'])) {
   header('location: .');
}
$vd = new Qms_DB();

$date = substr($_GET['id'], 0,10);
$dept = substr($_GET['id'], 10,7);
$line = substr($_GET['id'], 17,6);

$data = $vd->rework_data($date,$line,$dept);
 ?>
 <div class="modal-dialog modal-md">
 	<div class="modal-content">
 		<div class="modal-header">
 			
 			<h4 class="modal-title" id="largeModalLabel"> Total Rework <?php echo $line; ?></h4>
 			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
 			
 		</div>
 		<div class="modal-body">
			<ul class="list-group list-group-flush">
				<?php
				$total_rework = 0;
					foreach ($data as $value) {
						$total_rework += $value['jml_rework'];
				 ?>
				<li class="list-group-item">
					<h3>
					<?php 
					echo $value['desc']; 
					?>
					<span class="badge badge-warning float-right"><?php echo $value['jml_rework']; ?></span>
					</h3>
				</li>
				<?php } ?>
				<li class="list-group-item" style="background-color: #d8dfeb;">
					<h3>Total <span class="badge badge-primary float-right"> <?php echo $total_rework; ?></span> </h3>
				</li>
			</ul>
 		</div>
 		<div class="modal-footer">
 			<button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
 		</div>
 	</div>
 </div>