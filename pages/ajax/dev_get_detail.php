<?php
ini_set('display_errors', 0);
error_reporting(0);
include __DIR__ . "/../../koneksi.php";
header('Content-Type: application/json');

// Detail AJAX untuk menu:
// - Matching Development (blok Development di Form-Matching)

$order  = isset($_POST['order']) ? strtoupper(trim($_POST['order'])) : '';
$demand = isset($_POST['demand']) ? trim($_POST['demand']) : '';

if ($order === '' || $demand === '') {
    echo json_encode([
        'success' => false,
        'message' => 'No Order atau No Production Demand kosong.',
    ]);
    exit;
}

try {
    // Mengikuti query lama: ITXVIEW_KK_TAS dengan PROJECTCODE LIKE dan filter NO_DEMAND
    $sql = "
        SELECT *
        FROM ITXVIEW_KK_TAS
        WHERE PROJECTCODE LIKE ?
          AND TRIM(NO_DEMAND) = ?
        FETCH FIRST 1 ROW ONLY
    ";

    $stmt = db2_prepare($conn1, $sql);
    if (!$stmt || !db2_execute($stmt, ['%' . $order . '%', $demand])) {
        throw new Exception('Gagal mengambil data Development.');
    }

    $row = db2_fetch_assoc($stmt);
    if (!$row) {
        echo json_encode([
            'success' => false,
            'message' => 'Data Development tidak ditemukan.',
        ]);
        exit;
    }

    // Mapping field seperti $dt_kk_tas lama
    $no_item1    = trim($row['NO_HANGER'] ?? '');
    $color_code  = trim($row['NO_WARNA'] ?? '');
    $kain        = trim($row['JENIS_KAIN'] ?? '');
    $warna       = trim($row['WARNA'] ?? '');
    $lebar       = trim($row['LEBAR'] ?? '');
    $gramasi     = trim($row['GRAMASI'] ?? '');
    $benang      = trim($row['JENIS_BENANG'] ?? '');
    $cocokWarna  = trim($row['STDCCKWARNA'] ?? '');
    $tglKirimRaw = $row['TGL_KIRIM'] ?? '';
    $qty         = trim($row['QTY'] ?? '');
    $buyer       = trim($row['BUYER'] ?? '');

    $tgl_delivery = '';
    if (!empty($tglKirimRaw)) {
        $dt = date_create($tglKirimRaw);
        if ($dt) {
            $tgl_delivery = date_format($dt, 'Y-m-d');
        }
    }

    echo json_encode([
        'success'      => true,
        'order'        => $order,
        'demand'       => $demand,
        'buyer'        => $buyer,
        'no_item1'     => $no_item1,
        'color_code'   => $color_code,
        'kain'         => $kain,
        'warna'        => $warna,
        'no_warna'     => '',           // form lama memang kosong
        'lebar'        => $lebar,
        'gramasi'      => $gramasi,
        'benang'       => $benang,
        'cocok_warna'  => $cocokWarna,
        'tgl_delivery' => $tgl_delivery,
        'qty'          => $qty,
    ], JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}
