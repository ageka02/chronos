<?php 
// require '../lib/connection.php';
require '../lib/Database.php';
date_default_timezone_set('Asia/Jakarta');
session_start();
if (!isset($_SESSION['user'])) {
   header('location: .');
}
$db = new Database();
 ?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="refresh" content="10"/> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Report | Chronos</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="../images/faviconku.png">

    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
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
        <!-- Header-->
        
        <?php 
            // $building = '';
            // $dept = '';
            // $date_scan = '';
            // if (isset($_POST['dept']) AND isset($_POST['tgl'])) {
            //     $building = $_POST['building'];
            //     $dept = $_POST['dept'];
            //     $date_scan = $_POST['tgl'];
            // }else{
            //     $building = 'A1';
            //     $dept = '121-AS1';
            //     $date_scan = date('Y-m-d');
            // }
            // if ($dept == '121-ST1') {
            //     $deptaing = "Stitching";
            // }elseif ($dept == '121-CP1') {
            //     $deptaing = "Cutting";
            // }elseif ($dept == '121-SC0') {
            //     $deptaing = "Subcon Out";
            // }elseif ($dept == '121-SC1') {
            //     $deptaing = "Subcon In(receipt)";
            // }elseif ($dept == '121-PT1') {
            //     $deptaing = "Rubber";
            // }elseif ($dept == '121-DS1') {
            //     $deptaing = "Stockfit";
            // }elseif ($dept == '121-PRE') {
            //     $deptaing = "Supermarket Central";
            // }elseif ($dept == '121-FGD') {
            //     $deptaing = "Finish Good";
            // }elseif ($dept == '121-AS1') {
            //     $deptaing = "Assembly";
            // }

            ?>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Report</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <h1><?php echo date('l, d F Y',strtotime(date('Y-m-d'))); ?></h1>
                </div>
            </div>
        </div>

        <div class="content mt-2">
            <div class="col-lg-12" >
                <div class="card border border-primary">
                    <div class="card-header">
                        <h3>Report All Process</h3>
                    </div>
                    <div class="col-lg-6">
                     <form action="xls.php" method="POST" class="">
                         <div class="form-group">
                            <label class="control-label px-1">Bucket from:</label>
                            <input type="text" class="form-control" name="bucket_from" placeholder="yy-mm/dd" required="">
                         </div>
                         <div class="form-group">
                            <label class="control-label px-1">Bucket to:</label>
                            <input type="text" class="form-control" name="bucket_to" placeholder="yy-mm/dd" required="">
                         </div>
                         <div class="form-group has-success">
                             <label class="control-label px-1">Date :</label>
                             <div class="input-group input-append date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field = "dtp_input2" data-link-format="yyyy-mm-dd">
                                 <input type="text" class="form-control" placeholder="Select date here" required>
                                 <span class="add-on input-group-addon" data-toggle="tooltip" data-placemnet="bottom" title="Clear date">
                                     <i class="fa fa-remove" style="color: red;"></i>
                                 </span>
                                 <span class="input-group-addon add-on">
                                     <i class="fa fa-calendar"></i>
                                 </span>
                             </div>
                             <input type="hidden" id="dtp_input2" name="tgl" value="">
                         </div>
                         <div class="form-group ">
                             <button type="submit" class=" form-control btn btn-primary" >
                                 <i class="fa fa-download"></i> Generate
                             </button>
                         </div>
                     </form>   
                    </div>
                    <div class="col-lg-6">
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <!-- <div class="card-body">
                        Report 
                    </div> -->
                </div>
            </div>
        </div><!-- .content -->

    </div>
    <!-- Right Panel -->
      
</body>
     <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../vendors/bootstrap-datetimepicker/js/bootstrap-datetimepicker1.min.js" charset="UTF-8"></script>
    <script src="../vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="../vendors/chartjs-plugin-datalabels.min.js"></script>
    <script src="../assets/js/main.js"></script>
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

</html>
