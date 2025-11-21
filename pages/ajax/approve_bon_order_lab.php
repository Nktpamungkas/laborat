<?php
header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ERROR | E_PARSE);

require_once '../../koneksi.php';

// --- Helper ---
function esc($con, $s) { return mysqli_real_escape_string($con, (string)$s); }

/** Ubah berbagai format tanggal ke SQL 'YYYY-mm-dd' atau NULL */
function to_date_sql($con, $s) {
    $s = trim((string)$s);
    if ($s === '') return "NULL";

    // bersihkan separator umum
    $s = str_replace(['T', '/'], [' ', '-'], $s);
    $ts = strtotime($s);
    if ($ts === false) return "NULL";
    return "'" . esc($con, date('Y-m-d', $ts)) . "'";
}

/** Ambil POST wajib, jika kosong -> error 400 */
function req($key) {
    if (!isset($_POST[$key])) {
        http_response_code(400);
        echo "Parameter '$key' wajib diisi.";
        exit;
    }
    return $_POST[$key];
}

// --- Ambil input utama ---
$code            = strtoupper(trim(req('code')));
$customer        = trim(req('customer'));                 // dari DOM
$tgl_approve_rmp = trim(req('tgl_approve_rmp'));          // string tanggal
$pic_lab         = trim(req('pic_lab'));
$status          = trim(req('status'));                   // 'Approved' / 'Rejected'
$is_revision     = intval($_POST['is_revision'] ?? 0);
$approvalrmpdatetime = trim(req('approvalrmpdatetime'));
if ($approvalrmpdatetime === '') {
    $approvalrmpdatetime = null;
}

// kolom-kolom revisi (header)
$revisic     = $_POST['revisic']     ?? '';
$revisi2     = $_POST['revisi2']     ?? '';
$revisi3     = $_POST['revisi3']     ?? '';
$revisi4     = $_POST['revisi4']     ?? '';
$revisi5     = $_POST['revisi5']     ?? '';
$revisin     = $_POST['revisin']     ?? '';
$drevisi2    = $_POST['drevisi2']    ?? '';
$drevisi3    = $_POST['drevisi3']    ?? '';
$drevisi4    = $_POST['drevisi4']    ?? '';
$drevisi5    = $_POST['drevisi5']    ?? '';
$revisi1date = $_POST['revisi1date'] ?? '';
$revisi2date = $_POST['revisi2date'] ?? '';
$revisi3date = $_POST['revisi3date'] ?? '';
$revisi4date = $_POST['revisi4date'] ?? '';
$revisi5date = $_POST['revisi5date'] ?? '';

// snapshot detail line (JSON dari endpoint JSON)
$lines_json_raw = $_POST['lines_json'] ?? '';
$lines = [];
if ($lines_json_raw !== '') {
    $tmp = json_decode($lines_json_raw, true);
    if (is_array($tmp)) $lines = $tmp;
}

// Validasi status
if (!in_array($status, ['Approved', 'Rejected'], true)) {
    http_response_code(400);
    echo "Status tidak valid.";
    exit;
}

// Siapkan nilai tanggal lab
$tgl_approve_lab_sql  = ($status === 'Approved') ? "NOW()" : "NULL";
$tgl_rejected_lab_sql = ($status === 'Rejected') ? "NOW()" : "NULL";

// Siapkan nilai tanggal RMP
$tgl_approve_rmp_sql = to_date_sql($con, $tgl_approve_rmp);

// Mulai transaksi
mysqli_begin_transaction($con);

