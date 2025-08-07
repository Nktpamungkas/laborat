<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=detailtutup11 " . date($_GET['tgl'])." ". $_GET['tipe'] . ".xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
ob_start();
?>
<?php
$tgl_tutup = isset($_GET['tgl']) ? $_GET['tgl'] : '';
$warehouse = isset($_GET['tipe']) ? $_GET['tipe'] : '';
?>
<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
include "../../includes/Penomoran_helper.php";
$tgl = date("Y-m-d");
?>
<table border="1">
                  <thead>
                   <tr>
                    <th><strong>No</strong></th>
                    <th><strong>KODE OBAT</strong></th>
                    <th><strong>NAMA OBAT</strong></th>
                    <th><strong>LOTCODE</strong></th>
                    <th><strong>LOGICALWAREHOUSE</strong></th>
                    <th><strong>QTY (ENDING BALANCE)</strong></th>
                    <th><strong>Stock Opname</strong></th>
                    </tr>                
                  </thead>
                  <tbody>
				  <?php				  
   $no=1;   
   $c=0;
   $totstock=0;
   $sql = mysqli_query($con,"SELECT *
        FROM tbl_stock_opname_gk 
        WHERE 
            tgl_tutup = '$tgl_tutup'
            AND LOGICALWAREHOUSECODE = '$warehouse'
        ORDER BY KODE_OBAT ASC");		  
    while($r = mysqli_fetch_array($sql)){
    if($r['konfirmasi']==1){
      $stock=  Penomoran_helper::nilaiKeRibuan($r['total_stock']);
      $totstock+=$r['total_stock'];
    }else{
      $stock=  "-";
      $totstock+=0;
    }
?>
	   <tr>
	    <td><?= $no; ?></td>
      <td><?= $r['KODE_OBAT']; ?></td>
      <td><?= $r['LONGDESCRIPTION']; ?></td>
      <td><?= $r['LOTCODE']; ?></td>
      <td><?= $r['LOGICALWAREHOUSECODE']; ?></td>
      <td align="center"><?=Penomoran_helper::nilaiKeRibuan($r['total_qty']) ?></td>
      <td align="center"><?=$stock ?></td>
    </tr>
<?php $no++;
		$totqty=$totqty+$r['total_qty'];
	} ?>
				  </tbody>
				<tfoot>
                  <tr>
                    <td colspan="5"><center><strong>TOTAL Balance</strong></center></td>
                    <td><strong><?php echo Penomoran_helper::nilaiKeRibuan($totqty); ?></strong></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="5"><center><strong>TOTAL Opname</strong></center></td>
                    <td><strong><?php echo Penomoran_helper::nilaiKeRibuan($totstock); ?></strong></td>
                    <td></td>
                  </tr>
                  </tfoot>                  
                </table>
<?php ob_end_flush(); ?>