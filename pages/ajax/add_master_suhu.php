<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../../koneksi.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['product_name']);
    $program = $_POST['program'];
    $dyeing = $_POST['dyeing'];
    $dispensing = $_POST['dispensing'];

    // Ambil suhu & durasi dari product_name
    preg_match("/(\d+)[^\d]+X[^\d]+(\d+)/", $product_name, $matches);
    $suhu = isset($matches[1]) ? $matches[1] : '';
    $durasi = isset($matches[2]) ? $matches[2] : '';

    if ($suhu == '' || $durasi == '') {
        echo "<div style='color:red'>Format Product Name salah. Contoh benar: 60°C X 30 MNT</div>";
    } else {
        if ($program == 'KONSTAN') {
            $prefix = "1";

            // Cari apakah sudah ada suhu dengan awalan ini
            $query = mysqli_query($con, "SELECT `group` FROM master_suhu WHERE program='1' AND product_name LIKE '$suhu%' LIMIT 1");
            if (mysqli_num_rows($query) > 0) {
                $row = mysqli_fetch_assoc($query);
                $group = $row['group'];
            } else {
                // Ambil max kode belakang, generate baru
                $last = mysqli_query($con, "SELECT MAX(SUBSTRING(`group`, 2, 2)) as max_suffix FROM master_suhu WHERE program='1'");
                $max_suffix = mysqli_fetch_assoc($last)['max_suffix'];
                $next_suffix = str_pad(((int)$max_suffix) + 1, 2, '0', STR_PAD_LEFT);
                $group = "1" . $next_suffix;
            }

            $code = $suhu . str_pad($durasi, 2, '0', STR_PAD_LEFT) . "1" . $dyeing . $dispensing;
        } elseif ($program == 'RAISING') {
            // Raising: group selalu naik satu angka
            $prefix = "2";
            $last = mysqli_query($con, "SELECT MAX(`group`) as max_group FROM master_suhu WHERE program='2'");
            $last_group = mysqli_fetch_assoc($last)['max_group'];
            $group = $last_group ? $last_group + 1 : 201;

            $code = $suhu . str_pad($durasi, 2, '0', STR_PAD_LEFT) . "2" . $dyeing . $dispensing;
        } else {
            echo "<div style='color:red'>Program tidak valid</div>";
            exit;
        }

        // Simpan ke database
        $stmt = mysqli_prepare($con, "INSERT INTO master_suhu (`group`, product_name, code, program, dyeing, dispensing, suhu, waktu) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssssssii', $group, $product_name, $code, $prefix, $dyeing, $dispensing, $suhu, $durasi);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambahkan!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data.']);
        }
    }
}
?>
