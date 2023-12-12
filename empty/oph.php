<?php 
$building = $_SESSION['line'];
$dept = $_SESSION['proses'];
$date_now = date('l, d-M-Y');
// $date_scan = '2020-04-17';
$date_scan = date('Y-m-d');


 ?>
<div class="col-4" style="width: 420px;"> 
    <div class="card" style="height: auto; " >
        <table width="100%">
            <tr>
                <th colspan="2" class="bg-dark">
                    <h1 class="text-sm-center text-white" style=" font-size: 70px;"><?php echo $building; ?></h1>
                </th>
            </tr>
            <tr>
                <th colspan="2" class="bg-dark">
                    <h2 class="text-sm-center text-white">
                         <?php 
                            if ($dept == '121-ST1') {
                                echo "STITCHING";
                            }elseif ($dept == '121-CP1') {
                                echo "CUTTING";
                            }elseif ($dept == '121-SC0') {
                                echo "SUBCONT";
                            }elseif ($dept == '121-PT1') {
                                echo "RUBBER";
                            }elseif ($dept == '121-AS1') {
                                echo "ASSEMBLY";
                            }elseif ($dept == '121-DS1') {
                                echo "STOCKFIT";
                            }elseif ($dept == '121-PRE') {
                                echo "SUPERMARKET CENTRAL";
                            }elseif ($dept == '121-FGD') {
                                echo "FINISH GOOD";
                            }
                        ?>
                    </h2>
                </th>
            </tr>
            <tr>
                <th colspan="2">
                    <h3>ACTUAL</h3>
                </th>
            </tr>
            <tr>
                <th colspan="2">
                    <h1 class="text-sm-center" style=" font-size: 70px;">
                        0
                    </h1>
                </th>
                
            </tr>
            <tr>
                <th colspan="2">
                    <h3>PERCENTAGE</h3>
                </th>
            </tr>
            <tr>
                <th colspan="2">
                    <h1 class="text-sm-center text-white bg-primary" style=" font-size: 70px;">
                        0 %
                    </h1>
                </th>
            </tr>
            <tr>
                <th colspan="2">
                    <h3>TARGET</h3>
                </th>
            </tr>
            <tr>
                <th>
                    <h4 class="text-sm-center">Daily</h4>
                </th>
                <th>
                    <h4 class="text-sm-center">Hours</h4>
                </th>
            </tr>
            <tr>
                <th>
                    <h1 class="text-sm-center" style=" font-size: 50px;">
                        2112
                    </h1>
                </th>
                <th>
                    <h1 class="text-sm-center" style=" font-size: 50px;">
                        264
                    </h1>
                </th>
            </tr>
            <tr>
                <th colspan="2">
                    <h3>STATUS</h3>
                </th>
            </tr>
            <tr>
                <th>
                    <h4 class="text-sm-center">Balance</h4>
                </th>
                <th>
                    <h4 class="text-sm-center">Average/Hours</h4>
                </th>
            </tr>
            <!-- BG balance -->
             
             <tr>
                 <th>
                     <h1 class="text-sm-center" style=" font-size: 50px;">0</h1>
                 </th>
                 <th>
                     <h1 class="text-sm-center" style=" font-size: 50px;">0</h1>
                 </th>
             </tr>
             <tr>
                 <th colspan="2">
                     <h3>QMS DATA</h3>
                 </th>
             </tr>
             <tr>
                 <th>
                     <h4 class="text-sm-center">QC</h4>
                 </th>
                 <th>
                     <h4 class="text-sm-center">Pass</h4>
                 </th>
             </tr>
             <tr>
                <th rowspan="4">
                    <h1 class="text-sm-center" style=" font-size: 50px;">
                        0
                    </h1>
                </th>
                <th>
                    <h1 class="text-sm-center" style=" font-size: 50px;">
                       0
                    </h1>
                </th> 
             </tr>
             <tr>
                 <th>
                     <h4 class="text-sm-center">REWORK</h4>
                 </th>
             </tr>
             <tr>
                 <th>
                     <h1 class="text-sm-center" style=" font-size: 50px;">
                        0
                     </h1>
                 </th>
             </tr>
        </table>
    </div>
</div>
<div class="outeraing">
    <?php 
    $i = 1;
    $start = strtotime('08:00');
    while ($i <= 8) {
        ?>
        <div class="col-3 ">
            <div class="card text-white bg-danger">
                <div class="card-header">   
                    <h4 class="text-sm-center text-white" style="font-size: 30px;"><?php echo date('H:i', $start)." - ".date('H:i', strtotime('+1 hours', $start)); ?></h4>
                </div>
                <div class="card-body" style="height: 190px; background-image: url('images/bg-card-body.png'); background-repeat: no-repeat;">
                    <h2 class="text-sm-center" style="font-size: 50px;">0</h2>
                    <hr>
                    <h1 class="text-sm-center count" style="font-size: 50px;">0</h1>
                </div>
            </div>
        </div>
        <?php
        
        $start = strtotime('+1 hours', $start);
        if ($start == strtotime('12:00')) {
            $start = strtotime('13:00');
        }
        $i++;
    }
     ?>
</div>