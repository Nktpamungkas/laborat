<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
$sql = mysqli_query($con, "SELECT a.id as id_status, a.idm, b.id AS id_tblmatching, a.flag, a.grp, a.matcher, a.cek_warna, a.cek_dye, a.status, a.kt_status, 
a.koreksi_resep, a.koreksi_resep2,a.koreksi_resep3, a.koreksi_resep4, a.koreksi_resep5, a.koreksi_resep6,a.koreksi_resep7, a.koreksi_resep8,  a.percobaan_ke, a.benang_aktual, a.lebar_aktual, a.gramasi_aktual, a.soaping_sh, a.soaping_tm, a.rc_sh, a.rc_tm, a.lr, a.cie_wi, a.cie_tint, a.yellowness, a.done_matching, a.ph,
a.spektro_r, a.ket, a.created_at as tgl_buat_status, a.created_by as status_created_by, a.edited_at, a.edited_by, a.target_selesai, a.cside_c,
a.cside_min, a.tside_c, a.tside_min, a.mulai_by, a.mulai_at, a.selesai_by, a.selesai_at, a.approve_by, a.approve_at, a.approve,
b.id, b.no_resep, b.no_order, b.no_po, b.langganan, b.no_item, b.jenis_kain, b.benang, b.cocok_warna, b.warna, a.kadar_air,
b.no_warna, b.lebar, b.gramasi, b.qty_order, b.tgl_in, b.tgl_out,b.recipe_code,
b.proses, b.buyer, a.final_matcher, a.colorist1, a.colorist2,a.colorist3, a.colorist4,a.colorist5, a.colorist6,a.colorist7, a.colorist8, a.bleaching_tm, a.bleaching_sh, a.second_lr,
b.tgl_delivery, b.note, b.jenis_matching, b.tgl_buat, b.tgl_update, b.created_by, a.remark_dye,
b.suhu_chamber, b.warna_flourescent
FROM tbl_status_matching a
INNER JOIN tbl_matching b ON a.idm = b.no_resep
where a.id = '$_GET[idm]'
ORDER BY a.id desc limit 1");
$data = mysqli_fetch_array($sql); ?>
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

<body>
    <div class="container-fluid">
        <button type="button" style="background-color: grey; color: white; margin-bottom: 10px;" class="btn btn-sm"><?php echo $data['idm'] ?></button>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#input-status"><b>Basic Info</b></a></li>
            <li id="tab_resep"><a data-toggle="tab" href="#step1"><b>RESEP</b></a></li>
            <li id="tab_resep"><a data-toggle="tab" href="#addt_order"><b>Additional Order</b></a></li>
            <li id="tab_hasil_celup"><a data-toggle="tab" href="#hasil_celup"><b>Hasil Celup</b></a></li>
            <li class="pull-right">
                <button type="button" id="exsecute" class="btn btn-danger btn-sm">Save Changes <i class="fa fa-save"></i></button>
            </li>
        </ul>
    </div>
    <form action="#" class="form-horizontal" id="form-status">
        <div class="tab-content">
            <div id="input-status" class="tab-pane fade in active">
                <div class="row" style="margin-top: 20px">

                    <input type="hidden" name="id_matching" id="id_matching" value="<?php echo $data['id'] ?>" readonly="true">
                    <input type="hidden" name="id_status" id="id_status" value="<?php echo $data['id_status'] ?>" readonly="true">
                    <input type="hidden" name="idm" id="idm" value="<?php echo $data['idm'] ?>" readonly="true">
                    <input type="hidden" name="tgl_buat_status" id="tgl_buat_status" value="<?php echo $data['tgl_buat_status'] ?>" readonly="true">
                    <input type="hidden" name="id_tblmatching" id="id_tblmatching" value="<?php echo $data['id_tblmatching'] ?>" readonly="true">
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
                            <label for="recipe_code" class="col-sm-3 control-label">Recipe Code</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?php echo $data['recipe_code'] ?>" class="form-control input-sm" name="recipe_code" id="recipe_code" placeholder="Recipe Code">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="no_warna" class="col-sm-3 control-label">No.warna</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?php echo $data['no_warna'] ?>" readonly class="form-control input-sm" name="no_warna" id="no_warna" placeholder="no_warna">
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label for="color_code" class="col-sm-3 control-label">Color Code</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?php echo $data['color_code'] ?>" readonly class="form-control input-sm" name="color_code" id="color_code" placeholder="Color Code">
                            </div>
                        </div>
						<div class="form-group">
                            <label for="recipe_code" class="col-sm-3 control-label">Recipe Code</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?php echo $data['recipe_code'] ?>" readonly class="form-control input-sm" name="recipe_code" id="recipe_code" placeholder="Recipe Code">
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label for="warna" class="col-sm-3 control-label">Warna</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?php echo $data['warna'] ?>" readonly class="form-control input-sm" name="warna" id="warna" placeholder="warna">
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
                                <input type="text" class="form-control input-sm" name="Order" id="Order" placeholder="Order" value="<?php echo $data['no_order'] ?>" readonly>
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
                            <label for="Proses" class="col-sm-3 control-label">Proses</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" name="Proses" id="Proses" placeholder="Proses" value="<?php echo $data['proses'] ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Buyer" class="col-sm-3 control-label">Buyer</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" name="Buyer" id="Buyer" placeholder="Buyer" value="<?php echo $data['buyer'] ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lamp" class="col-sm-3 control-label">Lampu :</label>
                            <?php $sqlLamp = mysqli_query($con, "SELECT * FROM vpot_lampbuy where buyer = '$data[buyer]'"); ?>
                            <?php while ($lamp = mysqli_fetch_array($sqlLamp)) { ?>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control input-sm" value="<?php echo $lamp['lampu'] ?>" readonly>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Suhu Chamber 120</label>
                            <div class="col-sm-9">
                                <input type="checkbox" name="suhu_chamber" id="suhu_chamber" value="1" <?= ($data['suhu_chamber'] == '1') ? 'checked' : ''; ?>>
                                <label for="suhu_chamber">Stempel Aktif</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Warna Fluorescent</label>
                            <div class="col-sm-9">
                                <input type="checkbox" name="warna_fluorescent" id="warna_fluorescent" value="1" <?= ($data['warna_fluorescent'] == '1') ? 'checked' : ''; ?>>
                                <label for="warna_fluorescent">Stempel Aktif</label>
                            </div>
                        </div>
                    </div>
                    <!-- KANAN -->
                    <div class="col-md-7">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="status_created_by" class="col-sm-2 control-label">Dibuat oleh :</label>
                                <div class="col-sm-3">
                                    <input readonly type="text" width="100%" class="form-control" required name="status_created_by" id="status_created_by" value="<?php echo $data['status_created_by'] ?>" placeholder="C°...">
                                </div>
                                <label for="tgl_buat_status" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i>

                                </label>
                                <div class="col-sm-3">
                                    <input readonly type="text" required class="form-control" name="tgl_buat_status" id="tgl_buat_status" value="<?php echo $data['tgl_buat_status'] ?>" placeholder="Minute ...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="approve_by" class="col-sm-2 control-label">Approve oleh :</label>
                                <div class="col-sm-3">
                                    <input readonly type="text" width="100%" class="form-control" required name="approve_by" id="approve_by" value="<?php echo $data['approve_by'] ?>" placeholder="C°...">
                                </div>
                                <label for="approve_at" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i>

                                </label>
                                <div class="col-sm-3">
                                    <input readonly type="text" required class="form-control" name="approve_at" id="approve_at" value="<?php echo $data['approve_at'] ?>" placeholder="Minute ...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Done_Matching" class="col-sm-2 control-label">Tgl Done Matching</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control date-picker" disabled required name="Done_Matching" id="Done_Matching" placeholder="Tgl Selesai Matching" value="<?php if ($data['done_matching'] != "0000-00-00") echo $data['done_matching'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Done_Matching" class="col-sm-2 control-label">Final Matcher</label>
                                <div class="col-sm-6">
                                    <select disabled class="form-control select_Fmatcher" required name="f_matcher" id="f_matcher">
                                        <option value="<?php echo $data['final_matcher'] ?>" selected><?php echo $data['final_matcher'] ?></option>
                                    </select>
                                </div>
                            </div>
                            <?php if ($data['jenis_matching'] == "LD NOW" || $data['jenis_matching'] == "L/D") { ?>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 1</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="koreksi" id="koreksi">
                                            <option value="<?php echo $data['koreksi_resep'] ?>" selected><?php echo $data['koreksi_resep'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="koreksi2" id="koreksi2">
                                            <option value="<?php echo $data['koreksi_resep2'] ?>" selected><?php echo $data['koreksi_resep2'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 2</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="koreksi3" id="koreksi3">
                                            <option value="<?php echo $data['koreksi_resep3'] ?>" selected><?php echo $data['koreksi_resep3'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="koreksi4" id="koreksi4">
                                            <option value="<?php echo $data['koreksi_resep4'] ?>" selected><?php echo $data['koreksi_resep4'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 3</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="koreksi5" id="koreksi5">
                                            <option value="<?php echo $data['koreksi_resep5'] ?>" selected><?php echo $data['koreksi_resep5'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="koreksi6" id="koreksi6">
                                            <option value="<?php echo $data['koreksi_resep6'] ?>" selected><?php echo $data['koreksi_resep6'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 4</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="koreksi7" id="koreksi7">
                                            <option value="<?php echo $data['koreksi_resep7'] ?>" selected><?php echo $data['koreksi_resep7'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="koreksi8" id="koreksi8">
                                            <option value="<?php echo $data['koreksi_resep8'] ?>" selected><?php echo $data['koreksi_resep8'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 1</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="colorist_1" id="colorist_1">
                                            <option value="<?php echo $data['colorist1'] ?>" selected><?php echo $data['colorist1'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="colorist_2" id="colorist_2">
                                            <option value="<?php echo $data['colorist2'] ?>" selected><?php echo $data['colorist2'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 2</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="colorist_3" id="colorist_3">
                                            <option value="<?php echo $data['colorist3'] ?>" selected><?php echo $data['colorist3'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="colorist_4" id="colorist_4">
                                            <option value="<?php echo $data['colorist4'] ?>" selected><?php echo $data['colorist4'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 3</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="colorist_5" id="colorist_5">
                                            <option value="<?php echo $data['colorist5'] ?>" selected><?php echo $data['colorist5'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="colorist_6" id="colorist_6">
                                            <option value="<?php echo $data['colorist6'] ?>" selected><?php echo $data['colorist6'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 4</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="colorist_7" id="colorist_7">
                                            <option value="<?php echo $data['colorist7'] ?>" selected><?php echo $data['colorist7'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" name="colorist_8" id="colorist_8">
                                            <option value="<?php echo $data['colorist8'] ?>" selected><?php echo $data['colorist8'] ?></option>
                                        </select>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep</label>
                                    <div class="col-sm-3">
                                        <select disabled class="form-control select_Koreksi" required name="koreksi" id="koreksi">
                                            <option value="<?php echo $data['koreksi_resep'] ?>" selected><?php echo $data['koreksi_resep'] ?></option>
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep</label>
                                    <div class="col-sm-3">
                                        <select disabled class="form-control select_Koreksi" required name="koreksi2" id="koreksi2">
                                            <option value="<?php echo $data['koreksi_resep2'] ?>" selected><?php echo $data['koreksi_resep2'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist1</label>
                                    <div class="col-sm-3">
                                        <select disabled class="form-control select_Koreksi" required name="colorist_1" id="colorist_1">
                                            <option value="<?php echo $data['colorist1'] ?>" selected><?php echo $data['colorist1'] ?></option>
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist2</label>
                                    <div class="col-sm-3">
                                        <select disabled class="form-control select_Koreksi" required name="colorist_2" id="colorist_2">
                                            <option value="<?php echo $data['colorist2'] ?>" selected><?php echo $data['colorist2'] ?></option>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="form-group">
                                <label for="Matching-ke" class="col-sm-2 control-label">Percobaan-ke</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" required id="Matching-ke" name="Matching-ke" maxlength="2" placeholder="Matching Ke" value="<?php echo floatval($data['percobaan_ke']) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Matching-ke" class="col-sm-2 control-label">Percobaan berapa kali</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" required id="howmany_Matching-ke" name="howmany_Matching-ke" maxlength="2" placeholder="Percobaan Berapa Kali" value="<?php echo $data['howmany_percobaan_ke'] ?>">
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
                                    <input type="text" class="form-control" required id="kadar_air_true" name="kadar_air_true" placeholder="Kadar Air..." value="<?php echo floatval($data['kadar_air']) ?>">
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
                            <div class="form-group">
                                <label for="CIE_TINT" class="col-sm-2 control-label">YELLOWNESS</label>
                                <div class="col-sm-9">
                                    <input type="text" required class="form-control" name="YELLOWNESS" id="YELLOWNESS" placeholder="YELLOWNESS" value="<?php echo floatval($data['yellowness']) ?>">
                                </div>
                            </div>
                            <!-- <div class="form-group"> -->
                            <!-- <label for="Spektro R" required class="col-sm-2 control-label">Spektro R</label> -->
                            <!-- <div class="col-sm-9"> -->
                            <input type="hidden" required class="form-control" name="Spektro_R" id="Spektro_R" placeholder="Spektro Reading" value="<?php echo $data['spektro_r'] ?>">
                            <!-- </div> -->
                            <!-- </div> -->
                            <div class="form-group">
                                <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="keterangan" id="keterangan" rows="3"><?php echo $data['ket'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- step1 -->
            <div id="step1" class="tab-pane fade">
                <br />
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-6">
                            <button type="button" class="btn btn-success btn-xs" data-toggle="modal" id="btn_date_data" attr-data="<?php echo $data['id_status'] ?>"><i class="fa fa-calendar"></i> Date Data
                            </button>
                        </div>
                        <div class="align-right text-right" style="margin-bottom: 4px;">
                            <!-- <button type="button" id="show_adjust1" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Adjust-1</button> ▐&nbsp; -->
                            <div class="btn-group" id="showadjust">
                                <button type="button" class="btn btn-info btn-xs btn-flat">
                                    <i class="fa fa-eye"></i> Show Adjust</button>
                                <button type="button" class="btn btn-info btn-xs btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="javascript:void(0)" id="show_adjust1">Adjust-1</a></li>
                                    <li><a href="javascript:void(0)" id="show_adjust2">Adjust-2</a></li>
                                    <li><a href="javascript:void(0)" id="show_adjust3">Adjust-3</a></li>
                                    <li><a href="javascript:void(0)" id="show_adjust4">Adjust-4</a></li>
                                    <li><a href="javascript:void(0)" id="show_adjust5">Adjust-5</a></li>
                                    <li><a href="javascript:void(0)" id="show_adjust6">Adjust-6</a></li>
                                    <li><a href="javascript:void(0)" id="show_adjust7">Adjust-7</a></li>
                                    <li><a href="javascript:void(0)" id="show_adjust8">Adjust-8</a></li>
                                    <li><a href="javascript:void(0)" id="show_adjust9">Adjust-9</a></li>
                                </ul>
                            </div> ▐&nbsp;
                            <div class="btn-group" id="before">
                                <button type="button" class="btn btn-warning btn-xs btn-flat">
                                    <i class="fa fa-plus"></i> Before</button>
                                <button type="button" class="btn btn-warning btn-xs btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu" id="row_flag">
                                    <li><a href="javascript:void(0)">Action</a></li>
                                </ul>
                            </div> ▐&nbsp;
                            <button type="button" id="plus_c1" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Conc</button>
                            <button type="button" id="minus_c1" class="btn btn-danger btn-xs"><i class="fa fa-minus text-black"></i> Conc</button> ▐&nbsp;
                            <button type="button" id="plus1" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Baris</button>
                            <button type="button" id="minus1" class="btn btn-danger btn-xs"><i class="fa fa-minus text-black"></i> Baris</button>
                        </div>
                    </div>
                    <div class="col-lg-12 overflow-auto table-responsive well" style="overflow-x: auto;">
                        <table id="lookupmodal1" class="lookupST display nowrap" width="120%" style="padding-right: 16px;">
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
                            $hold_resep = mysqli_query($con, "SELECT * from tbl_matching_detail where `id_matching` = '$data[id]' and `id_status` = '$data[id_status]' AND NOT resep = 'dye' order by flag");
                            ?>
                            <tbody id="tb-lookup1">
                                <?php while ($hold = mysqli_fetch_array($hold_resep)) : ?>
                                    <tr id="<?php echo $hold['id'] ?>">
                                        <td align="center" class="nomor"><?php echo $hold['flag'] ?></td>
                                        <td>
                                            <select style="width: 100%" type="text" class="form-control input-xs" placeholder="type code here ..">
                                                <option value="<?php echo $hold['kode'] ?>" selected><?php echo $hold['kode'] ?></option>
                                            </select>
                                        </td>
                                        <td><input style="width: 100%" readonly type="text" class="form-control input-xs name" value="<?php echo $hold['nama'] ?>"></td>
                                        <td flag_td="1"><input readonly style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc1']) ?>"></td>
                                        <td flag_td="2"><input readonly style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc2']) ?>"></td>
                                        <td flag_td="3"><input readonly style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc3']) ?>"></td>
                                        <td flag_td="4"><input readonly style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc4']) ?>"></td>
                                        <td flag_td="5"><input readonly style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc5']) ?>"></td>
                                        <td flag_td="6"><input readonly style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc6']) ?>"></td>
                                        <td flag_td="7"><input readonly style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc7']) ?>"></td>
                                        <td flag_td="8"><input readonly style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc8']) ?>"></td>
                                        <td flag_td="9"><input readonly style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc9']) ?>"></td>
                                        <td flag_td="10"><input readonly style="width: 100%" type="text" class="form-control input-xs conc" value="<?php echo floatval($hold['conc10']) ?>"></td>
                                        <td><input readonly style="width: 100%" type="text" class="form-control input-xs remark" value="<?php echo $hold['remark'] ?>"></td>
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
                    <div class="col-lg-12 well" style="margin-top: 10px;">
                        <div class="box">
                            <div class="container-fluid">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-top: 15px;">
                                        <label for="L_R" class="col-sm-2 control-label">T-SIDE L:R :</label>
                                        <div class="col-sm-3">
                                            <select type="text" style="width:100%" class="form-control select2_lr" required name="L_R" id="L_R" placeholder="L_R">
                                                <option selected value="<?php echo $data['lr'] ?>"><?php echo $data['lr'] ?></option>
                                                <option value="1:6">1:6</option>
                                                <option value="1:9">1:9</option>
                                                <option value="1:10">1:10</option>
                                                <option value="1:12">1:12</option>
                                            </select>
                                            <span></span>
                                        </div>
                                        <label for="L_R" class="col-sm-2 control-label">C-SIDE L:R :</label>
                                        <div class="col-sm-3">
                                            <select type="text" style="width:100%" class="form-control second_lr" required name="second_lr" id="second_lr" placeholder="L_R">
                                                <option selected value="<?php echo $data['second_lr'] ?>"><?php echo $data['second_lr'] ?></option>
                                                <option value="1:6">1:6</option>
                                                <option value="1:9">1:9</option>
                                                <option value="1:10">1:10</option>
                                                <option value="1:12">1:12</option>
                                            </select>
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="L_R" class="col-sm-2 control-label">Ph :</label>
                                        <div class="col-sm-3">
                                            <input type="text" required class="form-control" name="kadar_air" id="kadar_air" value="<?php echo floatval($data['ph']) ?>" placeholder="ph air...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-top: 15px;">
                                    <div class="form-group">
                                        <label for="L_R" class="col-sm-1 control-label">Remark Dyeing</label>
                                        <div class="col-sm-11">
                                            <textarea type="text" class="form-control" name="remark_dye" id="remark_dye" placeholder="Remark ..." rows="8"><?php echo $data['remark_dye'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
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
                        <!-- end conditionally column -->
                    </div>
                </div>
            </div>
            <div id="addt_order" class="tab-pane fade">
                <div class="col-md-12" style="margin-top: 10px; background-color: white;">
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
                                <th>Waktu</th>
                                <th>Bon Resep</th>
                                <th>Note</th>
                                <th>Tgl Mulai</th>
                                <th>Analisa</th>
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
    </form>
    <!-- <button class="btn btn-success" id="test">test</button> -->
</body>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width:95%">
        <div class="modal-content">
            <div class="modal-body" id="modal_body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- SPINNER LOADING FOR SHOW LOADER ON AJAX PROCESS // THIS VERY IMPORTANT to PREVENT DATA NOT SENDED ! -->
<script type="text/javascript">
    var spinner = new jQuerySpinner({
        parentId: 'block-full-page'
    });

    function disableScroll() {
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
            window.onscroll = function() {
                window.scrollTo(scrollLeft, scrollTop);
            };
    }

    function enableScroll() {
        window.onscroll = function() {};
    }

    function SpinnerShow() {
        spinner.show();
        disableScroll()
    }

    function SpinnerHide() {
        setTimeout(function() {
            spinner.hide();
            enableScroll();
            window.location.href = 'index1.php?p=Adjust_Resep_Lab_New&idm=<?php echo $_GET["idm"] ?>';
        }, 4000);
    }
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
        if (parseFloat($('#lab').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='1']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='1']").remove()
            })
        } else {
            var fieldConc = 1;
            console.log(parseFloat($('#lab').html()))
        }

        if (parseFloat($('#Adj_1').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='2']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='2']").remove()
            })
        } else {
            var fieldConc = 2;
            console.log(parseFloat($('#Adj_2').html()))
        }
        if (parseFloat($('#Adj_2').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='3']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='3']").remove()
            })
        } else {
            var fieldConc = 3;
            console.log(parseFloat($('#Adj_3').html()))
        }
        if (parseFloat($('#Adj_3').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='4']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='4']").remove()
            })
        } else {
            var fieldConc = 4;
            console.log(parseFloat($('#Adj_3').html()))
        }
        if (parseFloat($('#Adj_4').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='5']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='5']").remove()
            })
        } else {
            var fieldConc = 5;
            console.log(parseFloat($('#Adj_4').html()))
        }
        if (parseFloat($('#Adj_5').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='6']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='6']").remove()
            })
        } else {
            var fieldConc = 6;
            console.log(parseFloat($('#Adj_5').html()))
        }
        if (parseFloat($('#Adj_6').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='7']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='7']").remove()
            })
        } else {
            var fieldConc = 7;
            console.log(parseFloat($('#Adj_6').html()))
        }
        if (parseFloat($('#Adj_7').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='8']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='8']").remove()
            })
        } else {
            var fieldConc = 8;
            console.log(parseFloat($('#Adj_7').html()))
        }
        if (parseFloat($('#Adj_8').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='9']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='9']").remove()
            })
        } else {
            var fieldConc = 9;
            console.log(parseFloat($('#Adj_8').html()))
        }
        if (parseFloat($('#Adj_9').html()) == 0) {
            $("#lookupmodal1 thead tr th[flag_th|='10']").remove()
            $('#lookupmodal1 tbody tr').each(function() {
                $(this).find("td[flag_td|='10']").remove()
            })
        } else {
            var fieldConc = 10;
            console.log(parseFloat($('#Adj_9').html()))
        }

        // HIDE FOOTER TABLE
        $('#tfoot').hide()

        $('#plus_c1').click(function() {
            var attribute = $("#th-lookup1 tr th:last").prev();
            var attri = attribute.attr('flag_th')
            var goesto = $('#tb-lookup1 tr td:last').prev();
            var goes = goesto.attr('flag_td');
            if (attri == undefined) {
                var flag = 1;
            } else if (attri == '10') {
                toastr.error('Adjust maximal in 9 column !');
            } else {
                var flag = parseInt(attri) + 1;
                var flag_td = parseInt(attri) + 1;
                $("#th-lookup1 th:last").before('<th width="60px" class="th_conc" flag_th="' + flag + '">Adjust-' + parseInt(flag - 1) + '</th>');
                $("#tb-lookup1 tr").each(function() {
                    if ($(this).find('td:last').prev().find('input').is('[disabled=""]')) {
                        $(this).find('td:last').before('<td flag_td="' + flag_td + '"><input style="width: 100%" type="text" class="form-control input-xs conc" disabled="" value="0"></td>');
                    } else {
                        $(this).find('td:last').before('<td flag_td="' + flag_td + '"><input value="' + $(this).find('td:last').prev().children().val() + '" style="width: 100%" type="text" class="form-control input-xs conc"></td>');
                    }
                })
            }
        })

        $('#minus_c1').click(function() {
            var attribute = $("#th-lookup1 tr th:last").prev();
            var flag_th = attribute.attr('flag_th')
            var goesto = $('#tb-lookup1 tr td:last').prev();
            var flag_td = goesto.attr('flag_td');
            // console.log();
            if (flag_th == fieldConc) {
                toastr.error('You cant delete Default Concentrate !')
            } else {
                $(attribute).remove();
                $("#tb-lookup1 tr").each(function() {
                    var last_c = $(this).find('td:last').prev();
                    $(last_c).remove();
                })
            }
        })

        $('#plus1').click(function() {
            let getno = $('#tb-lookup1 tr:last td:first').html();
            if (getno == undefined) {
                var nomor = 1;
            } else if (getno == '26') {
                toastr.error('Maximal column is 26 row !')
            } else {
                var nomor = parseInt(getno) + 1;
                $("#tb-lookup1").append(
                    '<tr>' + $('#tb-lookup1 tr:last').html() + '</tr>'
                );
                $('#tb-lookup1 tr:last td:first').html(nomor)
                $('#tb-lookup1 tr:last td:eq(1)').html('<select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here .."></select>')
                $('#tb-lookup1 tr:last').find('td').find('input.form-control.input-xs.conc').prop('readonly', false)
                $('#tb-lookup1 tr:last').find('td').find('input').val('')
                $('#tb-lookup1 tr:last td:last').find('input').prop('readonly', false)
            }
        })

        var row = $('#tb-lookup1').find('tr').length;

        $('#minus1').click(function() {
            if ($('#tb-lookup1').find('tr').length == row) {
                toastr.error('You cannot delete default CONCENTRATE !')
            } else {
                $('#tb-lookup1 tr:last').remove();
            }
        })
    });
