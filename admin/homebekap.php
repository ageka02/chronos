<?php 
// require '../lib/connection.php';
require '../lib/Database.php';
date_default_timezone_set('Asia/Jakarta');
session_start();
if (!isset($_SESSION['user'])) {
   header('location: .');
}
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
                    Welcome to mobile legends
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
    

</html>
