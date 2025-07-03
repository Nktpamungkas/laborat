<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Edit Matching</title>
</head>
<?php
    // ini_set("error_reporting", 1);
    // session_start();
    require_once('koneksi.php');
    $rcode = $_GET['rcode'];
    $sql = mysqli_query($con, "SELECT * FROM tbl_matching where no_resep = '$rcode' LIMIT 1");
    $data = mysqli_fetch_array($sql);
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
        $recipe = str_replace("'", "''", $_POST['recipe_code']);
        $colorcode = str_replace("'", "''", $_POST['color_code']);
        $tempCode = $_POST['temp_code'];
        $tempCode2 = $_POST['temp_code2'];

        $qry = mysqli_query($con, "UPDATE tbl_matching SET
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
            recipe_code='$recipe',
            color_code='$colorcode',
            lebar='$_POST[lebar]',
            qty_order='$_POST[qty]',
            gramasi='$_POST[gramasi]',
            proses='$_POST[proses]',
            buyer='$_POST[buyer]',
            tgl_delivery='$_POST[tgl_delivery]',
            jenis_matching='$_POST[jen_matching]',
            temp_code='$tempCode',
            temp_code2='$tempCode2'
            where no_resep = '$_POST[no_resep]' LIMIT 1
            ");

        if ($qry) {
            mysqli_query($con, "INSERT INTO log_status_matching SET
                `ids` = '$_POST[no_resep]',
                `status` = 'Belum bagi',
                `info` = 'Update data resep',
                `do_by` = '$_SESSION[userLAB]',
                `do_at` = '$time',
                `ip_address` = '$ip_num'");
            echo "<script>alert('Data Tersimpan');window.location.href='?p=List-Schedule';</script>";
        } else {
            echo "There's been a problem: " . mysqli_error();
        }
    }
?>


<body>
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">Edit Data Matching</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
                            <div class="box-body">
                                <input type="hidden" value="<?php echo $nourut; ?>" id="shadow_no_resep" name="shadow_no_resep">
                                <div class=" form-group">
                                    <label for="order" class="col-sm-2 control-label">Rcode</label>
                                    <div class="col-sm-2">
                                        <input name="no_resep" type="text" class="form-control" id="no_resep" placeholder="No Resep" required readonly value="<?php echo $_GET['rcode'] ?>">
                                    </div>
                                </div>
                                <div class=" form-group">
                                    <label for="order" class="col-sm-2 control-label">J. Matching</label>
                                    <div class="col-sm-2">
                                        <select class="form-control" id="jen_matching" name="jen_matching" required>
                                            <option selected disabled>Pilih...</option>
                                            <option <?php if ($data['jenis_matching'] == "L/D") {
                                                        echo "selected";
                                                    } ?> value="L/D">L/D</option>
                                            <option <?php if ($data['jenis_matching'] == "LD NOW") {
                                                        echo "selected";
                                                    } ?> value="LD NOW">L/D NOW</option>
                                            <option <?php if ($data['jenis_matching'] == "Matching Ulang") {
                                                        echo "selected";
                                                    } ?> value="Matching Ulang">Matching Ulang</option>
                                            <option <?php if ($data['jenis_matching'] == "Perbaikan") {
                                                        echo "selected";
                                                    } ?> value="Perbaikan">Perbaikan</option>
                                            <option <?php if ($data['jenis_matching'] == "Matching Ulang NOW") {
                                                        echo "selected";
                                                    } ?> value="Matching Ulang NOW">Matching Ulang NOW</option>
                                            <option <?php if ($data['jenis_matching'] == "Perbaikan NOW") {
                                                        echo "selected";
                                                    } ?> value="Perbaikan NOW">Perbaikan NOW</option>
                                            <option <?php if ($data['jenis_matching'] == "Matching Development") {
                                                        echo "selected";
                                                    } ?> value="Matching Development">Matching Development</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="echoing_the_choice">
                                    <div id="before_append">
                                        <!-- <div class=" form-group">
                                            <label for="order" class="col-sm-4 control-label" style="font-style: italic;">Pilih Jenis Matching untuk men-generate form...</label>
                                        </div> -->
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
            <input name="no_order" placeholder="No order ..." type="text" class="form-control ordercuy" id="order" value="<?php echo $data['no_order'] ?>" placeholder="No Order" required>
        </div>
    </div>
    <div class="form-group">
        <label for="langganan" class="col-sm-2 control-label">Langganan</label>
        <div class="col-sm-8">
            <input name="langganan" type="text" class="form-control" id="langganan" placeholder="Langganan" value="<?php echo $data['langganan'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="no_item" class="col-sm-2 control-label">Item</label>
        <div class="col-sm-10">
            <select name="no_item" class="form-control selectNoItem" id="no_item" required style="width: 400px;">
                <?php
                // $sqljk = sqlsrv_query($conn, "select productmaster.id,productpartner.productcode,productmaster.color,colorno,hangerno from Joborders
                //                     left join salesorders on soid= salesorders.id
                //                     left join SODetails on SalesOrders.id=SODetails.SOID
                //                     left join productmaster on productmaster.id= SODetails.productid
                //                     left join productpartner on productpartner.productid= SODetails.productid
                //                     where JobOrders.documentno='$data[no_order]'
                //                     GROUP BY productmaster.id,productpartner.productcode,productmaster.color,
                //                     productmaster.colorno,productmaster.hangerno");
                ?>
                <option value="">Pilih</option>
                <?php //while ($r = sqlsrv_fetch_array($sqljk)) { ?>
                    <option value="<?php //echo $r['id']; ?>" <?php //if ($r['hangerno'] == $data['no_item']) echo "selected"; ?>><?php //echo $r['hangerno'] . "-" . $r['colorno'] . " | " . $r['color']; ?></option>
                    <?php
                    // if ($r['hangerno'] == $data['no_item']) {
                    //     $idItem = $r['id'];
                    //     $order = $data['no_order'];
                    // }
                    ?>
                <?php //} ?>
            </select>
            <?php
                // $sqljkd = sqlsrv_query($conn, "select processcontrol.id as pcid,processcontrolJO.SODID,salesorders.ponumber,joborders.documentno,
                //                     processcontrol.productid,salesorders.customerid,CONVERT(varchar(10), SODetails.RequiredDate, 121) as RequiredDate,
                //                     salesorders.buyerid,processcontrolbatches.lotno,productcode,productmaster.color,colorno,description,productmaster.weight,cuttablewidth,
                //                     SOSampleColor.OtherDesc,SOSampleColor.Flag,hangerno from Joborders
                //                     Left join salesorders on soid= salesorders.id
                //                     Left join SOSampleColor on SOSampleColor.SOID=SalesOrders.id
                //                     Left join SODetails on SalesOrders.id=SODetails.SOID
                //                     left join productmaster on productmaster.id= SODetails.productid
                //                     left join productpartner on productpartner.productid= SODetails.productid
                //                     left join processcontrolJO on processcontrolJO.joid = Joborders.id
                //                     left join processcontrol on processcontrolJO.pcid = processcontrol.id
                //                     left join processcontrolbatches on processcontrolbatches.pcid = processcontrol.id
                //                     where productmaster.id='$idItem' and processcontrol.productid='$idItem' and JobOrders.documentno='$order' ");
                // $r1 = sqlsrv_fetch_array($sqljkd);
                // $cek1 = sqlsrv_num_rows($sqljkd);
            ?>
            <input name="no_item1" type="hidden" class="form-control" id="no_item1" placeholder="No Item" value="<?php echo $data['no_item']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="color_code" class="col-sm-2 control-label">Color Code</label>
        <div class="col-sm-4">
            <input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="<?php echo $data['color_code']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
        <div class="col-sm-4">
            <!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
            <textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"><?php echo $data['recipe_code']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="no_po" class="col-sm-2 control-label">PO Greige</label>
        <div class="col-sm-4">
            <input name="no_po" type="text" class="form-control" id="no_po" placeholder="No PO" value="<?php echo $data['no_po']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="kain" class="col-sm-2 control-label">Kain</label>
        <div class="col-sm-8">
            <input name="kain" type="text" class="form-control" id="kain" placeholder="Kain" value="<?php $data['jenis_kain']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="warna" class="col-sm-2 control-label">Warna</label>
        <div class="col-sm-6">
            <input name="warna" type="text" class="form-control" id="warna" placeholder="Warna" value="<?php echo $data['warna']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="no_warna" class="col-sm-2 control-label">No Warna</label>
        <div class="col-sm-6">
            <input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="No Warna" value="<?php echo $data['no_warna']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="lebar" class="col-sm-2 control-label">Lebar x Gramasi</label>
        <div class="col-sm-2">
            <input name="lebar" type="text" class="form-control" id="lebar" placeholder="Inci" value="<?php echo $data['lebar']; ?>">
        </div>
        <div class="col-sm-2">
            <input name="gramasi" type="text" class="form-control" id="gramasi" placeholder="Gr/M2" value="<?php echo $data['gramasi']; ?>">
        </div>
    </div>
    <?php
        // $bng = sqlsrv_query($conn, "SELECT CAST(SODetailsAdditional.Note AS NVARCHAR(255)) as note from Joborders
        // left join processcontrolJO on processcontrolJO.joid = Joborders.id
        // left join SODetailsAdditional on processcontrolJO.sodid=SODetailsAdditional.sodid
        // WHERE  JobOrders.documentno='$order' and processcontrolJO.pcid='$r1[pcid]'");
        // $r3 = sqlsrv_fetch_array($bng);
    ?>
    <div class="form-group">
        <label for="benang" class="col-sm-2 control-label">Benang</label>
        <div class="col-sm-8">
            <textarea name="benang" rows="6" class="form-control" id="benang" placeholder="Benang"><?php echo $data['benang'] ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
        <div class="col-sm-8">
            <input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna" value="<?= $data['cocok_warna']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
        <div class="col-sm-3">
            <input name="tgl_delivery" type="text" class="form-control datepicker" id="tgl_delivery" placeholder="Tgl Delivery" value="<?php $date_deliv = date_create($data['tgl_delivery']); echo date_format($date_deliv, "Y-m-d"); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="qty" class="col-sm-2 control-label">Qty Order</label>
        <div class="col-sm-3">
            <input name="qty" type="text" required class="form-control" id="qty" placeholder="Qty Order" value="<?php echo $data['qty_order'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="lampu" class="col-sm-2 control-label">Buyer</label>
        <div class="col-sm-3">
            <select name="buyer" id="buyer" class="form-control selectBuyer1" style="width: 100%;">
                <?php $sqlbuyer = mysqli_query($con, "SELECT id, buyer FROM vpot_lampbuy group by buyer order by id desc"); ?>
                <?php while ($option = mysqli_fetch_array($sqlbuyer)) { ?>
                    <option value="<?php echo $option['buyer'] ?>" <?php if ($data['buyer'] == $option['buyer']) echo "selected"; ?>><?php echo $option['buyer'] ?></option>
                <?php } ?>
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
                <?php $sqlprocss = mysqli_query($con, "SELECT nama_proses FROM master_proses where is_active = 'TRUE' order by id desc"); ?>
                <?php while ($procss = mysqli_fetch_array($sqlprocss)) { ?>
                    <option value="<?php echo $procss['nama_proses'] ?>" <?php if ($procss['nama_proses'] == $data['proses']) echo 'selected' ?>><?php echo $procss['nama_proses'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <!-- Temp 1 -->
	<div class="form-group">
		<label for="temp_code" class="col-sm-2 control-label">Temp</label>
		<div class="col-sm-2">
			<select name="temp_code" id="temp_code" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi akan diisi otomatis oleh JS -->
			</select>
		</div>
	</div>

	<!-- Temp 2  -->
	<div class="form-group" id="temp2-wrapper" style="display: none;">
		<label for="temp_code2" class="col-sm-2 control-label">Temp 2</label>
		<div class="col-sm-2">
			<select name="temp_code2" id="temp_code2" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi bisa diisi sama seperti temp_code jika perlu -->
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
            <input name="no_order" value="<?php echo $data['no_order'] ?>" type="text" class="form-control" id="order" required placeholder="Request Number">
        </div>
    </div>
    <div class="form-group">
        <label for="langganan" class="col-sm-2 control-label">Langganan</label>
        <div class="col-sm-6">
            <input name="langganan" type="text" value="<?php echo $data['langganan'] ?>" class="form-control" id="langganan" placeholder="Langganan" required>
        </div>
    </div>
    <!-- hidden item -->
    <!-- <input type="hidden" name="no_item1" id="no_item1" class="form-control" value="-"> -->
    <input name="no_po" type="hidden" class="form-control" id="no_po" placeholder="No PO" value="-">
    <!-- <input name="kain" type="hidden" class="form-control" id="kain" placeholder="Kain" value="-"> -->
    <!--/ hidden kain -->
    <div class="form-group">
        <label for="no_item1" class="col-sm-2 control-label">No. Item</label>
        <div class="col-sm-6">
            <input name="no_item1" type="text" class="form-control" id="no_item1" placeholder="No item" value="<?php echo $data['no_item'] ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label for="kain" class="col-sm-2 control-label">Jenis Kain</label>
        <div class="col-sm-6">
            <input name="kain" type="text" class="form-control" id="kain" placeholder="Jenis Kain" value="<?php echo $data['jenis_kain'] ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label for="color_code" class="col-sm-2 control-label">Color Code</label>
        <div class="col-sm-4">
            <input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="<?php echo $data['color_code']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
        <div class="col-sm-4">
            <!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
            <textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"><?php echo $data['recipe_code']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="warna" class="col-sm-2 control-label">Warna</label>
        <div class="col-sm-6">
            <input name="warna" type="text" class="form-control" id="warna" placeholder="Warna" value="<?php echo $data['warna'] ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label for="no_warna" class="col-sm-2 control-label">No Warna</label>
        <div class="col-sm-6">
            <input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="No Warna" value="<?php echo $data['no_warna'] ?>" required>
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
            <input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna" value="<?php echo $data['no_warna'] ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
        <div class="col-sm-3">
            <input name="tgl_delivery" type="text" class="form-control datepicker" id="tgl_delivery" value="<?php echo $data['tgl_delivery'] ?>" placeholder="Tgl Delivery" required>
        </div>
    </div>
    <!-- HIDDEN INPUT -->
    <input name="qty" type="hidden" value="0" class="form-control" id="qty" placeholder="Qty Order">
    <!-- HIDDEN INPUT -->

    <div class="form-group">
        <label for="lampu" class="col-sm-2 control-label">Buyer</label>
        <div class="col-sm-3">
            <select name="buyer" id="buyer" class="form-control selectBuyer2" style="width: 100%;">
                <?php $sqlbuyer = mysqli_query($con, "SELECT id, buyer FROM vpot_lampbuy group by buyer order by id desc"); ?>
                <?php while ($option = mysqli_fetch_array($sqlbuyer)) { ?>
                    <option value="<?php echo $option['buyer'] ?>" <?php if ($data['buyer'] == $option['buyer']) echo "selected"; ?>><?php echo $option['buyer'] ?></option>
                <?php } ?>
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
            <select class="form-control selectProses1" name="proses" id="proses" style="width: 100%;">
                <?php $sqlprocss = mysqli_query($con, "SELECT nama_proses FROM master_proses where is_active = 'TRUE' order by id desc"); ?>
                <?php while ($procss = mysqli_fetch_array($sqlprocss)) { ?>
                    <option value="<?php echo $procss['nama_proses'] ?>" <?php if ($procss['nama_proses'] == $data['proses']) echo 'selected' ?>><?php echo $procss['nama_proses'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <!-- Temp 1 -->
	<div class="form-group">
		<label for="temp_code" class="col-sm-2 control-label">Temp</label>
		<div class="col-sm-2">
			<select name="temp_code" id="temp_code" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi akan diisi otomatis oleh JS -->
			</select>
		</div>
	</div>

	<!-- Temp 2  -->
	<div class="form-group" id="temp2-wrapper" style="display: none;">
		<label for="temp_code2" class="col-sm-2 control-label">Temp 2</label>
		<div class="col-sm-2">
			<select name="temp_code2" id="temp_code2" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi bisa diisi sama seperti temp_code jika perlu -->
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
            <input name="no_order" type="text" class="form-control" id="order" value="<?php echo $data['no_order'] ?>" required placeholder="No Order...">
        </div>
    </div>
    <div class="form-group">
        <label for="langganan" class="col-sm-2 control-label">Langganan</label>
        <div class="col-sm-8">
            <input name="langganan" type="text" class="form-control" id="langganan" placeholder="Langganan" value="<?php echo $data['langganan'] ?>">
        </div>
    </div>
    <!-- HIDDEN -->
    <input name="no_po" type="hidden" class="form-control" id="no_po" value="-">
    <!-- HIDDEN -->
    <div class="form-group">
        <label for="warna" class="col-sm-2 control-label">No. Item</label>
        <div class="col-sm-6">
            <input type="text" name="no_item1" id="no_item1" class="form-control" required placeholder="No. item ..." value="<?php echo $data['no_item'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="warna" class="col-sm-2 control-label">Jenis Kain</label>
        <div class="col-sm-6">
            <input name="kain" type="text" class="form-control" required id="kain" placeholder="Jenis kain..." value="<?php echo $data['jenis_kain'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="color_code" class="col-sm-2 control-label">Color Code</label>
        <div class="col-sm-4">
            <input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="<?php echo $data['color_code']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
        <div class="col-sm-4">
            <!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
            <textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"><?php echo $data['recipe_code']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="warna" class="col-sm-2 control-label">Warna</label>
        <div class="col-sm-6">
            <input name="warna" type="text" class="form-control" id="warna" placeholder="Warna" value="<?php echo $data['warna'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="no_warna" class="col-sm-2 control-label">No Warna</label>
        <div class="col-sm-6">
            <input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="No Warna" value="<?php echo $data['no_warna'] ?>">
        </div>
    </div>
    <!-- HIDDEN VALUE -->
    <div class="form-group">
        <label for="gramasi" class="col-sm-2 control-label">Gramasi</label>
        <div class="col-sm-2">
            <input name="lebar" required type="text" class="form-control" id="lebar" placeholder="Inci" value="<?php echo $data['lebar'] ?>">
        </div>
        <div class="col-sm-2">
            <input name="gramasi" required type="text" class="form-control" id="gramasi" placeholder="Gr/M2" value="<?php echo $data['gramasi'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="benang" class="col-sm-2 control-label">Benang</label>
        <div class="col-sm-8">
            <textarea name="benang" rows="6" class="form-control" id="benang" required placeholder="Benang"><?php echo $data['benang'] ?></textarea>
        </div>
    </div>
    <!-- HIDDEN VALUE -->
    <div class="form-group">
        <label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
        <div class="col-sm-8">
            <input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna" value="<?php echo $data['cocok_warna'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
        <div class="col-sm-3">
            <input name="tgl_delivery" type="text" class="form-control datepicker" id="tgl_delivery" placeholder="Tgl Delivery" value="<?php echo $data['tgl_delivery'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="qty" class="col-sm-2 control-label">Qty Order</label>
        <div class="col-sm-3">
            <input name="qty" type="text" required class="form-control" id="qty" placeholder="Qty Order" value="<?php echo $data['qty_order'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="lampu" class="col-sm-2 control-label">Buyer</label>
        <div class="col-sm-3">
            <select name="buyer" id="buyer" class="form-control selectBuyer3" style="width: 100%;">
                <?php $sqlbuyer = mysqli_query($con, "SELECT id, buyer FROM vpot_lampbuy group by buyer order by id desc"); ?>
                <?php while ($option = mysqli_fetch_array($sqlbuyer)) { ?>
                    <option value="<?php echo $option['buyer'] ?>" <?php if ($data['buyer'] == $option['buyer']) echo "selected"; ?>><?php echo $option['buyer'] ?></option>
                <?php } ?>
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
            <select class="form-control selectProses3" name="proses" id="proses" style="width: 100%;">
                <?php $sqlprocss = mysqli_query($con, "SELECT nama_proses FROM master_proses where is_active = 'TRUE' order by id desc"); ?>
                <?php while ($procss = mysqli_fetch_array($sqlprocss)) { ?>
                    <option value="<?php echo $procss['nama_proses'] ?>" <?php if ($procss['nama_proses'] == $data['proses']) echo 'selected' ?>><?php echo $procss['nama_proses'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <!-- Temp 1 -->
	<div class="form-group">
		<label for="temp_code" class="col-sm-2 control-label">Temp</label>
		<div class="col-sm-2">
			<select name="temp_code" id="temp_code" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi akan diisi otomatis oleh JS -->
			</select>
		</div>
	</div>

	<!-- Temp 2  -->
	<div class="form-group" id="temp2-wrapper" style="display: none;">
		<label for="temp_code2" class="col-sm-2 control-label">Temp 2</label>
		<div class="col-sm-2">
			<select name="temp_code2" id="temp_code2" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi bisa diisi sama seperti temp_code jika perlu -->
			</select>
		</div>
	</div>

    <div class="box-footer">
        <div class="col-sm-2">
            <button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
        </div>
    </div>
</div>
<div id="LDNOW" style="display: none;">
    <div class="form-group">
        <label for="order" class="col-sm-2 control-label">L/D Req No.</label>
        <div class="col-sm-4">
            <input name="no_order" value="<?php echo $data['no_order'] ?>" type="text" class="form-control ordernowcuyld" id="order" required placeholder="Request Number">
        </div>
    </div>
    <div class="form-group">
        <label for="langganan" class="col-sm-2 control-label">Langganan</label>
        <div class="col-sm-6">
            <input name="langganan" type="text" value="<?php echo $data['langganan'] ?>" class="form-control" id="langganan" placeholder="Langganan" required>
        </div>
    </div>
    <!-- hidden item -->
    <!-- <input type="hidden" name="no_item1" id="no_item1" class="form-control" value="-"> -->
    <input name="no_po" type="hidden" class="form-control" id="no_po" placeholder="No PO" value="-">
    <!-- <input name="kain" type="hidden" class="form-control" id="kain" placeholder="Kain" value="-"> -->
    <!--/ hidden kain -->
    <div class="form-group">
        <label for="no_item1" class="col-sm-2 control-label">No. Item</label>
        <div class="col-sm-6">
            <input name="no_item1" type="text" class="form-control" id="no_item1" placeholder="No item" value="<?php echo $data['no_item'] ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label for="kain" class="col-sm-2 control-label">Jenis Kain</label>
        <div class="col-sm-6">
            <input name="kain" type="text" class="form-control" id="kain" placeholder="Jenis Kain" value="<?php echo $data['jenis_kain'] ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label for="color_code" class="col-sm-2 control-label">Color Code</label>
        <div class="col-sm-4">
            <input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="<?php echo $data['color_code']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
        <div class="col-sm-4">
            <!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
            <textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"><?php echo $data['recipe_code']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="no_po" class="col-sm-2 control-label">PO Greige</label>
        <div class="col-sm-4">
            <input name="no_po" type="text" class="form-control" id="no_po" placeholder="No PO" value="<?php echo $data['no_po']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="warna" class="col-sm-2 control-label">Warna</label>
        <div class="col-sm-6">
            <input name="warna" type="text" class="form-control" id="warna" placeholder="Warna" value="<?php echo $data['warna'] ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label for="no_warna" class="col-sm-2 control-label">No Warna</label>
        <div class="col-sm-6">
            <input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="No Warna" value="<?php echo $data['no_warna'] ?>" required>
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
            <input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna" value="<?php echo $data['cocok_warna'] ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
        <div class="col-sm-3">
            <input name="tgl_delivery" type="text" class="form-control datepicker" id="tgl_delivery" value="<?php echo $data['tgl_delivery'] ?>" placeholder="Tgl Delivery" required>
        </div>
    </div>
    <!-- HIDDEN INPUT -->
    <input name="qty" type="hidden" value="0" class="form-control" id="qty" placeholder="Qty Order">
    <!-- HIDDEN INPUT -->

    <div class="form-group">
        <label for="lampu" class="col-sm-2 control-label">Buyer</label>
        <div class="col-sm-3">
            <select name="buyer" id="buyer" class="form-control selectBuyer2" style="width: 100%;">
                <?php $sqlbuyer = mysqli_query($con, "SELECT id, buyer FROM vpot_lampbuy group by buyer order by id desc"); ?>
                <?php while ($option = mysqli_fetch_array($sqlbuyer)) { ?>
                    <option value="<?php echo $option['buyer'] ?>" <?php if ($data['buyer'] == $option['buyer']) echo "selected"; ?>><?php echo $option['buyer'] ?></option>
                <?php } ?>
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
            <select class="form-control selectProses1" name="proses" id="proses" style="width: 100%;">
                <?php $sqlprocss = mysqli_query($con, "SELECT nama_proses FROM master_proses where is_active = 'TRUE' order by id desc"); ?>
                <?php while ($procss = mysqli_fetch_array($sqlprocss)) { ?>
                    <option value="<?php echo $procss['nama_proses'] ?>" <?php if ($procss['nama_proses'] == $data['proses']) echo 'selected' ?>><?php echo $procss['nama_proses'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <!-- Temp 1 -->
	<div class="form-group">
		<label for="temp_code" class="col-sm-2 control-label">Temp</label>
		<div class="col-sm-2">
			<select name="temp_code" id="temp_code" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi akan diisi otomatis oleh JS -->
			</select>
		</div>
	</div>

	<!-- Temp 2  -->
	<div class="form-group" id="temp2-wrapper" style="display: none;">
		<label for="temp_code2" class="col-sm-2 control-label">Temp 2</label>
		<div class="col-sm-2">
			<select name="temp_code2" id="temp_code2" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi bisa diisi sama seperti temp_code jika perlu -->
			</select>
		</div>
	</div>

    <div class="box-footer">
        <div class="col-sm-2">
            <button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
        </div>
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////// NowForm -->
<div id="NowForm" style="display: none;">
    <div class="form-group">
        <label for="order" class="col-sm-2 control-label">No Order</label>
        <div class="col-sm-4">
            <input name="no_order" placeholder="No order ..." type="text" class="form-control ordernowcuy" id="order" value="<?php echo $data['no_order'] ?>" placeholder="No Order" required>
        </div>
    </div>
    <div class="form-group">
        <label for="langganan" class="col-sm-2 control-label">Langganan</label>
        <div class="col-sm-8">
            <input name="langganan" type="text" class="form-control" id="langganan" placeholder="Langganan" value="<?php echo $data['langganan'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="no_item" class="col-sm-2 control-label">Item</label>
        <div class="col-sm-10">
            <select name="no_item" class="form-control selectNoItem" id="no_item" required style="width: 400px;">
                <option value="<?= $data['no_item']; ?>" selected><?= $data['no_item']; ?></option>
            </select>
            <input name="no_item1" type="hidden" class="form-control" id="no_item1" placeholder="No Item" value="<?= $data['no_item']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="color_code" class="col-sm-2 control-label">Color Code</label>
        <div class="col-sm-4">
            <input name="color_code" type="text" class="form-control" id="color_code" placeholder="Color Code" value="<?php echo $data['color_code']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="recipe_code" class="col-sm-2 control-label">Recipe Code</label>
        <div class="col-sm-4">
            <!--<input name="recipe_code" type="text" class="form-control" id="recipe_code" placeholder="Recipe Code" value="">-->
            <textarea name="recipe_code" class="form-control" id="recipe_code" placeholder="Recipe Code"><?php echo $data['recipe_code']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="no_po" class="col-sm-2 control-label">PO Greige</label>
        <div class="col-sm-4">
            <input name="no_po" type="text" class="form-control" id="no_po" placeholder="No PO" value="<?php echo $data['no_po']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="kain" class="col-sm-2 control-label">Kain</label>
        <div class="col-sm-8">
            <input name="kain" type="text" class="form-control" id="kain" placeholder="Kain" value="<?php echo $data['jenis_kain']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="warna" class="col-sm-2 control-label">Warna</label>
        <div class="col-sm-6">
            <input name="warna" type="text" class="form-control" id="warna" placeholder="Warna" value="<?php echo $data['warna']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="no_warna" class="col-sm-2 control-label">No Warna</label>
        <div class="col-sm-6">
            <input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="No Warna" value="<?php echo $data['no_warna']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="lebar" class="col-sm-2 control-label">Lebar x Gramasi</label>
        <div class="col-sm-2">
            <input name="lebar" type="text" class="form-control" id="lebar" placeholder="Inci" value="<?php echo $data['lebar']; ?>">
        </div>
        <div class="col-sm-2">
            <input name="gramasi" type="text" class="form-control" id="gramasi" placeholder="Gr/M2" value="<?php echo $data['gramasi']; ?>">
        </div>
    </div>
    <?php
    //     $bng = sqlsrv_query($conn, "SELECT CAST(SODetailsAdditional.Note AS NVARCHAR(255)) as note from Joborders
    //     left join processcontrolJO on processcontrolJO.joid = Joborders.id
    //     left join SODetailsAdditional on processcontrolJO.sodid=SODetailsAdditional.sodid
    //     WHERE  JobOrders.documentno='$order' and processcontrolJO.pcid='$r1[pcid]'");
    // $r3 = sqlsrv_fetch_array($bng);
    ?>
    <div class="form-group">
        <label for="benang" class="col-sm-2 control-label">Benang</label>
        <div class="col-sm-8">
            <textarea name="benang" rows="6" class="form-control" id="benang" placeholder="Benang"><?php echo $data['benang'] ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="cocok_warna" class="col-sm-2 control-label">Cocok Warna</label>
        <div class="col-sm-8">
            <input name="cocok_warna" type="text" class="form-control" id="cocok_warna" placeholder="Cocok Warna" value="<?= $data['cocok_warna']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="tgl_delivery" class="col-sm-2 control-label">Tgl Delivery</label>
        <div class="col-sm-3">
            <input name="tgl_delivery" type="text" class="form-control datepicker" id="tgl_delivery" placeholder="Tgl Delivery" value="<?php $date_deliv = date_create($data['tgl_delivery']); echo date_format($date_deliv, "Y-m-d"); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="qty" class="col-sm-2 control-label">Qty Order</label>
        <div class="col-sm-3">
            <input name="qty" type="text" required class="form-control" id="qty" placeholder="Qty Order" value="<?php echo $data['qty_order'] ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="lampu" class="col-sm-2 control-label">Buyer</label>
        <div class="col-sm-3">
            <select name="buyer" id="buyer" class="form-control selectBuyer1" style="width: 100%;">
                <?php $sqlbuyer = mysqli_query($con, "SELECT id, buyer FROM vpot_lampbuy group by buyer order by id desc"); ?>
                <?php while ($option = mysqli_fetch_array($sqlbuyer)) { ?>
                    <option value="<?php echo $option['buyer'] ?>" <?php if ($data['buyer'] == $option['buyer']) echo "selected"; ?>><?php echo $option['buyer'] ?></option>
                <?php } ?>
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
                <?php $sqlprocss = mysqli_query($con, "SELECT nama_proses FROM master_proses where is_active = 'TRUE' order by id desc"); ?>
                <?php while ($procss = mysqli_fetch_array($sqlprocss)) { ?>
                    <option value="<?php echo $procss['nama_proses'] ?>" <?php if ($procss['nama_proses'] == $data['proses']) echo 'selected' ?>><?php echo $procss['nama_proses'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <!-- Temp 1 -->
	<div class="form-group">
		<label for="temp_code" class="col-sm-2 control-label">Temp</label>
		<div class="col-sm-2">
			<select name="temp_code" id="temp_code" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi akan diisi otomatis oleh JS -->
			</select>
		</div>
	</div>

	<!-- Temp 2  -->
	<div class="form-group" id="temp2-wrapper" style="display: none;">
		<label for="temp_code2" class="col-sm-2 control-label">Temp 2</label>
		<div class="col-sm-2">
			<select name="temp_code2" id="temp_code2" class="form-control">
			<option value="">Pilih...</option>
			<!-- Opsi bisa diisi sama seperti temp_code jika perlu -->
			</select>
		</div>
	</div>

    <div class="box-footer">
        <div class="col-sm-2">
            <button type="submit" class="btn btn-block btn-social btn-linkedin" name="simpan" style="width: 80%">Simpan <i class="fa fa-save"></i></button>
        </div>
    </div>
</div>
<!-- Development -->
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        })

        $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/ajax/get_lampuFbuyer.php",
            data: {
                buyer: $('.selectBuyer1').find(':selected').val()
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

        $('.selectBuyer1').on('change', function() {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/get_lampuFbuyer.php",
                data: {
                    buyer: $(this).find(':selected').val()
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
        })

        $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/ajax/get_lampuFbuyer.php",
            data: {
                buyer: $('.selectBuyer2').find(':selected').val()
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

        $('.selectBuyer2').on('change', function() {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/get_lampuFbuyer.php",
                data: {
                    buyer: $(this).find(':selected').val()
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
        })
        $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/ajax/get_lampuFbuyer.php",
            data: {
                buyer: $('.selectBuyer3').find(':selected').val()
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

        $('.selectBuyer3').on('change', function() {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/get_lampuFbuyer.php",
                data: {
                    buyer: $(this).find(':selected').val()
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
        })

        if ($('.form-control.ordercuy').val().length >= 12) {
			$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
			$('#Matching_ulang_perbaikan').appendTo('#echoing_the_choice');
			$("#Matching_ulang_perbaikan").show();
            toggleTemp2();
		} else if ($('.form-control.ordernowcuy').val().length >= 6) {
			if ($('.form-control.ordernowcuyld').val().includes("LAB")) {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#LDNOW').appendTo('#echoing_the_choice');
				$("#LDNOW").show();
                toggleTemp2();
			} else {
				$("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
				$('#NowForm').appendTo('#echoing_the_choice');
				$("#NowForm").show();
                toggleTemp2();
			}
		}

        if ($(this).find(":selected").val() == 'Matching Ulang' || $(this).find(":selected").val() == 'Perbaikan') {
            $("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
            $('#Matching_ulang_perbaikan').appendTo('#echoing_the_choice');
            $("#Matching_ulang_perbaikan").show();
            toggleTemp2();
        } else if ($(this).find(":selected").val() == 'L/D') {
            $("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
            $('#LD').appendTo('#echoing_the_choice');
            $("#LD").show();
            toggleTemp2();
        } else if ($(this).find(":selected").val() == 'LD NOW') {
            $("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
            $('#LDNOW').appendTo('#echoing_the_choice');
            $("#LDNOW").show();
            toggleTemp2();
        } else if ($(this).find(":selected").val() == "Matching Development") {
            $("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
            $('#Development').appendTo('#echoing_the_choice');
            $("#Development").show();
            toggleTemp2();
        } else if ($(this).find(":selected").val() == 'Matching Ulang NOW' || $(this).find(":selected").val() == 'Perbaikan NOW') {
            $("#echoing_the_choice").children(":first").appendTo('#hidding-choice');
            $('#NowForm').appendTo('#echoing_the_choice');
            $("#NowForm").show();
            toggleTemp2();
        }


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

<!-- <script>
	function toggleTemp2() {
		const noResepInput = document.getElementById('no_resep');
		const temp2Wrapper = document.getElementById('temp2-wrapper');

		if (noResepInput && temp2Wrapper) {
			// Cek apakah value no_resep diawali dengan 'DR'
			if (noResepInput.value.trim().toUpperCase().startsWith('DR')) {
				temp2Wrapper.style.display = 'flex';
			} else {
				temp2Wrapper.style.display = 'none';
			}
		}
	}

	document.addEventListener('DOMContentLoaded', function () {
		const noResepInput = document.getElementById('no_resep');
		if (noResepInput) {
			toggleTemp2();
			noResepInput.addEventListener('input', toggleTemp2);
		}
	});
</script> -->
<script>
	function toggleTemp2() {
		const noResepInput = document.getElementById('no_resep');
		const temp2Wrapper = document.getElementById('temp2-wrapper');
		const tempCode = document.getElementById('temp_code');
		const tempCode2 = document.getElementById('temp_code2');

		if (!noResepInput) return;

		const value = noResepInput.value.trim().toUpperCase();
		let dystf = '';
		const prefix = value.slice(0, 2);

		if (prefix === 'DR') {
			dystf = 'DR';
			temp2Wrapper.style.display = 'flex';
		} else if (prefix === 'CD' || prefix === 'OB') {
			dystf = prefix;
			temp2Wrapper.style.display = 'none';
		} else {
			const char = prefix.charAt(0);
			if (char === 'D' || char === 'A') {
				dystf = 'D';
			} else if (char === 'R') {
				dystf = 'R';
			}
			temp2Wrapper.style.display = 'none';
		}

		let existingTemp = { temp_code: '', temp_code2: '' };

		// Ambil data existing dari tbl_matching
		fetch('pages/ajax/get_existing_temp.php?no_resep=' + encodeURIComponent(value))
			.then(response => response.json())
			.then(data => {
				existingTemp = data;

				// Load suhu options sesuai Dystf
				if (dystf !== '') {
					fetch('pages/ajax/get_suhu_options.php?Dystf=' + encodeURIComponent(dystf))
						.then(response => response.text())
						.then(optionsHTML => {
							if (tempCode) {
								tempCode.innerHTML = '<option value="">Pilih...</option>' + optionsHTML;
								if (existingTemp.temp_code) {
									tempCode.value = existingTemp.temp_code;
								}
							}
							if (tempCode2) {
								if (dystf === 'DR') {
									tempCode2.innerHTML = '<option value="">Pilih...</option>' + optionsHTML;
									if (existingTemp.temp_code2) {
										tempCode2.value = existingTemp.temp_code2;
									}
								} else {
									tempCode2.innerHTML = '<option value="">Pilih...</option>';
								}
							}
						});
				} else {
					if (tempCode) tempCode.innerHTML = '<option value="">Pilih...</option>';
					if (tempCode2) tempCode2.innerHTML = '<option value="">Pilih...</option>';
				}
			});
	}

	document.addEventListener('DOMContentLoaded', function () {
		const noResepInput = document.getElementById('no_resep');
		if (noResepInput) {
			toggleTemp2();
			noResepInput.addEventListener('input', toggleTemp2);
		}
	});
</script>

</html>