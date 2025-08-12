<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
?>
<?php
// Set nilai-nilai $_POST ke dalam session saat formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $_SESSION['tgl'] = $_POST['tgl'];
    // $_SESSION['warehouse'] = $_POST['warehouse'];
}
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Stock Opname Gd. Kimia</title>
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
                <!-- <form action="" method="post"> -->
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-5" style="display: flex; gap: 10px;">
                                <input type="date" class="form-control" id="tgl_tutup" placeholder="Tanggal Awal" name="tgl_tutup"> 
                                <select class="form-select" aria-label="Default select example" id="warehouse">
                                    <option value="" readonly>Pilih Gudang</option>
                                    <option value="M101">M101</option>
                                    <option value="M510">M510</option>
                                </select>                           
                            </div>
                            <div class="col-sm-2">
                                <button name="submit" class="btn btn-primary btn-sm cariData"><i class="icofont icofont-search-alt-1"></i> Cari data</button>
                            </div>                            
                        </div>
                    </div>                    
                <!-- </form>            -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">                
                <div class="box-header with-border">
                <div class="card-header table-card-header">
                    <h5>STOCK OPNAME GUDANG KIMIA</h5>
                </div>
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">    
                        <div id="tabelDetail">
                            Silahkan Pilih Tanggal Tutup Buku dan Warehouse
                        </div>                                    
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
                <h4 class="modal-title">Detail Stock Opname</h4>
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
    var cari_data=false;
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

    $(document).on('click', '.cariData', function() {
        let tgl_tutup = $("#tgl_tutup").val();
        let warehouse = $("#warehouse").val();
        if(tgl_tutup==""){
            Swal.fire({
                title: 'Form Tidak Lengkap',
                text: 'Silahkan Isi Tanggal Tutup Buku',
                icon: 'warning'
            })
        }else if(warehouse==""){
            Swal.fire({
                title: 'Form Tidak Lengkap',
                text: 'Silahkan Pilih Warehouse',
                icon: 'warning'
            })
        }
        $('#tabelDetail').html('<p>Loading data...</p>');
        $.ajax({
            url: 'pages/ajax/stock_opname_gk_detail.php',
            type: 'POST',
            data: { tgl_tutup: tgl_tutup, warehouse: warehouse },
            success: function(response) {
                $('#tabelDetail').html(response);
                if ($.fn.DataTable.isDataTable('#detailmasukTable')) {
                    console.log('Destroying existing DataTable');
                    $('#detailmasukTable').DataTable().destroy();
                }
                cari_data=true;
                $('#detailmasukTable').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    order: [[0, 'asc']]
                });
            },
            error: function() {
                $('#tabelDetail').html('<p class="text-danger">Gagal memuat data.</p>');
            }
        });
    });


$(document).on('keyup', '.qty_dus', function(e) {
    let val=formatAngka($(this).val());
    this.value= val;
    let parent=$(this).parent().parent();
    let id = parent.data('id');
    let pu = parent.data('pu');
    
    let total_stock=Number(val)*pu;
    total_stock=total_stock.toFixed(2);

    $("#ts_"+id).html(nilaiKeRibuan(total_stock, ".",","));
    parent.data('ts',total_stock);
});

function formatAngka(val){
    var Num=val;
    Num += '';
    Num = Num.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1').replace(/^0[^.]/, '0');
    return Num;
}

$(document).on('click', '.confirm', function() {
    let parent=$(this).parent().parent();
    let id = parent.data('id');
    let dataPost={status:"konfirmasi",id_dt: id};
    $.ajax({
        url: 'pages/ajax/stock_opname_gk_ajax.php',
        type: 'POST',
        data: dataPost,
        dataType: "JSON",
        success: function(response) {
            if(response.success){ 
                let idconfirm=response.messages[1];
                $("#confirm_"+idconfirm).html("<i class='fa fa-check' aria-hidden='true'></i> OK");
                Swal.fire({
                    title: 'Saved',
                    text: 'Berhasil Konfirmasi',
                    icon: 'success',
                    timer: 1000,
                    position : 'top-end',
                    showConfirmButton: false
                })
            }else{
                alert("Terjadi Error Update, mohon hubungi DIT");
            }
        },
        error: function() {
            alert("Jaringan Terputus, Gagal Confirm");
        }
    });
});

function numberWithCommas(x,ribu) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ribu);
}

function nilaiKeRibuan(angka,ribuan,koma){
    try{
        let angkaFloat=parseFloat(angka);
        let str = String(angkaFloat).split(".");
        if(str.length==2){
            return numberWithCommas(str[0],ribuan)+koma+str[1];
        }else{
            return numberWithCommas(str[0],ribuan);
        }
    }
    catch (e) {
            return angka;
    }
}

function checkData(){
    let tgl_tutup = $("#tgl_tutup").val();
    let warehouse = $("#warehouse").val();
    if(tgl_tutup!="" && warehouse!=""){
        let sts="cek_data";
        if(warehouse=="M510"){
            sts="cek_data_m510";
        }
        $.ajax({
            url: 'pages/ajax/stock_opname_gk_ajax.php',
            type: 'POST',
            data: {status:sts, tgl_tutup: tgl_tutup, warehouse: warehouse },
            success: function(response) {
                if(response.success){
                    $.each( response.data, function( key, value ) {
                        $("#td_dus_"+value.id).html(value.qty_dus);
                        $("#ts_"+value.id).html(value.total_stock);
                        $("#ps_"+value.id).html(value.pakingan_standar);
                        $("#confirm_"+value.id).html(value.konfirm);
                    });
                }else{
                    cari_data=false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Mohon Maaf anda sudah logout, Silahkan Refresh halaman.',
                        buttons: [
                            'Refresh Halaman'
                        ],
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        } else {
                            location.reload();
                        }
                    });
                }
            },
            error: function() {
            }
        });
    }  
}

setInterval(function(){
    if (cari_data){
        checkData()
    }
}, 3000);
</script>