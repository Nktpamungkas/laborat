<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$time = date('Y-m-d H:i:s');
$ip_num = $_SERVER['REMOTE_ADDR'];
$benang_a = str_replace("'", "''", $_POST['benang_a']);
$Benang = str_replace("'", "''", $_POST['Benang']);
mysqli_query($con,"DELETE from tbl_matching_detail where id_matching = '$_POST[id_matching]'  and id_status = '$_POST[id_status]'");

mysqli_query($con,"UPDATE `tbl_status_matching` SET
                    `percobaan_ke` = '$_POST[matching_ke]',
                    `howmany_percobaan_ke` = '$_POST[howmany_Matching_ke]',
                    `benang_aktual` = '$benang_a',
                    `lebar_aktual` = '$_POST[lebar_a]',
                    `gramasi_aktual` = '$_POST[gramasi_a]',
                    `lr` = '$_POST[l_R]',
                    `ph` = '$_POST[kadar_air]',
                    `rc_sh` = '$_POST[RC_Suhu]',
                    `rc_tm` = '$_POST[RCWaktu]',
                    `soaping_sh` = '$_POST[soapingSuhu]',
                    `soaping_tm` = '$_POST[soapingWaktu]',
                    `status` = 'hold',
                    `cie_wi` = '$_POST[cie_wi]',
                    `cie_tint` = '$_POST[cie_tint]',
                    `yellowness` = '$_POST[yellowness]',
                    `spektro_r` = '$_POST[Spektro_R]',
                    `done_matching` = '$_POST[Done_Matching]',
                    `ket` = '$_POST[keterangan]',
                    `hold_by` = '$_SESSION[userLAB]',
                    `hold_at` = '$time',
                    `tside_c` = '$_POST[tside_c]',
                    `tside_min` = '$_POST[tside_min]',
                    `cside_c` = '$_POST[cside_c]',
                    `cside_min`= '$_POST[cside_min]',
                    `kadar_air`= '$_POST[kadar_air_true]',
                    `koreksi_resep`= '$_POST[koreksi_resep]',
                    `final_matcher`= '$_POST[final_matcher]',
                    `colorist1` = '$_POST[colorist1]',
                    `colorist2` = '$_POST[colorist2]',
                    `matcher` = '$_POST[Matcher]',
                    `grp`='$_POST[Group]',
                    `bleaching_sh`='$_POST[bleaching_sh]',
                    `bleaching_tm`='$_POST[bleaching_tm]',
                    `second_lr`='$_POST[second_lr]'
                    where `id` = '$_POST[id_status]' and `idm` = '$_POST[idm]'
");

mysqli_query($con,"UPDATE tbl_matching SET 
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

mysqli_query($con,"INSERT into log_status_matching set 
                `ids` = '$_POST[idm]',
                `status` = 'hold',
                `info` = 'Save & Pause',
                `do_by` = '$_SESSION[userLAB]', 
                `do_at` = '$time', 
                `ip_address` = '$ip_num'");

$response = array(
    'session' => 'LIB_SUCCSS_HOLD',
    'exp' => 'updated'
);
echo json_encode($response);
