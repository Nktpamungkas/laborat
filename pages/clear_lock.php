<?php
session_start();

include "../koneksi.php";

// --- cek konfirmasi
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    $sql = "TRUNCATE TABLE log_preliminary";
    if (mysqli_query($con, $sql)) {
        echo "<center><h3>✅ Active Lock sudah dihapus (tabel log_preliminary kosong kembali).</h3>
              <p><a href='index1.php?p=Preliminary-Schedule'>Kembali ke halaman Preliminary-Schedule</a></p></center>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
} else {
    echo "<center>
            <h3>⚠️ Apakah Anda yakin ingin menghapus semua data lock?</h3>
            <p><a href='clear_lock.php?confirm=yes' style='color:red;'>Ya, hapus semua (TRUNCATE)</a></p>
            <p><a href='index1.php?p=Preliminary-Schedule'>Tidak, kembali ke halaman</a></p>
          </center>";
}
?>
