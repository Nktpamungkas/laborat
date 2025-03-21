<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
$sql = mysqli_query($con, "SELECT a.id as id_status, a.idm, a.flag, a.grp, a.matcher, a.cek_warna, a.cek_dye, a.status, a.kt_status, a.koreksi_resep, a.koreksi_resep2,a.koreksi_resep3, a.koreksi_resep4,a.koreksi_resep5, a.koreksi_resep6,a.koreksi_resep7, a.koreksi_resep8, a.create_resep, a.acc_ulang_ok, a.acc_resep1, a.acc_resep2, a.percobaan_ke, a.benang_aktual, a.lebar_aktual, a.gramasi_aktual, a.soaping_sh, a.soaping_tm, a.rc_sh, a.rc_tm, a.lr, a.cie_wi, a.cie_tint, a.yellowness, a.done_matching, a.ph,
a.spektro_r, a.ket, a.created_at as tgl_buat_status, a.created_by as status_created_by, a.edited_at, a.edited_by, a.target_selesai, a.cside_c,
a.cside_min, a.tside_c, a.tside_min, a.mulai_by, a.mulai_at, a.selesai_by, a.selesai_at, a.approve_by, a.approve_at, a.approve,
b.id, b.no_resep, b.no_order, b.no_po, b.langganan, b.no_item, b.jenis_kain, b.benang, b.cocok_warna, b.warna, a.kadar_air,
b.no_warna, b.lebar, b.gramasi, b.qty_order, b.tgl_in, b.tgl_out, b.proses, b.buyer, a.final_matcher, a.colorist1, a.colorist2,a.colorist3, a.colorist4,a.colorist5, a.colorist6,a.colorist7, a.colorist8,
b.tgl_delivery, b.note, b.jenis_matching, b.tgl_buat, b.tgl_update, b.created_by, a.bleaching_sh, a.bleaching_tm, a.second_lr, b.color_code, b.recipe_code
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
    <div class="container col-md-12">
        <?php if ($data['approve'] == 'TRUE') : ?>
            <button class="btn btn pull-right" style="background-color: grey; color: white; margin-bottom: 10px;"><?php echo $data['idm'] ?> <i class="fa fa-arrow-right" aria-hidden="true"></i>
                <strong>Selesai</strong></button>
        <?php else : ?>
            <button class="btn btn pull-right" style="background-color: white; color: black; margin-bottom: 10px;"><strong><?php echo $data['idm'] ?></strong></button>
        <?php endif; ?>
    </div>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#input-status"><b>Basic Info</b></a></li>
            <li id="tab_resep"><a data-toggle="tab" href="#step1"><b>RESEP</b></a></li>
            <li class="pull-right">
                <?php if ($data['approve'] == 'NONE' && $data['status'] == 'selesai') : ?>
                    <button type="button" style="color: white; width: 150px;" class="btn btn-block btn-sm btn-success approve" idm="<?php echo $data['idm'] ?>" id_status="<?php echo $data['id_status'] ?>"><strong>Approve ! <i class="fa fa-check-circle"></i></strong></button>
                <?php else : ?>
                    <button style="width: 150px; background-color: grey; color: white;" class="btn"><strong>Status > <?php echo $data['status'] ?></strong><i class="fa fa-fw fa-print"></i></button>
                <?php endif; ?>
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
                    </div>
                    <!-- KANAN -->
                    <div class="col-md-7">
                        <div class="form-group">
                            <!-- tambahan -->
                            <div class="form-group">
                                <label for="status_created_by" class="col-sm-2 control-label">Dibuat oleh :</label>
                                <div class="col-sm-3">
                                    <input type="text" width="100%" class="form-control" required name="status_created_by" id="status_created_by" value="<?php echo $data['status_created_by'] ?>" placeholder="C°...">
                                </div>
                                <label for="tgl_buat_status" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i>

                                </label>
                                <div class="col-sm-3">
                                    <input type="text" required class="form-control" name="tgl_buat_status" id="tgl_buat_status" value="<?php echo $data['tgl_buat_status'] ?>" placeholder="Minute ...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="approve_by" class="col-sm-2 control-label">Approve oleh :</label>
                                <div class="col-sm-3">
                                    <input type="text" width="100%" class="form-control" required name="approve_by" id="approve_by" value="<?php echo $data['approve_by'] ?>" placeholder="C°...">
                                </div>
                                <label for="approve_at" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-fw fa-clock-o" aria-hidden="true"></i>

                                </label>
                                <div class="col-sm-3">
                                    <input type="text" required class="form-control" name="approve_at" id="approve_at" value="<?php echo $data['approve_at'] ?>" placeholder="Minute ...">
                                </div>
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
                                <label for="Done_Matching" class="col-sm-2 control-label">Tgl Done Matching</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control date-picker" required name="Done_Matching" id="Done_Matching" placeholder="Tgl Selesai Matching" value="<?php echo $data['done_matching'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea required class="form-control" name="keterangan" id="keterangan" rows="3"><?php echo $data['ket'] ?></textarea>
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
                                    <label for="Done_Matching" class="col-sm-2 control-label">Create Resep</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_UserResep" required name="create_resep" id="create_resep">
                                            <option value="<?php echo $data['create_resep'] ?>" selected><?php echo $data['create_resep'] ?></option>
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Tes Ulang OK</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="acc_ulang_ok" id="acc_ulang_ok">
                                            <option value="<?php echo $data['acc_ulang_ok'] ?>" selected><?php echo $data['acc_ulang_ok'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Resep Pertama1</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="acc_resep1" id="acc_resep1">
                                            <option value="<?php echo $data['acc_resep1'] ?>" selected><?php echo $data['acc_resep1'] ?></option>
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Resep Pertama2</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="acc_resep2" id="acc_resep2">
                                            <option value="<?php echo $data['acc_resep2'] ?>" selected><?php echo $data['acc_resep2'] ?></option>
                                        </select>
                                    </div>
                                </div>
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
                                        <select disabled class="form-control select_Koreksi" required name="koreksi3" id="koreksi3">
                                            <option value="<?php echo $data['koreksi_resep3'] ?>" selected><?php echo $data['koreksi_resep3'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="koreksi4" id="koreksi4">
                                            <option value="<?php echo $data['koreksi_resep4'] ?>" selected><?php echo $data['koreksi_resep4'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 3</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="koreksi5" id="koreksi5">
                                            <option value="<?php echo $data['koreksi_resep5'] ?>" selected><?php echo $data['koreksi_resep5'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="koreksi6" id="koreksi6">
                                            <option value="<?php echo $data['koreksi_resep6'] ?>" selected><?php echo $data['koreksi_resep6'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 4</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="koreksi7" id="koreksi7">
                                            <option value="<?php echo $data['koreksi_resep7'] ?>" selected><?php echo $data['koreksi_resep7'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="koreksi8" id="koreksi8">
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
                                        <select disabled class="form-control select_Koreksi" required name="colorist_3" id="colorist_3">
                                            <option value="<?php echo $data['colorist3'] ?>" selected><?php echo $data['colorist3'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="colorist_4" id="colorist_4">
                                            <option value="<?php echo $data['colorist4'] ?>" selected><?php echo $data['colorist4'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 3</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="colorist_5" id="colorist_5">
                                            <option value="<?php echo $data['colorist5'] ?>" selected><?php echo $data['colorist5'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="colorist_6" id="colorist_6">
                                            <option value="<?php echo $data['colorist6'] ?>" selected><?php echo $data['colorist6'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 4</label>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="colorist_7" id="colorist_7">
                                            <option value="<?php echo $data['colorist7'] ?>" selected><?php echo $data['colorist7'] ?></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select disabled class="form-control select_Koreksi" required name="colorist_8" id="colorist_8">
                                            <option value="<?php echo $data['colorist8'] ?>" selected><?php echo $data['colorist8'] ?></option>
                                        </select>
                                    </div>
                                </div> <?php } else { ?>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Create Resep</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_UserResep" required name="create_resep" id="create_resep">
                                            <option value="<?php echo $data['create_resep'] ?>" selected><?php echo $data['create_resep'] ?></option>
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Tes Ulang OK</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="acc_ulang_ok" id="acc_ulang_ok">
                                            <option value="<?php echo $data['acc_ulang_ok'] ?>" selected><?php echo $data['acc_ulang_ok'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Resep Pertama1</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="acc_resep1" id="acc_resep1">
                                            <option value="<?php echo $data['acc_resep1'] ?>" selected><?php echo $data['acc_resep1'] ?></option>
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Resep Pertama2</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="acc_resep2" id="acc_resep2">
                                            <option value="<?php echo $data['acc_resep2'] ?>" selected><?php echo $data['acc_resep2'] ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 1</label>
                                    <div class="col-sm-3">
                                        <select disabled class="form-control select_Koreksi" required name="koreksi" id="koreksi">
                                            <option value="<?php echo $data['koreksi_resep'] ?>" selected><?php echo $data['koreksi_resep'] ?></option>
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 2</label>
                                    <div class="col-sm-3">
                                        <select disabled class="form-control select_Koreksi" required name="koreksi2" id="koreksi2">
                                            <option value="<?php echo $data['koreksi_resep2'] ?>" selected><?php echo $data['koreksi_resep2'] ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 1</label>
                                    <div class="col-sm-3">
                                        <select disabled class="form-control select_Koreksi" required name="colorist_1" id="colorist_1">
                                            <option value="<?php echo $data['colorist1'] ?>" selected><?php echo $data['colorist1'] ?></option>
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 2</label>
                                    <div class="col-sm-3">
                                        <select disabled class="form-control select_Koreksi" required name="colorist_2" id="colorist_2">
                                            <option value="<?php echo $data['colorist2'] ?>" selected><?php echo $data['colorist2'] ?></option>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>


                        </div>
                    </div>
                </div>
            </div>
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
                            $hold_resep = mysqli_query($con, "SELECT * from tbl_matching_detail where `id_matching` = '$data[id]' and `id_status` = '$data[id_status]' order by flag");
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
                            <label for="L_R" class="col-sm-1 control-label">T-SIDE L:R :</label>
                            <div class="col-sm-2">
                                <select type="text" width="100%" class="form-control" required name="L_R" id="L_R" placeholder="L_R">
                                    <option selected value="<?php echo $data['lr'] ?>"><?php echo $data['lr'] ?></option>
                                </select>
                                <span></span>
                            </div>
                            <label for="L_R" class="col-sm-1 control-label">C-SIDE L:R :</label>
                            <div class="col-sm-2">
                                <select type="text" width="100%" class="form-control" required name="second_lr" id="second_lr" placeholder="L_R">
                                    <option selected value="<?php echo $data['second_lr'] ?>"><?php echo $data['second_lr'] ?></option>
                                </select>
                                <span></span>
                            </div>
                            <div class="form-group">
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
                                            <input type="text" class="form-control" id="bleaching_sh" value="<?php if (floatval($data['bleaching_sh']) != 0) echo floatval($data['bleaching_sh']) ?>" required name="bleaching_sh" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="bleaching_tm" value="<?php if (floatval($data['bleaching_tm']) != 0) echo floatval($data['bleaching_tm']) ?>" required name="bleaching_tm" placeholder="Waktu/Menit">
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
                                            <input type="text" class="form-control" id="bleaching_sh" value="<?php if (floatval($data['bleaching_sh']) != 0) echo floatval($data['bleaching_sh']) ?>" required name="bleaching_sh" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="bleaching_tm" value="<?php if (floatval($data['bleaching_tm']) != 0) echo floatval($data['bleaching_tm']) ?>" required name="bleaching_tm" placeholder="Waktu/Menit">
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
                                            <input type="text" class="form-control" id="bleaching_sh" value="<?php if (floatval($data['bleaching_sh']) != 0) echo floatval($data['bleaching_sh']) ?>" required name="bleaching_sh" placeholder="Suhu">
                                            <div class="input-group-addon">°C</div>
                                        </div>
                                        <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                        <div class="input-group col-md-5">
                                            <input type="text" class="form-control" id="bleaching_tm" value="<?php if (floatval($data['bleaching_tm']) != 0) echo floatval($data['bleaching_tm']) ?>" required name="bleaching_tm" placeholder="Waktu/Menit">
                                            <div class="input-group-addon">Menit</div>
                                        </div>
                                    </div>
                                    <!-- //RC -->
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
    <!-- <button class="btn btn-success" id="test">test</button> -->
</body>
<script>
    $(document).ready(function() {
        $('input').prop("disabled", true);
        $('select').prop("disabled", true);
        $('textarea').prop("disabled", true);
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
        // $("#tab_resep").click(function() {
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

        // hr
        $('#tfoot').hide()
        // });
    });
</script>

<script>
    $(document).ready(function() {
        $(".btn.btn-sm.btn-success.approve").click(function() {
            var idm = $(this).attr('idm');
            var id_status = $(this).attr('id_status');
            var no_order = $('#no_order').val();
            var id_matching = $('#id_matching').val();
            var benang = $('#Benang').val();
            Swal.fire({
                title: 'Apakah anda yakin untuk approve ' + idm + ' ?',
                showCancelButton: true,
                confirmButtonText: `Save`,
                denyButtonText: `Don't save`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Approve_resep.php",
                        data: {
                            id_status: id_status,
                        },
                        success: function(response) {
                            insertNomor_order(id_matching, id_status, idm, no_order, 'ORDER-ASAL', benang)
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                    // Swal.fire('Saved!', '', 'success')
                }
            })
        })

        function insertNomor_order(id_matching, id_status, Rcode, no_order, lot, benang) {
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
                    addt_benang: benang
                },
                success: function(response) {
                    if (response.session == "LIB_SUCCSS") {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Matching ' + Rcode + ' Sekarang telah approve !',
                            showConfirmButton: false,
                            // timer: 1500,
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 1505);
                    } else {
                        toastr.error("ajax error !")
                    }
                },
                error: function() {
                    alert("Error hubungi DIT");
                }
            });
        }
    })
</script>

<!-- ALL ABOUT HOLD HERE ! -->
<!-- <script>
    $(document).ready(function() {
        $('#hold').click(function() {
            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: "Untuk Hold Resep dengan R-code : <php echo $data['idm'] ?>!",
                icon: 'warning',
                allowOutsideClick: false,
                showCancelButton: true,
                confirmButtonColor: '#5cb85c',
                cancelButtonColor: '#292b2c',
                confirmButtonText: 'Yes, Hold <php echo $data['idm'] ?>'
            }).then((result) => {
                if (result.isConfirmed) {
                    Hold_action_after_check_table()
                }
            })
        })

        function Hold_action_after_check_table() {
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
            Update_StatusMatching_ToHold($("#id_matching").val(), $("#id_status").val(), $("#idm").val(), $('#Matching-ke').val(), $('#BENANG-A').val(), $("#LEBAR-A").val(), $("#GRAMASI-A").val(), $("#L_R").find('option:selected').val(), $("#kadar_air").val(), RC_Suhu, RCWaktu, soapingSuhu, soapingWaktu, $("#CIE_WI").val(), $("#CIE_TINT").val(), $("#Spektro_R").val(), $("#Done_Matching").val(), $("#keterangan").val(), $("#tgl_buat_status").val(), tside_c, tside_min, cside_c, cside_min)
        }

        function Update_StatusMatching_ToHold(id_matching, id_status, idm, matching_ke, benang_a, lebar_a, gramasi_a, l_R, kadar_air, RC_Suhu, RCWaktu, soapingSuhu, soapingWaktu, cie_wi, cie_tint, Spektro_R, Done_Matching, keterangan, tgl_buat_status, tside_c, tside_min, cside_c, cside_min) {
            SpinnerShow()
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/Update_StatusMatching_ToHold.php",
                data: {
                    id_matching: id_matching,
                    id_status: id_status,
                    idm: idm,
                    matching_ke: matching_ke,
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
                    Spektro_R: Spektro_R,
                    Done_Matching: Done_Matching,
                    keterangan: keterangan,
                    tgl_buat_status: tgl_buat_status,
                    tside_c: tside_c,
                    tside_min: tside_min,
                    cside_c: cside_c,
                    cside_min: cside_min
                },
                success: function(response) {
                    if (response.session == "LIB_SUCCSS_HOLD") {
                        console.log(response)
                        after_Hold_Insert_dataTableResep_toDB()
                    } else {
                        toastr.error("ajax error !")
                    }
                },
                error: function() {
                    alert("Error");
                }
            });
        }

        function after_Hold_Insert_dataTableResep_toDB() {
            var count = $("#lookupmodal1 tbody tr").length;
            var id_matching = $("#id_matching").val();
            var id_status = $("#id_status").val();
            if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 1) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax here
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 2) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(2) input').val();
                    var conc = $(this).find('td:eq(3) input').val();
                    var conc1 = $(this).find('td:eq(4) input').val();
                    var keterangan = $(this).find('td:last input').val();
                    // ajax here
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 3) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 4) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 5) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 6) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 7) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 8) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 9) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 10) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            }
            // window.location.href = 'index1.php?p=Status-Matching';
        }
    });