</script>

<!-- DISABLED INPUT IF CODE DYESTUFF == '-------' -->
<script>
    $(document).ready(function() {
        $("#tb-lookup1 tr").each(function() {
            if ($(this).find('td:eq(1) select option:selected').val() == "---") {
                $(this).find('input').prop('disabled', true)
            }
        })
        $("#tb-lookup1 tr").each(function() {
            if ($(this).find('td:eq(1) select option:selected').val() == "") {
                $(this).find('input').prop('readonly', false)
                $(this).find('select').prop('disabled', false)
                $(this).find('td:eq(2) input').prop('readonly', true)
            }
        })

        $(document).on('click', '#btn_date_data', function(e) {
            var id_status = $(this).attr("attr-data");
            $.ajax({
                url: "pages/ajax/get_date_data.php",
                type: "GET",
                data: {
                    id: id_status,
                },
                success: function(ajaxData) {
                    $("#modal_body").html(ajaxData);
                    $("#myModal").modal('show', {
                        backdrop: 'true'
                    });
                }
            });
        });
    })
</script>

<!-- Jquery validation, alert if leave here ! -->
<script>
    $(document).ready(function() {
        $('.select2_lr').select2({
            placeholder: "Pilih...",
            tags: true
        });

        var form1 = $('#form-status');
        var error1 = $('.alert-danger', form1);

        form1.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error text-sm',
            // focusInvalid: false,
            ignore: "",
            rules: {
                L_R: {
                    required: true,
                },
                kadar_air_true: {
                    required: true,
                },
                kadar_air: {
                    required: true,
                },
                RCWaktu: {
                    required: true,
                },
                RC_Suhu: {
                    required: true,
                },
                soapingSuhu: {
                    required: true,
                },
                soapingWaktu: {
                    required: true,
                },
                CIE_WI: {
                    required: true,
                },
                CIE_TINT: {
                    required: true,
                },
                Spektro_R: {
                    required: true,
                },
                Done_matching: {
                    required: true,
                }
            },
            // messege error-------------------------------------------------------
            messages: {
                L_R: {
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

        $('#exsecute').click(function(e) {
            e.preventDefault();
            if ($("#form-status").valid()) {
                var count = $("#lookupmodal1 tbody tr").length;
                if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 1) {
                    $('#lookupmodal1 tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        var conc = $(this).find('td:eq(3) input').val();
                        if (code == undefined) {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else {
                            if (parseInt(index + 1) == count) {
                                Preparation_BeforeSend_dataStatus()
                            } else {
                                console.log(parseInt(index + 1))
                            }
                        }
                    });
                } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 2) {
                    $('#lookupmodal1 tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        var conc = $(this).find('td:eq(3) input').val();
                        var conc1 = $(this).find('td:eq(4) input').val();
                        if (code == undefined) {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc1 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else {
                            if (parseInt(index + 1) == count) {
                                Preparation_BeforeSend_dataStatus()
                            } else {
                                console.log(parseInt(index + 1))
                            }
                        }
                    });
                } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 3) {
                    $('#lookupmodal1 tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        var conc = $(this).find('td:eq(3) input').val();
                        var conc1 = $(this).find('td:eq(4) input').val();
                        var conc2 = $(this).find('td:eq(5) input').val();
                        if (code == undefined) {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc1 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc2 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else {

                            if (parseInt(index + 1) == count) {
                                Preparation_BeforeSend_dataStatus()
                            } else {
                                console.log(parseInt(index + 1))
                            }
                        }
                    });
                } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 4) {
                    $('#lookupmodal1 tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        var conc = $(this).find('td:eq(3) input').val();
                        var conc1 = $(this).find('td:eq(4) input').val();
                        var conc2 = $(this).find('td:eq(5) input').val();
                        var conc3 = $(this).find('td:eq(6) input').val();
                        if (code == undefined) {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc1 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc2 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc3 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else {
                            if (parseInt(index + 1) == count) {
                                Preparation_BeforeSend_dataStatus()
                            } else {
                                console.log(parseInt(index + 1))
                            }
                        }
                    });
                } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 5) {
                    $('#lookupmodal1 tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        var conc = $(this).find('td:eq(3) input').val();
                        var conc1 = $(this).find('td:eq(4) input').val();
                        var conc2 = $(this).find('td:eq(5) input').val();
                        var conc3 = $(this).find('td:eq(6) input').val();
                        var conc4 = $(this).find('td:eq(7) input').val();
                        if (code == undefined) {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc1 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc2 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc3 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc4 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else {
                            if (parseInt(index + 1) == count) {
                                Preparation_BeforeSend_dataStatus()
                            } else {
                                console.log(parseInt(index + 1))
                            }
                        }
                    });
                } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 6) {
                    $('#lookupmodal1 tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        var conc = $(this).find('td:eq(3) input').val();
                        var conc1 = $(this).find('td:eq(4) input').val();
                        var conc2 = $(this).find('td:eq(5) input').val();
                        var conc3 = $(this).find('td:eq(6) input').val();
                        var conc4 = $(this).find('td:eq(7) input').val();
                        var conc5 = $(this).find('td:eq(8) input').val();
                        if (code == undefined) {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc1 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc2 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc3 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc4 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc5 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else {
                            if (parseInt(index + 1) == count) {
                                Preparation_BeforeSend_dataStatus()
                            } else {
                                console.log(parseInt(index + 1))
                            }
                        }
                    });
                } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 7) {
                    $('#lookupmodal1 tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        var conc = $(this).find('td:eq(3) input').val();
                        var conc1 = $(this).find('td:eq(4) input').val();
                        var conc2 = $(this).find('td:eq(5) input').val();
                        var conc3 = $(this).find('td:eq(6) input').val();
                        var conc4 = $(this).find('td:eq(7) input').val();
                        var conc5 = $(this).find('td:eq(8) input').val();
                        var conc6 = $(this).find('td:eq(9) input').val();
                        if (code == undefined) {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc1 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc2 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc3 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc4 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc5 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc6 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else {
                            if (parseInt(index + 1) == count) {
                                Preparation_BeforeSend_dataStatus()
                            } else {
                                console.log(parseInt(index + 1))
                            }

                        }
                    });
                } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 8) {
                    $('#lookupmodal1 tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        var conc = $(this).find('td:eq(3) input').val();
                        var conc1 = $(this).find('td:eq(4) input').val();
                        var conc2 = $(this).find('td:eq(5) input').val();
                        var conc3 = $(this).find('td:eq(6) input').val();
                        var conc4 = $(this).find('td:eq(7) input').val();
                        var conc5 = $(this).find('td:eq(8) input').val();
                        var conc6 = $(this).find('td:eq(9) input').val();
                        var conc7 = $(this).find('td:eq(10) input').val();
                        if (code == undefined) {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc1 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc2 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc3 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc4 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc5 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc6 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc7 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else {

                            if (parseInt(index + 1) == count) {
                                Preparation_BeforeSend_dataStatus()
                            } else {
                                console.log(parseInt(index + 1))
                            }

                        }
                    });
                } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 9) {
                    $('#lookupmodal1 tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        var conc = $(this).find('td:eq(3) input').val();
                        var conc1 = $(this).find('td:eq(4) input').val();
                        var conc2 = $(this).find('td:eq(5) input').val();
                        var conc3 = $(this).find('td:eq(6) input').val();
                        var conc4 = $(this).find('td:eq(7) input').val();
                        var conc5 = $(this).find('td:eq(8) input').val();
                        var conc6 = $(this).find('td:eq(9) input').val();
                        var conc7 = $(this).find('td:eq(10) input').val();
                        var conc8 = $(this).find('td:eq(10) input').val();
                        if (code == undefined) {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc1 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc2 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc3 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc4 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc5 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc6 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc7 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc8 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else {

                            if (parseInt(index + 1) == count) {
                                Preparation_BeforeSend_dataStatus()
                            } else {
                                console.log(parseInt(index + 1))
                            }

                        }
                    });
                } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 10) {
                    $('#lookupmodal1 tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        var conc = $(this).find('td:eq(3) input').val();
                        var conc1 = $(this).find('td:eq(4) input').val();
                        var conc2 = $(this).find('td:eq(5) input').val();
                        var conc3 = $(this).find('td:eq(6) input').val();
                        var conc4 = $(this).find('td:eq(7) input').val();
                        var conc5 = $(this).find('td:eq(8) input').val();
                        var conc6 = $(this).find('td:eq(9) input').val();
                        var conc7 = $(this).find('td:eq(10) input').val();
                        var conc8 = $(this).find('td:eq(11) input').val();
                        var conc9 = $(this).find('td:eq(12) input').val();
                        if (code == undefined) {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc1 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc2 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc3 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc4 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc5 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc6 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc7 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc8 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else if (conc9 == "") {
                            toastr.error('Lengkapi table Resep baris ' + parseInt(index + 1) + ' atau hapus bila tidak digunakan !')
                            return false;
                        } else {

                            if (parseInt(index + 1) == count) {
                                Preparation_BeforeSend_dataStatus()
                            } else {
                                console.log(parseInt(index + 1))
                            }

                        }
                    });
                }
            } else {
                toastr.error('Tab <b>Basic Info</b> belum lengkap !');
            }
        });

        function Preparation_BeforeSend_dataStatus() {
            if ($("#RC_Suhu").val() == undefined) {
                var RC_Suhu = '';
            } else {
                var RC_Suhu = $("#RC_Suhu").val();
            }
            if ($("#RCWaktu").val() == undefined) {
                var RCWaktu = '';
            } else {
                var RCWaktu = $("#RCWaktu").val()
            }
            if ($("#soapingSuhu").val() == undefined) {
                var soapingSuhu = '';
            } else {
                var soapingSuhu = $("#soapingSuhu").val();
            }
            if ($("#soapingWaktu").val() == undefined) {
                var soapingWaktu = "";
            } else {
                var soapingWaktu = $('#soapingWaktu').val()
            }
            if ($("#tside_c").val() == undefined) {
                var tside_c = "";
            } else {
                var tside_c = $("#tside_c").val();
            }
            if ($("#tside_min").val() == undefined) {
                var tside_min = "";
            } else {
                var tside_min = $("#tside_min").val();
            }
            if ($("#cside_c").val() == undefined) {
                var cside_c = "";
            } else {
                var cside_c = $("#cside_c").val();
            }
            if ($("#cside_min").val() == undefined) {
                var cside_min = "";
            } else {
                var cside_min = $("#cside_min").val();
            }
            if ($("#bleaching_sh").val() == undefined) {
                var bleaching_sh = "";
            } else {
                var bleaching_sh = $("#bleaching_sh").val();
            }
            if ($("#bleaching_tm").val() == undefined) {
                var bleaching_tm = "";
            } else {
                var bleaching_tm = $("#bleaching_tm").val();
            }
            insertInto_StatusMatching_DetailMatching($("#id_tblmatching").val(), $("#id_matching").val(), $("#id_status").val(), $("#idm").val(), $('#recipe_code').val(), $('#Matching-ke').val(), $('#howmany_Matching-ke').val(), $('#BENANG-A').val(), $("#LEBAR-A").val(), $("#GRAMASI-A").val(), $("#L_R").find('option:selected').val(), $("#kadar_air").val(), RC_Suhu, RCWaktu, soapingSuhu, soapingWaktu, $("#CIE_WI").val(), $("#CIE_TINT").val(), $("#YELLOWNESS").val(), $("#Spektro_R").val(), $("#Done_Matching").val(), $("#keterangan").val(), $("#tgl_buat_status").val(), cside_c, cside_min, tside_c, tside_min, $("#kadar_air_true").val(), bleaching_sh, bleaching_tm, $('#second_lr').find('option:selected').val(), $('#remark_dye').val())
        }

        function insertInto_StatusMatching_DetailMatching(id_tblmatching, id_matching, id_status, idm, recipe_code, matching_ke, howmany_Matching_ke, benang_a, lebar_a, gramasi_a, l_R, kadar_air, RC_Suhu, RCWaktu, soapingSuhu, soapingWaktu, cie_wi, cie_tint, yellowness, Spektro_R, Done_Matching, keterangan, tgl_buat_status, cside_c, cside_min, tside_c, tside_min, kadar_air_true, bleaching_sh, bleaching_tm, second_lr, remark_dye) {
            SpinnerShow()
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/Update_resep.php",
                data: {
                    id_tblmatching: id_tblmatching,
                    id_matching: id_matching,
                    id_status: id_status,
                    idm: idm,
                    recipe_code: recipe_code,
                    matching_ke: matching_ke,
                    howmany_Matching_ke: howmany_Matching_ke,
                    benang_a: benang_a,
                    lebar_a: lebar_a,
                    gramasi_a: gramasi_a,
                    l_R: l_R,
                    kadar_air: kadar_air,
                    RC_Suhu: RC_Suhu,
                    RCWaktu: RCWaktu,
                    soapingSuhu: soapingSuhu,
                    soapingWaktu: soapingWaktu,
                    cie_wi: cie_wi,
                    cie_tint: cie_tint,
                    yellowness: yellowness,
                    Spektro_R: Spektro_R,
                    Done_Matching: Done_Matching,
                    keterangan: keterangan,
                    tgl_buat_status: tgl_buat_status,
                    cside_c: cside_c,
                    cside_min: cside_min,
                    tside_c: tside_c,
                    tside_min: tside_min,
                    kadar_air_true: kadar_air_true,
                    bleaching_sh: bleaching_sh,
                    bleaching_tm: bleaching_tm,
                    second_lr: second_lr,
                    remark_dye: remark_dye,
                },
                success: function(response) {
                    if (response.session == "LIB_SUCCSS") {
                        console.log(response)
                        Update_tableResep_OnApprvRsp();
                        // SendMessage(idm);
                    } else {
                        toastr.error("ajax error !")
                    }
                },
                error: function() {
                    alert("Error");
                }
            });
        }

        // function SendMessage(idm) {
        //     $.ajax({
        //         dataType: "json",
        //         type: "POST",
        //         url: "pages/sendMessage.php",
        //         data: {
        //             message_text: '<php echo $_SESSION["userLAB"] ?> Telah memodifikasi Resep dengan Rcode : ' + idm,
        //         },
        //         success: function(response) {
        //             // toastr.error('berhasil kirim pesan')
        //             console.log('got it telegram sended')
        //         },
        //         error: function() {
        //             console.log('telegram error')
        //             // alert("telegram error");
        //         }
        //     });
        // }

        function Update_tableResep_OnApprvRsp() {
            if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 1) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax here
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Update_tableResep_OnApprvRsp.php",
                        data: {
                            flag: flag,
                            id_matching: id_matching,
                            id_status: id_status,
                            code: code,
                            desc_code: desc_code,
                            conc: conc,
                            keterangan: keterangan
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                console.log(response)
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 2) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var conc1 = $(this).find('td:eq(4) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax here
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Update_tableResep_OnApprvRsp.php",
                        data: {
                            flag: flag,
                            id_matching: id_matching,
                            id_status: id_status,
                            code: code,
                            desc_code: desc_code,
                            conc: conc,
                            conc1: conc1,
                            keterangan: keterangan
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                console.log(response)
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 3) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var conc1 = $(this).find('td:eq(4) input').val();
                    var conc2 = $(this).find('td:eq(5) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax here
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Update_tableResep_OnApprvRsp.php",
                        data: {
                            flag: flag,
                            id_matching: id_matching,
                            id_status: id_status,
                            code: code,
                            desc_code: desc_code,
                            conc: conc,
                            conc1: conc1,
                            conc2: conc2,
                            keterangan: keterangan
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                console.log(response)
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 4) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var conc1 = $(this).find('td:eq(4) input').val();
                    var conc2 = $(this).find('td:eq(5) input').val();
                    var conc3 = $(this).find('td:eq(6) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Update_tableResep_OnApprvRsp.php",
                        data: {
                            flag: flag,
                            id_matching: id_matching,
                            id_status: id_status,
                            code: code,
                            desc_code: desc_code,
                            conc: conc,
                            conc1: conc1,
                            conc2: conc2,
                            conc3: conc3,
                            keterangan: keterangan
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                console.log(response)
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 5) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var conc1 = $(this).find('td:eq(4) input').val();
                    var conc2 = $(this).find('td:eq(5) input').val();
                    var conc3 = $(this).find('td:eq(6) input').val();
                    var conc4 = $(this).find('td:eq(7) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Update_tableResep_OnApprvRsp.php",
                        data: {
                            flag: flag,
                            id_matching: id_matching,
                            id_status: id_status,
                            code: code,
                            desc_code: desc_code,
                            conc: conc,
                            conc1: conc1,
                            conc2: conc2,
                            conc3: conc3,
                            conc4: conc4,
                            keterangan: keterangan
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                console.log(response.session)
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 6) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var conc1 = $(this).find('td:eq(4) input').val();
                    var conc2 = $(this).find('td:eq(5) input').val();
                    var conc3 = $(this).find('td:eq(6) input').val();
                    var conc4 = $(this).find('td:eq(7) input').val();
                    var conc5 = $(this).find('td:eq(8) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Update_tableResep_OnApprvRsp.php",
                        data: {
                            flag: flag,
                            id_matching: id_matching,
                            id_status: id_status,
                            code: code,
                            desc_code: desc_code,
                            conc: conc,
                            conc1: conc1,
                            conc2: conc2,
                            conc3: conc3,
                            conc4: conc4,
                            conc5: conc5,
                            keterangan: keterangan
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                console.log(response)
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 7) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var conc1 = $(this).find('td:eq(4) input').val();
                    var conc2 = $(this).find('td:eq(5) input').val();
                    var conc3 = $(this).find('td:eq(6) input').val();
                    var conc4 = $(this).find('td:eq(7) input').val();
                    var conc5 = $(this).find('td:eq(8) input').val();
                    var conc6 = $(this).find('td:eq(9) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Update_tableResep_OnApprvRsp.php",
                        data: {
                            flag: flag,
                            id_matching: id_matching,
                            id_status: id_status,
                            code: code,
                            desc_code: desc_code,
                            conc: conc,
                            conc1: conc1,
                            conc2: conc2,
                            conc3: conc3,
                            conc4: conc4,
                            conc5: conc5,
                            conc6: conc6,
                            keterangan: keterangan
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                console.log(response)
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 8) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var conc1 = $(this).find('td:eq(4) input').val();
                    var conc2 = $(this).find('td:eq(5) input').val();
                    var conc3 = $(this).find('td:eq(6) input').val();
                    var conc4 = $(this).find('td:eq(7) input').val();
                    var conc5 = $(this).find('td:eq(8) input').val();
                    var conc6 = $(this).find('td:eq(9) input').val();
                    var conc7 = $(this).find('td:eq(10) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Update_tableResep_OnApprvRsp.php",
                        data: {
                            flag: flag,
                            id_matching: id_matching,
                            id_status: id_status,
                            code: code,
                            desc_code: desc_code,
                            conc: conc,
                            conc1: conc1,
                            conc2: conc2,
                            conc3: conc3,
                            conc4: conc4,
                            conc5: conc5,
                            conc6: conc6,
                            conc7: conc7,
                            keterangan: keterangan
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                console.log(response)
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 9) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var conc1 = $(this).find('td:eq(4) input').val();
                    var conc2 = $(this).find('td:eq(5) input').val();
                    var conc3 = $(this).find('td:eq(6) input').val();
                    var conc4 = $(this).find('td:eq(7) input').val();
                    var conc5 = $(this).find('td:eq(8) input').val();
                    var conc6 = $(this).find('td:eq(9) input').val();
                    var conc7 = $(this).find('td:eq(10) input').val();
                    var conc8 = $(this).find('td:eq(11) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Update_tableResep_OnApprvRsp.php",
                        data: {
                            flag: flag,
                            id_matching: id_matching,
                            id_status: id_status,
                            code: code,
                            desc_code: desc_code,
                            conc: conc,
                            conc1: conc1,
                            conc2: conc2,
                            conc3: conc3,
                            conc4: conc4,
                            conc5: conc5,
                            conc6: conc6,
                            conc7: conc7,
                            conc8: conc8,
                            keterangan: keterangan
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                console.log(response)
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 10) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var conc1 = $(this).find('td:eq(4) input').val();
                    var conc2 = $(this).find('td:eq(5) input').val();
                    var conc3 = $(this).find('td:eq(6) input').val();
                    var conc4 = $(this).find('td:eq(7) input').val();
                    var conc5 = $(this).find('td:eq(8) input').val();
                    var conc6 = $(this).find('td:eq(9) input').val();
                    var conc7 = $(this).find('td:eq(10) input').val();
                    var conc8 = $(this).find('td:eq(11) input').val();
                    var conc9 = $(this).find('td:eq(12) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Update_tableResep_OnApprvRsp.php",
                        data: {
                            flag: flag,
                            id_matching: id_matching,
                            id_status: id_status,
                            code: code,
                            desc_code: desc_code,
                            conc: conc,
                            conc1: conc1,
                            conc2: conc2,
                            conc3: conc3,
                            conc4: conc4,
                            conc5: conc5,
                            conc6: conc6,
                            conc7: conc7,
                            conc8: conc8,
                            conc9: conc9,
                            keterangan: keterangan
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                console.log(response)
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                });
            }
            SpinnerHide();
            // window.location.href = 'index1.php?p=Status-Matching';
        }

    });
</script>

<!-- on focus just can input integer -->
<script>
    $(document).on('focus', '.form-control.input-xs.conc', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('focus', '#Matching-ke', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('focus', '#LEBAR-A', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('focus', '#GRAMASI-A', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('focus', '#kadar_air_true', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('input', '#kadar_air', function() {
        this.value = this.value.replace(/(?!^-)[^0-9.]/g, "").replace(/(\..*)\./g, '$1');
        var values = $(this).val()
        if ((values !== '') && (values.indexOf('.') === -1)) {
            $(this).val(Math.max(Math.min(values, 14), -14));
        }
    })
    $(document).on('focus', '#soapingSuhu', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('focus', '#soapingWaktu', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('focus', '#RC_Suhu', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('focus', '#RCWaktu', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('focus', '#bleaching_sh', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('focus', '#bleaching_tm', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('input', '#CIE_WI', function() {
        this.value = this.value.replace(/(?!^-)[^0-9.]/g, "").replace(/(\..*)\./g, '$1');
    })
    $(document).on('input', '#CIE_TINT', function() {
        this.value = this.value.replace(/(?!^-)[^0-9.]/g, "").replace(/(\..*)\./g, '$1');
    })
    $(document).on('focus', '#Spektro_R', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
</script>

<!-- Ajax Select2  -->
<script>
    $(document).on('click', ".form-control.input-xs.code", function() {
        $(this).select2({
            minimumInputLength: 2,
            allowClear: true,
            placeholder: 'Insert code',
            ajax: {
                dataType: 'json',
                url: 'pages/ajax/tabledyestuff/GetCodedyestuff.php',
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
        }).on('select2:select', function(evt) {
            var select_selected = $(this).find(':selected').val();
            var getTr = $(this).parent().parent();
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/tabledyestuff/GetNameFcode.php",
                data: {
                    code: select_selected
                },
                // success: function(response) {
                //     $(getTr).find("td:eq(2)").find('input').val(response);
                //     if (response == "-----------------------") {
                //         $(getTr).find("input.form-control.input-xs.conc").val(0);
                //         $(getTr).find("input.form-control.input-xs.conc").prop('disabled', true);
                //         $(getTr).find("td:last").find('input').val("-----------------------");
                //         $(getTr).find("td:last").find('input').prop('disabled', true);
                //     } else {
                //         $(getTr).find("input.form-control.input-xs.conc").prop('disabled', false);
                //         $(getTr).find("td:last").find('input').prop('disabled', false);
                //         // $(getTr).find("input.form-control.input-xs.conc").val('');
                //         // $(getTr).find("td:last").find('input').val("");
                //     }
                // },
                success: function(response) {
                    $(getTr).find("td:eq(2)").find('input').val(response.Product_Name);
                    console.log(response.Product_Name);
                    if (response.Product_Name == "-----------------------") {
                        $(getTr).find("input.form-control.input-xs.conc").val(0);
                        $(getTr).find("input.form-control.input-xs.conc").prop('disabled', true);
                        $(getTr).find("td:last").find('input').val("-----------------------");
                        $(getTr).find("td:last").find('input').prop('disabled', true);
                    } else {
                        if (response.ket == "Suhu") {
                            $(getTr).find("input.form-control.input-xs.conc").prop('disabled', false);
                            $(getTr).find("td:last").find('input').prop('disabled', false);
                            $(getTr).find("input.form-control.input-xs.conc").val(0);
                            $(getTr).find("td:last").find('input').val("");
                        } else {
                            $(getTr).find("input.form-control.input-xs.conc").prop('disabled', false);
                            $(getTr).find("td:last").find('input').prop('disabled', false);
                            // $(getTr).find("input.form-control.input-xs.conc").val('');
                            // $(getTr).find("td:last").find('input').val("");
                        }
                    }
                },
                error: function() {
                    alert("Hubungi Departement DIT !");
                }
            });
        });
    })
</script>

<!-- ADD & DELETE ROW COLUMN FUNCTIONALITY  -->
<script>
    $(document).ready(function() {
        $('#second_lr').select2({
            placeholder: "Pilih...",
            tags: true
        });
        var row = $('#tb-lookup1').find('tr').length;
        $('#lookupmodal1').on("keydown", function(e) {
            if (e.which == 13) {
                let getno = $('#tb-lookup1 tr:last td:first').html();
                if (getno == undefined) {
                    var nomor = 1;
                } else if (getno == '26') {
                    toastr.error('Maximal column is 26 row !')
                } else {
                    var nomor = parseInt(getno) + 1;
                    $("#tb-lookup1").append(
                        '<tr>' + $('#tb-lookup1 tr:last').html() + '</tr>'
                    );
                    $('#tb-lookup1 tr:last td:first').html(nomor)
                    $('#tb-lookup1 tr:last td:eq(1)').html('<select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here .."></select>')
                    $('#tb-lookup1 tr:last').find('td').find('input.form-control.input-xs.conc').prop('disabled', false)
                    $('#tb-lookup1 tr:last').find('td').find('input').val('')
                    $('#tb-lookup1 tr:last td:last').find('input').prop('disabled', false)
                }
            }
            if (e.which == 220) {
                if ($('#tb-lookup1').find('tr').length == row) {
                    toastr.error('You cannot delete entire table !')
                } else {
                    $('#tb-lookup1 tr:last').remove();
                }
            }
        });

        $('#before').click(function() {
            $("#row_flag").html('');
            $("#lookupmodal1 tbody tr").each(function() {
                $("#row_flag").append('<li><a href="javascript:void(0)" class="selected_before"> Before ' + $(this).find('td:eq(0)').html() + '</a></li>');
            })
        })

        $(document).on('click', '.selected_before', function() {
            // SpinnerShow()
            var flag = $(this).html().substring(8);
            $("#lookupmodal1 tbody tr").each(function() {
                if ($(this).find('td:eq(0)').html() == flag) {
                    $(this).before('<tr>' + $(this).html() + '</tr>')
                    $(this).find('td:eq(0)').html(parseInt($(this).find('td:eq(0)').html()) + 1)
                } else {
                    if (parseInt($(this).find('td:eq(0)').html()) > flag) {
                        $(this).find('td:eq(0)').html(parseInt($(this).find('td:eq(0)').html()) + 1)
                    }
                }
            })
            $("#lookupmodal1 tbody tr").each(function() {
                if ($(this).find('td:eq(0)').html() == flag) {
                    $(this).find('td:eq(1)').html('<select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here .."></select>')
                    $(this).find('td').find('input.form-control.input-xs.conc').prop('readonly', false)
                    $(this).find('td').find('input').val('')
                    $(this).find('td:last').find('input').prop('readonly', false)
                    console.log('sama ' + $(this).find('td:eq(0)').html() + ' ' + flag);
                } else {
                    console.log('beda ' + $(this).find('td:eq(0)').html() + ' ' + flag);
                }
            })
        })

        //Show Adjust Table
        $('#show_adjust1').click(function() {
            $("#th-lookup1 th:eq(4)").before('<th width="60px" class="th_conc" flag_th="2">Adjust-1</th>');
            $("#tb-lookup1 tr").each(function() {
                if ($(this).find('td:eq(4)').prev().find('input').is('[disabled=""]')) {
                    $(this).find('td:eq(4)').before('<td flag_td="2"><input style="width: 100%" type="text" class="form-control input-xs conc" disabled="" value="0"></td>');
                } else {
                    $(this).find('td:eq(4)').before('<td flag_td="2"><input value="0" style="width: 100%" type="text" class="form-control input-xs conc"></td>');
                }
            })
        })
        $('#show_adjust2').click(function() {
            var flag = $(this).html().substring(8);
            if ($(this).find('td:eq(5)').html() <= flag) {
                toastr.error('Insert Adjust-1 Terlebih Dahulu !')
            } else {
                $("#th-lookup1 th:eq(5)").before('<th width="60px" class="th_conc" flag_th="3">Adjust-2</th>');
                $("#tb-lookup1 tr").each(function() {
                    if ($(this).find('td:eq(5)').prev().find('input').is('[disabled=""]')) {
                        $(this).find('td:eq(5)').before('<td flag_td="3"><input style="width: 100%" type="text" class="form-control input-xs conc" disabled="" value="0"></td>');
                    } else {
                        $(this).find('td:eq(5)').before('<td flag_td="3"><input value="0" style="width: 100%" type="text" class="form-control input-xs conc"></td>');
                    }
                })
            }
        })
        $('#show_adjust3').click(function() {
            var flag = $(this).html().substring(8);
            if ($(this).find('td:eq(6)').html() <= flag) {
                toastr.error('Insert Adjust-2 Terlebih Dahulu !')
            } else {
                $("#th-lookup1 th:eq(6)").before('<th width="60px" class="th_conc" flag_th="4">Adjust-3</th>');
                $("#tb-lookup1 tr").each(function() {
                    if ($(this).find('td:eq(6)').prev().find('input').is('[disabled=""]')) {
                        $(this).find('td:eq(6)').before('<td flag_td="4"><input style="width: 100%" type="text" class="form-control input-xs conc" disabled="" value="0"></td>');
                    } else {
                        $(this).find('td:eq(6)').before('<td flag_td="4"><input value="0" style="width: 100%" type="text" class="form-control input-xs conc"></td>');
                    }
                })
            }
        })
        $('#show_adjust4').click(function() {
            var flag = $(this).html().substring(8);
            if ($(this).find('td:eq(7)').html() <= flag) {
                toastr.error('Insert Adjust-3 Terlebih Dahulu !')
            } else {
                $("#th-lookup1 th:eq(7)").before('<th width="60px" class="th_conc" flag_th="5">Adjust-4</th>');
                $("#tb-lookup1 tr").each(function() {
                    if ($(this).find('td:eq(7)').prev().find('input').is('[disabled=""]')) {
                        $(this).find('td:eq(7)').before('<td flag_td="5"><input style="width: 100%" type="text" class="form-control input-xs conc" disabled="" value="0"></td>');
                    } else {
                        $(this).find('td:eq(7)').before('<td flag_td="5"><input value="0" style="width: 100%" type="text" class="form-control input-xs conc"></td>');
                    }
                })
            }
        })
        $('#show_adjust5').click(function() {
            var flag = $(this).html().substring(8);
            if ($(this).find('td:eq(8)').html() <= flag) {
                toastr.error('Insert Adjust-4 Terlebih Dahulu !')
            } else {
                $("#th-lookup1 th:eq(8)").before('<th width="60px" class="th_conc" flag_th="6">Adjust-5</th>');
                $("#tb-lookup1 tr").each(function() {
                    if ($(this).find('td:eq(8)').prev().find('input').is('[disabled=""]')) {
                        $(this).find('td:eq(8)').before('<td flag_td="6"><input style="width: 100%" type="text" class="form-control input-xs conc" disabled="" value="0"></td>');
                    } else {
                        $(this).find('td:eq(8)').before('<td flag_td="6"><input value="0" style="width: 100%" type="text" class="form-control input-xs conc"></td>');
                    }
                })
            }
        })
        $('#show_adjust6').click(function() {
            var flag = $(this).html().substring(8);
            if ($(this).find('td:eq(9)').html() <= flag) {
                toastr.error('Insert Adjust-5 Terlebih Dahulu !')
            } else {
                $("#th-lookup1 th:eq(9)").before('<th width="60px" class="th_conc" flag_th="7">Adjust-6</th>');
                $("#tb-lookup1 tr").each(function() {
                    if ($(this).find('td:eq(9)').prev().find('input').is('[disabled=""]')) {
                        $(this).find('td:eq(9)').before('<td flag_td="7"><input style="width: 100%" type="text" class="form-control input-xs conc" disabled="" value="0"></td>');
                    } else {
                        $(this).find('td:eq(9)').before('<td flag_td="7"><input value="0" style="width: 100%" type="text" class="form-control input-xs conc"></td>');
                    }
                })
            }
        })
        $('#show_adjust7').click(function() {
            var flag = $(this).html().substring(8);
            if ($(this).find('td:eq(10)').html() <= flag) {
                toastr.error('Insert Adjust-6 Terlebih Dahulu !')
            } else {
                $("#th-lookup1 th:eq(10)").before('<th width="60px" class="th_conc" flag_th="8">Adjust-7</th>');
                $("#tb-lookup1 tr").each(function() {
                    if ($(this).find('td:eq(10)').prev().find('input').is('[disabled=""]')) {
                        $(this).find('td:eq(10)').before('<td flag_td="8"><input style="width: 100%" type="text" class="form-control input-xs conc" disabled="" value="0"></td>');
                    } else {
                        $(this).find('td:eq(10)').before('<td flag_td="8"><input value="0" style="width: 100%" type="text" class="form-control input-xs conc"></td>');
                    }
                })
            }
        })
        $('#show_adjust8').click(function() {
            var flag = $(this).html().substring(8);
            if ($(this).find('td:eq(11)').html() <= flag) {
                toastr.error('Insert Adjust-7 Terlebih Dahulu !')
            } else {
                $("#th-lookup1 th:eq(11)").before('<th width="60px" class="th_conc" flag_th="9">Adjust-8</th>');
                $("#tb-lookup1 tr").each(function() {
                    if ($(this).find('td:eq(11)').prev().find('input').is('[disabled=""]')) {
                        $(this).find('td:eq(11)').before('<td flag_td="9"><input style="width: 100%" type="text" class="form-control input-xs conc" disabled="" value="0"></td>');
                    } else {
                        $(this).find('td:eq(11)').before('<td flag_td="9"><input value="0" style="width: 100%" type="text" class="form-control input-xs conc"></td>');
                    }
                })
            }
        })
        $('#show_adjust9').click(function() {
            var flag = $(this).html().substring(8);
            if ($(this).find('td:eq(12)').html() <= flag) {
                toastr.error('Insert Adjust-8 Terlebih Dahulu !')
            } else {
                $("#th-lookup1 th:eq(12)").before('<th width="60px" class="th_conc" flag_th="10">Adjust-9</th>');
                $("#tb-lookup1 tr").each(function() {
                    if ($(this).find('td:eq(12)').prev().find('input').is('[disabled=""]')) {
                        $(this).find('td:eq(12)').before('<td flag_td="10"><input style="width: 100%" type="text" class="form-control input-xs conc" disabled="" value="0"></td>');
                    } else {
                        $(this).find('td:eq(12)').before('<td flag_td="10"><input value="0" style="width: 100%" type="text" class="form-control input-xs conc"></td>');
                    }
                })
            }
        })


        // Table Dynamic Drageable
        $('#tb-lookup1').sortable({
            placeholder: "ui-state-highlight",
            update: function(event, ui) {
                var page_id_array = new Array();
                $('#tb-lookup1 tr').each(function() {
                    page_id_array.push($(this).attr('id'));
                });

                $("#lookupmodal1 tbody tr").each(function(index) {
                    $(this).find('td:eq(0)').html(index + 1);
                })
            }
        });

        <?php if ($_SESSION['jabatanLAB'] == 'Super admin') : ?>
            $('#lookupmodal1 tbody tr td input.form-control.input-xs.conc').prop('readonly', false);
        <?php endif; ?>

    })
</script>

<!-- Hasil CELUP funcionality -->
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
                    p: "Adjust_Resep_Lab_New"
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
                    if (colIndex == 8) {
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

        $(document).on('click', '.posisi_kk', function() {
            var url_bon = $(this).attr('data');
            centeredPopup(url_bon, 'myWindow', '800', '400', 'yes');
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

<!-- additional order dataserver -->
<script>
    $(document).ready(function() {
        var dataTable = $('#additional_order_table').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 50,
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
                    id_matching: $('#id_matching').val(),
                    id_status: $('#id_status').val()
                },
                error: function() {
                    $(".dataku-error").html("");
                    $("#dataku").append('<tbody class="dataku-error"><tr><th colspan="3">Tidak ada data untuk ditampilkan</th></tr></tbody>');
                    $("#dataku-error-proses").css("display", "none");
                }
            },
            "columnDefs": [{
                    "className": "text-center",
                    "targets": [0, 1, 2, 3, 4, 5]
                },
                {
                    "targets": [0],
                    "visible": false
                }
            ],
        });
    })
</script>

<script>
    $(document).ready(function() {
        $('#suhu_chamber').change(function() {
            let isChecked = $(this).is(':checked') ? 1 : 0;
            $.post('pages/ajax/update_suhuchamber_warna_flourescent.php?idm=<?= $_GET['idm']; ?>', {
                setting: 'suhu_chamber',
                value: isChecked
            }, function(response) {
                if (response.trim() === 'OK') {
                    Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Pengaturan Suhu Chamber berhasil diperbarui!'
                    });
                } else {
                    Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memperbarui.'
                    });
                }
            });
        });

        $('#warna_fluorescent').change(function() {
            let isChecked = $(this).is(':checked') ? 1 : 0;
            $.post('pages/ajax/update_suhuchamber_warna_flourescent.php?idm=<?= $_GET['idm']; ?>', {
                setting: 'warna_flourescent',
                value: isChecked
            }, function(response) {
                if (response.trim() === 'OK') {
                    Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Pengaturan Warna Fluorescent berhasil diperbarui!'
                    });
                } else {
                    Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memperbarui.'
                    });
                }
            });
        });
    });
</script>