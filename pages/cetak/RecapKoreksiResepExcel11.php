<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LAB_RecapKoreksiResep11".date('Y-m-d').".xls"); //ganti nama sesuai keperluan
	header("Pragma: no-cache");
	header("Expires: 0");
	// disini script laporan anda
?>
<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
$date_s = date('Y-m-d', strtotime("-1 days"));
$date_e = date('Y-m-d');
$time_s = "23:00";
$time_e = "23:00";
$start 	= $date_s." ".$time_s;
$end 	= $date_e." ".$time_e;
?>
<table>                                 
<tr>
	<th>Nama</th>
	<th>Matching Ulang</th>
	<th>Perbaikan</th>
	<th>L/D</th>
	<th>Matching Development</th>
	<th>Total</th>
</tr>
<?php
	function get_val($start, $end, $jenis, $colorist)
	{
		include "../../koneksi.php";
		$sql = mysqli_query($con,"SELECT
									a.koreksi_resep,
									a.koreksi_resep2,
									a.koreksi_resep3,
									a.koreksi_resep4,
									a.koreksi_resep5,
									a.koreksi_resep6,
									a.koreksi_resep7,
									a.koreksi_resep8,
									SUM(IF( a.koreksi_resep = '$colorist', 0.5, 0 ) +
										IF(a.koreksi_resep2 = '$colorist', 0.5, 0 ) +
										IF(a.koreksi_resep3 = '$colorist', 0.5, 0 ) +
										IF(a.koreksi_resep4 = '$colorist', 0.5, 0 ) +
										IF(a.koreksi_resep5 = '$colorist', 0.5, 0 ) +
										IF(a.koreksi_resep6 = '$colorist', 0.5, 0 ) +
										IF(a.koreksi_resep7 = '$colorist', 0.5, 0 ) +
										IF(a.koreksi_resep8 = '$colorist', 0.5, 0 )) AS total_value 
								FROM
									tbl_status_matching a
									JOIN tbl_matching b ON a.idm = b.no_resep 
								WHERE
									DATE_FORMAT( a.approve_at, '%Y-%m-%d %H:%i' ) >= '$start' AND DATE_FORMAT( a.approve_at, '%Y-%m-%d %H:%i' ) <= '$end' 
									AND jenis_matching = '$jenis' 
									AND (a.koreksi_resep = '$colorist' 
										OR a.koreksi_resep2 = '$colorist'
										OR a.koreksi_resep3 = '$colorist'
										OR a.koreksi_resep4 = '$colorist'
										OR a.koreksi_resep5 = '$colorist'
										OR a.koreksi_resep6 = '$colorist'
										OR a.koreksi_resep7 = '$colorist'
										OR a.koreksi_resep8 = '$colorist')
									AND `status` = 'selesai'");
		$data = mysqli_fetch_array($sql);

		return $data['total_value'];
	}
			$alll = 0;			
			$colorist = mysqli_query($con,"SELECT * FROM tbl_colorist WHERE is_active= 'TRUE' ");
			while ($clrst = mysqli_fetch_array($colorist)) { ?>
				<tr>
					<td><?php echo $clrst['nama'] ?></td>
					<td><?php $mu2 = get_val($start, $end, 'Matching Ulang', $clrst['nama']) + get_val($start, $end, 'Matching Ulang NOW', $clrst['nama']);
						echo $mu2; ?> </td>
					<td><?php $mp2 = get_val($start, $end, 'Perbaikan', $clrst['nama']) + get_val($start, $end, 'Perbaikan NOW', $clrst['nama']);
						echo $mp2; ?> </td>
					<td><?php $ld2 = get_val($start, $end, 'L/D', $clrst['nama']) + get_val($start, $end, 'LD NOW', $clrst['nama']);
						echo $ld2; ?> </td>
					<td><?php $md2 = get_val($start, $end, 'Matching Development', $clrst['nama']) + 0;
						echo $md2; ?> </td>
					<td><?php $totall = $mu2 + $mp2 + $ld2 + $md2;
						echo $totall ?></td>
					<?php $alll += $totall; ?>
				</tr>
			<?php } ?>                                        
</table>