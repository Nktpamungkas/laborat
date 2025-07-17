<?php
include '../../koneksi.php';

$getDyestuff = $_GET['Dystf'] ?? null;
$getJnsMtcg = $_GET['jnsMtcg'] ?? null;

$where = "1";

if ($getDyestuff) {
	if ($getDyestuff === 'DR') {
		$where = "dispensing IN (1,2,3)";

		if (in_array($getJnsMtcg, ['L/D', 'LD NOW'])) {
            echo '<option value="-">-</option>';
        }
	} elseif ($getDyestuff === 'CD') {
		$where = "dispensing = 1";
	} elseif ($getDyestuff === 'OB') {
		$where = "dispensing = 3";
	} else {
		$char = strtoupper(substr($getDyestuff, 0, 1));
		switch ($char) {
			case 'D':
			case 'A': $where = "dispensing = 1"; break;
			case 'R': $where = "dispensing = 2"; break;
			default:  $where = "1";
		}
	}

	$query = "SELECT * FROM master_suhu WHERE $where ORDER BY suhu ASC, waktu ASC";
	$result = mysqli_query($con, $query);

	while ($row = mysqli_fetch_assoc($result)) {
		$info = '';
		if ($row['program'] == 1) $info = 'KONSTAN';
		elseif ($row['program'] == 2) $info = 'RAISING';
		else $info = '-';

		if ($row['dyeing'] == 1) $info .= ' - POLY';
		elseif ($row['dyeing'] == 2) $info .= ' - COTTON';

		if ($row['dispensing'] == 1) $info .= ' - POLY';
		elseif ($row['dispensing'] == 2) $info .= ' - COTTON';
		elseif ($row['dispensing'] == 3) $info .= ' - WHITE';

		echo '<option value="' . htmlspecialchars($row['code']) . '">' .
		     htmlspecialchars($row['product_name']) . ' (' . $info . ')</option>';
	}
}
?>
