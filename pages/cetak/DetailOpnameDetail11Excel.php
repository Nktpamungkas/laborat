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
   $sql = mysqli_query($con,"SELECT DISTINCT
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
            and not KODE_OBAT ='E-1-000'
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

            $value = (string) $r['total_qty'];

            if (strpos($value, '.') !== false) {
              // Hapus nol di belakang desimal, tapi jangan hilangkan titik kalau hasilnya bilangan bulat
              $formatted = rtrim(rtrim($value, '0'), '.');

              // Jika desimalnya habis (misal 50.), tambahkan .00
              if (strpos($formatted, '.') === false) {
                $formatted .= '.00';
              } else {
                // Kalau desimalnya tinggal 1 digit, tambahkan 0
                $decimal_part = explode('.', $formatted)[1];
                if (strlen($decimal_part) === 1) {
                  $formatted .= '0';
                }
              }
            } else {
              // Bilangan bulat → tambahkan .00
              $formatted = $value . '.00';
            }
?>
	   <tr>
	  <td><?php echo $no; ?></td>
      <td><?php echo $r['KODE_OBAT']; ?></td>
      <td><?php echo $r['LONGDESCRIPTION']; ?></td>
      <td><?php echo $r['LOTCODE']; ?></td>
      <td><?php echo $r['LOGICALWAREHOUSECODE']; ?></td>
      <td align="center"><?= $formatted ?></td>
    </tr>
<?php $no++;
		$totqty=$totqty+$formatted;
	} ?>
				  </tbody>
				<tfoot>
                  <tr>
                    <td colspan="5"><center><strong>TOTAL</strong></center></td>
                    <td><strong><?php echo $totqty; ?></strong></td>
                    </tr>
                  </tfoot>                  
                </table>
<?php ob_end_flush(); ?>