<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Bangkok');
include('connection.php');

// Validate input
$usr = isset($_GET['username']) ? $_GET['username'] : '';
$pwd = isset($_GET['password']) ? $_GET['password'] : '';
$desktopname = gethostname();
$chanel = isset($_GET['chanel']) ? $_GET['chanel'] : NULL;

if (empty($usr) || empty($pwd)) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Create a prepared statement for the INSERT query
$insertsql = "INSERT INTO rx_call_disp_pcsetchanel (pcname, chanel) VALUES (?, ?)
              ON DUPLICATE KEY UPDATE chanel = ?";
$insertquery = mysqli_prepare($conn, $insertsql);
if (!$insertquery) {
    echo json_encode(['error' => 'Database error']);
    exit;
}

// Bind parameters and execute the INSERT query
mysqli_stmt_bind_param($insertquery, 'sss', $desktopname, $chanel, $chanel);
if (!mysqli_stmt_execute($insertquery)) {
    echo json_encode(['error' => 'Database error']);
    exit;
}

// Create a prepared statement for the SELECT query
$sql = "SELECT opduser.loginname,opduser.name FROM opduser WHERE upper(loginname) = upper(?) AND passweb = ?";
$query = mysqli_prepare($conn, $sql);
if (!$query) {
    echo json_encode(['error' => 'Database error']);
    exit;
}

// Hash the password using password_hash() for secure comparison
$hashedPwd = md5($pwd); // Change this to password_hash($pwd, PASSWORD_DEFAULT);

// Bind parameters and execute the SELECT query
mysqli_stmt_bind_param($query, 'ss', $usr, $hashedPwd);
if (!mysqli_stmt_execute($query)) {
    echo json_encode(['error' => 'Database error']);
    exit;
}

$resultArray = [];
$result = mysqli_stmt_get_result($query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $resultArray[] = $row;
    }
    echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([]);
}

// Close prepared statements and database connection
mysqli_stmt_close($insertquery);
mysqli_stmt_close($query);
mysqli_close($conn);
?>
