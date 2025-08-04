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
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="../login_assets/images/icons/ITTI_Logo index.ico" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login_assets/vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login_assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login_assets/fonts/iconic/css/material-design-iconic-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login_assets/vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login_assets/vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login_assets/vendor/animsition/css/animsition.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login_assets/vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login_assets/vendor/daterangepicker/daterangepicker.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../login_assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="../login_assets/css/main.css">
	<!--===============================================================================================-->
    <style>
        body{
        padding-top:4.2rem;
		padding-bottom:4.2rem;
		background:rgba(0, 0, 0, 0.76);
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
                    <div class="form-group">
                        <div class="col-sm-12" style="display: flex; gap: 10px;">
                                <input type="date" class="form-control" id="tgl_tutup" placeholder="Tanggal Awal" name="tgl_tutup" autofocus> 
                                <select class="form-select" aria-label="Default select example" id="warehouse">
                                    <option value="" readonly>Pilih Gudang</option>
                                    <option value="M101">M101</option>
                                    <option value="M510">M510</option>
                                </select>                           
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="barcode" id="label_barcode" class="text-center">Silahkan Isi Tanggal Tutup Buku</label>
                        <input type="text" name="barcode"  class="form-control" id="barcode" placeholder="Silahkan Klik disini sebelum Scan" >
                    </div>
                    <div class="form-group">
                        <div id="response_pesan" class="text-center"></div>
                    </div> 
				</div>
			</div>
		</div>
    </div>   
    <!-- Modal Detail -->
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered " role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="m-title">Detail Stock Opname</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div id="m-content" class="table-responsive">
                        <input type="hidden" value="0" id="opname_id">
                        <input type="hidden" value="0" id="opname_total_qty">
                        <input type="hidden" value="0" id="opname_pakingan_utuh">
                        <input type="hidden" value="0" id="opname_total_stock">
                        <table border=0>
                            <tr>
                                <td>Code</td>
                                <td>: &nbsp;</td>
                                <td id="opname_kode_obat"></td>
                            </tr>
                            <tr>
                                <td>Nama Obat</td>
                                <td>: &nbsp;</td>
                                <td id="opname_nama_obat"></td>
                            </tr>
                            <tr>
                                <td>Lot</td>
                                <td>: &nbsp;</td>
                                <td id="opname_lot"></td>
                            </tr>
                            <tr>
                                <td>Qty Stock</td>
                                <td>: &nbsp;</td>
                                <td id="opname_total_qty_text"></td>
                            </tr>
                            <tr class="WH_M101 WAREHOUSE_ALL">
                                <td>Qty Dus</td>
                                <td>: &nbsp;</td>
                                <td id="opname_qty_dus_text"><input type="text" class='form-control qty_dus' id='opname_qty_dus' placeholder='Quantity Dus' title='Quantity Dus' /></td>
                            </tr>
                            <tr class="WH_M510 WAREHOUSE_ALL">
                                <td>JENIS</td>
                                <td>: &nbsp;</td>
                                <td id="opname_qty_dus_text">
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                    <label class="form-check-label" for="inlineRadio1">1</label>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>Standar packaging</td>
                                <td>: &nbsp;</td>
                                <td id="opname_pakingan_utuh_text"></td>
                            </tr>
                                <td>Total Stock</td>
                                <td>: &nbsp;</td>
                                <td id="opname_total_stock_text"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td id="opname_submit"><button class='btn btn-primary btn-sm confirm' title='Submit' data-toggle='tooltip' ><i class='fa fa-check-square-o' aria-hidden='true'></i> Submit</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

	<!--===============================================================================================-->
	<script src="../login_assets/vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="../login_assets/vendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script src="../login_assets/vendor/bootstrap/js/popper.js"></script>
	<script src="../login_assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="../login_assets/vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
	<script src="../login_assets/vendor/daterangepicker/moment.min.js"></script>
	<script src="../login_assets/vendor/daterangepicker/daterangepicker.js"></script>
	<!--===============================================================================================-->
	<script src="../login_assets/vendor/countdowntime/countdowntime.js"></script>
	<!--===============================================================================================-->
	<script src="../login_assets/js/main.js"></script>

    <script>
        var loading = false;
        $(".container").on("click", function() {
            // checkForm(false);
        } );
        $("#barcode").on("focus", function() {
            checkForm(true);
        } );
        $("#barcode").on("focusout", function() {
            // checkForm(false);
        } );
        $("#tgl_tutup").on("change", function() {
            checkForm(false);
            $("#barcode").val("");
            $("#response_pesan").html("");
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
            $(".WAREHOUSE_ALL").hide();
            let dataPost={check:"check_transaksi", val:$(this).val(),tgl_tutup: $("#tgl_tutup").val(),warehouse:$("#warehouse").val()};
            $.ajax({
                url: '<?=$baseUrl?>pages/ajax/stock_opname_gk_ajax.php',
                type: 'POST',
                data: dataPost,
                dataType: "JSON",
                success: function(response) {
                    loading = false;
                    if(response.success){ 
                        $("#opname_kode_obat").html(response.data.kode_obat);
                        $("#opname_nama_obat").html(response.data.nama_obat);
                        $("#opname_lot").html(response.data.lot);
                        $("#opname_total_qty_text").html(response.data.total_qty_text +" Kg");
                        // $("#opname_qty_dus_text").html(response.data.qty_dus_text);
                        $("#opname_pakingan_utuh_text").html(response.data.pakingan_utuh_text);
                        $("#opname_total_stock_text").html(response.data.total_stock_text);
                        // $("#opname_submit").html("submit");

                         $("#opname_id").val(response.data.id);
                         $("#opname_total_qty").val(response.data.total_qty);
                         if(response.data.qty_dus==0){
                            $("#opname_qty_dus").val("");
                         }else{
                            $("#opname_qty_dus").val(response.data.qty_dus);
                         }
                         $("#opname_pakingan_utuh").val(response.data.pakingan_utuh);
                         $("#opname_total_stock").val(response.data.total_stock);

                        $("#detailModal").modal("show");
                        $("#m-title").html(response.messages[1].kode_obat+" lot "+response.messages[1].lot);
                        $("#response_pesan").html(" ");
                        
                        
                        $(".WH_"+$("#warehouse").val()).show();
                    }else{
                        $("#response_pesan").html(response.messages[0]+" <button type='button' class='btn btn-outline-warning btn-sm' id='reset_barcode' title='reset'><i class='fa fa-ban' aria-hidden='true'></i></button>");
                    }
                },
                error: function() {
                }
            });
            }
        } );
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
            let pu = $("#opname_pakingan_utuh").val();
            
            let total_stock=Number(val)*pu;
            total_stock=total_stock.toFixed(2);

            $("#opname_total_stock_text").html(nilaiKeRibuan(total_stock, ".",","));
            $("#opname_total_stock").val(total_stock);
        });
        $(document).on('click', '.confirm', function() {
            let id = $("#opname_id").val();
            let dataPost={check:"simpan_stock",id_dt: id,qty_dus:$("#opname_qty_dus").val(),total_stock:$("#opname_total_stock").val()};
            $.ajax({
                url: '<?=$baseUrl?>pages/ajax/stock_opname_gk_ajax.php',
                type: 'POST',
                data: dataPost,
                dataType: "JSON",
                success: function(response) {
                    if(response.success){ 
                        $("#detailModal").modal("hide");
                    }else{
                        alert("Terjadi Error Update, mohon hubungi DIT");
                    }
                },
                error: function() {
                    alert("Jaringan Terputus, Gagal Confirm");
                }
            });
        });
        function formatAngka(val){
            var Num=val;
            Num += '';
            Num = Num.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1').replace(/^0[^.]/, '0');
            return Num;
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

        
    </script>
</body>

</html>