<?php 
require '../lib/connection.php';
session_start();
if (!isset($_SESSION['user'])) {
   header('location: .');
}
 ?>
<?PHP
  // Original PHP code by Chirp Internet: www.chirp.com.au
// namespace Chirp;
require '../lib/connection.php';
  // 
$line = $_GET["line"];
$dept = $_GET["dept"];
$tgl = $_GET["tgl"]; 

$deptaing = "";
if ($_GET["dept"] = "121-ST1") {
    $deptaing = "Stitching";
}elseif ($_GET["dept"] = "121-CP1") {
    $deptaing = "Cutting";
}elseif ($_GET["dept"] = "121-SC0") {
    $deptaing = "Subcon";
}elseif ($_GET["dept"] = "121-PT1") {
    $deptaing = "Rubber";
}elseif ($_GET["dept"] = "121-DS1") {
    $deptaing = "Stockfit";
}elseif ($_GET["dept"] = "121-PRE") {
    $deptaing = "Supermarket Central";
}elseif ($_GET["dept"] = "121-FGD") {
    $deptaing = "Finish Good";
}elseif ($_GET["dept"] = "121-AS1") {
    $deptaing = "Assembly";
}

  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }
date_default_timezone_set('Asia/Jakarta');
  // file name for download
  $filename = "[Chronos] Balance ".$line." ".$deptaing." ". date('d-m-Y-H-i') . ".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
 $sql = "select * 
         ,isnull([001],0)+isnull([002],0)+isnull([003],0)+isnull([004],0)+isnull([005],0)+isnull([006],0)+isnull([007],0)+isnull([008],0)+isnull([009],0)+isnull([010],0)+isnull([011],0)+isnull([012],0)+isnull([013],0)+isnull([014],0)+isnull([015],0)+isnull([01T],0)+isnull([02T],0)+isnull([03T],0)+isnull([04T],0)+isnull([05T],0)+isnull([06T],0)+isnull([07T],0)+isnull([08T],0)+isnull([09T],0)+isnull([10T],0)+isnull([11T],0)+isnull([12T],0)+isnull([13T],0) [total], pivot_table.[line target] [target]
         from(
         select jo.[po no], jo.[po item],bs.[line code scan], bs.[department code], l.[line target], bs.[qty], convert(varchar,bs.[date scan],23) [date scan]
         , datediff(ss,Dateadd(ss, 0, Datediff(day, 0, dateadd(ss,-27000,bs.[date scan]))),dateadd(ss,-27000,bs.[date scan]))/3600+1 [jam]
         , bs.[size]
         from [barcode scan] bs
         left join [job order] jo
         on jo.[jo id] = bs.[jo id]
         left join [line] l
         on l.[line code] = bs.[line code scan]
  where 1=1
  and bs.[scan type] = 'IN' and bs.[line code scan] = '".$line."' and bs.[department code] = '".$dept."' and convert(varchar,bs.[date scan],23) = '".$tgl."'
         )t
         pivot(
         sum(t.[qty])
         for t.[size] in(
  [001],[002],[003],[004],[005],[006],[007],[008],[009],[010],[011],[012],[013],[014],[015],[01T],[02T],[03T],[04T],[05T],[06T],[07T],[08T],[09T],[10T],[11T],[12T],[13T]
         )
         )as pivot_table
         order by [date scan], [jam]";
  $result = sqlsrv_query( $conn, $sql);
  if( $result === false ) {
     die( print_r( sqlsrv_errors(), true));
}
echo "Data Balance\r\n";
  while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\r\n";
      $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
  }

  exit;
?>