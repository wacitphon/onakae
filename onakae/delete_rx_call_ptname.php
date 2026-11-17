<?php
header( 'Content-Type: text/html; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( ~0 );
date_default_timezone_set( 'Asia/Bangkok' );

if ( !defined( 'INCLUDED_CONNECTION_PHP' ) ) {
    define( 'INCLUDED_CONNECTION_PHP', 1 );
    require_once( 'connection.php' );
}

// Make sure data is sent via POST
if ( $_SERVER[ 'REQUEST_METHOD' ] !== 'POST' ) {
    http_response_code( 405 );
    // Method Not Allowed
    echo 'เฉพาะการส่งข้อมูลแบบ POST เท่านั้นที่อนุญาต';
    exit;
}

$hn = $conn->real_escape_string( $_POST[ 'hn' ] );
$sqldelete = "DELETE FROM rx_call_ptname WHERE hn = '$hn'";
if ( $conn->query( $sqldelete ) === TRUE ) {
    echo '';
}

?>