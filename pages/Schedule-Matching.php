<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
$time = date('Y-m-d H:i:s');
if (isset($_POST['savee'])) {
	$qry1 = mysqli_query($con, "UPDATE `tbl_status_matching` SET
		  `grp`='$_POST[grp]',
		  `matcher`='$_POST[matcher]',
		  `kt_status`='$_POST[ket_st]',
		  `ket`='$_POST[keteee]',
		  `edited_at`= '$time',
		  `edited_by`= '$_SESSION[userLAB]'
	  	   WHERE id = '$_POST[id]'
	  ");
	$ip_num = $_SERVER['REMOTE_ADDR'];
	mysqli_query($con, "INSERT INTO log_status_matching SET
		  `ids` = '$_GET[idm]', 
		  `status` = 'buka', 
		  `info`='edit at atur schedule',
		  `do_by` = '$_SESSION[userLAB]', 
		  `do_at` = '$time', 
		  `ip_address` = '$ip_num'");
	if ($qry1) {
		echo "<script>window.location.href='?p=Schedule-Matching'</script>";
	} else {
		echo "There's been a problem: " . mysqli_error();
	}
}
$po = urlencode($_GET['po']);
$qryPO = mysqli_query($con, "SELECT * FROM tbl_matching WHERE `no_resep`='$_GET[idm]' LIMIT 1");
$dPO = mysqli_fetch_array($qryPO);
$qryCek = mysqli_query($con, "SELECT * FROM tbl_status_matching WHERE `idm`='$_GET[idm]'");
$rCek = mysqli_fetch_array($qryCek);
?>
<?php
if (isset($_POST['save'])) {
	$ket = str_replace("'", "''", $_POST['ket']);
	mysqli_query($con, "INSERT INTO tbl_status_matching SET
		`idm`='$_POST[no_resep]',
    	`grp`='$_POST[grup]',
    	`matcher`='$_POST[matcher]',
		`ket`='$ket',
		`status`= 'buka',
		`kt_status`='$_POST[kt_status]',
		`created_at`= '$time',
		`created_by`= '$_SESSION[userLAB]',
		`mulai_at`= '$time',
		`mulai_by`= '$_SESSION[userLAB]'
		");
	$ip_num = $_SERVER['REMOTE_ADDR'];
	mysqli_query($con, "INSERT INTO log_status_matching SET
		`ids` = '$_POST[no_resep]', 
		`status` = 'buka', 
		`info` = 'buka resep', 
		`do_by` = '$_SESSION[userLAB]', 
		`do_at` = '$time', 
		`ip_address` = '$ip_num'");
	echo "<script>window.location.href='?p=Schedule-Matching'</script>";
}
?>
<div class="box box-info">
	<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
		<div class="box-header with-border">
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">

			<div class="form-group">
				<label for="no_resep" class="col-sm-2 control-label">RCODE</label>
				<div class="col-sm-2">
					<div class="input-group">
						<input name="no_resep" type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" id="no_resep" onchange="window.location='?p=Schedule-Matching&idm='+this.value" onBlur="window.location='?p=Schedule-Matching&idm='+this.value" value="<?php if ($_GET['idm'] != "") {
																																																											echo $_GET['idm'];
																																																										} ?>" placeholder="No Resep" required>
						<span class="input-group-addon"><a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-search"></i> </a></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="no_order" class="col-sm-2 control-label">No Order</label>
				<div class="col-sm-3">
					<div class="input-group">
						<input name="no_order" required type="text" class="form-control" id="no_order" value="<?php echo $dPO['no_order']; ?>" placeholder="No Order">
						<span class="input-group-addon">
							<a href="#" class="_merge"><strong>Add-order</strong> <i class="fa fa-link" aria-hidden="true"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="no_order" class="col-sm-2 control-label">Jenis Matching</label>
				<div class="col-sm-3">
					<input readonly name="no_order" required type="text" class="form-control" id="no_order" value="<?php echo $dPO['jenis_matching']; ?>" readonly placeholder="Jenis Matching">
				</div>
			</div>
			<div class="form-group">
				<label for="langgan" class="col-sm-2 control-label">Langganan</label>
				<div class="col-sm-6">
					<input readonly name="langgan" required type="text" class="form-control" id="langgan" value="<?php echo $dPO['langganan']; ?>" placeholder="Langganan">
				</div>
			</div>
			<div class="form-group">
				<label for="no_item" class="col-sm-2 control-label">No Item</label>
				<div class="col-sm-2">
					<input readonly name="no_item" type="text" class="form-control" id="no_item" value="<?php echo $dPO['no_item']; ?>" placeholder="No Item">
				</div>
			</div>
			<div class="form-group">
				<label for="no_item" class="col-sm-2 control-label">No Warna</label>
				<div class="col-sm-2">
					<input readonly name="no_warna" required type="text" class="form-control" id="no_warna" value="<?php echo $dPO['no_warna']; ?>" placeholder="No warna">
				</div>
			</div>
			<div class="form-group">
				<label for="no_item" class="col-sm-2 control-label">Warna</label>
				<div class="col-sm-2">
					<input readonly name="warna" required type="text" class="form-control" id="warna" value="<?php echo $dPO['warna']; ?>" placeholder="Warna">
				</div>
			</div>
			<div class="form-group">
				<label for="qty_order" class="col-sm-2 control-label">Qty Order</label>
				<div class="col-sm-2">
					<div class="input-group">
						<input readonly name="qty_order" type="text" class="form-control" id="qty_order" value="<?php echo $dPO['qty_order']; ?>" placeholder="0.00" required style="text-align: right;"><span class="input-group-addon">KG</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="proses" class="col-sm-2 control-label">Buyer</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" value="<?php echo $dPO['buyer']; ?>" readonly>
				</div>
			</div>
			<?php $sqlLamp = mysqli_query($con, "SELECT * FROM vpot_lampbuy where buyer = '$dPO[buyer]'"); ?>
			<div class="form-group">
				<label for="proses" class="col-sm-2 control-label text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lampu</label>
				<div class="col-sm-8">
					<?php while ($lamp = mysqli_fetch_array($sqlLamp)) { ?>
						<div class="col-sm-2">
							<input type="text" class="form-control input-sm" value="<?php echo $lamp['lampu'] ?>" readonly>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label for="proses" class="col-sm-2 control-label">Proses</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" value="<?php echo $dPO['proses']; ?>" readonly>
				</div>
			</div>
			<div class="form-group">
				<label for="grup" class="col-sm-2 control-label">Group</label>
				<div class="col-sm-2">
					<select name="grup" id="grup" class="form-control" required>
						<option selected disabled value="">Pilih</option>
						<option value="A">A</option>
						<option value="B">B</option>
						<option value="C">C</option>
						<option value="D">D</option>
						<option value="E">E</option>
						<option value="F">F</option>
						<option value="SA">SHIFT A</option>
						<option value="SB">SHIFT B</option>
						<option value="SC">SHIFT C</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="matcher" class="col-sm-2 control-label">Matcher</label>
				<div class="col-sm-2">
					<select name="matcher" id="matcher" class="form-control" required>
						<option selected disabled value="">Pilih</option>
						<?php $qrymc = mysqli_query($con, "SELECT * FROM tbl_matcher WHERE `status`='Aktif' ORDER BY nama ASC");
						while ($dmc = mysqli_fetch_array($qrymc)) { ?>
							<option value="<?php echo $dmc['nama']; ?>"><?php echo $dmc['nama']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="kt_status" class="col-sm-2 control-label">Ket. Status</label>
				<div class="col-sm-2">
					<select name="kt_status" id="kt_status" class="form-control">
						<option value="Normal">Normal</option>
						<option value="Urgent">Urgent</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="ket" class="col-sm-2 control-label">Keterangan</label>
				<div class="col-sm-6">
					<textarea name="ket" rows="4" class="form-control" id="Ket" placeholder="Keterangan"><?php echo $dPO['ket']; ?></textarea>
				</div>
			</div>
			<?php
			$qryM = mysqli_query($con, "SELECT * FROM tbl_status_matching WHERE `idm`='$_GET[idm]'");
			$rM = mysqli_fetch_array($qryM);
			?>

			<div class="box-footer">
				<div class="col-sm-3">
					<?php if ($rM['id'] == "") {
					?>
						<button type="submit" value="save" class="btn btn-block btn-social btn-linkedin <?php if ($_SESSION['lvlLAB'] == "3") {
																											echo "disabled";
																										} ?>" name="save" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
					<?php } else { ?>
						<div class="btn btn-sm btn-danger" role="alert">
							R-Code Telah digunakan !, silahkan buat/pilih R-code lain !
						</div>
					<?php } ?>
				</div>
			</div>
			<!-- /.box-footer -->



		</div>
	</form>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header with-border">

			</div>
			<div class="box-body">
				<table id="tablee" class="table" width="100%">
					<thead class="bg-green">
						<tr>
							<th width="24">
								<div align="center">No</div>
							</th>
							<th width="69">
								<div align="center">Kode</div>
							</th>
							<th width="80">
								<div align="center">Matcher</div>
							</th>
							<th width="69">
								<div align="center">Group</div>
							</th>
							<th width="94">
								<div align="center">Langganan</div>
							</th>
							<th width="89">
								<div align="center">No Order</div>
							</th>
							<th width="98">
								<div align="center">No Item</div>
							</th>
							<th width="82">
								<div align="center">Jenis Kain</div>
							</th>
							<th width="76">
								<div align="center">No Warna</div>
							</th>
							<th width="73">
								<div align="center">Warna</div>
							</th>
							<th width="100">
								<div align="center">Action</div>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$sql = mysqli_query($con, " SELECT a.id, a.idm, a.matcher, a.created_at, a.`status`, a.grp,b.langganan,
												b.no_order, b.no_item, b.warna, b.no_warna, b.jenis_kain
												FROM tbl_status_matching a
												JOIN tbl_matching b ON a.idm = b.no_resep
												where a.status in ('buka', 'mulai')
												ORDER BY a.id DESC
												LIMIT 100 ");
						while ($r = mysqli_fetch_array($sql)) {
							$no++;
							$bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
						?>
							<tr bgcolor="<?php echo $bgcolor; ?>">
								<td align="center">
									<?php echo $no; ?>
								</td>
								<td align="center">
									<?php echo $r['idm']; ?>
								</td>
								<td align="center">
									<?php echo $r['matcher']; ?>
								</td>
								<td align="center">
									<?php echo $r['grp']; ?>
								</td>
								<td align="left">
									<?php echo $r['langganan']; ?>
								</td>
								<td align="center">
									<?php echo $r['no_order']; ?>
								</td>
								<td align="center">
									<?php echo $r['no_item']; ?>
								</td>
								<td align="left">
									<?php echo $r['jenis_kain']; ?>
								</td>
								<td align="center">
									<?php echo $r['no_warna']; ?>
								</td>
								<td align="center">
									<?php echo $r['warna']; ?>
								</td>
								<td align="center">
									<div class="btn-group">
										<a href="#" class="btn btn-xs btn-primary dataMatching_edit <?php if ($_SESSION['lvlLAB'] == "3") {
																										echo "disabled";
																									} ?>" id="<?php echo $r['id']; ?>"><i class="fa fa-edit"></i> </a>&nbsp;&nbsp;
										<!-- <button data-target="#delDataM<?php echo $r['id'] ?>" data-toggle="modal" class="btn btn-xs btn-danger <php
																																				if ($_SESSION['lvlLAB'] == "3" or $r[status] != "buka") {
																																					echo " disabled";
																																				} ?>"><i class="fa fa-trash"></i> </button> -->

										<a href="pages/cetak/matching.php?idkk=<?php echo $r['idm'] ?>" class="btn btn-xs btn-warning">
											<i class="fa fa-print"></i>
										</a>
									</div>
								</td>
							</tr>
							<div id="delDataM<?php echo $r['id'] ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-sm">
									<div class="modal-content" style="margin-top:100px;">
										<form action="pages/action/Delete_from_status_matching.php" method="POST">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" style="text-align:center;">Are you sure to delete this information ?</h4>
											</div>
											<input type="hidden" name="id" value="<?php echo $r['id'] ?>">
											<div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
												<button type="submit" class="btn btn-danger">Delete</button>
												<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						<?php } ?>
					</tbody>
				</table>
				<div id="DataMatchingEdit" class="modal fade modal-3d-slit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade modal-3d-slit" id="myModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:90%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Data Matching</h4>
			</div>
			<div class="modal-body">
				<style>
					#data_server_tbl_matching {
						font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
						border-collapse: collapse;
						width: 100%;
						font-size: 9pt !important;
					}

					#data_server_tbl_matching td,
					#data_server_tbl_matching th {
						border: 1px solid #ddd;
						padding: 4px;
					}

					#data_server_tbl_matching tr:nth-child(even) {
						background-color: #f2f2f2;
					}

					#data_server_tbl_matching tr:hover {
						background-color: rgb(151, 170, 212);
					}

					#data_server_tbl_matching th {
						padding-top: 10px;
						padding-bottom: 10px;
						text-align: left;
						background-color: #4CAF50;
						color: white;
					}

					.pilih {
						text-decoration: underline;
					}
				</style>
				<table id="data_server_tbl_matching" class="table display compact" style="width:100%">
					<thead>
						<tr>
							<th width="5%">No</th>
							<th width="14%">No Resep</th>
							<th width="16%">No Order</th>
							<th width="40%">Po. Greige</th>
							<th width="40%">Warna</th>
							<th width="40%">No.warna</th>
							<th width="17%">Langganan</th>
							<th width="8%">No Item</th>
							<th width="8%">Status</th>

						</tr>
					</thead>
					<tbody>
						<!-- i do some magic here -->
					</tbody>
				</table>
				<script>
					$(document).ready(function() {
						var dataTable = $('#data_server_tbl_matching').DataTable({
							"processing": true,
							"serverSide": true,
							"order": [
								[0, "desc"]
							],
							"pageLength": 15,
							"ajax": {
								url: "pages/ajax/data_server_tbl_matching.php",
								type: "post",
								error: function() {
									$(".dataku-error").html("");
									$("#dataku").append('<tbody class="dataku-error"><tr><th colspan="8">Tidak ada data untuk ditampilkan</th></tr></tbody>');
									$("#dataku-error-proses").css("display", "none");
								}
							},
							"columnDefs": [{
								"targets": [0, 1, 2, 3, 4, 5, 6, 7, 8],
								"className": "text-center"
							}, {
								"orderable": false,
								"targets": [0, 1, 2, 3, 4, 5, 6, 7]
							}],
						});
					});
				</script>
			</div>
		</div>
	</div>
