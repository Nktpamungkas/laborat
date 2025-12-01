<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LAB_RecapColorist11" . date('Y-m-d') . ".xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
// disini script laporan anda
?>
<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';

//$start_date = date('Y-m-d', strtotime("-1 days"));
//$end_date = date('Y-m-d');
$start_date = '2025-05-14';
$end_date = '2025-05-15';

$start = $start_date . " 23:00:00";
$end = $end_date . " 23:00:00";

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
	function get_value($start, $end, $jenis, $colorist)
	{
		include '../../koneksi.php';
		$sql = mysqli_query($con, "SELECT
							a.colorist1,
							a.colorist2,
							a.colorist3,
							a.colorist4,
							a.colorist5,
							a.colorist6,
							a.colorist7,
							a.colorist8,
							SUM(IF(a.colorist1 = '$colorist', 0.5, 0 ) + 
								IF(a.colorist2 = '$colorist', 0.5, 0 ) +
								IF(a.colorist3 = '$colorist', 0.5, 0 ) +
								IF(a.colorist4 = '$colorist', 0.5, 0 ) +
								IF(a.colorist5 = '$colorist', 0.5, 0 ) +
								IF(a.colorist6 = '$colorist', 0.5, 0 ) +
								IF(a.colorist7 = '$colorist', 0.5, 0 ) +
								IF(a.colorist8 = '$colorist', 0.5, 0 )) AS total_value 
						FROM
							tbl_status_matching a
							JOIN tbl_matching b ON a.idm = b.no_resep 
						WHERE
							a.approve_at >= '$start'
							AND a.approve_at < '$end'
							AND b.jenis_matching = '$jenis'
							AND ('$colorist' IN (a.colorist1, a.colorist2, a.colorist3, a.colorist4,
												a.colorist5, a.colorist6, a.colorist7, a.colorist8))
							AND a.status = 'selesai'");

		$data = mysqli_fetch_array($sql);

		return $data['total_value'];
	}
	$colorist = mysqli_query($con, "SELECT * FROM tbl_colorist WHERE is_active = 'TRUE'");
	?>
	<?php
	$all = 0;
	while ($clrst = mysqli_fetch_array($colorist)) { ?>
		<tr>
			<td><?php echo $clrst['nama'] ?></td>
			<td><?php $mu = get_value($start, $end, 'Matching Ulang', $clrst['nama']) + get_value($start, $end, 'Matching Ulang NOW', $clrst['nama']);
				echo $mu; ?> </td>
			<td><?php $mp = get_value($start, $end, 'Perbaikan', $clrst['nama']) + get_value($start, $end, 'Perbaikan NOW', $clrst['nama']);
				echo $mp; ?> </td>
			<td><?php $ld = get_value($start, $end, 'L/D', $clrst['nama']) + get_value($start, $end, 'LD NOW', $clrst['nama']);
				echo $ld; ?> </td>
			<td><?php $md = get_value($start, $end, 'Matching Development', $clrst['nama']);
				echo $md; ?> </td>
			<td><?php $total = $mu + $mp + $ld + $md;
				echo $total ?></td>
			<?php $all += $total; ?>
		</tr>
	<?php } ?>
</table>