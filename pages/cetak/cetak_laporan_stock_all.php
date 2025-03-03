<?php
    $hostname = "10.0.0.21";
                             // $database = "NOWTEST"; // SERVER NOW 20
    $database    = "NOWPRD"; // SERVER NOW 22
    $user        = "db2admin";
    $passworddb2 = "Sunkam@24809";
    $port        = "25000";
    $conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
    // $conn1 = db2_pconnect($conn_string,'', '');
    $conn1 = db2_connect($conn_string, '', '');
    ini_set("error_reporting", 0);
?>
<!DOCTYPE html>
<html>
<head>
    <title>::DIT - Laporan Stock Spare Part:</title>
    <link rel="shortcut icon" href="img/logo_ITTI.ico">
</head>
<style>
    body {
      background: white;
    }
    page[size="A4"] {
      background: white;
      width: 21cm;
      height: 29.7cm;
      display: block;
      margin: 0 auto;
      margin-bottom: 0.5cm;
      box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
    }

    @media print {
      body, page[size="A4"] {
        margin: 0;
        box-shadow: 0;
      }
    }
    table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 0.1px;
            text-align: center;
            font-size: 12px;
        }
        table#t01 tr:nth-child(even) {
            background-color: white;
        }
        table#t01 tr:nth-child(odd) {
           background-color: white;
        }
        table#t01 th {
            background-color: black;
            color: white;
        }
</style>
<body>
<!-- <body onload="print();"> -->
<!-- <page size="A4">  -->
    <table width="100%" border="1">
        <label style="font-weight: bold; font-size: 12px;">LAPORAN STOCK </label><br>
        <label style="font-size: 12px;"><u>DEPARTEMEN DIT</u></label><br>
        <label style="font-weight: bold; font-size: 12px;">Periode :                                                                     <?php echo $date1; ?> s/d<?php echo $date2; ?><br>
        <tr>
            <td width="30">NO</td>
            <td width="180">KODE BARANG</td>
            <td width="400">JENIS BARANG</td>
            <td width="75">KATEGORI</td>
            <td width="75">STOK Min</td>
            <td width="75">STOK AWAL</td>
            <td width="75">UNIT</td>
            <td width="75">MASUK</td>
            <td width="75">KELUAR</td>
            <td width="75">UNIT</td>
            <td width="75">STOK AKHIR</td>
            <td width="100">CATATAN</td>
        </tr>
        <?php
            $query_barang = db2_exec($conn1, "WITH RankedData AS (
            SELECT
                TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' ||
                TRIM(s.DECOSUBCODE03) || '-' || TRIM(s.DECOSUBCODE04) || '-' ||
                TRIM(s.DECOSUBCODE05) || '-' || TRIM(s.DECOSUBCODE06) AS KODE_BARANG,
                p.LONGDESCRIPTION AS NAMA_BARANG,
                CASE
                    WHEN LOWER(p.LONGDESCRIPTION) LIKE '%server%' THEN 'Server'
                    WHEN LOWER(p.LONGDESCRIPTION) LIKE '%rusak%' THEN 'Rusak'
                    ELSE 'Sparepart'
                END AS KATEGORI,
                CASE
                    WHEN s.USERPRIMARYUOMCODE = 'Rol' THEN s.BASEPRIMARYUOMCODE
                    WHEN s.USERPRIMARYUOMCODE = 'm' THEN s.BASEPRIMARYUOMCODE
                    WHEN s.USERPRIMARYUOMCODE = 'ft' THEN s.BASEPRIMARYUOMCODE
					WHEN s.USERPRIMARYUOMCODE = 'BGS' THEN s.BASEPRIMARYUOMCODE
                    ELSE s.USERPRIMARYUOMCODE
                END AS UNIT,
                ROW_NUMBER() OVER (PARTITION BY TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' ||
                    TRIM(s.DECOSUBCODE03) || '-' || TRIM(s.DECOSUBCODE04) || '-' ||
                    TRIM(s.DECOSUBCODE05) || '-' || TRIM(s.DECOSUBCODE06) ORDER BY s.TRANSACTIONDATE) AS RowNum
            FROM
                STOCKTRANSACTION s
            LEFT JOIN PRODUCT p ON
                p.ITEMTYPECODE = s.ITEMTYPECODE
                AND p.SUBCODE01 = s.DECOSUBCODE01
                AND p.SUBCODE02 = s.DECOSUBCODE02
                AND p.SUBCODE03 = s.DECOSUBCODE03
                AND p.SUBCODE04 = s.DECOSUBCODE04
                AND p.SUBCODE05 = s.DECOSUBCODE05
                AND p.SUBCODE06 = s.DECOSUBCODE06
            RIGHT JOIN BALANCE b ON
                b.ITEMTYPECODE = p.ITEMTYPECODE
                AND b.DECOSUBCODE01 = p.SUBCODE01
                AND b.DECOSUBCODE02 = p.SUBCODE02
                AND b.DECOSUBCODE03 = p.SUBCODE03
                AND b.DECOSUBCODE04 = p.SUBCODE04
                AND b.DECOSUBCODE05 = p.SUBCODE05
                AND b.DECOSUBCODE06 = p.SUBCODE06
            WHERE
                s.ITEMTYPECODE = 'SPR'
                AND s.DECOSUBCODE01 = 'DIT'
                AND (s.TEMPLATECODE IN ('101', 'OPN', 'QCT', '201', '098'))
                AND (s.TRANSACTIONDATE  BETWEEN '$date1' AND '$date2' OR s.TRANSACTIONDATE NOT BETWEEN '$date1' AND '$date2' )
        )
        SELECT
            KODE_BARANG,
            NAMA_BARANG,
            KATEGORI,
            UNIT
        FROM
            RankedData
        WHERE
            RowNum = 1
        ORDER BY
        KATEGORI ASC,
        NAMA_BARANG ASC;
        ");

        ?>
