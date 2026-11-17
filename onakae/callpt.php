<?php
header( 'Content-Type: text/html; charset=utf-8' );
date_default_timezone_set( 'Asia/Bangkok' );
ini_set( 'display_errors', 1 );
error_reporting( ~0 );

// include( 'connection.php' );

if ( isset( $_GET[ 'text' ] ) && $_GET[ 'text' ] != '' ) {
    $calltxt = htmlspecialchars( $_GET[ 'text' ] );
    $calltxt = rawurlencode( $calltxt );
    $callhtml = file_get_contents( 'https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $calltxt . '&tl=th-TH&speed=1' );
    $callplayer = '<audio id="audio" class ="display-hide" controls autoplay><source src="data:audio/mpeg;base64,' . base64_encode( $callhtml ) . '"></audio>';
    echo $callplayer;

}
?>

<!doctype html>
<html>

<head>
<meta charset = 'utf-8'>
<title>เรียกรับยา</title>

<style>
.display-hide {
    display: none;
}
</style>
</head>

<body>

</body>

</html>