<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
?>
<?php
// Set nilai-nilai $_POST ke dalam session saat formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['tgl'] = $_POST['tgl'];
    $_SESSION['warehouse'] = $_POST['warehouse'];
}
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>LAB - Tutup Harian Gd. Kimia</title>
</head>
<style>
    .modal-backdrop {
    z-index: 1040 !important;
    }
    .modal {
    z-index: 1050 !important;
    }
    td.details-control {
        background: url('bower_components/DataTable/img/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('bower_components/DataTable/img/details_close.png') no-repeat center center;
    }

    th {
        font-size: 10pt;
    }

    td {
        font-size: 9pt;
    }

    #Table-sm td,
    #Table-sm th {
        border: 0.1px solid #ddd;
    }

    #Table-sm th {
        color: black;
        background: #4CAF50;
    }

    #Table-sm_filter label input.form-control {
        width: 500px;
    }

    #Table-sm tr:hover {
        background-color: rgb(151, 170, 212);
    }

    #Table-sm>thead>tr>td {
        border: 1px solid #ddd;
    }

    .btn-circle {
        border-radius: 10px;
        color: black;
        font-weight: 800;
    }

    .btn-grp>a,
    .btn-grp>button {
        margin-top: 2px;
    }
</style>
<style>
.modal {
  display: none; 
  position: fixed; 
  z-index: 999; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4); 
}

.modal-content {
  background-color: #fefefe;
  margin: 5% auto;
  padding: 20px;
  border-radius: 6px;
  width: 60%;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.close {
  color: #aaa;
  float: right;
  font-size: 24px;
  font-weight: bold;
  cursor: pointer;
}
#Table-obat tbody tr:hover {
    background-color: #f2f9ff; /* biru muda */
    cursor: pointer;
}
#Table-obat.table-bordered th,
#Table-obat.table-bordered td {
    border: 1px solid #6c757d; /* abu tua, bisa diganti hitam (#000) */
}

.modal-dialog.modal-custom {
    max-width: 95%;  /* bisa kamu ubah ke 90%, 98%, dll */
    width: 95%;
    margin: 30px auto;
}

.btn-fixed {
        display: inline-block;
        width: 100px; /* kamu bisa ubah jadi 80px atau 90px sesuai keinginan */
        text-align: center;
        padding: 6px 0;
    }

    td {
        text-align: center; /* agar tombol di tengah kolom */
        vertical-align: middle;
    }

    .btn-fixed {
    display: inline-block;
    min-width: 100px;
    text-align: center;
    
}

.modal-dialog {
    width: 80% !important;
    max-width: 80% !important;
    margin: 30px auto;
}