<?php $no = 1;while ($row_barang = db2_fetch_assoc($query_barang)): ?>
        <tr>
            <td style="text-align: center;"><?php echo $no++; ?></td>
            <!-- <td style="text-align: left; font-size: 10px;"><?php echo $row_barang['KODE_BARANG']; ?></td> -->
            <td style="text-align: left; font-size: 10px;                                                                                                                   <?php echo(($row_barang['TRANSACTIONDATE'] >= $date1 && $row_barang['TRANSACTIONDATE'] <= $date2) ? 'background-color: yellow;' : ''); ?>">
    <?php echo $row_barang['KODE_BARANG']; ?>
</td>
            <td style="text-align: left;"><?php echo $row_barang['NAMA_BARANG']; ?></td>
            <td style="text-align: center;"><?php echo $row_barang['KATEGORI']; ?></td>

            <td style="text-align: center;">
                <?php
                    if ($row_barang['NAMA_BARANG'] == 'Kabel LAN Cat. 5e Indoor' && $row_barang['KATEGORI'] == 'Sparepart' && $row_barang['KODE_BARANG'] == 'DIT-NETWK-LOC-CABLE5E---INDOOR-FT') {
                        $stock_min = 200;
                    } else if ($row_barang['KATEGORI'] == 'Sparepart' && $row_barang['KODE_BARANG'] == 'DIT-NETWK-LOC-RJ45---PLASTIC') {
                        $stock_min = 10;
                    } else if ($row_barang['KATEGORI'] == 'Sparepart' && $row_barang['KODE_BARANG'] == 'DIT-PERIF-LOC-BATTERY---UPS' || $row_barang['KODE_BARANG'] == 'DIT-PERIF-LOC-KEYBOARD---USB'
                        || $row_barang['KODE_BARANG'] == 'DIT-CORE-LOC-LAPTOP-LENOVO-THINK-E14' || $row_barang['KODE_BARANG'] == 'DIT-MONITOR-LOC-19INCH-LG--' || $row_barang['KODE_BARANG'] == 'DIT-PERIF-LOC-MOUSE----'
                        || $row_barang['KODE_BARANG'] == 'DIT-CORE-LOC-PSU-DAZUMBA-PC' || $row_barang['KODE_BARANG'] == 'DIT-PRINT-LOC-LABEL-NORSEL-BP700' || $row_barang['KODE_BARANG'] == 'DIT-NETWK-LOC-CONVERT-TP-LINK--'
                        || $row_barang['KODE_BARANG'] == 'DIT-NETWK-LOC-SFP-UBIQUITI-UACC-SM-1G' || $row_barang['KODE_BARANG'] == 'DIT-NETWK-LOC-SWITCH-UNIFI-SW48' || $row_barang['KODE_BARANG'] == 'DIT-NETWK-LOC-SWITCH-UNIFI-SW8POE'
                        || $row_barang['KODE_BARANG'] == 'DIT-CORE-LOC-DDR3----' || $row_barang['KODE_BARANG'] == 'DIT-CPU-LOC-I5-ASUS--'
                    ) {
                        $stock_min = 2;
                    } else {
                        $stock_min = '';
                    }

                    // && $row_barang['KODE_BARANG'] == ''

                    echo $stock_min;
                ?>
            </td><!-- STOK Min -->

            <td style="text-align: center;">
                <?php
                    if ($date1 == '2024-01-10') {
                        $where_date = "AND (s.TRANSACTIONDATE) = '$date1'";
                    } else {
                        $tanggal_hasil = date("Y-m-d", strtotime($date1 . " -1 day"));
                        $where_date    = "AND (s.TRANSACTIONDATE) BETWEEN '2024-01-10' AND '$tanggal_hasil'";
                        // $where_date = "AND (s.TRANSACTIONDATE) BETWEEN '2024-01-10' AND $tanggal_hasil  = date("Y-m-d", strtotime($date1 . " -1 day"));";
                        // $where_date = "AND (s.TRANSACTIONDATE) >= '2024-01-10' AND (s.TRANSACTIONDATE) <= '$date1' + INTERVAL 1 DAY";

                        // $where_date = "AND (s.TRANSACTIONDATE) BETWEEN '$date1' AND '$date2'";
                        // $where_date = "AND (s.TRANSACTIONDATE) >= '2024-01-10' AND (s.TRANSACTIONDATE) <= '$date1'";
                        // $where_date = "AND (s.TRANSACTIONDATE) >= '2024-01-10' AND (s.TRANSACTIONDATE) <= '$date1'";
                        // $where_date = "AND ((s.TRANSACTIONDATE) = '$date1' OR ((s.TRANSACTIONDATE) BETWEEN '2024-01-10' AND '$date1'))";
                    }
                    // $q_stok_awal    = db2_exec($conn1, "SELECT
                    //                                         floor(SUM(s.USERPRIMARYQUANTITY)) AS STOK_AWAL
                    //                                     FROM
                    //                                         STOCKTRANSACTION s
                    //                                     WHERE
                    //                                         s.ITEMTYPECODE ='SPR'
                    //                                         AND s.DECOSUBCODE01 = 'DIT'
                    //                                         AND TRIM(s.DECOSUBCODE01) || '-' ||
                    //                                             TRIM(s.DECOSUBCODE02) || '-' ||
                    //                                             TRIM(s.DECOSUBCODE03) || '-' ||
                    //                                             TRIM(s.DECOSUBCODE04) || '-' ||
                    //                                             TRIM(s.DECOSUBCODE05) || '-' ||
                    //                                             TRIM(s.DECOSUBCODE06)  = '$row_barang[KODE_BARANG]'
                    //                                         AND (s.TEMPLATECODE = '101' OR s.TEMPLATECODE = 'OPN' OR s.TEMPLATECODE = 'QCT')
                    //                                         $where_date");

                    $q_stok_awal = db2_exec($conn1, "SELECT
                                                            SUM(QTY_AWAL) AS STOK_AWAL
                                                        FROM
                                                            (
                                                            SELECT
                                                                CASE
                                                                    WHEN s.TEMPLATECODE = '101'
                                                                    OR s.TEMPLATECODE = 'OPN'
                                                                    OR s.TEMPLATECODE = 'QCT' THEN
                                                                    CASE
                                                                        WHEN TRIM(s.BASEPRIMARYUOMCODE) = 'm' OR TRIM(s.BASEPRIMARYUOMCODE) = 'un' THEN floor(SUM(s.BASEPRIMARYQUANTITY))
                                                                        ELSE floor(SUM(s.USERPRIMARYQUANTITY))
                                                                    END
                                                                    WHEN s.TEMPLATECODE = '201'OR s.TEMPLATECODE = '098' THEN -
                                                                        CASE
                                                                            WHEN TRIM(s.BASEPRIMARYUOMCODE) = 'm' THEN floor(SUM(s.BASEPRIMARYQUANTITY))
                                                                            ELSE floor(SUM(s.USERPRIMARYQUANTITY))
                                                                        END
                                                                END AS QTY_AWAL
                                                            FROM
                                                                STOCKTRANSACTION s
                                                            LEFT JOIN PRODUCT p ON
                                                                p.ITEMTYPECODE = s.ITEMTYPECODE
                                                                AND p.SUBCODE01 = s.DECOSUBCODE01
                                                                AND p.SUBCODE02 = s.DECOSUBCODE02
                                                                AND p.SUBCODE03 = s.DECOSUBCODE03
                                                                AND p.SUBCODE04 = s.DECOSUBCODE04
                                                                AND p.SUBCODE05 = s.DECOSUBCODE05
                                                                AND p.SUBCODE06 = s.DECOSUBCODE06
                                                            WHERE
                                                                s.ITEMTYPECODE = 'SPR'
                                                                AND s.DECOSUBCODE01 = 'DIT'
                                                                AND TRIM(s.DECOSUBCODE01) || '-' ||
                                                                                                                TRIM(s.DECOSUBCODE02) || '-' ||
                                                                                                                TRIM(s.DECOSUBCODE03) || '-' ||
                                                                                                                TRIM(s.DECOSUBCODE04) || '-' ||
                                                                                                                TRIM(s.DECOSUBCODE05) || '-' ||
                                                                                                                TRIM(s.DECOSUBCODE06) = '$row_barang[KODE_BARANG]'
                                                                AND (s.TEMPLATECODE = '101'
                                                                    OR s.TEMPLATECODE = 'OPN'
                                                                    OR s.TEMPLATECODE = 'QCT'
                                                                    OR s.TEMPLATECODE = '201'
                                                                    OR s.TEMPLATECODE = '098')
                                                            $where_date
                                                            GROUP BY
                                                                s.TEMPLATECODE,
                                                                s.BASEPRIMARYUOMCODE)");

                    $row_stok_awal = db2_fetch_assoc($q_stok_awal);
                    if ($row_stok_awal['STOK_AWAL']) {
                        $stok_awal = $row_stok_awal['STOK_AWAL'];
                    } else {
                        $stok_awal = 0;
                    }
                    echo $stok_awal;
                ?>
            </td><!-- STOK AWAL -->


            <td style="text-align: center;"><?php echo $row_barang['UNIT']; ?></td>

            <td style="text-align: center;">
                <?php

                    $q_stok_masuk = db2_exec($conn1, "SELECT
                                                                CASE
                                                                    WHEN s.USERPRIMARYUOMCODE = 'Rol' THEN floor(SUM(s.BASEPRIMARYQUANTITY))
                                                                    ELSE floor(SUM(s.BASEPRIMARYQUANTITY))
                                                                END	AS MASUK
                                                            FROM
                                                                STOCKTRANSACTION s
                                                            WHERE
                                                                s.ITEMTYPECODE ='SPR'
                                                                AND s.DECOSUBCODE01 = 'DIT'
                                                                AND TRIM(s.DECOSUBCODE01) || '-' ||
                                                                    TRIM(s.DECOSUBCODE02) || '-' ||
                                                                    TRIM(s.DECOSUBCODE03) || '-' ||
                                                                    TRIM(s.DECOSUBCODE04) || '-' ||
                                                                    TRIM(s.DECOSUBCODE05) || '-' ||
                                                                    TRIM(s.DECOSUBCODE06)  = '$row_barang[KODE_BARANG]'
                                                                AND (s.TEMPLATECODE = '101' OR s.TEMPLATECODE = 'OPN' OR s.TEMPLATECODE = 'QCT')
                                                                AND (s.TRANSACTIONDATE) >= '$date1' AND (s.TRANSACTIONDATE) <= '$date2'
                                                            GROUP BY
                                                                s.USERPRIMARYUOMCODE
                                                                ");
                    $row_stok_masuk = db2_fetch_assoc($q_stok_masuk);
                    if ($row_stok_masuk['MASUK']) {
                        $stok_masuk = $row_stok_masuk['MASUK'];
                    } else {
                        $stok_masuk = 0;
                    }
                    echo $stok_masuk;
                ?>
            </td><!-- STOK MASUK -->

            <td style="text-align: center;">
                <?php
                    $q_stok_keluar = db2_exec($conn1, "SELECT
                                                                floor(SUM(s.USERPRIMARYQUANTITY)) AS KELUAR
                                                            FROM
                                                                STOCKTRANSACTION s
                                                            WHERE
                                                                s.ITEMTYPECODE ='SPR'
                                                                AND s.DECOSUBCODE01 = 'DIT'
                                                                AND TRIM(s.DECOSUBCODE01) || '-' ||
                                                                    TRIM(s.DECOSUBCODE02) || '-' ||
                                                                    TRIM(s.DECOSUBCODE03) || '-' ||
                                                                    TRIM(s.DECOSUBCODE04) || '-' ||
                                                                    TRIM(s.DECOSUBCODE05) || '-' ||
                                                                    TRIM(s.DECOSUBCODE06)  = '$row_barang[KODE_BARANG]'
                                                                AND (s.TEMPLATECODE = '201' OR s.TEMPLATECODE = '098')
                                                                AND (s.TRANSACTIONDATE) >= '$date1' AND (s.TRANSACTIONDATE) <= '$date2'");
                    $row_stok_keluar = db2_fetch_assoc($q_stok_keluar);
                    if ($row_stok_keluar['KELUAR']) {
                        $stok_keluar = $row_stok_keluar['KELUAR'];
                    } else {
                        $stok_keluar = 0;
                    }
                    echo $stok_keluar;
                ?>
            </td><!-- STOK KELUAR -->

            <td style="text-align: center;"><?php echo $row_barang['UNIT']; ?></td>

            <td style="text-align: center;"><?php echo $stok_awal + $stok_masuk - $stok_keluar; ?></td>

            <td style="text-align: center;"></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <table width="100%" border="1">
        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>DIBUAT OLEH</td>
            <td>DIPERIKSA OLEH</td>
            <td>DISETUJUI OLEH</td>
        </tr>
        <tr>
            <td style="text-align: left;">NAMA</td>
            <td><input style="border:0;outline:0; font-size: 11px;" type=text placeholder="Ketik disini" size="20" maxlength="30"></td>
            <td><input style="border:0;outline:0; font-size: 11px;" type=text placeholder="Ketik disini" size="20" maxlength="30"></td>
            <td><input style="border:0;outline:0; font-size: 11px;" type=text placeholder="Ketik disini" size="20" maxlength="30"></td>
        </tr>
        <tr>
            <td style="text-align: left;">JABATAN</td>
            <td><input style="border:0;outline:0; font-size: 11px;" type=text placeholder="Ketik disini" size="20" maxlength="30"></td>
            <td><input style="border:0;outline:0; font-size: 11px;" type=text placeholder="Ketik disini" size="20" maxlength="30"></td>
            <td><input style="border:0;outline:0; font-size: 11px;" type=text placeholder="Ketik disini" size="20" maxlength="30"></td>
        </tr>
        <tr>
            <td style="text-align: left;">TANGGAL</td>
            <td><input style="border:0;outline:0;" type=date size="20"></td>
            <td><input style="border:0;outline:0;" type=date size="20"></td>
            <td><input style="border:0;outline:0;" type=date size="20"></td>
        </tr>
        <tr>
            <td style="text-align: left;" valign="top">TANDA TANGAN</td>
            <td></td>
            <td></td>
            <td height="50"></td>
        </tr>
    </table>
<!-- </page> -->
</body>
</html>
