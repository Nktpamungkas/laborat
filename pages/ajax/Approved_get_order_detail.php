<?php
// koneksi ke DB
include "../../koneksi.php";

$code = $_POST['code'];

$query = "SELECT
        i.SALESORDERCODE,
        i.ORDERLINE,
        i.LEGALNAME1,
        p.LONGDESCRIPTION AS JENIS_KAIN,
        i.NOTETAS_KGF || '/' || TRIM(i.SUBCODE01) || '-' || TRIM(i.SUBCODE02) || '-' || TRIM(i.SUBCODE03) || '-' || TRIM(i.SUBCODE04) AS ITEMCODE,
        i.NOTETAS,
        i.EXTERNALREFERENCE AS NO_PO,
        COALESCE(i2.GRAMASI_KFF, i2.GRAMASI_FKF) AS GRAMASI,
        i3.LEBAR,
        pg.PO_GREIGE,
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
    LEFT JOIN (
        SELECT 
            ORIGDLVSALORDLINESALORDERCODE AS SALESORDERCODE,
            ORIGDLVSALORDERLINEORDERLINE AS ORDERLINE,
            LISTAGG(CODE, ', ') WITHIN GROUP (ORDER BY CODE) AS PO_GREIGE
        FROM ITXVIEW_RAJUT
        WHERE TGLPOGREIGE IS NOT NULL
        GROUP BY ORIGDLVSALORDLINESALORDERCODE, ORIGDLVSALORDERLINEORDERLINE
    ) pg ON pg.SALESORDERCODE = i.SALESORDERCODE AND pg.ORDERLINE = i.ORDERLINE
    WHERE i.SALESORDERCODE = '$code'";

