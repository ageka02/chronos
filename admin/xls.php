<?php
// date_default_timezone_set('Asia/Jakarta');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=[CHRONOS] All Process".date("dFY").".xls");//ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0"); 

require '../lib/Database.php';
$db = new Database();

$date = $_POST["tgl"];
$bucket_from = $_POST["bucket_from"];
$bucket_to = $_POST["bucket_to"];

//disini script laporan anda
        echo "
        <table>
        <tr>
        <th colspan='5' style='font-size:20;' align='left'>Chronos Report | All Process </th>
        </tr>
        <tr>
        <td> Date</td>
        <td colspan='2'>: $date</td>
        </tr>
        <tr>
        <td>Bucket</td>
        <td colspan='2'>: $bucket_from - $bucket_to</td>
        </tr>
        </table>
        <table width='100%' border='1' cellpadding='5' cellspacing='0'>
        <thead>        
            <tr style='background-color: #b2b3b1;'>
                <th rowspan='2'>Line</th>
                <th rowspan='2'>Bucket</th>
                <th rowspan='2'>Po Number</th>
                <th rowspan='2'>Po item</th>
                <th rowspan='2'>Style</th>
                <th rowspan='2'>Description</th>
                <th rowspan='2'>Gender</th>
                <th rowspan='2'>OGAC</th>
                <th rowspan='2'>Order Qty</th>
                <th colspan='4' >Cutting </th>
                <th colspan='4'>Subcont Out </th>
                <th colspan='4'>Subcont In </th>
                <th colspan='4'>Preparation CUT</th>
                <th colspan='4'>Preparation SUB</th>
                <th colspan='4'>Stitching </th>
                <th colspan='4'>Rubber </th>
                <th colspan='4'>Stockfit </th>
                <th colspan='4'>Assembly </th>
                <th colspan='4'>Finish Good </th>
            </tr>
            <tr style='background-color: #edc600;'>
                <th>QTY in</th>
                <th>balance</th>
                <th>QTY Out</th>
                <th>balance</th>
                <th>QTY in</th>
                <th>balance</th>
                <th>QTY Out</th>
                <th>balance</th>
                <th>QTY in</th>
                <th>balance</th>
                <th>QTY Out</th>
                <th>balance</th>
                <th>QTY in</th>
                <th>balance</th>
                <th>QTY Out</th>
                <th>balance</th>
                <th>QTY in</th>
                <th>balance</th>
                <th>QTY Out</th>
                <th>balance</th>
                <th>QTY in</th>
                <th>balance</th>
                <th>QTY Out</th>
                <th>balance</th>
                <th>QTY in</th>
                <th>balance</th>
                <th>QTY Out</th>
                <th>balance</th>
                <th>QTY in</th>
                <th>balance</th>
                <th>QTY Out</th>
                <th>balance</th>
                <th>QTY in</th>
                <th>balance</th>
                <th>QTY Out</th>
                <th>balance</th>
                <th>QTY in</th>
                <th>balance</th>
                <th>QTY Out</th>
                <th>balance</th>
            </tr>
        </thead>
        <tbody>
            ";
       $allproc = $db->report_all_process($date,$bucket_from,$bucket_to);
       $tot_cp1_in = $tot_cp1_in_bl = $tot_cp1_out = $tot_cp1_out_bl = $tot_sc0_in = $tot_sc0_in_bl = $tot_sc0_out = $tot_sc0_out_bl = $tot_sc1_in = $tot_sc1_in_bl = $tot_sc1_out = $tot_sc1_out_bl = $tot_pre_in = $tot_pre_in_bl = $tot_pre_out = $tot_pre_out_bl = $tot_st1_in = $tot_st1_in_bl = $tot_st1_out = $tot_st1_out_bl = $tot_pt1_in = $tot_pt1_in_bl = $tot_pt1_out = $tot_pt1_out_bl = $tot_ds1_in = $tot_ds1_in_bl = $tot_ds1_out = $tot_ds1_out_bl = $tot_as1_in = $tot_as1_in_bl = $tot_as1_out = $tot_as1_out_bl = $tot_fgd_in = $tot_fgd_in_bl = $tot_fgd_out = $tot_fgd_out_bl = 0;

       foreach ($allproc as $v) {
                
        // if ($v['line code'] == 'Line1') {
        //     # code...
        // }
           echo "
           <tr>
                <td>$v[line_code]</td>
               <td>$v[bucket]</td>
               <td>$v[po_no]</td>
               <td>$v[po_item]</td>
               <td>$v[style]</td>
               <td>$v[desc]</td>
               <td>$v[gender]</td>
               <td>$v[ogac]</td>
               <td>$v[line_qty]</td>
               <td>$v[cp1_in]</td>
               <td>$v[cp1_in_bl]</td>
               <td>$v[cp1_out]</td>
               <td>$v[cp1_out_bl]</td>
               <td>$v[sc0_in]</td>
               <td>$v[sc0_in_bl]</td>
               <td>$v[sc0_out]</td>
               <td>$v[sc0_out_bl]</td>
               <td>$v[sc1_in]</td>
               <td>$v[sc1_in_bl]</td>
               <td>$v[sc1_out]</td>
               <td>$v[sc1_out_bl]</td>
               <td>$v[pre_in]</td>
               <td>$v[pre_in_bl]</td>
               <td>$v[pre_out]</td>
               <td>$v[pre_out_bl]</td>
               <td>$v[pre2_in]</td>
               <td>$v[pre2_in_bl]</td>
               <td>$v[pre2_out]</td>
               <td>$v[pre2_out_bl]</td>
               <td>$v[st1_in]</td>
               <td>$v[st1_in_bl]</td>
               <td>$v[st1_out]</td>
               <td>$v[st1_out_bl]</td>
               <td>$v[pt1_in]</td>
               <td>$v[pt1_in_bl]</td>
               <td>$v[pt1_out]</td>
               <td>$v[pt1_out_bl]</td>
               <td>$v[ds1_in]</td>
               <td>$v[ds1_in_bl]</td>
               <td>$v[ds1_out]</td>
               <td>$v[ds1_out_bl]</td>
               <td>$v[as1_in]</td>
               <td>$v[as1_in_bl]</td>
               <td>$v[as1_out]</td>
               <td>$v[as1_out_bl]</td>
               <td>$v[fgd_in]</td>
               <td>$v[fgd_in_bl]</td>
               <td>$v[fgd_out]</td>
               <td>$v[fgd_out_bl]</td>
                  </tr>
                     ";
       }
?>
</tbody>
</table>
