<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// Get JSON data from request body
// $data = json_decode(file_get_contents('php://input'), true);

if (isset($_POST['vstdate'])) {
    $vstdate = date('Y-m-d', strtotime($_POST['vstdate']));
} else {
    $vstdate = null;
}

if (isset($_POST['vsttime'])) {
    $vsttime = date('H:i:s', strtotime($_POST['vsttime']));
} else {
    $vsttime = null;
}

if (isset($_POST['rxtime'])) {
    $rxtime = date('H:i:s', strtotime($_POST['rxtime']));
} else {
    $rxtime = null;
}

if (isset($_POST['cashiertime'])) {
    $cashiertime = date('H:i:s', strtotime($_POST['cashiertime']));
} else {
    $cashiertime = null;
}

if (isset($_POST['stickertime'])) {
    $stickertime = date('H:i:s', strtotime($_POST['stickertime']));
} else {
    $stickertime = null;
}

if (isset($_POST['calltime'])) {
    $calltime = date('H:i:s', strtotime($_POST['calltime']));
} else {
    $calltime = null;
}


// Escaping other variables
$vn = $conn->real_escape_string($_POST['vn']);
$hn = $conn->real_escape_string($_POST['hn']);

if (isset($_POST['pharmacy'])) {
    $pharmacy = $conn->real_escape_string($_POST['pharmacy']);
    $sql = "INSERT INTO rx_disp_time (vn, hn, vstdate, vsttime, rxtime, cashiertime, stickertime,calltime, dispentime, pharmacy)
    VALUES ('$vn', '$hn', '$vstdate', '$vsttime', '$rxtime', '$cashiertime', '$stickertime','$calltime', NOW(), '$pharmacy')
    ON DUPLICATE KEY UPDATE dispentime = NOW(), pharmacy = '$pharmacy',calltime='$calltime'";
} else {
    $pharmacy = null;
    $sql = "INSERT INTO rx_disp_time (vn, hn, vstdate, vsttime, rxtime, cashiertime, stickertime,calltime, dispentime)
    VALUES ('$vn', '$hn', '$vstdate', '$vsttime', '$rxtime', '$cashiertime', '$stickertime','$calltime', NOW())
    ON DUPLICATE KEY UPDATE dispentime = NOW(),calltime='$calltime' ";
}



// Creating the SQL query


if ($conn->query($sql) === TRUE) {
    echo "บันทึกข้อมูลสำเร็จ";
} else {
    http_response_code(500); // Internal Server Error
    echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
