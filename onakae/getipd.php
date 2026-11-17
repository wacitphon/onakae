<?php

header( 'Content-Type: text/html; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( ~0 );
date_default_timezone_set( 'Asia/Bangkok' );
include 'connection.php';

$an_digit = intval( get_an_digit( $conn ) );
$keysearch = trim( $_POST[ 'hn' ] );
$length = strlen( $keysearch );
$chanel = trim( $_POST[ 'chanel' ] );

if ( $length < $an_digit ) {
    $search = "i.hn =$keysearch";

} else {
    $search = "(i.an ='$keysearch' OR i.hn= '$keysearch') ";

}

$sql = "SELECT
    concat(trim(p.pname),trim(p.fname),'  ',trim(p.lname)) as ptname,
    concat(trim(p.fname),'  ',trim(p.lname)) as fname, 
    p.informaddr,p.informtel,
    m.image as ptimage,
    ThaiAge(p.birthday,CURDATE()) as age,
    DoctorName(i.admdoctor) as doctorname,
    DoctorName(i.incharge_doctor) as incharge_doctorname,
    CONCAT(trim(p.pname),trim(p.fname),'  ',SUBSTRING(trim(p.lname), 1, 3),'xxx') AS pttvname,
    y.name as pttype_name,
    p.drugallergy,w.name as wardname,
    i.*   
    FROM ipt i
    LEFT  JOIN ward w on i.ward=w.ward
    LEFT OUTER JOIN patient p on i.hn=p.hn
    LEFT OUTER JOIN patient_image m on p.hn = m.hn
    LEFT OUTER JOIN pttype y on i.pttype=y.pttype
    WHERE $search
    AND (i.dchdate IS NULL OR i.dchdate = curdate())
    -- i.an IN (SELECT x.an FROM ipt_order_no x WHERE x.order_type ='HMe'))
    LIMIT 1
    ";
//  echo  '<p>'.$sql.'</p>';

$result = $conn->query( $sql );

