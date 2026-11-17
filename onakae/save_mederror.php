<?php
header( 'Content-Type: text/html; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

// Include the connection.php file only if it hasn't been included before
if (!defined('INCLUDED_CONNECTION_PHP')) {
    define('INCLUDED_CONNECTION_PHP', 1);
    require_once('connection.php');
}

// Make sure data is sent via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo "เฉพาะการส่งข้อมูลแบบ POST เท่านั้นที่อนุญาต";
    exit;
}

// Assuming getuuid() and gethostname() functions are defined elsewhere
$id = getuuid();
$me_date = date('Y-m-d', strtotime($_POST['me_date']));
$me_department = $conn->real_escape_string($_POST['me_department']);
$me_event = $conn->real_escape_string($_POST['me_event']);
$me_event_detail = $conn->real_escape_string($_POST['me_event_detail']);
$me_drugname1 = $conn->real_escape_string($_POST['me_drugname1']);
$me_drugname2 = $conn->real_escape_string($_POST['me_drugname2']);
$me_level = $conn->real_escape_string($_POST['me_level']);
$me_solve = $conn->real_escape_string($_POST['me_solve']);
$me_phar = gethostname();
$updateflag = '1';

// SQL query using prepared statements
$insertsql = "INSERT INTO rx_med_error_report (
    id,
    me_report_date,
    me_date,
    me_department,
    me_event,
    me_event_detail,
    me_drugname1,
    me_drugname2,
    me_level,
    me_solve,
    me_phar,
    updateflag
    ) VALUES (
        '$id',
        NOW(),
        '$me_date',
        '$me_department',
        '$me_event',
        '$me_event_detail',
        '$me_drugname1',
        '$me_drugname2',
        '$me_level',
        '$me_solve',
        '$me_phar',
        '$updateflag'
    ) 
    ON DUPLICATE KEY UPDATE me_report_date = NOW()
";

if ($conn->query($insertsql) === TRUE) {
    echo "บันทึกข้อมูลสำเร็จ";
} else {
    http_response_code(500); // Internal Server Error
    echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
}



mysqli_close( $conn );

// echo json_encode( $response );
?>
