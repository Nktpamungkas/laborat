<?php
ini_set("error_reporting", 1);
session_start();
include 'koneksi.php';

// pie chart
$sql_row_selesai = mysqli_query($con,"SELECT * FROM tbl_status_matching where `status` = 'selesai' and approve = 'TRUE'");
$sql_row_tutup = mysqli_query($con,"SELECT * FROM tbl_status_matching where `status` = 'tutup'");
$sql_row_arsip = mysqli_query($con,"SELECT * FROM tbl_status_matching where `status` = 'arsip'");

$row_selesai = mysqli_num_rows($sql_row_selesai);
$row_tutup = mysqli_num_rows($sql_row_tutup);
$row_arsip = mysqli_num_rows($sql_row_arsip);
// end piechart


// line chart
// xAxis
$now = date('Y-m-d');
$hari = array();
$x = 0;
while ($x <= 12) {
    $hari[] = date('d M', strtotime("-$x days"));
    $x++;
}
$data_hari = json_encode($hari);

// Xdata 

function SelesaiGetByDay($day)
{
    include 'koneksi.php';
    $sql_getbyday = mysqli_query($con,"SELECT idm from tbl_status_matching where `status` = 'selesai' 
    and approve = 'TRUE' 
    and DATE_FORMAT(approve_at,'%Y-%m-%d') = '$day'");
    $getbyday = mysqli_num_rows($sql_getbyday);

    return $getbyday;
}
function ClosedGetByDay($day)
{
    include 'koneksi.php';
    $sql_getbyday = mysqli_query($con,"SELECT idm from tbl_status_matching where `status` = 'tutup'
    and DATE_FORMAT(tutup_at,'%Y-%m-%d') = '$day'");
    $getbyday = mysqli_num_rows($sql_getbyday);

    return $getbyday;
}
function ArsipGetByDay($day)
{
    include 'koneksi.php';
    $sql_getbyday = mysqli_query($con,"SELECT idm 
                                from tbl_status_matching a 
                                join log_status_matching b on a.idm = b.ids 
                                where a.status = 'arsip' and b.status = 'arsip'
                                and DATE_FORMAT(b.do_at,'%Y-%m-%d') = '$day' group by a.idm, b.ids");
    $getbyday = mysqli_num_rows($sql_getbyday);

    return $getbyday;
}

$closed = array();
$selesai = array();
$arsip = array();
$n = 0;
while ($n <= 12) {
    $selesai[] = intval(SelesaiGetByDay(date('Y-m-d', strtotime("-$n days"))));
    $closed[] = intval(ClosedGetByDay(date('Y-m-d', strtotime("-$n days"))));
    $arsip[] = intval(ArsipGetByDay(date('Y-m-d', strtotime("-$n days"))));
    $n++;
}
$_selesai = json_encode($selesai);
$_closed = json_encode($closed);
$_arsip = json_encode($arsip);
