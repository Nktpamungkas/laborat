<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$idm = $_GET['idm'];
$sql = mysqli_query($con,"SELECT a.id as id_status, a.idm, a.flag, a.grp, a.matcher, a.cek_warna, a.cek_dye, a.status, a.kt_status, a.koreksi_resep, a.percobaan_ke, a.benang_aktual, a.lebar_aktual, a.gramasi_aktual, a.soaping_sh, a.soaping_tm, a.rc_sh, a.rc_tm, a.lr, a.cie_wi, a.cie_tint, a.done_matching, a.ph,
a.spektro_r, a.ket, a.created_at as tgl_buat_status, a.created_by as status_created_by, a.edited_at, a.edited_by, a.target_selesai, a.cside_c,
a.cside_min, a.tside_c, a.tside_min, a.mulai_by, a.mulai_at, a.selesai_by, a.selesai_at, a.approve_by, a.approve_at, a.approve,
b.id, b.no_resep, b.no_order, b.no_po, b.langganan, b.no_item, b.jenis_kain, b.benang, b.cocok_warna, b.warna, a.kadar_air,
b.no_warna, b.lebar, b.gramasi, b.qty_order, b.tgl_in, b.tgl_out,
b.proses, b.buyer, a.final_matcher, a.colorist1, a.colorist2, 
b.tgl_delivery, b.note, b.jenis_matching, b.tgl_buat, b.tgl_update, b.created_by, b.color_code, b.recipe_code
FROM tbl_status_matching a
INNER JOIN tbl_matching b ON a.idm = b.no_resep
where a.id = '$idm'
ORDER BY a.id desc limit 1");
$data = mysqli_fetch_array($sql);
?>
<style>
    .lookupST {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        font-size: 9pt !important;
    }

    .lookupST td,
    .lookupST th {
        border: 1px solid black;
        padding: 2px;
    }

    .lookupST td {
        background-color: white;
    }

    .lookupST tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .lookupST tr:hover {
        background-color: rgb(151, 170, 212);
    }

    .lookupST th {
        padding-top: 5px;
        padding-bottom: 5px;
        text-align: center;
        background-color: #4CAF50;
        color: white;
    }

    .input-xs {
        height: 22px !important;
        padding: 2px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }

    .input-group-xs>.form-control,
    .input-group-xs>.input-group-addon,
    .input-group-xs>.input-group-btn>.btn {
        height: 22px;
        padding: 1px 5px;
        font-size: 12px;
        line-height: 1.5;
    }
</style>
<div class="modal-content">
    <div class="modal-body">
        <div class="container-fluid">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#Merge-order"><b>Additional Order</b></a></li>
                <li><a data-toggle="tab" href="#input-status"><b>Basic Info</b></a></li>
                <li id="tab_resep"><a data-toggle="tab" href="#step1"><b>RESEP</b></a></li>
                <li id="tab_hasil_celup"><a data-toggle="tab" href="#hasil_celup"><b>Hasil Celup</b></a></li>
                <li class="pull-right disabled bg-primary" disabled><a href="#" style="color: white;"><b>Parent > <?php echo $data['idm'] ?></b></a></li>
            </ul>
        </div>
        <div class="form-horizontal" id="form-status">
            <div class="tab-content">
        <!-- Col Additional Order -->
                <div id="Merge-order" class="tab-pane fade in active">
                    <div class="row" style="margin-top: 20px">
                        <form action="#" id="form-merge-order" method="post">
                            <input type="hidden" id="id_matching_order" value="<?php echo $data['id'] ?>">
                            <input type="hidden" id="id_status_order" value="<?php echo $data['id_status'] ?>">
                            <input type="hidden" id="r_code_order" value="<?php echo $data['idm'] ?>">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <p class="text-center" style="text-shadow: black; font-weight: bold; margin-bottom: 20px;">Input Additional Order</p>
                                    <label for="Jenis_Matching" class="col-sm-2 control-label">No. Order :</label>
                                    <div class="col-sm-10">
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="no_order_merger" id="no_order_merger" placeholder="No order to merge...">
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="LOT" id="LOT" placeholder="LOT...">
                                        </div>
                                        <div class="col-sm-1 input-group">
                                            <button type="button" class="btn btn-danger" id="button-merge-order">Submit <i class="fa fa-fw fa-link" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <label for="Jenis_Matching" class="col-sm-2 control-label" style="margin-top:10px;">Jenis Benang :</label>
                                    <div class="col-sm-9" style="margin-top:10px;">
                                        <textarea name="addt_benang" id="addt_benang" class="form-control" style="margin-left: 15px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-center" style="text-shadow: black; font-weight: bold;">Additional Order <?php echo $data['idm'] ?></p>
                                    <table class="table table-bordered table-sm" id="additional_order_table" width="100%">
                                        <thead class="bg-primary">
                                            <th>id</th>
                                            <th>flag</th>
                                            <th>No .Order</th>
                                            <th>Lot</th>
                                            <th>Benang</th>
                                            <th>insert at</th>
                                        </thead>
                                        <tbody>
                                            <!-- i do some magic here dude -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                        <form action="#" id="form-note-status" method="post">
                            <input type="hidden" id="id_matching_order" value="<?php echo $data['id'] ?>">
                            <input type="hidden" id="id_status_order" value="<?php echo $data['id_status'] ?>">
                            <input type="hidden" id="r_code_order" value="<?php echo $data['idm'] ?>">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="Jenis_Matching" class="col-sm-2 control-label" style="margin-top:10px;">Note Status :</label>
                                    <div class="col-sm-8" style="margin-top:10px;">
                                        <?php 
                                            $q_notestatus = mysqli_query($con, "SELECT * FROM tbl_notestatus WHERE id_matching='$data[id]' AND id_status='$data[id_status]' AND r_code='$data[idm]'");
                                            $d_notestatus = mysqli_fetch_assoc($q_notestatus);
                                        ?>
                                        <textarea name="note_status" id="note_status" class="form-control" style="margin-left: 15px;"><?= $d_notestatus['note']; ?></textarea>
                                        <button type="button" class="btn btn-sm btn-danger" id="button-note-delete">Delete Note <i class="fa fa-fw fa-trash" aria-hidden="true"></i></button>
                                    </div>
                                    <div class="col-sm-1" style="margin-top:10px;">
                                        <button type="button" class="btn btn-success" id="button-note-status">Submit Note <i class="fa fa-fw fa-link" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        <!-- End -->

        <!-- Col Basic Info -->
                <div id="input-status" class="tab-pane fade in">
                    <div class="row" style="margin-top: 20px">
                        <input type="hidden" name="id_matching" id="id_matching" value="<?php echo $data['id'] ?>" readonly="true">
                        <input type="hidden" name="id_status" id="id_status" value="<?php echo $data['id_status'] ?>" readonly="true">
                        <input type="hidden" name="idm" id="idm" value="<?php echo $data['idm'] ?>" readonly="true">
                        <input type="hidden" name="tgl_buat_status" id="tgl_buat_status" value="<?php echo $data['tgl_buat_status'] ?>" readonly="true">
                        <!-- KIRI -->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="Jenis_Matching" class="col-sm-3 control-label">Jenis Matching</label>
                                <div class="col-sm-9">
                                    <input type="text" value="<?php echo $data['jenis_matching'] ?>" readonly class="form-control input-sm" name="Jenis Matching" id="Jenis_Matching" placeholder="Jenis Matching">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="item" class="col-sm-3 control-label">Item</label>
                                <div class="col-sm-9">
                                    <input type="text" value="<?php echo $data['no_item'] ?>" readonly class="form-control input-sm" name="item" id="item" placeholder="item">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="no_warna" class="col-sm-3 control-label">No.warna</label>
                                <div class="col-sm-9">
                                    <input type="text" value="<?php echo $data['no_warna'] ?>" readonly class="form-control input-sm" name="no_warna" id="no_warna" placeholder="no_warna">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="warna" class="col-sm-3 control-label">Warna</label>
                                <div class="col-sm-9">
                                    <input type="text" value="<?php echo $data['warna'] ?>" readonly class="form-control input-sm" name="warna" id="warna" placeholder="warna">
                                </div>
                            </div>
							<div class="form-group">
                                <label for="color_code" class="col-sm-3 control-label">Color Code</label>
                                <div class="col-sm-9">
                                    <input type="text" value="<?php echo $data['color_code'] ?>" readonly class="form-control input-sm" name="color_code" id="color_code" placeholder="Color Code">
                                </div>
                            </div>
							<div class="form-group">
                                <label for="recipe_code" class="col-sm-3 control-label">Recipe Code</label>
                                <div class="col-sm-9">
                                    <!--<input type="text" value="<?php echo $data['recipe_code'] ?>" readonly class="form-control input-sm" name="recipe_code" id="recipe_code" placeholder="Recipe Code">-->
									<textarea readonly class="form-control input-sm" name="recipe_code" id="recipe_code" placeholder="Recipe Code"><?php echo $data['recipe_code'] ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Kain" class="col-sm-3 control-label">Kain</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control input-sm" name="Kain" id="Kain" readonly rows="2"><?php echo $data['jenis_kain'] ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Benang" class="col-sm-3 control-label">Benang</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control input-sm" name="Benang" id="Benang" rows="3" readonly><?php echo $data['benang'] ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Gramasi" class="col-sm-3 control-label">Gramasi</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-sm" name="Lebar" id="Lebar" placeholder="Inch" value="<?php echo $data['lebar'] ?> Inch" readonly>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-dark"> <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-sm" name="Gramasi" id="Gramasi" placeholder="Gr/M2" value="<?php echo $data['gramasi'] ?>  gr/m²" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Delivery" class="col-sm-3 control-label">Tgl Delivery</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" name="Tgl_delivery" id="Tgl_delivery" placeholder="Tgl delivery" value="<?php echo $data['tgl_delivery'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Order" class="col-sm-3 control-label"><?php if ($data['jenis_matching'] != 'L/D') {
                                                                                        echo 'No. Order';
                                                                                    } else {
                                                                                        echo 'Request No';
                                                                                    }   ?></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" name="no_order" id="no_order" placeholder="no_order" value="<?php echo $data['no_order'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Order" class="col-sm-3 control-label">PO.Greige</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" name="Order" id="Order" placeholder="Order" value="<?php echo $data['no_po'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="QtyOrder" class="col-sm-3 control-label">Qty Order</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" name="QtyOrder" id="QtyOrder" placeholder="Qty Order" value="<?php echo $data['qty_order'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="CocokWarna" class="col-sm-3 control-label">Cocok Warna</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" name="CocokWarna" id="CocokWarna" placeholder="Cocok Warna" value="<?php echo $data['cocok_warna'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Matcher" class="col-sm-3 control-label">Matcher</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" name="Matcher" id="Matcher" placeholder="Matcher" value="<?php echo $data['matcher'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Group" class="col-sm-3 control-label">Group</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm" name="Group" id="Group" placeholder="Group" value="<?php echo $data['grp'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status_created_by" class="col-sm-3 control-label">Dibuat oleh :</label>
                                <div class="col-sm-3">
                                    <input type="text" width="100%" class="form-control" required name="status_created_by" id="status_created_by" value="<?php echo $data['status_created_by'] ?>" placeholder="C°...">
                                </div>
                                <label for="tgl_buat_status" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i>

                                </label>
                                <div class="col-sm-4">
                                    <input type="text" required class="form-control" name="tgl_buat_status" id="tgl_buat_status" value="<?php echo $data['tgl_buat_status'] ?>" placeholder="Minute ...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="approve_by" class="col-sm-3 control-label">Approve oleh :</label>
                                <div class="col-sm-3">
                                    <input type="text" width="100%" class="form-control" required name="approve_by" id="approve_by" value="<?php echo $data['approve_by'] ?>" placeholder="C°...">
                                </div>
                                <label for="approve_at" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i>

                                </label>
                                <div class="col-sm-4">
                                    <input type="text" required class="form-control" name="approve_at" id="approve_at" value="<?php echo $data['approve_at'] ?>" placeholder="Minute ...">
                                </div>
                            </div>
                        </div>
                        <!-- KANAN -->
                        <div class="col-md-7">
                            <div class="form-group">
                                <!-- tambahan -->
                                <div class="form-group">
                                    <label for="Proses" class="col-sm-2 control-label">Proses</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control input-sm" name="Proses" id="Proses" placeholder="Proses" value="<?php echo $data['proses'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Buyer" class="col-sm-2 control-label">Buyer</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control input-sm" name="Buyer" id="Buyer" placeholder="Buyer" value="<?php echo $data['buyer'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lamp" class="col-sm-2 control-label">Lampu :</label>
                                    <?php $sqlLamp = mysqli_query($con,"SELECT * FROM vpot_lampbuy where buyer = '$data[buyer]'"); ?>
                                    <?php while ($lamp = mysqli_fetch_array($sqlLamp)) { ?>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control input-sm" value="<?php echo $lamp['lampu'] ?>" readonly>
                                        </div>
                                    <?php } ?>
                                </div>
                                <!-- tambahan -->
                                <div class="form-group">
                                    <label for="Matching-ke" class="col-sm-2 control-label">Percobaan-ke</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" required id="Matching-ke" name="Matching-ke" maxlength="2" placeholder="Matching Ke" value="<?php echo $data['percobaan_ke'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="BENANG-A" class="col-sm-2 control-label">BENANG-A</label>
                                    <div class="col-sm-9">
                                        <textarea name="BENANG-A" id="BENANG-A" rows="2" class="form-control" placeholder="Benang Aktual.." required><?php echo $data['benang_aktual'] ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="LEBAR-A" class="col-sm-2 control-label" style="margin-right: 15px;">LEBAR-A</label>
                                    <div class="input-group col-sm-5">
                                        <input type="text" class="form-control" required id="LEBAR-A" name="LEBAR-A" placeholder="Lebar Aktual.." value="<?php echo floatval($data['lebar_aktual']) ?>">
                                        <div class="input-group-addon"><small>Inches</small></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="GRAMASI-A" class="col-sm-2 control-label" style="margin-right: 15px;">GRAMASI-A</label>
                                    <div class="input-group col-sm-5">
                                        <input type="text" class="form-control" required id="GRAMASI-A" name="GRAMASI-A" placeholder="Gramasi Aktual..." value="<?php echo floatval($data['gramasi_aktual']) ?>">
                                        <div class="input-group-addon"><small>Gr/M²</small></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="kadar_air_true" class="col-sm-2 control-label" style="margin-right: 15px;">Kadar Air</label>
                                    <div class="input-group col-sm-5">
                                        <input type="text" class="form-control" id="kadar_air_true" name="kadar_air_true" placeholder="Kadar Air..." value="<?php echo floatval($data['kadar_air']) ?>">
                                        <div class="input-group-addon">%</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="CIE_WI" class="col-sm-2 control-label">CIE WI</label>
                                    <div class="col-sm-9">
                                        <input type="text" required class="form-control" name="CIE_WI" id="CIE_WI" placeholder="CIE WI" value="<?php echo floatval($data['cie_wi']) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="CIE_TINT" class="col-sm-2 control-label">CIE TINT</label>
                                    <div class="col-sm-9">
                                        <input type="text" required class="form-control" name="CIE_TINT" id="CIE_TINT" placeholder="CIE TINT" value="<?php echo floatval($data['cie_tint']) ?>">
                                    </div>
                                </div>
                                <!-- <div class="form-group"> -->
                                <!-- <label for="Spektro R" required class="col-sm-2 control-label">Spektro R</label> -->
                                <!-- <div class="col-sm-9"> -->
                                <input type="hidden" required class="form-control" name="Spektro_R" id="Spektro_R" placeholder="Spektro Reading" value="<?php echo $data['spektro_r'] ?>">
                                <!-- </div> -->
                                <!-- </div> -->
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Tgl Done Matching</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control date-picker" required name="Done_Matching" id="Done_Matching" placeholder="Tgl Selesai Matching" value="<?php echo $data['done_matching'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Final Matcher</label>
                                    <div class="col-sm-6">
                                        <select class="form-control select_Fmatcher" required name="f_matcher" id="f_matcher">
                                            <option value="<?php echo $data['final_matcher'] ?>" selected><?php echo $data['final_matcher'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep</label>
                                    <div class="col-sm-6">
                                        <select class="form-control select_Koreksi" required name="koreksi" id="koreksi">
                                            <option value="<?php echo $data['koreksi_resep'] ?>" selected><?php echo $data['koreksi_resep'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist1</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="colorist_1" id="colorist_1">
                                            <option value="<?php echo $data['colorist1'] ?>" selected><?php echo $data['colorist1'] ?></option>
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-1 control-label">Colorist2</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="colorist_2" id="colorist_2">
                                            <option value="<?php echo $data['colorist2'] ?>" selected><?php echo $data['colorist2'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-9">
                                        <textarea required class="form-control" name="keterangan" id="keterangan" rows="3"><?php echo $data['ket'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <!-- End -->

        <!-- Col Resep -->
                <!-- step1 -->
                <div id="step1" class="tab-pane fade">
                    <br />
                    <div class="row">
                        <!-- <div class="col-lg-12">
                        <div class="align-right text-right" style="margin-bottom: 4px;">
                            <button type="button" id="plus_c1" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Conc</button>
                            <button type="button" id="minus_c1" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i> Conc</button>||
                            <button type="button" id="plus1" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Baris</button>
                            <button type="button" id="minus1" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i> Baris</button>
                        </div>
                    </div> -->
                        <div class="col-lg-12 overflow-auto table-responsive well" style="overflow-x: auto;">
                            <table id="lookupmodal1" class="lookupST display nowrap" width="110%" style="padding-right: 16px;">
                                <thead id="th-lookup1">
                                    <tr>
                                        <th width="5px">#</th>
                                        <th width="100px" class="th_code">Code</th>
                                        <th width="150px" class="th_name">Name</th>
                                        <th width="60px" class="th_conc" flag_th="1">Lab</th>
                                        <th width="60px" class="th_conc" flag_th="2">Adjust-1</th>
                                        <th width="60px" class="th_conc" flag_th="3">Adjust-2</th>
                                        <th width="60px" class="th_conc" flag_th="4">Adjust-3</th>
                                        <th width="60px" class="th_conc" flag_th="5">Adjust-4</th>
                                        <th width="60px" class="th_conc" flag_th="6">Adjust-5</th>
                                        <th width="60px" class="th_conc" flag_th="7">Adjust-6</th>
                                        <th width="60px" class="th_conc" flag_th="8">Adjust-7</th>
                                        <th width="60px" class="th_conc" flag_th="9">Adjust-8</th>
                                        <th width="60px" class="th_conc" flag_th="10">Adjust-9</th>
                                        <th width="150px" class="th_remark">Remark</th>
                                    </tr>
                                </thead>
                                <?php
                                $hold_resep = mysqli_query($con,"SELECT * from tbl_matching_detail where `id_matching` = '$data[id]' and `id_status` = '$data[id_status]' order by flag");
                                ?>
                                <tbody id="tb-lookup1">
                                    <?php while ($hold = mysqli_fetch_array($hold_resep)) : ?>
                                        <tr>
                                            <td align="center" class="nomor"><?php echo $hold['flag'] ?></td>
                                            <td>
                                                <select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here ..">
                                                    <option value="<?php echo $hold['kode'] ?>" selected><?php echo $hold['kode'] ?></option>
                                                </select>
                                            </td>
                                            <td><input style="width: 100%" readonly type="text" class="form-control input-xs name" value="<?php echo $hold['nama'] ?>"></td>
                                            <td flag_td="1"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc1']) ?>"></td>
                                            <td flag_td="2"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc2']) ?>"></td>
                                            <td flag_td="3"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc3']) ?>"></td>
                                            <td flag_td="4"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc4']) ?>"></td>
                                            <td flag_td="5"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc5']) ?>"></td>
                                            <td flag_td="6"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc6']) ?>"></td>
                                            <td flag_td="7"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc7']) ?>"></td>
                                            <td flag_td="8"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc8']) ?>"></td>
                                            <td flag_td="9"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc9']) ?>"></td>
                                            <td flag_td="10"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc10']) ?>"></td>
                                            <td><input style="width: 100%" type="text" class="form-control input-xs remark" value="<?php echo $hold['remark'] ?>"></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot id="tfoot">
                                    <tr>
                                        <th colspan="3">TOTAL</th>
                                        <th id="lab"></th>
                                        <th id="Adj_1"></th>
                                        <th id="Adj_2"></th>
                                        <th id="Adj_3"></th>
                                        <th id="Adj_4"></th>
                                        <th id="Adj_5"></th>
                                        <th id="Adj_6"></th>
                                        <th id="Adj_7"></th>
                                        <th id="Adj_8"></th>
                                        <th id="Adj_9"></th>
                                        <th id="keterangan"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-lg-11 well" style="margin-top: 10px;">
                            <div class="form-group">
                                <label for="L_R" class="col-sm-1 control-label">L:R :</label>
                                <div class="col-sm-2">
                                    <select type="text" width="100%" class="form-control" required name="L_R" id="L_R" placeholder="L_R">
                                        <option selected disabled>Pilih...</option>
                                        <option <?php if ($data['lr'] == "1:6") echo "selected" ?> value="1:6">1:6</option>
                                        <option <?php if ($data['lr'] == "1:9") echo "selected" ?> value="1:9">1:9</option>
                                        <option <?php if ($data['lr'] == "1:10") echo "selected" ?> value="1:10">1:10</option>
                                        <option <?php if ($data['lr'] == "1:12") echo "selected" ?> value="1:12">1:12</option>
                                    </select>
                                    <span></span>
                                </div>
                                <label for="L_R" class="col-sm-1 control-label">Ph :</label>
                                <div class="col-sm-3">
                                    <input type="text" required class="form-control" name="kadar_air" id="kadar_air" value="<?php echo $data['ph'] ?>">
                                </div>
                            </div>
                            <!-- conditionally column -->
                            <div class="col-md-12 well" style="margin-top: 20px;">
                                <?php if (substr($data['idm'], 0, 2) == 'D2' or substr($data['idm'], 0, 1) == 'C') { ?>
                                    <div class="form-group">
                                        <label for="tside_c" class="col-sm-2 control-label">T-SIDE :</label>
                                        <div class="col-sm-2">
                                            <input type="text" width="100%" class="form-control" required name="tside_c" id="tside_c" value="<?php echo floatval($data['tside_c']) ?>" placeholder="C°...">
                                        </div>
                                        <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                        </label>
                                        <div class="col-sm-2">
                                            <input type="text" required class="form-control" name="tside_min" id="tside_min" value="<?php echo floatval($data['tside_min']) ?>" placeholder="Minute ...">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                        <label for=" RC" class="col-sm-2 control-label" align="left">RC</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="RC_Suhu" required name="RC_Suhu" value="<?php echo floatval($data['rc_sh']) ?>" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="RCWaktu" required name="RCWaktu" value="<?php echo floatval($data['rc_tm']) ?>" placeholder="Waktu/Menit">
                                            <div class="input-group-addon">Menit</div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                        <label for=" RC" class="col-sm-2 control-label" align="left">Bleaching</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="bleaching_sh" value="<?php echo floatval($data['bleaching_sh']) ?>" required name="bleaching_sh" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="bleaching_tm" value="<?php echo floatval($data['bleaching_tm']) ?>" required name="bleaching_tm" placeholder="Waktu/Menit">
                                            <div class="input-group-addon">Menit</div>
                                        </div>
                                    </div>
                                <?php } else if (substr($data['idm'], 0, 1) == 'R' or substr($data['idm'], 0, 1) == 'A') { ?>
                                    <div class="form-group">
                                        <label for="cside_c" class="col-sm-2 control-label">C-SIDE :</label>
                                        <div class="col-sm-2">
                                            <input type="text" width="100%" class="form-control" required name="cside_c" id="cside_c" value="<?php echo floatval($data['cside_c']) ?>" placeholder="C°...">
                                        </div>
                                        <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                        </label>
                                        <div class="col-sm-2">
                                            <input type="text" required class="form-control" name="cside_min" id="cside_min" value="<?php echo floatval($data['cside_min']) ?>" placeholder="Minute ...">
                                        </div>
                                    </div>
                                    <!-- SOAPING -->
                                    <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                        <label for="SOAPING" class="col-sm-2 control-label" align="left">SOAPING</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" required id="soapingSuhu" name="soapingSuhu" value="<?php echo floatval($data['soaping_sh']) ?>" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="SOAPING" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" required id="soapingWaktu" name="soapingWaktu" value="<?php echo floatval($data['soaping_tm']) ?>" placeholder="Waktu/Menit">
                                            <div class="input-group-addon">Menit</div>
                                        </div>
                                    </div>
                                    <!-- //SOAPING -->
                                <?php } elseif (substr($data['idm'], 0, 2) == 'DR') { ?>
                                    <div class="form-group">
                                        <label for="tside_c" class="col-sm-2 control-label">T-SIDE :</label>
                                        <div class="col-sm-2">
                                            <input type="text" width="100%" class="form-control" required name="tside_c" id="tside_c" value="<?php echo floatval($data['tside_c']) ?>" placeholder="C°...">
                                        </div>
                                        <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                        </label>
                                        <div class="col-sm-2">
                                            <input type="text" required class="form-control" name="tside_min" id="tside_min" value="<?php echo floatval($data['tside_min']) ?>" placeholder="Minute ...">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cside_c" class="col-sm-2 control-label">C-SIDE :</label>
                                        <div class="col-sm-2">
                                            <input type="text" width="100%" class="form-control" required name="cside_c" id="cside_c" value="<?php echo floatval($data['cside_c']) ?>" placeholder="C°...">
                                        </div>
                                        <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                        </label>
                                        <div class="col-sm-2">
                                            <input type="text" required class="form-control" name="cside_min" id="cside_min" value="<?php echo floatval($data['cside_min']) ?>" placeholder="Minute ...">
                                        </div>
                                    </div>
                                    <!-- SOAPING -->
                                    <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                        <label for="SOAPING" class="col-sm-2 control-label" align="left">SOAPING</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" required id="soapingSuhu" name="soapingSuhu" value="<?php echo floatval($data['soaping_sh']) ?>" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="SOAPING" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" required id="soapingWaktu" name="soapingWaktu" value="<?php echo floatval($data['soaping_tm']) ?>" placeholder="Waktu/Menit">
                                            <div class="input-group-addon">Menit</div>
                                        </div>
                                    </div>
                                    <!-- //SOAPING -->
                                    <!-- RC -->
                                    <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                        <label for=" RC" class="col-sm-2 control-label" align="left">RC</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" required id="RC_Suhu" name="RC_Suhu" value="<?php echo floatval($data['rc_sh']) ?>" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" required id="RCWaktu" name="RCWaktu" value="<?php echo floatval($data['rc_tm']) ?>" placeholder="Waktu/Menit">
                                            <div class="input-group-addon">Menit</div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                        <label for=" RC" class="col-sm-2 control-label" align="left">Bleaching</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="bleaching_sh" value="<?php echo floatval($data['bleaching_sh']) ?>" required name="bleaching_sh" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="bleaching_tm" value="<?php echo floatval($data['bleaching_tm']) ?>" required name="bleaching_tm" placeholder="Waktu/Menit">
                                            <div class="input-group-addon">Menit</div>
                                        </div>
                                    </div>
                                    <!-- //RC -->
                                <?php } else if (substr($data['idm'], 0, 2) == 'OB') { ?>
                                    <!-- echoing nothing -->
                                    <br />
                                    <div class="form-group">
                                        <label for="tside_c" class="col-sm-2 control-label">T/C SIDE :</label>
                                        <div class="col-sm-2">
                                            <input type="text" width="100%" class="form-control" required name="tside_c" id="tside_c" value="<?php echo floatval($data['tside_c']) ?>" placeholder="C°...">
                                        </div>
                                        <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                        </label>
                                        <div class="col-sm-2">
                                            <input type="text" required class="form-control" name="tside_min" id="tside_min" value="<?php echo floatval($data['tside_min']) ?>" placeholder="Minute ...">
                                        </div>
                                    </div>
                                    <p style="font-style: italic; font-weight: bold;">Field Rc and Soaping not avaliable at O+B matching !</p>
                                <?php } else { ?>
                                    <div class="form-group">
                                        <label for="tside_c" class="col-sm-2 control-label">T-SIDE :</label>
                                        <div class="col-sm-2">
                                            <input type="text" width="100%" class="form-control" required name="tside_c" id="tside_c" value="<?php echo floatval($data['tside_c']) ?>" placeholder="C°...">
                                        </div>
                                        <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                        </label>
                                        <div class="col-sm-2">
                                            <input type="text" required class="form-control" name="tside_min" id="tside_min" value="<?php echo floatval($data['tside_min']) ?>" placeholder="Minute ...">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cside_c" class="col-sm-2 control-label">C-SIDE :</label>
                                        <div class="col-sm-2">
                                            <input type="text" width="100%" class="form-control" required name="cside_c" id="cside_c" value="<?php echo floatval($data['cside_c']) ?>" placeholder="C°...">
                                        </div>
                                        <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                        </label>
                                        <div class="col-sm-2">
                                            <input type="text" required class="form-control" name="cside_min" id="cside_min" value="<?php echo floatval($data['cside_min']) ?>" placeholder="Minute ...">
                                        </div>
                                    </div>
                                    <!-- SOAPING -->
                                    <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                        <label for="SOAPING" class="col-sm-2 control-label" align="left">SOAPING</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="soapingSuhu" name="soapingSuhu" value="<?php echo floatval($data['soaping_sh']) ?>" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="SOAPING" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="soapingWaktu" name="soapingWaktu" value="<?php echo floatval($data['soaping_tm']) ?>" placeholder="Waktu/Menit">
                                            <div class="input-group-addon">Menit</div>
                                        </div>
                                    </div>
                                    <!-- //SOAPING -->
                                    <!-- RC -->
                                    <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                        <label for=" RC" class="col-sm-2 control-label" align="left">RC</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="RC_Suhu" name="RC_Suhu" value="<?php echo floatval($data['rc_sh']) ?>" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="RCWaktu" name="RCWaktu" value="<?php echo floatval($data['rc_tm']) ?>" placeholder="Waktu/Menit">
                                            <div class="input-group-addon">Menit</div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                        <label for=" RC" class="col-sm-2 control-label" align="left">Bleaching</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="bleaching_sh" value="<?php echo floatval($data['bleaching_sh']) ?>" required name="bleaching_sh" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="bleaching_tm" value="<?php echo floatval($data['bleaching_tm']) ?>" required name="bleaching_tm" placeholder="Waktu/Menit">
                                            <div class="input-group-addon">Menit</div>
                                        </div>
                                    </div>
                                    <!-- //RC -->
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
        <!-- End -->

        <!-- Col Hasil Celup -->
                <div id="hasil_celup" class="tab-pane fade in active">
                    <div class="row" style="margin-top:20px; background-color: white;">
                        <div class="col-md-12" style="margin-top: 10px;">
                            <div class="header">
                                <div class="col-md-12">
                                    <h4>
                                        <p class="text-center" style="text-shadow: black; font-weight: bold;">List Hasil Celup Resep <Strong style="text-decoration: underline;"><?php echo $data['idm'] ?></Strong> : <strong style="font-style: italic;"> <?php echo $data['warna'] ?> </strong></p>
                                    </h4>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-toggle="dropdown">Note <i class="fa fa-comments-o" aria-hidden="true"></i>
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" id="seeTheNote">See the note</a></li>
                                            <li><a href="#" id="AddNote">Add Note</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-striped table-bordered" id="table_hasil_celup" width="100%">
                                <thead class="bg-primary">
                                    <th>id</th>
                                    <th>No.</th>
                                    <th>No .Order</th>
                                    <th>No .KK</th>
                                    <th>No .Demand</th>
                                    <th>Lot</th>
                                    <th>Qty</th>
                                    <th>Loading</th>
                                    <th>L:R</th>
                                    <th>MC</th>
                                    <th>Kesetabilan</th>
                                    <th>Proses</th>
                                    <th>Status Celup</th>
                                    <th>Benang</th>
                                    <th style="width: 30mm;">Keterangan</th>
                                    <th>Target</th>
                                    <th>Waktu</th>
                                    <th>Bon Resep</th>
                                    <th>Note</th>
                                </thead>
                                <tbody>
                                    <!-- i do some magic here dude -->
                                </tbody>
                            </table>
                        </div>
                        <div class="modal fade modal-3d-slit" id="ModalAddNote" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div id="body_Addnote" class="modal-dialog" style="width:60%">

                            </div>
                        </div>
                        <div class="modal fade modal-3d-slit" id="ModalSeeResep" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div id="body_SeeResep" class="modal-dialog" style="width:70%">

                            </div>
                        </div>
                        <div class="modal fade modal-3d-slit" id="addnoteclp" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div id="body_addnoteclp" class="modal-dialog" style="width:60%">

                            </div>
                        </div>
                        <div class="modal fade modal-3d-slit" id="PopUpSeeNote" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div id="body_PopUpSeeNote" class="modal-dialog" style="width:70%">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        <!-- End -->
        <!-- <button class="btn btn-success" id="test">test</button> -->
        <div class="modal-footer" style="border-top: 1px solid black; height: 45px;">
            <div class="pull-right">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- disabling all input -->
<script>
    $(document).ready(function() {
        $('#form-status').find('input').prop('disabled', true)
        $('#form-status').find('select').prop('disabled', true)
        $('#form-status').find('textarea').prop('disabled', true)
        $('#form-status').find('#no_order_merger').prop("disabled", false);
        $('#form-status').find('#LOT').prop("disabled", false);
        $('#form-status').find('#addt_benang').prop("disabled", false);
        $('#form-status').find('#note_status').prop("disabled", false);
    })
</script>
<!-- form validation & reload ajax table here  -->
<script>
    $(document).ready(function() {
        var dataTable = $('#additional_order_table').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 25,
            "ordering": false,
            "lengthChange": false,
            "searching": false,
            "order": [
                [1, "desc"]
            ],
            "ajax": {
                url: "pages/ajax/data_server_AddtionalOrderExisting.php",
                type: "post",
                data: {
                    id_matching: $('#id_matching_order').val(),
                    id_status: $('#id_status_order').val()
                },
                error: function() {
                    $(".dataku-error").html("");
                    $("#dataku").append('<tbody class="dataku-error"><tr><th colspan="3">Tidak ada data untuk ditampilkan</th></tr></tbody>');
                    $("#dataku-error-proses").css("display", "none");
                }
            },
            "columnDefs": [{
                    "className": "text-center",
                    "targets": [0, 1, 2, 3, 5]
                },
                {
                    "targets": [0],
                    "visible": false
                }
            ],
        });

        $(document).on('click', '._hapusOrder', function() {
            let id = $(this).attr('data-pk')
            if (confirm('apakah anda yakin ingin menghapus order ini ?')) {
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: "pages/ajax/DeleteOrderChild.php",
                    data: {
                        id: id,
                        id_status: $('#id_status_order').val()
                    },
                    success: function(response) {
                        if (response.session == "LIB_SUCCSS") {
                            toastr.success('Order Number Removed')
                            dataTable.ajax.reload()
                        } else {
                            toastr.error("System Error !")
                        }
                    },
                    error: function() {
                        alert("Error hubungi DIT");
                    }
                });
            } else {
                console.log('cancel button')
            }
        });

        var form1 = $('#form-merge-order');
        var error1 = $('.alert-danger', form1);
        form1.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error text-sm',
            // focusInvalid: false,
            ignore: "",
            rules: {
                no_order_merger: {
                    required: false,
                },
                LOT: {
                    required: true
                }
            },
            // messege error-------------------------------------------------------
            messages: {
                LOT: {
                    required: "This field is required !"
                },
            },

            invalidHandler: function(event, validator) { //display error alert on form submit
                // success1.hide();
                error1.show();
                // App.scrollTo(error1, -200);
            },

            errorPlacement: function(error, element) { // render error placement for each input type
                var cont = $(element).parent('.input-group');
                if (cont.length > 0) {
                    cont.after(error);
                } else {
                    element.after(error);
                }
            },

            highlight: function(element) { // hightlight error inputs

                $(element)
                    .closest('.form-group').addClass(
                        'has-error'); // set error class to the control group
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass(
                        'has-error'); // set error class to the control group
            },

            submitHandler: function(form) {
                // success1.show();
                error1.hide();
            }
        });
        $.validator.setDefaults({
            debug: true,
            success: 'valid'
        });
        
        var form2 = $('#form-note-status');
        var error2 = $('.alert-danger', form2);
        form2.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error text-sm',
            // focusInvalid: false,
            ignore: "",

            invalidHandler: function(event, validator) { //display error alert on form submit
                // success1.hide();
                error2.show();
                // App.scrollTo(error1, -200);
            },

            errorPlacement: function(error, element) { // render error placement for each input type
                var cont = $(element).parent('.input-group');
                if (cont.length > 0) {
                    cont.after(error);
                } else {
                    element.after(error);
                }
            },

            highlight: function(element) { // hightlight error inputs

                $(element)
                    .closest('.form-group').addClass(
                        'has-error'); // set error class to the control group
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass(
                        'has-error'); // set error class to the control group
            },

            submitHandler: function(form) {
                // success1.show();
                error2.hide();
            }
        });
        $.validator.setDefaults({
            debug: true,
            success: 'valid'
        });

        $('#button-merge-order').click(function(e) {
            e.preventDefault();
            if ($("#form-merge-order").valid()) {
                insertNomor_order($('#id_matching_order').val(), $('#id_status_order').val(), $('#r_code_order').val(),
                    // $('#no_order_merger').find(':selected').val(), $('#LOT').val(), $('#addt_benang').val())
                    $('#no_order_merger').val(), $('#LOT').val(), $('#addt_benang').val())
            } else {
                toastr.error('Data yang anda input belum lengkap !');
            }
        });

        $('#button-note-delete').click(function(e) {
            e.preventDefault();
            if ($("#button-note-delete").valid()) {
                deleteNote_status($('#id_matching_order').val(), $('#id_status_order').val(), $('#r_code_order').val(),$('#note_status').val())
            } else {
                toastr.error('Data yang anda input belum lengkap !');
            }
        });

        $('#button-note-status').click(function(e) {
            e.preventDefault();
            if ($("#button-note-status").valid()) {
                insertNote_status($('#id_matching_order').val(), $('#id_status_order').val(), $('#r_code_order').val(),$('#note_status').val())
            } else {
                toastr.error('Data yang anda input belum lengkap !');
            }
        });        

        function insertNote_status(id_matching, id_status, Rcode, note_status) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/insertNote_status.php",
                data: {
                    id_matching: id_matching,
                    id_status: id_status,
                    Rcode: Rcode,
                    note_status: note_status
                },
                success: function(response) {
                    if (response.session == "LIB_SUCCSS") {
                        toastr.success(Rcode + 'Berhasil menambahkan Note Status !')
                        $('#note_status').val("");
                        dataTable.ajax.reload()
                    } else {
                        toastr.error("Nomor.order sudah terdaftar !")
                    }
                },
                error: function() {
                    alert("Error hubungi DIT. Data tidak boleh kosong");
                }
            });
        }

        function deleteNote_status(id_matching, id_status, Rcode, note_status) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/deleteNote_status.php",
                data: {
                    id_matching: id_matching,
                    id_status: id_status,
                    Rcode: Rcode,
                    note_status: note_status
                },
                success: function(response) {
                    if (response.session == "LIB_SUCCSS") {
                        toastr.success(Rcode + 'Berhasil delete Note Status !')
                        $('#note_status').val("");
                        dataTable.ajax.reload()
                    } else {
                        toastr.error("Note sudah terhapus !")
                    }
                },
                error: function() {
                    alert("Error deleting note, hubungi DIT. ");
                }
            });
        }

        function insertNomor_order(id_matching, id_status, Rcode, no_order, lot, addt_benang) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/insertNomor_order.php",
                data: {
                    id_matching: id_matching,
                    id_status: id_status,
                    Rcode: Rcode,
                    no_order: no_order,
                    lot: lot,
                    addt_benang: addt_benang
                },
                success: function(response) {
                    if (response.session == "LIB_SUCCSS") {
                        toastr.success(Rcode + 'Added to order !')
                        $('#LOT').val("");
                        $('#addt_benang').val("");
                        $('#no_order_merger').val(null).trigger('change');
                        dataTable.ajax.reload()
                    } else {
                        toastr.error("Nomor.order sudah terdaftar !")
                    }
                },
                error: function() {
                    alert("Error hubungi DIT");
                }
            });
        }
    });
