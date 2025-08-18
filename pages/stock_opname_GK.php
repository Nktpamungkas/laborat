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
<div id="detai_scan" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="width: 80%; max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Scan Stock Opname</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div class="row">
                    <div class="col-md-6">
                        <table id="list_scan_opname" class="table table-bordered table-hover table-striped" width="100%">
                            <thead class="bg-green">
                                <tr>
                                    <th >
                                        <div align="center">Waktu Scan</div>
                                    </th>
                                    <th >
                                        <div align="center">Qty Scan</div>
                                    </th>
                                    <th >
                                        <div align="center">Kategori</div>
                                    </th>
                                    <th >
                                        <div align="center">Standar</div>
                                    </th>
                                    <th >
                                        <div align="center">Total Scan</div>
                                    </th>
                                    <th >
                                        <div align="center">Action</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="scan_opname">
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 " >
                        <div id="m-content" class="table-responsive jumbroton" >
                            <input type="hidden" value="0" id="opname_id">
                            <input type="hidden" value="0" id="opname_total_qty">
                            <input type="hidden" value="0" id="opname_pakingan_standar">
                            <input type="hidden" value="0" id="opname_total_stock">
                            <input type="hidden" value="0" id="opname_total_scan">
                            <input type="hidden" value="0" id="opname_total_stock_old">
                            <input type="hidden" value="0" id="opname_qty_old">
                            <input type="hidden" value="0" id="opname_ut">
                            <input type="hidden" value="0" id="opname_tg">
                            <input type="hidden" value="0" id="opname_bj">
                            <input type="hidden" value="0" id="opname_bp">
                            <input type="hidden" value="0" id="opname_bk">
                            <table border=0>
                            <tr class="va-top">
                                    <td class="padTopBot5">Code</td>
                                    <td>: &nbsp;</td>
                                    <td id="opname_kode_obat"></td>
                                </tr>
                            <tr class="va-top">
                                    <td class="padTopBot5">Waktu Scan</td>
                                    <td>: &nbsp;</td>
                                    <td id="opname_waktu_scan"></td>
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
                                    <td id="opname_qty_dus_text"><input type="text" class='form-control qty_dus' inputmode="numeric"  id='opname_qty_dus'  /></td>
                                </tr>
                                <tr class="va-top">
                                    <td class="padTopBot5">Standar packaging</td>
                                    <td>: &nbsp;</td>
                                    <td id="opname_pakingan_standar_text"></td>
                                </tr >
                                <tr class="va-top">
                                    <td class="padTopBot5">Total Scan</td>
                                    <td>: &nbsp;</td>
                                    <td id="opname_total_stock_text"></td>
                                </tr>
                                <tr>
                                    <td class="padTopBot5"></td>
                                    <td></td>
                                    <td id="opname_submit"><button class='btn btn-primary btn-sm save_scan' title='Save' ><i class='fa fa-floppy-o' ></i> Save</button> <button class='btn btn-danger btn-sm cancel_edit_scan' title='Cancel' ><i class='fa fa-ban' ></i> Cancel</button> <p id="loading_confirm" style="display:none">Mohon Tunggu Sedang Save</p></td>
                                </tr>
                            </table>
                        </div>
                        </br>
                    </div>
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
    var lihat_detail=false;
  $(document).ready(function () {
    var kode_obat="";
    var id_stock_opname="0";
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
                if(response!="Data Tutup Buku Tidak Tersedia"){
                cari_data=true;
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
    $(document).on('click', '.detail', function() {
        $("#m-content").hide();
        let parent=$(this).parent().parent();
        kode_obat=parent.data('ko');
        id_stock_opname=parent.data('id');
        let dataPost={status:"get_scan_opname",id_dt: id_stock_opname};
        refreshScanData(dataPost); 
        if($('#detai_scan').hasClass('in')||$('#detai_scan').hasClass('show')){
        }else{
            $("#detai_scan").modal("show"); 
        } 
        lihat_detail=true;   
    });
    $(document).on('click', '.edit_scan_opname', function() {
        let id = $(this).data('id');
        let time = $(this).data('time'); 
        $(".WAREHOUSE_ALL").hide();          
        $("#opname_waktu_scan").html(time);
        let dataPost={status:"edit_scan_opname",id_dt: id,kode_obat:kode_obat};
        $.ajax({
            url: 'pages/ajax/stock_opname_gk_ajax.php',
            type: 'POST',
            data: dataPost,
            dataType: "JSON",
            success: function(response) {
                if(response.success){          
                    $("#label_qty").html("Qty Dus");
                    $("#opname_kode_obat").html(response.data.kode_obat);
                    $("#opname_id").val(response.data.id);
                    $("#opname_pakingan_standar").val(response.data.pakingan_standar);
                    $("#opname_total_stock").val(response.data.total_stock);
                    $("#opname_total_stock_old").val("0");
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

                    $(".WH_"+$("#warehouse").val()).show();
                    
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
                        }
                    }
                    
                    $("#opname_total_stock_text").html(response.data.total_stock_text);
                    hitungTotal();
                    $("#m-content").show("slide", { direction: "left" }, 1000); 
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
    });
    $("#kategori").on("change", function() {
        if($(this).val()=="utuhan"){
            $("#formula_row").hide();
            $("#berat_row").hide();
            $("#label_qty").html("Qty Dus");
            selectUT();
        }else{
            $("#formula_row").show(); 
            ubahFormula();
        }
    });
    $(".cancel_edit_scan").on("click", function() {
        $("#m-content").hide("slide", { direction: "left" }, 1000);
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
    $(document).on('click', '.save_scan', function() {
        if($("#opname_qty_dus").val()==""||$("#opname_qty_dus").val()=="0"){
            Swal.fire({
                title: 'Error',
                text: 'Qty Tidak boleh kosong',
                icon: 'error',
            });
            return 1;
        }
        $(".save_scan").hide();
        $("#loading_confirm").show();
        let id = $("#opname_id").val();
        let ctgr = "utuhan";
        if($("#warehouse").val()=="M510"){
            if($("#kategori").val()=="utuhan"){
                ctgr = $("#kategori").val();
            }
            else{
                if($("#formula").val()=="berat"){
                    ctgr = $("#berat").val();
                }else{
                    ctgr = $("#formula").val();
                }
            }
        }
        let dataPost={
            status:"simpan_scan",
            id_dt: id,
            id_stock_opname: id_stock_opname,
            qty_scan:$("#opname_qty_dus").val(),
            total_scan:$("#opname_total_scan").val(),
            pakingan_standar:$("#opname_pakingan_standar").val(),
            kategori:ctgr
        };
        $.ajax({
            url: '<?=$baseUrl?>pages/ajax/stock_opname_gk_ajax.php',
            type: 'POST',
            data: dataPost,
            dataType: "JSON",
            success: function(response) {
                if(response.success){ 
                let dataPost={status:"get_scan_opname",id_dt: id_stock_opname};
                refreshScanData(dataPost); 
                    $("#m-content").hide("slide", { direction: "left" }, 1000); 
                    Swal.fire({
                        title: 'Saved',
                        text: 'Berhasil Meyimpan',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false
                    })
                }else{
                    alert("Terjadi Error Update, mohon hubungi DIT");
                }
                $(".save_scan").show();
                $("#loading_confirm").hide();  
            },
            error: function() {
                alert("Jaringan Terputus, Silahkan klik Confirm kembali");
                $(".save_scan").show();
                $("#loading_confirm").hide();
            }
        });
    });
 
    //logic function
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
        $("#label_qty").html(ucfirst($("#formula").val()));
        let val=0;
        if($("#formula").val()=="berat"){
            $("#berat_row").show();
            ubahBerat();
            return true;
        }else if($("#formula").val()=="volume"){
            val=$("#opname_bj").val();
        }else if($("#formula").val()=="tinggi"){
            val=$("#opname_tg").val();
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
                total_stock=(Number(val)*1000)-ps;
            }else if($("#formula").val()=="volume"){
                $("#opname_pakingan_standar").val($("#opname_bj").val());
                ps=$("#opname_bj").val();
                total_stock=Number(val)*ps;
            }else if($("#formula").val()=="tinggi"){  
                $("#opname_pakingan_standar").val($("#opname_tg").val());
                ps=$("#opname_tg").val();
                total_stock=Number(val)/ps*$("#opname_ut").val();  
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
    function refreshScanData(dataPost){
        $.ajax({
            url: 'pages/ajax/stock_opname_gk_ajax.php',
            type: 'POST',
            data: dataPost,
            dataType: "JSON",
            success: function(response) {
                if(response.success){ 
                    let row="";
                    $.each( response.data, function( key, value ) {
                        row+=`
                        <tr>
                        <td>`+value.time+`</td>
                        <td>`+value.qty_dus+`</td>
                        <td>`+value.kategori+`</td>
                        <td>`+value.pakingan_standar+`</td>
                        <td>`+value.total_stock+`</td>
                        <td><button class='btn btn-success btn-sm edit_scan_opname' title='Edit' data-toggle='tooltip' data-id='`+value.id+`' data-time='`+value.time+`' ><i class='fa fa-pencil-square-o '></i></button> </td>
                        </tr>
                        `;
                    });
                    if(row==""){
                        $("#scan_opname").html("<tr><td colspan='6'>Tidak Ada Data</td></tr>");
                    }else{
                        $("#scan_opname").html(row);
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
    $('#detai_scan').on('hidden.bs.modal', function () {
        lihat_detail=false;
    });
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
    if(lihat_detail){
        let dataPost={status:"get_scan_opname",id_dt: id_stock_opname};
        refreshScanData(dataPost); 
    }
}, 3000);
  });
</script>