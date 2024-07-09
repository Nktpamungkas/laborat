<?php
ini_set("error_reporting", 1);
session_start();
include "../../koneksi.php";
//--
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
	<!--<link href="styles_cetaktest1.css" rel="stylesheet" type="text/css">-->
	<title>Form Permintaan</title>
	<style>
		td {
			border-top: 0px #000000 solid;
			border-bottom: 0px #000000 solid;
			border-left: 0px #000000 solid;
			border-right: 0px #000000 solid;
		}

		body,
		td,
		th {
			/*font-family: Courier New, Courier, monospace; */
			font-family: sans-serif, Roman, serif;
			font-size: 12px;
		}

		pre {
			font-family: sans-serif, Roman, serif;
			clear: both;
			margin: 0px auto 0px;
			padding: 0px;
			white-space: pre-wrap;
			/* Since CSS 2.1 */
			white-space: -moz-pre-wrap;
			/* Mozilla, since 1999 */
			white-space: -pre-wrap;
			/* Opera 4-6 */
			white-space: -o-pre-wrap;
			/* Opera 7 */
			word-wrap: break-word;

		}

		body {
			margin: 0px auto 0px;
			padding: 2px;
			font-size: 8px;
			color: #000;
			width: 98%;
			background-position: top;
			background-color: #fff;
		}

		.table-list {
			clear: both;
			text-align: left;
			border-collapse: collapse;
			margin: 0px 0px 10px 0px;
			background: #fff;
		}

		.table-list td {
			color: #333;
			font-size: 12px;
			border-color: #fff;
			border-collapse: collapse;
			vertical-align: center;
			padding: 3px 5px;
			border-bottom: 1px #000000 solid;
			border-left: 1px #000000 solid;
			border-right: 1px #000000 solid;


		}

		.table-list1 {
			clear: both;
			text-align: left;
			border-collapse: collapse;
			margin: 0px 0px 5px 0px;
			background: #fff;
		}

		.table-list1 td {
			color: #333;
			font-size: 11px;
			border-color: #fff;
			border-collapse: collapse;
			vertical-align: center;
			padding: 1px 3px;
			border-bottom: 1px #000000 solid;
			border-top: 1px #000000 solid;
			border-left: 1px #000000 solid;
			border-right: 1px #000000 solid;


		}

		@page {
			size: A4;
			margin: 10px 10px 10px 10px;
			font-size: 8pt !important;
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			size: portrait;
		}

		@media print {
			@page {
				size: A4;
				margin: 10px 10px 10px 10px;
				size: portrait;
				font-size: 8pt !important;
			}
	</style>
</head>


<body>
	<table border="0" style="width: 7.3in;">
		<!--style="width: 7.5in;"-->
		<tbody>
			<tr>
				<td align="left" valign="top" style="height: 1.6in;">&nbsp;</td>
				<td align="left" valign="top" style="height: 1.6in;">
					<table width="100%" border="0" class="table-list1" style="width: 2.3in;">
						<tbody>
							<tr>
								<td colspan="3" align="center" valign="middle" style="font-size: 9px;"><strong>FORM PERMINTAAN TEST LABORATORY</strong></td>
							</tr>
							<tr>
								<td style=" font-size: 9px;"><strong>BUYER</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r['buyer']; ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>NAMA</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo strtoupper($r['created_by']); ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>TANGGAL</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php if ($r['tgl_update'] != "") {
																		echo date('d-m-Y H:i', strtotime(substr($r['tgl_update'], 0, 18)));
																	} ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>NAMA WARNA</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r['warna']; ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>NOMER WARNA</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r['no_warna']; ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>QC CODE</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r['no_counter']; ?></strong></td>
							</tr>
							<?php
							$no = 0; // Inisialisasi variabel nomor
							$rowspan = count(array_filter($detail2)); // Hitung jumlah elemen yang tidak kosong

							// Cetak baris pertama dengan rowspan
							if ($rowspan > 0) {
							?>
								<tr>
									<td rowspan="<?php echo $rowspan; ?>" align="left" valign="top" style=" font-size:9px;"><strong>PERMINTAAN TESTING</strong></td>
									<td style=" font-size:9px;" align="center" valign="middle"><strong><?php echo $no + 1; ?></strong></td>
									<td style="font-size:9px;"><strong>
											<?php if (!empty($detail2[0])) {
												echo $detail2[0];
											} else {
												echo "FULL TEST";
											} ?>
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
										<td style=" font-size:9px;" align="center" valign="middle"><strong><?php echo $no + 1; ?></strong></td>
										<td style="font-size:9px;"><strong> <?php echo $detail2[$i]; ?></strong></td>
									</tr>
							<?php
								}
							}
							?>
							<?php if ($r['permintaan_testing'] == "") {  ?>
								<tr>
									<td style=" font-size:9px;"><strong>PERMINTAAN TESTING</strong></td>
									<td align="center" valign="middle" style="font-size:9px;"><strong><?php echo "1"; ?></strong></td>
									<td style="font-size:9px;"><strong><?php echo "FULL TEST"; ?></strong></td>
								</tr>
							<?php } ?>
							<tr>
								<td style=" font-size:9px;"><strong>NOTE LAB</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong><?php echo ":"; ?></strong></td>
								<td style="font-size:9px;"><strong><?php echo $r1['note_laborat']; ?></strong></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td></td>
				<td align="left" valign="top" style="height: 1.6in;">
					<table width="100%" border="0" class="table-list1" style="width: 2.3in;">
						<tbody>
							<tr>
								<td colspan="3" align="center" valign="middle" style="font-size: 9px;"><strong>FORM PERMINTAAN TEST LABORATORY</strong></td>
							</tr>
							<tr>
								<td style=" font-size: 9px;"><strong>BUYER</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r1['buyer']; ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>NAMA</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo strtoupper($r1['created_by']); ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>TANGGAL</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php if ($r1['tgl_update'] != "") {
																		echo date('d-m-Y H:i', strtotime(substr($r1['tgl_update'], 0, 18)));
																	} ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>NAMA WARNA</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r1['warna']; ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>NOMER WARNA</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r1['no_warna']; ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>QC CODE</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r1['no_counter']; ?></strong></td>
							</tr>
							<?php
							$no = 0; // Inisialisasi variabel nomor
							$rowspan = count(array_filter($detail21)); // Hitung jumlah elemen yang tidak kosong

							// Cetak baris pertama dengan rowspan
							if ($rowspan > 0) {
							?>
								<tr>
									<td rowspan="<?php echo $rowspan; ?>" align="left" valign="top" style=" font-size:9px;"><strong>PERMINTAAN TESTING</strong></td>
									<td style=" font-size:9px;" align="center" valign="middle"><strong><?php echo $no + 1; ?></strong></td>
									<td style="font-size:9px;"><strong>
											<?php if (!empty($detail21[0])) {
												echo $detail21[0];
											} else {
												echo "FULL TEST";
											} ?>
										</strong></td>
								</tr>
								<?php
							}

							// Lakukan sesuatu dengan $detail2 di sini, mulai dari indeks 1
							for ($i = 1; $i < count($detail21); $i++) {
								if (!empty(trim($detail21[$i]))) {
									$no++;
								?>
									<tr>
										<td style=" font-size:9px;" align="center" valign="middle"><strong><?php echo $no + 1; ?></strong></td>
										<td style="font-size:9px;"><strong> <?php echo $detail21[$i]; ?></strong></td>
									</tr>
							<?php
								}
							}
							?>
							<?php if ($r1['permintaan_testing'] == "") {  ?>
								<tr>
									<td style=" font-size:9px;"><strong>PERMINTAAN TESTING</strong></td>
									<td align="center" valign="middle" style="font-size:9px;"><strong><?php echo "1"; ?></strong></td>
									<td style="font-size:9px;"><strong><?php echo "FULL TEST"; ?></strong></td>
								</tr>
							<?php } ?>
							<tr>
								<td style=" font-size:9px;"><strong>NOTE LAB</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong><?php echo ":"; ?></strong></td>
								<td style="font-size:9px;"><strong><?php echo $r1['note_laborat']; ?></strong></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td></td>
				<td align="left" valign="top" style="height: 1.6in;">
					<table width="100%" border="0" class="table-list1" style="width: 2.3in;">
						<tbody>
							<tr>
								<td colspan="3" align="center" valign="middle" style="font-size: 9px;"><strong>FORM PERMINTAAN TEST LABORATORY</strong></td>
							</tr>
							<tr>
								<td style=" font-size: 9px;"><strong>BUYER</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r2['buyer']; ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>NAMA</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo strtoupper($r2['created_by']); ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>TANGGAL</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php if ($r2['tgl_update'] != "") {
																		echo date('d-m-Y H:i', strtotime(substr($r2['tgl_update'], 0, 18)));
																	} ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>NAMA WARNA</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r2['warna']; ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>NOMER WARNA</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r2['no_warna']; ?></strong></td>
							</tr>
							<tr>
								<td style=" font-size:9px;"><strong>QC CODE</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong>:</strong></td>
								<td style="font-size:9px;"><strong><?php echo $r2['no_counter']; ?></strong></td>
							</tr>
							<?php
							$no = 0; // Inisialisasi variabel nomor
							$rowspan = count(array_filter($detail22)); // Hitung jumlah elemen yang tidak kosong

							// Cetak baris pertama dengan rowspan
							if ($rowspan > 0) {
							?>
								<tr>
									<td rowspan="<?php echo $rowspan; ?>" align="left" valign="top" style=" font-size:9px;"><strong>PERMINTAAN TESTING</strong></td>
									<td style=" font-size:9px;" align="center" valign="middle"><strong><?php echo $no + 1; ?></strong></td>
									<td style="font-size:9px;"><strong>
											<?php if (!empty($detail22[0])) {
												echo $detail22[0];
											} else {
												echo "FULL TEST";
											} ?>
										</strong></td>
								</tr>
								<?php
							}

							// Lakukan sesuatu dengan $detail2 di sini, mulai dari indeks 1
							for ($i = 1; $i < count($detail22); $i++) {
								if (!empty(trim($detail22[$i]))) {
									$no++;
								?>
									<tr>
										<td style=" font-size:9px;" align="center" valign="middle"><strong><?php echo $no + 1; ?></strong></td>
										<td style="font-size:9px;"><strong> <?php echo $detail22[$i]; ?></strong></td>
									</tr>
							<?php
								}
							}
							?>
							<?php if ($r2['permintaan_testing'] == "") {  ?>
								<tr>
									<td style=" font-size:9px;"><strong>PERMINTAAN TESTING</strong></td>
									<td align="center" valign="middle" style="font-size:9px;"><strong><?php echo "1"; ?></strong></td>
									<td style="font-size:9px;"><strong><?php echo "FULL TEST"; ?></strong></td>
								</tr>
							<?php } ?>
							<tr>
								<td style=" font-size:9px;"><strong>NOTE LAB</strong></td>
								<td align="center" valign="middle" style="font-size:9px;"><strong><?php echo ":"; ?></strong></td>
								<td style="font-size:9px;"><strong><?php echo $r1['note_laborat']; ?></strong></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</body>

</html>