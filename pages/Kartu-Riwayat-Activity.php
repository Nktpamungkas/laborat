<?php
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <style>
        #dataku {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 9pt !important;
            display: none; /* Tabel disembunyikan di awal */
        }

        #dataku td, #dataku th {
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

                    <button type="button" class="btn btn-primary" onclick="submitForm()">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box" id="boxDataku" style="display: none;"> <!-- BOX INI AKAN DISEMBUNYIKAN -->
                <div class="box-body">
                    <table width="100%" class="table table-bordered table-hover display" id="dataku">
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
                            <!-- Data akan dimuat dari kartu_riwayat_activity_data.php -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script>
        var dataTable;

        function submitForm() {
            var tanggalAwal = $("#tanggal_awal").val();
            var tanggalAkhir = $("#tanggal_akhir").val();

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

            // Kirim data ke server dengan AJAX
            $.ajax({
                url: "pages/ajax/kartu_riwayat_activity_data.php", // File PHP untuk mengambil data
                type: "POST",
                data: { tanggal_awal: tanggalAwal, tanggal_akhir: tanggalAkhir },
                dataType: "html",
                success: function (response) {
                    // Tampilkan box jika belum muncul
                    $("#boxDataku").show();
                    $("#dataku").show();

                    // Hapus DataTable lama jika sudah ada
                    if ($.fn.DataTable.isDataTable("#dataku")) {
                        dataTable.destroy();
                    }

                    // Masukkan hasil query ke dalam tabel
                    $("#dataku tbody").html(response);

                    // Inisialisasi ulang DataTable
                    dataTable = $("#dataku").DataTable();
                },
                error: function () {
                    alert("Gagal mengambil data!");
                }
            });
        }
    </script>

</body>
</html>
