<?PHP
    ini_set("error_reporting", 1);
    session_start();
    include "koneksi.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan & Kartu Stock</title>
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
                    <center><h4><strong>Laporan & Kartu Stock Laborat</strong></h4></center>
                </div>
                <div class="box-body">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <select class="form-control" id="nama_barang">
                                <option value="all">All</option>
                                <option>Barang 1</option>
                                <option>Barang 2</option>
                                <option>Barang 3</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_awal">Tanggal Awal</label>
                            <input type="date" class="form-control" id="tanggal_awal" placeholder="dd / mm / yyyy">
                        </div>

                        <div class="form-group">
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="tanggal_akhir" placeholder="dd / mm / yyyy">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>
</div>
</div>
</body>
</html>
