<?php
header( 'Content-Type: text/html; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( ~0 );
date_default_timezone_set( 'Asia/Bangkok' );

if ( !defined( 'INCLUDED_CONNECTION_PHP' ) ) {
    define( 'INCLUDED_CONNECTION_PHP', 1 );
    require_once( 'connection.php' );
}

// Make sure data is sent via POST
if ( $_SERVER[ 'REQUEST_METHOD' ] !== 'POST' ) {
    http_response_code( 405 );
    // Method Not Allowed
    echo 'เฉพาะการส่งข้อมูลแบบ POST เท่านั้นที่อนุญาต';
    exit;
}

$vn = $conn->real_escape_string( $_POST[ 'vn' ] );
$hn = $conn->real_escape_string( $_POST[ 'hn' ] );
$ptname_call = $conn->real_escape_string( $_POST[ 'ptname_call' ] );
$ptname_tv_display = $conn->real_escape_string( $_POST[ 'ptname_tv_display' ] );
$call_chanel = $conn->real_escape_string( $_POST[ 'call_chanel' ] );
$call1 = $conn->real_escape_string( $_POST[ 'call1' ] );

$sqlinsert = "INSERT INTO rx_call_ptname 
   (calltime,
    ptname_call,
    ptname_tv_display,
    call_chanel,
    hn,
    vn,
    call1
    )
    VALUES (
    NOW(),
    '$ptname_call',
    '$ptname_tv_display',
    '$call_chanel',
    '$hn',
    '$vn',
    '$call1')
    ON DUPLICATE KEY UPDATE call_chanel = '$call_chanel',calltime=NOW()";
if ( $conn->query( $sqlinsert ) === TRUE ) {
    // echo 'บันทึกข้อมูลสำเร็จ';
} else {
    // http_response_code( 500 );
    // Internal Server Error
    // echo 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $conn->error;
}

$sql = "SELECT  * FROM rx_call_ptname
ORDER BY calltime LIMIT 1
";
$result = $conn->query( $sql );

if ( $result->num_rows > 0 ) {

    while ( $row = $result->fetch_assoc() ) {

        $calltime = $row[ 'calltime' ];
        $ptname_call = $row[ 'ptname_call' ];
        $ptname_tv_display = $row[ 'ptname_tv_display' ];
        $call_chanel = $row[ 'call_chanel' ];
        $hn = $row[ 'hn' ];
        $vn = $row[ 'vn' ];
        $call1 = $row[ 'call1' ];

        echo '<tr style="background-color:#EBF5FB;">';
        echo '<td class="hide-column" id="calltime_' . $vn . '"> '.$calltime.'</td>';
        echo '<td style="widht:100%;background-color:#EBF5FB;" id="ptname_call_' . $vn . '"> '.$ptname_call.'</td>';
        echo '<td class="hide-column" id="ptname_tv_display_' . $vn . '"> '.$ptname_tv_display.'</td>';
        echo '<td class="hide-column" id="call_chanel_' . $vn . '"> '.$call_chanel.'</td>';
        echo '<td class="hide-column" id="hn_' . $vn . '"> '.$hn.'</td>';
        echo '<td class="hide-column" id="vn_' . $vn . '"> '.$vn.'</td>';
        echo '<td class="hide-column" id="call1_' . $vn . '"> '.$call1.'</td>';
        echo '</tr>';

        // $sqldelete = "DELETE FROM rx_call_ptname WHERE vn = '$vn';
        // if ( $conn->query( $sqldelete ) === TRUE ) {
        // } else {
        // }

    }

} else {
    echo '';
}

$conn->close();

?>
