<?php
// function insertCycleLog($con, $no_resep, $stage, $status, $keterangan = '') {
//     $maxCycle = 0;
//     $lastStatus = null;
//     // Dapatkan cycle terakhir dari no_resep
//     $stmt = $con->prepare("SELECT MAX(cycle) FROM tbl_cycle_log WHERE no_resep = ?");
//     $stmt->bind_param("s", $no_resep);
//     $stmt->execute();
//     $stmt->bind_result($maxCycle);
//     $stmt->fetch();
//     $stmt->close();

//     $currentCycle = $maxCycle ?: 1;
//     // Cek status terakhir di cycle aktif
//     $stmt = $con->prepare("SELECT status FROM tbl_cycle_log 
//                            WHERE no_resep = ? AND cycle = ? 
//                            ORDER BY id DESC LIMIT 1");
//     $stmt->bind_param("si", $no_resep, $currentCycle);
//     $stmt->execute();
//     $stmt->bind_result($lastStatus);
//     $stmt->fetch();
//     $stmt->close();

//     if ($lastStatus === 'repeat') {
//         $currentCycle++;
//     }

//     // Insert log ke cycle yang sesuai
//     $stmt = $con->prepare("INSERT INTO tbl_cycle_log 
//         (no_resep, stage, status, waktu, keterangan, cycle) 
//         VALUES (?, ?, ?, NOW(), ?, ?)");
    
//     if ($stmt) {
//         $stmt->bind_param("sissi", $no_resep, $stage, $status, $keterangan, $currentCycle);
//         return $stmt->execute();
//     } else {
//         return false;
//     }
// }


function logResepHistory($no_resep, $stage, $status, $remarks = '', $qty = null) {
    global $con;
    $stmt = $con->prepare("INSERT INTO tbl_ct_history (no_resep, qty, stage, status, remarks) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("siiss", $no_resep, $qty, $stage, $status, $remarks);
        $stmt->execute();
        $stmt->close();
    } else {
        error_log("Prepare failed: " . $con->error);
    }
}

?>
