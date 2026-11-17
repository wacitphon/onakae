<?php
header( 'Content-Type: text/html; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( ~0 );
date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );
$chanel = trim( $_POST[ 'chanel' ] );

$sql = "DELETE FROM rx_tv_display WHERE chanel='$chanel'
";
$result = $conn->query( $sql );

$conn->close();