<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
ini_set('display_errors', 1);
error_reporting(~0);

include('connection.php');

if (isset($_GET['text'])) {
    $txt = htmlspecialchars($_GET['text']);
    $txt = rawurlencode($txt);
    $html = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $txt . '&tl=th-TH');
    $player = '<audio id="audio" class ="display-hide" controls autoplay><source src="data:audio/mpeg;base64,' . base64_encode($html) . '"></audio>';
    echo $player;
}
?>
<!doctype html>
<html>

    <head>
        <meta charset="utf-8">
        <title>เรียกรับยา</title>
        
        <style>
            .display-hide {
                display: none;
            }
        </style>
    </head>

    <body>
        <form method="GET">
            <label for="text">Enter text:</label>
            <input type="text" id="text" name="text" required>
            <button type="submit">Submit</button>
        </form>
    </body>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // var urlParams = new URLSearchParams(window.location.search);
            // var stockname = urlParams.get('stockname');


            $("#txtSEARCH").focus();

        });

    </script>

</html>