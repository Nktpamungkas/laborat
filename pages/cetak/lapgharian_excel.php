<?php
include "./../../koneksi.php";

// Ambil parameter tanggal dari URL
$awalParam  = $_GET['awal']  ?? '';
$Bln2  = (new DateTime($awalParam))->format('m');
$Thn2  = (new DateTime($awalParam))->format('Y');

$Bulan			= $Thn2."-".$Bln2;
$namaFile = "Laporan harian gudang-{$Bulan}.xls";

header("Content-type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$namaFile\"");
header("Pragma: no-cache");
header("Expires: 0");

// $bulanTahun = strtoupper(date("F Y", strtotime($awalParam)));
$d = cal_days_in_month(CAL_GREGORIAN, $Bln2, $Thn2);
if($Thn2!="" and $Bln2!=""){
$Lalu 		= $Bln2-1;	
	if($Lalu=="0"){
	$BlnLalu="12";
	$Thn=$Thn2-1;	
	}else{
	$BlnLalu=$Lalu;
	}	
}
function namabln($b){
if($b=="01" or $b=="1"){ $Nbln="Januari";}
if($b=="02" or $b=="2"){ $Nbln="Febuari";}
if($b=="03" or $b=="3"){ $Nbln="Maret";}
if($b=="04" or $b=="4"){ $Nbln="April";}
if($b=="05" or $b=="5"){ $Nbln="Mei";}
if($b=="06" or $b=="6"){ $Nbln="Juni";}
if($b=="07" or $b=="7"){ $Nbln="Juli";}
if($b=="08" or $b=="8"){ $Nbln="Agustus";}
if($b=="09" or $b=="9"){ $Nbln="September";}
if($b=="10"){ $Nbln="Oktober";}
if($b=="11"){ $Nbln="November";}
if($b=="12"){ $Nbln="Desember";}	
	return $Nbln;
}

$total = 0;



?>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<body>

    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
        <!-- Logo (optional) -->
        <!--
        <div style="margin-right: 20px;">
            <img src="dist/img/indo.png" alt="Logo" style="height: 80px;">
        </div>
        -->

        <!-- Judul -->
        <div style="text-align: center;">
            <h3 style="margin: 0;"><strong>LAPORAN HARIAN GUDANG KAIN GREIGE</strong></h3>
            <h5 style="margin: 10px 0;"><strong>BULAN <?php if($Bln2!="01"){echo namabln($Bln2)." ".$Thn2;}else{echo namabln($Bln2)." ".$Thn;} ?></strong></h5>
            <h5 style="margin: 10px 0;"><strong>No Form : FW-19-GKG-11/03</strong></h5>
            <h5 style="margin: 10px 0;"><strong>Halaman :</strong></h5>
        </div>
    </div>
