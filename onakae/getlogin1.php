<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Bangkok');
include('connection.php');

// Validate input
$usr = isset($_GET['username']) ? $_GET['username'] : '';
$pwd = isset($_GET['password']) ? $_GET['password'] : '';

// if (empty($usr) || empty($pwd)) {
//     echo json_encode(array('error' => 'Invalid input'));
//     exit;
// }

// Create SQL query with parameterized query
$sql = "SELECT * FROM opduser WHERE loginname = ? AND passweb = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $usr, md5($pwd));
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $resultArray = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $resultArray[] = $row;
        }

        mysqli_free_result($result);

        // Get user agent and chanel from POST data
        $desktopname = gethostname();
        $chanel = isset($_GET['chanel']) ? $_GET['chanel'] : NULL;

        // Insert or update record in rx_call_disp_pcsetchanel table
        $insertsql = "INSERT INTO rx_call_disp_pcsetchanel (pcname, chanel) VALUES (?, ?)
                      ON DUPLICATE KEY UPDATE chanel = ?";
        $insertstmt = mysqli_prepare($conn, $insertsql);

        if ($insertstmt) {
            mysqli_stmt_bind_param($insertstmt, "sss", $desktopname, $chanel, $chanel);
            mysqli_stmt_execute($insertstmt);
            mysqli_stmt_close($insertstmt);
        }

        echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array());
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(array('error' => 'Database error'));
}

mysqli_close($conn);
?>