.modal-content {
    width: 100%;
    padding: 20px;
    border-radius: 6px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Buat tinggi menyesuaikan data, dan hanya scroll kalau di layar kecil */
@media (max-height: 600px) {
    .modal-body {
        max-height: 80vh;
        overflow-y: auto;
    }
}

</style>
<body>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> Filter Data</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i></button>
                    </div>
                </div>                 
                <form action="" method="post">
                <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-2" style="display: flex; gap: 10px;">
                            <input type="date" class="form-control" required
                                    placeholder="Tanggal Awal" name="tgl"
                                    value="<?php if (isset($_POST['submit'])) {
                                        echo $_POST['tgl'];
                                    } ?>"
                                    required>                            
                            </div>
                                
                            <div class="col-sm-2">
                            <button type="submit" name="submit"
                                class="btn btn-primary btn-sm"><i
                                    class="icofont icofont-search-alt-1"></i> Cari data</button>
                            </div>                            
                        </div>
                    </div>                    
                </form>           
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">                
                <div class="box-header with-border">
                <div class="card-header table-card-header">
                    <h5>LAPORAN PEMAKAIAN OBAT GUDANG KIMIA PERKATEGORI</h5>
                </div>
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">                                          
                            <table id="Table-obat" class="table table-bordered table-hover" style="width: 100%;">                       
                                <thead>
                                    <tr>
                                    <th><center>No</center></th>
                                    <th><center>Action</center></th>
                                    <th><center>Detail</center></th>
                                    <th><center>Tgl Tutup</center></th>
                                    <th><center>Warehouse</center></th>
                                    <th><center>Qty (Kg)</center></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                $tutup_transaksi = mysqli_query($con, "SELECT ITEMTYPECODE, 
                                                                                    LOGICALWAREHOUSECODE, 
                                                                                    tgl_tutup, 
                                                                                    SUM(BASEPRIMARYQUANTITYUNIT) as total_qty 
                                                                                    FROM tblopname_11
                                                                                    where  tgl_tutup = '$_POST[tgl]' 
                                                                                    and not kode_obat = 'E-1-000'
                                                                                    GROUP BY LOGICALWAREHOUSECODE, 
                                                                                    tgl_tutup");
                                while ($row = mysqli_fetch_array($tutup_transaksi)) {
                                    $tgl_tutup = $row['tgl_tutup'];
                                    $warehouse = $row['LOGICALWAREHOUSECODE'];
                                    $total_qty = (substr(number_format($row['total_qty'], 2), -3) == '.00')
                                        ? number_format($row['total_qty'], 0)
                                        : number_format($row['total_qty'], 2);
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><a href="pages/cetak/DetailOpnameDetail11Excel.php?tgl=<?= htmlspecialchars($tgl_tutup) ?>&tipe=<?= htmlspecialchars($warehouse) ?>" 
                                            class="btn btn-danger btn-sm" target="_blank"> <i class="fa fa-excel"></i> Lihat Data</a> || 
                                            <a href="pages/cetak/DetailOpnameProses11Excel.php?tgl=<?= htmlspecialchars($tgl_tutup) ?>&tipe=<?= htmlspecialchars($warehouse) ?>"
                                            class="btn btn-primary btn-sm" target="_blank"> <i class="fa fa-file-excel"></i> Excel</a></td>
                                            <td>
                                                <a href="#" class="btn btn-success btn-sm open-detail2"
                                                    data-tgl_tutup="<?= htmlspecialchars($tgl_tutup) ?>"
                                                    data-warehouse="<?= htmlspecialchars($warehouse) ?>" data-toggle="modal"
                                                    data-target="#detailModal_masuk">
                                                    Lihat Detail
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($tgl_tutup) ?></td>
                                            <td><?= htmlspecialchars($warehouse) ?></td>
                                            <td><?= $total_qty ?></td>
                                        </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Modal Detail -->
<div id="detailModal_masuk" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="width: 80%; max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail QTY Tutup Harian</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div id="modal-content_masuk" class="table-responsive">
                    <p class="text-muted text-center">Menunggu data...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>



</html>
<script>
  $(document).ready(function () {
    $('#Table-obat').DataTable({
      ordering: false,
      pageLength: 25,
      responsive: true,
      language: {
        searchPlaceholder: "Search..."
      }
    });
  });

$(document).on('click', '.open-detail2', function() {
            var tgl_tutup = $(this).data('tgl_tutup');
            var warehouse = $(this).data('warehouse');

        $('#modal-content_masuk').html('<p>Loading data...</p>');

        $.ajax({
        url: 'pages/ajax/tutup_harian_detail.php',
        type: 'POST',
        data: { tgl_tutup: tgl_tutup, warehouse: warehouse },
        success: function(response) {
            console.log('Response received');
            $('#modal-content_masuk').html(response);

            if ($.fn.DataTable.isDataTable('#detailmasukTable')) {
                console.log('Destroying existing DataTable');
                $('#detailmasukTable').DataTable().destroy();
            }
            console.log('Initializing DataTable');
            $('#detailmasukTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                order: [[0, 'asc']]
            });
        },
        error: function() {
            $('#modal-content_masuk').html('<p class="text-danger">Gagal memuat data.</p>');
        }
        });
    });



</script>