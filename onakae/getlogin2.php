<?php

    header('Content-Type: application/json; charset=utf-8');
	// header('Method: GET');
	ini_set('display_errors', 1);
	error_reporting(~0);
	date_default_timezone_set('Asia/Bangkok');
	include('connection.php');
//     $usr =$_GET['username'];// isset($_GET['username']) ? $_GET['username'] : '';
// $pwd =$_GET['username'];// isset($_GET['password']) ? $_GET['password'] : '';
	$sql = "
    SELECT 
    *
    FROM opduser;  
    ";
	

	$query = mysqli_query($conn,$sql);
	if (!$query) {
		printf("Error: %s\n", $conn->error);
		exit();
	}
	$resultArray = array();
	if (mysqli_num_rows($query) > 0) {
	while($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
		array_push($resultArray,$result);
	}
	echo json_encode($resultArray,\JSON_UNESCAPED_UNICODE);
	} else{
		echo '[{"units":"xxxxx"}]';	
	}
	mysqli_close($conn);
?>

