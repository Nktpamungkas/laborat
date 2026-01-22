<?php
session_start();
require_once "koneksi.php";
require_once __DIR__ . "/../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

function h($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

function normalizeHeader($x)
{
    $x = trim((string) $x);
    $x = str_replace(["\n", "\r", "\t"], " ", $x);
    $x = preg_replace('/\s+/', ' ', $x);
    return strtoupper($x);
}

/**
 * Format date from Excel:
 * - numeric excel date serial -> converted
 * - string -> parsed by strtotime
 * Result:
 * - $withTime=false => Y-m-d
 * - $withTime=true  => Y-m-d H:i:s
 */
function formatExcelDate($value, $withTime = false)
{
    if ($value === null)
        return '';

    // Excel serial date number
    if (is_numeric($value) && $value !== '') {
        try {
            $dt = ExcelDate::excelToDateTimeObject($value);
            return $dt->format($withTime ? 'Y-m-d H:i:s' : 'Y-m-d');
        } catch (Exception $e) {
            return (string) $value;
        }
    }

    // string date
    $s = trim((string) $value);
    if ($s === '')
        return '';

    $ts = strtotime($s);
    if ($ts !== false) {
        return date($withTime ? 'Y-m-d H:i:s' : 'Y-m-d', $ts);
    }

    return $s; // fallback
}

/**
 * Stable dynamic bind_param for mysqli (PHP 7.x).
 * $params is an indexed array of values (without types).
 */
function bindParams($stmt, $types, $params)
{
    $bind_names = [];
    $bind_names[] = $types;
    for ($i = 0; $i < count($params); $i++) {
        $bind_names[] = &$params[$i];
    }
    return call_user_func_array([$stmt, 'bind_param'], $bind_names);
}

$requiredHeaders = [
    'ITEMTYPECODE',
    'LOGICALWAREHOUSECODE',
    'DECOSUBCODE01',
    'DECOSUBCODE02',
    'DECOSUBCODE03',
    'DECOSUBCODE04',
    'DECOSUBCODE05',
    'DECOSUBCODE06',
    'DECOSUBCODE07',
    'DECOSUBCODE08',
    'DECOSUBCODE09',
    'DECOSUBCODE10',
    'WAREHOUSELOCATIONCODE',
    'WHSLOCATIONWAREHOUSEZONECODE',
    'LOTCODE',
    'KODE_OBAT',
    'LONGDESCRIPTION',
    'BASEPRIMARYUNITCODE',
    'BASEPRIMARYQUANTITYUNIT',
    'TGL_TUTUP',
    'TGL_BUAT'
];

$msg = $_SESSION['flash_msg'] ?? '';
unset($_SESSION['flash_msg']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CLEAR preview
    if (isset($_POST['clear'])) {
        unset($_SESSION['opname_preview']);
        $_SESSION['flash_msg'] = "Preview dibersihkan.";
        header("Location: upload_opname.php");
        exit;
    }

    // UPLOAD + READ EXCEL
    if (isset($_POST['upload'])) {
        if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_msg'] = "Upload gagal. Coba ulang.";
            header("Location: upload_opname.php");
            exit;
        }

        $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xlsx', 'xls'])) {
            $_SESSION['flash_msg'] = "File harus .xlsx atau .xls";
            header("Location: upload_opname.php");
            exit;
        }

        if (!is_dir(__DIR__ . "/uploads")) {
            @mkdir(__DIR__ . "/uploads", 0777, true);
        }

        $tmp = $_FILES['file_excel']['tmp_name'];
        $target = __DIR__ . "/uploads/opname_" . date('Ymd_His') . "_" . rand(100, 999) . "." . $ext;

        if (!move_uploaded_file($tmp, $target)) {
            $_SESSION['flash_msg'] = "Gagal menyimpan file upload.";
            header("Location: upload_opname.php");
            exit;
        }

        try {
            $spreadsheet = IOFactory::load($target);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            if (count($rows) < 2) {
                $_SESSION['flash_msg'] = "Excel kosong / tidak ada data.";
                header("Location: upload_opname.php");
                exit;
            }

            // Read header row (row 1)
            $headerRow = $rows[1];
            $map = [];
            foreach ($headerRow as $col => $name) {
                $hn = normalizeHeader($name);
                if ($hn !== '')
                    $map[$hn] = $col;
            }

            // validate required headers exist
            $missing = [];
            foreach ($requiredHeaders as $rh) {
                if (!isset($map[$rh]))
                    $missing[] = $rh;
            }
            if ($missing) {
                $_SESSION['flash_msg'] = "Header kolom Excel kurang: " . implode(", ", $missing);
                header("Location: upload_opname.php");
                exit;
            }

            $data = [];
            $rowCount = count($rows);

            for ($i = 2; $i <= $rowCount; $i++) {
                if (!isset($rows[$i]))
                    continue;
                $r = $rows[$i];

                // skip blank rows
                $checkA = trim((string) ($r[$map['ITEMTYPECODE']] ?? ''));
                $checkB = trim((string) ($r[$map['DECOSUBCODE01']] ?? ''));
                if ($checkA === '' && $checkB === '')
                    continue;

                $row = [];
                foreach ($requiredHeaders as $field) {
                    $col = $map[$field];
                    $val = $r[$col] ?? '';

                    if ($field === 'TGL_TUTUP')
                        $val = formatExcelDate($val, false);
                    if ($field === 'TGL_BUAT')
                        $val = formatExcelDate($val, true);

                    $row[$field] = is_string($val) ? trim($val) : $val;
                }

                $data[] = $row;
            }

            if (!$data) {
                $_SESSION['flash_msg'] = "Tidak ada data valid (baris kosong semua).";
                header("Location: upload_opname.php");
                exit;
            }

            $_SESSION['opname_preview'] = [
                'file' => basename($target),
                'rows' => $data
            ];

            $_SESSION['flash_msg'] = "Upload sukses. Data berhasil dibaca: " . count($data) . " baris.";
            header("Location: upload_opname.php");
            exit;

        } catch (Exception $e) {
            $_SESSION['flash_msg'] = "Gagal baca Excel: " . $e->getMessage();
            header("Location: upload_opname.php");
            exit;
        }
    }

    // SAVE to DB
    if (isset($_POST['save'])) {
        if (empty($_SESSION['opname_preview']['rows'])) {
            $_SESSION['flash_msg'] = "Tidak ada data preview untuk disimpan.";
            header("Location: upload_opname.php");
            exit;
        }

        $rows = $_SESSION['opname_preview']['rows'];

        mysqli_begin_transaction($con);

        try {
            // DB column list (note: last two are lowercase in DB)
            $cols = [
                'ITEMTYPECODE',
                'LOGICALWAREHOUSECODE',
                'DECOSUBCODE01',
                'DECOSUBCODE02',
                'DECOSUBCODE03',
                'DECOSUBCODE04',
                'DECOSUBCODE05',
                'DECOSUBCODE06',
                'DECOSUBCODE07',
                'DECOSUBCODE08',
                'DECOSUBCODE09',
                'DECOSUBCODE10',
                'WAREHOUSELOCATIONCODE',
                'WHSLOCATIONWAREHOUSEZONECODE',
                'LOTCODE',
                'KODE_OBAT',
                'LONGDESCRIPTION',
                'BASEPRIMARYUNITCODE',
                'BASEPRIMARYQUANTITYUNIT',
                'tgl_tutup',
                'tgl_buat'
            ];

            $placeholders = implode(',', array_fill(0, count($cols), '?'));
            $sql = "INSERT INTO tblopname_11 (" . implode(',', $cols) . ") VALUES ($placeholders)";

            $stmt = mysqli_prepare($con, $sql);
            if (!$stmt) {
                throw new Exception("Prepare gagal: " . mysqli_error($con));
            }

            $types = str_repeat('s', count($cols)); // treat all as string

            foreach ($rows as $r) {
                $values = [
                    $r['ITEMTYPECODE'],
                    $r['LOGICALWAREHOUSECODE'],
                    $r['DECOSUBCODE01'],
                    $r['DECOSUBCODE02'],
                    $r['DECOSUBCODE03'],
                    $r['DECOSUBCODE04'],
                    $r['DECOSUBCODE05'],
                    $r['DECOSUBCODE06'],
                    $r['DECOSUBCODE07'],
                    $r['DECOSUBCODE08'],
                    $r['DECOSUBCODE09'],
                    $r['DECOSUBCODE10'],
                    $r['WAREHOUSELOCATIONCODE'],
                    $r['WHSLOCATIONWAREHOUSEZONECODE'],
                    $r['LOTCODE'],
                    $r['KODE_OBAT'],
                    $r['LONGDESCRIPTION'],
                    $r['BASEPRIMARYUNITCODE'],
                    $r['BASEPRIMARYQUANTITYUNIT'],
                    $r['TGL_TUTUP'], // already Y-m-d
                    $r['TGL_BUAT']   // already Y-m-d H:i:s
                ];

                if (!bindParams($stmt, $types, $values)) {
                    throw new Exception("Bind gagal: " . mysqli_stmt_error($stmt));
                }

                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Execute gagal: " . mysqli_stmt_error($stmt));
                }
            }

            mysqli_stmt_close($stmt);
            mysqli_commit($con);

            unset($_SESSION['opname_preview']);
            $_SESSION['flash_msg'] = "Simpan sukses. Data masuk: " . count($rows) . " baris. Preview dikosongkan.";
            header("Location: upload_opname.php");
            exit;

        } catch (Exception $e) {
            mysqli_rollback($con);
            $_SESSION['flash_msg'] = "Simpan gagal: " . $e->getMessage();
            header("Location: upload_opname.php");
            exit;
        }
    }
}