</script> -->

<!-- Jquery validation, alert if leave here ! -->
<!-- <script>
    $(document).ready(function() {
        // $(window).bind("beforeunload", function(event) {
        //     return confirm('You have some unsaved changes');
        // });
        // $("#lookupmodal1").DataTable({
        //     ordering: false,
        //     searching: false,
        //     "lengthChange": false,
        //     "paging": false,
        //     "bInfo": false,
        // responsive: true
        // "scrollX": true
        // })

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
                },
                keterangan: {
                    required: true,
                },
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
                toastr.error('Tab <b>Data Status</b> belum lengkap !');
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
            insertInto_StatusMatching_DetailMatching($("#id_matching").val(), $("#id_status").val(), $("#idm").val(), $('#Matching-ke').val(), $('#BENANG-A').val(), $("#LEBAR-A").val(), $("#GRAMASI-A").val(), $("#L_R").find('option:selected').val(), $("#kadar_air").val(), RC_Suhu, RCWaktu, soapingSuhu, soapingWaktu, $("#CIE_WI").val(), $("#CIE_TINT").val(), $("#Spektro_R").val(), $("#Done_Matching").val(), $("#keterangan").val(), $("#tgl_buat_status").val(), cside_c, cside_min, tside_c, tside_min)
        }

        function insertInto_StatusMatching_DetailMatching(id_matching, id_status, idm, matching_ke, benang_a, lebar_a, gramasi_a, l_R, kadar_air, RC_Suhu, RCWaktu, soapingSuhu, soapingWaktu, cie_wi, cie_tint, Spektro_R, Done_Matching, keterangan, tgl_buat_status, cside_c, cside_min, tside_c, tside_min) {
            SpinnerShow()
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/insertInto_StatusMatching_DetailMatching.php",
                data: {
                    id_matching: id_matching,
                    id_status: id_status,
                    idm: idm,
                    matching_ke: matching_ke,
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
                    Spektro_R: Spektro_R,
                    Done_Matching: Done_Matching,
                    keterangan: keterangan,
                    tgl_buat_status: tgl_buat_status,
                    cside_c: cside_c,
                    cside_min: cside_min,
                    tside_c: tside_c,
                    tside_min: tside_min
                },
                success: function(response) {
                    if (response.session == "LIB_SUCCSS") {
                        console.log(response)
                        Insert_dataTableResep_toDB();
                    } else {
                        toastr.error("ajax error !")
                    }
                },
                error: function() {
                    alert("Error");
                }
            });
        }

        function Insert_dataTableResep_toDB() {
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
                        url: "pages/ajax/Insert_dataTableResep_toDB.php",
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
</script> -->

<!-- on focus just can input integer -->
<!-- <script>
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
    $(document).on('focus', '#kadar_air', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
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
    $(document).on('focus', '#CIE_WI', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('focus', '#CIE_TINT', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
    $(document).on('focus', '#Spektro_R', function() {
        $(this).keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    })
</script> -->

<!-- Ajax Select2  -->
<!-- <script>
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
                success: function(response) {
                    $(getTr).find("td:eq(2)").find('input').val(response);
                },
                error: function() {
                    alert("Hubungi Departement DIT !");
                }
            });
        });
    })
