<?php 
session_start();
unset($_SESSION['line']);
unset($_SESSION['proses']);
header('Location: index.php');

 ?>