<?php
header( 'Content-Type: application/json; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );

$desktopname = gethostname();

$creatsql = "CREATE TABLE IF NOT EXISTS rx_med_error_report 
  (
    id VARCHAR(100) PRIMARY KEY NOT NULL,
    me_report_date datetime,
    me_date  date,
    me_department  varchar(100),
    me_event  varchar(10),
    me_event_detail  varchar(10),
    me_drugname1  varchar(255),
    me_drugname2  varchar(255),
    me_icode1  varchar(100),
    me_icode2  varchar(100),
    me_level  varchar(10),
    me_solve  varchar(255),
    me_phar  varchar(100),
    updateflag  varchar(1) 
    )
    ";
$createquery = mysqli_query( $conn, $creatsql );
if ( !$createquery ) {
    die( 'Error creating table: ' . mysqli_error( $conn ) );
}


$creatsql = "CREATE TABLE IF NOT EXISTS rx_call_disp_pcsetchanel 
  (
    pcname VARCHAR(255) PRIMARY KEY NOT NULL,
    ipaddress  varchar(100),
    chanel  varchar(2),
    print_server  varchar(255),
    ptcall  varchar(1),
    displayscreen  varchar(1) 
    )
    ";
$createquery = mysqli_query( $conn, $creatsql );
if ( !$createquery ) {
    die( 'Error creating table: ' . mysqli_error( $conn ) );
}

$creatsql = "CREATE TABLE IF NOT EXISTS rx_call_ptname (
    hn  varchar(100) PRIMARY KEY NOT NULL,
    vn  varchar(100),
    calltime  datetime,
    ptname_call  varchar(255),
    ptname_tv_display  varchar(255),
    call_chanel  varchar(10),
    call1  varchar(1)
    );  
    ";

$createquery = mysqli_query( $conn, $creatsql );
if ( !$createquery ) {
    die( 'Error creating table: ' . mysqli_error( $conn ) );
}

$creatsql = "CREATE TABLE IF NOT EXISTS rx_disp_time (
    vn  varchar(100) PRIMARY KEY NOT NULL,
    hn  varchar(100),
    vstdate  date,
    vsttime  time,
    rxtime  time,
    cashiertime  time,
    stickertime  time,
    dispentime  time,
    calltime  time,
    pharmacy  varchar(255),
    updateflag  varchar(1)
    );  
";
$createquery = mysqli_query( $conn, $creatsql );
if ( !$createquery ) {
    die( 'Error creating table: ' . mysqli_error( $conn ) );
}

$creatsql = "CREATE TABLE IF NOT EXISTS rx_disp_report (
    vn  varchar(100) PRIMARY KEY NOT NULL,
    hn  varchar(100),
    vstdate  date,
    vsttime  time,
    rxtime  time,
    cashiertime  time,
    stickertime  time,
    dispentime  time,
    calltime  time,
    phar  varchar(255),
    updateflag  varchar(1)
    );  
";
$createquery = mysqli_query( $conn, $creatsql );
if ( !$createquery ) {
    die( 'Error creating table: ' . mysqli_error( $conn ) );
}

$sql = "SELECT 
    pcname ,
    ipaddress,
    chanel,
    print_server,
    ptcall,
    displayscreen
  FROM rx_call_disp_pcsetchanel WHERE pcname = ?";
$query = mysqli_prepare( $conn, $sql );
mysqli_stmt_bind_param( $query, 's', $desktopname );
if ( !mysqli_stmt_execute( $query ) ) {
    die( 'Error: ' . mysqli_error( $conn ) );
}

$resultArray = array();
$result = mysqli_stmt_get_result( $query );
if ( mysqli_num_rows( $result ) > 0 ) {
    while ( $row = mysqli_fetch_assoc( $result ) ) {
        $resultArray[] = $row;
    }
    echo json_encode( $resultArray, JSON_UNESCAPED_UNICODE );
} else {
    echo json_encode( array() );

    $insertsql = 'INSERT INTO rx_call_disp_pcsetchanel (pcname) VALUES (?) ON DUPLICATE KEY UPDATE pcname = ?';
    $insertquery = mysqli_prepare( $conn, $insertsql );
    mysqli_stmt_bind_param( $insertquery, 'ss', $desktopname, $desktopname );
    if ( !mysqli_stmt_execute( $insertquery ) ) {
        die( 'Error inserting data: ' . mysqli_error( $conn ) );
    }
}


mysqli_close( $conn );
?>
