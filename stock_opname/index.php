<?php
ini_set("error_reporting", 1);
session_start();
$_SESSION["opname"] = "gk";
function url(){
  return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $_SERVER['REQUEST_URI']
  );
}
$url=url();
$baseUrl=str_replace("stock_opname/index.php","",$url);
?>
<!DOCTYPE html>
<head>
	<title>Scan Barcode Laborat</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="../login_assets/images/icons/ITTI_Logo index.ico" />
	<link rel="stylesheet" type="text/css" href="../login_assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../login_assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../login_assets/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="../login_assets/vendor/daterangepicker/daterangepicker.css">
	<link href="../bower_components/sweet-alert/dist/sweetalert2.css" rel="stylesheet" type="text/css">
    <style>
        body{
            padding-top: 2.2rem;
            padding-bottom: 4.2rem;
            background: rgb(177 177 177);
        }
        .myform{
            position: relative;
            display: -ms-flexbox;
            display: flex;
            padding: 1rem;
            -ms-flex-direction: column;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0,0,0,.2);
            border-radius: 1.1rem;
            outline: 0;
            max-width: 500px;
            box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px;
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
         
    </style>
</head>

<body>

	<div class="container">
        <div class="row">
			<div class="col-md-5 mx-auto">
			<div id="first">
				<div class="myform form ">
					<div class="logo mb-3">
						<div class="col-md-12 text-center">
							<h1>Scan Stock Opname Gudang Kimia</h1>
						</div>
					</div>
                    <input type="hidden" value="0" id='trans_kd_obat'>
                    <input type="hidden" value="0" id='trans_lot'>
                    <div class="form-group">
                        <div class="col-sm-12" style="display: flex; gap: 10px;">
                                <input type="date" class="form-control" id="tgl_tutup" placeholder="Tanggal Awal" name="tgl_tutup" autofocus> 
                                <select class="form-select" id="warehouse">
                                    <option value="" readonly>Pilih Gudang</option>
                                    <option value="M101">M101</option>
                                    <option value="M510">M510</option>
                                </select>                           
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <label for="barcode" id="label_barcode" class="text-center">Silahkan Isi Tanggal Tutup Buku</label>
                        <input type="text" name="barcode"  class="form-control" id="barcode" placeholder="" >
                    </div>
                    <div class="form-group">
                        <div id="response_pesan" class="text-center"></div>
                    </div> 
				</div>
			</div>
		</div>
    </div>   
    <!-- Modal Detail -->
    <div id="detailModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered " role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="m-title">Detail Stock Opname</h4>
                </div>
                <div class="modal-body p-3">
                    <div id="m-content" class="table-responsive">
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
                                <td>Code</td>
                                <td>: &nbsp;</td>
                                <td id="opname_kode_obat"></td>
                            </tr>
                            <tr class="va-top">
                                <td>Nama Obat</td>
                                <td>: &nbsp;</td>
                                <td id="opname_nama_obat"></td>
                            </tr>
                            <tr class="va-top">
                                <td>Lot</td>
                                <td>: &nbsp;</td>
                                <td id="opname_lot"></td>
                            </tr>
                            <tr class="va-top">
                                <td>Qty Stock</td>
                                <td>: &nbsp;</td>
                                <td id="opname_total_qty_text"></td>
                            </tr>
                            <tr class="WH_M510 WAREHOUSE_ALL KATEGORI va-top" id="kategori_row">
                                <td>Kategori</td>
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
                                <td>Formula</td>
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
                                <td>Berat</td>
                                <td>: &nbsp;</td>
                                <td id="formula_text">
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
                                <td id="label_qty">Qty Dus</td>
                                <td>: &nbsp;</td>
                                <td id="opname_qty_dus_text"><input type="text" class='form-control qty_dus' inputmode="numeric"  id='opname_qty_dus' autocomplete="off" /></td>
                            </tr>
                            <tr class="va-top">
                                <td>Standar packaging</td>
                                <td>: &nbsp;</td>
                                <td id="opname_pakingan_standar_text"></td>
                            </tr >
                            <tr class="WH_M510 WAREHOUSE_ALL TINGGI_STANDAR va-top" id="tinggi_row">
                                <td class="padTopBot5">SP Utuhan</td>
                                <td>: &nbsp;</td>
                                <td id="opname_pakingan_tinggi_standar_text"></td>
                            </tr>
                            <tr class="va-top">
                                <td>Total Stock</td>
                                <td>: &nbsp;</td>
                                <td id="opname_total_stock_text"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td id="opname_submit"><button class='btn btn-primary btn-sm confirm' title='Submit' ><i class='fa fa-check-square-o' ></i> Submit</button> <p id="loading_confirm" style="display:none">Mohon Tunggu Sedang Save</p></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <div id="response_pesan2" class="text-center"></div>
                    </div>
                    <button type="button" class="btn btn-secondary" id="close_modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

	<script src="../login_assets/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="../login_assets/vendor/bootstrap/js/popper.js"></script>
	<script src="../login_assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="../login_assets/vendor/select2/select2.min.js"></script>
	<script src="../login_assets/vendor/daterangepicker/moment.min.js"></script>
	<script src="../login_assets/vendor/daterangepicker/daterangepicker.js"></script>
	<script src="../login_assets/js/main.js"></script>
    <script type="text/javascript" src="../bower_components/sweet-alert/dist/sweetalert2.min.js"></script>

    <script>
        var loading = false;
         $("#first").on("click", function(e) {
            if(e.target.id=="warehouse" || e.target.id=="tgl_tutup"){
                
            }else{
                checkForm(false);
            }
        } );
        $("#barcode").on("focus", function() {
            checkForm(true);
            $(this).attr("placeholder", "");
        } );
        $("#barcode").on("focusout", function() {
            //kalau stack matikan saja ini
            $(this).attr("placeholder", "Silahkan Klik Disini Untuk Scan");
        } );
        $("#tgl_tutup").on("change", function() {
            checkForm(false);
            $("#barcode").val("");
            $("#response_pesan").html("");
        } );
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
        } );
        $("#formula").on("change", function() {
            ubahFormula();
        } );
        $("#berat").on("change", function() {
            ubahBerat();
        } );
        $("#warehouse").on("change", function() {
            checkForm(false);
            $("#barcode").val("");
            $("#response_pesan").html("");
        } );
        $("#barcode").on("keyup", function() {
            if(loading){
                return 1;
            }
            else{
                loading = true;
                let dataPost={check:"check_transaksi_multiple", val:$(this).val(),tgl_tutup: $("#tgl_tutup").val(),warehouse:$("#warehouse").val()};
                $.ajax({
                    url: '../pages/ajax/stock_opname_gk_ajax.php',
                    type: 'POST',
                    data: dataPost,
                    dataType: "JSON",
                    success: function(response) {
                        loading = false;
                        if(response.success){ 
                            if(response.messages[1]==1){
                                $("#trans_kd_obat").val(response.data[1].kode_obat);
                                $("#trans_lot").val(response.data[1].lot);
                                $("#m-title").html($("#trans_kd_obat").val()+" lot "+$("#trans_lot").val());
                                editData(true)
                            }else{
                                let select=`<select class="form-select" id="lot_obat_multiple">`;
                                $.each( response.data, function( key, value ) {
                                    select+=`<option value="`+value.lot+`" > &nbsp; `+value.lot+` &nbsp; </option>`;
                                });
                                select+= `</select> `;

                                $("#trans_kd_obat").val(response.data[1].kode_obat);
                                $("#m-title").html(response.data[1].kode_obat+" lot (Select) "+select);
                                $("#lot_obat_multiple").val(response.data[1].lot).trigger("change");                                 
                            }
                        }else{
                            $("#response_pesan").html(response.messages[0]+" <button type='button' class='btn btn-outline-warning btn-sm' id='reset_barcode' title='reset'><i class='fa fa-ban' ></i></button>");
                        }
                    },
                    error: function() {
                    }
                });
            }
        } );
        function editData(satu_transaksi){
            $(".WAREHOUSE_ALL").hide();
            let dataPost={check:"edit_data", val:$("#barcode").val(),tgl_tutup: $("#tgl_tutup").val(),warehouse:$("#warehouse").val(),kode_obat:$("#trans_kd_obat").val(),lot:$("#trans_lot").val()};
            $.ajax({
                url: '../pages/ajax/stock_opname_gk_ajax.php',
                type: 'POST',
                data: dataPost,
                dataType: "JSON",
                success: function(response) {
                    if(response.success){ 
                        $("#label_qty").html("Qty Dus");
                        $("#opname_kode_obat").html(response.data.kode_obat);
                        $("#opname_nama_obat").html(response.data.nama_obat);
                        $("#opname_lot").html(response.data.lot);
                        $("#opname_total_qty_text").html(response.data.total_qty_text +" GR");

                         $("#opname_id").val(response.data.id);
                         $("#opname_total_qty").val(response.data.total_qty);
                         $("#opname_qty_dus").val("");
                         $("#opname_pakingan_standar").val(response.data.pakingan_standar);
                         $("#opname_total_stock").val(response.data.total_stock);
                         $("#opname_total_stock_old").val(response.data.total_stock);
                         $("#opname_total_stock_response").val(response.data.total_stock);
                         $("#opname_qty_old").val(response.data.qty_dus);
                       
                        updateSP(response.data.ut,"ut");
                        updateSP(response.data.tg,"tg");
                        updateSP(response.data.bj,"bj");
                        updateSP(response.data.bp,"bp");
                        updateSP(response.data.bk,"bk");

                        $(".WH_"+$("#warehouse").val()).show();
                        $("#response_pesan").html(" ");
                        $("#response_pesan2").html(" ");
                        
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
                        $(".modal-body").show();
                        if($('#detailModal').hasClass('in')||$('#detailModal').hasClass('show')){

                        }else{
                            $("#detailModal").modal("show"); 
                        }  
                    }else{
                        if(satu_transaksi){
                            $("#response_pesan").html(response.messages[0]+" <button type='button' class='btn btn-outline-warning btn-sm' id='reset_barcode' title='reset'><i class='fa fa-ban' ></i></button>");
                        }else{
                            $(".modal-body").hide();
                            $("#response_pesan2").html(response.messages[0]+" ");
                            if($('#detailModal').hasClass('in')||$('#detailModal').hasClass('show')){

                            }else{
                                $("#detailModal").modal("show"); 
                            }
                        }
                    }
                },
                error: function() {
                }
            });
        }
        $(document).on('change', '#lot_obat_multiple', function(e) {
            $("#trans_lot").val($(this).val());                  
            editData(false) 
        });
        $('#detailModal').on('hidden.bs.modal', function () {
            $("#barcode").val("").focus();
        })
        $("#response_pesan").on('click', '#reset_barcode', function() {
            $("#barcode").val("").focus();
            $("#response_pesan").html("");
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
        $(document).on('click', '.confirm', function() {
            if($("#opname_qty_dus").val()==""||$("#opname_qty_dus").val()=="0"){
                Swal.fire({
                    title: 'Error',
                    text: 'Qty Tidak boleh kosong',
                    icon: 'error',
                });
                return 1;
            }
            $(".confirm").hide();
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
            let post_value = parseFloat($("#opname_qty_old").val()) + parseFloat($("#opname_qty_dus").val());
            let dataPost={
                check:"simpan_stock",
                id_dt: id,
                qty_dus:post_value.toFixed(5),
                qty_scan:$("#opname_qty_dus").val(),
                total_stock:$("#opname_total_stock").val(),
                total_scan:$("#opname_total_scan").val(),
                pakingan_standar:$("#opname_pakingan_standar").val(),
                kategori:ctgr
            };
            $.ajax({
                url: '../pages/ajax/stock_opname_gk_ajax.php',
                type: 'POST',
                data: dataPost,
                dataType: "JSON",
                success: function(response) {
                    if(response.success){ 
                        $("#detailModal").modal("hide");
                        Swal.fire({
                            title: 'Saved',
                            text: 'Berhasil Submit',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false
                        })
                    }else{
                        alert("Terjadi Error Update, mohon hubungi DIT");
                    }
                    $(".confirm").show();
                    $("#loading_confirm").hide();
                },
                error: function() {
                    alert("Jaringan Terputus, Silahkan klik Confirm kembali");
                    $(".confirm").show();
                    $("#loading_confirm").hide();
                }
            });
        });
        $("#close_modal").click(function(){
            $("#detailModal").modal("hide"); 
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
        function checkForm(barcode){
            let tgl_tutup = $("#tgl_tutup").val();
            let warehouse = $("#warehouse").val();
            if(tgl_tutup==""){
                $("#label_barcode").html("Silahkan Isi Tanggal Tutup Buku");
                $("#tgl_tutup").focus();
                $(".daterangepicker").show();
            }else if(warehouse==""){
                $("#label_barcode").html("Silahkan Pilih Warehouse");
                $("#warehouse").focus();
            }else{
                $("#label_barcode").html("Silahkan Scan Barcode");
                if(!barcode && !loading){
                    $("#barcode").focus();
                }
            }
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
        
    </script>
</body>

</html>