$preview = $_SESSION['opname_preview'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Upload Opname Excel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f7fb;
            margin: 0;
            padding: 20px;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 14px;
        }

        .row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: end;
        }

        label {
            font-size: 12px;
            color: #374151;
            display: block;
            margin-bottom: 6px;
        }

        input[type="file"] {
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #fff;
        }

        button {
            padding: 9px 14px;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn {
            background: #2563eb;
            color: white;
        }

        .btn2 {
            background: #10b981;
            color: white;
        }

        .btn3 {
            background: #ef4444;
            color: white;
        }

        .msg {
            padding: 10px 12px;
            border-radius: 8px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            white-space: nowrap;
        }

        th {
            background: #f3f4f6;
            position: sticky;
            top: 0;
        }

        .table-wrap {
            max-height: 420px;
            overflow: auto;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
        }

        .muted {
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <?php if ($msg): ?>
        <div class="msg card"><?= h($msg) ?></div>
    <?php endif; ?>

    <div class="card">
        <h3 style="margin:0 0 10px 0;">Upload Excel Opname</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div>
                    <label>File Excel (.xlsx / .xls)</label>
                    <input type="file" name="file_excel" accept=".xlsx,.xls" required>
                </div>
                <div>
                    <button class="btn" type="submit" name="upload">Upload & Preview</button>
                </div>
            </div>
            <div class="muted" style="margin-top:10px;">
                * Pastikan header Excel memuat TGL_TUTUP dan TGL_BUAT.
            </div>
        </form>
    </div>

    <div class="card">
        <h3 style="margin:0 0 10px 0;">Preview Data</h3>

        <?php if (!$preview): ?>
            <div class="muted">Belum ada data. Silakan upload file Excel.</div>
        <?php else: ?>
            <div class="muted" style="margin-bottom:10px;">
                File: <b><?= h($preview['file']) ?></b> |
                Total baris: <b><?= count($preview['rows']) ?></b>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <?php foreach ($requiredHeaders as $hcol): ?>
                                <th><?= h($hcol) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($preview['rows'] as $r): ?>
                            <tr>
                                <?php foreach ($requiredHeaders as $hcol): ?>
                                    <td><?= h($r[$hcol] ?? '') ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <form method="post" style="margin-top:12px; display:flex; gap:10px;">
                <button class="btn2" type="submit" name="save" onclick="return confirm('Simpan semua data ke database?')">
                    Simpan ke Database
                </button>
                <button class="btn3" type="submit" name="clear" onclick="return confirm('Kosongkan preview?')">
                    Kosongkan Preview
                </button>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>