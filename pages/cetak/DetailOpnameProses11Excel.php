<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=detailtutupproses11_" . date("Ymd", strtotime($_GET['tgl'])) . "_" . $_GET['tipe'] . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
ob_start();
?>
<?php
$tgl_tutup = isset($_GET['tgl']) ? $_GET['tgl'] : '';
$warehouse = isset($_GET['tipe']) ? $_GET['tipe'] : '';

// echo "<pre>";
// print_r($_GET); // Debug POST value
// echo "</pre>";
?>
<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
$tgl = date("Y-m-d");
// Konversi ke format seperti pada gambar
$bulanIndo = [
    'January' => 'JANUARI',
    'February' => 'FEBRUARI',
    'March' => 'MARET',
    'April' => 'APRIL',
    'May' => 'MEI',
    'June' => 'JUNI',
    'July' => 'JULI',
    'August' => 'AGUSTUS',
    'September' => 'SEPTEMBER',
    'October' => 'OKTOBER',
    'November' => 'NOVEMBER',
    'December' => 'DESEMBER'
];

$date = new DateTime($tgl_tutup);
$hari = $date->format('d');
$bulan = $bulanIndo[$date->format('F')];
$tahun = $date->format('Y');
?>
<style type="text/css">
.no-border th {
    border: none !important;
  }	
</style>


<p><br>
<table border="1" style="border-collapse: collapse;">
                  <thead>
                  <tr class="no-border">
                    <th colspan="7" align="center"><strong>LAPORAN STOCK GD. KIMIA</strong></th>
                  </tr>
                  <tr class="no-border">
                    <th colspan="7" align="center"><strong>PT. INDO TAICHEN TEXTILE INDUSTRY</strong></th>
                  </tr>
                  <tr>
                    <td colspan="7" align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">FW-##-GD#-02/03</td>
                  </tr>
                  <tr>
                    <td colspan="7" align="left" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="7" align="left" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="7" align="left" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;"><strong><?Php echo "TANGGAL : $hari $bulan $tahun"; ?></strong>
                    </td>
                    </tr>
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
            // echo "<pre>$query</pre>";
            $total_qty = (substr(number_format($r['total_qty'], 2), -3) == '.00')
              ? number_format($r['total_qty'], 0)
              : number_format($r['total_qty'], 2);
?>
	  <tr>
	  <td><?php echo $no; ?></td>
      <td><?php echo $r['KODE_OBAT']; ?></td>
      <td ><?php echo $r['LONGDESCRIPTION']; ?></td>
      <td><?php echo $r['LOTCODE']; ?></td>
      <td><?php echo $r['LOGICALWAREHOUSECODE']; ?></td>
      <td align="center"><?= $total_qty ?></td>     
      </tr>	  				  
<?php	$no++;
		$totqty=$totqty+$r['total_qty'];
	} ?>
				  </tbody>
				<tfoot>
				  <tr>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    </tr>	
                  
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Grand Total</strong></td>
                    <td align="right"><strong><?php echo number_format(round($totqty,3),3); ?></strong></td>                
                  <tr>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td align="right" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                    <td colspan="1" align="center">Dibuat Oleh :</td>
                    <td colspan="2" align="center">Diperiksa Oleh :</td>
                    <td colspan="1" align="center">Mengetahui:</td>
                    <td align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">NAMA</td>
                    <td colspan="1" align="center">.........</td>
                    <td colspan="2" align="center">.........</td>
                    <td colspan="1" align="center">.........</td>
                    <td align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">JABATAN</td>
                    <td colspan="1" align="center">Staff</td>
                    <td colspan="2" align="center">N/A</td>
                    <td colspan="1" align="center">Leader</td>
                    <td align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">TANGGAL</td>
                    <td colspan="1" align="center">&nbsp;</td>
                    <td colspan="2" align="center">&nbsp;</td>
                    <td colspan="1" align="center">&nbsp;</td>
                    <td align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">TANDA TANGAN
					  <p>&nbsp;</p>
   					<p>&nbsp;</p></td>
                    <td colspan="1">&nbsp;</td>
                    <td colspan="2" align="center">N/A</td>
                    <td colspan="1" align="center">&nbsp;</td>
                    <td align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  </tfoot>                  
                </table>
<br>
<?php ob_end_flush(); ?>
