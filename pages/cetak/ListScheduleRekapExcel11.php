<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LAB_listschedule11".$_GET['tgl'].".xls"); //ganti nama sesuai keperluan
	header("Pragma: no-cache");
	header("Expires: 0");
	// disini script laporan anda
?>
<?php
	include "../../koneksi.php";
	ini_set("error_reporting", 1);
	$TglTutup=$_GET['tgl'];
?>
<table>
                  <tr>
                    <th>Status</th>
                    <th>No. Resep</th>
                    <th>J. Matching</th>
                    <th>No. Order</th>
                    <th>Benang</th>
                    <th>Warna</th>
                    <th>No.warna</th>
                    <th>Langganan</th>
                    <th>No. Item</th>
                    <th>Keterangan</th>
                    <th>Tgl Update</th>
                    <th>Tgl Tutup</th>
                    </tr>
				  <?php	
   $no=1;   
   $c=0;
   $sqlDB21 = " SELECT *
   FROM tbl_listsch_11 WHERE tgl_tutup ='".date("Y-m-d", strtotime($TglTutup))."'
   ORDER BY id DESC";
	$stmt1   = mysqli_query($con,$sqlDB21);
    while($li = mysqli_fetch_array($stmt1)){		
	?>
	  <tr>
	    <td><?php if ($li['status'] == null or $li['status'] == "") { ?>
                                                <!-- status kosong -->
                                                <?php if ($li['status_bagi'] == 'siap bagi') { 
                                                    echo "Siap Bagi";                                              
													} else if ($li['status_bagi'] == 'tunggu') {
													echo "tunggu"; 
                                                } else { echo "Belum Bagi"; } ?>
           <?php } else { ?>
                                                <?php if ($li['status'] == 'buka') {
                                                    echo 'sedang jalan';
                                                } else if ($li['status'] == 'selesai' && $li['approve'] == 'NONE') {
                                                    echo 'Waiting Approval';
                                                } else if ($li['status'] == 'selesai' && $li['approve'] == 'TRUE') {
                                                    echo 'Selesai';
                                                } else {
                                                    echo  $li['status'];
                                                }
                                                ?>
                                            <?php } ?></td>
	    <td><?php echo $li['no_resep'] ?></td>
	    <td><?php echo $li['jenis_matching'] ?></td>
	    <td><?php echo $li['no_order'] ?></td>
	    <td><?php echo $li['benang'] ?></td>
	    <td><?php echo $li['warna'] ?></td>
	    <td><?php echo $li['no_warna'] ?></td>
	    <td><?php echo $li['langganan'] ?></td>
	    <td><?php echo $li['no_item'] ?></td>
	    <td><?php echo $li['ket'] ?></td>
	    <td><?php echo $li['tgl_update'] ?></td>
	    <td><?php echo $li['tgl_tutup'] ?></td>
      </tr>				  
<?php	$no++; } ?>                  
        </table>