$stmt = db2_exec($conn1, $query);
$no = 1;
if ($stmt) {
    echo "<table class='table table-bordered table-striped' id='detailApprovedTable'>";
    echo "<thead>
            <tr>
                <th>No</th>
                <th>No PO</th>
                <th>Nama Buyer</th>
                <th>Jenis Kain</th>
                <th>Itemcode</th>
                <th>Notetas</th>
                <th>Gramasi</th>
                <th>Lebar</th>
                <th>Color Standard</th>
                <th>Benang</th>
                <th>Po Greige</th>
            </tr>
          </thead>";
    echo "<tbody>";

    while ($row = db2_fetch_assoc($stmt)) {
        $q_itxviewkk	= db2_exec($conn1, "SELECT * FROM ITXVIEWBONORDER i WHERE SALESORDERCODE = '$row[SALESORDERCODE]' AND ORDERLINE = '$row[ORDERLINE]'");
        $d_itxviewkk	= db2_fetch_assoc($q_itxviewkk);

        if($d_itxviewkk['ITEMTYPEAFICODE'] == 'KFF'){
            $subcode04 = $d_itxviewkk['RESERVATION_SUBCODE04'];
        }elseif ($d_itxviewkk['ITEMTYPEAFICODE'] == 'FKF') {
            $subcode04 = $d_itxviewkk['SUBCODE04'];
        }else{
            $subcode04 = $d_itxviewkk['SUBCODE04'];
        }
        
        $q_rajut	= db2_exec($conn1, "SELECT
                                            *
                                        FROM
                                            ITXVIEW_RAJUT
                                        WHERE
                                            SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
                                            AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
                                            AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
                                            AND SUBCODE04 = '$subcode04'
                                            AND ORIGDLVSALORDLINESALORDERCODE = '$row[SALESORDERCODE]'
                                            AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
        $d_rajut	= db2_fetch_assoc($q_rajut);
        $q_booking_blm_ready_1	= db2_exec($conn1, "SELECT
                                                        *
                                                    FROM
                                                        ITXVIEW_BOOKING_BLM_READY ibbr 
                                                    WHERE
                                                        SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
                                                        AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
                                                        AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
                                                        AND SUBCODE04 = '$subcode04'
                                                        AND ORIGDLVSALORDLINESALORDERCODE = '$d_itxviewkk[ADDITIONALDATA]'-- NGAMBIL DARI ADDITIONAL DATA 
                                                        AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
        $d_booking_blm_ready_1	= db2_fetch_assoc($q_booking_blm_ready_1);

        $q_booking_blm_ready_2	= db2_exec($conn1, "SELECT
																*
															FROM
																ITXVIEW_BOOKING_BLM_READY ibbr 
															WHERE
																SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
																AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
																AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
																AND SUBCODE04 = '$subcode04'
																AND ORIGDLVSALORDLINESALORDERCODE = '$d_itxviewkk[ADDITIONALDATA2]'-- NGAMBIL DARI ADDITIONAL DATA 
																AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
				$d_booking_blm_ready_2	= db2_fetch_assoc($q_booking_blm_ready_2);
				
				$q_booking_blm_ready_3	= db2_exec($conn1, "SELECT
																*
															FROM
																ITXVIEW_BOOKING_BLM_READY ibbr 
															WHERE
																SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
																AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
																AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
																AND SUBCODE04 = '$subcode04'
																AND ORIGDLVSALORDLINESALORDERCODE = '$d_itxviewkk[ADDITIONALDATA3]'-- NGAMBIL DARI ADDITIONAL DATA 
																AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
				$d_booking_blm_ready_3	= db2_fetch_assoc($q_booking_blm_ready_3);
				
				$q_booking_blm_ready_4	= db2_exec($conn1, "SELECT
																*
															FROM
																ITXVIEW_BOOKING_BLM_READY ibbr 
															WHERE
																SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
																AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
																AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
																AND SUBCODE04 = '$subcode04'
																AND ORIGDLVSALORDLINESALORDERCODE = '$d_itxviewkk[ADDITIONALDATA4]'-- NGAMBIL DARI ADDITIONAL DATA 
																AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
				$d_booking_blm_ready_4	= db2_fetch_assoc($q_booking_blm_ready_4);
				
				$q_booking_blm_ready_5	= db2_exec($conn1, "SELECT
																*
															FROM
																ITXVIEW_BOOKING_BLM_READY ibbr 
															WHERE
																SUBCODE01 = '$d_itxviewkk[SUBCODE01]'
																AND SUBCODE02 = '$d_itxviewkk[SUBCODE02]'
																AND SUBCODE03 = '$d_itxviewkk[SUBCODE03]'
																AND SUBCODE04 = '$subcode04'
																AND ORIGDLVSALORDLINESALORDERCODE = '$d_itxviewkk[ADDITIONALDATA4]'-- NGAMBIL DARI ADDITIONAL DATA 
																AND (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE = 'FKG')");
				$d_booking_blm_ready_5	= db2_fetch_assoc($q_booking_blm_ready_5);

				$q_booking_new	= db2_exec($conn1, "SELECT
														*
													FROM
														ITXVIEW_BOOKING_NEW ibn 
													WHERE
														SALESORDERCODE = '$row[SALESORDERCODE]'
														AND ORDERLINE = '$row[ORDERLINE]'");
				$d_booking_new	= db2_fetch_assoc($q_booking_new);
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . htmlspecialchars($row['NO_PO'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['LEGALNAME1'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['JENIS_KAIN'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['ITEMCODE'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['NOTETAS'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars(number_format($row['GRAMASI'] ?? 0, 2)) . "</td>";
        echo "<td>" . htmlspecialchars(number_format($row['LEBAR'] ?? 0, 2)) . "</td>";
        echo "<td>" . htmlspecialchars($row['COLOR_STANDARD'] ?? '') . "</td>";
        echo "<td>";
            if (!empty($d_rajut['SUMMARIZEDDESCRIPTION'])) {
                echo htmlspecialchars($d_rajut['SUMMARIZEDDESCRIPTION']);
            }
            if(!empty($d_booking_blm_ready_1['SUMMARIZEDDESCRIPTION'])){ 
                echo $d_booking_blm_ready_1['SUMMARIZEDDESCRIPTION'].' - '.$d_booking_blm_ready_1['ORIGDLVSALORDLINESALORDERCODE'].'&#13;&#10;'; 
            } 
            if(!empty($d_booking_blm_ready_2['SUMMARIZEDDESCRIPTION'])){ 
                echo $d_booking_blm_ready_2['SUMMARIZEDDESCRIPTION'].' - '.$d_booking_blm_ready_2['ORIGDLVSALORDLINESALORDERCODE'].'&#13;&#10;'; 
            } 
            if(!empty($d_booking_blm_ready_3['SUMMARIZEDDESCRIPTION'])){ 
                echo $d_booking_blm_ready_3['SUMMARIZEDDESCRIPTION'].' - '.$d_booking_blm_ready_3['ORIGDLVSALORDLINESALORDERCODE'].'&#13;&#10;'; 
            } 
            if(!empty($d_booking_blm_ready_4['SUMMARIZEDDESCRIPTION'])){ 
                echo $d_booking_blm_ready_4['SUMMARIZEDDESCRIPTION'].' - '.$d_booking_blm_ready_4['ORIGDLVSALORDLINESALORDERCODE'].'&#13;&#10;'; 
            } 
            if(!empty($d_booking_blm_ready_5['SUMMARIZEDDESCRIPTION'])){ 
                echo $d_booking_blm_ready_5['SUMMARIZEDDESCRIPTION'].' - '.$d_booking_blm_ready_5['ORIGDLVSALORDLINESALORDERCODE'].'&#13;&#10;'; 
            } 
            if(!empty($q_booking_new['SUMMARIZEDDESCRIPTION'])){ 
                echo $q_booking_new['SUMMARIZEDDESCRIPTION'].' - '.$q_booking_new['ORIGDLVSALORDLINESALORDERCODE'].'&#13;&#10;'; 
            } 
        echo "</td>";
        echo "<td>" . htmlspecialchars($row['PO_GREIGE'] ?? '') . "</td>";
    echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p class='text-danger'>Data tidak ditemukan.</p>";
}

?>
