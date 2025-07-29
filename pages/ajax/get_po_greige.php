<?php
include "../../koneksi.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_code'])) {
    $orderCode = $_POST['order_code'];

    $query = "SELECT DISTINCT 
                    SALESORDERCODE,
                    ORDERLINE,
                    LEGALNAME1,
                    AKJ,
                    JENIS_KAIN,
                    ITEMCODE,
                    LISTAGG(NOTETAS, ', ') AS NOTETAS,
                    NO_PO,
                    GRAMASI,
                    LEBAR,
                    COLOR_STANDARD,
                    WARNA,
                    KODE_WARNA,
                    COLORREMARKS,
                    SUBCODE01,
                    SUBCODE02,
                    SUBCODE03,
                    SUBCODE04,
                    SUBCODE04_FIXED,
                    SUBCODE05,
                    SUBCODE06,
                    SUBCODE07,
                    SUBCODE08,
                    SUBCODE09,
                    SUBCODE10
                FROM 
                (SELECT
                    i.SALESORDERCODE,
                    i.ORDERLINE,
                    CASE 
                        WHEN i.ITEMTYPEAFICODE = 'KFF' THEN i.RESERVATION_SUBCODE04
                        ELSE i.SUBCODE04
                    END AS SUBCODE04_FIXED,
                    i.LEGALNAME1,
                    i.AKJ,
                    p.LONGDESCRIPTION AS JENIS_KAIN,
                    i.NOTETAS_KGF || '/' || TRIM(i.SUBCODE01) || '-' || TRIM(i.SUBCODE02) || '-' || TRIM(i.SUBCODE03) || '-' || TRIM(i.SUBCODE04) AS ITEMCODE,
                    i.NOTETAS,
                    i.EXTERNALREFERENCE AS NO_PO,
                    COALESCE(i2.GRAMASI_KFF, i2.GRAMASI_FKF) AS GRAMASI,
                    i3.LEBAR,
                    CASE a.VALUESTRING
                        WHEN '1' THEN 'L/D'
                        WHEN '2' THEN 'First Lot'
                        WHEN '3' THEN 'Original'
                        WHEN '4' THEN 'Previous Order'
                        WHEN '5' THEN 'Master Color'
                        WHEN '6' THEN 'Lampiran Buyer'
                        WHEN '7' THEN 'Body'
                        ELSE ''
                    END AS COLOR_STANDARD,
                    i.WARNA,
                    TRIM(i.SUBCODE05) || ' (' || TRIM(i.COLORGROUP) || ')' AS KODE_WARNA,
                    a2.VALUESTRING AS COLORREMARKS,
                    TRIM(i.SUBCODE01) AS SUBCODE01,
                    TRIM(i.SUBCODE02) AS SUBCODE02,
                    TRIM(i.SUBCODE03) AS SUBCODE03,
                    TRIM(i.SUBCODE04) AS SUBCODE04,
                    TRIM(i.SUBCODE05) AS SUBCODE05,
                    TRIM(i.SUBCODE06) AS SUBCODE06,
                    TRIM(i.SUBCODE07) AS SUBCODE07,
                    TRIM(i.SUBCODE08) AS SUBCODE08,
                    TRIM(i.SUBCODE09) AS SUBCODE09,
                    TRIM(i.SUBCODE10) AS SUBCODE10
                FROM
                    ITXVIEWBONORDER i
                LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = i.ITEMTYPEAFICODE 
                                    AND p.SUBCODE01 = i.SUBCODE01 
                                    AND p.SUBCODE02 = i.SUBCODE02 
                                    AND p.SUBCODE03 = i.SUBCODE03 
                                    AND p.SUBCODE04 = i.SUBCODE04 
                                    AND p.SUBCODE05 = i.SUBCODE05 
                                    AND p.SUBCODE06 = i.SUBCODE06 
                                    AND p.SUBCODE07 = i.SUBCODE07 
                                    AND p.SUBCODE08 = i.SUBCODE08 
                                    AND p.SUBCODE09 = i.SUBCODE09 
                                    AND p.SUBCODE10 = i.SUBCODE10
                LEFT JOIN ITXVIEWGRAMASI i2 ON i2.SALESORDERCODE = i.SALESORDERCODE AND i2.ORDERLINE = i.ORDERLINE 
                LEFT JOIN ITXVIEWLEBAR i3 ON i3.SALESORDERCODE = i.SALESORDERCODE AND i3.ORDERLINE = i.ORDERLINE 
                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND a.FIELDNAME = 'ColorStandard'
                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = i.ABSUNIQUEID_SALESORDERLINE AND a2.FIELDNAME = 'ColorRemarks'
                WHERE i.SALESORDERCODE = '$orderCode')
                GROUP BY
                    SALESORDERCODE,
                    ORDERLINE,
                    LEGALNAME1,
                    AKJ,
                    JENIS_KAIN,
                    ITEMCODE,
                    NO_PO,
                    GRAMASI,
                    LEBAR,
                    COLOR_STANDARD,
                    WARNA,
                    KODE_WARNA,
                    COLORREMARKS,
                    SUBCODE01,
                    SUBCODE02,
                    SUBCODE03,
                    SUBCODE04,
                    SUBCODE04_FIXED,
                    SUBCODE05,
                    SUBCODE06,
                    SUBCODE07,
                    SUBCODE08,
                    SUBCODE09,
                    SUBCODE10
                ORDER BY
                    ORDERLINE 
                ASC";
    $stmt = db2_exec($conn1, $query);

    // Contoh isi dummy
    $html = '
    <table class="table table-sm table-bordered mb-0">
        <thead class="bg-warning text-white">
            <tr>
                <th>WARNA</th>          
                <th>Kode Warna</th>          
                <th>Color Remarks</th>          
                <th>Kode Item</th>          
                <th style="width: 40%;">BENANG</th>
                <th>PO GREIGE</th>
                <th>PIC Check</th>
                <th>Status Bon Order</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>';
        while ($row = db2_fetch_assoc($stmt)) {
            // Ambil data ITXVIEWBONORDER
            $q_itxviewkk = db2_exec($conn1, "SELECT * FROM ITXVIEWBONORDER i WHERE SALESORDERCODE = '{$row['SALESORDERCODE']}' AND ORDERLINE = '{$row['ORDERLINE']}'");
            $d_itxviewkk = db2_fetch_assoc($q_itxviewkk);

            // Tentukan $subcode04 berdasarkan ITEMTYPEAFICODE
            if ($d_itxviewkk['ITEMTYPEAFICODE'] === 'KFF') {
                $subcode04 = $d_itxviewkk['RESERVATION_SUBCODE04'];
            } else {
                $subcode04 = $d_itxviewkk['SUBCODE04'];
            }

            // Cek kondisi AKJ/AKW/ADDITIONALDATA/LEGACYORDER (Rajut)
                $skipRajut = (
                    $d_itxviewkk['AKJ'] === 'AKJ' ||
                    $d_itxviewkk['AKJ'] === 'AKW' ||
                    !empty($d_itxviewkk['ADDITIONALDATA']) ||
                    !empty($d_itxviewkk['LEGACYORDER'])
                );

                if ($skipRajut) {
                    $d_rajut = [
                        'BENANG' => '',
                        'PO_GREIGE' => ''
                    ];
                } else {
                    $q_rajut = db2_exec($conn1, "SELECT
                                                    SUMMARIZEDDESCRIPTION AS BENANG,
                                                    CODE AS PO_GREIGE
                                                FROM ITXVIEW_RAJUT
                                                WHERE
                                                    SUBCODE01 = '{$d_itxviewkk['SUBCODE01']}'
                                                    AND SUBCODE02 = '{$d_itxviewkk['SUBCODE02']}'
                                                    AND SUBCODE03 = '{$d_itxviewkk['SUBCODE03']}'
                                                    AND SUBCODE04 = '$subcode04'
                                                    AND ORIGDLVSALORDLINESALORDERCODE = '{$row['SALESORDERCODE']}'
                                                    AND (ITEMTYPEAFICODE = 'KGF' OR ITEMTYPEAFICODE = 'FKG')");
                    $d_rajut = db2_fetch_assoc($q_rajut) ?: ['BENANG' => '', 'PO_GREIGE' => ''];
                }
            // Cek kondisi AKJ/AKW/ADDITIONALDATA/LEGACYORDER (Rajut)

            // Cek kondisi AKJ/AKW/ADDITIONALDATA (Ready)
                $skipReady = (
                    $d_itxviewkk['AKJ'] === 'AKJ' ||
                    $d_itxviewkk['AKJ'] === 'AKW' ||
                    !empty($d_itxviewkk['ADDITIONALDATA'])
                );

                if ($skipReady) {
                    $d_booking_new = [
                        'BENANG' => '',
                        'PO_GREIGE' => ''
                    ];
                } else {
                    $q_booking_new = db2_exec($conn1, "SELECT
                                                            PROJECTCODE AS PO_GREIGE,
                                                            SUMMARIZEDDESCRIPTION AS BENANG
                                                        FROM ITXVIEW_BOOKING_NEW
                                                        WHERE
                                                            SALESORDERCODE = '{$row['SALESORDERCODE']}'
                                                            AND ORDERLINE = '{$row['ORDERLINE']}'");
                    $d_booking_new = db2_fetch_assoc($q_booking_new) ?: ['BENANG' => '', 'PO_GREIGE' => ''];
                }
            // Cek kondisi AKJ/AKW/ADDITIONALDATA (Ready)

            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 1
                $skipBlmReady1 = (
                    $d_itxviewkk['AKJ'] === 'AKJ' ||
                    $d_itxviewkk['AKJ'] === 'AKW' 
                );

                if ($skipBlmReady1) {
                    $d_booking_new = [
                        'BENANG' => '',
                        'PO_GREIGE' => ''
                    ];
                } else {
                    $q_booking_blm_ready_1	= db2_exec($conn1, "SELECT
                                                                    ORIGDLVSALORDLINESALORDERCODE AS PO_GREIGE,
                                                                    COALESCE(SUMMARIZEDDESCRIPTION, '') || COALESCE(ORIGDLVSALORDLINESALORDERCODE, '') AS BENANG
                                                                FROM
                                                                    ITXVIEW_BOOKING_BLM_READY ibbr 
                                                                WHERE
                                                                    SUBCODE01 = '{$d_itxviewkk['SUBCODE01']}'
                                                                    AND SUBCODE02 = '{$d_itxviewkk['SUBCODE02']}'
                                                                    AND SUBCODE03 = '{$d_itxviewkk['SUBCODE03']}'
                                                                    AND SUBCODE04 = '$subcode04'
                                                                    AND ORIGDLVSALORDLINESALORDERCODE = '{$d_itxviewkk['ADDITIONALDATA']}'
                                                                    AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
                    $d_booking_blm_ready_1	= db2_fetch_assoc($q_booking_blm_ready_1);
                    $d_booking_blm_ready_1 = $d_booking_blm_ready_1 ?: ['BENANG' => '', 'PO_GREIGE' => ''];
                }
            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 1
            
            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 2
                $skipBlmReady2 = (
                    $d_itxviewkk['AKJ'] === 'AKJ' ||
                    $d_itxviewkk['AKJ'] === 'AKW' 
                );

                if ($skipBlmReady2) {
                    $d_booking_new = [
                        'BENANG' => '',
                        'PO_GREIGE' => ''
                    ];
                } else {
                    $q_booking_blm_ready_2	= db2_exec($conn1, "SELECT
                                                                    ORIGDLVSALORDLINESALORDERCODE AS PO_GREIGE,
                                                                    COALESCE(SUMMARIZEDDESCRIPTION, '') || COALESCE(ORIGDLVSALORDLINESALORDERCODE, '') AS BENANG
                                                                FROM
                                                                    ITXVIEW_BOOKING_BLM_READY ibbr 
                                                                WHERE
                                                                    SUBCODE01 = '{$d_itxviewkk['SUBCODE01']}'
                                                                    AND SUBCODE02 = '{$d_itxviewkk['SUBCODE02']}'
                                                                    AND SUBCODE03 = '{$d_itxviewkk['SUBCODE03']}'
                                                                    AND SUBCODE04 = '$subcode04'
                                                                    AND ORIGDLVSALORDLINESALORDERCODE = '{$d_itxviewkk['ADDITIONALDATA2']}'
                                                                    AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
                    $d_booking_blm_ready_2	= db2_fetch_assoc($q_booking_blm_ready_2);
                    $d_booking_blm_ready_2 = $d_booking_blm_ready_2 ?: ['BENANG' => '', 'PO_GREIGE' => ''];
                }
            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 2
            
            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 3
                $skipBlmReady3 = (
                    $d_itxviewkk['AKJ'] === 'AKJ' ||
                    $d_itxviewkk['AKJ'] === 'AKW' 
                );

                if ($skipBlmReady3) {
                    $d_booking_blm_ready_3 = [
                        'BENANG' => '',
                        'PO_GREIGE' => ''
                    ];
                } else {
                    $q_booking_blm_ready_3 = db2_exec($conn1, "SELECT
                                                                    ORIGDLVSALORDLINESALORDERCODE AS PO_GREIGE,
                                                                    COALESCE(SUMMARIZEDDESCRIPTION, '') || COALESCE(ORIGDLVSALORDLINESALORDERCODE, '') AS BENANG
                                                                FROM
                                                                    ITXVIEW_BOOKING_BLM_READY ibbr 
                                                                WHERE
                                                                    SUBCODE01 = '{$d_itxviewkk['SUBCODE01']}'
                                                                    AND SUBCODE02 = '{$d_itxviewkk['SUBCODE02']}'
                                                                    AND SUBCODE03 = '{$d_itxviewkk['SUBCODE03']}'
                                                                    AND SUBCODE04 = '$subcode04'
                                                                    AND ORIGDLVSALORDLINESALORDERCODE = '{$d_itxviewkk['ADDITIONALDATA3']}'
                                                                    AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
                    $d_booking_blm_ready_3 = db2_fetch_assoc($q_booking_blm_ready_3);
                    $d_booking_blm_ready_3 = $d_booking_blm_ready_3 ?: ['BENANG' => '', 'PO_GREIGE' => ''];
                }
            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 3

            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 4
                $skipBlmReady4 = (
                    $d_itxviewkk['AKJ'] === 'AKJ' ||
                    $d_itxviewkk['AKJ'] === 'AKW' 
                );

                if ($skipBlmReady4) {
                    $d_booking_blm_ready_4 = [
                        'BENANG' => '',
                        'PO_GREIGE' => ''
                    ];
                } else {
                    $q_booking_blm_ready_4 = db2_exec($conn1, "SELECT
                                                                    ORIGDLVSALORDLINESALORDERCODE AS PO_GREIGE,
                                                                    COALESCE(SUMMARIZEDDESCRIPTION, '') || COALESCE(ORIGDLVSALORDLINESALORDERCODE, '') AS BENANG
                                                                FROM
                                                                    ITXVIEW_BOOKING_BLM_READY ibbr 
                                                                WHERE
                                                                    SUBCODE01 = '{$d_itxviewkk['SUBCODE01']}'
                                                                    AND SUBCODE02 = '{$d_itxviewkk['SUBCODE02']}'
                                                                    AND SUBCODE03 = '{$d_itxviewkk['SUBCODE03']}'
                                                                    AND SUBCODE04 = '$subcode04'
                                                                    AND ORIGDLVSALORDLINESALORDERCODE = '{$d_itxviewkk['ADDITIONALDATA4']}'
                                                                    AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
                    $d_booking_blm_ready_4 = db2_fetch_assoc($q_booking_blm_ready_4);
                    $d_booking_blm_ready_4 = $d_booking_blm_ready_4 ?: ['BENANG' => '', 'PO_GREIGE' => ''];
                }
            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 4

            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 5
                $skipBlmReady5 = (
                    $d_itxviewkk['AKJ'] === 'AKJ' ||
                    $d_itxviewkk['AKJ'] === 'AKW' 
                );

                if ($skipBlmReady5) {
                    $d_booking_blm_ready_5 = [
                        'BENANG' => '',
                        'PO_GREIGE' => ''
                    ];
                } else {
                    $q_booking_blm_ready_5 = db2_exec($conn1, "SELECT
                                                                    ORIGDLVSALORDLINESALORDERCODE AS PO_GREIGE,
                                                                    COALESCE(SUMMARIZEDDESCRIPTION, '') || COALESCE(ORIGDLVSALORDLINESALORDERCODE, '') AS BENANG
                                                                FROM
                                                                    ITXVIEW_BOOKING_BLM_READY ibbr 
                                                                WHERE
                                                                    SUBCODE01 = '{$d_itxviewkk['SUBCODE01']}'
                                                                    AND SUBCODE02 = '{$d_itxviewkk['SUBCODE02']}'
                                                                    AND SUBCODE03 = '{$d_itxviewkk['SUBCODE03']}'
                                                                    AND SUBCODE04 = '$subcode04'
                                                                    AND ORIGDLVSALORDLINESALORDERCODE = '{$d_itxviewkk['ADDITIONALDATA5']}'
                                                                    AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
                    $d_booking_blm_ready_5 = db2_fetch_assoc($q_booking_blm_ready_5);
                    $d_booking_blm_ready_5 = $d_booking_blm_ready_5 ?: ['BENANG' => '', 'PO_GREIGE' => ''];
                }
            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 5

            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 6
                $skipBlmReady6 = (
                    $d_itxviewkk['AKJ'] === 'AKJ' ||
                    $d_itxviewkk['AKJ'] === 'AKW' 
                );

                if ($skipBlmReady6) {
                    $d_booking_blm_ready_6 = [
                        'BENANG' => '',
                        'PO_GREIGE' => ''
                    ];
                } else {
                    $q_booking_blm_ready_6 = db2_exec($conn1, "SELECT
                                                                    ORIGDLVSALORDLINESALORDERCODE AS PO_GREIGE,
                                                                    COALESCE(SUMMARIZEDDESCRIPTION, '') || COALESCE(ORIGDLVSALORDLINESALORDERCODE, '') AS BENANG
                                                                FROM
                                                                    ITXVIEW_BOOKING_BLM_READY ibbr 
                                                                WHERE
                                                                    SUBCODE01 = '{$d_itxviewkk['SUBCODE01']}'
                                                                    AND SUBCODE02 = '{$d_itxviewkk['SUBCODE02']}'
                                                                    AND SUBCODE03 = '{$d_itxviewkk['SUBCODE03']}'
                                                                    AND SUBCODE04 = '$subcode04'
                                                                    AND ORIGDLVSALORDLINESALORDERCODE = '{$d_itxviewkk['ADDITIONALDATA6']}'
                                                                    AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
                    $d_booking_blm_ready_6 = db2_fetch_assoc($q_booking_blm_ready_6);
                    $d_booking_blm_ready_6 = $d_booking_blm_ready_6 ?: ['BENANG' => '', 'PO_GREIGE' => ''];
                }
            // Cek kondisi AKJ/AKW/ADDITIONALDATA (BlmReady) 6

            // Gabungkan BENANG
                $benangList = array_values(array_filter([
                    htmlspecialchars($d_rajut['BENANG'] ?? ''),
                    htmlspecialchars($d_booking_new['BENANG'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_1['BENANG'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_2['BENANG'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_3['BENANG'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_4['BENANG'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_5['BENANG'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_6['BENANG'] ?? '')
                ]));
            // Gabungkan BENANG

            // Gabungkan PO_GREIGE
                $po_greige_List = array_values(array_filter([
                    htmlspecialchars($d_rajut['PO_GREIGE'] ?? ''),
                    htmlspecialchars($d_booking_new['PO_GREIGE'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_1['PO_GREIGE'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_2['PO_GREIGE'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_3['PO_GREIGE'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_4['PO_GREIGE'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_5['PO_GREIGE'] ?? ''),
                    htmlspecialchars($d_booking_blm_ready_6['PO_GREIGE'] ?? '')
                ]));
            // Gabungkan PO_GREIGE

            // Hitung max — biar tidak error kalau jumlahnya beda
                $max = max(count($benangList), count($po_greige_List));
            // Hitung max — biar tidak error kalau jumlahnya beda

            for ($i = 0; $i < $max; $i++) {
                $benang = $benangList[$i] ?? '';
                $po     = $po_greige_List[$i] ?? '';

                // Cek data yang sudah pernah disimpan untuk kombinasi ini
                $selectedPIC = '';
                $selectedStatus = '';

                $queryCheck = "SELECT * FROM status_matching_bon_order 
                                    WHERE 
                                        salesorder = '{$row['SALESORDERCODE']}' 
                                        AND orderline = '{$row['ORDERLINE']}'
                                        AND warna = '{$row['WARNA']}'
                                        AND po_greige = '$po'
                                    LIMIT 1";
                $resultCheck = mysqli_query($con, $queryCheck);
                if ($resultCheck && mysqli_num_rows($resultCheck) > 0) {
                    $dataCheck = mysqli_fetch_assoc($resultCheck);
                    $selectedPIC = htmlspecialchars($dataCheck['pic_check']);
                    $selectedStatus = htmlspecialchars($dataCheck['status_bonorder']);
                    $btnLabelSimpanEdit = 'Edit';
                }else{
                    $btnLabelSimpanEdit = 'Simpan';
                }
                
                // PIC SELECT
                    $queryPIC = "SELECT * FROM tbl_user WHERE pic_bonorder = 1 ORDER BY id ASC";
                    $resultPIC = mysqli_query($con, $queryPIC);

                    $optionPIC = '<option value="">-- Pilih PIC --</option>';
                    while ($rowPIC = mysqli_fetch_assoc($resultPIC)) {
                        $picValue = htmlspecialchars($rowPIC['username']);
                        $selected = ($picValue === $selectedPIC) ? 'selected' : '';
                        $optionPIC .= "<option value=\"$picValue\" $selected>$picValue</option>";
                    }
                // PIC SELECT

                // Status Select
                    $statuses = ['OK', 'Matching Ulang'];
                    $optionStatus = "<option value=''>--Pilih--</option>";

                    foreach ($statuses as $status) {
                        $selected = ($status === $selectedStatus) ? 'selected' : '';
                        $optionStatus .= "<option value=\"$status\" $selected>$status</option>";
                    }
                // Status Select

                $html .= "
                    <tr class=\"row-item\">
                        <td hidden class=\"td-salesorder\">{$row['SALESORDERCODE']}</td>
                        <td hidden class=\"td-orderline\">{$row['ORDERLINE']}</td>
                        <td class=\"td-warna\">{$row['WARNA']}</td>
                        <td class=\"td-kode-warna\">{$row['KODE_WARNA']}</td>
                        <td class=\"td-color-remarks\">{$row['COLORREMARKS']}</td>
                        <td class=\"td-item-code\">{$row['ITEMCODE']}</td>
                        <td class=\"td-benang\">$benang</td>
                        <td class=\"td-po\">$po</td>
                        <td>
                            <select class=\"form-control form-control-sm pic-check\">
                                $optionPIC
                            </select>
                        </td>
                        <td>
                            <select class=\"form-control form-control-sm status-bonorder\">
                                $optionStatus
                            </select>
                        </td>
                        <td>
                            <button type=\"button\" class=\"btn btn-primary btn-sm btn-simpan-row\">
                                <span class=\"btn-text\">$btnLabelSimpanEdit</span>
                                <span class=\"spinner-border spinner-border-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                            </button>
                        </td>
                    </tr>
                ";
            }
        }

        $html .= '</tbody></table>';

    echo $html;
}
?>
<script>

$(document).ready(function(){
    const currentUser = "<?= $_SESSION['userLAB'] ?? '' ?>";

    toastr.options = {
        "positionClass": "toast-top-right",
        "closeButton": true,
        "progressBar": true,
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Lock select jika sudah ada data dari awal
    $('.row-item').each(function() {
        const row = $(this);
        const pic = row.find('.pic-check').val();
        const status = row.find('.status-bonorder').val();
        const btn = row.find('.btn-simpan-row');

        if (pic && status) {
            row.find('.pic-check').prop('disabled', true);
            row.find('.status-bonorder').prop('disabled', true);
            btn.find('.btn-text').text('Edit');
        }
    });

    // Handler untuk tombol Simpan/Edit/Update
    $(document).off('click', '.btn-simpan-row').on('click', '.btn-simpan-row', function(){
        console.log(currentUser);
        
        if (currentUser !== 'Riyan') {
            toastr.warning('Hanya user Riyan yang dapat melakukan edit');
            return;
        }

        const btn = $(this);
        const row = btn.closest('.row-item');
        const btnText = btn.find('.btn-text').text().trim();

        const salesorder = row.find('.td-salesorder').text().trim();
        const orderline  = row.find('.td-orderline').text().trim();
        const warna      = row.find('.td-warna').text().trim();
        const benang     = row.find('.td-benang').text().trim();
        const po         = row.find('.td-po').text().trim();
        const picSelect  = row.find('.pic-check');
        const statusSelect = row.find('.status-bonorder');

        const pic        = picSelect.val();
        const status     = statusSelect.val();

        if (btnText === 'Edit') {
            // Buka lock dropdown
            picSelect.prop('disabled', false);
            statusSelect.prop('disabled', false);
            btn.find('.btn-text').text('Update');
            return;
        }

        if (!pic || !status) {
            alert('PIC dan Status Bon Order wajib dipilih!');
            return;
        }

        // Animasi loading
        btn.addClass('btn-loading');
        btn.find('.btn-text').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
        btn.find('.spinner-border').removeClass('d-none');

        $.ajax({
            url: 'pages/ajax/simpan_status_matching_bonorder.php',
            type: 'POST',
            data: {
                salesorder,
                orderline,
                warna,
                benang,
                po_greige: po,
                pic_check: pic,
                status_bonorder: status
            },
            success: function(response) {
                toastr.success(response);

                setTimeout(function(){
                    btn.removeClass('btn-loading');
                    btn.find('.btn-text').text('Edit');
                    btn.find('.spinner-border').addClass('d-none');

                    // Lock kembali dropdown
                    picSelect.prop('disabled', true);
                    statusSelect.prop('disabled', true);
                }, 1000);
            },
            error: function(xhr, status, error) {
                toastr.error("Terjadi kesalahan: " + error);

                setTimeout(function(){
                    btn.removeClass('btn-loading');
                    btn.find('.btn-text').text('Update');
                    btn.find('.spinner-border').addClass('d-none');
                }, 1000);
            }
        });
    });

});
</script>