</script>
<!-- Ajax Select2  -->
<script>
    $(document).ready(function() {
        $('.form-control.select2').select2({
            minimumInputLength: 6,
            allowClear: true,
            placeholder: 'Insert No.Order ....',
            ajax: {
                dataType: 'json',
                url: 'pages/ajax/Get_no_order_to_merge.php',
                delay: 500,
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
</script>
<!-- PREPARATION FOR TABLE editable hold-->
<script>
    $(document).ready(function() {
        $("#lab").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(3) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_1").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(4) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_2").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(5) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_3").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(6) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_4").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(7) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_5").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(8) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_6").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(9) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_7").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(10) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_8").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(11) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
        $("#Adj_9").html(function() {
            let a = 0;
            $("#lookupmodal1 tbody tr").each(function() {
                a += parseFloat($(this).find('td:eq(12) input').val());
            })
            $(this).html(parseFloat(a).toFixed(2))
        });
    });
</script>
<!-- EKSEKUSI SETELAH ADA PARAM DARI PREPARATION -->
<script>
    $(document).ready(function() {
        if (parseFloat($('#Adj_2').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='3']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='3']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_2').html()))
        }
        if (parseFloat($('#Adj_3').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='4']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='4']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_3').html()))
        }
        if (parseFloat($('#Adj_4').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='5']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='5']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_4').html()))
        }
        if (parseFloat($('#Adj_5').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='6']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='6']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_5').html()))
        }
        if (parseFloat($('#Adj_6').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='7']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='7']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_6').html()))
        }
        if (parseFloat($('#Adj_7').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='8']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='8']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_7').html()))
        }
        if (parseFloat($('#Adj_8').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='9']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='9']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_8').html()))
        }
        if (parseFloat($('#Adj_9').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='10']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='10']").remove()
            })
        } else {
            console.log(parseFloat($('#Adj_9').html()))
        }


        $('#tfoot').hide()

    });
