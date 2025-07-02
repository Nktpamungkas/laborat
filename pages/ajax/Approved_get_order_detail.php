<?php
// koneksi ke DB
include "../../koneksi.php";

$code = $_POST['code'];

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
            WHERE i.SALESORDERCODE = '$code')
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
$no = 1;
if ($stmt) {
    echo "<table class='table table-bordered table-striped' id='detailApprovedTable'>";
    echo "<thead>
            <tr>
                <th>No</th>
                <th>Bon Order</th>
                <th>No PO</th>
                <th>Nama Buyer</th>
                <th>Jenis Kain</th>
                <th>AKJ</th>
                <th>Itemcode</th>
                <th>Notetas</th>
                <th>Gramasi</th>
                <th>Lebar</th>
                <th>Color Standard</th>
                <th>Warna</th>
                <th>Kode Warna</th>
                <th>Color Remarks</th>
                <th>Benang</th>
                <th>Po Greige</th>
            </tr>
          </thead>";
    echo "<tbody>";

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
            $benangList = [
                htmlspecialchars($d_rajut['BENANG'] ?? ''),
                htmlspecialchars($d_booking_new['BENANG'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_1['BENANG'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_2['BENANG'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_3['BENANG'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_4['BENANG'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_5['BENANG'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_6['BENANG'] ?? '')
            ];
            $benang = implode('<br><br>', array_filter($benangList));
        // Gabungkan BENANG

        // Gabungkan PO_GREIGE
            $po_greige_List = [
                htmlspecialchars($d_rajut['PO_GREIGE'] ?? ''),
                htmlspecialchars($d_booking_new['PO_GREIGE'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_1['PO_GREIGE'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_2['PO_GREIGE'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_3['PO_GREIGE'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_4['PO_GREIGE'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_5['PO_GREIGE'] ?? ''),
                htmlspecialchars($d_booking_blm_ready_6['PO_GREIGE'] ?? '')
            ];
            $po_greige = implode('<br><br>', array_filter($po_greige_List));
        // Gabungkan PO_GREIGE

        echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['SALESORDERCODE'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['NO_PO'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['LEGALNAME1'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['JENIS_KAIN'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['AKJ'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['ITEMCODE'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['NOTETAS'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars(number_format($row['GRAMASI'] ?? 0, 2)) . "</td>";
            echo "<td>" . htmlspecialchars(number_format($row['LEBAR'] ?? 0, 2)) . "</td>";
            echo "<td>" . htmlspecialchars($row['COLOR_STANDARD'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['WARNA'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['KODE_WARNA'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['COLORREMARKS'] ?? '') . "</td>";
            echo "<td>" . $benang . "</td>";
            echo "<td>" . $po_greige . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p class='text-danger'>Data tidak ditemukan.</p>";
}

?>
