<?php
header( 'Content-Type: application/json; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( ~0 );
date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );

$sql = "SELECT server_name FROM printserver WHERE print_mode='STICKER' 
    ";

$query = mysqli_query( $conn, $sql );
if ( !$query ) {
    printf( 'Error: %s\n' );
    exit();
}
$resultArray = array();
if ( mysqli_num_rows( $query ) > 0 ) {
    while ( $result = mysqli_fetch_array( $query, MYSQLI_ASSOC ) ) {
        array_push( $resultArray, $result );
    }
    echo json_encode( $resultArray, JSON_UNESCAPED_UNICODE );
} else {
    echo json_encode( array() );

}

mysqli_close( $conn );
?>
