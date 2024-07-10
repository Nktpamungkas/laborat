<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";

$sql = mysqli_query($con, "SELECT * FROM tbl_test_qc WHERE sts_laborat <> 'Approved Full' ORDER BY id ASC");
while ($r0 = mysqli_fetch_array($sql)) {

	if ($_POST['cek'][$r0['id']] == $r0['id']) {
		$idkk1 = $r0['id'] . "," . $idkk1;
	}
}
$idP = explode(",", $idkk1);
$id_P1 = $idP[0];
$id_P2 = $idP[1];
$id_P3 = $idP[2];
$data = mysqli_query($con, "SELECT * FROM tbl_test_qc WHERE id='$id_P1' ORDER BY id DESC LIMIT 1");
$r = mysqli_fetch_array($data);
$detail2 = explode(",", $r['permintaan_testing']);
$data1 = mysqli_query($con, "SELECT * FROM tbl_test_qc WHERE id='$id_P2' ORDER BY id DESC LIMIT 1");
$r1 = mysqli_fetch_array($data1);
$detail21 = explode(",", $r1['permintaan_testing']);
$data2 = mysqli_query($con, "SELECT * FROM tbl_test_qc WHERE id='$id_P3' ORDER BY id DESC LIMIT 1");
$r2 = mysqli_fetch_array($data2);
$detail22 = explode(",", $r2['permintaan_testing']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Form Permintaan</title>
	<style>
		body,
		td,
		th {
			font-family: sans-serif, Roman, serif;
			font-size: 12px;
		}

		td {
			border-top: 0px #000000 solid;
			border-bottom: 0px #000000 solid;
			border-left: 0px #000000 solid;
			border-right: 0px #000000 solid;
		}

		body {
			padding-left: 1.6cm;
			padding-top: 0.2cm;
		}
	</style>
</head>


<body>
	<table width="100%" border="0" style="width: 7in;">
		<tbody>
			<tr>
				<td align="left" valign="top" style="height: 1.6in;">
					<table width="100%" border="0" class="table-list1" style="width: 2.3in;">
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid; ">
								<div style="font-size: 9px;"><strong>FORM PERMINTAAN TEST LABORATORY</strong></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">BUYER : <?php echo $r['buyer']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NAMA : <?php echo strtoupper($r['created_by']); ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">TANGGAL : <?php if ($r['tgl_update'] != "") {
																			echo date('d-m-Y H:i', strtotime(substr($r['tgl_update'], 0, 18)));
																		} ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NAMA WARNA : <?php echo $r['warna']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NOMER WARNA : <?php echo $r['no_warna']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">QR CODE : <?php echo $r['no_counter']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">PERM TESTING : <?php echo ($r['permintaan_testing'] == null || $r['permintaan_testing'] == '') ? "FULL TEST" : $r['permintaan_testing']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NOTE LAB : <?php echo $r['note_laborat']; ?></div>
							</td>
						</tr>
					</table>
				</td>
				<td align="left" valign="top" style="height: 1.6in;">
					<table width="100%" border="0" class="table-list1" style="width: 2.3in;">
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid; ">
								<div style="font-size: 9px;"><strong>FORM PERMINTAAN TEST LABORATORY</strong></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">BUYER : <?php echo $r['buyer']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NAMA : <?php echo strtoupper($r['created_by']); ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">TANGGAL : <?php if ($r['tgl_update'] != "") {
																			echo date('d-m-Y H:i', strtotime(substr($r['tgl_update'], 0, 18)));
																		} ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NAMA WARNA : <?php echo $r['warna']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NOMER WARNA : <?php echo $r['no_warna']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">QR CODE : <?php echo $r['no_counter']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">PERM TESTING : <?php echo ($r['permintaan_testing'] == null || $r['permintaan_testing'] == '') ? "FULL TEST" : $r['permintaan_testing']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NOTE LAB : <?php echo $r['note_laborat']; ?></div>
							</td>
						</tr>
					</table>
				</td>
				<td align="left" valign="top" style="height: 1.6in;">
					<table width="100%" border="0" class="table-list1" style="width: 2.3in;">
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid; ">
								<div style="font-size: 9px;"><strong>FORM PERMINTAAN TEST LABORATORY</strong></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">BUYER : <?php echo $r['buyer']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NAMA : <?php echo strtoupper($r['created_by']); ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">TANGGAL : <?php if ($r['tgl_update'] != "") {
																			echo date('d-m-Y H:i', strtotime(substr($r['tgl_update'], 0, 18)));
																		} ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NAMA WARNA : <?php echo $r['warna']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NOMER WARNA : <?php echo $r['no_warna']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">QR CODE : <?php echo $r['no_counter']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">PERM TESTING : <?php echo ($r['permintaan_testing'] == null || $r['permintaan_testing'] == '') ? "FULL TEST" : $r['permintaan_testing']; ?></div>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="border-top:0px #000000 solid; border-bottom:0px #000000 solid; border-left:0px #000000 solid; border-right:0px #000000 solid;">
								<div style="font-size: 8px;">NOTE LAB : <?php echo $r['note_laborat']; ?></div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</body>

</html>