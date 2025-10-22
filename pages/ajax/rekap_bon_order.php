<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';

$kemarin = date('Y-m-d', strtotime('-1 day'));
$today = date('Y-m-d');

$todays = date('N'); // 1 = Senin, 7 = Minggu

if ($todays == 1) {
    // Hari ini Senin, jadi kemarin dianggap Hari Sabtu (2 hari sebelumnya)
    $kemarin = date('Y-m-d', strtotime('-2 days'));
} else {
    // Hari selain Senin, kemarin = 1 hari sebelum hari ini
    $kemarin = date('Y-m-d', strtotime('-1 day'));
    // $kemarin = "2025-09-23";
}

$tanggalAwal = '2025-06-01';

// Ambil semua PIC
$rekap = [];
$resPIC = mysqli_query($con, "SELECT username FROM tbl_user WHERE pic_bonorder = 1 ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($resPIC)) {
    $pic = $row['username'];
    $rekap[$pic] = [
        'approved' => 0,
        'reject' => 0,
        'matching_ulang' => 0,
        'ok' => 0
    ];
}

$sqlApproved = "SELECT * FROM approval_bon_order WHERE tgl_approve_lab ='$kemarin' ORDER BY id DESC";
$resultApproved = mysqli_query($con, $sqlApproved);
$approve_today = mysqli_num_rows($resultApproved);
// echo mysqli_num_rows($resultApproved);