</div>

<!-- modal add order -->
<div class="modal fade modal-3d-slit" id="ModalMergeOrder" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div id="body_ModalMergeOrder" class="modal-dialog" style="width:95%">

	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('click', '._merge', function(e) {
			var m = $('#no_resep').val();
			console.log(m);
			$.ajax({
				url: "pages/ajax/merge_order_On_Unapproved.php",
				type: "GET",
				data: {
					idm: m,
				},
				success: function(ajaxData) {
					$("#body_ModalMergeOrder").html(ajaxData);
					$("#ModalMergeOrder").modal('show', {
						backdrop: 'false'
					});
				}
			});
		});
		$(document).ready(function() {
			$("#ModalMergeOrder").on("hidden.bs.modal", function() {
				$("#body_ModalMergeOrder").empty();
			});
		})
		$(document).on('click', '.pilih', function(e) {
			document.getElementById("no_resep").value = $(this).text();
			document.getElementById("no_resep").focus();
			$('#myModal').modal('hide');
		});
		$(document).on('click', '._hapus', function() {
			let rcode = $(this).parent().parent().next('td').find('.pilih').text();

			Swal.fire({
				title: 'Apakah anda yakin ?',
				text: `Aksi ini tidak dapat di rollback untuk ${rcode}`,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Hapus!'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						dataType: "json",
						type: "POST",
						url: "pages/ajax/delete_schedule_matching.php",
						data: {
							rcode: rcode
						},
						success: function(response) {
							if (response.session == "LIB_SUCCSS") {
								Swal.fire(
									'Deleted!',
									'Your data has been deleted.',
									'success'
								)
								setTimeout(function() {
									window.location.reload(1);
								}, 1000);
							} else {
								toastr.error("ajax error !")
							}
						},
						error: function() {
							alert("Error");
						}
					});
				}
			})
		})
	})
</script>