<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LAB_RecapColorist11".date('Y-m-d').".xls"); //ganti nama sesuai keperluan
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
function get_value($start, $end, $jenis, $colorist)
{
include "../../koneksi.php";
$sql = mysqli_query($con,"SELECT idm, status , approve , colorist1 , colorist2 ,SUM(IF(a.colorist1 = '$colorist' , 0.5, 0) + IF(a.colorist2 = '$colorist' , 0.5, 0)) as total_value
	from tbl_status_matching a
	join tbl_matching b on a.idm = b.no_resep
	where DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') >= '$start' AND DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') <= '$end'
	and jenis_matching = '$jenis' and (a.colorist1 = '$colorist' or a.colorist2 = '$colorist')");
$data = mysqli_fetch_array($sql);

return $data['total_value'];
}
$colorist = mysqli_query($con,"SELECT * FROM tbl_colorist WHERE is_active = 'TRUE'"); 
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
                            