try {
    // Insert ke approval_bon_order (HEADER tetap sama)
    $sql = "
        INSERT INTO approval_bon_order
            (code, customer, tgl_approve_rmp, tgl_approve_lab, tgl_rejected_lab, pic_lab, status, is_revision,
             revisic, revisi2, revisi3, revisi4, revisi5,
             revisin, drevisi2, drevisi3, drevisi4, drevisi5,
             revisi1date, revisi2date, revisi3date, revisi4date, revisi5date, approvalrmpdatetime)
        VALUES
            (
                '" . esc($con, $code) . "',
                '" . esc($con, $customer) . "',
                {$tgl_approve_rmp_sql},
                {$tgl_approve_lab_sql},
                {$tgl_rejected_lab_sql},
                '" . esc($con, $pic_lab) . "',
                '" . esc($con, $status) . "',
                {$is_revision},

                '" . esc($con, $revisic)  . "',
                '" . esc($con, $revisi2)  . "',
                '" . esc($con, $revisi3)  . "',
                '" . esc($con, $revisi4)  . "',
                '" . esc($con, $revisi5)  . "',
                '" . esc($con, $revisin)  . "',
                '" . esc($con, $drevisi2) . "',
                '" . esc($con, $drevisi3) . "',
                '" . esc($con, $drevisi4) . "',
                '" . esc($con, $drevisi5) . "',

                " . to_date_sql($con, $revisi1date) . ",
                " . to_date_sql($con, $revisi2date) . ",
                " . to_date_sql($con, $revisi3date) . ",
                " . to_date_sql($con, $revisi4date) . ",
                " . to_date_sql($con, $revisi5date) . ",
                " . ($approvalrmpdatetime === null 
                        ? "NULL" 
                        : "'" . esc($con, $approvalrmpdatetime) . "'"
                ) . "
            )
    ";

    if (!mysqli_query($con, $sql)) {
        throw new Exception("Gagal simpan header: " . mysqli_error($con));
    }

    $approval_id = mysqli_insert_id($con);

    // Jika ada data line -> insert batch ke line_revision
    if (!empty($lines)) {
        $values = [];
        foreach ($lines as $ln) {
            // ====== AMBIL NILAI DENGAN NAMA BARU (sesuai DB2) + fallback ke nama lama ======
            $orderline  = esc($con, $ln['orderline'] ?? '');

            // C-group: revisic (utama) + revisic1..revisic4
            $lv_revisic  = esc($con, $ln['revisic']  ?? '');                            // C (utama)
            $lv_revc1    = esc($con, $ln['revisic1'] ?? ($ln['revisi2'] ?? ''));        // fallback lama
            $lv_revc2    = esc($con, $ln['revisic2'] ?? ($ln['revisi3'] ?? ''));
            $lv_revc3    = esc($con, $ln['revisic3'] ?? ($ln['revisi4'] ?? ''));
            $lv_revc4    = esc($con, $ln['revisic4'] ?? ($ln['revisi5'] ?? ''));

            // D-group: revisid (utama) + revisi2..revisi5
            $lv_revid    = esc($con, $ln['revisid']  ?? ($ln['revisin']  ?? ''));       // fallback lama: revisin
            $lv_revid1   = esc($con, $ln['revisi2'] ?? ($ln['drevisi2'] ?? ''));
            $lv_revid2   = esc($con, $ln['revisi3'] ?? ($ln['drevisi3'] ?? ''));
            $lv_revid3   = esc($con, $ln['revisi4'] ?? ($ln['drevisi4'] ?? ''));
            $lv_revid4   = esc($con, $ln['revisi5'] ?? ($ln['drevisi5'] ?? ''));

            // Dates (tetap sama namanya)
            $d1 = to_date_sql($con, $ln['revisi1date'] ?? '');
            $d2 = to_date_sql($con, $ln['revisi2date'] ?? '');
            $d3 = to_date_sql($con, $ln['revisi3date'] ?? '');
            $d4 = to_date_sql($con, $ln['revisi4date'] ?? '');
            $d5 = to_date_sql($con, $ln['revisi5date'] ?? '');

            $values[] = "(" .
                intval($approval_id) . ", " .
                "'" . esc($con, $code) . "', " .
                "'" . $orderline . "', " .
                // kolom BARU sesuai DB2:
                "'" . $lv_revisic . "', " .   // revisic (C utama)
                "'" . $lv_revc1   . "', " .   // revisic1
                "'" . $lv_revc2   . "', " .   // revisic2
                "'" . $lv_revc3   . "', " .   // revisic3
                "'" . $lv_revc4   . "', " .   // revisic4
                "'" . $lv_revid   . "', " .   // revisid (D utama)
                "'" . $lv_revid1  . "', " .   // revisi2
                "'" . $lv_revid2  . "', " .   // revisi3
                "'" . $lv_revid3  . "', " .   // revisi4
                "'" . $lv_revid4  . "', " .   // revisi5
                "{$d1}, {$d2}, {$d3}, {$d4}, {$d5}" .
            ")";
        }

        if (!empty($values)) {
            $sqlLines = "
                INSERT INTO line_revision
                    (approval_id, code, orderline,
                     revisic, revisic1, revisic2, revisic3, revisic4,
                     revisid, revisi2, revisi3, revisi4, revisi5,
                     revisi1date, revisi2date, revisi3date, revisi4date, revisi5date)
                VALUES " . implode(",\n", $values);

            if (!mysqli_query($con, $sqlLines)) {
                throw new Exception("Gagal simpan detail line: " . mysqli_error($con));
            }
        }
    }

    mysqli_commit($con);

    // Respon sukses
    // echo "Data approval berhasil disimpan" . (!empty($lines) ? " (termasuk " . count($lines) . " baris line)." : ".");
    echo "Data approval berhasil disimpan";

} catch (Exception $e) {
    mysqli_rollback($con);
    http_response_code(500);
    echo $e->getMessage();
}
