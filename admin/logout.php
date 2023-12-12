<?php 
session_start();
unset($_SESSION['user']);
unset($_SESSION['name']);
unset($_SESSION['level']);
header('Location: .');

 ?>