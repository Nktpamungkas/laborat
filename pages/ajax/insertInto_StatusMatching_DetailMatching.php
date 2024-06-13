<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$ip_num = $_SERVER['REMOTE_ADDR'];
$time = date('Y-m-d H:i:s');
$awal  = strtotime($_POST['tgl_buat_status']);
$akhir = strtotime(date('Y-m-d H:i:s'));
$diff  = $akhir - $awal;
$hari  = floor($diff / (60 * 60 * 24));
$jam   = floor(($diff - ($hari * (60 * 60 * 24))) / (60 * 60));
$menit = ($diff - ($hari * (60 * 60 * 24))) - (($jam) * (60 * 60));
$timer =  $hari . ' Hari, ' . $jam .  ' Jam, ' . floor($menit / 60) . ' Menit';
$benang_a = str_replace("'", "''", $_POST['benang_a']);
$Benang = str_replace("'", "''", $_POST['Benang']);


//fungsi regex untuk remove spasi mau satu spasi atau lebih tidak ngaruh
if (!empty($_POST['second_lr'])) {
    $second_lr_format = preg_replace('/\s*:\s*/', ':', $_POST['second_lr']);
} else {
    $second_lr_format = "0:0";
}

if (!empty($_POST['l_R'])) {
    $lr_format = preg_replace('/\s*:\s*/', ':', $_POST['l_R']);
} else {
    $lr_format = null;
}

$hapus = mysqli_query($con, "DELETE from tbl_matching_detail where id_matching = '$_POST[id_matching]' and id_status = '$_POST[id_status]'");

mysqli_query($con, "UPDATE `tbl_status_matching` SET
                    `percobaan_ke` = '$_POST[matching_ke]',
                    `benang_aktual` = '$benang_a',
                    `lebar_aktual` = '$_POST[lebar_a]',
                    `gramasi_aktual` = '$_POST[gramasi_a]',
                    `lr` = '$lr_format',
                    `ph` = '$_POST[kadar_air]',
                    `rc_sh` = '$_POST[RC_Suhu]',
                    `rc_tm` = '$_POST[RCWaktu]',
                    `soaping_sh` = '$_POST[soapingSuhu]',
                    `soaping_tm` = '$_POST[soapingWaktu]',
                    `status` = 'selesai',
                    `cie_wi` = '$_POST[cie_wi]',
                    `cie_tint` = '$_POST[cie_tint]',
                    `yellowness` = '$_POST[yellowness]',
                    `spektro_r` = '$_POST[Spektro_R]',
                    `done_matching` = '$_POST[Done_Matching]',
                    `ket` = '$_POST[keterangan]',
                    `selesai_by` = '$_SESSION[userLAB]',
                    `selesai_at` = '$time',
                    `tside_c` = '$_POST[tside_c]',
                    `tside_min` = '$_POST[tside_min]',
                    `cside_c` = '$_POST[cside_c]',
                    `cside_min`= '$_POST[cside_min]',
                    `timer` = '$timer',
                    `kadar_air`= '$_POST[kadar_air_true]',
                    `koreksi_resep`= '$_POST[koreksi_resep]',
                    `koreksi_resep2`= '$_POST[koreksi_resep2]',
                    `koreksi_resep3`= '$_POST[koreksi_resep3]',
                    `koreksi_resep4`= '$_POST[koreksi_resep4]',
                    `koreksi_resep5`= '$_POST[koreksi_resep5]',
                    `koreksi_resep6`= '$_POST[koreksi_resep6]',
                    `koreksi_resep7`= '$_POST[koreksi_resep7]',
                    `koreksi_resep8`= '$_POST[koreksi_resep8]',
                    `final_matcher`= '$_POST[final_matcher]',
					`create_resep` = '$_POST[create_resep]',
                    `acc_ulang_ok` = '$_POST[acc_ulang_ok]',
					`acc_resep1` = '$_POST[acc_resep1]',
                    `acc_resep2` = '$_POST[acc_resep2]',
                    `colorist1` = '$_POST[colorist1]',
                    `colorist2` = '$_POST[colorist2]',
                    `colorist3` = '$_POST[colorist3]',
                    `colorist4` = '$_POST[colorist4]',
                    `colorist5` = '$_POST[colorist5]',
                    `colorist6` = '$_POST[colorist6]',
                    `colorist7` = '$_POST[colorist7]',
                    `colorist8` = '$_POST[colorist8]',
                    `matcher` = '$_POST[Matcher]',
                    `grp`='$_POST[Group]',
                    `bleaching_sh`='$_POST[bleaching_sh]',
                    `bleaching_tm`='$_POST[bleaching_tm]',
                    `second_lr`='$second_lr_format'
                    where `id` = '$_POST[id_status]' and `idm` = '$_POST[idm]'
");


mysqli_query($con, "UPDATE tbl_matching SET 
                `cocok_warna` = '$_POST[cocok_warna]',
                `proses`='$_POST[proses]',
                `no_item`='$_POST[item]',
                `no_warna`='$_POST[no_warna]',
                `warna`='$_POST[warna]',
                `jenis_kain`='$_POST[Kain]',
                `benang`='$Benang',
                `lebar`='$_POST[Lebar]',
                `gramasi`='$_POST[Gramasi]',
                `tgl_delivery`='$_POST[Tgl_delivery]',
                `no_order`='$_POST[Order]',
                `qty_order`='$_POST[QtyOrder]',
                `buyer`='$_POST[Buyer]',
                `recipe_code` = '$_POST[recipe_code]'
                where id = '$_POST[id_matching]'");


mysqli_query($con, "INSERT into log_status_matching set 
                `ids` = '$_POST[idm]',
                `status` = 'selesai',
                `info` = 'not yet approved',
                `do_by` = '$_SESSION[userLAB]', 
                `do_at` = '$time', 
                `ip_address` = '$ip_num'");

$response = array(
    'session' => 'LIB_SUCCSS',
    'exp' => 'updated'
);
echo json_encode($response);
