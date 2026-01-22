<?php
session_start();
require_once "koneksi.php";
require_once __DIR__ . "/../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

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
    'BASEPRIMARYQUANTITYUNIT'
];

$msg = $_SESSION['flash_msg'] ?? '';
unset($_SESSION['flash_msg']);

// ====== HANDLE ACTIONS ======
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
        $tgl_tutup = $_POST['tgl_tutup'] ?? '';
        if ($tgl_tutup === '') {
            $_SESSION['flash_msg'] = "Tanggal tutup wajib diisi.";
            header("Location: upload_opname.php");
            exit;
        }

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
            $rows = $sheet->toArray(null, true, true, true); // keyed by column letters

            if (count($rows) < 2) {
                $_SESSION['flash_msg'] = "Excel kosong / tidak ada data.";
                header("Location: upload_opname.php");
                exit;
            }

            // header row = baris 1
            $headerRow = $rows[1];
            $map = []; // fieldName => columnLetter
            foreach ($headerRow as $col => $name) {
                $hn = normalizeHeader($name);
                if ($hn !== '')
                    $map[$hn] = $col;
            }

            // cek header wajib
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
            for ($i = 2; $i <= count($rows); $i++) {
                $r = $rows[$i];

                // ambil minimal 1 kolom untuk deteksi baris kosong
                $check = trim((string) ($r[$map['ITEMTYPECODE']] ?? ''));
                $check2 = trim((string) ($r[$map['DECOSUBCODE01']] ?? ''));
                if ($check === '' && $check2 === '')
                    continue; // skip baris kosong

                $row = [];
                foreach ($requiredHeaders as $field) {
                    $col = $map[$field];
                    $row[$field] = isset($r[$col]) ? trim((string) $r[$col]) : '';
                }
                $data[] = $row;
            }

            if (!$data) {
                $_SESSION['flash_msg'] = "Tidak ada data valid (baris kosong semua).";
                header("Location: upload_opname.php");
                exit;
            }

            $_SESSION['opname_preview'] = [
                'tgl_tutup' => $tgl_tutup,
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

        $tgl_tutup = $_SESSION['opname_preview']['tgl_tutup'];
        $rows = $_SESSION['opname_preview']['rows'];

        mysqli_begin_transaction($con);

        try {
            $sql = "INSERT INTO tblopname_11a
      (ITEMTYPECODE, LOGICALWAREHOUSECODE,
       DECOSUBCODE01, DECOSUBCODE02, DECOSUBCODE03, DECOSUBCODE04, DECOSUBCODE05,
       DECOSUBCODE06, DECOSUBCODE07, DECOSUBCODE08, DECOSUBCODE09, DECOSUBCODE10,
       WAREHOUSELOCATIONCODE, WHSLOCATIONWAREHOUSEZONECODE,
       LOTCODE, KODE_OBAT, LONGDESCRIPTION,
       BASEPRIMARYUNITCODE, BASEPRIMARYQUANTITYUNIT,
       tgl_tutup, tgl_buat)
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, NOW())";

            $stmt = mysqli_prepare($con, $sql);
            if (!$stmt) {
                throw new Exception("Prepare gagal: " . mysqli_error($con));
            }

            foreach ($rows as $r) {
                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssssssssssssssssss",
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
                    $tgl_tutup
                );

                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Execute gagal: " . mysqli_stmt_error($stmt));
                }
            }

            mysqli_stmt_close($stmt);
            mysqli_commit($con);

            // kosongkan preview setelah sukses simpan
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
    body{font-family:Arial, sans-serif; background:#f6f7fb; margin:0; padding:20px;}
    .card{background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:16px; margin-bottom:14px;}
    .row{display:flex; gap:12px; flex-wrap:wrap; align-items:end;}
    label{font-size:12px; color:#374151; display:block; margin-bottom:6px;}
    input[type="date"], input[type="file"]{padding:8px; border:1px solid #d1d5db; border-radius:8px; background:#fff;}
    button{padding:9px 14px; border:0; border-radius:8px; cursor:pointer;}
    .btn{background:#2563eb; color:white;}
    .btn2{background:#10b981; color:white;}
    .btn3{background:#ef4444; color:white;}
    .msg{padding:10px 12px; border-radius:8px; background:#fff7ed; border:1px solid #fed7aa; color:#9a3412;}
    table{border-collapse:collapse; width:100%; font-size:12px;}
    th,td{border:1px solid #e5e7eb; padding:6px 8px; white-space:nowrap;}
    th{background:#f3f4f6; position:sticky; top:0;}
    .table-wrap{max-height:420px; overflow:auto; border:1px solid #e5e7eb; border-radius:10px;}
    .muted{color:#6b7280; font-size:12px;}
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
        <label>Tanggal Tutup (tgl_tutup)</label>
        <input type="date" name="tgl_tutup" value="<?= h($preview['tgl_tutup'] ?? '') ?>" required>
      </div>
      <div>
        <label>File Excel (.xlsx / .xls)</label>
        <input type="file" name="file_excel" accept=".xlsx,.xls" required>
      </div>
      <div>
        <button class="btn" type="submit" name="upload">Upload & Preview</button>
      </div>
    </div>
    <div class="muted" style="margin-top:10px;">
      * Baris pertama Excel harus berisi header kolom sesuai field database.
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
              Tgl Tutup: <b><?= h($preview['tgl_tutup']) ?></b> |
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
              <button class="btn2" type="submit" name="save">Simpan ke Database</button>
              <button class="btn3" type="submit" name="clear" onclick="return confirm('Kosongkan preview?')">Kosongkan Preview</button>
            </form>
  <?php endif; ?>
</div>

    <script type="text/javascript" src="files\bower_components\jquery\js\jquery.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-ui\js\jquery-ui.min.js"></script>
    <script type="text/javascript" src="files\bower_components\popper.js\js\popper.min.js"></script>
    <script type="text/javascript" src="files\bower_components\bootstrap\js\bootstrap.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-slimscroll\js\jquery.slimscroll.js"></script>
    <script type="text/javascript" src="files\bower_components\modernizr\js\modernizr.js"></script>
    <script type="text/javascript" src="files\bower_components\modernizr\js\css-scrollbars.js"></script>
    <script src="files\bower_components\datatables.net\js\jquery.dataTables.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\dataTables.buttons.min.js"></script>
    <script src="files\assets\pages\data-table\js\jszip.min.js"></script>
    <script src="files\assets\pages\data-table\js\pdfmake.min.js"></script>
    <script src="files\assets\pages\data-table\js\vfs_fonts.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\dataTables.buttons.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\buttons.flash.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\jszip.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\vfs_fonts.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\buttons.colVis.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\buttons.print.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\buttons.html5.min.js"></script>
    <script src="files\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js"></script>
    <script src="files\bower_components\datatables.net-responsive\js\dataTables.responsive.min.js"></script>
    <script src="files\bower_components\datatables.net-responsive-bs4\js\responsive.bootstrap4.min.js"></script>
    <script type="text/javascript" src="files\bower_components\i18next\js\i18next.min.js"></script>
    <script type="text/javascript" src="files\bower_components\i18next-xhr-backend\js\i18nextXHRBackend.min.js">
    </script>
    <script type="text/javascript"
        src="files\bower_components\i18next-browser-languagedetector\js\i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-i18next\js\jquery-i18next.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\extension-btns-custom.js"></script>
    <script src="files\assets\js\pcoded.min.js"></script>
    <script src="files\assets\js\menu\menu-hori-fixed.js"></script>
    <script src="files\assets\js\jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="files\assets\js\script.js"></script>
</body>

</html>