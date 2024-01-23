<?php
date_default_timezone_set('Asia/Jakarta');
// $host       = "10.0.0.4";
// $username   = "timdit";
// $password   = "4dm1n";
// $db_name    = "TM";
// $time       = date('Y-m-d H:i:s');
// $connInfo   = array("Database" => $db_name, "UID" => $username, "PWD" => $password);
// $conn       = sqlsrv_connect($host, $connInfo);
$con        = mysqli_connect("10.0.0.10", "dit", "4dm1n", "db_laborat");

$time = date('Y-m-d H:i:s');

function SiapBagi($jenis_matching)
{
    $con = mysqli_connect("10.0.0.10", "dit", "4dm1n", "db_laborat");
    $sql = mysqli_fetch_array(
        /*mysqli_query($con,"SELECT count(a.id) as count 
                                        from tbl_matching a
                                        left join tbl_status_matching b on a.no_resep = b.idm
                                        where a.jenis_matching = '$jenis_matching' and a.status_bagi = 'siap bagi' and ifnull(b.`status`, 'siap bagi') = 'siap bagi'")*/
        mysqli_query($con, "SELECT count(a.id) as `count` FROM tbl_matching a 
                            LEFT JOIN tbl_status_matching b on a.`no_resep` = b.`idm`
                            WHERE b.approve_at is null  and b.status is null and a.status_bagi = 'siap bagi' and a.jenis_matching = '$jenis_matching'")
    );

    return $sql['count'];
}

$sql_siap_bagi = mysqli_query($con, "INSERT into sisa_schedule set
                                                `data` = 'Siap Bagi',
                                                lab_dip = " . SiapBagi('L/D') + SiapBagi('LD NOW') . ",
                                                matching_ulang = " . SiapBagi('Matching Ulang') + SiapBagi('Matching Ulang NOW') . ",
                                                perbaikan = " . SiapBagi('Perbaikan') + SiapBagi('Perbaikan NOW') . ",
                                                development = " . SiapBagi('Matching Development') . ",
                                                `time` = '$time' ");
if ($sql_siap_bagi) {
    echo 'berhasil 1';
} else {
    echo 'gagal 1';
}

function SedangJalan($jenis_matching)
{
    $con = mysqli_connect("10.0.0.10", "dit", "4dm1n", "db_laborat");
    $sql = mysqli_fetch_array(
        /* mysqli_query($con,"SELECT count(b.id) as `count`
        FROM tbl_status_matching a
        JOIN tbl_matching b ON a.idm = b.no_resep
        where a.status in ('buka', 'mulai', 'hold', 'batal', 'revisi','tunggu') 
        and b.jenis_matching = '$jenis_matching'")*/
        mysqli_query($con, "SELECT count(b.id) as `count`
                            FROM tbl_status_matching a
                            JOIN tbl_matching b ON a.idm = b.no_resep
                            where a.status ='buka'
                            and b.jenis_matching = '$jenis_matching'")
    );
    return $sql['count'];
}

$sql_sedang_jalan = mysqli_query($con, "INSERT into sisa_schedule set
                                        `data` = 'Sedang Jalan',
                                        lab_dip = " . SedangJalan('L/D') + SedangJalan('LD NOW') . ",
                                        matching_ulang = " . SedangJalan('Matching Ulang') + SedangJalan('Matching Ulang NOW') . ",
                                        perbaikan = " . SedangJalan('Perbaikan') + SedangJalan('Perbaikan NOW') . ",
                                        development = " . SedangJalan('Matching Development') . ",
                                        `time` = '$time' ");
if ($sql_sedang_jalan) {
    echo 'berhasil 2';
} else {
    echo 'gagal 2';
}


function WaitingApprove($jenis_matching)
{
    $con = mysqli_connect("10.0.0.10", "dit", "4dm1n", "db_laborat");
    $sql = mysqli_fetch_array(
        mysqli_query($con, "SELECT count(b.id) as `count`
        FROM tbl_status_matching a
        INNER JOIN tbl_matching b ON a.idm = b.no_resep
        where a.status in ('selesai', 'batal') and a.approve = 'NONE' and b.jenis_matching = '$jenis_matching'")
    );
    return $sql['count'];
}

$sql_WaitingApprove = mysqli_query($con, "INSERT into sisa_schedule set
                            `data` = 'Waiting Approve',
                            lab_dip = " . WaitingApprove('L/D') + WaitingApprove('LD NOW') . ",
                            matching_ulang = " . WaitingApprove('Matching Ulang') + WaitingApprove('Matching Ulang NOW') . ",
                            perbaikan = " . WaitingApprove('Perbaikan') + WaitingApprove('Perbaikan NOW') . ",
                            development = " . WaitingApprove('Matching Development') . ",
                            `time` = '$time' ");
if ($sql_WaitingApprove) {
    echo 'berhasil 3';
} else {
    echo 'gagal 3';
}


function Tunggu($jenis_matching)
{
    $con = mysqli_connect("10.0.0.10", "dit", "4dm1n", "db_laborat");
    $sql = mysqli_fetch_array(
        //mysqli_query($con,"SELECT count(id) as `count` from tbl_matching where status_bagi = 'tunggu' and jenis_matching = '$jenis_matching'")
        mysqli_query($con, "select  count(a.id) as `count` FROM tbl_matching a 
			left join tbl_status_matching b on a.`no_resep` = b.`idm`
			where b.approve_at is null  and b.status is null and a.status_bagi = 'tunggu' and a.jenis_matching = '$jenis_matching'")

    );

    return $sql['count'];
}

$sql_Tunggu = mysqli_query($con, "INSERT into sisa_schedule set
                            `data` = 'Tunggu (list schedule)',
                            lab_dip = " . Tunggu('L/D') + Tunggu('LD NOW') . ",
                            matching_ulang = " . Tunggu('Matching Ulang') + Tunggu('Matching Ulang NOW') . ",
                            perbaikan = " . Tunggu('Perbaikan') + Tunggu('Perbaikan NOW') . ",
                            development = " . Tunggu('Matching Development') . ",
                            `time` = '$time' ");
if ($sql_Tunggu) {
    echo 'berhasil 4';
} else {
    echo 'gagal 4';
}



function belum_bagi($jenis_matching)
{
    $con = mysqli_connect("10.0.0.10", "dit", "4dm1n", "db_laborat");
    $sql = mysqli_fetch_array(
        /*mysqli_query($con,"SELECT count(a.id) as `count` from tbl_matching a
        left join tbl_status_matching b on a.no_resep = b.idm
        where a.jenis_matching = '$jenis_matching' and a.status_bagi IS NULL and ifnull(b.`status`, 'siap bagi') = 'siap bagi'")*/
        mysqli_query($con, "select  count(a.id) as `count` FROM tbl_matching a 
			left join tbl_status_matching b on a.`no_resep` = b.`idm`
			where b.approve_at is null  and b.status is null and 
			a.status_bagi IS NULL and a.jenis_matching = '$jenis_matching'")
    );

    return $sql['count'];
}

$sql_belum_bagi = mysqli_query($con, "INSERT into sisa_schedule set
                            `data` = 'Belum Bagi',
                            lab_dip = " . belum_bagi('L/D') + belum_bagi('LD NOW') . ",
                            matching_ulang = " . belum_bagi('Matching Ulang') + belum_bagi('Matching Ulang NOW') . ",
                            perbaikan = " . belum_bagi('Perbaikan') + belum_bagi('Perbaikan NOW') . ",
                            development = " . belum_bagi('Matching Development') . ",
                            `time` = '$time' ");
if ($sql_belum_bagi) {
    echo 'berhasil 5';
} else {
    echo 'gagal 5';
}
