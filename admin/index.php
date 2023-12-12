<?php 
require '../lib/connection_mysql.php';
// require '../lib/connection.php';
session_start();
if (isset($_SESSION['user'])) {
   header('location: home');
}
 ?>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Chronos</title>
    <meta name="description" content="Chronos | SLI Production Dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../images/faviconku.png">
    <link rel="icon" href="../images/faviconku.png">


    <link rel="stylesheet" href="../vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../vendors/selectFX/css/cs-skin-elastic.css">

    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'> -->
    <style type="text/css">
        .bg{
            background-image: url('../images/bg5b.jpg');
            background-size: 100% 100%;
            height: 100%;
            background-repeat: no-repeat;
        }
    </style>

</head>

<body class="bg">
    <!-- <div class="content"> -->
        <div class="col-lg-8" style=" ">
            <!-- <h1 class="text-center text-white">J2 DASHBOARD</h1> -->
            <a href=".." class="btn btn-danger "><i class="fa fa-backward"></i> DASHBOARD</a>
        </div>
        <div class=" col-lg-4" style=" height: 100%; background-color: rgba(10,15,15, 0.5); ">
            <div class="text-center text-white">
            <img src="../images/chronos_logo.png" alt="CHRONOS" style="margin: 30% 0 0; width: 300px; ">
            <h1 style="margin: 0 0 5% 0 ;">ADMIN</h1>
            </div>
            
            <form method="POST" >                
                <!-- <input type="hidden" name="refurl" value="<?php //echo "http://".$_SERVER['HTTP_HOST']."/"; ?>" /> -->
                <input type="hidden" name="refurl" value="<?php echo ($_SERVER['HTTP_REFERER']); ?>" />
                <div class="form-group" >
                    <input type="text" class="form-control" placeholder="username" name="nik">
                </div>
                <div class="form-group" >
                    <input type="password" class="form-control" placeholder="Password" name="password">
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn social twitter btn-flat " name="submit"><i class="fa fa-sign-in"></i>LOGIN</button>            
                </div>
                <div class="text-white m-t-15 text-center" >
                    <br>
                    <label>ERP DEPT - PT. SHOETOWN LIGUNG INDONESIA</label>
                    <p>Copyright © <?php echo date('Y'); ?> All rights reserved | SLI-ERP</p>                                
                </div>
            </form>
        </div>
    <!-- </div>     -->
</body>
<script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>

<?php 
if(isset($_POST['submit'])){
    $nik = $_POST['nik'];
    $password = $_POST['password'];    
    $query = "select nik,name,password,level from tb_user where nik='$nik'";
    $sql = mysqli_query($conn_mysql, $query);

    if ($data = mysqli_fetch_array($sql)) {
        if ($data['nik'] = $nik) {
            if ($password = password_verify($password,$data['password'])) {
                if ($_POST['refurl'] == "http://".$_SERVER['HTTP_HOST']."/" OR $_POST['refurl'] == "http://".$_SERVER['HTTP_HOST']."/chronos_prod/") {
                    $refurl = '';
                }else{
                    $refurl = isset($_POST['refurl']) ? ($_POST['refurl']) : '';                    
                }
                    $_SESSION['user'] = $nik;
                    $_SESSION['name'] = $data['name'];
                    $_SESSION['level'] = $data['level']; 
                    if ($data['level'] == 0) {
                        // if (!empty($refurl)) {
                        //     header("Location: $refurl");
                        //     die();
                        // }else{                            
                            header("location: oph_summary");
                        // }
                    }elseif ($data['level'] == 1) {
                        // if (!empty($refurl)) {
                        //     header("Location: $refurl");
                        //     die();
                        // }else{ 
                        header("location: oph");
                        // }
                    }else{
                        if (!empty($refurl)) {
                            header("Location: $refurl");
                            
                        }else{ 
                            header("location: home");
                        }    
                    }                    
                    die();
            }else{
                echo '<script>alert("Password yang anda masukan salah!");</script>';
            }
        }else{
            echo '<script>alert("User yang anda masukan salah!");</script>';
        }
    }else{
        echo '<script>alert("user belum terdaftar!");</script>';
    }

}
 ?>
</html>
