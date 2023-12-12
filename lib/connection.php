<?php
$serverName = "10.50.171.25"; //serverName\instanceName
$connectionInfo = array( "Database"=>"NYX_J2", "UID"=>"nyxuid", "PWD"=>"slinyxpwd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( !$conn ){
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}

?>