// Rekap Approved & Rejected dari approval_bon_order
// $resApproval = mysqli_query($con, "SELECT
//                                         pic_lab,
//                                         `status`
//                                     FROM
//                                         approval_bon_order
//                                     WHERE
//                                         (STATUS = 'Approved'
//                                         AND tgl_approve_lab between '$kemarin' AND '$today')
//                                         OR (STATUS = 'Rejected'
//                                             AND tgl_rejected_lab between '$kemarin' AND '$today')
//                                             ");
$resApproval = mysqli_query($con, "SELECT
                                        pic_lab,
                                        `status`
                                    FROM
                                        approval_bon_order
                                    WHERE
                                        (STATUS = 'Approved'
                                        AND tgl_approve_lab = '$kemarin')
                                        OR (STATUS = 'Rejected'
                                            AND tgl_rejected_lab = '$kemarin')
                                            ");

while ($row = mysqli_fetch_assoc($resApproval)) {
    $pic = $row['pic_lab'];
    $status = strtolower(trim($row['status']));

    if (!isset($rekap[$pic])) {
        $rekap[$pic] = [
            'approved' => 0,
            'reject' => 0,
            'matching_ulang' => 0,
            'ok' => 0
        ];
    }

    if ($status === 'approved') {
        $rekap[$pic]['approved'] += 1;
    } elseif ($status === 'rejected') {
        $rekap[$pic]['reject'] += 1;
    }
}

// Rekap status_matching_bon_order JOIN approval_bon_order (ambil yg code match & sesuai tanggal H-1)
// $resStatus = mysqli_query($con, "SELECT 
//                                     smb.pic_check, 
//                                     LOWER(TRIM(smb.status_bonorder)) AS status_bonorder
//                                 FROM status_matching_bon_order smb
//                                 JOIN approval_bon_order ab ON ab.code = smb.salesorder
//                                 WHERE 
//                                     (
//                                         (ab.status = 'Approved' AND ab.tgl_approve_lab between '$kemarin' and '$today') OR
//                                         (ab.status = 'Rejected' AND ab.tgl_rejected_lab between '$kemarin' and '$today')
//                                     )
//                             ");
$resStatus = mysqli_query($con, "SELECT 
                                    smb.pic_check, 
                                    LOWER(TRIM(smb.status_bonorder)) AS status_bonorder
                                FROM status_matching_bon_order smb
                                JOIN approval_bon_order ab ON ab.code = smb.salesorder
                                WHERE 
                                    (
                                        (ab.status = 'Approved' AND ab.tgl_approve_lab =  '$kemarin') OR
                                        (ab.status = 'Rejected' AND ab.tgl_rejected_lab =  '$kemarin')
                                    )
                            ");

while ($row = mysqli_fetch_assoc($resStatus)) {
    $pic = $row['pic_check'];
    $status = $row['status_bonorder'];

    if (!isset($rekap[$pic])) {
        $rekap[$pic] = [
            'approved' => 0,
            'reject' => 0,
            'matching_ulang' => 0,
            'ok' => 0
        ];
    }

    if ($status === 'matching ulang' || $status === 'matching_ulang') {
        $rekap[$pic]['matching_ulang'] += 1;
    } elseif ($status === 'ok') {
        $rekap[$pic]['ok'] += 1;
    }
}

// Total Bon Order diterima H-1 (via query dari ITXVIEW)
$approvedCodes = [];
$resCode = mysqli_query($con, "SELECT code FROM approval_bon_order");
while ($r = mysqli_fetch_assoc($resCode)) {
    $approvedCodes[] = "'" . mysqli_real_escape_string($con, $r['code']) . "'";
}
$codeList = implode(",", $approvedCodes);

$sqlTBO1 = "SELECT DISTINCT 
                isa.CODE AS CODE,
                COALESCE(ip.LANGGANAN, '') || COALESCE(ip.BUYER, '') AS CUSTOMER,
                isa.TGL_APPROVEDRMP AS TGL_APPROVE_RMP,
                VARCHAR_FORMAT(a.VALUETIMESTAMP, 'YYYY-MM-DD HH24:MI:SS') AS ApprovalRMPDateTime
            FROM ITXVIEW_SALESORDER_APPROVED isa
            LEFT JOIN SALESORDER s
                ON s.CODE = isa.CODE
            LEFT JOIN ITXVIEW_PELANGGAN ip
                ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE
                AND ip.CODE = s.CODE
            LEFT JOIN ADSTORAGE a
                ON a.UNIQUEID = s.ABSUNIQUEID
                AND a.FIELDNAME = 'ApprovalRMPDateTime'
            WHERE a.VALUETIMESTAMP IS NOT NULL
                AND DATE(a.VALUETIMESTAMP) = '$kemarin'
";
if (!empty($codeList)) {
    $sqlTBO1 .= " AND isa.CODE NOT IN ($codeList)";
}

$resultTBO1 = db2_exec($conn1, $sqlTBO1, ['cursor' => DB2_SCROLLABLE]);
$totalH11 = db2_num_rows($resultTBO1);

$totalH1 = $approve_today + $totalH11;
// Hitung total per status
$totalApproved = $totalReject = $totalMatchingUlang = $totalOK = 0;
foreach ($rekap as $data) {
    $totalApproved += $data['approved'];
    $totalReject += $data['reject'];
    $totalMatchingUlang += $data['matching_ulang'];
    $totalOK += $data['ok'];
}

$sisaReview = $totalH1 - ($totalApproved + $totalReject);
?>

<div class="col-md-6">
    <div class="box">
        <h4 class="text-center" style="font-weight: bold;">REKAP STATUS BON ORDER <span class="text-center" style="font-weight: bold;">H-1 (<?=$kemarin; ?>)</span></h4>

        <table class="table table-chart">
            <thead class="table-secondary">
                <tr class="text-center">
                    <th style="text-align: center;">PIC</th>
                    <th style="text-align: center;">Approved</th>
                    <!-- <th style="text-align: center;">Reject</th> -->
                    <th style="text-align: center;">Matching Ulang</th>
                    <th style="text-align: center;">OK</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rekap as $pic => $data): ?>
                    <tr>
                        <td><?= htmlspecialchars($pic) ?></td>
                        <td class="text-center"><?= $data['approved'] ?></td>
                        <!-- <td class="text-center"><?= $data['reject'] ?></td> -->
                        <td class="text-center"><?= $data['matching_ulang'] ?></td>
                        <td class="text-center"><?= $data['ok'] ?></td>
                    </tr>
                <?php endforeach; ?>

                <tr class="fw-bold table-light">
                    <th>Total</th>
                    <th style="text-align: center;"><?= $totalApproved ?></th>
                    <!-- <th style="text-align: center;"><?= $totalReject ?></th> -->
                    <th style="text-align: center;"><?= $totalMatchingUlang ?></th>
                    <th style="text-align: center;"><?= $totalOK ?></th>
                </tr>
                <tr class="table-warning fw-bold">
                    <th>Total Bon Order Diterima H-1</th>
                    <th colspan="4" style="text-align: center;"><?= $totalH1 ?></th>
                    <!-- <th colspan="4" style="text-align: center;"><?= $totalH1 ?></th> -->
                </tr>
                <tr class="table-danger fw-bold">
                    <th>Sisa Bon Order Belum Direview</th>
                    <th colspan="4" style="text-align: center;"><?= $totalH11 ?></th>
                    <!-- <th colspan="4" style="text-align: center;"><?= max(0, $sisaReview) ?></th> -->
                </tr>
            </tbody>
        </table>
    </div>
</div>
