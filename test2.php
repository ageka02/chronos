<?php
date_default_timezone_set('Asia/Jakarta');

require 'lib/Database_D.php';
require 'lib/Qms_D.php';
// require 'lib/VD_D.php';

// $date = date('Y-m-d');
$db = new Database();
// $db = new Qms_DB();
// $vd = new Vd_d();
// $data = $db->select_line('2020-04-24','A1','121-');
$data = $db->get_oph('2020-04-30','Line10','121-ST1');
// $data = $db->get_overtime('2020-03-17','A1','121-AS1');
// $data = $db->cek_data_oph('2020-03-17','A1','121-AS1');
// $data = $db->get_overtime('2020-03-24','A1','121-AS1');
// $data = $db->get_oph('2020-03-24','A1','121-CP1');
// $data = $db->get_jumat_pagi('2020-03-27','A1','121-ST1');
// $data = $db->get_jumat_siang('2020-03-27','A1','121-AS1');
// $data = $db->get_overtime_jumat('2020-03-27','A1','121-AS1');
// $data = $db->get_building();
// $data = $db->get_balance_table('2020-03-26','A1','121-AS1');
// $data = $db->get_pph_week('2020-04-20','A1','121-ST1');
// $data = $db->cek_pph('2020-04-16','A1','121-AS1');
// $data = $db->show_table();
// $data = $db->get_summary('2020-04-29');
// print_r($data);
// $data = $db->qms_asy('Line15','2020-05-04');
// foreach ($data as $value) {
// 	if (empty($value['pass'])) {
// 		echo 'gak ada data';
// 	}
// }
// print_r($data);
$diff = strtotime('16:30') - strtotime('08:30');
$selisih = floor($diff/(60*60));
echo $selisih;
 // echo (710/(104*4));
// print_r($array_data_out);
// $data = $db->rework_data('2020-04-27','101','121-AS1');
// var_dump($data);
// $i = 0;
// $jam = array();
// $in = array();
// $out = array();
// while ($i < count($data)) {
// 	foreach ($data[$i] as $key => $value) {
// 		if ($i == 0) {
// 			$jam[] = $key;
// 			if ($value == '') {
// 				$in[] = 0;
// 			}else{

// 				$in[] = round($value);
// 			}
// 		}else{
// 			if ($value == '') {
// 				$out[] = 0;
// 			}else{
// 				$out[] = round($value);

// 			}
// 		}
// 	}
// 	$i++;
// }
// print_r($jam);
// print_r($in);
// print_r($out);

// $checked = $out[count($out)-1];
// $must_unset = false;
// for ($y = count($out)-1; $y >= 0 ; $y--) { 
// 	if ($out[$y] == 0) {
// 		if($checked == 0){
// 			$must_unset = true;
// 		}
// 		if ($must_unset) {
// 			unset($out[$y]);
// 			unset($in[$y]);
// 			unset($jam[$y]);
// 			$must_unset = false;
// 		}
// 	}else{
// 		$checked = $out[$y];
// 	}
// }
// print_r($out);
// print_r($in);
// print_r($jam);

//tampilkan jam lembur
// $i = 0;
// while ( $i < count($lembur)) {
// 	if ($lembur[$i] != '') {
// 		echo "lembur jam ke ".$i." = ".$lembur[$i]."<br>";
// 	}else{
// 		if($lembur[$i+1] != ''){
// 			echo "lembur jam ke ".$i." = ".$lembur[$i]."<br>";
// 		}else{
// 			echo "lembur jam ke ".$i." = ".$lembur[$i]."<br>";
// 		}
// 	}
// 	$i++;
// }
// $lembur = array('0','4','0','8','0','0');
// print_r($lembur);
// echo "<br><br><br>";

// $i = count($lembur);
// for ($i=count($lembur)-1; $lembur[$i] == 0 ; $i--) { 
// 	echo "jam lembur ke ".$i." = ".$lembur[$i]."<br>";
// }
// if (strtotime(date('H:i')) < strtotime('12:00')) {
//                                     $diff = strtotime(date('H:i')) - strtotime('07:00');
//                                 }
//                          $selisih = floor($diff/(60*60));
//                          echo $diff;       