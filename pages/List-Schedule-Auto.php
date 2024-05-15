<?php
ini_set("error_reporting", 1);
include "../koneksi.php";
$Awal = date('Y-m-d');
$cektgl=mysqli_query($con,"SELECT
	DATE_FORMAT(NOW(), '%Y-%m-%d') as tgl,
	COUNT(tgl_tutup) as ck ,
	DATE_FORMAT(NOW(), '%H') as jam,
	DATE_FORMAT(NOW(), '%H:%i') as jam1,
	tgl_tutup 
FROM
	tbl_listsch_11
WHERE
	tgl_tutup = '$Awal'
LIMIT 1");
$dcek=mysqli_fetch_array($cektgl);
if($dcek['ck']>0){
	echo "<script>";
	echo "alert('Stok Tgl ".$dcek['tgl_tutup']." Ini Sudah Pernah ditutup')";
	echo "</script>";
}else if($_GET['note']!="" or $_GET['note']=="Berhasil"){
	echo "Tutup Transaksi Berhasil";
}else{
?>
<?php
                        
                        $sql = mysqli_query($con,"SELECT a.`id`, a.`no_resep`, a.`no_order`, a.`warna`, a.`no_warna`, a.`no_item`, a.`langganan`, a.`no_po`,b.approve, a.jenis_matching, a.benang,
                                                        b.`id` as id_status, b.status, a.status_bagi, ifnull(b.`ket`, a.note) as ket, a.tgl_update
                                                        FROM tbl_matching a 
                                                        left join tbl_status_matching b on a.`no_resep` = b.`idm`
                                                        where b.approve_at is null
                                                        order by a.id desc");
                                                ?>
<?php while ($li = mysqli_fetch_array($sql)) { 
	$warna = str_replace("'","''",$li['warna']);
	$no_warna = str_replace("'","''",$li['no_warna']);
	$no_item = str_replace("'","''",$li['no_item']);
	$langganan = str_replace("'","''",$li['langganan']);
	$no_po = str_replace("'","''",$li['no_po']);
	$jenis_matching = str_replace("'","''",$li['jenis_matching']);
	$benang = str_replace("'","''",$li['benang']);
	$ket = str_replace("'","''",$li['ket']);
	$sqlupdate = mysqli_query($con,"INSERT INTO tbl_listsch_11 SET 
	`no_resep`='".$li['no_resep']."',
	`no_order`='".$li['no_order']."',
	`warna`='".$warna."',
	`no_warna`='".$no_warna."',
	`no_item`='".$no_item."',
	`langganan`='".$langganan."',
	`no_po`='".$no_po."',
	`approve`='".$li['approve']."',
	`jenis_matching`='".$jenis_matching."',
	`benang`='".$benang."',
	`id_status`='".$li['id_status']."',
	`status`='".$li['status']."',
	`status_bagi`='".$li['status_bagi']."',
	`ket`='".$ket."',
	`tgl_update`='".$li['tgl_update']."',
	`tgl_tutup`='$Awal',
	`tgl_buat`=now()
	");
	
}
if($sqlupdate){
		echo "<meta http-equiv='refresh' content='0; url=cetak/ListScheduleRekapExcel11.php?tgl=$Awal'>";
		//echo "<meta http-equiv='refresh' content='0; url=List-Schedule-Auto.php?note=Berhasil'>";
	}
}
?>

                        