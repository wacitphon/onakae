<?php
date_default_timezone_set( 'Asia/Bangkok' );
$conn = mysqli_connect( '10.10.10.5', 'akatUser', 'akatUser@3863', 'hos' ) or die( 'Error: ' . mysqli_error( $conn ) );
mysqli_query( $conn, "SET NAMES 'utf8' " );

function getuuid()
 {
    $uuid = sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff )
    );

    return $uuid;
}

function DateThai( $strDate, $returnform )
 {
    $strYear = date( 'Y', strtotime( $strDate ) ) - 1957;
    $strMonth = date( 'n', strtotime( $strDate ) );
    $strDay = date( 'j', strtotime( $strDate ) );
    $strHour = date( 'H', strtotime( $strDate ) );
    $strMinute = date( 'i', strtotime( $strDate ) );
    $strSeconds = date( 's', strtotime( $strDate ) );
    $strMonthCut = array( '', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.' );
    $strMonthThai = $strMonthCut[ $strMonth ];
    $strMonthCutFull = array(
        '',
        'มกราคม',
        'กุมภาพันธ์',
        'มีนาคม',
        'เมษายน',
        'พฤษภาคม',
        'มิถุนายน',
        'กรกฎาคม',
        'สิงหาคม',
        'กันยายน',
        'ตุลาคม',
        'พฤศจิกายน',
        'ธันวาคม'
    );
    $strMonthThaiFull = $strMonthCutFull[ $strMonth ];
    if ( $returnform === 'd m eeee' || $returnform === 'dmeeee' ) {
        $strYear = date( 'Y', strtotime( $strDate ) ) + 543;
        return "$strDay $strMonthThai $strYear";
        //, $strHour:$strMinute";
	} else {
		if ($returnform === 'd m ee' || $returnform === 'dmee') {
			$strYear = date("Y", strtotime($strDate)) - 1957;
			return "$strDay $strMonthThai $strYear"; //, $strHour:$strMinute";

    } else {
        if ( $returnform === 'd/m/yyyy' ) {
            $strYear = date( 'Y', strtotime( $strDate ) );
            return "$strDay/$strMonth/$strYear";
            //, $strHour:$strMinute";			
			} else {
				if ($returnform === 'd M EEEE' || $returnform === 'dMEEEE') {
					$strYear = date("Y", strtotime($strDate)) + 543;
					return "$strDay $strMonthThaiFull $strYear"; //, $strHour:$strMinute";

        } else {
            if ( $returnform === 'd-m-yyyy' ) {
                $strYear = date( 'Y', strtotime( $strDate ) );
                return "$strDay-$strMonth-$strYear";
                //, $strHour:$strMinute";			
					} else {
						if ($returnform === 'd-m-yy') {
							$strYear = date("Y", strtotime($strDate))-2000;
							return "$strDay-$strMonth-$strYear"; //, $strHour:$strMinute";

            } else {
                return "$strDay $strMonthThai $strYear";
                //, $strHour:$strMinute";
	
						}

					}

				}

			}
		}
	}
	// return "$strDay $strMonthThai $strYear";//, $strHour:$strMinute";
            }

            // $strDate = '2008-08-14 13:42:44';
            // echo 'ThaiCreate.Com Time now : '.DateThai( $strDate );

            ?>