<body>
    <?php 
    if($Bln2!="01"){
		if(strlen($BlnLalu)==1){$bl0="0".$BlnLalu;}else{$bl0=$BlnLalu;}
		$BlnLL=$Thn2."-".$bl0;
}else{
    if(strlen($BlnLalu)==1){$bl0="0".$BlnLalu;}else{$bl0=$BlnLalu;}
    $BlnLL=$Thn."-".$bl0;
}
$sql = sqlsrv_query($con," SELECT TOP 1 
												tgl_tutup, 
												SUM(rol) AS rol, 
												SUM(weight) AS kg 
											FROM 
												dbnow_gkg.tblopname 
											WHERE 
												FORMAT(tgl_tutup, 'yyyy-MM') = '$BlnLL' 
											GROUP BY 
												tgl_tutup 
											ORDER BY 
												tgl_tutup DESC;
 ");		  
    $r = sqlsrv_fetch_array($sql);

    $stokmati = sqlsrv_query($con, "WITH MaxTutup AS (
				SELECT 
					(SELECT MAX(tgl_tutup) 
					FROM Stock_mati_gkg 
					WHERE proj_awal LIKE '%/%' 
					AND FORMAT(tgl_tutup, 'yyyy-MM') = '$Bulan') AS tgl_bulan_ini,
					
					(SELECT MAX(tgl_tutup) 
					FROM Stock_mati_gkg 
					WHERE proj_awal LIKE '%/%' 
					AND FORMAT(tgl_tutup, 'yyyy-MM') = '$BlnLL') AS tgl_bulan_lalu
			)
			SELECT 
				(SELECT SUM(kgs) 
				FROM Stock_mati_gkg 
				WHERE proj_awal LIKE '%/%' 
				AND tgl_tutup = tgl_bulan_ini) AS total_bulan_ini,

				(SELECT SUM(kgs) 
				FROM Stock_mati_gkg 
				WHERE proj_awal LIKE '%/%' 
				AND tgl_tutup = tgl_bulan_lalu) AS total_bulan_lalu
			FROM MaxTutup
		");
$stokmatiT = sqlsrv_fetch_array($stokmati);


$stokAwal = round($r['kg'] ?? 0, 2) - round($stokmatiT['total_bulan_lalu'] ?? 0, 2);
?>

<!-- <div align="LEFT">TGL : <?php echo date($_GET['tanggal1']); ?></div> -->
<table width="125%" border="1" align="Center">
    <tr>
        <td colspan="3"><strong>SISA STOCK BULAN <?php if($Bln2!="01"){echo namabln($BlnLalu)." ".$Thn2;}else{echo namabln($BlnLalu)." ".$Thn;} ?></strong></td>
        <td><strong>KAIN I</strong></td>
        <td align="right"><strong><?php echo $stokAwal ?></strong></td>
        <td><strong>KAIN II</strong></td>
        <td></td>
        <td><strong>TOTAL</strong></td>
        <td colspan="2"><strong><?php echo $stokAwal ?></strong></td>
    </tr>
</table>
<table></table>
<table width="125%" border="1" align="Center">
    <!-- Header: Masuk, Keluar, Retur -->
    <tr>
        <td rowspan="2" style="text-align: center;"><strong>TGL.</strong></td>
        <td colspan="2" style="text-align: center;"><strong>MASUK</strong></td>
        <td colspan="2" style="text-align: center;"><strong>KELUAR</strong></td>
        <td colspan="4" style="text-align: center;"><strong>RETUR</strong></td>
        <td rowspan="2" style="text-align: center;"><strong>SISA</strong></td>
    </tr>

    <!-- Sub-header: Masuk, Keluar, Retur Detail -->
    <tr>
        <td><strong>KAIN MASUK I</strong></td>
        <td><strong>KAIN MASUK II</strong></td>
        <td><strong>KAIN BAGI I</strong></td>
        <td><strong>KAIN BAGI II</strong></td>
        <td><strong>DALAM</strong></td>
        <td><strong>SEBAB</strong></td>
        <td><strong>LUAR</strong></td>
        <td><strong>SEBAB</strong></td>
    </tr>

</table>
<table width="125%" border="1" align="Center">


<?php
$tM = $tN = $tK = $tKR = $tR = $tP = $tS = 0;
$tbulansekarang = 0;
$Tbagi1 = 0;
$Tbagi2 = 0;
$Tbg2 = 0;
$rMati = 0;
$Tmat = 0;

for ($i = 1; $i <= $d; $i++) {
    $tgl = sprintf("%04d-%02d-%02d", $Thn2, $Bln2, $i);


    $masuk = " SELECT 
        (
            -- QTY KG
            (
                SELECT COALESCE(SUM(STOCKTRANSACTION.WEIGHTNET), 0)
                FROM INTERNALDOCUMENT
                LEFT JOIN INTERNALDOCUMENTLINE ON
                    INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE
                    AND INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE
                    AND INTERNALDOCUMENTLINE.DESTINATIONWAREHOUSECODE = 'M021'
                LEFT JOIN STOCKTRANSACTION ON
                    INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE = STOCKTRANSACTION.ORDERCODE
                    AND INTERNALDOCUMENTLINE.ORDERLINE = STOCKTRANSACTION.ORDERLINE
                WHERE
                    STOCKTRANSACTION.TEMPLATECODE = '204'
                    AND STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M021'
                    AND STOCKTRANSACTION.TRANSACTIONDATE = '$Thn2-$Bln2-$i'
                    AND INTERNALDOCUMENTLINE.ORDERLINE IS NOT NULL
            )
            +
            -- QTY NONFK
            (
                SELECT COALESCE(SUM(s.BASEPRIMARYQUANTITY), 0)
                FROM STOCKTRANSACTION s
                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.NAMENAME = 'StatusRetur'
                WHERE
                    s.TRANSACTIONDATE = '$Thn2-$Bln2-$i'
                    AND s.ITEMTYPECODE = 'KGF'
                    AND s.LOGICALWAREHOUSECODE = 'M021'
                    AND s.TEMPLATECODE = 'OPN'
                    AND a.VALUESTRING = '1'
            )
            +
            -- QTY FK
            (
                SELECT COALESCE(SUM(st.BASEPRIMARYQUANTITY), 0)
                FROM INTERNALDOCUMENT
                LEFT JOIN INTERNALDOCUMENTLINE ON
                    INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE
                    AND INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE
                LEFT JOIN STOCKTRANSACTION st ON
                    INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE = st.ORDERCODE
                    AND INTERNALDOCUMENTLINE.ORDERLINE = st.ORDERLINE
                WHERE
                    st.TEMPLATECODE = '204'
                    AND st.LOGICALWAREHOUSECODE = 'M021'
                    AND st.TRANSACTIONDATE = '$Thn2-$Bln2-$i'
                    AND INTERNALDOCUMENTLINE.ORDERLINE IS NOT NULL
                    AND INTERNALDOCUMENTLINE.SUBCODE02 IN ('FKP', 'FKY', 'FJQ')
            )
            +
            -- QTY CWD
            (
                SELECT COALESCE(SUM(s.BASEPRIMARYQUANTITY), 0)
                FROM STOCKTRANSACTION s
                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.NAMENAME = 'StatusRetur'
                WHERE 
                    s.TRANSACTIONDATE = '$Thn2-$Bln2-$i'
                    AND s.ITEMTYPECODE = 'KGF'
                    AND s.LOGICALWAREHOUSECODE = 'M021'
                    AND s.TEMPLATECODE = 'OPN'
                    AND a.VALUESTRING = '2'
                    AND s.PROJECTCODE LIKE '%CWD%'
            )
        ) AS TOTAL_QTY_MASUK
        FROM SYSIBM.SYSDUMMY1;
    ";

    $stmtmasuk = db2_exec($conn1, $masuk, ['cursor' => DB2_SCROLLABLE]);
	$rMasuk = db2_fetch_assoc($stmtmasuk);


    // $kainMati = "SELECT
    //         t.TRANSACTIONDATE,
    //         SUM(t.QTY_KG) AS TOTAL_KG
    //     FROM (
    //         SELECT
    //             STOCKTRANSACTION.TRANSACTIONDATE,
    //             STOCKTRANSACTION.LOTCODE,
    //             SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG
    //         FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
    //         LEFT JOIN (
    //             SELECT
    //                 ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE,
    //                 LISTAGG(DISTINCT TRIM(ITXVIEWHEADERKNTORDER.ORIGDLVSALORDLINESALORDERCODE), ', ') AS ORIGDLVSALORDLINESALORDERCODE,
    //                 LISTAGG(DISTINCT TRIM(ITXVIEWHEADERKNTORDER.PRODUCTIONDEMANDCODE), ', ') AS PRODUCTIONDEMANDCODE,
    //                 LISTAGG(DISTINCT TRIM(ITXVIEWHEADERKNTORDER.PROJECTCODE), ', ') AS PROJECTCODE,
    //                 LISTAGG(DISTINCT TRIM(ITXVIEWHEADERKNTORDER.LEGALNAME1), ', ') AS LEGALNAME1
    //             FROM DB2ADMIN.ITXVIEWHEADERKNTORDER
    //             GROUP BY ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE
    //         ) ITXVIEWHEADERKNTORDER ON ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE = STOCKTRANSACTION.ORDERCODE
    //         LEFT JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER
    //             ON STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
    //         WHERE 
    //             STOCKTRANSACTION.ITEMTYPECODE IN ('KGF', 'FKG') AND
    //             STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M021' AND
    //             STOCKTRANSACTION.ONHANDUPDATE > 1 AND
    //             STOCKTRANSACTION.TRANSACTIONDATE = '$Thn2-$Bln2-$i' AND
    //             STOCKTRANSACTION.ORDERCODE IS NOT NULL AND
    //             STOCKTRANSACTION.LOTCODE LIKE '%/%'
    //         GROUP BY 
    //             STOCKTRANSACTION.TRANSACTIONDATE,
    //             STOCKTRANSACTION.LOTCODE
    //     ) t
    //     GROUP BY 
    //         t.TRANSACTIONDATE
    //     ORDER BY 
    //         t.TRANSACTIONDATE"
    // ;

    $kainMati = "SELECT
    t.TRANSACTIONDATE,
    SUM(t.QTY_KG) AS TOTAL_KG
FROM (
    -- Bagian 1: Query awal
    SELECT
        STOCKTRANSACTION.TRANSACTIONDATE,
        SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG
    FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
    LEFT JOIN (
        SELECT
            ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE,
            LISTAGG(DISTINCT TRIM(ITXVIEWHEADERKNTORDER.ORIGDLVSALORDLINESALORDERCODE), ', ') AS ORIGDLVSALORDLINESALORDERCODE,
            LISTAGG(DISTINCT TRIM(ITXVIEWHEADERKNTORDER.PRODUCTIONDEMANDCODE), ', ') AS PRODUCTIONDEMANDCODE,
            LISTAGG(DISTINCT TRIM(ITXVIEWHEADERKNTORDER.PROJECTCODE), ', ') AS PROJECTCODE,
            LISTAGG(DISTINCT TRIM(ITXVIEWHEADERKNTORDER.LEGALNAME1), ', ') AS LEGALNAME1
        FROM DB2ADMIN.ITXVIEWHEADERKNTORDER
        GROUP BY ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE
    ) ITXVIEWHEADERKNTORDER ON ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE = STOCKTRANSACTION.ORDERCODE
    LEFT JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER
        ON STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
    WHERE 
        STOCKTRANSACTION.ITEMTYPECODE IN ('KGF', 'FKG') AND
        STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M021' AND
        STOCKTRANSACTION.ONHANDUPDATE > 1 AND
        STOCKTRANSACTION.TRANSACTIONDATE = '$Thn2-$Bln2-$i' AND
        STOCKTRANSACTION.ORDERCODE IS NOT NULL AND
        STOCKTRANSACTION.LOTCODE LIKE '%/%'
    GROUP BY 
        STOCKTRANSACTION.TRANSACTIONDATE,
        STOCKTRANSACTION.LOTCODE

    UNION ALL

    -- Bagian 2: Query tambahan
    SELECT 
        s.TRANSACTIONDATE,
        SUM(s.BASEPRIMARYQUANTITY) AS QTY_KG
    FROM DB2ADMIN.STOCKTRANSACTION s
    LEFT JOIN DB2ADMIN.ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.NAMENAME = 'StatusPotong'
    LEFT JOIN DB2ADMIN.ADSTORAGE a1 ON a1.UNIQUEID = s.ABSUNIQUEID AND a1.NAMENAME = 'NoteMintaPotong'
    WHERE 
        s.ITEMTYPECODE = 'KGF' AND
        s.LOGICALWAREHOUSECODE = 'M021' AND
        s.TEMPLATECODE = '098' AND
        (a.VALUESTRING IN ('1', '2', '3')) AND
        s.TRANSACTIONDATE = '$Thn2-$Bln2-$i' AND
        s.LOTCODE LIKE '%/%'
    GROUP BY 
        s.TRANSACTIONDATE,
        s.LOTCODE

) t
GROUP BY 
    t.TRANSACTIONDATE
ORDER BY 
    t.TRANSACTIONDATE
";

    $stmtMati = db2_exec($conn1, $kainMati, ['cursor' => DB2_SCROLLABLE]);
	$KMati = db2_fetch_assoc($stmtMati);

    // Masuk II - projectcode LIKE '%CWD%'
    $rMMasuk = sqlsrv_fetch_array(sqlsrv_query($con, " SELECT SUM(berat) AS kg 
        FROM 
            dbnow_gkg.tblmasukkain 
        WHERE 
            tgl_tutup = '$tgl' AND no_bon IS NULL AND projectcode LIKE '%CWD%'"));

    // Keluar I
    $sqlKeluar = sqlsrv_query($con," SELECT tgl_tutup, 
        SUM(qty) AS rol, 
        SUM(berat) AS kg 
        FROM 
            dbnow_gkg.tblkeluarkain 
        WHERE 
            tgl_tutup = '$Thn2-$Bln2-$i' 
        -- AND demand IS NOT NULL 
        GROUP BY 
            tgl_tutup
    ");		  
    $rKeluar = sqlsrv_fetch_array($sqlKeluar);

    // Retur dalam
   $sqlRMasuk = sqlsrv_query($con, " SELECT tgl_tutup, 
	    SUM(qty) AS rol, 
	    SUM(berat) AS kg 
        FROM 
            dbnow_gkg.tblmasukkain 
        WHERE 
            tgl_tutup = '$Thn2-$Bln2-$i'
        AND no_bon IS NULL 
        -- AND mesin_rajut = 'maklun' 
        AND (
            projectcode LIKE '%CWD%' 
            OR projectcode IS NULL
        )
        AND (
            mesin_rajut IS NULL 
            OR mesin_rajut = 'retur'
            )
        GROUP BY tgl_tutup"
    );
	$rRMasuk = sqlsrv_fetch_array($sqlRMasuk);

    $sqlRKeluar = sqlsrv_query($con, " SELECT tgl_tutup, 
        SUM(qty) AS rol, 
        SUM(berat) AS kg 
        FROM 
            dbnow_gkg.tblkeluarkain 
        WHERE 
            tgl_tutup = '$Thn2-$Bln2-$i'
        AND demand IS NOT NULL 
		AND projectcode LIKE '%CWD%'
        GROUP BY 
            tgl_tutup"
    );
	$rRKeluar = sqlsrv_fetch_array($sqlRKeluar);

    // Retur luar
    $rPotong = sqlsrv_fetch_array(sqlsrv_query($con, "
        SELECT SUM(berat) AS kg 
        FROM dbnow_gkg.tblkeluarkain 
        WHERE tgl_tutup = '$tgl' AND demand IS NULL"));

   $m1 = ($rMasuk['TOTAL_QTY_MASUK'] ?? 0) - ($rRMasuk['kg'] ?? 0);

    $m2 = $rMMasuk['kg']  ?? 0;
    $k1 = $rKeluar['kg']  ?? 0;
    $k2 = $rRKeluar['kg'] ?? 0;
    $r1 = $rRMasuk['kg']  ?? 0;
    $r2 = $rPotong['kg']  ?? 0;
    $rMati = $KMati['TOTAL_KG'] ?? 0;

   $Tbagi1 = ($rKeluar['kg'] ?? 0) - ($rRKeluar['kg'] ?? 0) - $rMati;
  


    $tM  += $m1;
    $tN  += $m2;
    $tK  += $k1;
    $tKR += $k2;
    $tR  += $r1;
    $Tbg2 += $Tbagi1;
    $Tmat += $rMati;
    // $tP  += $r2;
    //  $Tbagi2 = ($rKeluar['kg'] ?? 0) - $Tbg2;
    $tbulansekarang = ($stokAwal + $tM + $tN) - ($tK + $tKR);

    echo "<tr>
    <td>$tgl</td>
    <td align='right'>" . number_format($m1, 2) . "</td>
    <td align='right'>" . number_format($r1, 2) . "</td>
    <td align='right'>" . number_format($Tbagi1, 2) . "</td>
    <td align='right'>" . number_format($k2, 2) . "</td>
    <td align='right'>&nbsp;</td>
    <td align='right'>&nbsp;</td
    <td align='right'>&nbsp;</td>
    <td align='right'>&nbsp;</td
    <td align='right'><strong>" . number_format($tbulansekarang, 2) . "</strong></td>
</tr>\n";

}
?>
      <tfoot>
            <tr>
                <td><strong>Total</strong></td>
                <td align="right"><strong><?= number_format($tM, 2) ?></strong></td>
                <td align="right"><strong><?= number_format($tR, 2) ?></strong></td>
                <td align="right"><strong><?= number_format($Tbg2, 2) ?></strong></td> <!-- Ini sudah total semua $Tbagi1 -->
                <td align="right"><strong><?= number_format($tKR, 2) ?></strong></td>
                <td align="right"><strong></strong></td>
                <td align="right"><strong></strong></td>
                <td align="right"><strong></strong></td>
                <td align="right"><strong></strong></td>
                <?php $totalAll = ($stokAwal + $tM + $tR) - ($Tbg2 + $tKR); ?>
                <td align="right"><strong><?= number_format($totalAll, 2) ?></strong></td>
            </tr>
            </tfoot>

    </table>

    
<table></table>
<table></table>

<table style="width: auto;" border="1">
     <tr>
  <td colspan="3"></td>
  <td colspan="2" style="text-align: center; vertical-align: middle;">Dibuat Oleh :</td>
  <td colspan="3" style="text-align: center; vertical-align: middle;">Diperiksa Oleh :</td>
  <td colspan="2" style="text-align: center; vertical-align: middle;">Mengetahui :</td>
</tr>
<tr>
  <td colspan="3" style="text-align: center; vertical-align: middle;">Nama</td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"></td>
</tr>
<tr>
  <td colspan="3" style="text-align: center; vertical-align: middle;">Jabatan</td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"></td>
</tr>
<tr>
  <td colspan="3" style="text-align: center; vertical-align: middle;">Tanggal</td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"></td>
</tr>
<tr>
  <td colspan="3" style="text-align: center; vertical-align: middle;">Tanda Tangan</td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"><br><br><br><br></td>
  <td colspan="3" style="text-align: center; vertical-align: middle;"></td>
  <td colspan="2" style="text-align: center; vertical-align: middle;"></td>
</tr>
</table>

</table>

</body>
</html>
