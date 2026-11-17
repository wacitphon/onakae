<?php
header( 'Content-Type: application/json; charset=utf-8' );
// header( 'Method: GET' );
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
// เปลี่ยนให้แสดงข้อผิดพลาดทั้งหมด
date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );

$hn_digit = intval( get_hn_digit( $conn ) );
$keysearch = trim( $_POST[ 'hn' ] );
$length = strlen( $keysearch );

if ( $length < $hn_digit ) {

    $sql = "SELECT a.* FROM opd_allergy a
    WHERE a.hn = (SELECT o.hn from ovst o where o.oqueue = '$hn' and o.vstdate=curdate()  ) 
    ";
} else {
    $sql = "SELECT a.* FROM opd_allergy a
    WHERE a.hn = '$hn'  
    ";
}

$query = mysqli_query( $conn, $sql );
if ( !$query ) {
    printf( 'Error: %s\n', mysqli_error( $conn ) );
    // แสดงข้อความผิดพลาดเพิ่มเติม
    exit();
}
$resultArray = array();
if ( mysqli_num_rows( $query ) > 0 ) {
    while ($result = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        array_push($resultArray, $result);
    }
    echo json_encode( $resultArray, JSON_UNESCAPED_UNICODE );
} else {
    echo json_encode( array() );

}

mysqli_close( $conn );

function get_hn_digit( $conn ) {
    // include 'connection.php';
    $sql = "select sys_value from sys_var where sys_name='hn_digit'";
    $result = $conn->query( $sql );
    $row = $result->fetch_assoc();
    if ( $row[ 'sys_value' ] != null ) {
        return $row[ 'sys_value' ];
    } else {
        $lastvstdate = '7';
    }
}

?>
