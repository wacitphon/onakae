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
                IFNULL(shortlist, '-'), 
                IFNULL(name1, '-'), 
                IFNULL(name2, '-'), 
                IFNULL(name3, '-'), 
                IFNULL(drugusage, '-')
            ) AS usagename 
        FROM drugusage 
        WHERE drugusage.status='Y'
        AND UPPER(shortlist) LIKE '$term%'
        ORDER BY shortlist";


$query = mysqli_query($conn, $sql);
if (!$query) {
    exit();
}

$resultArray = array();
if (mysqli_num_rows($query) > 0) {
    while ($result = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        array_push($resultArray, $result['usagename']);
    }
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array());
}

mysqli_close($conn);

// header('Content-Type: application/json; charset=utf-8');
// header('Method: GET');
// ini_set('display_errors', 1);
// error_reporting(~0);
// date_default_timezone_set('Asia/Bangkok');
// include('connection.php');
// $term = $_GET['term'];

// $sql = "SELECT 
//             CONCAT_WS('|   ', 
//                 IFNULL(shortlist, '-'), 
//                 IFNULL(name1, '-'), 
//                 IFNULL(name2, '-'), 
//                 IFNULL(name3, '-'), 
//                 IFNULL(drugusage, '-')
//             ) AS usagename 
//         FROM drugusage 
//         WHERE drugusage.status='Y'
//         AND shortlist LIKE ?
//         ORDER BY shortlist";

// $query = mysqli_prepare($conn, $sql);
// if (!$query) {
//     exit();
// }

// $termWithWildcard = $term . '%';
// mysqli_stmt_bind_param($query, "s", $termWithWildcard);
// mysqli_stmt_execute($query);
// $result = mysqli_stmt_get_result($query);

// $resultArray = array();
// while ($row = mysqli_fetch_assoc($result)) {
//     array_push($resultArray, $row['usagename']);
// }

// echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);

// mysqli_stmt_close($query);
// mysqli_close($conn);
?>
