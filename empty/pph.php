<?php 
// session_start();
$building = $_SESSION['line'];
$dept = $_SESSION['proses'];
$date_now = date('l, d-M-Y');
// $date_scan = '2020-04-15';
$date_scan = date('Y-m-d');

 ?>
<div class="breadcrumbs" style="background-color: #FFD400;">
    <marquee behavior="alternate" scrollamount="10"> 
        <h1 style="color: red;">// Please Input Standard PPH for today //
        </h1>
    </marquee>
</div>
<hr>
<div class="col-4" style="width: 400px;"> 
    <div class="card" id="stitching_A1" style="height: auto; " >
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
                    <h4>MP STANDARD</h4>
                </th>
            </tr>
            <tr>
                <th colspan="2">
                    <h1 class="text-sm-center" style="font-size: 60px;" >0</h1>
                </th>
            </tr>
            <tr>
                <th colspan="2">
                    <h4>MP ACTUAL</h4>
                </th>
            </tr>
            <tr>
                <th>
                    <h1 class="text-sm-center" style="font-size: 60px;">0</h1>
                </th>
            </tr>
            <tr>
                <th>
                    <h4>PPH STANDARD</h4>
                </th>
            </tr>
            <tr>
                <th>
                    <h1 class="text-sm-center text-white bg-success" style="font-size: 60px;">0</h1>
                </th>
            </tr>
             <tr>
                 <th colspan="2">
                     <h4>PPH DAILY</h4>
                 </th>
             </tr>
             <tr>
                 <th colspan="2" class="bg-primary">
                    <h1 class="text-sm-center text-white" style="font-size: 60px;">0</h1>
                 </th>
             </tr>
             <tr>
                 <th colspan="2">
                     <h4>PERCENTAGE</h4>
                 </th>
             </tr>
             <tr>
                 <th colspan="">
                    <h1 class="text-sm-center text-white bg-primary" style="font-size: 60px;">0 %</h1>
                 </th>
             </tr>
       </table>
    </div>
</div>
<div class="outeraing">
    <?php 
    $i = 1;
    $start = strtotime('07:30');
    while ($i <= 8) {
        ?>
        <div class="col-3 ">
            <div class="card text-white bg-danger">
                <div class="card-header">   
                    <h4 class="text-sm-center text-white" style="font-size: 30px;"><?php echo date('H:i', $start)." - ".date('H:i', strtotime('+1 hours', $start)); ?></h4>
                </div>
                <div class="card-body" style="height: 190px; background-image: url('images/bg_pph.png'); background-repeat: no-repeat;">
                    <h2 class="text-sm-center" style="font-size: 50px;">0</h2>
                    <hr>
                    <h1 class="text-sm-center count" style="font-size: 50px;">0</h1>
                </div>
            </div>
        </div>
        <?php
        
        $start = strtotime('+1 hours', $start);
        if ($start == strtotime('11:30')) {
            $start = strtotime('12:30');
        }
        $i++;
    }
     ?>
</div>