<?php
header( 'Content-Type: application/json; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( ~0 );
date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );

$sql = "SELECT * FROM rx_youtube_video LIMIT 1
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
    echo json_encode( [
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/9scnDD8-1T4?si=T3meL6Fwmf4B2tPY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>'] );

}

mysqli_close( $conn );
?>
