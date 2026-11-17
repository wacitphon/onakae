<?php
header( 'Content-Type: application/json; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );

$desktopname = gethostname();
$chanel = $conn->real_escape_string( $_POST[ 'chanel' ] );

$sql = "INSERT INTO rx_chanel_call (id,chanel) VALUES('1','$chanel') 
ON DUPLICATE KEY UPDATE chanel = '$chanel'
";
$result = $conn->query( $sql );

$sql = "UPDATE rx_call_disp_pcsetchanel SET ptcall='1' 
WHERE chanel='$chanel' 
";
$result = $conn->query( $sql );


mysqli_close( $conn );
?>
