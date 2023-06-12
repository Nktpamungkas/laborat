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
    $conc = 0;
    $dt = "";
    $doby = "";
}
if (!empty($_POST['conc1'])) {
    $conc1 = $_POST['conc1'];
    $dt1 = $time;
    $doby1 = $_SESSION['userLAB'];
} else {
    $conc1 = 0;
    $dt1 = "";
    $doby1 = "";
}
if (!empty($_POST['conc2'])) {
    $conc2 = $_POST['conc2'];
    $dt2 = $time;
    $doby2 = $_SESSION['userLAB'];
} else {
    $conc2 = 0;
    $dt2 = "";
    $doby2 = "";
}
if (!empty($_POST['conc3'])) {
    $conc3 = $_POST['conc3'];
    $dt3 = $time;
    $doby3 = $_SESSION['userLAB'];
} else {
    $conc3 = 0;
    $dt3 = "";
    $doby3 = "";
}
if (!empty($_POST['conc4'])) {
    $conc4 = $_POST['conc4'];
    $dt4 = $time;
    $doby4 = $_SESSION['userLAB'];
} else {
    $conc4 = 0;
    $dt = "";
    $doby = "";
}
if (!empty($_POST['conc5'])) {
    $conc5 = $_POST['conc5'];
    $dt5 = $time;
    $doby5 = $_SESSION['userLAB'];
} else {
    $conc5 = 0;
    $dt5 = "";
    $doby5 = "";
}
if (!empty($_POST['conc6'])) {
    $conc6 = $_POST['conc6'];
    $dt6 = $time;
    $doby6 = $_SESSION['userLAB'];
} else {
    $conc6 = 0;
    $dt6 = "";
    $doby6 = "";
}
if (!empty($_POST['conc7'])) {
    $conc7 = $_POST['conc7'];
    $dt7 = $time;
    $doby7 = $_SESSION['userLAB'];
} else {
    $conc7 = 0;
    $dt7 = "";
    $doby7 = "";
}
if (!empty($_POST['conc8'])) {
    $conc8 = $_POST['conc8'];
    $dt8 = $time;
    $doby8 = $_SESSION['userLAB'];
} else {
    $conc8 = 0;
    $dt8 = "";
    $doby8 = "";
}
if (!empty($_POST['conc9'])) {
    $conc9 = $_POST['conc9'];
    $dt9 = $time;
    $doby9 = $_SESSION['userLAB'];
} else {
    $conc9 = 0;
    $dt9 = "";
    $doby9 = "";
}

$sql = mysqli_query($con,"SELECT * from `tbl_matching_detail` where `id_matching` = '$_POST[id_matching]' and `id_status`= '$_POST[id_status]' and `flag` = '$_POST[flag]' LIMIT 1");
$count = mysqli_num_rows($sql);
$data = mysqli_fetch_array($sql);
if ($count > 0) {
    if ($data['kode'] != $_POST['code']) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `kode`= '$_POST[code]',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['nama'] != $_POST['desc_code']) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `nama` = '$_POST[desc_code]',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['conc1'] != $conc) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `conc1` = '$conc',
        `time_1` = now(),
        `doby1` = '$_SESSION[userLAB]',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['conc2'] != $conc1) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `conc2` = '$conc1',
        `time_2` = '$dt1',
        `doby2` = '$doby1',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['conc3'] != $conc2) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `conc3` = '$conc2',
        `time_3` = '$dt2',
        `doby3` = '$doby2',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['conc4'] != $conc3) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `conc4` = '$conc3',
        `time_4` = '$dt3',
        `doby4` = '$doby3',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['conc5'] != $conc4) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `conc5` = '$conc4',
        `time_5` = '$dt4',
        `doby5` = '$doby4',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['conc6'] != $conc5) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `conc6` = '$conc5',
        `time_6` = '$dt5',
        `doby6` = '$doby5',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['conc7'] != $conc6) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `conc7` = '$conc6',
        `time_7` = '$dt6',
        `doby7` = '$doby6',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['conc8'] != $conc7) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `conc8` = '$conc7',
        `time_8` = '$dt7',
        `doby8` = '$doby7',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['conc9'] != $conc8) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `conc9` = '$conc8',
        `time_9` = '$dt8',
        `doby9` = '$doby8',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['conc10'] != $conc9) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `conc10` = '$conc9',
        `time_10` = '$dt9',
        `doby10` = '$doby9',
        `last_edit_at` = '$time',
        `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    if ($data['remark'] != $_POST['keterangan']) {
        mysqli_query($con,"UPDATE `tbl_matching_detail` SET
        `remark` = '$_POST[keterangan]'
        -- `last_edit_at` = '$time',
        -- `last_edit_by` = '$_SESSION[userLAB]'
        where `id_matching` = '$_POST[id_matching]' 
        and `id_status` = '$_POST[id_status]' 
        and `flag` = '$_POST[flag]'");
    }
    $LIB_SUCCSS = "LIB_SUCCSS";
} else {
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
    $LIB_SUCCSS = "LIB_SUCCSS";
}

$response = array(
    'session' => $LIB_SUCCSS,
    'exp' => 'inserted',
);
echo json_encode($response);
