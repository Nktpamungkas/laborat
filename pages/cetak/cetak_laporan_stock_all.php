<?php
    include '../../koneksi.php';

    $hostname = "10.0.0.21";
                             // $database = "NOWTEST"; // SERVER NOW 20
    $database    = "NOWPRD"; // SERVER NOW 22
    $user        = "db2admin";
    $passworddb2 = "Sunkam@24809";
    $port        = "25000";
    $conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
    // $conn1 = db2_pconnect($conn_string,'', '');
    $conn1 = db2_connect($conn_string, '', '');
    ini_set("error_reporting", 1);

    $id_barang = $_GET['id_barang'];
    $date1     = $_GET['tanggal_awal'];
    $date2     = $_GET['tanggal_akhir'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>::Laporan Stock All Laborat ::</title>
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
        <label style="font-size: 12px;"><u>DEPARTEMEN LABORAT</u></label><br>
        <label style="font-weight: bold; font-size: 12px;">Periode :                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <?php echo $date1; ?> s/d &nbsp;<?php echo $date2; ?><br>
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

            $query_barang = mysqli_query($con, "SELECT
                CONCAT_WS('-',
                    TRIM(s.DECOSUBCODE01),
                    TRIM(s.DECOSUBCODE02),
                    TRIM(s.DECOSUBCODE03),
                    TRIM(s.DECOSUBCODE04),
                    TRIM(s.DECOSUBCODE05),
                    TRIM(s.DECOSUBCODE06)
                ) AS KODE_BARANG,
                s.DESCRIPTION AS NAMA_BARANG,
                CASE
                    WHEN s.ITEMTYPECODE = 'SUP' THEN 'Supplies'
                    ELSE 'Sparepart'
                END AS KATEGORI,
                s.UNITOFMEASURE  AS UNIT,
                s.id AS ID_BARANG
            FROM tbl_master_barang  s
            ORDER BY KATEGORI ASC, NAMA_BARANG ASC");

        ?>
<?php
    $no = 1;
    while ($row_barang = mysqli_fetch_assoc($query_barang)):
?>
        <tr>
            <td style="text-align: center;"><?php echo $no++; ?></td>
            <!-- <td style="text-align: left; font-size: 10px;"><?php echo $row_barang['KODE_BARANG']; ?></td> -->
            <td style="text-align: left; font-size: 10px;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <?php echo(($row_barang['TRANSACTIONDATE'] >= $date1 && $row_barang['TRANSACTIONDATE'] <= $date2) ? 'background-color: yellow;' : ''); ?>">
                <?php echo $row_barang['KODE_BARANG']; ?>
            </td>
            <td style="text-align: left;"><?php echo $row_barang['NAMA_BARANG']; ?></td>
            <td style="text-align: center;"><?php echo $row_barang['KATEGORI']; ?></td>

            <td style="text-align: center;">
                &nbsp;
            </td><!-- STOK Min -->

            <td style="text-align: center;">
                <?php
                    $id_barang        = $row_barang['ID_BARANG'];
                    $query_stok_awal  = "SELECT * FROM tbl_master_barang where id='$id_barang' LIMIT 1";
                    $result_stok_awal = mysqli_query($con, $query_stok_awal);

                    $data_stok_awal = mysqli_fetch_assoc($result_stok_awal);

                    if ($data_stok_awal['STOCK']) {
                        $stok_awal = $data_stok_awal['STOCK'];
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
                                                               SUM(s.BASEPRIMARYQUANTITY) AS MASUK
                                                            FROM
                                                                STOCKTRANSACTION s
                                                            WHERE
                                                                s.LOGICALWAREHOUSECODE = 'M241'
                                                                AND TRIM(s.DECOSUBCODE01) || '-' ||
                                                                    TRIM(s.DECOSUBCODE02) || '-' ||
                                                                    TRIM(s.DECOSUBCODE03) || '-' ||
                                                                    TRIM(s.DECOSUBCODE04) || '-' ||
                                                                    TRIM(s.DECOSUBCODE05) || '-' ||
                                                                    TRIM(s.DECOSUBCODE06)  = '$row_barang[KODE_BARANG]'
                                                                AND (s.TEMPLATECODE = 'OPN' OR s.TEMPLATECODE = 'QCR')
                                                                AND (s.TRANSACTIONDATE) >= '$date1' AND (s.TRANSACTIONDATE) <= '$date2'
                                                            GROUP BY
                                                                s.USERPRIMARYUOMCODE
                                                                ");
                    $row_stok_masuk = db2_fetch_assoc($q_stok_masuk);
                    if ($row_stok_masuk['MASUK']) {
                        $stok_masuk = (int) $row_stok_masuk['MASUK'];
                    } else {
                        $stok_masuk = 0;
                    }
                    echo $stok_masuk;
                ?>
            </td><!-- STOK MASUK -->

            <td style="text-align: center;">
                <?php
                    $q_stok_keluar = db2_exec($conn1, "SELECT
                                                                SUM(s.USERPRIMARYQUANTITY) AS KELUAR
                                                            FROM
                                                                STOCKTRANSACTION s
                                                            WHERE
                                                                s.LOGICALWAREHOUSECODE = 'M241'
                                                                AND TRIM(s.DECOSUBCODE01) || '-' ||
                                                                    TRIM(s.DECOSUBCODE02) || '-' ||
                                                                    TRIM(s.DECOSUBCODE03) || '-' ||
                                                                    TRIM(s.DECOSUBCODE04) || '-' ||
                                                                    TRIM(s.DECOSUBCODE05) || '-' ||
                                                                    TRIM(s.DECOSUBCODE06)  = '$row_barang[KODE_BARANG]'
                                                                AND (s.TEMPLATECODE = '201')
                                                                AND (s.TRANSACTIONDATE) >= '$date1' AND (s.TRANSACTIONDATE) <= '$date2'");
                    $row_stok_keluar = db2_fetch_assoc($q_stok_keluar);
                    if ($row_stok_keluar['KELUAR']) {
                        $stok_keluar = (int) $row_stok_keluar['KELUAR'];
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
