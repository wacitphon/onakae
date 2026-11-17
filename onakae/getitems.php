<?php
header('Content-Type: application/json; charset=utf-8');
header('Method: GET');
ini_set('display_errors', 1);
error_reporting(~0);
date_default_timezone_set('Asia/Bangkok');
include('connection.php');

$term = $_GET['term'];
$stockname = $_GET['stockname'];
$sql = "SELECT 
            CONCAT(
                IF(d.name IS NULL, '-', d.name), '|   ',            
                IF(d.icode IS NULL, '-',d.icode), '|   ',  
                IF(d.units IS NULL, '', units)
             
            ) AS itemsname 
        FROM drugitems d
        WHERE d.name like '$term%'
        ORDER BY d.name";


$query = mysqli_query($conn, $sql);
if (!$query) {
    exit();
}

$resultArray = array();
if (mysqli_num_rows($query) > 0) {
    while ($result = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        array_push($resultArray, $result['itemsname']);
    }
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array());
}

mysqli_close($conn);
?>