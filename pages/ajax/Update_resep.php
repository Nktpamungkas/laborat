<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');
$awal  = strtotime($_POST['tgl_buat_status']);
$akhir = strtotime(date('Y-m-d H:i:s'));
$benang_a = str_replace("'", "''", $_POST['benang_a']);
mysqli_query($con, "UPDATE `tbl_status_matching` SET
                        `percobaan_ke` = '$_POST[matching_ke]',
                        `benang_aktual` = '$benang_a',
                        `lebar_aktual` = '$_POST[lebar_a]',
                        `gramasi_aktual` = '$_POST[gramasi_a]',
                        `lr` = '$_POST[l_R]',
                        `ph` = '$_POST[kadar_air]',
                        `rc_sh` = '$_POST[RC_Suhu]',
                        `rc_tm` = '$_POST[RCWaktu]',
                        `soaping_sh` = '$_POST[soapingSuhu]',
                        `soaping_tm` = '$_POST[soapingWaktu]',
                        `cie_wi` = '$_POST[cie_wi]',
                        `cie_tint` = '$_POST[cie_tint]',
                        `yellowness` = '$_POST[yellowness]',
                        `spektro_r` = '$_POST[Spektro_R]',
                        `ket` = '$_POST[keterangan]',
                        `tside_c` = '$_POST[tside_c]',
                        `tside_min` = '$_POST[tside_min]',
                        `cside_c` = '$_POST[cside_c]',
                        `cside_min`= '$_POST[cside_min]',
                        `kadar_air`= '$_POST[kadar_air_true]',
                        `edited_at` = '$_SESSION[userLAB]',
                        `edited_by` = '$time',
                        `bleaching_sh`='$_POST[bleaching_sh]',
                        `bleaching_tm`='$_POST[bleaching_tm]',
                        `second_lr`='$_POST[second_lr]',
                        `remark_dye` = '$_POST[remark_dye]'
                        where `id` = '$_POST[id_status]' and `idm` = '$_POST[idm]'");
mysqli_query($con, "UPDATE tbl_matching SET
                            recipe_code = '$_POST[recipe_code]'
                        WHERE id = '$_POST[id_tblmatching]'");
$ip_num = $_SERVER['REMOTE_ADDR'];
mysqli_query($con, "INSERT into log_status_matching set 
                `ids` = '$_POST[idm]',
                `status` = 'selesai',
                `info` = 'modifikasi resep',
                `do_by` = '$_SESSION[userLAB]', 
                `do_at` = '$time', 
                `ip_address` = '$ip_num'");

$response = array(
    'session' => 'LIB_SUCCSS',
    'exp' => 'updated'
);
echo json_encode($response);
