<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LAB_statusresep11".$_GET['tgl'].".xls"); //ganti nama sesuai keperluan
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
                    <th>Stts</th>
                    <th>Grp</th>
                    <th>Matcher</th>
                    <th>Rcode</th>
                    <th>No.Order</th>
                    <th>Langganan</th>
                    <th>Warna</th>
                    <th>No.Warna</th>
                    <th>No. Item</th>
                    <th>timer</th>
                    <th>Tgl Tutup</th>
                    </tr>
				  <?php	
   $no=1;   
   $c=0;
   $sqlDB21 = " SELECT *
                                    FROM tbl_sts_matching_11
									WHERE tgl_tutup='".date("Y-m-d", strtotime($TglTutup))."'
                                    group by idm, no_resep
                                    ORDER BY id asc";
	$stmt1   = mysqli_query($con,$sqlDB21);
    while($r = mysqli_fetch_array($stmt1)){		
	?>
	  <tr>
	    <td><?php echo $r['status'] ?> <?php echo $r['kt_status']; ?></td>
	    <td><?php echo $r['grp']; ?></td>
	    <td><?php echo $r['matcher']; ?></td>
	    <td><?php echo $r['idm']; ?></td>
	    <td><?php echo $r['no_order']; ?></td>
	    <td><?php echo $r['langganan']; ?></td>
	    <td><?php echo $r['warna']; ?></td>
	    <td><?php echo $r['no_warna']; ?></td>
	    <td><?php echo $r['no_item']; ?></td>
	    <td><?php
                      $awal  = strtotime($r['tgl_buat_status']);
                      $akhir = strtotime(date('Y-m-d H:i:s'));
                      $diff  = $akhir - $awal;

                      $hari  = floor($diff / (60 * 60 * 24));
                      $jam   = floor(($diff - ($hari * (60 * 60 * 24))) / (60 * 60));
                      $menit = ($diff - ($hari * (60 * 60 * 24))) - (($jam) * (60 * 60));

                      echo "<span>" . $hari . " Hari</span> : <span>" . $jam . " Jam</span> : <span>" . floor($menit / 60) . " Menit</span>";
                      ?></td>
	    <td><?php echo $r['tgl_tutup'] ?></td>
      </tr>				  
<?php	$no++; } ?>                  
        </table>