<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');
if (!empty($_POST['conc'])) {
    $conc = $_POST['conc'];
    $dt = $time;
    $doby = $_SESSION['userLAB'];
} else {
    $conc = "";
    $dt = "";
    $doby = "";
}
if (!empty($_POST['conc1'])) {
    $conc1 = $_POST['conc1'];
    $dt1 = $time;
    $doby1 = $_SESSION['userLAB'];
} else {
    $conc1 = "";
    $dt1 = "";
    $doby1 = "";
}
if (!empty($_POST['conc2'])) {
    $conc2 = $_POST['conc2'];
    $dt2 = $time;
    $doby2 = $_SESSION['userLAB'];
} else {
    $conc2 = "";
    $dt2 = "";
    $doby2 = "";
}
if (!empty($_POST['conc3'])) {
    $conc3 = $_POST['conc3'];
    $dt3 = $time;
    $doby3 = $_SESSION['userLAB'];
} else {
    $conc3 = "";
    $dt3 = "";
    $doby3 = "";
}
if (!empty($_POST['conc4'])) {
    $conc4 = $_POST['conc4'];
    $dt4 = $time;
    $doby4 = $_SESSION['userLAB'];
} else {
    $conc4 = "";
    $dt4 = "";
    $doby4 = "";
}
if (!empty($_POST['conc5'])) {
    $conc5 = $_POST['conc5'];
    $dt5 = $time;
    $doby5 = $_SESSION['userLAB'];
} else {
    $conc5 = "";
    $dt5 = "";
    $doby5 = "";
}
if (!empty($_POST['conc6'])) {
    $conc6 = $_POST['conc6'];
    $dt6 = $time;
    $doby6 = $_SESSION['userLAB'];
} else {
    $conc6 = "";
    $dt6 = "";
    $doby6 = "";
}
if (!empty($_POST['conc7'])) {
    $conc7 = $_POST['conc7'];
    $dt7 = $time;
    $doby7 = $_SESSION['userLAB'];
} else {
    $conc7 = "";
    $dt7 = "";
    $doby7 = "";
}
if (!empty($_POST['conc8'])) {
    $conc8 = $_POST['conc8'];
    $dt8 = $time;
    $doby8 = $_SESSION['userLAB'];
} else {
    $conc8 = "";
    $dt8 = "";
    $doby8 = "";
}
if (!empty($_POST['conc9'])) {
    $conc9 = $_POST['conc9'];
    $dt9 = $time;
    $doby9 = $_SESSION['userLAB'];
} else {
    $conc9 = "";
    $dt9 = "";
    $doby9 = "";
}
mysqli_query($con,"INSERT INTO `tbl_matching_detail` SET
`flag` = '$_POST[flag]',
`id_matching`= '$_POST[id_matching]',
`id_status` = '$_POST[id_status]',
`kode`= '$_POST[code]',
`nama` = '$_POST[desc_code]',
`conc1` = '$conc',
`conc2` = '$conc1',
`conc3` = '$conc2',
`conc4` = '$conc3',
`conc5` = '$conc4',
`conc6` = '$conc5',
`conc7` = '$conc6',
`conc8` = '$conc7',
`conc9` = '$conc8',
`conc10` = '$conc9',
`time_1` = '$dt',
`time_2` = '$dt1',
`time_3` = '$dt2',
`time_4` = '$dt3',
`time_5` = '$dt4',
`time_6` = '$dt5',
`time_7` = '$dt6',
`time_8` = '$dt7',
`time_9` = '$dt8',
`time_10` = '$dt9',
`doby1` = '$doby',
`doby2` = '$doby1',
`doby3` = '$doby2',
`doby4` = '$doby3',
`doby5` = '$doby4',
`doby6` = '$doby5',
`doby7` = '$doby6',
`doby8` = '$doby7',
`doby9` = '$doby8',
`doby10` = '$doby9',
`remark` = '$_POST[keterangan]',
`inserted_at` = '$time',
`inserted_by` = '$_SESSION[userLAB]'
");

$response = array(
    'session' => 'LIB_SUCCSS',
    'exp' => 'inserted'
);
echo json_encode($response);
