<?php

header( 'Content-Type: text/html; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( ~0 );
date_default_timezone_set( 'Asia/Bangkok' );
include 'connection.php';

$hn = trim( $_GET[ 'hn' ] );
if (isset($_POST['chanel'])) {
    $chanel = trim($_POST['chanel']);


    if ( !empty( $hn ) && !is_null( $hn ) ) {
        $sql = "SELECT
        concat(trim(p.pname),trim(p.fname),'  ',trim(p.lname)) as ptname,
        concat(trim(p.fname),'  ',trim(p.lname)) as fname, 
        CONCAT(trim(p.pname),trim(p.fname),'  ',SUBSTRING(trim(p.lname), 1, 3),'xxx') AS pttvname,
        p.informaddr,p.informtel,
        m.image as ptimage,
        ThaiAge(p.birthday,CURDATE()) as age,
        y.name as pttype_name,
        p.hn,
        p.drugallergy
            
        FROM patient p
        LEFT OUTER JOIN patient_image m on p.hn = m.hn
        LEFT OUTER JOIN pttype y on p.pttype=y.pttype  
        WHERE p.hn = '$hn'
        ";
        $result = $conn->query( $sql );
    
        if ( $result->num_rows > 0 ) {
    
            echo '<table style="width:100%;">';
            echo '<tbody>';
    
            while ( $row = $result->fetch_assoc() ) {
    
                $hn = $row[ 'hn' ];
                insert_tv($hn,$pttvname,$chanel,$conn);
                $hos_guid = $row[ 'hn' ];
                $ptname = $row[ 'ptname' ];
                $fname = $row[ 'fname' ];
                $age = $row[ 'age' ];
                $informaddr = $row[ 'informaddr' ];
                $informtel = $row[ 'informtel' ];
                $ptimage = $row[ 'ptimage' ];
                $pttype_name = $row[ 'pttype_name' ];
    
                $drugallergysql = "SELECT  o.*   FROM opd_allergy o
                 WHERE o.hn = '$hn'";
    
                $drugallergyresult = $conn->query( $drugallergysql );
                $ptdrugallergy = '';
    
                if ( $drugallergyresult->num_rows > 0 ) {
                    while ( $drugallergyrow = $drugallergyresult->fetch_assoc() ) {
                        $ptdrugallergy .= '<span style="background-color:#FF0000;color:#FFFFFF"><b>' . $drugallergyrow[ 'agent' ] . '</b>&nbsp;:&nbsp;' . $drugallergyrow[ 'symptom' ] . '</span>';
                    }
                }
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
    
                $drugallergys = $row[ 'drugallergy' ];
                if ( $drugallergys != '' ) {
                    $drugallergy = '<span style="background-color:#FF0000;color:#FFFFFF"><b>'.$drugallergys.'</b>&nbsp;</span><br>';
                } else {
                    $drugallergy = '';
                }
    
                $img = '';
                if ( $ptimage != null ) {
                    $img = '<img id="hover-image" onclick="callptname(\''.$fname.'\')" style="width: 100px; height: 120px;cursor: pointer;" src="data:image/jpeg;base64,'.base64_encode( $ptimage ).'"   alt=""/>';
                } else {
                    $img = '<img id="hover-image" onclick="callptname(\''.$fname.'\')" style="width: 100px; height: 120px;cursor: pointer;"  src="person1.png"   alt=""  />';
    
                }
    
                echo '<tr style="background-color:#EBF5FB;width:100%;">';
                echo '<td style="width:100%;  background-color: #F8F9F9;">
                <table style="width:100%;">
                    <tr class="acontainer">
                        <td style="width: 180px;">
                            <div class="container">'.$img.
                               '<div class="middle">
                                    <button class="text" onclick="callptname(\''.$fname.'\')" >เรียกชื่อรับยา</button>                      
                                    <span class="hide-column" id="fname">'.$fname.'</span>      
                                </div>                 
                            </div>             
                        </td>
    
                        <td  style="width:50%; background-color: #F8F9F9;">
                            <span class="bbw" id ="ptname_'.$hos_guid.'" >'.$ptname.'&nbsp;&nbsp;'.$age.'</span><span></span><br>                      
                            <span style="background-color: #EAECEE;">HN :</span><span class="bw" id ="hn_'.$hos_guid.'">'.$hn.'</span>&nbsp;&nbsp;
                            <span style="background-color: #EAECEE;">AN :</span><span class="bw" id ="an_'.$hos_guid.'"></span>&nbsp;&nbsp;
                            <span style="background-color: #EAECEE;">Q :</span><span class="bw" id ="q_'.$hos_guid.'"></span><br>
                            <span style="background-color: #EAECEE;">สิทธิ :</span>&nbsp;&nbsp;<span class="bw" id ="pttype_name_'.$hos_guid.'">'.$pttype_name.'</span><br>
                            <span style="background-color: #EAECEE;">ที่อยู่ :</span>&nbsp;&nbsp;<span class="bw" id ="informaddr_'.$hos_guid.'">'.$informaddr.'</span><br>
                            <span style="background-color: #EAECEE;">เบอร์โทร. :</span>&nbsp;&nbsp;<span class="bw" id ="informtel_'.$hos_guid.'">'.$informtel.'</span><br>
                            แพ้ยา :&nbsp;&nbsp;'.$ptdrugallergy.
                        '</td>
    
                        <td class="texttitle" style="width:30%;">
                            การนัดหมาย<br>
                            <span style="background-color: #EAECEE;">มาครั้งที่แล้ว </span><span style="background-color: #FFFFFF;">'.$lastvstdate.'</span><br>
                            <span style="background-color: #EAECEE;">นัดครั้งที่แล้ว </span><span style="background-color: #FFFFFF;">'.$lastnextdate.'</span><br>
                            <span style="background-color: #EAECEE;">นัดครั้งถัดไป </span><span style="background-color: #FFFFFF;">'.$ptoappnextdate.'</span><br>
                        </td>
                            <td class="texttitle" style="width:20%;">                               
                            <span style="background-color: #EAECEE;"></span><span style="background-color: #FFFFFF;"></span><br>                    
                            <span style="background-color: #EAECEE;"> </span><span id=""calldisp style="background-color: #FFFFFF;"></span><br>
                            <span style="background-color: #EAECEE;">พิมพ์ฉลากยา. </span><span style="background-color: #FFFFFF;"></span><br>
                            <span style="background-color: #EAECEE;">เรียกรับยา. </span><span id=""calldisp style="background-color: #FFFFFF;"></span><br>
                            <span style="background-color: #EAECEE;"></span><span style="background-color: #FFFFFF;"></span><br>
                           
                        </td>
                 </tr>                  
                </table>';
                echo '</td>';
    
                echo '</tbody>';
                echo '</table>';
                // echo '<br>';
            }
        } else {
            echo '<script>alert("ไม่พบข้อมูลที่ค้นหา")</script>';
        }
    }    
} else {
    // ไม่มีค่า 'chanel' ถูกส่งมา
    echo '';
}



$conn->close();

function insert_tv($hn,$pttvname,$chanel,$conn){
    $sql = "INSERT INTO rx_tv_display 
    (hn,ptname,calltime,chanel)
    VALUES('$hn','$pttvname',NOW(),'$chanel')
    ON DUPLICATE KEY UPDATE ptname='$pttvname',chanel='$chanel',calltime=NOW()
    ";
    $result = $conn->query( $sql );
}

?>

