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
    <title>Laporan & Kartu Stock</title>
    <script>
        function submitForm() {
            var idBarang = document.getElementById("nama_barang").value;
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
            var url = actionPage + "?id_barang=" + idBarang + "&tanggal_awal=" + tanggalAwal + "&tanggal_akhir=" + tanggalAkhir;

            window.open(url, '_blank');
        }
    </script>
</head>

<body>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <center><h4><strong>Laporan & Kartu Stock Laborat</strong></h4></center>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <select class="form-control" id="nama_barang">
                            <option value="all">All</option>
                            <?php
                                $query  = "SELECT id, description FROM tbl_master_barang";
                                $result = mysqli_query($con, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['description'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>

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
