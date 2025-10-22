<?php
include "../../koneksi.php";

$is_revision = isset($_GET['is_revision']) ? (int)$_GET['is_revision'] : 0;

$sqlApproved = "
  SELECT id, customer, code, tgl_approve_lab, pic_lab, status
  FROM approval_bon_order
  WHERE is_revision = $is_revision
  ORDER BY id DESC
";
$resApproved = mysqli_query($con, $sqlApproved);

$rowsApproved = [];
$codes = [];
while ($r = mysqli_fetch_assoc($resApproved)) {
    $rowsApproved[] = $r;
    $codes[] = strtoupper(trim($r['code']));
}

function db2_quote($s) { return str_replace("'", "''", $s); }

$mapDb2Date = [];
if (!empty($codes)) {
    $chunkSize = 500;
    foreach (array_chunk($codes, $chunkSize) as $chunk) {
        $inList = implode(",", array_map(function($c){
            return "'" . db2_quote($c) . "'";
        }, $chunk));

        $sqlDb2 = "
            SELECT s.CODE AS CODE,
                   DATE(a.VALUETIMESTAMP) AS TGL_APPROVE_RMP
            FROM SALESORDER s
            JOIN ADSTORAGE a
              ON a.UNIQUEID = s.ABSUNIQUEID
             AND a.FIELDNAME = 'ApprovalRMPDateTime'
            WHERE a.VALUETIMESTAMP IS NOT NULL
              AND s.CODE IN ($inList)
        ";

        $stmt = db2_exec($conn1, $sqlDb2, ['cursor' => DB2_SCROLLABLE]);
        if ($stmt === false) {
            // DEBUG
            // echo "<!-- DB2 error: ".htmlspecialchars(db2_conn_errormsg($conn1)." | ".db2_stmt_errormsg())." -->";
            continue;
        }

        while ($row = db2_fetch_assoc($stmt)) {
            $mapDb2Date[strtoupper(trim($row['CODE']))] = $row['TGL_APPROVE_RMP']; // YYYY-MM-DD
        }
    }
}

foreach ($rowsApproved as $row) {
    $code   = strtoupper(trim($row['code']));
    $tglRmp = $mapDb2Date[$code] ?? ''; 

    $statusClass = ($row['status'] === 'Approved') ? 'text-success' : 'text-danger';

    echo "<tr>";
    echo   "<td style='display: none;'>" . htmlspecialchars($row['id']) . "</td>";
    echo   "<td>" . htmlspecialchars($row['customer']) . "</td>";
    echo   "<td>
              <a href=\"#\" class=\"btn btn-primary btn-sm open-detail\"
                 data-code=\"" . htmlspecialchars($code) . "\"
                 data-toggle=\"modal\" data-target=\"#detailModal\">" .
                 htmlspecialchars($code) .
              "</a>
            </td>";
    // ⬇️ kolom Tgl Approved RMP murni dari DB2
    echo   "<td>" . htmlspecialchars($tglRmp ?: '') . "</td>";
    echo   "<td>" . htmlspecialchars($row['tgl_approve_lab']) . "</td>";
    echo   "<td>" . htmlspecialchars($row['pic_lab']) . "</td>";
    echo   "<td><strong class=\"" . $statusClass . "\">" . htmlspecialchars($row['status']) . "</strong></td>";
    echo "</tr>";
}
