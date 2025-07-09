<?php
session_start();
include '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['no_resep'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Metode atau parameter tidak valid."]);
    exit;
}

$no_resep = $_POST['no_resep'];
$dispensing_code = $_POST['dispensing_code'] ?? '';

try {
    // Ambil data sesuai kode dispensing, dengan join master_suhu
    $query = "
        SELECT ps.id, ps.no_resep, ps.order_index, ps.status
        FROM tbl_preliminary_schedule ps
        LEFT JOIN master_suhu ms ON ps.code = ms.code
        WHERE (
                (? = '' AND (COALESCE(ms.dispensing, '') NOT IN ('1', '2')))
                OR ms.dispensing = ?
              )
        AND ps.status NOT IN ('ready')
        ORDER BY ps.order_index ASC
    ";

    $stmt = $con->prepare($query);
    if (!$stmt) throw new Exception("Prepare failed: " . $con->error);
    $stmt->bind_param("ss", $dispensing_code, $dispensing_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (empty($rows)) {
        http_response_code(404);
        echo json_encode(["success" => false, "error" => "Tidak ada data ditemukan untuk kode dispensing."]);
        exit;
    }

    // Bagi ke blok-blok isi 16 baris
    $rowsPerBlock = 16;
    $blocks = array_chunk($rows, $rowsPerBlock);

    // Cari blok pertama yang masih ada status 'scheduled'
    $firstScheduledBlockIndex = null;
    foreach ($blocks as $index => $block) {
        foreach ($block as $row) {
            if ($row['status'] === 'scheduled') {
                $firstScheduledBlockIndex = $index;
                break 2; // keluar dua loop sekaligus
            }
        }
    }

    // Kalau ada blok yang harus diselesaikan dulu
    if ($firstScheduledBlockIndex !== null) {
        $allowedBlock = $blocks[$firstScheduledBlockIndex];
        $allowedNoResepList = array_column($allowedBlock, 'no_resep');

        // Kalau no_resep yang discan tidak ada di blok ini → tolak
        if (!in_array($no_resep, $allowedNoResepList)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "error" => "Silakan selesaikan scan di blok sebelumnya terlebih dahulu (blok #" . ($firstScheduledBlockIndex + 1) . ")."
            ]);
            exit;
        }
    }
    // Jika tidak ada blok scheduled, artinya semua selesai → boleh proses bebas

    // Cari blok yang mengandung no_resep dengan status 'scheduled' lalu update
    foreach ($blocks as $blockIndex => $blockRows) {
        $adaNoResep = false;
        $adaScheduled = false;

        foreach ($blockRows as $row) {
            if ($row['no_resep'] === $no_resep) {
                $adaNoResep = true;
                if ($row['status'] === 'scheduled') {
                    $adaScheduled = true;
                }
            }
        }

        if ($adaNoResep && $adaScheduled) {
            $filteredRows = array_filter($blockRows, fn($r) => $r['no_resep'] === $no_resep && $r['status'] === 'scheduled');
            $ids = array_column($filteredRows, 'id');

            updateRows($con, $ids);

            echo json_encode([
                "success" => true,
                "updated_ids" => $ids,
                "block_index" => $blockIndex,
                "updated_count" => count($ids)
            ]);
            exit;
        }
    }

    // Kalau sampai sini, berarti semua blok untuk no_resep tsb sudah diproses
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Semua blok untuk No. Resep ini sudah diproses."]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
} finally {
    $con->close();
}

// Fungsi update status baris berdasarkan ID
function updateRows($con, array $ids): void {
    if (empty($ids)) return;

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    $sql = "
        UPDATE tbl_preliminary_schedule 
        SET status = 'in_progress_dispensing',
            dispensing_start = NOW()
        WHERE id IN ($placeholders)
    ";

    $stmt = $con->prepare($sql);
    if (!$stmt) throw new Exception("Prepare update failed: " . $con->error);

    $stmt->bind_param($types, ...$ids);
    if (!$stmt->execute()) {
        throw new Exception("Execute update failed: " . $stmt->error);
    }

    $stmt->close();
}
