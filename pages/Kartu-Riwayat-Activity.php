<?PHP
    ini_set("error_reporting", 1);
    session_start();
    include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Riwayat Activity</title>
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
    <script>
        function submitForm() {
            var tanggalAwal = document.getElementById("tanggal_awal").value;
            var tanggalAkhir = document.getElementById("tanggal_akhir").value;

            if (tanggalAwal === "") {
                alert("Tanggal awal tidak boleh kosong!");
                return;
            }
            if (tanggalAkhir === "") {
                alert("Tanggal akhir tidak boleh kosong!");
                return;
            }

            if (tanggalAkhir < tanggalAwal) {
                alert("Tanggal akhir tidak boleh lebih kecil dari tanggal awal!");
                return;
            }

            var actionPage = (idBarang === "all") ? "pages/cetak/cetak_laporan_stock_all.php" : "pages/cetak/cetak_kartu_stock.php";
            var url = actionPage + "?tanggal_awal=" + tanggalAwal + "&tanggal_akhir=" + tanggalAkhir;

            window.open(url, '_blank');
        }
    </script>
</head>

<body>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <center><h4><strong>Kartu Riwayat Activity</strong></h4></center>
                </div>
                <div class="box-body">

                    <div class="form-group">
                        <label for="tanggal_awal">Tanggal Awal</label>
                        <input type="date" class="form-control" id="tanggal_awal">
                    </div>

                    <div class="form-group">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="tanggal_akhir">
                    </div>

                    <button type="submit" class="btn btn-primary" onclick="submitForm()">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table width="100%" class="table table-bordered table-hover display" id="dataku" style="border: 1px solid #595959; padding:5px;">
                        <thead class="btn-primary">
                            <tr>
                                <th width="5%" style="text-align: center;">No</th>
                                <th width="15%" style="text-align: center;">No. BD</th>
                                <th width="10%" style="text-align: center;">Start Date</th>
                                <th width="10%" style="text-align: center;">End Date</th>
                                <th width="50%" style="text-align: center;">Remarks</th>
                                <th width="10%" style="text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $breakdownentrycode = $row_breakdown_header['BREAKDOWNENTRYCODE'];
                                $q_mesinLAB         = db2_exec($conn1, "SELECT * FROM PMWORKORDERDETAIL WHERE ASSIGNEDTOUSERID ='clivi.lab' ");
                                $no                 = 1;
                                while ($value = db2_fetch_assoc($q_mesinLAB)) {
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="text-center"><?php echo $breakdownentrycode ?></td>
                                    <td class="text-center"><?php echo date('Y-m-d H:i:s', strtotime($value['STARTDATE'])); ?></td>
                                    <td class="text-center"><?php echo date('Y-m-d H:i:s', strtotime($value['ENDDATE'])); ?></td>
                                    <td class="text-center"><?php echo $value['REMARKS'] ?></td>
                                    <td class="text-center">
                                        <?php
                                            $statusMap = [
                                                    0 => "Open",
                                                    1 => "Assigned",
                                                    2 => "In Progress",
                                                    3 => "Closed",
                                                    4 => "Suspended",
                                                    5 => "Canceled",
                                                ];

                                                echo isset($statusMap[$value['STATUS']]) ? $statusMap[$value['STATUS']] : "Unknown";
                                            ?>
                                    </td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
