<?php
header( 'Content-Type: text/html; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( ~0 );
date_default_timezone_set( 'Asia/Bangkok' );
include( 'connection.php' );
echo '<table class="table table-borderless table-pimary" style="width:100%;">';
echo '<thead>';
echo '<tr style="background-color: #82E0AA;" >';
echo '<th style="width:80%;"><h1 style="background-color: #ABEBC6;text-align:center;font-size: 4rem;color: #566573">ชื่อผู้รับบริการ</h1></th>';
echo '<th class="centered" style="width:20%;" ><h1  style= "background-color: #ABEBC6;text-align:center;font-size: 2rem;color: #2875665734A6">ช่องจ่ายยา</h1></th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
$sql = 'SELECT *  FROM rx_tv_display';
$result = $conn->query( $sql );

if ( $result->num_rows > 0 ) {

    $count = 1;
    while ( $row = $result->fetch_assoc() ) {

        $hn = $row[ 'hn' ];
        $ptname = $row[ 'ptname' ];
        $chanel = $row[ 'chanel' ];

        echo '<tr style="background-color: #82E0AA;font-size: 14px;" >';
        echo '<td  id="ptname_' . $hn . '" ><span class="fill-span" style="background-color: #ABEBC6;font-size: 4rem;color: #2874A6">' . $ptname . '</span></td>';
        echo '<td  id="hn_' . $hn . '"><span class="fill-span" style="background-color: #FEF9E7;text-align:center;font-size: 4rem;color: #2874A6">' . $chanel . '</span></td>';
        echo '</tr>';


    }
} else {
    echo '';
}
echo '</tbody>';
echo '</table>';
$conn->close();