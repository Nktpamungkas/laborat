<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Form Matching</title>
</head>

<body>
	<?php
		ini_set("error_reporting", 1);
		session_start();
		include "koneksi.php";
		function nourut($str)
		{
			include "koneksi.php";
			// $res = preg_replace("/[^0-9]/", "", $str);
			// $fourStr = substr($res, 0, 4);
			// $fourInt = intval(substr($res, 4, 4) + 1);
			// $date = date('ym');

			// if ($fourStr != $date) {
			// 	$FChar = $date . "0001";
			// } else {
			// 	$FE = "";
			// 	if (strlen(strval($fourInt)) <= 3) {
			// 		for ($i = strlen(strval($fourInt)); $i <= 3; $i++) {
			// 			$FE = "0" . $fourInt;
			// 		}
			// 		$FChar = $date . $FE;
			// 	} else {
			// 		$FChar = $date . $fourInt;
			// 	}
			// }

			// $fourInt = $str + 1;
			return $str;
		}

		// $sqlNoResep = mysqli_query($con,"SELECT no_resep FROM tbl_matching where id = (select max(id) from tbl_matching) LIMIT 1");
		// $noResep = mysqli_fetch_array($sqlNoResep);
		// $nourut = nourut($noResep['no_resep']);

		$sqlNoResep = mysqli_query($con, "SELECT nourut FROM no_urut_matching");
		$noResep = mysqli_fetch_array($sqlNoResep);
		$nourut = nourut($noResep['nourut']) + 1;

		if ($_GET['idk'] != "") {
			$order 		= $_GET['idk'];
			$jns_match	= $_GET['jn_mcng'];
			// QUERY untuk Standart Cocok Warna Dan Lap Dip
			$stdcckwarna_lapdip = "CASE
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 1 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END			
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 2 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 3 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 4 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 5 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 6 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 7 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN ITXVIEW_COLORREMARKS.VALUESTRING
												ELSE SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, 1, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) -1)
											END	
									END AS LABDIPNO,
									CASE
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 1 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Labdip - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Labdip - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50) 
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 2 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'First Lot - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'First Lot - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50) 
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 3 THEN   
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Original - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Original - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50)
											END	
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 4 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Previous Order - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Previous Order - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50) 
											END
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 5 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Master Color - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Master Color - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50)
											END
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 6 THEN  
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Lampiran Buyer - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Lampiran Buyer - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50) 
											END
										WHEN ITXVIEW_COLORSTANDARD.VALUESTRING = 7 THEN 
											CASE
												WHEN LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING) = '0' THEN 'Body - ' || TRIM(ITXVIEW_COLORREMARKS.VALUESTRING)
												ELSE 'Body - ' || SUBSTR(ITXVIEW_COLORREMARKS.VALUESTRING, LOCATE('(', ITXVIEW_COLORREMARKS.VALUESTRING), 50)
											END
									END AS STDCCKWARNA,";

			if ($jns_match == "Matching Ulang NOW" or $jns_match == "Perbaikan NOW") {
				$query_langganan = db2_exec($conn1, "SELECT TRIM(s.CODE) AS PROJECTCODE, TRIM(ip.LANGGANAN) AS LANGGANAN, TRIM(ip.BUYER) AS BUYER
														FROM SALESORDER s 
														LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.CODE 
														WHERE s.CODE LIKE '%$order%'");
				$dt_langganan = db2_fetch_assoc($query_langganan);
			} else if ($jns_match == "LD NOW") {
				$query_langganan = db2_exec($conn1, "SELECT TRIM(s.CODE) AS PROJECTCODE, TRIM(ip.LANGGANAN) AS LANGGANAN, TRIM(ip.BUYER) AS BUYER
														FROM SALESORDER s 
														LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.CODE 
														WHERE s.CODE LIKE '%$order%'");
				$dt_langganan = db2_fetch_assoc($query_langganan);
			} else {
				$sqlLot = sqlsrv_query($conn, "SELECT
													x.*,dbo.fn_StockMovementDetails_GetTotalWeightPCC(0, x.PCBID) as Weight,
													dbo.fn_StockMovementDetails_GetTotalRollPCC(0, x.PCBID) as RollCount
												FROM( SELECT
													so.CustomerID, so.BuyerID,
													sod.ID as SODID, sod.ProductID, sod.UnitID, sod.WeightUnitID,
													pcb.ID as PCBID,pcb.UnitID as BatchUnitID,
													pcblp.DepartmentID,pcb.PCID,pcb.LotNo,pcb.ChildLevel,pcb.RootID
												FROM
													SalesOrders so INNER JOIN
													JobOrders jo ON jo.SOID=so.ID INNER JOIN
													SODetails sod ON so.ID = sod.SOID INNER JOIN
													SODetailsAdditional soda ON sod.ID = soda.SODID LEFT JOIN
													ProcessControlJO pcjo ON sod.ID = pcjo.SODID LEFT JOIN
													ProcessControlBatches pcb ON pcjo.PCID = pcb.PCID LEFT JOIN
													ProcessControlBatchesLastPosition pcblp ON pcb.ID = pcblp.PCBID LEFT JOIN
													ProcessFlowProcessNo pfpn ON pfpn.EntryType = 2 and pcb.ID = pfpn.ParentID AND pfpn.MachineType = 24 LEFT JOIN
													ProcessFlowDetailsNote pfdn ON pfpn.EntryType = pfdn.EntryType AND pfpn.ID = pfdn.ParentID
												WHERE jo.DocumentNo='" . $_GET['idk'] . "' AND pcb.Gross<>'0'
													GROUP BY
														so.SONumber, so.SODate, so.CustomerID, so.BuyerID, so.PONumber, so.PODate,jo.DocumentNo,
														sod.ID, sod.ProductID, sod.Quantity, sod.UnitID, sod.Weight, sod.WeightUnitID,
														soda.RefNo,pcb.DocumentNo,pcb.Dated,sod.RequiredDate,
														pcb.ID, pcb.DocumentNo, pcb.Gross,
														pcb.Quantity, pcb.UnitID, pcb.ScheduledDate, pcb.ProductionScheduledDate,
														pcblp.DepartmentID,pcb.LotNo,pcb.PCID,pcb.ChildLevel,pcb.RootID
													) x INNER JOIN
													ProductMaster pm ON x.ProductID = pm.ID LEFT JOIN
													Departments dep ON x.DepartmentID  = dep.ID LEFT JOIN
													Departments pdep ON dep.RootID = pdep.ID LEFT JOIN
													Partners cust ON x.CustomerID = cust.ID LEFT JOIN
													Partners buy ON x.BuyerID = buy.ID LEFT JOIN
													UnitDescription udq ON x.UnitID = udq.ID LEFT JOIN
													UnitDescription udw ON x.WeightUnitID = udw.ID LEFT JOIN
													UnitDescription udb ON x.BatchUnitID = udb.ID
												ORDER BY
													x.SODID, x.PCBID");
				$sLot = sqlsrv_fetch_array($sqlLot);

				$cLot = sqlsrv_num_rows($sqlLot);
				$child = $sLot['ChildLevel'];

				if ($child > 0) {
					$sqlgetparent = sqlsrv_query($conn, "select ID,LotNo from ProcessControlBatches where ID='$sLot[RootID]' and ChildLevel='0'");
					$rowgp = sqlsrv_fetch_array($sqlgetparent);

					$nomLot = $rowgp['LotNo'];
					$nomorLot = "$nomLot/K$sLot[ChildLevel]&nbsp;";
				} else {
					$nomorLot = $sLot['LotNo'];
				}

				$sqlLot1 = "Select count(*) as TotalLot From ProcessControlBatches where PCID='$sLot[PCID]' and RootID='0' and LotNo < '1000'";
				$qryLot1 = sqlsrv_query($conn, $sqlLot1) or die('A error occured : ');
				$rowLot = sqlsrv_fetch_array($qryLot1);

				$sqls = sqlsrv_query($conn, "select salesorders.customerid,salesorders.buyerid from Joborders
					left join salesorders on soid= salesorders.id
					where JobOrders.documentno='$_GET[idk]'", array(), array("Scrollable" => 'static'));
				$ssr = sqlsrv_fetch_array($sqls);
				$cek = sqlsrv_num_rows($sqls);
				$lgn1 = sqlsrv_query($conn, "select partnername from partners where id='$ssr[customerid]'");
				$ssr1 = sqlsrv_fetch_array($lgn1);
				$lgn2 = sqlsrv_query($conn, "select partnername from partners where id='$ssr[buyerid]'");
				$ssr2 = sqlsrv_fetch_array($lgn2);
			}
		}
	?>
	<?php
		$sqljkd = sqlsrv_query($conn, "select processcontrol.id as pcid,processcontrolJO.SODID,salesorders.ponumber,joborders.documentno,
											processcontrol.productid,salesorders.customerid,CONVERT(varchar(10), SODetails.RequiredDate, 121) as RequiredDate,
											salesorders.buyerid,processcontrolbatches.lotno,productcode,productmaster.color,colorno,description,productmaster.weight,cuttablewidth,
											SOSampleColor.OtherDesc,SOSampleColor.Flag,hangerno from Joborders
											Left join salesorders on soid= salesorders.id
											Left join SOSampleColor on SOSampleColor.SOID=SalesOrders.id
												Left join SODetails on SalesOrders.id=SODetails.SOID
											left join productmaster on productmaster.id= SODetails.productid
											left join productpartner on productpartner.productid= SODetails.productid
												left join processcontrolJO on processcontrolJO.joid = Joborders.id
												left join processcontrol on processcontrolJO.pcid = processcontrol.id
											left join processcontrolbatches on processcontrolbatches.pcid = processcontrol.id
											where productmaster.id='$_GET[iditem]' and processcontrol.productid='$_GET[iditem]' and JobOrders.documentno='$_GET[idk]' ", array(), array("Scrollable" => 'static'));
		$r1 = sqlsrv_fetch_array($sqljkd);
		$cek1 = sqlsrv_num_rows($sqljkd);
	?>
	<?php
		if (isset($_POST['simpan'])) {
			$ip_num = $_SERVER['REMOTE_ADDR'];
			$kain = str_replace("'", "''", $_POST['kain']);
			$benang = str_replace("'", "''", $_POST['benang']);
			$cocok_warna = str_replace("'", "''", $_POST['cocok_warna']);
			$warna = str_replace("'", "''", $_POST['warna']);
			$nowarna = str_replace("'", "", $_POST['no_warna']);
			$langganan = str_replace("'", "''", $_POST['langganan']);
			if ($_POST['salesman_sample'] == "1") {
				$salesman = "1";
			} else {
				$salesman = "0";
			}
			$char = preg_replace('/[^a-z]/i', '', $_POST['no_resep']);
			$noUrut = nourut($noResep['no_resep']);
			// $no_resep = $char . $nourut;
			$no_resep = $_POST['Dyestuff'] . $nourut;
			$recipe = str_replace("'", "''", $_POST['recipe_code']);
			$colorcode = str_replace("'", "''", $_POST['color_code']);
			$gLD = str_replace("'", "''", $_POST['g_LD']);

			if ($_POST['jen_matching'] == "Matching Ulang NOW") {
				$jnsMtch = "Matching Ulang";
			} else if ($_POST['jen_matching'] == "Perbaikan NOW") {
				$jnsMtch = "Perbaikan";
			} else {
				$jnsMtch = $_POST['jen_matching'];
			}

			$qry = mysqli_query($con, "INSERT INTO tbl_matching SET
				no_resep='$no_resep',
				no_order='$_POST[no_order]',
				no_po='$_POST[no_po]',
				langganan='$langganan',
				no_item='$_POST[no_item1]',
				jenis_kain='$kain',
				benang='$benang',
				tgl_in=now(),
				cocok_warna='$cocok_warna',
				warna='$warna',
				no_warna='$nowarna',
				lebar='$_POST[lebar]',
				qty_order='$_POST[qty]',
				gramasi='$_POST[gramasi]',
				proses='$_POST[proses]',
				buyer='$_POST[buyer]',
				tgl_delivery='$_POST[tgl_delivery]',
				jenis_matching='$jnsMtch',
				recipe_code='$recipe',
				color_code='$colorcode',
				g_ld='$gLD',
				tgl_buat= now(),
				tgl_update=now(),
				salesman_sample='" . $salesman . "',
				created_by = '$_SESSION[userLAB]'
				");

			// update nomor urut terakhir
			mysqli_query($con, "UPDATE no_urut_matching SET nourut = '$nourut'");

			if ($qry) {
				mysqli_query($con, "INSERT INTO log_status_matching SET
					`ids` = '$_POST[no_resep]',
					`status` = 'Create No.resep',
					`info` = 'generate no resep',
					`do_by` = '$_SESSION[userLAB]',
					`do_at` = '$time',
					`ip_address` = '$ip_num'");
				echo "<script>alert('Data Tersimpan');window.location.href='?p=form-matching-detail&noresep=$_POST[no_resep]';</script>";
			} else {
				echo "There's been a problem: " . mysqli_error();
			}
		}
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
									<label for="order" class="col-sm-2 control-label">Dyestuff</label>
									<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#staticBackdrop">
										...
									</button>
									<div class="col-sm-2">
										<select value="<?php echo $_GET['Dystf'] ?>" type="text" class="form-control" id="Dyestuff" name="Dyestuff" required>
											<option value="" selected disabled>Pilih...</option>
											<?php
											$sqlmstrcd = mysqli_query($con, "SELECT kode, `value` from tbl_mstrheadercd;");
											while ($li = mysqli_fetch_array($sqlmstrcd)) { ?>
												<option value="<?php echo $li['value'] ?>" <?php if ($li['value'] == $_GET['Dystf']) {
																								echo 'selected';
																							} ?>><?php echo $li['kode'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<input type="hidden" value="<?php echo $nourut; ?>" id="shadow_no_resep" name="shadow_no_resep">
								<div class=" form-group">
									<label for="order" class="col-sm-2 control-label">Rcode</label>
									<div class="col-sm-2">
										<input name="no_resep" type="text" class="form-control" id="no_resep" placeholder="No Resep" required readonly>
									</div>
								</div>
								<div class=" form-group">
									<label for="order" class="col-sm-2 control-label">J. Matching</label>
									<div class="col-sm-2">
										<select class="form-control" id="jen_matching" name="jen_matching" required>
											<option selected disabled>Pilih...</option>
											<option <?php if ($_GET['jn_mcng'] == "L/D") {
														echo "selected";
													} ?> value="L/D">L/D</option>
											<option <?php if ($_GET['jn_mcng'] == "LD NOW") {
														echo "selected";
													} ?> value="LD NOW">L/D NOW</option>
											<option <?php if ($_GET['jn_mcng'] == "Matching Ulang") {
														echo "selected";
													} ?> value="Matching Ulang">Matching Ulang</option>
											<option <?php if ($_GET['jn_mcng'] == "Perbaikan") {
														echo "selected";
													} ?> value="Perbaikan">Perbaikan</option>
											<option <?php if ($_GET['jn_mcng'] == "Matching Ulang NOW") {
														echo "selected";
													} ?> value="Matching Ulang NOW">Matching Ulang NOW</option>
											<option <?php if ($_GET['jn_mcng'] == "Perbaikan NOW") {
														echo "selected";
													} ?> value="Perbaikan NOW">Perbaikan NOW</option>
											<option <?php if ($_GET['jn_mcng'] == "Matching Development") {
														echo "selected";
													} ?> value="Matching Development">Matching Development</option>
										</select>
									</div>
								</div>
								<div id="echoing_the_choice">
									<div id="before_append">
										<div class=" form-group">
											<label for="order" class="col-sm-4 control-label" style="font-style: italic;"> Pilih Jenis Matching untuk men-generate form...</label>
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
							$sqlmstrcd = mysqli_query($con, "SELECT kode, keterangan from tbl_mstrheadercd;");
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

<!--/////////////////////////////////////////////////////////////// Matching_ulang_perbaikan -->
<div id="Matching_ulang_perbaikan" style="display: none;">
	<div class="form-group">
		<label for="order" class="col-sm-2 control-label">No Order</label>
		<div class="col-sm-4">
			<input name="no_order" placeholder="No order ..." type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control ordercuy" id="order" onchange="window.location='?p=Form-Matching&idk='+this.value+'&Dystf='+document.getElementById(`Dyestuff`).value+'&jn_mcng='+document.getElementById(`jen_matching`).value" value="<?php if ($_GET['idk'] != "") {
																																																																											echo $_GET['idk'];
																																																																										} ?>" placeholder="No Order" required>
		</div>
	</div>
	<div class="form-group">
		<label for="langganan" class="col-sm-2 control-label">Langganan</label>
		<div class="col-sm-8">
			<input name="langganan" type="text" class="form-control" id="langganan" placeholder="Langganan" value="<?php if ($cek > 0) {
																														echo $ssr1['partnername'] . "/" . $ssr2['partnername'];
																													} else {
																														echo $rw['langganan'];
																													} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="no_item" class="col-sm-2 control-label">Item</label>
		<div class="col-sm-10">
			<select name="no_item" class="form-control selectNoItem" id="no_item" onchange="window.location='?p=Form-Matching&idk=<?php echo $_GET['idk']; ?>&iditem='+this.value+'&Dystf='+document.getElementById(`Dyestuff`).value+'&jn_mcng='+document.getElementById(`jen_matching`).value" required style="width: 400px;">
				<?php
				$sqljk = sqlsrv_query($conn, "select productmaster.id,productpartner.productcode,productmaster.color,colorno,hangerno from Joborders
									left join salesorders on soid= salesorders.id
									left join SODetails on SalesOrders.id=SODetails.SOID
									left join productmaster on productmaster.id= SODetails.productid
									left join productpartner on productpartner.productid= SODetails.productid
									where JobOrders.documentno='$_GET[idk]'
									GROUP BY productmaster.id,productpartner.productcode,productmaster.color,
									productmaster.colorno,productmaster.hangerno");
				?>
				<option value="">Pilih</option>
				<?php while ($r = sqlsrv_fetch_array($sqljk)) {
				?>
					<option value="<?php echo $r['id']; ?>" <?php if ($_GET['iditem'] == $r['id']) {
																echo "SELECTED";
															} ?>>
						<?php echo $r['hangerno'] . "-" . $r['colorno'] . " | " . $r['color']; ?>
					</option>
				<?php
				} ?>
			</select>
			<input name="no_item1" type="hidden" class="form-control" id="no_item1" placeholder="No Item" value="<?php if ($cek1 > 0) {
																														if ($r1['hangerno'] != "") {
																															echo $r1['hangerno'];
																														} else {
																															echo $r1['productcode'];
																														}
																													} else {
																														echo $rw['no_item'];
																													} ?>">
		</div>
	</div>
	<?php
	$ko = sqlsrv_query($conn, "select  ko.KONo from
										ProcessControlJO pcjo inner join
										ProcessControl pc on pcjo.PCID = pc.ID left join
										KnittingOrders ko on pc.CID = ko.CID and pcjo.KONo = ko.KONo
									where
										pcjo.PCID = '$r1[pcid]'
								group by ko.KONo");
	$r2 = sqlsrv_fetch_array($ko);
	?>
	<div class="form-group">
		<label for="color_code" class="col-sm-2 control-label">Color Code</label>
		<div class="col-sm-4">
			<input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="">
		</div>
	</div>
	<div class="form-group">
		<label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
		<div class="col-sm-4">
			<!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
			<textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="no_po" class="col-sm-2 control-label">PO Greige</label>

		<div class="col-sm-4">
			<input name="no_po" type="text" class="form-control" id="no_po" placeholder="No PO" value="<?php echo $r2['KONo']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="kain" class="col-sm-2 control-label">Kain</label>
		<div class="col-sm-8">
			<input name="kain" type="text" class="form-control" id="kain" placeholder="Kain" value="<?php if ($cek1 > 0) {
																										echo htmlentities($r1['description'], ENT_QUOTES);
																									} else {
																										echo $rw['jenis_kain'];
																									} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Warna</label>
		<div class="col-sm-6">
			<input name="warna" type="text" class="form-control" id="warna" placeholder="Warna" value="<?php if ($cek1 > 0) {
																											echo $r1['color'];
																										} else {
																											echo $rw['warna'];
																										} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="no_warna" class="col-sm-2 control-label">LAB DIP NO</label>
		<div class="col-sm-6">
			<input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="LAB DIP NO" value="<?php if ($cek1 > 0) {
																														echo $r1['colorno'];
																													} else {
																														echo $rw['no_warna'];
																													} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="gramasi" class="col-sm-2 control-label">Gramasi</label>
		<div class="col-sm-2">
			<input name="lebar" type="text" class="form-control" id="lebar" placeholder="Inci" value="<?php if ($cek1 > 0) {
																											echo round($r1['cuttablewidth']);
																										} else {
																											echo $rw['warna'];
																										} ?>">
		</div>
		<div class="col-sm-2">
			<input name="gramasi" type="text" class="form-control" id="gramasi" placeholder="Gr/M2" value="<?php if ($cek1 > 0) {
																												echo round($r1['weight']);
																											} else {
																												echo $rw['warna'];
																											} ?>">
		</div>
	</div>
	<?php
	$bng = sqlsrv_query($conn, "SELECT CAST(SODetailsAdditional.Note AS NVARCHAR(255)) as note from Joborders
										left join processcontrolJO on processcontrolJO.joid = Joborders.id
										left join SODetailsAdditional on processcontrolJO.sodid=SODetailsAdditional.sodid
									WHERE  JobOrders.documentno='$_GET[idk]' and processcontrolJO.pcid='$r1[pcid]'");
	$r3 = sqlsrv_fetch_array($bng);
	?>
	<div class="form-group">
		<label for="benang" class="col-sm-2 control-label">Benang</label>
		<div class="col-sm-8">
			<textarea name="benang" rows="6" class="form-control" id="benang" placeholder="Benang"><?php if (htmlentities($r3['note'], ENT_QUOTES) != "") {
																										echo htmlentities($r3['note'], ENT_QUOTES);
																									} else {
																										echo $r3['note'];
																									}  ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
		<div class="col-sm-8">
			<input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna" value="<?php if ($r1['Flag'] == " 1") {
																																echo "Original Color";
																															} elseif ($r1['Flag'] == "2") {
																																echo "Color LD";
																															} else {
																																echo
																																$r1['OtherDesc'];
																															} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
		<div class="col-sm-3">
			<input name="tgl_delivery" type="text" class="form-control datepicker" id="tgl_delivery" placeholder="Tgl Delivery" value="<?php echo $r1['RequiredDate']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="salesman_sample" class="col-sm-2 control-label">Salesman Sample</label>
		<div class="col-sm-8">
			<input type="checkbox" id="salesman_sample" name="salesman_sample" <?php if ($cek > 0) {
																				} else {
																					if ($rw['salesman_sample'] == "1") {
																						echo "checked";
																					}
																				}  ?> value="1">
		</div>
	</div>
	<div class="form-group">
		<label for="qty" class="col-sm-2 control-label">Qty Order</label>
		<div class="col-sm-3">
			<input name="qty" type="text" required class="form-control" id="qty" placeholder="Qty Order">
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Buyer</label>
		<div class="col-sm-3">
			<select name="buyer" id="buyer" class="form-control selectBuyer1" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Lampu Buyer :</label>
		<div class="col-sm-10" id="lampu-buyer1">
			<!-- i do some magic here  -->
		</div>
	</div>
	<div class="form-group">
		<label for="proses" class="col-sm-2 control-label">Proses</label>
		<div class="col-sm-3">
			<select class="form-control selectProses1" name="proses" id="proses" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	<div class="box-footer">
		<div class="col-sm-2">
			<button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
		</div>
	</div>
</div>
<!-- ////////////////////////////////////////////////////////////////////// LD -->
<div id="LD" style="display: none;">
	<div class="form-group">
		<label for="order" class="col-sm-2 control-label">L/D Req No.</label>
		<div class="col-sm-4">
			<input name="no_order" type="text" class="form-control" id="order" required placeholder="Request Number">
		</div>
	</div>
	<div class="form-group">
		<label for="langganan" class="col-sm-2 control-label">Langganan</label>
		<div class="col-sm-6">
			<input name="langganan" type="text" class="form-control" id="langganan" placeholder="Langganan" required>
		</div>
	</div>
	<!-- hidden item -->
	<!-- <input type="hidden" name="no_item1" id="no_item1" class="form-control" value="-"> -->
	<input name="no_po" type="hidden" class="form-control" id="no_po" placeholder="No PO" value="-">
	<!-- <input name="kain" type="hidden" class="form-control" id="kain" placeholder="Kain" value="-"> -->
	<!--/ hidden kain -->
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">No. Item</label>
		<div class="col-sm-6">
			<input name="no_item1" type="text" class="form-control" id="no_item1" placeholder="No item" required>
		</div>
	</div>
	<div class="form-group">
		<label for="color_code" class="col-sm-2 control-label">Color Code</label>
		<div class="col-sm-4">
			<input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="">
		</div>
	</div>
	<div class="form-group">
		<label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
		<div class="col-sm-4">
			<!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
			<textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Jenis Kain</label>
		<div class="col-sm-6">
			<input name="kain" type="text" class="form-control" id="kain" placeholder="Jenis Kain" required>
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Warna</label>
		<div class="col-sm-6">
			<input name="warna" type="text" class="form-control" id="warna" placeholder="Warna" required>
		</div>
	</div>
	<div class="form-group">
		<label for="no_warna" class="col-sm-2 control-label">LAB DIP NO</label>
		<div class="col-sm-6">
			<input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="LAB DIP NO" required>
		</div>
	</div>
	<!-- HIDDEN INPUT -->
	<input name="lebar" type="hidden" value="-" class="form-control" id="lebar" placeholder="Inci">
	<input name="gramasi" type="hidden" value="-" class="form-control" id="gramasi" placeholder="Gr/M2">
	<input name="benang" value="-" class="form-control" id="benang" type="hidden" placeholder="Benang">
	<!-- HIDDEN INPUT -->
	<div class="form-group">
		<label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
		<div class="col-sm-6">
			<input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna" required>
		</div>
	</div>
	<div class="form-group">
		<label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
		<div class="col-sm-3">
			<input name="tgl_delivery" type="text" class="form-control datepicker" id="tgl_delivery" placeholder="Tgl Delivery" required>
		</div>
	</div>
	<!-- HIDDEN INPUT -->
	<input name="qty" type="hidden" value="0" class="form-control" id="qty" placeholder="Qty Order">
	<!-- HIDDEN INPUT -->

	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Buyer</label>
		<div class="col-sm-3">
			<select name="buyer" id="buyer" class="form-control selectBuyer2" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Lampu Buyer :</label>
		<div class="col-sm-10" id="lampu-buyer2">
			<!-- i do some magic here  -->
		</div>
	</div>
	<div class="form-group">
		<label for="proses" class="col-sm-2 control-label">Proses</label>
		<div class="col-sm-3">
			<select class="form-control selectProses2" name="proses" id="proses" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	<div class="box-footer">
		<div class="col-sm-2">
			<button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
		</div>
	</div>
</div>
<!-- ////////////////////////////////////////////////////////////////////// Development -->
<div id="Development" style="display: none;">
	<div class="form-group">
		<label for="order" class="col-sm-2 control-label">No Order</label>
		<div class="col-sm-4">
			<input name="no_order" type="text" class="form-control" id="order" onkeyup="this.value = this.value.toUpperCase();" required placeholder="No Order...">
		</div>
	</div>
	<div class="form-group">
		<label for="langganan" class="col-sm-2 control-label">Langganan</label>
		<div class="col-sm-8">
			<input name="langganan" type="text" class="form-control" id="langganan" placeholder="Langganan">
		</div>
	</div>
	<!-- HIDDEN -->
	<input name="no_po" type="hidden" class="form-control" id="no_po" value="-">
	<!-- HIDDEN -->
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">No. Item</label>
		<div class="col-sm-6">
			<input type="text" value="" name="no_item1" id="no_item1" class="form-control" required placeholder="No. item ...">
		</div>
	</div>
	<div class="form-group">
		<label for="color_code" class="col-sm-2 control-label">Color Code</label>
		<div class="col-sm-4">
			<input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="">
		</div>
	</div>
	<div class="form-group">
		<label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
		<div class="col-sm-4">
			<!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
			<textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Jenis Kain</label>
		<div class="col-sm-6">
			<input name="kain" type="text" value="" class="form-control" required id="kain" placeholder="Jenis kain...">
		</div>
	</div>
	<div class="form-group">
		<label for="warna" class="col-sm-2 control-label">Warna</label>
		<div class="col-sm-6">
			<input name="warna" type="text" class="form-control" id="warna" placeholder="Warna">
		</div>
	</div>
	<div class="form-group">
		<label for="no_warna" class="col-sm-2 control-label">LAB DIP NO</label>
		<div class="col-sm-6">
			<input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="LAB DIP NO">
		</div>
	</div>
	<!-- HIDDEN VALUE -->
	<div class="form-group">
		<label for="gramasi" class="col-sm-2 control-label">Gramasi</label>
		<div class="col-sm-2">
			<input name="lebar" required type="text" class="form-control" id="lebar" placeholder="Inci" value="<?php if ($cek1 > 0) {
																													echo round($r1['cuttablewidth']);
																												} else {
																													echo $rw['warna'];
																												} ?>">
		</div>
		<div class="col-sm-2">
			<input name="gramasi" required type="text" class="form-control" id="gramasi" placeholder="Gr/M2" value="<?php if ($cek1 > 0) {
																														echo round($r1['weight']);
																													} else {
																														echo $rw['warna'];
																													} ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="benang" class="col-sm-2 control-label">Benang</label>
		<div class="col-sm-8">
			<textarea name="benang" rows="6" class="form-control" id="benang" required placeholder="Benang">-</textarea>
		</div>
	</div>
	<!-- HIDDEN VALUE -->
	<div class="form-group">
		<label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
		<div class="col-sm-8">
			<input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna">
		</div>
	</div>
	<div class="form-group">
		<label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
		<div class="col-sm-3">
			<input name="tgl_delivery" type="text" class="form-control datepicker" id="tgl_delivery" placeholder="Tgl Delivery">
		</div>
	</div>
	<div class="form-group">
		<label for="salesman_sample" class="col-sm-2 control-label">Salesman Sample</label>
		<div class="col-sm-8">
			<input type="checkbox" id="salesman_sample" name="salesman_sample" <?php if ($cek > 0) {
																				} else {
																					if ($rw['salesman_sample'] == "1") {
																						echo "checked";
																					}
																				}  ?> value="1">
		</div>
	</div>
	<div class="form-group">
		<label for="qty" class="col-sm-2 control-label">Qty Order</label>
		<div class="col-sm-3">
			<input name="qty" type="text" required class="form-control" id="qty" placeholder="Qty Order">
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Buyer</label>
		<div class="col-sm-3">
			<select name="buyer" id="buyer" class="form-control selectBuyer3" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="lampu" class="col-sm-2 control-label">Lampu Buyer :</label>
		<div class="col-sm-10" id="lampu-buyer3">
			<!-- i do some magic here  -->
		</div>
	</div>
	<div class="form-group">
		<label for="proses" class="col-sm-2 control-label">Proses</label>
		<div class="col-sm-3">
			<select class="form-control selectProses3" required name="proses" id="proses" style="width: 100%;">
				<!-- i do some magic here  -->
			</select>
		</div>
	</div>
	<div class="box-footer">
		<div class="col-sm-2">
			<button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
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

		if ($('.form-control.ordercuy').val().length >= 12) {
			$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
			$('#Matching_ulang_perbaikan').appendTo('#echoing_the_choice');
			$("#Matching_ulang_perbaikan").show()
		} 
		// else if ($('.form-control.ordernowcuy').val().length >= 6) {
		// 	if ($('.form-control.ordernowcuyld').val().includes("LAB")) {
		// 		$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
		// 		$('#LDNOW').appendTo('#echoing_the_choice');
		// 		$("#LDNOW").show()
		// 	} else {
		// 		$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
		// 		$('#NowForm').appendTo('#echoing_the_choice');
		// 		$("#NowForm").show()
		// 	}
		// }

		let antrian = $('#shadow_no_resep').val();
		var no_resep_fix = $(this).find(":selected").val() + antrian;
		$('#no_resep').val(no_resep_fix);

		$('#Dyestuff').change(function() {
			var Q = $('#shadow_no_resep').val();
			var no_resep_fix = $(this).find(":selected").val() + Q;
			$('#no_resep').val(no_resep_fix);
		})

		$('#jen_matching').change(function() {
			if ($(this).find(":selected").val() == 'Matching Ulang' || $(this).find(":selected").val() == 'Perbaikan') {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#Matching_ulang_perbaikan').appendTo('#echoing_the_choice');
				$("#Matching_ulang_perbaikan").show()
			} else if ($(this).find(":selected").val() == 'L/D') {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#LD').appendTo('#echoing_the_choice');
				$("#LD").show()
			} else if ($(this).find(":selected").val() == 'LD NOW') {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#LDNOW').appendTo('#echoing_the_choice');
				$("#LDNOW").show()
			} else if ($(this).find(":selected").val() == "Matching Development") {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#Development').appendTo('#echoing_the_choice');
				$("#Development").show()
			} 
			// else if ($(this).find(":selected").val() == 'Matching Ulang NOW' || $(this).find(":selected").val() == 'Perbaikan NOW') {
			// 	$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
			// 	$('#NowForm').appendTo('#echoing_the_choice');
			// 	$("#NowForm").show()
			// }
		})

		$('.selectNoItem').select2();

		$('.selectNoItemNOW').on('click', function() {
			$(this).select2({});
		})

		$('.selectBuyer1').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/get_distinc_buyer.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			}).on('select2:select', function(evt) {
				var select_selected = $(this).find(':selected').val();
				$.ajax({
					dataType: "json",
					type: "POST",
					url: "pages/ajax/get_lampuFbuyer.php",
					data: {
						buyer: select_selected
					},
					success: function(response) {
						$('#lampu-buyer1').html('');
						$.each(response, function(key, value) {
							$('#lampu-buyer1').append('<div class="col-sm-2"><input class="form-control" value="' + value + '" readonly></div>')
						});
					},
					error: function() {
						alert("Hubungi Departement DIT !");
					}
				});
			});
		})

		$('.selectProses1').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/Get_List_process.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			})
		})

		$('.selectBuyer2').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/get_distinc_buyer.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			}).on('select2:select', function(evt) {
				var select_selected = $(this).find(':selected').val();
				$.ajax({
					dataType: "json",
					type: "POST",
					url: "pages/ajax/get_lampuFbuyer.php",
					data: {
						buyer: select_selected
					},
					success: function(response) {
						$('#lampu-buyer2').html('');
						$.each(response, function(key, value) {
							$('#lampu-buyer2').append('<div class="col-sm-2"><input class="form-control" value="' + value + '" readonly></div>')
						});
					},
					error: function() {
						alert("Hubungi Departement DIT !");
					}
				});
			});
		});

		$('.selectProses2').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/Get_List_process.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			})
		})

		$('.selectBuyer3').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/get_distinc_buyer.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			}).on('select2:select', function(evt) {
				var select_selected = $(this).find(':selected').val();
				$.ajax({
					dataType: "json",
					type: "POST",
					url: "pages/ajax/get_lampuFbuyer.php",
					data: {
						buyer: select_selected
					},
					success: function(response) {
						$('#lampu-buyer3').html('');
						$.each(response, function(key, value) {
							$('#lampu-buyer3').append('<div class="col-sm-2"><input class="form-control" value="' + value + '" readonly></div>')
						});
					},
					error: function() {
						alert("Hubungi Departement DIT !");
					}
				});
			});
		});

		$('.selectProses3').on('click', function() {
			$(this).select2({
				minimumInputLength: 0,
				allowClear: true,
				placeholder: 'Insert keyword',
				ajax: {
					dataType: 'json',
					url: 'pages/ajax/Get_List_process.php',
					delay: 300,
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function(data, page) {
						return {
							results: data
						};
					},
				}
			})
		})
	});
</script>

</html>