<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(~0);
date_default_timezone_set('Asia/Bangkok');
include('connection.php');

$hn = TRIM($_POST['hn']);

$sql = "SELECT o.*,s.symptom FROM ovst o
LEFT OUTER JOIN opdscreen s ON o.vn=s.vn

WHERE o.hn = '$hn'
and o.vstdate between  DATE_ADD(curdate(), INTERVAL -12 MONTH) and curdate()   

    group by o.vn
    order by o.vstdate desc
    limit 50
 ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // echo '<p>';
    echo '<table>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {

        $vstdate = DateThai($row['vstdate'], 'd m ee');
        $hn = $row['hn'];
        $vn = $row['vn'];
        $hos_guid = $row['hos_guid'];
        $symptom = $row['symptom'];


        $rxsql = "SELECT 
        CONCAT(IF(d.name IS NULL,'',TRIM(d.name)),' ',IF(d.strength IS NULL,'',TRIM(d.strength))) AS drugname, 
        if(o.sp_use = '',CONCAT(u.name1,' ',u.name2,' ',u.name3),'')  AS usagename, 
        CONCAT('#',CAST(o.qty AS CHAR),' ',d.units) AS qty
        FROM opitemrece o 
        LEFT OUTER JOIN drugitems d ON o.icode=d.icode 
        LEFT OUTER JOIN patient p ON o.hn=p.hn 
        LEFT OUTER JOIN drugusage u ON o.drugusage = u.drugusage 
        WHERE o.vn='$vn'  
        AND LEFT(o.icode,1)='1'    
        AND o.vn <>''
        ";
        $rxdata = '';
        $rxresult = $conn->query($rxsql);
        if ($rxresult->num_rows > 0) {
            while ($rxrow = $rxresult->fetch_assoc()) {
                $drugname = $rxrow['drugname'];
                $usagename = $rxrow['usagename'];
                $qty = $rxrow['qty'];
                $rxdata .= $drugname . '\n';

            }

        }

        echo '<tr>'; //style="background-color: #FEF9E7;"
        echo '<td><span style="background-color:#FFBF00;">' . $vstdate . '</span>&nbsp;&nbsp;<span>' . $symptom . '</span>
             <span>' . $rxdata .  '</span> 
            </td>';

        echo '</tr>';

    }

    echo '</tbody>';
    echo '</table>';
    // echo '</p>';

} else {
    echo '';
}

$conn->close();

?>
