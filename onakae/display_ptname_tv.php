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

$vn = $conn->real_escape_string( $_POST[ 'vn' ] );
$hn = $conn->real_escape_string( $_POST[ 'hn' ] );
$ptname_call = $conn->real_escape_string( $_POST[ 'ptname_call' ] );
$ptname_tv_display = $conn->real_escape_string( $_POST[ 'ptname_tv_display' ] );
$call_chanel = $conn->real_escape_string( $_POST[ 'call_chanel' ] );
$call1 = $conn->real_escape_string( $_POST[ 'call1' ] );

$sqlinsert = "INSERT INTO rx_call_ptname_tv 
   (calltime,
    ptname_call,
    ptname_tv_display,
    call_chanel,
    hn,
    vn,
    call1
    )
    VALUES (
    NOW(),
    '$ptname_call',
    '$ptname_tv_display',
    '$call_chanel',
    '$hn',
    '$vn',
    '$call1')
    ON DUPLICATE KEY UPDATE call_chanel = '$call_chanel',calltime=NOW()";
if ( $conn->query( $sqlinsert ) === TRUE ) {
    // echo 'บันทึกข้อมูลสำเร็จ';
} else {
    // http_response_code( 500 );
    // Internal Server Error
    // echo 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $conn->error;
}

// $sql = "SELECT  * FROM rx_call_ptname_tv
// ORDER BY calltime 
// ";
// $query = mysqli_query( $conn, $sql );
// if ( !$query ) {
//     printf( 'Error: %s\n' );
//     exit();
// }
// $resultArray = array();
// if ( mysqli_num_rows( $query ) > 0 ) {
//     while ( $result = mysqli_fetch_array( $query, MYSQLI_ASSOC ) ) {
//         array_push( $resultArray, $result );
//     }
//     echo json_encode( $resultArray, JSON_UNESCAPED_UNICODE );
// } else {
//     echo json_encode( array() );

// }

$conn->close();

?>
