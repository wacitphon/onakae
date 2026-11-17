<?php
header( 'Content-Type: application/json; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );

$desktopname = gethostname();
$ptcall = $conn->real_escape_string( $_POST[ 'ptcall' ] );

$sql = "INSERT INTO rx_call_disp_pcsetchanel (pcname,ptcall) VALUES ('$desktopname', '$ptcall') 
ON DUPLICATE KEY UPDATE ptcall = '$ptcall'
";
$result = $conn->query( $sql );

mysqli_close( $conn );
?>
