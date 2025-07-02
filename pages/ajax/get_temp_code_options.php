<?php
include '../../koneksi.php';

$rcode = $_GET['rcode'] ?? null;

// Default: tampilkan semua jika tidak ada rcode
$where = "1";

if ($rcode) {
    // Ambil awalan (max 2 huruf pertama)
    $prefix = strtoupper(substr($rcode, 0, 2));

    // Jika huruf pertama dan kedua tidak termasuk daftar, coba ambil hanya 1 huruf
    $prefixFallback = strtoupper(substr($rcode, 0, 1));

    switch ($prefix) {
        case 'DR':
            $where = "dispensing IN (1,2,3)";
            break;
        case 'CD':
            $where = "dispensing = 1";
            break;
        case 'OB':
            $where = "dispensing = 3";
            break;
        default:
            // Fallback 1 huruf
            switch ($prefixFallback) {
                case 'D':
                case 'A':
                    $where = "dispensing = 1";
                    break;
                case 'R':
                    $where = "dispensing = 2";
                    break;
                default:
                    $where = "1"; // no filtering
            }
    }
}

$query = "SELECT code, product_name, program, dyeing, dispensing 
          FROM master_suhu 
          WHERE $where 
          ORDER BY suhu ASC, waktu ASC";

$result = mysqli_query($con, $query);

$options = [];
while ($row = mysqli_fetch_assoc($result)) {
    $options[] = [
        'code' => $row['code'],
        'label' => $row['product_name'],
        'program' => $row['program'],
        'dyeing' => $row['dyeing'],
        'dispensing' => $row['dispensing']
    ];
}

header('Content-Type: application/json');
echo json_encode($options);