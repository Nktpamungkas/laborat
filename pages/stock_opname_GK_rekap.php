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

    .btn-sm{
        min-width:35px;
    }

    .tgl{
        max-width:160px;
    }

    .jam{
        max-width: 80px;
    }
    
    .kategori{
        max-width:160px;
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

.jumbroton{
    background-color: #e9ecef;
    border-radius: .3rem;
    padding: 10px;
}

.mb0{
    margin-bottom :0px !important;
}
.mr25{
    margin-left: 25px !important;
}
.pd0{
    padding-left : 0px !important;
}
.w100{
    width: 100% !important;
}
.va-top{
    vertical-align: top;
}
.padTopBot5{
    padding-top:5px;
    padding-bottom:5px;
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
                            <div class="col-sm-3 col-md-2" >
                                <label for="tgl_tutup">Tanggal Tutup Buku</label>
                                <input type="date" class="form-control tgl" id="tgl_tutup" placeholder="Tanggal Tutup Buku" name="tgl_tutup">                       
                            </div>
                            <div class="col-sm-9 col-md-10" >
                                <label for="tgl_stk_op">Tanggal dan Jam Stock Opname</label>
                                <div style="display: flex; gap: 10px;">
                                    <input type="date" class="form-control tgl" id="tgl_stk_op" placeholder="Tanggal Stock Opname" name="tgl_stk_op"> 
                                    <input name="jam_stk_op" type="text" class="form-control jam" id="jam_stk_op"
                                        placeholder="00:00" pattern="[0-9]{2}:[0-9]{2}$"
                                        title=" e.g 14:25" onkeyup="
                                                        var time = this.value;
                                                        if (time.match(/^\d{2}$/) !== null) {
                                                            this.value = time + ':';
                                                        } else if (time.match(/^\d{2}\:\d{2}$/) !== null) {
                                                            this.value = time + '';
                                                        }" value="" size="5" maxlength="5" >
                                    <select class="form-control kategori" aria-label="Default select example" id="kategori" >
                                        <option value="" readonly>Pilih Kategori</option>
                                        <option value="DYESTUFF">DYESTUFF</option>
                                        <option value="CHEMICAL">CHEMICAL</option>
                                    </select>
                                    <button name="submit" class="btn btn-primary btn-sm cariData" style="display:block"><i class="icofont icofont-search-alt-1"></i> Cari data</button>
                                    <button name="exportData" class="btn btn-success btn-sm exportData" style="display:block"><i class="icofont icofont-search-alt-1"></i> Export data</button>
                                </div>
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
                    <h5>REKAP STOCK OPNAME GUDANG KIMIA</h5>
                </div>
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">    
                        <div id="tabelDetail">
                            Silahkan Pilih Tanggal Tutup Buku, Tanggal dan Jam Stock Opname dan Kategori
                        </div>                                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
    
  $(document).ready(function () {
    $(document).on('click', '.cariData', function() {
        let tgl_tutup = $("#tgl_tutup").val();
        let tgl_stk_op = $("#tgl_stk_op").val();
        let jam_stk_op = $("#jam_stk_op").val();
        let kategori = $("#kategori").val();
        if(validasi()==false){
            return true;
        }
        $('#tabelDetail').html('<p>Loading data...</p>');
        $.ajax({
            url: 'pages/ajax/stock_opname_gk_rekap_stock_opname.php',
            type: 'POST',
            data: { tgl_tutup: tgl_tutup, tgl_stk_op: tgl_stk_op , jam_stk_op: jam_stk_op, kategori: kategori , akses: '<?=$_SESSION['jabatanLAB']?>'},
            success: function(response) {
                $('#tabelDetail').html(response);
                if ($.fn.DataTable.isDataTable('#detailmasukTable')) {
                    console.log('Destroying existing DataTable');
                    $('#detailmasukTable').DataTable().destroy();
                }
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
    $(document).on('click', '.exportData', function() {
        let tgl_tutup = $("#tgl_tutup").val();
        let tgl_stk_op = $("#tgl_stk_op").val();
        let jam_stk_op = $("#jam_stk_op").val();
        let kategori = $("#kategori").val();
        if(validasi()==false){
            return true;
        }
        let url="pages/cetak/cetak_stock_opname_gk_rekap.php?tgl_tutup="+encodeURIComponent($("#tgl_tutup").val())+"&tgl_stk_op="+encodeURIComponent($("#tgl_stk_op").val())+"&jam_stk_op="+encodeURIComponent($("#jam_stk_op").val())+"&kategori="+encodeURIComponent($("#kategori").val())+"";
        window.open(url, "_blank");
    });
    //logic function
    function validasi(){
        if($("#tgl_tutup").val()==""){
            Swal.fire({
                title: 'Form Tidak Lengkap',
                text: 'Silahkan Isi Tanggal Tutup Buku',
                icon: 'warning'
            })
            return false;
        }else if($("#tgl_stk_op").val()==""){
            Swal.fire({
                title: 'Form Tidak Lengkap',
                text: 'Silahkan Isi Tanggal Stock Opname',
                icon: 'warning'
            })
            return false;
        }else if($("#jam_stk_op").val()==""){
            Swal.fire({
                title: 'Form Tidak Lengkap',
                text: 'Silahkan Isi Jam Stock Opname',
                icon: 'warning'
            })
            return false;
        }else if($("#kategori").val()==""){
            Swal.fire({
                title: 'Form Tidak Lengkap',
                text: 'Silahkan Pilih Kategori',
                icon: 'warning'
            })
            return false;
        } 
        let jam=$("#jam_stk_op").val();
        if(jam.length!=5){
            Swal.fire({
                title: 'Format Jam Salah',
                text: 'Silahkan Isi Jam Stock Opname Seperti 01:01',
                icon: 'warning'
            })
            return false;
        }
        return true;
    }
    //helper function
    function formatAngka(val){
        var Num=val;
        Num += '';
        Num = Num.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1').replace(/^0[^.]/, '0');
        return Num;
    }
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
    function ucfirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1)
    }

  });
</script>