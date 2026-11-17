<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Bangkok');
include('connection.php');

$callmsg = $conn->real_escape_string($_POST['callmsg']);
$lang = $conn->real_escape_string($_POST['lang']);

// ใช้ prepared statement เพื่อป้องกัน SQL injection
$sql = "INSERT INTO rx_call_pt (timeq, callmsg,lang) VALUES (NOW(), ?,?) 
        ON DUPLICATE KEY UPDATE callmsg = VALUES(callmsg)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $callmsg,$lang);

if ($stmt->execute()) {
    $response = array('success' => true, 'message' => 'ข้อมูลถูกบันทึกหรืออัปเดตสำเร็จ');
} else {
    $response = array('success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $conn->error);
}

echo json_encode($response);

mysqli_close($conn);


// 
// header( 'Content-Type: application/json; charset=utf-8' );
// ini_set( 'display_errors', 1 );
// error_reporting( E_ALL );

// date_default_timezone_set( 'Asia/Bangkok' );
// include( 'connection.php' );

// $callmsg = $conn->real_escape_string( $_POST[ 'callmsg' ] );

// $sql = "INSERT INTO rx_call_pt (timeq,callmsg) 
// VALUES (NOW(),'$callmsg') 
// ON DUPLICATE KEY UPDATE callmsg = '$callmsg'
// ";
// $result = $conn->query( $sql );

// mysqli_close( $conn );
// 
?>
