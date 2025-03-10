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
</body>
</html>
