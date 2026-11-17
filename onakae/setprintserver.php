<?php
header( 'Content-Type: application/json; charset=utf-8' );
ini_set( 'display_errors', 0 );
date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );
$print_server = isset( $_POST[ 'print_server' ] ) ? $_POST[ 'print_server' ] : '';
$desktopname = gethostname();
$insertsql = "INSERT INTO rx_call_disp_pcsetchanel (pcname, print_server) VALUES (?, ?)
                      ON DUPLICATE KEY UPDATE print_server = ?";
$insertstmt = mysqli_prepare( $conn, $insertsql );
if ( $insertstmt ) {
    mysqli_stmt_bind_param( $insertstmt, 'sss', $desktopname, $print_server, $print_server );
    mysqli_stmt_execute( $insertstmt );
    mysqli_stmt_close( $insertstmt );
}

mysqli_close( $conn );
?>
