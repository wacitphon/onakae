<?php
header( 'Content-Type: application/json; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );
// $userIP = $_SERVER[ 'REMOTE_ADDR' ];
// $userAgent = $_SERVER[ 'REMOTE_ADDR' ].$_SERVER[ 'HTTP_USER_AGENT' ];
// echo $userAgent;


echo gethostname(); // may output e.g,: sandie
// Or, an option that also works before PHP 5.3
echo php_uname('n'); // may output e.g,: sandie


mysqli_close( $conn );
?>
