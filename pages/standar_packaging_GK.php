<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
include "includes/Penomoran_helper.php";

$username = $_SESSION['userLAB'];
if($username!="dit"){
    echo "<center><h1>Tidak ada Akses";
    exit();
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
    .error {
        color: red !important;
    }
    .form-control.error {
        color: black !important;
    }
    .error2 {
        color: red !important;
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
    margin: 30px auto;
}

.w80{
    width: 80% !important;
    max-width: 80% !important;
}

.modal-content {
    width: 100%;
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
                    <div class="card-header table-card-header">
                        <h5>Standar Packaging</h5>
                    </div>
                    <div class="col-lg-12">
                        <div class="col-lg-12 text-right" style="margin-bottom: 5px;">
                            <button name="addData" id="addData" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Data</button> 
                        </div>
                    </div>
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">   
                        <div id="standartPackaging">
                            <?php
                                $standar_packaging = mysqli_query($con,"select * from tbl_standar_packaging") ;
                                $no = 1;
                            ?>
                            <table class='table table-bordered table-striped' id='standartPackagingTable'>
                                <thead>
                                    <tr>
                                        <th class='text-center' rowspan='2'>No</th>
                                        <th class='text-center' rowspan='2'>KODE OBAT ERP NOW</th>
                                        <th class='text-center' rowspan='2'>NAMA DAN JENIS BAHAN KIMIA/DYESTUFF</th>
                                        <th class='text-center' rowspan='2'>PACKINGAN (GR) UTUH</th>
                                        <th class='text-center' colspan='4'>PACKINGAN BUKAAN</th>
                                        <th class='text-center' rowspan='2'>JENIS</th>
                                        <th class='text-center' rowspan='2' style='min-width: 91px;'>Action</th>
                                    </tr>
                                    <tr>
                                        <th class='text-center'>TINGGI PACKINGAN UTUH (CM)</th>
                                        <th class='text-center'>BERAT JENIS</th>
                                        <th class='text-center'>BERAT PACKINGAN (GR)</th>
                                        <th class='text-center'>BERAT PACKINGAN BOTOL KECIL (GR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $button="
                                        <button class='btn btn-warning btn-sm edit_sp' title='Edit' data-toggle='tooltip' ><i class='fa fa-pencil'></i></button> 
                                        <button class='btn btn-danger btn-sm delete_sp' title='Delete' data-toggle='tooltip' ><i class='fa fa-trash'></i></button> 
                                        <button class='btn btn-success btn-sm preview_sp' title='Preview' data-toggle='tooltip' ><i class='fa fa-list'></i></button>";
                                    while ($row = mysqli_fetch_assoc($standar_packaging)) {
                                        echo "<tr id='tr_".$row['id']."' data-id='" .$row['id']. "' data-no='" .$no. "'>
                                                <td class='text-center'>".$no++."</td>
                                                <td>" . htmlspecialchars($row['kode_erp']) . "</td>
                                                <td>" . htmlspecialchars($row['nama_obat']) . "</td>
                                                <td>" . Penomoran_helper::nilaiKeRibuanStandarPackage($row['pakingan_utuh']) . "</td>
                                                <td>" . Penomoran_helper::nilaiKeRibuanStandarPackage($row['tinggi_pakingan']) . "</td>
                                                <td>" . Penomoran_helper::nilaiKeRibuanStandarPackage($row['bj_pakingan']) . "</td>
                                                <td>" . Penomoran_helper::nilaiKeRibuanStandarPackage($row['berat_pakingan']) . "</td>
                                                <td>" . Penomoran_helper::nilaiKeRibuanStandarPackage($row['berat_pakingan_botol_kecil']) . "</td>
                                                <td>" . htmlspecialchars($row['jenis']) . "</td>
                                                <td>" . $button . "</td>
                                            </tr>";     
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>                                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- Modal Detail -->
<div id="modal_sp" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered w80" role="document" style="width: 80%; max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_sp_header">-</h4>
            </div>
            <div class="modal-body">
                <form id="form_sp">
                    <input type="hidden" name="status" id="status">
                    <input type="hidden" name="last_no" id="last_no">
                    <input type="hidden" name="sp_id" id="sp_id">
                    <div class="row">
                        <div class="col-xs-4" style="display:none !important">
                            <div class="form-group">
                                <label for="sp_kode_obat">KODE OBAT</label>
                                <input type="text" class="form-control" name="sp_kode_obat" id="sp_kode_obat">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="sp_kode_erp">KODE OBAT ERP NOW</label>
                                <!-- <input type="text" class="form-control" name="sp_kode_erp" id="sp_kode_erp" required> -->
                                <select name="sp_kode_erp" id="sp_kode_erp" class="form-control" required="required">
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="sp_nama_obat">NAMA DAN JENIS BAHAN KIMIA/DYESTUFF</label>
                                <input type="text" class="form-control" name="sp_nama_obat" id="sp_nama_obat" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="sp_pakingan_utuh">PACKINGAN (GR) UTUH</label>
                                <input type="text" class="form-control check_number_sp_multi" title="PACKINGAN (GR) UTUH" name="sp_pakingan_utuh" id="sp_pakingan_utuh" required>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="sp_pakingan_utuh_keterangan">KETERANGAN PACKINGAN UTUH</label>
                                <input type="text" class="form-control" name="sp_pakingan_utuh_keterangan" id="sp_pakingan_utuh_keterangan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="sp_tinggi_pakingan">TINGGI PACKINGAN UTUH (CM)</label>
                                <input type="text" class="form-control check_number_sp" name="sp_tinggi_pakingan" id="sp_tinggi_pakingan">
                            </div>
                            </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="sp_bj_pakingan">BERAT JENIS</label>
                                <input type="text" class="form-control check_number_sp" name="sp_bj_pakingan" id="sp_bj_pakingan">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="sp_berat_pakingan">BERAT PACKINGAN (GR)</label>
                                <input type="text" class="form-control check_number_sp_multi" name="sp_berat_pakingan" id="sp_berat_pakingan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="sp_berat_pakingan_botol_kecil">BERAT PACKINGAN BOTOL KECIL (GR)</label>
                                <input type="text" class="form-control check_number_sp" name="sp_berat_pakingan_botol_kecil" id="sp_berat_pakingan_botol_kecil">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="sp_keterangan">KETERANGAN PACKINGAN BUKAAN</label>
                                <input type="text" class="form-control" name="sp_keterangan" id="sp_keterangan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="sp_jenis">JENIS</label>
                                <input type="text" class="form-control" name="sp_jenis" id="sp_jenis">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-xs-12">
                        <h5 class="alert-heading">Note : </h5>
                        <p>- Format pengisian Standar Packaging harus angka dengan desimal menggunakan titik (Contoh 10000 atau untuk desimal 10000.5)</br>
                           - Untuk Standar Packaging lebih dari 1 (Multiple) menggunakan pemisah || (Contoh 10000||15000 atau untuk desimal 10000||15000.5)</br> 
                           - Multiple Standar Packaging hanya bisa di PACKINGAN UTUH dan BERAT PACKINGAN</p> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save_sp">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_preview" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Preview Standar Packaging</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div class="row">
                    <div class="col-md-12 " >
                        <div id="m-content" class="table-responsive jumbroton">
                            <input type="hidden" value="0" id="opname_id">
                            <input type="hidden" value="0" id="opname_total_qty">
                            <input type="hidden" value="0" id="opname_pakingan_standar">
                            <input type="hidden" value="0" id="opname_total_stock">
                            <input type="hidden" value="0" id="opname_total_scan">
                            <input type="hidden" value="0" id="opname_total_stock_old">
                            <input type="hidden" value="0" id="opname_total_stock_response">
                            <input type="hidden" value="0" id="opname_qty_old">
                            <input type="hidden" value="0" id="opname_ut">
                            <input type="hidden" value="0" id="opname_tg">
                            <input type="hidden" value="0" id="opname_bj">
                            <input type="hidden" value="0" id="opname_bp">
                            <input type="hidden" value="0" id="opname_bk">
                            <table border=0 width="100%">
                                <tr class="va-top">
                                    <td class="padTopBot5">Code</td>
                                    <td>: &nbsp;</td>
                                    <td id="opname_kode_obat"></td>
                                </tr>
                                <tr class="va-top">
                                    <td>Nama Obat</td>
                                    <td>: &nbsp;</td>
                                    <td id="opname_nama_obat"></td>
                                </tr>
                                <tr class="WH_M510 WAREHOUSE_ALL KATEGORI va-top" id="kategori_row">
                                    <td class="padTopBot5">Kategori</td>
                                    <td>: &nbsp;</td>
                                    <td id="kategori_text">
                                        <div class="col-sm-12 pd0">
                                            <div class="form-group mb0">
                                                <select class="form-select w100" id="kategori">
                                                    <option value="utuhan">Utuhan</option>
                                                    <option value="bukaan">Bukaan</option>
                                                </select> 
                                            </div>
                                        </div>                                    
                                    </td>
                                </tr>
                                <tr class="WH_M510 WAREHOUSE_ALL FORMULA va-top" id="formula_row">
                                    <td class="padTopBot5">Formula</td>
                                    <td>: &nbsp;</td>
                                    <td id="formula_text">
                                        <div class="col-sm-12 pd0">
                                            <div class="form-group mb0">
                                            <select class="form-select w100" id="formula">
                                                    <option value="berat">Berat</option>
                                                    <option value="volume">Volume</option>
                                                    <option value="tinggi">Tinggi</option>
                                                </select> 
                                            </div>
                                        </div>                                    
                                    </td>
                                </tr>
                                <tr class="WH_M510 WAREHOUSE_ALL FORMULA va-top" id="berat_row">
                                    <td class="padTopBot5">Berat</td>
                                    <td>: &nbsp;</td>
                                    <td id="berat_text">
                                        <div class="col-sm-12 pd0">
                                            <div class="form-group mb0">
                                                <select class="form-select w100" id="berat">
                                                    <option value="kardus">Kardus Tong </option>
                                                    <option value="toples">Toples</option>
                                                </select> 
                                            </div>
                                        </div>                                    
                                    </td>
                                </tr>
                                <tr class="va-top">
                                    <td  class="padTopBot5" id="label_qty">Qty Dus</td>
                                    <td>: &nbsp;</td>
                                    <td id="opname_qty_dus_text"><input type="text" class='form-control qty_dus' inputmode="numeric"  id='opname_qty_dus' autocomplete="off" /></td>
                                </tr>
                                <tr class="va-top">
                                    <td class="padTopBot5">Standar packaging</td>
                                    <td>: &nbsp;</td>
                                    <td id="opname_pakingan_standar_text"></td>
                                </tr >
                                <tr class="WH_M510 WAREHOUSE_ALL TINGGI_STANDAR va-top" id="tinggi_row">
                                    <td class="padTopBot5">SP Utuhan</td>
                                    <td>: &nbsp;</td>
                                    <td id="opname_pakingan_tinggi_standar_text"></td>
                                </tr>
                                <tr class="va-top">
                                    <td class="padTopBot5">Total Scan</td>
                                    <td>: &nbsp;</td>
                                    <td id="opname_total_stock_text"></td>
                                </tr>
                                <tr>
                                    <td class="padTopBot5"></td>
                                    <td></td>
                                    <td id="opname_submit"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var last_no=parseInt('<?=$no;?>');        
        var kode_option=[];
        var form_validate = $("#form_sp").validate();
        var tabel_sp = $('#standartPackagingTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            order: [[0, 'asc']]
        });

        $("#addData").on("click", function() {
            form_validate.resetForm();
            createKodeObat('create');
            $('#form_sp').trigger('reset');
            $(".error2").hide();
            $("#modal_sp_header").html("Add Standar Packaging");
            $("#status").val("add_sp");
            $("#last_no").val(last_no);
            $("#modal_sp").modal('show');
        });

        $(document).on('click', '.edit_sp', function() {
            createKodeObat('update');
            let parent=$(this).parent().parent();
            let dataPost={status:"get_sp",id_dt: parent.data("id")};
            $.ajax({
                url: 'pages/ajax/standar_packaging_gk_ajax.php',
                type: 'POST',
                data: dataPost,
                dataType: "JSON",
                success: function(response) {
                    if(response.success){ 
                        form_validate.resetForm();
                        $('#form_sp').trigger('reset');
                        $(".error2").hide();
                        $("#modal_sp_header").html("Edit Standar Packaging");
                        $("#status").val("edit_sp");
                        $("#last_no").val(parent.data("no"));
                        $.each( response.data, function( key, value ) {
                            $("#sp_"+key).val(value);
                        });
                        $("#sp_kode_erp").prepend(new Option(response.data.kode_erp, response.data.kode_erp,true));
                        kode_option[response.data.kode_erp]=response.data.nama_obat;
                        $("#modal_sp").modal('show');
                    }else{
                        alert("Terjadi Error Update, mohon hubungi DIT");
                    }
                     $("#save_sp").show();
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Jaringan Terputus, Silahkan ulangi kembali',
                        icon: 'error',
                        timer: 1000,
                        position : 'top-end',
                        showConfirmButton: false
                    });
                }
            });
        });

        $(document).on('click', '.preview_sp', function() {
            let parent=$(this).parent().parent();
            let dataPost={status:"preview_sp",id_dt: parent.data("id")};
            
            $(".WAREHOUSE_ALL").hide();
            $.ajax({
                url: '<?=$baseUrl?>pages/ajax/standar_packaging_gk_ajax.php',
                type: 'POST',
                data: dataPost,
                dataType: "JSON",
                success: function(response) {
                    if(response.success){          
                        $("#label_qty").html("Qty Dus");
                        $("#opname_kode_obat").html(response.data.kode_obat);
                        $("#opname_nama_obat").html(response.data.nama_obat);
                        $("#opname_id").val(response.data.id);
                        $("#opname_pakingan_standar").val(response.data.pakingan_standar);
                        $("#opname_total_stock").val(response.data.total_stock);
                        $("#opname_total_stock_old").val("0");
                        $("#opname_total_stock_response").val(response.data.total_stock);
                        $("#opname_qty_old").val("0");
                        if(response.data.qty_dus==0){
                            $("#opname_qty_dus").val("");
                        }else{
                            $("#opname_qty_dus").val(response.data.qty_dus);
                        }
                    
                        updateSP(response.data.ut,"ut");
                        updateSP(response.data.tg,"tg");
                        updateSP(response.data.bj,"bj");
                        updateSP(response.data.bp,"bp");
                        updateSP(response.data.bk,"bk");

                        $(".WH_"+'M510').show();
                        
                        if(response.data.kategori=="utuhan"){
                            $("#kategori").val(response.data.kategori).trigger("change");
                            selectUT();
                        }
                        else{
                            if(response.data.kategori=="kardus"||response.data.kategori=="toples"){
                                $("#berat").val(response.data.kategori);
                                $("#formula").val("berat");
                                $("#kategori").val('bukaan').trigger("change");
                                if(response.data.kategori=="kardus"){
                                    selectBP();
                                }
                                
                            }else{
                                $("#formula").val(response.data.kategori);
                                $("#kategori").val('bukaan').trigger("change");
                                if(response.data.kategori=="tinggi"){
                                    selectTG();
                                }
                            }
                        }
                        
                        $("#opname_total_stock_text").html(response.data.total_stock_text);
                        hitungTotal();
                        if($('#modal_preview').hasClass('in')||$('#modal_preview').hasClass('show')){
                        }else{
                            $("#modal_preview").modal("show"); 
                        }
                    }else{
                        alert("Terjadi Error Update, mohon hubungi DIT");
                    }
                },
                error: function() {
                }
            });
        });
        
        $(document).on('click', '.delete_sp', function() {
            let parent=$(this).parent().parent();
            let dataPost={status:"delete_sp",id_dt: parent.data("id"),last_no:parent.data("no")};
            let row = tabel_sp.row($(this).parent().parents('tr'));
            Swal.fire({
                icon: 'warning',
                title: 'Konfirmasi',
                text: 'Apakah yakin ingin menghapus data.',
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'pages/ajax/standar_packaging_gk_ajax.php',
                        type: 'POST',
                        data: dataPost,
                        dataType: "JSON",
                        success: function(response) {
                            if(response.success){ 
                                row.remove().draw(false);
                            }else{
                                alert("Terjadi Error Update, mohon hubungi DIT");
                            }
                            $("#save_sp").show();
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error',
                                text: 'Jaringan Terputus, Silahkan ulangi kembali',
                                icon: 'error',
                                timer: 1000,
                                position : 'top-end',
                                showConfirmButton: false
                            });
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    
                }
            });         
        });

        $("#save_sp").on("click", function() {
            $("#save_sp").hide();
            if($("#form_sp").valid()==false){
                Swal.fire({
                    title: 'Error',
                    text: 'Silahkan Lengkapi Form',
                    icon: 'error',
                    timer: 1000,
                    position : 'top-end',
                    showConfirmButton: false
                })
                $("#save_sp").show();
                return true;
            }
            $.ajax({
                url: 'pages/ajax/standar_packaging_gk_ajax.php',
                type: 'POST',
                data: $('#form_sp').serialize(),
                dataType: "JSON",
                success: function(response) {
                    if(response.success){ 
                        $("#modal_sp").modal('hide');
                        if(response.messages[3]=="add_sp"){
                            tabel_sp.row.add($(response.messages[1])).draw(false);
                            last_no++;
                        }else if(response.messages[3]=="edit_sp"){
                            $('#tr_'+response.messages[2]).html($(response.messages[1]));
                        }
                        Swal.fire({
                            title: 'Saved',
                            text: response.messages[0],
                            icon: 'success',
                            timer: 1000,
                            position : 'top-end',
                            showConfirmButton: false
                        })
                    }else{
                        alert("Terjadi Error Update, mohon hubungi DIT");
                    }
                    $("#save_sp").show();
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Jaringan Terputus, Silahkan ulangi kembali',
                        icon: 'error',
                        timer: 1000,
                        position : 'top-end',
                        showConfirmButton: false
                    });
                }
            });
        });

        $( window ).on( "resize", function() {
            tabel_sp.columns.adjust().draw(false);
        } );

        const regex = /^\d+(?:\.\d+)?$/;
        $(".check_number_sp").on("keyup", function() {
            if($(this).val()==""||$(this).val()=="-"){
                removeError($(this));
                return true;
            }
            if(regex.test($(this).val())==true){
                removeError($(this));
            }else{
                displayError($(this));
            }
        });

        const regex_multi = /^\d+(?:\.\d+)?(?:\|\|\d+(?:\.\d+)?)*$/;
        $(".check_number_sp_multi").on("keyup", function() {
            if($(this).val()==""||$(this).val()=="-"){
                removeError($(this));
                return true;
            }
            if(regex_multi.test($(this).val())==true){
                removeError($(this));
            }else{
                displayError($(this));
            }
        });

        
        $("#sp_kode_erp").on("change", function() {
            let val = $("#sp_kode_erp").val();
            if(val === null || val==""){
                return true;
            }
            let first= val.charAt(0).toUpperCase();
            if(['C','D','R'].includes(first)){
                $("#sp_jenis").val("DYESTUFF");
            }else if(['E'].includes(first)){
                $("#sp_jenis").val("CHEMICAL");
            }
            $("#sp_nama_obat").val(kode_option[val]);
        });

        //preview sp
        $("#kategori").on("change", function() {
            if($(this).val()=="utuhan"){
                $("#formula_row").hide();
                $("#berat_row").hide();
                $("#tinggi_row").hide();
                $("#label_qty").html("Qty Dus");
                selectUT();
            }else{
                $("#formula_row").show(); 
                ubahFormula();
            }
        });
        $("#formula").on("change", function() {
            ubahFormula();
        });

        $("#berat").on("change", function() {
            ubahBerat();
        });

        $(document).on('keyup', '.qty_dus', function(e) {
            let val=formatAngka($(this).val());
            this.value= val;
            hitungTotal()
        });

        $(document).on('change', '#pakingan_multiple', function(e) {
            hitungTotal()
        });

        $(document).on('change', '#kardus_multiple', function(e) {
            hitungTotal()
        });

        $(document).on('change', '#tinggi_multiple', function(e) {
            hitungTotal()
        });
        //logic function
        //TG untuk standar utuhan di tinggi, BP untuk berat packingan, UT untuk standar utuhan
        function selectTG(){
            let paking_kds = $("#opname_ut").val().split("||");
            if(paking_kds.length>=2){
                let select=`<select class="form-select w100" id="tinggi_multiple">`;
                let val=$("#opname_qty_dus").val();
                let ps=$("#opname_tg").val();
                let ts= $("#opname_total_stock_response").val();
                let last_val=0;
                if(Number(val)!= 0 && ps !=0){
                    let tmp=val/ps;
                    last_val=ts/tmp;
                }
                for (let i = 0; i < paking_kds.length; i++) { 
                    let selected= last_val.toFixed() ==paking_kds[i]?"selected": "";
                    select+=`<option value="`+paking_kds[i]+`" `+selected+`>`+nilaiKeRibuan(paking_kds[i], ".",",")+`</option>`;
                }
                select+= `</select> `;
                $("#opname_pakingan_tinggi_standar_text").html(select);
                $("#opname_pakingan_standar_text").html(nilaiKeRibuan($("#opname_tg").val(), ".",","));
                $("#tinggi_multiple").trigger("change");
            }else{
                $("#opname_pakingan_tinggi_standar_text").html(nilaiKeRibuan(paking_kds, ".",","));
                $("#opname_pakingan_standar_text").html(nilaiKeRibuan($("#opname_tg").val(), ".",","));
                hitungTotal();
            }
        }
        function selectBP(){
            let paking_kds = $("#opname_bp").val().split("||");
            if(paking_kds.length>=2){
                let select=`<select class="form-select w100" id="kardus_multiple">`;
                for (let i = 0; i < paking_kds.length; i++) { 
                    let selected= $("#opname_pakingan_standar").val() ==paking_kds[i]?"selected": "";
                    select+=`<option value="`+paking_kds[i]+`" `+selected+`>`+nilaiKeRibuan(paking_kds[i], ".",",")+`</option>`;
                }
                select+= `</select> `;
                $("#opname_pakingan_standar_text").html(select);
                $("#kardus_multiple").trigger("change");
            }else{
                $("#opname_pakingan_standar_text").html(nilaiKeRibuan($("#opname_bp").val(), ".",","));
                hitungTotal();
            }
        }
        function selectUT(){
            let paking_std = $("#opname_ut").val().split("||");
            if(paking_std.length>=2){
                let select=`<select class="form-select w100" id="pakingan_multiple">`;
                for (let i = 0; i < paking_std.length; i++) { 
                    let selected= $("#opname_pakingan_standar").val() ==paking_std[i]?"selected": "";
                    select+=`<option value="`+paking_std[i]+`" `+selected+`>`+nilaiKeRibuan(paking_std[i], ".",",")+`</option>`;
                }
                select+= `</select> `;
                $("#opname_pakingan_standar_text").html(select);
                $("#pakingan_multiple").trigger("change");
            }else{
                $("#opname_pakingan_standar_text").html(nilaiKeRibuan($("#opname_ut").val(), ".",","));
                hitungTotal();
            }
        }
        function ubahFormula(){
            $("#berat_row").hide();
            $("#tinggi_row").hide();
            $("#label_qty").html(ucfirst($("#formula").val()));
            let val=0;
            if($("#formula").val()=="berat"){
                $("#berat_row").show();
                ubahBerat();
                return true;
            }else if($("#formula").val()=="volume"){
                val=$("#opname_bj").val();
            }else if($("#formula").val()=="tinggi"){
                $("#tinggi_row").show();
                selectTG();
                return true;
            }
            $("#opname_pakingan_standar_text").html(nilaiKeRibuan(val, ".",","));
            hitungTotal();
        }
        function ubahBerat(){
            $("#label_qty").html(ucfirst($("#berat").val()));
            let val=0;
            if($("#berat").val()=="kardus"){
                val=$("#opname_bp").val();
                selectBP()
                return 1;
            }else if($("#berat").val()=="toples"){
                val=$("#opname_bk").val();
            }
            $("#opname_pakingan_standar_text").html(nilaiKeRibuan(val, ".",","));
            hitungTotal();
        }
        function hitungTotal(){
            let ps=0;
            let total_stock=0;
            let val=$("#opname_qty_dus").val();
            
            if(Number(val)!= 0){
            if($("#kategori").val()=="utuhan"){
                ps=$("#opname_ut").val();
                let pkg_std = ps.split("||");
                if(pkg_std.length>=2){
                    ps=$("#pakingan_multiple").val();
                }
                $("#opname_pakingan_standar").val(ps);
                total_stock=Number(val)*ps;
            }
            else{
                if($("#formula").val()=="berat"){
                    if($("#berat").val()=="kardus"){
                        ps=$("#opname_bp").val();
                        let pkg_kds = ps.split("||");
                        if(pkg_kds.length>=2){
                            ps=$("#kardus_multiple").val();
                        }
                        $("#opname_pakingan_standar").val(ps);
                    }else if($("#berat").val()=="toples"){
                        $("#opname_pakingan_standar").val($("#opname_bk").val());
                        ps=$("#opname_bk").val();
                    }
                    total_stock=Number(val)-ps;
                }else if($("#formula").val()=="volume"){
                    $("#opname_pakingan_standar").val($("#opname_bj").val());
                    ps=$("#opname_bj").val();
                    total_stock=Number(val)*ps;
                }else if($("#formula").val()=="tinggi"){  
                    let st=$("#opname_ut").val();
                    let sdt_tg = st.split("||");
                    if(sdt_tg.length>=2){
                        st=$("#tinggi_multiple").val();
                    }       
                    $("#opname_pakingan_standar").val($("#opname_tg").val());
                    ps=$("#opname_tg").val();
                    total_stock=Number(val)/ps*st;
                }
            }
            
            total_stock=total_stock.toFixed(2);
            $("#opname_total_scan").val(total_stock);
            total_stock=Number(total_stock)+Number($("#opname_total_stock_old").val());
            }
            else{
            $("#opname_total_scan").val("0");
            total_stock=$("#opname_total_stock_old").val();
            }
            $("#opname_total_stock_text").html(nilaiKeRibuan(total_stock, ".",","));
            $("#opname_total_stock").val(total_stock);
        }
        function createKodeObat(sts){
            let dataPost={status:"get_data_obat",id_dt:"1"};
            $("#sp_kode_erp").empty();
            kode_option=[];
            $.ajax({
                url: 'pages/ajax/standar_packaging_gk_ajax.php',
                type: 'POST',
                data: dataPost,
                dataType: "JSON",
                success: function(response) {
                    if(response.success){ 
                        $.each( response.data, function( key, value ) {
                            $("#sp_kode_erp").append(new Option(value.kode, value.kode));
                            kode_option[value.kode]=value.desc;
                        });
                        if(sts="create"){
                            $("#sp_kode_erp").val($("#sp_kode_erp option:first").val()).trigger('change');
                        }
                    }else{
                        alert("Terjadi Error Update, mohon hubungi DIT");
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Jaringan Terputus, Silahkan ulangi kembali',
                        icon: 'error',
                        timer: 1000,
                        position : 'top-end',
                        showConfirmButton: false
                    });
                }
            });
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
        function updateSP(val,sp){
            let value=0;
            if(val=="-"||val==""){
                value=0;
            }else{
                value=val;
            }
            $("#opname_"+sp).val(value);
        }
        function displayError(inputFieldId){
            let id=inputFieldId.attr('id');
            $("#"+id+"-error2").remove();
            inputFieldId.after("<label id='"+id+"-error2' class='error2' for='"+id+"'>Format Tidak sesuai, Silahkan Mengikuti Note</label>");
        }
        function removeError(inputFieldId){
            let id=inputFieldId.attr('id');
            $("#"+id+"-error2").remove();
        }
    });
</script>

</html>