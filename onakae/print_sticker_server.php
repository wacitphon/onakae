<?php
header( 'Content-Type: text/html; charset=utf-8' );
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

// Include the connection.php file only if it hasn't been included before
if (!defined('INCLUDED_CONNECTION_PHP')) {
    define('INCLUDED_CONNECTION_PHP', 1);
    require_once('connection.php');
}

// Make sure data is sent via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo "เฉพาะการส่งข้อมูลแบบ POST เท่านั้นที่อนุญาต";
    exit;
}

// Validate and sanitize input
$vn = $conn->real_escape_string($_POST['vn']);
$print_server = isset($_POST['print_server']) ? $_POST['print_server'] : '';
$chk = isset($_POST['chk']) ? $_POST['chk'] : '';

// Delete the record if vn is set

$doctor_order_print_code = $_POST['doctor_order_print_code'];
$vn = $_POST['vn'];
$doctor_code = $_POST['doctor_code'];
$doctor_order_print_date_time = $_POST['doctor_order_print_date_time'];
$patient_name = $_POST['patient_name'];
$drug_name = $_POST['drug_name'];
$qty_name = $_POST['qty_name'];
$line1 = $_POST['line1'];
$line2 = $_POST['line2'];
$line3 = $_POST['line3'];
$line4 = $_POST['line4'];
$shortlist = $_POST['shortlist'];
$print_status = $_POST['print_status'];
$hn = $_POST['hn'];
$print_server = $_POST['print_server'];
$item_price = $_POST['item_price'];
$qty_int = $_POST['qty_int'];
$patient_age = $_POST['patient_age'];
$patient_pttype_name = $_POST['patient_pttype_name'];
$patient_address = $_POST['patient_address'];
$doctor_name = $_POST['doctor_name'];
$diagnosis_name = $_POST['diagnosis_name'];
$patient_type = $_POST['patient_type'];
$print_date = $_POST['print_date'];
$print_time = $_POST['print_time'];
$ward_name = $_POST['ward_name'];
$bed_no = $_POST['bed_no'];
$staff = $_POST['staff'];
$print_mode = $_POST['print_mode'];
$rx_queue = $_POST['rx_queue'];
$item_no = $_POST['item_no'];
$dep_name = $_POST['dep_name'];
$icode = $_POST['icode'];
$orderstatus = $_POST['orderstatus'];
$order_no = $_POST['order_no'];
$pttype = $_POST['pttype'];
$print_count = $_POST['print_count'];
$line5 = $_POST['line5'];
$hos_guid = $_POST['hos_guid'];
$med_plan_number = $_POST['med_plan_number'];
$plan_begindate = $_POST['plan_begindate'];
$plan_enddate = $_POST['plan_enddate'];
$doph_id = $_POST['doph_id'];
$discount = $_POST['discount'];
$qr_code = $_POST['qr_code'];
$summary_qr_code = $_POST['summary_qr_code'];
$location_code = $_POST['location_code'];

if (!empty($vn)) {
    $deletesql = "DELETE FROM doctor_order_print 
                WHERE vn='$vn'
                AND print_server='$print_server'
                AND print_mode = 2";
    if ($conn->query($deletesql)) {
        echo "ลบรายการเรียบร้อยแล้ว";
        
    } else {
        echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . $conn->error;
    }


    $sqlinsert ="INSERT INTO doctor_order_print (
    doctor_order_print_code,
    vn,
    doctor_code,
    doctor_order_print_date_time,
    patient_name,
    drug_name,
    qty_name,
    line1,
    line2,
    line3,
    line4,
    shortlist,
    print_status,
    hn,
    print_server,
    item_price,
    qty_int,
    patient_age,
    patient_pttype_name,
    patient_address,
    doctor_name,
    diagnosis_name,
    patient_type,
    print_date,
    print_time,
    ward_name,
    bed_no,
    staff,
    print_mode,
    rx_queue,
    item_no,
    dep_name,
    icode,
    orderstatus,
    order_no,
    pttype,
    print_count,
    line5,
    hos_guid,
    med_plan_number,
    plan_begindate,
    plan_enddate,
    doph_id,
    discount,
    qr_code,
    summary_qr_code,
    location_code
)
VALUES(
    getserialnumber('doctor_order_print_code'),
    '$vn',
    '$doctor_code',
    '$doctor_order_print_date_time',
    '$patient_name',
    '$drug_name',
    '$qty_name',
    '$line1',
    '$line2',
    '$line3',
    '$line4',
    '$shortlist',
    '$print_status',
    '$hn',
    '$print_server',
    '$item_price',
    '$qty_int',
    '$patient_age',
    '$patient_pttype_name',
    '$patient_address',
    '$doctor_name',
    '$diagnosis_name',
    '$patient_type',
    '$print_date',
    '$print_time',
    '$ward_name',
    '$bed_no',
    '$staff',
    '$print_mode',
    '$rx_queue',
    '$item_no',
    '$dep_name',
    '$icode',
    '$orderstatus',
    '$order_no',
    '$pttype',
    '$print_count',
    '$line5',
    '$hos_guid',
    '$med_plan_number',
    '$plan_begindate',
    '$plan_enddate',
    '$doph_id',
    '$discount',
    '$qr_code',
    '$summary_qr_code',
    '$location_code'    

    )";

}

// Close the database connection
$conn->close();
?>
