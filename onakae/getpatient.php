<?php
header( 'Content-Type: application/json; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( ~0 );
date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );

$hn = $_GET[ 'hn' ];

if ( !empty( $hn ) && !is_null( $hn ) ) {
    $sql = "SELECT
    concat(trim(p.pname),trim(p.fname),'  ',trim(p.lname)) as ptname,
    concat(trim(p.fname),'  ',trim(p.lname)) as fname, 
    p.informaddr,p.informtel,
    m.image as ptimage,
    ThaiAge(p.birthday,CURDATE()) as age,
    y.name as pttype_name,
    p.drugallergy
        
    FROM patient p
    LEFT OUTER JOIN patient_image m on p.hn = m.hn
    LEFT OUTER JOIN pttype y on p.pttype=y.pttype  
    WHERE p.hn = '$hn'
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
}

mysqli_close( $conn );
?>
