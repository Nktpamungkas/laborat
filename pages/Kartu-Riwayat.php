<?PHP
    ini_set("error_reporting", 1);
    session_start();
    include "koneksi.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Kartu Riwayat</title>
</head>
<style>
    #dataku {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        font-size: 9pt !important;
    }

    #dataku td,

    #dataku th {
        border: 1px solid #ddd;
        padding: 4px;
    }

    #dataku tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #dataku tr:hover {
        background-color: rgb(151, 170, 212);
    }

    #dataku th {
        padding-top: 10px;
        padding-bottom: 10px;
        text-align: left;
        background-color: #337AB7;
        color: white;
    }
</style>

<body>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <center><h4><strong>Kartu Riwayat Mesin Laborat</strong></h4></center>
                </div>
                <div class="box-body">
                    <table width="100%" class="table table-bordered table-hover display" id="dataku" style="border: 1px solid #595959; padding:5px;">
                        <thead class="btn-primary">
                            <tr>
                                <th width="5%">No</th>
                                <th width="28%">Kode Mesin</th>
                                <th width="57%">Nama Mesin</th>
                                <th width="15%" style="text-align: center;">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php

                    $q_mesinLAB = db2_exec($conn1, "SELECT
                                    p.CODE AS KODE_MESIN,
                                    p.LONGDESCRIPTION AS NAMA_MESIN
                                    FROM
                                        PMBOM p
                                    LEFT JOIN PMBREAKDOWNENTRY pbe  ON p.CODE = pbe.PMBOMCODE
                                    WHERE pbe.COUNTERCODE ='PBD007'");
                    $no = 1;
                    while ($value = db2_fetch_assoc($q_mesinLAB)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $value['KODE_MESIN'] ?></td>
                        <td><?php echo $value['NAMA_MESIN'] ?></td>
                        <td class="text-center">
                            <a href="pages/cetak/cetak_kartu_riwayat.php?kode=<?php echo $value['KODE_MESIN'] ?>" target="_blank" rel="noopener noreferrer">
                                <i class="fa fa-print"></i> Print
                            </a>
                        </td>
                    </tr>
                    <?php }?>

                </tbody>
                    </table>
</body>
</html>

<script>
    $(document).ready(function () {
        $('#dataku').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthMenu": [ 10, 25, 50, 100],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>

