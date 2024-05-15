<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Form Testing</title>
	<script>
function uncheckAll() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = false;
    });
}
</script>
</head>

<body>
	<?php
		ini_set("error_reporting", 1);
		session_start();
		include "koneksi.php";
		$sqlData 	= mysqli_query($con,"SELECT * FROM tbl_test_qc where id = '$_GET[id]' LIMIT 1");
		$dataT 		= mysqli_fetch_array($sqlData);
		$detail2=explode(",",$dataT['permintaan_testing']);	

	?>	
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_1" data-toggle="tab">Input Order</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
							<div class="box-body">
								<div class="form-group">
									<label for="order" class="col-sm-2 control-label">Jenis Testing</label>
									<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#staticBackdrop">
										...
									</button>
									<div class="col-sm-2">
										<select value="<?php echo $_GET['Dystf'] ?>" type="text" class="form-control select2" id="Dyestuff" name="Dyestuff"  required>
											<option value="" selected disabled>Pilih Jenis Testing</option>
											<?php
											$sqlmstrcd = mysqli_query($con, "SELECT kode, `value` from tbl_mstrjnstesting;");
											while ($li = mysqli_fetch_array($sqlmstrcd)) { ?>
												<option value="<?php echo $li['value'] ?>" <?php if ($li['value'] == $dataT['jenis_testing']) {
																								echo 'selected';
																							} ?>><?php echo $li['kode'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class=" form-group">
									<label for="no_resep" class="col-sm-2 control-label">Counter</label>
									<div class="col-sm-2">
										<input name="counter" type="text" class="form-control" id="counter" placeholder="Counter" readonly value="<?php echo $dataT['no_counter'];?>">
									</div>
								</div>
								<div class="form-group">
		<label for="suffix" class="col-sm-2 control-label">Suffix</label>
		<div class="col-sm-4">
			<input name="suffix" placeholder="Suffix ..." type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control suffixcuy" id="order" value="<?php if ($_GET['id'] != "") { echo $dataT['suffix']; } ?>" required>
		</div>
	</div>
								<div class=" form-group">
									<label for="jen_matching" class="col-sm-2 control-label">Treatment</label>
									<div class="col-sm-3">
										<select class="form-control select2" multiple="multiple" id="jen_matching" name="jen_matching[]" data-placeholder="Pilih Jenis Treatment" required>
											<!--<option selected disabled>Pilih...</option>-->
											<option <?php if ($dataT['treatment'] == "non sublimasi / FIN") {
														echo "selected";
													} ?> value="non sublimasi / FIN">non sublimasi / FIN</option>
											<option <?php if ($dataT['treatment'] == "sublimasi 110C") {
														echo "selected";
													} ?> value="sublimasi 110C">sublimasi 110'C</option>
											<option <?php if ($dataT['treatment'] == "sublimasi 120C") {
														echo "selected";
													} ?> value="sublimasi 120C">sublimasi 120'C</option>
											<option <?php if ($dataT['treatment'] == "sublimasi 130C") {
														echo "selected";
													} ?> value="sublimasi 130C">sublimasi 130'C</option>
											<option <?php if ($dataT['treatment'] == "sublimasi 140C") {
														echo "selected";
													} ?> value="sublimasi 140C">sublimasi 140'C</option>
											<option <?php if ($dataT['treatment'] == "FINISHING (cotton/ CVC)") {
														echo "selected";
													} ?> value="FINISHING (cotton/ CVC)">FINISHING (cotton/ CVC)</option>
											<option <?php if ($dataT['treatment'] == "non WR") {
														echo "selected";
													} ?> value="non WR">non WR</option>
											<option <?php if ($dataT['treatment'] == "WR") {
														echo "selected";
													} ?> value="WR">WR</option>
											<option <?php if ($dataT['treatment'] == "non protx2") {
														echo "selected";
													} ?> value="non protx2">non protx2</option>
											<option <?php if ($dataT['treatment'] == "protx2") {
														echo "selected";
													} ?> value="protx2">protx2</option>
										</select>
									</div>
								</div>
								<div id="echoing_the_choice">
									<div id="before_append">
										<div class=" form-group">
											<label for="order" class="col-sm-4 control-label" style="font-style: italic;"> Pilih Jenis Treatment untuk men-generate form...</label>
										</div>
									</div>
								</div>
							</div>
						</form>
						<!-- /.box-body -->
					</div>
					<!-- /.tab-pane -->

				</div>
				<!-- /.tab-content -->
			</div>
			<!-- nav-tabs-custom -->
		</div>
		<!-- /.col -->
	</div>
</body>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-center" id="staticBackdropLabel">Rincian Kode</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid bg-light">
					<table id="tablee" class="display compact nowrap" style="width:100%">
						<thead>
							<th>No.</th>
							<th>Kode</th>
							<th class="text-center">Keterangan</th>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$sqlmstrcd = mysqli_query($con, "SELECT kode, keterangan from tbl_mstrjnstesting;");
							while ($title = mysqli_fetch_array($sqlmstrcd)) {
								echo '<tr><td>' . $i++ . '.</td>
									<td>' . $title['kode'] . '</td>
									<td>' . $title['keterangan'] . '</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- end modal -->
<div style="display: none;" id="hidding-choice">

</div>
<!--/////////////////////////////////////////////////////////////// inputanTest -->
<div id="inputanTest" style="display: none;">
	
	<div class="form-group">
		<label for="buyer" class="col-sm-2 control-label">Buyer</label>
		<div class="col-sm-8">
			<input name="buyer" type="text" class="form-control" id="buyer" placeholder="buyer" value="<?php echo $dataT['buyer']; ?>">
		</div>
	</div>	
	<div class="form-group">
		<label for="nowarna" class="col-sm-2 control-label">No Warna</label>
		<div class="col-sm-6">
			<input name="nowarna" type="text" class="form-control" id="nowarna" placeholder="No Warna" value="<?php echo $dataT['no_warna']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Nama Warna</label>
		<div class="col-sm-6">
			<input name="warna" type="text" class="form-control" id="warna" placeholder="Nama Warna" value="<?php echo $dataT['warna']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="noitem" class="col-sm-2 control-label">Item</label>
		<div class="col-sm-6">
			<input name="noitem" type="text" class="form-control" id="noitem" placeholder="No Item" value="<?php echo $dataT['no_item']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="jenis_kain" class="col-sm-2 control-label">Jenis Kain</label>
		<div class="col-sm-8">
			<input name="jenis_kain" type="text" class="form-control" id="jenis_kain" placeholder="Jenis Kain" value="<?php echo $dataT['jenis_kain']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="nama" class="col-sm-2 control-label">Nama Personil Testing</label>
		<div class="col-sm-6">
			<input name="nama" type="text" class="form-control" id="nama" placeholder="nama" value="<?php echo $dataT['nama_personil_test']; ?>" required>
		</div>
	</div>
	<div class="form-group">
		<label for="permintaan_testing" class="col-sm-2 control-label">Permintaan Testing</label>			
		<div class="col-sm-2">
						<label><input type="checkbox" class="minimal" name="colorfastness[]" value="WASHING" <?php if(in_array("WASHING",$detail2)){echo "checked";} ?>> Washing Fastness
						</label>
						<br>
						<label><input type="checkbox" class="minimal" name="colorfastness[]" value="PERSPIRATION ACID" <?php if(in_array("PERSPIRATION ACID",$detail2)){echo "checked";} ?>> Perpiration Fastness ACID
						</label>
						<br>
						<label><input type="checkbox" class="minimal" name="colorfastness[]" value="PERSPIRATION ALKALINE" <?php if(in_array("PERSPIRATION ACID",$detail2)){echo "checked";} ?>> Perpiration Fastness ALKALINE
						</label>					
						<br>
						<label><input type="checkbox" class="minimal" name="colorfastness[]" value="WATER" <?php if(in_array("WATER",$detail2)){echo "checked";} ?>> Water Fastness 
						</label>
						
						<br>
						<label><input type="checkbox" class="minimal" name="colorfastness[]" value="CROCKING" <?php if(in_array("CROCKING",$detail2)){echo "checked";} ?>> Crocking Fastness
						</label>
						<br>
						<label><input type="checkbox" class="minimal" name="colorfastness[]" value="COLOR MIGRATION-OVEN TEST" <?php if(in_array("COLOR MIGRATION-OVEN TEST",$detail2)){echo "checked";} ?>> Color Migration - Oven Test
						</label>
						<br>
						<label><input type="checkbox" class="minimal" name="colorfastness[]" value="COLOR MIGRATION" <?php if(in_array("COLOR MIGRATION",$detail2)){echo "checked";} ?>> Color Migration Fastness 
						<br>
						<label><input type="checkbox" class="minimal" name="colorfastness[]" value="CHLORIN & NON-CHLORIN" <?php if(in_array("CHLORIN & NON-CHLORIN",$detail2)){echo "checked";} ?>> Chlorin &amp; Non-Chlorin 
						</label>
						<br>
						<label><input type="checkbox" class="minimal" name="colorfastness[]" value="BLEEDING <?php if(in_array("BLEEDING",$detail2)){echo "checked";} ?>"> Bleeding 
						</label>
						<br>
						<label><input type="checkbox" class="minimal" name="colorfastness[]" value="PHENOLIC YELLOWING" <?php if(in_array("PHENOLIC YELLOWING",$detail2)){echo "checked";} ?>> Phenolic Yellowing 
						</label>
							
		</div>
		<div class="col-sm-2">
			<label><input type="checkbox" class="minimal" name="colorfastness[]" value="LIGHT" <?php if(in_array("LIGHT",$detail2)){echo "checked";} ?>> Light Fastness  
						</label> <br>
			<label><input type="checkbox" class="minimal" name="colorfastness[]" value="LIGHT PERSPIRATION" <?php if(in_array("LIGHT PERSPIRATION",$detail2)){echo "checked";} ?>> Light Perspiration  
						</label> <br>
			<label><input type="checkbox" class="minimal" name="colorfastness[]" value="PH" <?php if(in_array("PH",$detail2)){echo "checked";} ?>> PH3 &amp; PH4
						</label> <br>
			<label><input type="checkbox" class="minimal" name="colorfastness[]" value="SUHU" <?php if(in_array("SUHU",$detail2)){echo "checked";} ?>> SUHU 30'C &amp; 40'C  
						</label> <br>
		</div>
	</div>
	<div class="form-group">
		<label for="sts" class="col-sm-2 control-label"></label>
		<div class="col-sm-6">
		<label><input type="checkbox" class="minimal" name="colorfastness[]" value="" <?php if(in_array("",$detail2)){echo "checked";} ?>> Full Test</label>
		</div>	
	</div>	
	<div class="form-group">
		<label for="sts" class="col-sm-2 control-label">Status</label>
		<div class="col-sm-6">
		<select class="form-control select2" id="sts" name="sts" required>
		<option value="" selected disabled>Pilih status</option>
		<option value="normal" <?php if($dataT['sts']=="normal"){ echo "SELECTED"; } ?>>Normal</option>
		<option value="urgent" <?php if($dataT['sts']=="urgent"){ echo "SELECTED"; } ?>>Urgent</option>	
		</select>	
		</div>
	</div>	
	
</div>



<script>
	$(document).ready(function() {
		$('.datepicker').datepicker({
			autoclose: true,
			format: 'yyyy-mm-dd',
			todayHighlight: true,
		})

		if ($('.form-control.suffixcuy').val().length >= 2) {			
			
			
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#inputanTest').appendTo('#echoing_the_choice');
				$("#inputanTest").show()
			
		}

		let antrian = $('#shadow_no_resep').val();
		var no_resep_fix = antrian + $(this).find(":selected").val() ;
		$('#no_resep').val(no_resep_fix);

		$('#Dyestuff').change(function() {
			var Q = $('#shadow_no_resep').val();
			var no_resep_fix = Q + $(this).find(":selected").val()  ;
			$('#no_resep').val(no_resep_fix);
		})

		$('#jen_matching').change(function() {
			if ($(this).find(":selected").val() != '') { 
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#inputanTest').appendTo('#echoing_the_choice');
				$("#inputanTest").show()
			}
		})
		
	});
</script>

</html>