</script> -->

<!-- ADD & DELETE ROW COLUMN FUNCTIONALITY TABLE  -->
<!-- <script>
    $(document).ready(function() {
        $('#plus_c1').click(function() {
            var attribute = $("#th-lookup1 tr th:last").prev();
            var attri = attribute.attr('flag_th')
            var goesto = $('#tb-lookup1 tr td:last').prev();
            var goes = goesto.attr('flag_td');
            if (attri == undefined) {
                var flag = 1;
            } else if (attri == '10') {
                toastr.error('Concentrate maximal in 10 column !');
            } else {
                var flag = parseInt(attri) + 1;
                var flag_td = parseInt(attri) + 1;
                $("#th-lookup1 th:last").before('<th width="60px" class="th_conc" flag_th="' + flag + '">Adjust-' + parseInt(flag - 1) + '</th>');
                $("#tb-lookup1 tr").each(function() {
                    $(this).find('td:last').before('<td flag_td="' + flag_td + '"><input style="width: 100%" type="text" class="form-control input-xs conc"></td>');
                })
            }
        })

        $('#minus_c1').click(function() {
            var attribute = $("#th-lookup1 tr th:last").prev();
            var flag_th = attribute.attr('flag_th')
            var goesto = $('#tb-lookup1 tr td:last').prev();
            var flag_td = goesto.attr('flag_td');
            if (flag_th == '1') {
                toastr.error('You cannot delete entire Concentrate column !')
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
            } else if (getno == '15') {
                toastr.error('Maximal column is 15 row !')
            } else {
                var nomor = parseInt(getno) + 1;
                $("#tb-lookup1").append(
                    '<tr>' + $('#tb-lookup1 tr:last').html() + '</tr>'
                );
                $('#tb-lookup1 tr:last td:first').html(nomor)
                $('#tb-lookup1 tr:last td:eq(1)').html('<select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here .."></select>')
                $('#tb-lookup1 tr:last td:eq(2) input').val("");
                $('#tb-lookup1 tr:last td:last input').val("");
                $('#tb-lookup1 tr:last td input.form-control.input-xs.conc').val("");

            }

        })

        $('#minus1').click(function() {
            if ($('#tb-lookup1').find('tr').length == 1) {
                toastr.error('You cannot delete entire table !')
            } else {
                $('#tb-lookup1 tr:last').remove();
            }
        })
    });
</script> -->