</script>
<script>
    $(document).ready(function() {
        var dataTable = $('#table_hasil_celup').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 50,
            "ordering": false,
            "lengthChange": false,
            "searching": false,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                url: "pages/ajax/data_server_GetHasilCelup_fromDyeing.php",
                type: "post",
                data: {
                    r_code: $('#idm').val(),
                    p: "Adjust_Resep_Lab"
                },
                error: function() {
                    $(".dataku-error").html("");
                    $("#dataku").append('<tbody class="dataku-error"><tr><th colspan="3">Tidak ada data untuk ditampilkan</th></tr></tbody>');
                    $("#dataku-error-proses").css("display", "none");
                }
            },
            "columnDefs": [{
                    "className": "text-center",
                    "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]
                },
                {
                    "targets": [0],
                    "visible": false
                }
            ],
            createdRow: function(row, data, rowIndex) {
                $.each($('td', row), function(colIndex) {
                    if (colIndex == 9) {
                        $(this).attr('data-name', 'edit_kesetabilan');
                        $(this).attr('class', 'edit_kesetabilan text-center text-primary');
                        $(this).attr('data-type', 'select');
                        $(this).attr('data-pk', data[0]);
                    }
                });
            }
        });

        $('#table_hasil_celup').editable({
            container: 'body',
            selector: 'td.edit_kesetabilan',
            url: 'pages/ajax/edit_ksetabilan.php',
            title: 'Kesetabilan Resep',
            type: 'POST',
            datatype: 'json',
            source: [{
                value: "0X",
                text: "0X"
            }, {
                value: "1X",
                text: "1X"
            }, {
                value: "2X",
                text: "2X"
            }, {
                value: "3X",
                text: "3X"
            }, {
                value: "4X",
                text: "4X"
            }, {
                value: "5X",
                text: "5X"
            }, {
                value: "6X",
                text: "6X"
            }, {
                value: "7X",
                text: "7X"
            }, {
                value: "> 5X",
                text: "> 5X"
            }, ],
            validate: function(value) {
                if ($.trim(value) == '') {
                    return 'This field is required';
                }
            }
        });

        $(document).on('click', '.delete_celup', function() {
            let id = $(this).attr('data-pk');
            const conf = confirm('Apakah anda yakin ingin menghapus Hasil Celup ini ?')
            if (conf) {
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: "pages/ajax/Cut_relationWDye.php",
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert("Hubungi Departement DIT !");
                    }
                });
            }
        })

        $(document).on('click', '.bon_resep', function() {
            var url_bon = $(this).attr('data');
            centeredPopup(url_bon, 'myWindow', '800', '400', 'yes');
        })

        $(document).on('click', '#AddNote', function(e) {
            let m = '<?php echo $data['id_status'] ?>'
            $.ajax({
                url: "pages/ajax/modal_AddNote.php",
                type: "GET",
                data: {
                    idm: m,
                },
                success: function(ajaxData) {
                    $("#body_Addnote").html(ajaxData);
                    $("#ModalAddNote").modal('show', {
                        backdrop: 'false'
                    });
                }
            });
        });
        $(document).on('click', '#seeTheNote', function(e) {
            let m = '<?php echo $data['id_status'] ?>'
            $.ajax({
                url: "pages/ajax/showTimelineNote.php",
                type: "GET",
                data: {
                    id_status: m,
                },
                success: function(ajaxData) {
                    $("#body_SeeResep").html(ajaxData);
                    $("#ModalSeeResep").modal('show', {
                        backdrop: 'false'
                    });
                }
            });
        });
    })

    var popupWindow = null;

    function centeredPopup(url, winName, w, h, scroll) {
        LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
        TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
        settings =
            'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
        popupWindow = window.open(url, winName, settings)
    }
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '._addnoteclp', function(e) {
            let kk = $(this).attr('data-kk')
            let id_status = $('#id_status').val();
            // console.log(id)
            $.ajax({
                url: "pages/ajax/pop_up_addnoteclp.php",
                type: "GET",
                data: {
                    kk: kk,
                    id_status: id_status,
                },
                success: function(ajaxData) {
                    $("#body_addnoteclp").html(ajaxData);
                    $("#addnoteclp").modal('show', {
                        backdrop: 'false'
                    });
                }
            });
        });

        $(document).on('click', '._seenote', function(e) {
            let id_status = $('#id_status').val();
            let kk = $(this).attr('data-kk')
            $.ajax({
                url: "pages/ajax/Pop_Up_SeeNote.php",
                type: "GET",
                data: {
                    kk: kk,
                    id_status: id_status
                },
                success: function(ajaxData) {
                    $("#body_PopUpSeeNote").html(ajaxData);
                    $("#PopUpSeeNote").modal('show', {
                        backdrop: 'false'
                    });
                }
            });
        });
    })
</script>