<?php

header( 'Content-Type: text/html; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( ~0 );
date_default_timezone_set( 'Asia/Bangkok' );
include 'connection.php';

$hn_digit = intval( get_hn_digit( $conn ) );
$keysearch = trim( $_POST[ 'hn' ] );
$length = strlen( $keysearch );

if ( $length < $hn_digit ) {
    $search = "o.oqueue =$keysearch";
} else {
    $search = "o.hn ='$keysearch'";
}

$chanel = trim( $_POST[ 'chanel' ] );

$sql = "SELECT
o.hos_guid,
    o.vn,
    o.hn,
    o.an,
    o.vstdate,
    o.vsttime,
    o.doctor,
    o.oqueue,
    o.pttype,
    o.spclty,
    o.hcode,
    o.cur_dep,
    o.cur_dep_busy,
    o.last_dep,
    o.cur_dep_time,
    o.rx_queue,
    o.diag_text,
    o.main_dep,
    o.main_dep_queue,
    p.last_visit,
    DoctorName(o.doctor) as doctorname,
    CONCAT(trim(p.pname),trim(p.fname),'  ',SUBSTRING(trim(p.lname), 1, 3),'xxx') AS pttvname,
    concat(trim(p.pname),trim(p.fname),'  ',trim(p.lname)) as ptname,
    concat(trim(p.fname),'  ',trim(p.lname)) as fname,
 
    p.informaddr,Telephone(o.hn) as informtel,
    m.image as ptimage,
    ThaiAge(p.birthday,CURDATE()) as age,
    y.name as pttype_name,
    p.drugallergy,
    if(s.fbs='',FORMAT(eGFR(o.vn), 0),FORMAT(s.fbs, 0)) as egfr,
    Diagnosis('OPD',o.vn,'1') as diag,
 if(s.cc IS NULL,s.symptom,s.cc) as symptom,s.bps,s.bpd,s.bmi,FORMAT(s.fbs, 0) as fbss,s.fbs,s.egfr as egfrs,s.ldl,s.bw,
FORMAT(s.bw, 1) AS bbw,
FORMAT(s.bps, 0) AS sbps,FORMAT(s.bpd, 0) AS sbpd,s.cc
FROM ovst o
LEFT OUTER JOIN opdscreen s ON o.vn=s.vn
LEFT OUTER JOIN patient p on o.hn=p.hn
LEFT OUTER JOIN patient_image m on p.hn = m.hn

LEFT OUTER JOIN pttype y on o.pttype=y.pttype

WHERE $search
and o.vstdate = curdate()

    group by o.vn
    order by o.vstdate desc
 ";

$result = $conn->query( $sql );

if ( $result->num_rows > 0 ) {



    echo '<table style="width:100%;" class="table">';
    echo '<tbody>';

    while ( $row = $result->fetch_assoc() ) {
        $hn = $row[ 'hn' ];
        $pttvname =$row[ 'pttvname' ];
        insert_tv($hn,$pttvname,$chanel,$conn);

        $vstdate = DateThai( $row[ 'vstdate' ], 'd m ee' );
        

        $drugallergysql = "SELECT  o.*   FROM opd_allergy o
        WHERE o.hn = '$hn'";

        $drugallergyresult = $conn->query( $drugallergysql );
        $ptdrugallergy = '';

        if ( $drugallergyresult->num_rows > 0 ) {
            while ( $drugallergyrow = $drugallergyresult->fetch_assoc() ) {
                $ptdrugallergy .= '<span style="background-color:#FF0000;color:#FFFFFF"><b>' . $drugallergyrow[ 'agent' ] . '</b>&nbsp;:&nbsp;' . $drugallergyrow[ 'symptom' ] . '</span><br>';
            }
        }
        // '<span style="background-color:#FF0000;color:#FFFFFF"><b>'.$drugallergys.'</b>&nbsp;</span><br>'
        if ( $ptdrugallergy != '' ) {
            $ptdrugallergy .= '<br>';
        }

        $lastvisitsql = "SELECT o.vstdate FROM ovst o WHERE hn = '$hn' AND o.vstdate <> CURDATE() AND o.vstdate IS NOT NULL 
        AND o.vstdate between DATE_ADD(curdate(), INTERVAL -6 MONTH) and curdate()
        ORDER BY o.vstdate DESC LIMIT 1";
        $lastvistresult = $conn->query( $lastvisitsql );
        $lastvisitrow = $lastvistresult->fetch_assoc();

        $lastvstdate = '';

        if ( $lastvisitrow && isset( $lastvisitrow[ 'vstdate' ] ) ) {
            $lastvstdate = DateThai( $lastvisitrow[ 'vstdate' ], 'd m ee' ) ;
        }

        // last oapp
        $lastoappsql = "SELECT o.nextdate,DoctorName(o.doctor) as doctorname FROM oapp o 
        WHERE o.hn = '$hn' 
        AND o.vstdate <> CURDATE() 
        AND o.nextdate IS NOT NULL 
        AND o.vstdate between DATE_ADD(curdate(), INTERVAL -6 MONTH) and curdate()
        ORDER BY o.vstdate DESC LIMIT 1";
        $lastoappresult = $conn->query( $lastoappsql );
        $lastoapprow = $lastoappresult->fetch_assoc();

        $lastnextdate = '';

        if ( $lastoapprow && isset( $lastoapprow[ 'nextdate' ] ) ) {
            $lastnextdate = DateThai( $lastoapprow[ 'nextdate' ], 'd m ee' ).'&nbsp;['.$lastoapprow[ 'doctorname' ].']';
        }

        $oappsql = "SELECT  o.*,DoctorName(o.doctor) as doctorname   FROM oapp o   
        WHERE o.hn = '$hn' 
        AND o.nextdate > curdate()
        -- AND o.vstdate = curdate()
        AND o.nextdate is not null
        ";

        $oappresult = $conn->query( $oappsql );
        $ptoapp = '';
        $ptoappnextdate = '';
        if ( $oappresult->num_rows > 0 ) {
            while ( $oapprow = $oappresult->fetch_assoc() ) {

                $ptoappnextdate = DateThai( $oapprow[ 'nextdate' ], 'd m ee' ) .'&nbsp;['.$oapprow[ 'doctorname' ].']' ;

                $ptoapp .= '<span style="background-color:#FF0000;color:#FFFFFF"><b>' . $oapprow[ 'nextdate' ] . '</b>&nbsp;:&nbsp;' . $oapprow[ 'note' ] .
                '&nbsp;&nbspแพทย์&nbsp;:&nbsp'.$oapprow[ 'doctorname' ] .
                '</span>';
            }
        }

        if ( $ptoapp != '' ) {
            $ptoapp .= '<br>';
        }

        $vn = $row[ 'vn' ];

        $lab = "SELECT l.lab_order_result,
            if(lg.lab_items_sub_group_name IS NULL,'',lg.lab_items_sub_group_name) AS lab_items_sub_group_name,           
            i.lab_items_name,
            h.order_date,h.report_date,
            i.lab_items_unit,
            IF(i.lab_items_normal_value IS NULL,i.lab_items_normal_value,concat('(',i.lab_items_normal_value,')')) AS lab_items_normal_value,
            i.lab_items_code 
            FROM lab_order l    
            LEFT JOIN lab_head h ON l.lab_order_number=h.lab_order_number   
            LEFT JOIN patient p ON h.hn=p.hn   
            LEFT JOIN lab_items i ON i.lab_items_code=l.lab_items_code   
            LEFT JOIN lab_items_sub_group lg ON lg.lab_items_sub_group_code = l.lab_items_sub_group_code   
            WHERE l.lab_order_number IN (SELECT lab_order_number FROM lab_head  hh 
            WHERE hh.hn=h.hn )
            AND  h.vn ='$vn'
            AND l.lab_order_result IS NOT NULL   
            AND l.lab_order_result<>'' AND l.confirm='Y'  
            ORDER BY h.report_date DESC,lg.lab_items_sub_group_name 
            ";

        $get_lab = mysqli_query( $conn, $lab );

        $labgr = '';
        $labgr1 = '';
        $labdata = '';
        $labs = '';
        if ( $get_lab->num_rows > 0 ) {

            $labs .= '<div style="background-color: #C4C9E3">'.  'Lab.'.'</div>';

            while( $rowlab = $get_lab->fetch_assoc() ) {

                $labgr = $rowlab[ 'lab_items_sub_group_name' ];

                $labs .= '<div style = "color:#1332A5">';

                if ( $labgr !== $labgr1 ) {

                    $labs .= '<div style="background-color: #EFFAEB">'. nl2br( 'ผล '.$rowlab[ 'lab_items_sub_group_name' ].'<br>' ).'</div>' ;
                }
                $labs .=  $rowlab[ 'lab_items_name' ].'   = '
                .'<font color=blue>'.$rowlab[ 'lab_order_result' ].'</font>  '
                .$rowlab[ 'lab_items_unit' ].' '.$rowlab[ 'lab_items_normal_value' ] ;

                $labs .= '</div>';

                $labgr1 = $rowlab[ 'lab_items_sub_group_name' ];
            }

            $labdata .= '<button onclick="displayovst()" class="collapsible" > 🧪&nbsp;ผลตรวจทางห้องปฏิบัติการ</button>   
            <div  class="content">' . $labs . '</div>';

        } else {
            $labdata = '';

        }

        $vstdiagsql = "SELECT v.icd10, if(i.name is null,trim(i.tname),trim(i.name)) as icdname   FROM ovstdiag v 
        LEFT OUTER JOIN icd101 i ON v.icd10 = i.code 
        WHERE v.vn = '$vn'";
        $vstdiagresult = $conn->query( $vstdiagsql );
        $doctordiag = '';
        if ( $vstdiagresult->num_rows > 0 ) {
            while ( $vstdiagrow = $vstdiagresult->fetch_assoc() ) {
                $doctordiag .= '<br><span><b>' . $vstdiagrow[ 'icd10' ] . '</b>&nbsp;&nbsp;' . $vstdiagrow[ 'icdname' ] . '</span>';
            }
        }

        $hos_guid = $row[ 'hos_guid' ];
        $symptom = $row[ 'symptom' ];
        $drugallergys = $row[ 'drugallergy' ];
        if ( $drugallergys != '' ) {
            $drugallergy = '<span style="background-color:#FF0000;color:#FFFFFF"><b>'.$drugallergys.'</b>&nbsp;</span><br>';
        } else {
            $drugallergy = '';
        }

        $diag = $row[ 'diag' ];
        $doctorname = $row[ 'doctorname' ];
        $ptname = $row[ 'ptname' ];
        $fname = $row[ 'fname' ];
        $age = $row[ 'age' ];
        $informaddr = $row[ 'informaddr' ];
        $informtel = $row[ 'informtel' ];
        $ptimage = $row[ 'ptimage' ];
        $pttype_name = $row[ 'pttype_name' ];

        $last_visit = DateThai( $row[ 'last_visit' ], 'd m ee' );
        $vsttime = substr( $row[ 'vsttime' ], 0, 5 );

        if ( $vsttime != '' ) {
            $vsttime = $vsttime .' น.';
        } else {
            $vsttime = '';
        }
        ;
        $doctor = $row[ 'doctor' ];
        $oqueue = $row[ 'oqueue' ];
        $pttype = $row[ 'pttype' ];
        $spclty = $row[ 'spclty' ];
        $hcode = $row[ 'hcode' ];
        $cur_dep = $row[ 'cur_dep' ];
        $cur_dep_busy = $row[ 'cur_dep_busy' ];
        $last_dep = $row[ 'last_dep' ];
        $cur_dep_time = $row[ 'cur_dep_time' ];
        $rx_queue = $row[ 'rx_queue' ];
        $diag_text = $row[ 'diag_text' ];

        $main_dep = $row[ 'main_dep' ];
        $main_dep_queue = $row[ 'main_dep_queue' ];
        $sbpd = $row[ 'sbpd' ];
        if ( $sbpd == '0' ) {
            $sbpd = '-';
        }
        $sbps = $row[ 'sbps' ];
        if ( $sbpd == '0' ) {
            $sbpd = '-';
        }
        $cc = $row[ 'cc' ];
        $fbss = $row[ 'fbss' ];
        if ( $fbss == '0' ) {
            $fbss = '';
        }

        $egfrs = $row[ 'egfr' ];
        if ( $egfrs == '0' ) {
            $egfrs = '';
        }

        $bw = '  <b>'.$row[ 'bbw' ].'</b> Kg.';
        $ffbs = '  <b>'.$row[ 'fbs' ].'</b> mg/dl';
        $eegfr = '  <b>'.$row[ 'egfr' ].'</b>';

        $calltime = '';

        $rxdata = '';
        $rxtime = '';
        $rx_time = '';
        $dispvstdate = '';
        $dispvsttime = '';
        $disprxtime = '';
        $dispcashiertime = '';
        $dispstickertime = '';
        $dispentime = '';
        $datadisptime = [];
        $data_disptime = '';

        $disptimesql = "SELECT vn,hn,
        vstdate,vsttime,rxtime,cashiertime,stickertime,dispentime,
        TIMEDIFF(rxtime,vsttime) as timerx,
        TIMEDIFF(cashiertime,rxtime) as timecash,
        TIMEDIFF(stickertime,cashiertime) as timesticker,
        TIMEDIFF(cashiertime,dispentime) as timedisp,
        TIMEDIFF(dispentime,rxtime) as timedisprx,
        TIMEDIFF(dispentime,vsttime) as timedispvst

       FROM rx_disp_time 
        WHERE vn='$vn'   ";
        $dispresult = $conn->query( $disptimesql );
        if ( $dispresult->num_rows > 0 ) {

            $disprow = $dispresult->fetch_assoc() ;
            $dispvstdate = $disprow[ 'vstdate' ];
            $dispvsttime = $disprow[ 'vsttime' ];
            $disprxtime = $disprow[ 'rxtime' ];
            $dispcashiertime = $disprow[ 'cashiertime' ];
            $dispstickertime = $disprow[ 'stickertime' ];
            $dispentime = $disprow[ 'dispentime' ];
            // $datadisptime = [ $vn, $hn, $dispvstdate, $dispvsttime, $disprxtime, $dispcashiertime, $dispstickertime, $dispentime ];
            // $data_disptime = json_encode( $datadisptime );
        } else {
            $disprow = $dispresult->fetch_assoc() ;
            $dispvstdate = $row[ 'vstdate' ];
            $dispvsttime = $row[ 'vsttime' ];
            $disprxtime = '';
            // $disprow[ 'rxtime' ];
            $dispcashiertime = '';
            // $disprow[ 'cashiertime' ];
            $dispstickertime = '';
            // $disprow[ 'stickertime' ];
            $dispentime = '';
            // $disprow[ 'dispentime' ];

        }

        $rxsql = "SELECT
        CONCAT(
            IF(s.name IS NULL, '', TRIM(s.name)),
            ' ',
            IF(s.strength IS NULL, '', TRIM(s.strength))
        ) AS drugname,
        IF(
            o.sp_use = '',
            CONCAT(u.name1, ' ', u.name2, ' ', u.name3),
            (
                SELECT TRIM(CONCAT(sp.name1, ' ', sp.name2, ' ', sp.name3))
                FROM sp_use sp
                WHERE sp.sp_use = o.sp_use
            )
        ) AS usagename,
        IF(
            o.sp_use = '',
            IF(u.name1 IS NULL, '', u.name1),
            (
                SELECT IF(sp1.name1 IS NULL, '', sp1.name1)
                FROM sp_use sp1
                WHERE sp1.sp_use = o.sp_use
            )
        ) AS line1,
        IF(
            o.sp_use = '',
            IF(u.name2 IS NULL, '', u.name2),
            (
                SELECT IF(sp2.name2 IS NULL, '', sp2.name2)
                FROM sp_use sp2
                WHERE sp2.sp_use = o.sp_use
            )
        ) AS line2,
        IF(
            o.sp_use = '',
            IF(u.name3 IS NULL, '', u.name3),
            (
                SELECT IF(sp3.name3 IS NULL, '', sp3.name3)
                FROM sp_use sp3
                WHERE sp3.sp_use = o.sp_use
            )
        ) AS line3,
        d.therapeutic,
        d.units,
        o.unitprice,
        FORMAT(o.sum_price, 2) AS sum_price,
        o.sum_price as sumprice,
        o.rxtime,
        r.rx_time,
        u.shortlist,
        o.hos_guid,
        o.drugusage,
        o.sp_use,
        o.qty,
        IF(LEFT(o.icode,1)='1','0','1') AS ind,
        IF(o.icode IN (SELECT l.icode FROM lab_items l WHERE l.active_status='Y'),'1','0') as lab,
        o.icode
    FROM opitemrece o
    LEFT OUTER JOIN drugitems d ON o.icode = d.icode
    LEFT OUTER JOIN s_drugitems s ON o.icode = s.icode
    LEFT OUTER JOIN patient p ON o.hn = p.hn
    LEFT OUTER JOIN drugusage u ON o.drugusage = u.drugusage AND u.status = 'Y'
    LEFT OUTER JOIN rx_operator r ON o.vn = r.vn AND r.login IN (
        SELECT x.loginname FROM opduser x WHERE x.groupname = 'ห้องยา'
    )
    WHERE o.vn = '$vn'
        -- AND LEFT(o.icode, 1) = '1'
        AND o.vn <> ''
    ORDER BY lab,o.icode,o.item_no;
    
        ";

        $rxresult = $conn->query( $rxsql );
        if ( $rxresult->num_rows > 0 ) {

            // $disprxtime = '';
            // $disprow[ 'rxtime' ];

            // $dispcashiertime = '';
            // $disprow[ 'cashiertime' ];
            // $dispstickertime = '';
            // $disprow[ 'stickertime' ];
            // $dispentime = '';
            // $disprow[ 'dispentime' ];

            $count = 0;
            // $rxdata .= '<form id="formprint">';
            $rxdata .= '<table style="width:100%;" class="tablex" id="rxdata">';
            $rxdata .= '<tr>';

            $rxdata .=  '<th class="centered">No</th>';
            $rxdata .=  '<th class="centered">รายการ</th>';
            $rxdata .=  '<th class="centered">จำนวน</th>';
            $rxdata .=  '<th class="centered">วิธีบริหารยา</th>';
            $rxdata .=  '<th class="centered">ค่ารักษา</th>';
            $rxdata .=  '<th class="centered"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></th>';
            // $rxdata .=  '<th class="centered"><i class="fa fa-print" aria-hidden="true"></i></th>';
            $rxdata .=  '</tr>';
            while ( $rxrow = $rxresult->fetch_assoc() ) {

                if ( $disprxtime == '' ) {
                    $disprxtime = $rxrow[ 'rxtime' ];
                }

                if ( $dispstickertime == '' ) {
                    $dispstickertime = $rxrow[ 'rx_time' ];
                }

                $hos_guid = $rxrow[ 'hos_guid' ];

                $drugname = $rxrow[ 'drugname' ];
                $usagename = $rxrow[ 'usagename' ];

                $drugusage = $rxrow[ 'drugusage' ];
                $sp_use = $rxrow[ 'sp_use' ];
                $shortlist = $rxrow[ 'shortlist' ];

                $line1 = $rxrow[ 'line1' ];
                $line2 = $rxrow[ 'line2' ];
                $line3 = $rxrow[ 'line3' ];
                $line = [ $shortlist, $line1, $line2, $line3, $drugusage, $sp_use, $hos_guid ];
                $line_json = json_encode( $line);

                $therapeutic = $rxrow[ 'therapeutic' ];
                $units = $rxrow[ 'units' ];
                $sum_price = $rxrow[ 'sum_price' ];
                $sumprice = $rxrow[ 'sumprice' ];
                $icode = $rxrow[ 'icode' ];

                // $fvsttime = strtotime( $row[ 'vsttime' ] );
                // $frxtime = strtotime( $rxrow[ 'rxtime' ] );
                // $time_difference_seconds = abs( $frxtime - $fvsttime );
                // $timewait1 = '';

                // if ( $time_difference_seconds >= 3600 ) {
                //     $hours = floor( $time_difference_seconds / 3600 );
                //     $minutes = floor( ( $time_difference_seconds % 3600 ) / 60 );
                //     $timewait1 = "$hours hr.&nbsp;$minutes min";
                // } else {
                //     $minutes = round( $time_difference_seconds / 60, 2 );
                //     $timewait1 = "$minutes min";
                // }
                // $rxtime = substr( $rxrow[ 'rxtime' ], 0, 5 );
                // if ( $rxtime != '' ) {
                //     $rxtime = $rxtime .' น.'.' ['.$timewait1.']';
                // } else {
                //     $rxtime = '';
                // }
                // ;

                // $rx_time = substr( $rxrow[ 'rx_time' ], 0, 5 );
                // if ( $rx_time != '' ) {
                //     $rx_time = $rx_time .' น.';
                // } else {
                //     $rx_time = '';
                // }
                // ;

                if ( $therapeutic === '' || $therapeutic === null ) {
                    $therapeutic = '';
                } else {
                    $therapeutic = '<span> ('.$rxrow[ 'therapeutic' ].')</span><br>';
                }
                $qty = $rxrow[ 'qty' ];
                $lab = $rxrow[ 'lab' ] ;

                $ind = $rxrow[ 'ind' ];


                if ( $ind == '0' ) {
                $rxdata .=  '<tr style="background-color:#F9E79F;width:100%;">';
                $rxdata .=  '<td class="centered">'.++$count.'</td>';
                } else {
                    $rxdata .=  '<tr style="background-color:#F4F6F6;width:100%;">';
                    IF($lab=='1'){
                        $rxdata .=  '<td class="centered">🧪</td>';
                    } else {
                        $rxdata .=  '<td class="centered">📝</td>';
                    }
                    
                }

                
                $rxdata .=  '<td class="hide-column" id="' . $hos_guid . '"> '.$hos_guid.'</td>';
                $rxdata .=  '<td id="drugname_' . $hos_guid . '"><b> '.$drugname.'</b></td>';
                $rxdata .=  '<td id="units_' . $hos_guid . '" class="centered"><b>'.$qty.'</b>&nbsp;&nbsp;'.$units.'</td>';
                $rxdata .=  '<td id="shortlist_' . $hos_guid . '" style="margin-left: 10px;">'.$shortlist.'</td>';
                $rxdata .=  '<td id="sum_price_" class="centered">'.$sum_price.'</td>';
                $rxdata .=  '<td id="sumprice_" style="margin-left: 10px;" class="sumprice">'.$sumprice.'</td>';
                if ( $ind == '0' ) {
                    $rxdata .=  '<td style="width:1%;" class="centered"><button onclick="displayformdrugusage(' . htmlspecialchars( $line_json, ENT_QUOTES, 'UTF-8' ) . ')" class="btn btn-link btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>';
                    // $rxdata .=  '<td style="width:5%;" class="centered"><input type="checkbox" id="chk[]" name="chk[]" value="'.$icode.'" style="cursor: pointer;"></td>';

                } else {
                    $rxdata .=  '<td style="width:1%; " class="centered"></td>';
                    $rxdata .=  '<td style="width:5%;" class="centered"></td>';

                }

                $rxdata .=  '<td class="hide-column" id="sp_use_' . $hos_guid . '"> '.$sp_use.'</td>';
                $rxdata .=  '<td class="hide-column" id="drugusage_' . $hos_guid . '"> '.$drugusage.'</td>';

                $rxdata .=  '</tr>';
                // $therapeutic . '<br>';

            }
            if ( $dispvsttime == '' ) {
                $dispvsttime = $row[ 'vsttime' ];
            }

            $currentDateTime = new DateTime();
            $calltime = $currentDateTime->format( 'H:i:s' );
            $datadisptime = [ $vn, $hn, $dispvstdate, $dispvsttime, $disprxtime, $dispcashiertime, $dispstickertime, $calltime, $dispentime ];
            $data_disptime = json_encode( $datadisptime );

            $rxdata .=  '<tfoot>';
            $rxdata .=  '<tr>';
            $rxdata .=  '<td>&nbsp;</td>';
            $rxdata .=  '<td>&nbsp;</td>';
            $rxdata .=  '<td>&nbsp;</td>';
            $rxdata .=  '<td class="centered">รวมค่ารักษา</td>';
            $rxdata .=  '<td class="centered"><b><span  id="txtSumprice"></span></b></td>';
            $rxdata .=  '<td></td>';
            $rxdata .=  '<td></td>';
            $rxdata .=  '</tr>';
            $rxdata .=  '</tfoot>';

            $rxdata .=  '</table>';
            // $rxdata .= '</form>';
        } else {
            $rxdata = '❌ไม่พบรายการยา';
        }
// onclick="callptname(\''.$fname.'\')"
        $img = '';
        if ( $ptimage != null ) {
            $img = '<img id="hover-image" onclick="save_call_pt()" style="width: 100px; height: 120px;cursor: pointer;margin-top: 10px;" src="data:image/jpeg;base64,'.base64_encode( $ptimage ).'"   alt=""/>';
        } else {
            $img = '<img id="hover-image" onclick="save_call_pt()" style="width: 100px; height: 120px;cursor: pointer;margin-top: 10px;"  src="person1.png"   alt=""  />';

        }

        echo '<tr style="background-color:#EBF5FB;width:100%;">';
        echo '<td style="width:100%;  background-color: #F8F9F9;">
            <table style="width:100%;" class="b-table">
                <tr class="acontainer">
                    <td style="width: 180px;">
                    <div class="container">'.$img.'<div class="middle">
                    <button class="text" onclick="save_call_pt()" >เรียกชื่อรับยา</button>                      
                    <span class="hide-column" id="fname">'.$fname.'</span>      
                    <span class="hide-column" id="enptname">'.$ptname.'</span> 
                    </div> 
                 </div>
                
                
                 </td>

                    <td  style="width:50%;">

                        <span class="bbw" id ="ptname_'.$hos_guid.'" >'.$ptname.'&nbsp;&nbsp;'.$age.'</span><span></span><br>
                       
                        <span style="background-color: #EAECEE;">HN :</span><span class="bw" id ="lblHn">'.$hn.'</span>&nbsp;&nbsp;
                        <span style="background-color: #EAECEE;">VN :</span><span class="bw" id ="lblVN">'.$vn.'</span>&nbsp;&nbsp;
                        <span style="background-color: #EAECEE;">Q :</span><span class="bw" id ="q_'.$hos_guid.'">'.$oqueue.'</span><br>
                        <span style="background-color: #EAECEE;">สิทธิ :</span>&nbsp;&nbsp;<span class="bw" id ="pttype_name_'.$hos_guid.'">&nbsp;'.$pttype_name.'</span><br>
                        <span style="background-color: #EAECEE;">ที่อยู่ :</span>&nbsp;&nbsp;<span class="bw" id ="informaddr_'.$hos_guid.'">&nbsp;'.$informaddr.'</span><br>
                        <span style="background-color: #EAECEE;">📞 :</span>&nbsp;&nbsp;<span class="bw" id ="informtel_'.$hos_guid.'">&nbsp;'.$informtel.'</span><br>
                        <span style="background-color: #EAECEE;">แพ้ยา :</span>&nbsp;&nbsp;'.$ptdrugallergy.'</td>

                    <td class="texttitle" style="width:30%;">
                    การนัดหมาย<br>
                    <span style="background-color: #EAECEE;">มาครั้งที่แล้ว </span><span style="background-color: #FFFFFF;">&nbsp;'.$lastvstdate.'</span><br>
                    <span style="background-color: #EAECEE;">นัดครั้งที่แล้ว </span><span style="background-color: #FFFFFF;">&nbsp;'.$lastnextdate.'</span><br>
                    <span style="background-color: #EAECEE;">📅นัดครั้งถัดไป </span><span style="background-color: #FFFFFF;">&nbsp;'.$ptoappnextdate.'</span><br>
                    </td>
                    <td class="texttitle" style="width:20%;">
                    
                    <span style="background-color: #EAECEE;">มารพ. </span><span style="background-color: #FFFFFF;">&nbsp;'.$dispvsttime.'</span><br>
                    <span style="background-color: #EAECEE;">แพทย์สั่งยา. </span><span style="background-color: #FFFFFF;">&nbsp;'.$disprxtime.'</span><br>
                    <span style="background-color: #EAECEE;">บันทึกการเงิน. </span><span id=""cashiertime style="background-color: #FFFFFF;">&nbsp;'.$dispcashiertime.'</span><br>
                    <span style="background-color: #EAECEE;">พิมพ์ฉลากยา. </span><span style="background-color: #FFFFFF;">&nbsp;'.$dispstickertime.'</span><br>
                    <span style="background-color: #EAECEE;">เรียกรับยา. </span><span id="calltime" style="background-color: #FFFFFF;">&nbsp;'.$calltime.'</span><br>
                    <button onclick="savedispen(' . htmlspecialchars( $data_disptime, ENT_QUOTES, 'UTF-8' ) . ')" id="btnSaveDisp" class="btn btn-danger  btn-sm"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                  บันทึกจ่ายยา</button>
                    </td>

                    </tr>

                   
            </table>';
        echo '</td>';
        //  <br><button onclick = "callptname(\'' . $fname . '\')" style = 'width:100%;'>เรียกชื่อรับยา</button>

        echo '</tr>';

        echo '<tr style="background-color:#D6DBDF;">';
        //style = 'background-color: #FEF9E7;'
        // '<span style="background-color:#F8F9F9;"  >eGFR :&nbsp;<b>'.$egfrs.'</b></span>' .
        // <button onclick="printSticker('.$vn.')" class="btn btn-link  btn-sm"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;พิมพ์ฉลากยา</button>
        echo '<td>
        <span style="background-color:#CCCCFF;"  >&nbsp;'.$vstdate.'&nbsp;</span>

        &nbsp;&nbsp;<span>'.$symptom.'</span><br>
       
        <span style="background-color:#F8F9F9;"  >BP:&nbsp; <b>'.$sbps.'/'.$sbpd.'</b></span>&nbsp;&nbsp;' .
        '<span style="background-color:#F8F9F9;"  >BW :&nbsp;'.$bw.'</span>&nbsp;&nbsp;' .
        '<span style="background-color:#F8F9F9;"  >FBS :&nbsp;<b>'.$fbss.'</b></span>&nbsp;&nbsp;' .
        

        '<p  style="background-color:#FFFFFF;" >
             <span style="background-color:#DFFF00; width:100%;">&nbsp;รายการยาที่ได้รับ</span>
           
             <br>'
             .$rxdata.

        '</p>'.
        $labdata.
        '<br><span style="background-color:#F8F9F9;"  >แพทย์ :&nbsp;&nbsp;<b>'.$doctorname .'</b></span><br>
        <span style="background-color:#F8F9F9;"  >Diagnosis&nbsp;</span>'.

        $doctordiag.

        '</td>';

        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

    echo '<br><span style="background-color:#F8F9F9;"  >ประวัติการรักษาย้อนหลัง</span><br>';
    // echo '<br>';
    ////////////////////////////////

    $sql = "SELECT o.hn,o.vn,o.vstdate,o.hos_guid,
    
    if(s.cc IS NULL,s.symptom,s.cc) as symptom,s.bps,s.bpd,s.bmi,s.fbs,s.egfr,s.ldl,
    DoctorName(o.doctor) as doctorname
    FROM ovst o
    LEFT OUTER JOIN opdscreen s ON o.vn=s.vn
    WHERE o.hn = '$hn'
    AND o.vstdate BETWEEN  DATE_ADD(curdate(), INTERVAL -12 MONTH) AND curdate()  
    AND o.vstdate <> curdate() 
    GROUP BY o.vn
    ORDER BY o.vstdate DESC
    LIMIT 50
    ";
    $result = $conn->query( $sql );

    if ( $result->num_rows > 0 ) {
        echo '<table style="width:100%;" class="w3-table">';
        echo '<tbody>';

        while ( $row = $result->fetch_assoc() ) {

            $vstdate = DateThai( $row[ 'vstdate' ], 'd m ee' );
            $hn = $row[ 'hn' ];
            $vn = $row[ 'vn' ];
            $hos_guid = $row[ 'hos_guid' ];
            $symptom = $row[ 'symptom' ];
            $doctorname = $row[ 'doctorname' ];

            $rxsql = "SELECT 
            CONCAT(IF(d.name IS NULL,'',TRIM(d.name)),' ',IF(d.strength IS NULL,'',TRIM(d.strength))) AS drugname, 
            if(o.sp_use = '',CONCAT(u.name1,' ',u.name2,' ',u.name3),'')  AS usagename, 
            d.therapeutic,u.shortlist,
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
            $rxresult = $conn->query( $rxsql );
            if ( $rxresult->num_rows > 0 ) {
                $count = 1;
                while ( $rxrow = $rxresult->fetch_assoc() ) {
                    $drugname = $rxrow[ 'drugname' ];
                    $usagename = $rxrow[ 'usagename' ];
                    $therapeutic = $rxrow[ 'therapeutic' ];
                    $shortlist = $rxrow[ 'shortlist' ];
                    if ( $therapeutic === '' || $therapeutic === null ) {
                        $therapeutic = '';
                    } else {
                        $therapeutic = '<span> (' . $rxrow[ 'therapeutic' ] . ')</span><br>';
                    }
                    $qty = $rxrow[ 'qty' ];

                    $rxdata .= '<span style="background-color:#9FF9F4;">' . $count . '.&nbsp;<b>' . $drugname . '</b></span>' . '&nbsp;&nbsp;' .
                    '<span  style="background-color:#D5D8DC;">' . $qty . '</span>&nbsp;&nbsp;' .
                    '<span>' . $shortlist . '</span><br>';
                    $count++;
                }
            } else {
                $rxdata = '-<br>';
            }

            $vstdiagsql = "SELECT v.icd10, if(i.name is null,trim(i.tname),trim(i.name)) as icdname   FROM ovstdiag v 
            LEFT OUTER JOIN icd101 i ON v.icd10 = i.code 
            WHERE v.vn = '$vn'";
            $vstdiagresult = $conn->query( $vstdiagsql );
            $doctordiag = '';
            if ( $vstdiagresult->num_rows > 0 ) {
                while ( $vstdiagrow = $vstdiagresult->fetch_assoc() ) {
                    $doctordiag .= '<br><span><b>' . $vstdiagrow[ 'icd10' ] . '</b>&nbsp;&nbsp;' . $vstdiagrow[ 'icdname' ] . '</span>';
                }
            }

            echo '<tr style="background-color:#FFFFFF;">';
            echo '<td>';
            echo '<button onclick="displayovst()" class="collapsible" ><i class="fa fa-user-md" aria-hidden="true"></i>&nbsp;' . $vstdate . '&nbsp;&nbsp;&nbsp;' . $symptom . '</button>   
            <div id="div_' . $hn . '" class="content">  
            <span style="background-color:#DFFF00; width:100%;">รายการยาที่ได้รับ</span><br>
            <span  style="background-color:#FFFFFF;"  > '
            . $rxdata .'</span>  
            <span style="background-color: #EAECEE;" >แพทย์ :&nbsp;&nbsp;</span><span style="background-color:#F8F9F9;" ><b>&nbsp;&nbsp;'.$doctorname .'</b></span><br>
            <span style="background-color:#EAECEE;"  >Diagnosis&nbsp;</span>'.
            $doctordiag.
            '</div>
           
            </td>';

            echo '</tr>';

        }

        echo '</tbody>';
        echo '</table>';

    } else {
        echo '-';
    }
} else {
    echo '';
}

$conn->close();

function get_hn_digit( $conn ) {
    // include 'connection.php';
    $sql = "select sys_value from sys_var where sys_name='hn_digit'";
    $result = $conn->query( $sql );
    $row = $result->fetch_assoc();
    if ( $row[ 'sys_value' ] != null ) {
        return $row[ 'sys_value' ];
    } else {
        $lastvstdate = '7';
    }
}

function insert_tv($hn,$pttvname,$chanel,$conn){
    $sql = "INSERT INTO rx_tv_display 
    (hn,ptname,calltime,chanel)
    VALUES('$hn','$pttvname',NOW(),'$chanel')
    ON DUPLICATE KEY UPDATE ptname='$pttvname',chanel='$chanel',calltime=NOW()
    ";
    $result = $conn->query( $sql );
}

?>