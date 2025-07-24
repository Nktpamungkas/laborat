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
                    </tr>                
                  </thead>
                  <tbody>
				  <?php				  
   $no=1;   
   $c=0;
   $sql = mysqli_query($con,"SELECT 
            ITEMTYPECODE,
            KODE_OBAT,
            LONGDESCRIPTION,
            LOTCODE,
            LOGICALWAREHOUSECODE,
            tgl_tutup,
            SUM(BASEPRIMARYQUANTITYUNIT) AS total_qty,
            BASEPRIMARYUNITCODE 
        FROM tblopname_11
        WHERE 
            tgl_tutup = '$tgl_tutup'
            AND LOGICALWAREHOUSECODE = '$warehouse'
        GROUP BY  
            ITEMTYPECODE,
            KODE_OBAT,
            LONGDESCRIPTION,
            LOTCODE,
            LOGICALWAREHOUSECODE,
            tgl_tutup,
            BASEPRIMARYUNITCODE
        ORDER BY KODE_OBAT ASC");		  
    while($r = mysqli_fetch_array($sql)){
            $total_qty = (substr(number_format($r['total_qty'], 2), -3) == '.00')
              ? number_format($r['total_qty'], 0)
              : number_format($r['total_qty'], 2);
?>
	   <tr>
	  <td><?php echo $no; ?></td>
      <td><?php echo $r['KODE_OBAT']; ?></td>
      <td><?php echo $r['LONGDESCRIPTION']; ?></td>
      <td><?php echo $r['LOTCODE']; ?></td>
      <td><?php echo $r['LOGICALWAREHOUSECODE']; ?></td>
      <td align="center"><?= $total_qty ?></td>
    </tr>
<?php $no++;
		$totqty=$totqty+$r['total_qty'];
	} ?>
				  </tbody>
				<tfoot>
                  <tr>
                    <td colspan="5"><center><strong>TOTAL</strong></center></td>
                    <td><strong><?php echo number_format($totqty,2); ?></strong></td>
                    </tr>
                  </tfoot>                  
                </table>
<?php ob_end_flush(); ?>