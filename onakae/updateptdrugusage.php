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

// Escaping other variables
$hos_guid = $conn->real_escape_string($_POST['hos_guid']);
if (isset($_POST['drugusage'])) {
    $drugusage = $conn->real_escape_string($_POST['drugusage']);
    $sql = "UPDATE opitemrece
    SET drugusage = '$drugusage',sp_use = ''
    WHERE hos_guid = '$hos_guid'
    ";

if ($conn->query($sql) === TRUE) {
    echo "บันทึกข้อมูลสำเร็จ";
} else {
    http_response_code(500); // Internal Server Error
    echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
}
}

// Close the database connection
$conn->close();
?>
