<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(~0);
date_default_timezone_set('Asia/Bangkok');
include('connection.php');

$hn = TRIM($_GET['hn']);

$sql = "SELECT o.*,
s.symptom,s.bps,s.bpd,s.bmi,s.fbs,s.egfr,s.ldl
FROM ovst o
LEFT OUTER JOIN opdscreen s ON o.vn=s.vn

WHERE o.hn = '$hn'
and o.vstdate between  DATE_ADD(curdate(), INTERVAL -12 MONTH) and curdate()   

    group by o.vn
    order by o.vstdate desc
    limit 50
 ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // echo '<br>';
    echo '<table style="width:100%;" class="w3-table">';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {

        $vstdate = DateThai($row['vstdate'], 'd m ee');
        $hn = $row['hn'];
        $vn = $row['vn'];
        $hos_guid = $row['hos_guid'];
        $symptom = $row['symptom'];
        if ($row['bpd'] != '0' && $row['bps'] != '0') {


            $bpdval = intval($row['bpd']);
            $bpsval = intval($row['bps']);
            $bpval = '👍';
            if ($bpdval > 120 || $bpsval > 80) {
                $bpval = '🔺';
            } else {
                if ($bpdval < 100 || $bpsval < 60) {
                    $bpval = '🔻';
                }
            }

            $bp = '<span>ค่าความดันเลือด : </span><span><b>' . $row['bps'] . '/' . $bp = $row['bpd'] . '</b>&nbsp;&nbsp;mmHg&nbsp;' . $bpval . '<br></span>';
        } else {
            $bp = '';
        }


        // if ($row['bmi'] != '0') {
        //     $bmi = '<span>&nbsp;,&nbsp;&nbsp;ค่า BMI : </span>&nbsp;<span><b>' . $row['bmi'] . '</b></span>';
        // } else {
        //     $bmi = '';
        // }


        if ($row['fbs'] != '0') {
            $fbsAsInt = intval($row['fbs']);
            $value = '👍';
            if ($fbsAsInt > 107) {
                $value = '🔺';
            } else {
                if ($fbsAsInt < 70) {
                    $value = '🔻';
                }
            }

            $fbs = '<span>ค่าน้ำตาลในเลือด : </span><span><b>&nbsp;' . $row['fbs'] . '</b>&nbsp;&nbsp;mg/dl&nbsp;' . $value . '</span><br>';
        } else {
            $fbs = '';
        }

        if ($row['ldl'] != '0') {
            $ldlAsInt = intval($row['ldl']);
            $ldlvalue = '👍';
            if ($ldlAsInt > 130 && $ldlAsInt < 160 ) {
                $ldlvalue = '(ก้ำกึ่งสูง)🔺';
            } else {
                if ($ldlAsInt >=160) {
                    $ldlvalue = '(สูง)🔺';
                }
            }

            $ldl = '<span>ค่าไขมันในเลือด(LDL) : </span><span><b>&nbsp;' . $row['ldl'] . '</b>&nbsp;&nbsp;mg/dl&nbsp;' . $ldlvalue . '</span><br>';
        } else {
            $ldl = '';
        }
        
        
        if ($row['egfr'] != '0') {
            $egfrAsInt = intval($row['egfr']);
            if($egfrAsInt >= 90){
                $egfrvalue ='(ไตทำงานปกติ) 👍';
            } else {
                if($egfrAsInt >= 60 && $egfrAsInt < 89 ){
                    $egfrvalue ='(การทำงานของไตมีผิดปกติเล็กน้อย)';
                } else {
                    if($egfrAsInt >=30 && $egfrAsInt < 60 ){
                        $egfrvalue ='(การทำงานของไตทำงานปานกลาง)';
                    } else {
                        if($egfrAsInt >= 15 && $egfrAsInt < 30 ){
                            $egfrvalue ='(การทำงานของไตทำงานผิดปกติมาก)';
                        } else {
                            if($egfrAsInt < 15 ){
                                $egfrvalue ='(การทำงานของไตอยู่ภาวะไตวาย)';
                            } else {
                                
                            }                             
                        }                         
                    }                        
                }               
            }

            
            $egfr = '<span>ค่าทำงานของไต : </span><span><b>&nbsp;' . $row['egfr'] . '</b>&nbsp;&nbsp;mL/min/1.73 mm<br>' . $egfrvalue . '</span>';
        } else {
            $egfr = '';
        }



        $rxsql = "SELECT 
        CONCAT(IF(d.name IS NULL,'',TRIM(d.name)),' ',IF(d.strength IS NULL,'',TRIM(d.strength))) AS drugname, 
        if(o.sp_use = '',CONCAT(u.name1,' ',u.name2,' ',u.name3),'')  AS usagename, 
        d.therapeutic,
        CONCAT(CAST(o.qty AS CHAR),' ',d.units) AS qty
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
            $count = 1;
            while ($rxrow = $rxresult->fetch_assoc()) {
                $drugname = trim($rxrow['drugname']);
                $usagename = trim($rxrow['usagename']);
                $therapeutic = trim($rxrow['therapeutic']);
                if ($therapeutic === '' || $therapeutic === null) {
                    $therapeutic = '';
                } else {
                    $therapeutic = "<span> (" . trim($rxrow['therapeutic']) . ")</span><br>";
                }
                $qty = $rxrow['qty'];
                $rxdata .= '<span style="background-color:#9FF9F4;">' . $count . '.' . $drugname . '</span>' . ' ' .
                    '<span  style="background-color:#D5D8DC;">' . $qty . "</span><br>" .
                    '<span>' . $usagename . '</span>' . "<br>" .

                    $therapeutic . "<br>";
                $count++;

            }

        } else {
            $rxdata = '-';
        }

        echo '<tr style="background-color:#D6DBDF;">'; //style="background-color: #FEF9E7;"
        echo '<td>

        <span style="background-color:#FFBF00;"  >&nbsp;' . $vstdate . '&nbsp;</span>
 
        &nbsp;&nbsp;<span>' . $symptom . '</span><br>' .
            '<span style="background-color:#F8F9F9;"  >' . $bp . '</span>' .
            '<span style="background-color:#F8F9F9;"  >' . $fbs . '</span>' .
            '<span style="background-color:#F8F9F9;"  >' . $ldl . '</span>' .
            '<span style="background-color:#F8F9F9;"  >' . $egfr . '</span>' .

            '<p  style="background-color:#FFFFFF;" > 
             <span style="background-color:#DFFF00; width:100%;">รายการยาที่ได้รับ</span><br>' . $rxdata .

            '</p>   
           
            </td>';

        echo '</tr>';

    }

    echo '</tbody>';
    echo '</table>';
    // echo '<br>';

} else {
    echo '----';
}

$conn->close();

?>
