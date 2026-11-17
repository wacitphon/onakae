<?php
header( 'Content-Type: application/json; charset=utf-8' );
ini_set( 'display_errors', 0 );
date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );
$ptcall = isset( $_POST[ 'ptcall' ] ) ? $_POST[ 'ptcall' ] : '';
$desktopname = gethostname();
$insertsql = "INSERT INTO rx_call_disp_pcsetchanel (pcname, ptcall) VALUES (?, ?)
                      ON DUPLICATE KEY UPDATE ptcall = ?";
$insertstmt = mysqli_prepare( $conn, $insertsql );
if ( $insertstmt ) {
    mysqli_stmt_bind_param( $insertstmt, 'sss', $desktopname, $ptcall, $ptcall );
    mysqli_stmt_execute( $insertstmt );
    mysqli_stmt_close( $insertstmt );
}

mysqli_close( $conn );
?>
