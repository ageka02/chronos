<?php 
session_start();
ob_start();
 ?>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chronos</title>
    <meta name="description" content="Chronos | SLI Production Dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="images/faviconku.png">
    <link rel="icon" href="images/faviconku.png">
    <link rel="apple-touch-icon" href="apple-icon.png">

    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css"> -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style type="text/css">
        .bg{
            background-image: url('images/landing.png');
            background-size: 100% 100%;
            height: 100%;
            background-repeat: no-repeat;
        }
    </style>

</head>

<body class="bg">
    <!-- <div class="content"> -->
        <div class="col-lg-8" >
            
        </div>
        <div class=" col-lg-4" style=" height: 100%; background-color: rgba(56,149,255, 0.30);">
            <div class="text-center text-white">
                <img src="images/chronos_logo.png" alt="CHRONOS" style="margin-top: 30%; width: 300px; ">    
                <h1 style="margin: 1% 0 10% 0 ;">DASHBOARD</h1>
            </div>
            <form method="POST">
                <!-- <div class="form-group has-success" >
                    <input type="text" class="form-control" placeholder="line" name="line">
                </div> -->
                <div class="form-group has-success">
                    <select name="line" class="form-control">
                        <option value=""> -- Select Line --</option>
                       <?php 
                           $i = 1;
                            while ($i < 19) {
                                ?>
                                <option value="<?php echo "Line".$i; ?>"><?php echo "Line".$i; ?></option>
                                <?php
                                $i++;
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group has-success">
                    <select name="proses" class="form-control">
                        <option value="121-"> -- Select Proccess --</option>
                        <option value="121-CP1">Cutting</option>
                        <option value="121-ST1">Stitching</option>
                        <option value="121-AS1">Assembly</option>
                    </select>
                </div>
                <div class="form-group text-center">
                        <button type="submit" class="btn social twitter btn-flat " name="submit"><i class="fa fa-desktop"></i>SHOW</button>            
                </div>
                <div class="text-white m-t-15 text-center" >
                    <br>
                    <label>ERP DEPT - PT. SHOETOWN LIGUNG INDONESIA</label>
                    <p>Copyright © <?php echo date('Y'); ?> All rights reserved | SLI-ERP</p>
                    <a href="admin" class="btn btn-danger"><i class="fa fa-user"></i> ADMIN</a>
                </div>
            </form>
        </div>
    <!-- </div>     -->
    
</body>
<script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</html>
<?php 
if (isset($_GET['line']) AND isset($_GET['proses'])) {
    $line = $_GET['line'];
    $proses = $_GET['proses'];
    $_SESSION['line'] = $line;
    $_SESSION['proses'] = $proses;
    header('location: oph.php');
}elseif(isset($_POST['submit'])){
    $line = $_POST['line'];
    $proses = $_POST['proses'];
    $_SESSION['line'] = $line;
    $_SESSION['proses'] = $proses;
    header('location: oph.php');
}
ob_flush();
?>