if ( $result->num_rows > 0 ) {

    echo '<table style="width:100%;" class="table">';
    echo '<tbody>';

    while ( $row = $result->fetch_assoc() ) {

        $regdate = DateThai( $row[ 'regdate' ], 'd m ee' );
        $dchdate = '';
        if ( $row[ 'dchdate' ] != null ) {
            $dchdate = DateThai( $row[ 'dchdate' ], 'd m ee' );
        }

        $hn = $row[ 'hn' ];

        insert_tv($hn,$pttvname,$chanel,$conn);

        $vn = $row[ 'vn' ];
        $an = $row[ 'an' ];
        $hos_guid = $row[ 'an' ];
        $incharge_doctorname = $row[ 'incharge_doctorname' ];
        $doctorname = $row[ 'doctorname' ];
        $bw = $row[ 'bw' ];
        $prediag = $row[ 'prediag' ];
        $wardname = $row[ 'wardname' ];

        $drugallergysql = "SELECT  o.*   FROM opd_allergy o
        WHERE o.hn = '$hn'";

        $drugallergyresult = $conn->query( $drugallergysql );
        $ptdrugallergy = '';

        if ( $drugallergyresult->num_rows > 0 ) {
            while ( $drugallergyrow = $drugallergyresult->fetch_assoc() ) {
                $ptdrugallergy .= '<span style="background-color:#FF0000;color:#FFFFFF"><b>' . $drugallergyrow[ 'agent' ] . '</b>&nbsp;:&nbsp;' . $drugallergyrow[ 'symptom' ] . '</span>';
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

        // $vn = $row[ 'vn' ];

        $vstdiagsql = "SELECT v.icd10, if(i.name is null,trim(i.tname),trim(i.name)) as icdname   FROM iptdiag v 
        LEFT OUTER JOIN icd101 i ON v.icd10 = i.code 
        WHERE v.hn = '$hn'";
        $vstdiagresult = $conn->query( $vstdiagsql );
        $doctordiag = '';
        if ( $vstdiagresult->num_rows > 0 ) {
            while ( $vstdiagrow = $vstdiagresult->fetch_assoc() ) {
                $doctordiag .= '<br><span>' . $vstdiagrow[ 'icd10' ] . '&nbsp;&nbsp;' . $vstdiagrow[ 'icdname' ] . '</span>';
            }
        }

        // $symptom = $row[ 'symptom' ];
        $drugallergys = $row[ 'drugallergy' ];
        if ( $drugallergys != '' ) {
            $drugallergy = '<span style="background-color:#FF0000;color:#FFFFFF"><b>'.$drugallergys.'</b>&nbsp;</span><br>';
        } else {
            $drugallergy = '';
        }

        // $diag = $row[ 'diag' ];
        // $doctorname = $row[ 'doctorname' ];
        $ptname = $row[ 'ptname' ];
        $fname = $row[ 'fname' ];
        $age = $row[ 'age' ];
        $informaddr = $row[ 'informaddr' ];
        $informtel = $row[ 'informtel' ];
        $ptimage = $row[ 'ptimage' ];
        $pttype_name = $row[ 'pttype_name' ];

        // $last_visit = DateThai( $row[ 'last_visit' ], 'd m ee' );
        // $vsttime = substr( $row[ 'vsttime' ], 0, 5 );

        // if ( $vsttime != '' ) {
        //     $vsttime = $vsttime .' น.';
        // } else {
        //     $vsttime = '';
        // }
        // ;
        $dchd = $row[ 'dchdate' ];
        $rxsql = "SELECT
        CONCAT(IF(s.name IS NULL,'',TRIM(s.name)),' ',IF(s.strength IS NULL,'',TRIM(s.strength))) AS drugname,
        if(o.sp_use = '',CONCAT(u.name1,' ',u.name2,' ',u.name3),'')  AS usagename,
        d.therapeutic,d.units,o.unitprice,FORMAT(o.sum_price, 2) AS sum_price,o.rxtime,
        r.rx_time,
        u.shortlist,o.hos_guid ,o.an,o.vn,
       
        o.qty
        FROM opitemrece o
   
        LEFT OUTER JOIN drugitems d ON o.icode=d.icode
        LEFT OUTER JOIN s_drugitems s ON o.icode=s.icode
        LEFT OUTER JOIN patient p ON o.hn=p.hn
        LEFT OUTER JOIN drugusage u ON o.drugusage = u.drugusage and u.status='Y' 
        LEFT OUTER JOIN rx_operator r ON o.vn=r.vn AND r.login IN (SELECT x.loginname FROM opduser x WHERE x.groupname ='ห้องยา' )
        WHERE o.hn='$hn'
        AND LEFT(o.icode,1) ='1'
        AND o.an <>''
        AND o.vstdate =curdate()
        -- AND o.order_no in (select x.order_no from ipt_order_no x where x.order_type ='HMe' )
        ORDER BY o.icode,o.rxtime
        ";
        $rxdata = '';
        $rxtime = '';
        $rx_time = '';

        $rxresult = $conn->query( $rxsql );
        if ( $rxresult->num_rows > 0 ) {

            $count = 0;
            $rxdata = '<table style="width:100%;" class="tablex" id="rxdata">';
            $rxdata .= '<tr>';

            $rxdata .=  '<th class="centered">No</th>';
            $rxdata .=  '<th class="centered">รายการ</th>';
            $rxdata .=  '<th class="centered">จำนวน</th>';
            $rxdata .=  '<th class="centered">วิธีบริหารยา</th>';
            $rxdata .=  '<th class="centered"></th>';

            // $rxdata .=  '<th class="centered"></th>';
            // $rxdata .=  '<th class="centered"></th>';
            $rxdata .=  '</tr>';
            while ( $rxrow = $rxresult->fetch_assoc() ) {

                $hos_guid = $rxrow[ 'hos_guid' ];
                $drugname = trim( $rxrow[ 'drugname' ] );
                $usagename = trim( $rxrow[ 'usagename' ] );
                $shortlist = trim( $rxrow[ 'shortlist' ] );

                $therapeutic = trim( $rxrow[ 'therapeutic' ] );
                $units = trim( $rxrow[ 'units' ] );
                $sum_price = trim( $rxrow[ 'sum_price' ] );
                $rxtime = $rxrow[ 'rxtime' ];
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

                // // เช็คหน่วยเวลาสำหรับพิมพ์ค่าออกทางหน้าจอ
                // // echo $timewait_display;

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
                    $therapeutic = '<span> ('.trim( $rxrow[ 'therapeutic' ] ).')</span><br>';
                }
                $qty = $rxrow[ 'qty' ];

                $rxdata .=  '<tr style="background-color:#F9E79F;width:100%;">';

                $rxdata .=  '<td  class="centered">'.++$count.'</td>';
                $rxdata .=  '<td><b> '.$drugname.'</b></td>';
                $rxdata .=  '<td  class="centered"><b>'.$qty.'</b>&nbsp;&nbsp;'.$units.'</td>';
                $rxdata .=  '<td style="margin-left: 10px;">'.$shortlist.'</td>';
                $rxdata .=  '<td  class="centered">'.$rxtime.'</td>';
                // $rxdata .=  '<td style="width:1%;" class="centered"><button onclick="fillformmain(this)" class="btn btn-link  btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>';
                // $rxdata .=  '<td style="width:1%;" class="centered"><button onclick="openformstockcard(this)" class="btn btn-link  btn-sm"><i class="fa fa-print" aria-hidden="true"></i></button></td>';

                $rxdata .=  '</tr>';
                // $therapeutic . '<br>';

            }

            $rxdata .=  '</table>';
        } else {
            $rxdata = 'ไม่พบรายการยา';
        }
        $img = '';
        if ( $ptimage != null ) {
            $img = '<img id="hover-image" onclick="displayptname()" style="width: 100px; height: 120px;cursor: pointer;margin-top: 10px; " src="data:image/jpeg;base64,'.base64_encode( $ptimage ).'"   alt=""/>';
        } else {
            $img = '<img id="hover-image" onclick="displayptname()" style="width: 100px; height: 120px;cursor: pointer;margin-top: 10px;"  src="person1.png"   alt=""  />';

        }
        echo '<tr style="background-color:#EBF5FB;width:100%;">';

        echo '<td style="width:100%;  background-color: #F8F9F9;">
            <table style="width:100%;" class="b-table">
                <tr class="acontainer">
                    <td style="width: 180px;">
                    <div class="container">'.$img.

        '<div class="middle">
                    <button class="text" onclick="displayptname()" >เรียกชื่อรับยา</button>                      
                   <span class="hide-column" id="fname">'.$fname.'</span>  
                   <span class="hide-column" id="enptname">'.$ptname.'</span>    
                    </div>
                  
                </div>
                
                
                 </td>

                    <td  style="width:50%;">
                        <span class="bbw" id ="ptname_'.$hos_guid.'" >'.$ptname.'&nbsp;&nbsp;'.$age.'</span><span></span><br>                      
                        <span style="background-color: #EAECEE;">HN :</span><span class="bw" id ="hn_'.$hos_guid.'">'.$hn.'</span>&nbsp;&nbsp;
                        <span style="background-color: #EAECEE;">AN :</span><span class="bw" id ="an_'.$hos_guid.'">'.$an.'</span>&nbsp;&nbsp;
                        <span style="background-color: #EAECEE;">เตียง :</span><span class="bw" id ="q_'.$hos_guid.'"></span><br>
                        <span style="background-color: #EAECEE;">สิทธิ :</span>&nbsp;&nbsp;<span class="bw" id ="pttype_name_'.$hos_guid.'">&nbsp;'.$pttype_name.'</span><br>
                        <span style="background-color: #EAECEE;">ที่อยู่ :</span>&nbsp;&nbsp;<span class="bw" id ="informaddr_'.$hos_guid.'">&nbsp;'.$informaddr.'</span><br>
                        <span style="background-color: #EAECEE;">เบอร์โทร. :</span>&nbsp;&nbsp;<span class="bw" id ="informtel_'.$hos_guid.'">&nbsp;'.$informtel.'</span><br>
                        แพ้ยา :&nbsp;&nbsp;'.$ptdrugallergy.
        '</td>

                    <td class="texttitle" style="width:30%;">
                    การนัดหมาย<br>
                    <span style="background-color: #EAECEE;">มาครั้งที่แล้ว </span><span style="background-color: #FFFFFF;">&nbsp;'.$lastvstdate.'</span><br>
                    <span style="background-color: #EAECEE;">นัดครั้งที่แล้ว </span><span style="background-color: #FFFFFF;">&nbsp;'.$lastnextdate.'</span><br>
                    <span style="background-color: #EAECEE;">นัดครั้งถัดไป </span><span style="background-color: #FFFFFF;">&nbsp;'.$ptoappnextdate.'</span><br>
                    </td>
                    <td class="texttitle" style="width:20%;">
                    
                    
                    <span style="background-color: #EAECEE;"></span><span style="background-color: #FFFFFF;"></span><br>
                    
                    <span style="background-color: #EAECEE;"> </span><span id=""calldisp style="background-color: #FFFFFF;"></span><br>
                    <span style="background-color: #EAECEE;">พิมพ์ฉลากยา. </span><span style="background-color: #FFFFFF;">'.$rx_time.'</span><br>
                    <span style="background-color: #EAECEE;">เรียกรับยา. </span><span id=""calldisp style="background-color: #FFFFFF;"></span><br>
                    <span style="background-color: #EAECEE;"></span><span style="background-color: #FFFFFF;"></span><br>
                    <button onclick="savedispen(\''.$vn.'\')" id="btnSaveDisp" class="btn btn-danger  btn-sm"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                  บันทึกจ่ายยา</button>
                    </td>

                    </tr>
                   
            </table>';
        echo '</td>';
        //  <br><button onclick = "callptname(\'' . $fname . '\')" style = 'width:100%;'>เรียกชื่อรับยา</button>

        echo '</tr>';

        echo '<tr style="background-color:#D6DBDF;">';
        //style = 'background-color: #FEF9E7;'
        echo '<td>
        <span style="background-color: #FFBF00;">Ward.&nbsp;</span><span style="background-color: #FFFFFF;">&nbsp;&nbsp;'.$wardname.'</span><br>
        <span style="background-color: #FFBF00;">วันที่รับเข้า.&nbsp;</span><span style="background-color: #FFFFFF;">&nbsp;&nbsp;'.$regdate.'</span>&nbsp;&nbsp;&nbsp;
        <span style="background-color: #FFBF00;">วันที่จำหน่าย.&nbsp;</span><span style="background-color: #FFFFFF;">&nbsp;&nbsp;'.$dchdate.'</span><br>
        <span style="background-color:#FFBF00;"  >Prediag.&nbsp;</span><span>'.$prediag.'</span><br>
        <span style="background-color: #FFBF00;">แพทย์&nbsp; </span><span style="background-color: #FFFFFF;">&nbsp;&nbsp;'.$doctorname.'</span><br>

        <p  style="background-color:#FFFFFF;" >
             <span style="background-color:#DFFF00; width:100%;">รายการยาที่ได้รับ</span>
             
             <br>'.$rxdata.

        '</p>
        <span style="background-color:#F8F9F9;"  >Incharge :&nbsp;&nbsp;'.$incharge_doctorname.'</span><br>
        <span style="background-color:#F8F9F9;"  >Diagnosis&nbsp;</span>'.

        $doctordiag.

        '</td>';

        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    // echo '<br>';
} else {
    echo '';
}

$conn->close();

// ( select CONCAT( sp1.name1, ' ', sp1.name2, ' ', sp1.name3 ) as sp_name from sp_use sp1 where sp1.sp_use = o.sp_use )

function get_an_digit( $conn ) {
    // include 'connection.php';
    $sql = "select sys_value from sys_var where sys_name='an_digit'";
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
