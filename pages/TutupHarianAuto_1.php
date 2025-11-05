<?php
include "../koneksi.php";
ini_set("error_reporting", 1);
$Awal = date("Y-m-d");

// --- CATAT WAKTU MULAI LOG ---
$start_time = date("Y-m-d H:i:s");
mysqli_query($con, "
    INSERT INTO Logs_tbopname_11 (start_query, status)
    VALUES ('$start_time', 'RUNNING')
");
$log_id = mysqli_insert_id($con);

// --- CEK APAKAH SUDAH TUTUP SEBELUMNYA ---
$cektgl = mysqli_query($con, "SELECT 
        DATE_FORMAT(NOW(),'%Y-%m-%d') AS tgl,
        COUNT(tgl_tutup) AS ck,
        DATE_FORMAT(NOW(),'%H') AS jam,
        DATE_FORMAT(NOW(),'%H:%i') AS jam1
    FROM tblopname_11a
    WHERE tgl_tutup = '$Awal' 
    LIMIT 1
");
$dcek = mysqli_fetch_array($cektgl);

$t1 = strtotime($Awal);
$t2 = strtotime($dcek['tgl']);
$selh = round(abs($t2 - $t1) / (60 * 60 * 45));

if ($dcek['ck'] > 0) {
	echo "<script>alert('Stok Tgl $Awal ini sudah pernah ditutup');</script>";
	$status = "GAGAL - SUDAH DITUTUP";
} elseif ($dcek['jam'] < 23 && $dcek['jam'] > 0) { 

	echo "<script>alert('Tidak bisa tutup sebelum jam 11 malam. Sekarang masih jam {$dcek['jam1']}');</script>";
	$status = "GAGAL - BELUM WAKTU";
} else {
	// --- EKSEKUSI INSERT DATA ---
	$sqlDB21 = "
        SELECT 
            b.ITEMTYPECODE,
            b.LOGICALWAREHOUSECODE,
            b.DECOSUBCODE01,
            b.DECOSUBCODE02,
            b.DECOSUBCODE03,
            b.DECOSUBCODE04,
            b.DECOSUBCODE05,
            b.DECOSUBCODE06,
            b.DECOSUBCODE07,
            b.DECOSUBCODE08,
            b.DECOSUBCODE09,
            b.DECOSUBCODE10, 
            b.WAREHOUSELOCATIONCODE,
            b.WHSLOCATIONWAREHOUSEZONECODE,
            b.LOTCODE,
            TRIM(b.DECOSUBCODE01) || '-' || TRIM(b.DECOSUBCODE02) || '-' || TRIM(b.DECOSUBCODE03) AS KODE_OBAT,
            b.BASEPRIMARYUNITCODE,
            b.BASEPRIMARYQUANTITYUNIT,
            p.LONGDESCRIPTION
        FROM BALANCE b
        LEFT JOIN PRODUCT p ON 
            p.ITEMTYPECODE = b.ITEMTYPECODE 
            AND p.SUBCODE01 = b.DECOSUBCODE01
            AND p.SUBCODE02 = b.DECOSUBCODE02 
            AND p.SUBCODE03 = b.DECOSUBCODE03 
            AND p.SUBCODE04 = b.DECOSUBCODE04 
            AND p.SUBCODE05 = b.DECOSUBCODE05 
            AND p.SUBCODE06 = b.DECOSUBCODE06 
            AND p.SUBCODE07 = b.DECOSUBCODE07 
            AND p.SUBCODE08 = b.DECOSUBCODE08 
            AND p.SUBCODE09 = b.DECOSUBCODE09 
            AND p.SUBCODE10 = b.DECOSUBCODE10 
        WHERE 
            b.ITEMTYPECODE = 'DYC'
            AND b.DETAILTYPE = 1
            AND b.LOGICALWAREHOUSECODE IN ('M510','M101')
    ";

	$stmt1 = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));

	$insert_count = 0;
	$error_flag = false;

	while ($rowdb21 = db2_fetch_assoc($stmt1)) {
		$sqlInsert = "
            INSERT INTO tblopname_11a SET 
                ITEMTYPECODE = '" . $rowdb21['ITEMTYPECODE'] . "',
                LOGICALWAREHOUSECODE = '" . $rowdb21['LOGICALWAREHOUSECODE'] . "',
                DECOSUBCODE01 = '" . $rowdb21['DECOSUBCODE01'] . "',
                DECOSUBCODE02 = '" . $rowdb21['DECOSUBCODE02'] . "',
                DECOSUBCODE03 = '" . $rowdb21['DECOSUBCODE03'] . "',
                DECOSUBCODE04 = '" . $rowdb21['DECOSUBCODE04'] . "',
                DECOSUBCODE05 = '" . $rowdb21['DECOSUBCODE05'] . "',
                DECOSUBCODE06 = '" . $rowdb21['DECOSUBCODE06'] . "',
                DECOSUBCODE07 = '" . $rowdb21['DECOSUBCODE07'] . "',
                DECOSUBCODE08 = '" . $rowdb21['DECOSUBCODE08'] . "',
                DECOSUBCODE09 = '" . $rowdb21['DECOSUBCODE09'] . "',
                DECOSUBCODE10 = '" . $rowdb21['DECOSUBCODE10'] . "',
                WAREHOUSELOCATIONCODE = '" . $rowdb21['WAREHOUSELOCATIONCODE'] . "',
                WHSLOCATIONWAREHOUSEZONECODE = '" . $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'] . "',
                LOTCODE = '" . $rowdb21['LOTCODE'] . "',
                KODE_OBAT = '" . $rowdb21['KODE_OBAT'] . "',
                LONGDESCRIPTION = '" . mysqli_real_escape_string($con, $rowdb21['LONGDESCRIPTION']) . "',
                BASEPRIMARYUNITCODE = '" . $rowdb21['BASEPRIMARYUNITCODE'] . "',
                BASEPRIMARYQUANTITYUNIT = '" . $rowdb21['BASEPRIMARYQUANTITYUNIT'] . "',
                tgl_tutup = '$Awal',
                tgl_buat = NOW(),
                note = 'cek'
        ";

		$simpan = mysqli_query($con, $sqlInsert);
		if ($simpan) {
			$insert_count++;
		} else {
			$error_flag = true;
			break;
		}
	}

	if (!$error_flag && $insert_count > 0) {
		echo "<script>
            alert('Stok Tgl $Awal sudah ditutup ($insert_count data tersimpan)');
            window.open('', '_self').close();
        </script>";
		$status = "SUKSES ($insert_count DATA)";
	} else {
		echo "<script>alert('Terjadi kesalahan saat insert data.');</script>";
		$status = "GAGAL INSERT DATA";
	}
}

// --- CATAT WAKTU SELESAI LOG ---
$end_time = date("Y-m-d H:i:s");
mysqli_query($con, "
    UPDATE Logs_tbopname_11 
    SET end_start_query = '$end_time', status = '$status' 
    WHERE id = '$log_id'
");
?>