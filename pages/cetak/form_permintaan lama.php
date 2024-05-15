<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
//--
$idkk=$_REQUEST['idkk'];
$act=$_GET['g'];
$data=mysqli_query($con,"SELECT * FROM tbl_test_qc WHERE id='$idkk' ORDER BY id DESC LIMIT 1");
$r=mysqli_fetch_array($data);
$detail2=explode(",",$r['permintaan_testing']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles_cetak.css" rel="stylesheet" type="text/css">
<title>Form Permintaan</title>
<style>
	td{
	border-top:0px #000000 solid; 
	border-bottom:0px #000000 solid;
	border-left:0px #000000 solid; 
	border-right:0px #000000 solid;
	}
	</style>
</head>


<body>
<table width="45%" border="0" class="table-list1">
  <tbody>
    <tr>
      <td colspan="3" align="center" valign="middle" style="height: 0.3in; font-size: 14px;"><strong>FORM PERMINTAAN TEST LABORATORY</strong></td>
    </tr>
    <tr>
      <td style="height: 0.25in;"><strong>BUYER</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo $r['buyer'];?></strong></td>
    </tr>
    <tr>
      <td style="height: 0.25in;"><strong>NAMA</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo strtoupper($r['created_by']);?></strong></td>
    </tr>
    <tr>
      <td style="height: 0.25in;"><strong>TANGGAL</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo date('d-m-Y H:i', strtotime(substr($r['tgl_update'],0,18)));?></strong></td>
    </tr>
    <tr>
      <td style="height: 0.25in;"><strong>NAMA WARNA</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo $r['warna']; ?></strong></td>
    </tr>
    <tr>
      <td style="height: 0.25in;"><strong>NOMER WARNA</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo $r['no_warna']; ?></strong></td>
    </tr>
    <tr>
      <td style="height: 0.25in;"><strong>QC CODE</strong></td>
      <td align="center" valign="middle"><strong>:</strong></td>
      <td><strong><?php echo $r['no_counter']; ?></strong></td>
    </tr>
	<?php   
	  $no = 0; // Inisialisasi variabel nomor
	  $rowspan = count(array_filter($detail2)); // Hitung jumlah elemen yang tidak kosong

	  // Cetak baris pertama dengan rowspan
	  if ($rowspan > 0) {
	?>
	<tr>
		<td rowspan="<?php echo $rowspan; ?>" align="left" valign="top" style="height: 0.25in;"><strong>PERMINTAAN TESTING</strong></td>
		<td style="height: 0.25in;" align="center" valign="middle"><strong><?php echo $no + 1; ?></strong></td>
		<td><strong>
			<?php if(!empty($detail2[0])){ echo $detail2[0]; } else { echo "FULL TEST"; } ?>
		</strong></td>
	</tr>
	<?php
	  }

		// Lakukan sesuatu dengan $detail2 di sini, mulai dari indeks 1
		for ($i = 1; $i < count($detail2); $i++) {
			if (!empty(trim($detail2[$i]))) {
				$no++;
	  ?>
		<tr>
			<td style="height: 0.25in;" align="center" valign="middle"><strong><?php echo $no + 1; ?></strong></td>
			<td><strong>
				<?php echo $detail2[$i]; ?>
			</strong></td>
		</tr>
		<?php
			}
		}
		?>

	<?php if($r['permintaan_testing']==""){  ?>
	<tr>
      <td style="height: 0.25in;"><strong>PERMINTAAN TESTING</strong></td>
      <td align="center" valign="middle"><strong><?php echo "1"; ?></strong></td>
      <td><strong><?php echo "FULL TEST"; ?></strong></td>
    </tr>
	<?php } ?>
  </tbody>
</table>
</body>
</html>