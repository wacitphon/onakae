<?php
header('Content-Type: application/json; charset=utf-8');
header('Method: GET');
ini_set('display_errors', 1);
error_reporting(~0);
date_default_timezone_set('Asia/Bangkok');
include('connection.php');

$term = strtoupper($_GET['term']);
$sql = "SELECT  
            CONCAT_WS('|   ', 
                IFNULL(CONCAT(drugitems.name,' ',drugitems.strength), ''), 
                IFNULL(drugitems.icode, ''), 
                IFNULL(drugitems.units, '')
            ) AS itemsname 
        FROM drugitems
        WHERE UPPER(drugitems.name) LIKE '%$term%'
        AND drugitems.istatus='Y'
        ORDER BY drugitems.name";


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