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
	tbl_sts_matching_11
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
                        
                        $sql = mysqli_query($con,"SELECT *, a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by
                                    FROM tbl_status_matching a
                                    JOIN tbl_matching b ON a.idm = b.no_resep
                                    where a.status in ('buka', 'mulai', 'hold', 'revisi','tunggu')
                                    group by a.idm, b.no_resep
                                    ORDER BY a.id asc");
                                                ?>
<?php while ($li = mysqli_fetch_array($sql)) { 
	$warna = str_replace("'","''",$li['warna']);
	$no_warna = str_replace("'","''",$li['no_warna']);
	$no_item = str_replace("'","''",$li['no_item']);
	$langganan = str_replace("'","''",$li['langganan']);
	$no_po = str_replace("'","''",$li['no_po']);
	$jenis_kain = str_replace("'","''",$li['jenis_kain']);
	$benang = str_replace("'","''",$li['benang']);
	$note = str_replace("'","''",$li['note']);
	$proses = str_replace("'","''",$li['proses']);
	$cocok_warna = str_replace("'","''",$li['cocok_warna']);
	$ket = str_replace("'","''",$li['ket']);												
	$sqlupdate = mysqli_query($con,"INSERT INTO tbl_sts_matching_11 SET 
	idm  = '".$li['idm']."',               
	flag  = '".$li['flag']."',                
	grp  = '".$li['grp']."',                 
	matcher  = '".$li['matcher']."',             
	cek_warna  = '".$li['cek_warna']."',           
	cek_dye  = '".$li['cek_dye']."',             
	status  = '".$li['status']."',         
	kt_status  = '".$li['kt_status']."',           
	koreksi_resep  = '".$li['koreksi_resep']."',       
	percobaan_ke  = '".$li['percobaan_ke']."',        
	howmany_percobaan_ke  = '".$li['howmany_percobaan_ke']."',
	benang_aktual  = '".$li['benang_aktual']."',      
	lebar_aktual  = '".$li['lebar_aktual']."',        
	gramasi_aktual  = '".$li['gramasi_aktual']."',      
	ph  = '".$li['ph']."',                  
	soaping_sh  = '".$li['soaping_sh']."',          
	soaping_tm  = '".$li['soaping_tm']."',          
	rc_sh  = '".$li['rc_sh']."',               
	rc_tm  = '".$li['rc_tm']."',               
	lr  = '".$li['lr']."',                  
	cie_wi  = '".$li['cie_wi']."',              
	cie_tint  = '".$li['cie_tint']."',            
	yellowness  = '".$li['yellowness']."',          
	spektro_r  = '".$li['spektro_r']."',           
	ket  = '".$ket."',                 
	cside_c  = '".$li['cside_c']."',             
	cside_min  = '".$li['cside_min']."',           
	tside_c  = '".$li['tside_c']."',             
	tside_min  = '".$li['tside_min']."',           
	done_matching  = '".$li['done_matching']."',       
	created_at  = '".$li['created_at']."',          
	created_by  = '".$li['created_by']."',          
	edited_at  = '".$li['edited_at']."',           
	edited_by  = '".$li['edited_by']."',           
	target_selesai  = '".$li['target_selesai']."',      
	mulai_by  = '".$li['mulai_by']."',            
	mulai_at  = '".$li['mulai_at']."',            
	selesai_by  = '".$li['selesai_by']."',          
	selesai_at  = '".$li['selesai_at']."',          
	approve_by  = '".$li['approve_by']."',          
	approve_at  = '".$li['approve_at']."',          
	approve  = '".$li['approve']."',             
	hold_at  = '".$li['hold_at']."',             
	hold_by  = '".$li['hold_by']."',             
	batal_by  = '".$li['batal_by']."',            
	batal_at  = '".$li['batal_at']."',            
	timer  = '".$li['timer']."',               
	why_batal  = '".$li['why_batal']."',           
	revisi_at  = '".$li['revisi_at']."',           
	revisi_by  = '".$li['revisi_by']."',           
	kadar_air  = '".$li['kadar_air']."',           
	tutup_by  = '".$li['tutup_by']."',            
	tutup_at  = '".$li['tutup_at']."',            
	final_matcher  = '".$li['final_matcher']."',       
	colorist1  = '".$li['colorist1']."',           
	colorist2  = '".$li['colorist2']."',           
	acc_resep1  = '".$li['acc_resep1']."',          
	acc_resep2  = '".$li['acc_resep2']."',          
	create_resep  = '".$li['create_resep']."',        
	acc_ulang_ok  = '".$li['acc_ulang_ok']."',        
	penanggung_jawab  = '".$li['penanggung_jawab']."',    
	bleaching_tm  = '".$li['bleaching_tm']."',        
	bleaching_sh  = '".$li['bleaching_sh']."',        
	second_lr  = '".$li['second_lr']."',           
	remark_dye  = '".$li['remark_dye']."',          
	status_bleaching  = '".$li['status_bleaching']."',    
	no_resep  = '".$li['no_resep']."',            
	no_order  = '".$li['no_order']."',            
	no_po  = '".$li['no_po']."',               
	langganan  = '".$langganan."',           
	no_item  = '".$no_item."',             
	jenis_kain  = '".$jenis_kain."',          
	benang  = '".$benang."',              
	cocok_warna  = '".$cocok_warna."',         
	warna  = '".$warna."',               
	no_warna  = '".$no_warna."',            
	lebar  = '".$li['lebar']."',               
	gramasi  = '".$li['gramasi']."',             
	qty_order  = '".$li['qty_order']."',           
	status_bagi  = '".$li['status_bagi']."',         
	tgl_in  = '".$li['tgl_in']."',              
	tgl_out  = '".$li['tgl_out']."',             
	proses  = '".$proses."',              
	buyer  = '".$li['buyer']."',               
	tgl_delivery  = '".$li['tgl_delivery']."',        
	note  = '".$note."',                
	jenis_matching  = '".$li['jenis_matching']."',      
	tgl_buat  = '".$li['tgl_buat']."',            
	created_by1  = '".$li['created_by1']."',         
	tgl_update  = '".$li['tgl_update']."',          
	last_update_by  = '".$li['last_update_by']."',      
	salesman_sample  = '".$li['salesman_sample']."',     
	recipe_code  = '".$li['recipe_code']."',         
	g_ld  = '".$li['g_ld']."',                
	color_code  = '".$li['color_code']."',          
	id_status  = '".$li['id_status']."',           
	tgl_buat_status  = '".$li['tgl_buat_status']."',     
	status_created_by  = '".$li['status_created_by']."',   
	tgl_tutup='$Awal'
	");
	
}
if($sqlupdate){
		echo "<meta http-equiv='refresh' content='0; url=cetak/StatusResepRekapExcel11.php?tgl=$Awal'>";
		//echo "<meta http-equiv='refresh' content='0; url=List-Schedule-Auto.php?note=Berhasil'>";
	}
}
?>

                        