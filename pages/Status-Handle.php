<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
$date = date('Y-m-d');
$sql = mysqli_query($con, "SELECT a.id as id_status, a.idm, a.flag, a.grp, a.matcher, a.cek_warna, a.cek_dye, a.status, a.kt_status, a.koreksi_resep, a.koreksi_resep2,
                                a.percobaan_ke, a.benang_aktual, a.lebar_aktual, a.gramasi_aktual, a.soaping_sh, a.soaping_tm, a.rc_sh, a.rc_tm, a.lr, a.cie_wi, a.cie_tint, a.yellowness,
                                a.spektro_r, a.ket, a.created_at as tgl_buat_status, a.created_by as status_created_by, a.edited_at, a.edited_by, a.target_selesai, 
                                a.mulai_by, a.mulai_at, a.selesai_by, a.selesai_at, a.approve_by, a.approve_at, a.approve, b.id, b.no_resep, b.no_order, b.no_po, b.langganan, b.no_item,
                                b.jenis_kain, b.benang, b.cocok_warna, b.warna, b.no_warna, b.lebar, b.gramasi, b.qty_order, b.tgl_in, b.tgl_out, b.proses, b.buyer,
                                b.tgl_delivery, b.note, b.jenis_matching, b.tgl_buat, b.tgl_update, b.created_by, a.bleaching_sh, a.bleaching_tm,b.color_code,b.recipe_code,
                                b.suhu_chamber, b.warna_flourescent
                                FROM tbl_status_matching a
                                INNER JOIN tbl_matching b ON a.idm = b.no_resep
                                where a.id = '$_GET[idm]'
                                ORDER BY a.id desc limit 1");
$data = mysqli_fetch_array($sql);
// Mulai sesi
session_start();

// Mendapatkan nilai $ldorno dari $data["jenis_matching"]
$ldorno = $data["jenis_matching"];

// Simpan nilai $ldorno dalam sesi
$_SESSION['jenis_matching'] = $ldorno;

// echo $data['recipe_code'];
?>
<style>
    #Table-sm td,
    #Table-sm th {
        border: 0.1px solid #ddd;
        vertical-align: middle;
        text-align: center;
    }

    #Table-sm th {
        color: black;
        background: #4CAF50;
    }

    #Table-sm tr:hover {
        background-color: rgb(151, 170, 212);
    }

    #Table-sm>thead>tr>td {
        border: 1px solid #ddd;
    }

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
        <button class="btn btn-xs pull-right" style="background-color: grey; color: white; margin-bottom: 10px;"><?php echo $data['idm']; ?> </button>
    </div>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li id="tab1" class="active"><a data-toggle="tab" href="#input-status"><b>Basic Info</b></a></li>
            <li id="tab2"><a data-toggle="tab" href="#step1"><b>RESEP</b></a></li>
            <li class="pull-right">
                <button type="button" id="hold" class="btn btn-success btn-sm text-black" style="font-weight: bold;">HOLD/PAUSE RESEP ! <i class="fa fa-pause"></i></button>
                <button type="button" id="exsecute" class="btn btn-danger btn-sm text-black"><strong>SAVE RESEP AND SUBMIT FOR APPROVAL ! <i class="fa fa-save"></i></strong></button>
            </li>
        </ul>
    </div>
    <form action="#" class="form-horizontal" id="form-status">
        <div class="tab-content">
            <div id="input-status" class="tab-pane fade active in">
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
                                <input type="text" value="<?php echo $data['no_item'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development') {
                                                                                                echo '';
                                                                                            } else {
                                                                                                echo 'readonly ';
                                                                                            } ?> class="form-control input-sm" name="item" id="item" placeholder="item">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="recipe_code" class="col-sm-3 control-label">Recipe Code</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?php echo $data['recipe_code'] ?>" class="form-control input-sm" name="recipe_code" id="recipe_code" placeholder="Recipe Code" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="color_code" class="col-sm-3 control-label">Color Code</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?php echo $data['color_code'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development' or $data['jenis_matching'] == 'Matching Ulang') {
                                                                                                    echo '';
                                                                                                } else {
                                                                                                    echo 'readonly ';
                                                                                                } ?> class="form-control input-sm" name="color_code" id="color_code" placeholder="Color Code" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="no_warna" class="col-sm-3 control-label">No.warna</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?php echo $data['no_warna'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development' or $data['jenis_matching'] == 'Matching Ulang' or $data['jenis_matching'] == 'Matching Ulang NOW') {
                                                                                                echo '';
                                                                                            } else {
                                                                                                echo 'readonly ';
                                                                                            } ?> class="form-control input-sm" name="no_warna" id="no_warna" placeholder="no_warna">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="warna" class="col-sm-3 control-label">Warna</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?php echo $data['warna'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development' or $data['jenis_matching'] == 'Matching Ulang' or $data['jenis_matching'] == 'Matching Ulang NOW') {
                                                                                            echo '';
                                                                                        } else {
                                                                                            echo 'readonly ';
                                                                                        } ?> class="form-control input-sm" name="warna" id="warna" placeholder="warna">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Kain" class="col-sm-3 control-label">Kain</label>
                            <div class="col-sm-9">
                                <textarea class="form-control input-sm" name="Kain" id="Kain" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development') {
                                                                                                    echo '';
                                                                                                } else {
                                                                                                    echo 'readonly ';
                                                                                                } ?> rows="2"><?php echo $data['jenis_kain'] ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Benang" class="col-sm-3 control-label">Benang</label>
                            <div class="col-sm-9">
                                <textarea class="form-control input-sm" name="Benang" id="Benang" rows="3" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development' or $data['jenis_matching'] == 'Matching Ulang' or $data['jenis_matching'] == 'Matching Ulang NOW') {
                                                                                                                echo '';
                                                                                                            } else {
                                                                                                                echo 'readonly ';
                                                                                                            } ?>><?php echo $data['benang'] ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Gramasi" class="col-sm-3 control-label">Gramasi</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm" name="Lebar" id="Lebar" placeholder="Inch" value="<?php echo $data['lebar'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development') {
                                                                                                                                                                        echo '';
                                                                                                                                                                    } else {
                                                                                                                                                                        echo 'readonly ';
                                                                                                                                                                    } ?>>
                                <div class="input-group-addon"><small>Inch</small></div>
                            </div>
                            <div class="col-sm-1">
                                <button type="button" class="btn btn-dark"> <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm" name="Gramasi" id="Gramasi" placeholder="Gr/M2" value="<?php echo $data['gramasi'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development') {
                                                                                                                                                                            echo '';
                                                                                                                                                                        } else {
                                                                                                                                                                            echo 'readonly ';
                                                                                                                                                                        } ?>>
                                <div class="input-group-addon"><small>Gr/M²</small></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Delivery" class="col-sm-3 control-label">Tgl Delivery</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control date-picker input-sm" name="Tgl_delivery" id="Tgl_delivery" placeholder="Tgl delivery" value="<?php echo $data['tgl_delivery'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development') {
                                                                                                                                                                                                                echo '';
                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                echo 'disabled ';
                                                                                                                                                                                                            } ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Order" class="col-sm-3 control-label"> <?php if ($data['jenis_matching'] != 'L/D') {
                                                                                    echo 'No. Order';
                                                                                } else {
                                                                                    echo 'Request No';
                                                                                }   ?></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" name="Order" id="Order" placeholder="Order" value="<?php echo $data['no_order'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development') {
                                                                                                                                                                            echo '';
                                                                                                                                                                        } else {
                                                                                                                                                                            echo 'readonly ';
                                                                                                                                                                        } ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Order" class="col-sm-3 control-label">PO.Greige</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" name="Order" id="Order" placeholder="Order" value="<?php echo $data['no_po'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development') {
                                                                                                                                                                        echo '';
                                                                                                                                                                    } else {
                                                                                                                                                                        echo 'readonly ';
                                                                                                                                                                    } ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="QtyOrder" class="col-sm-3 control-label">Qty Order</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" name="QtyOrder" id="QtyOrder" placeholder="Qty Order" value="<?php echo $data['qty_order'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development') {
                                                                                                                                                                                    echo '';
                                                                                                                                                                                } else {
                                                                                                                                                                                    echo 'readonly ';
                                                                                                                                                                                } ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Matcher" class="col-sm-3 control-label">Matcher Awal</label>
                            <div class="col-sm-9">
                                <select type="text" class="form-control input-sm select_Fmatcher" name="Matcher" id="Matcher" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development') {
                                                                                                                                    echo '';
                                                                                                                                } else {
                                                                                                                                    echo 'disabled ';
                                                                                                                                } ?>>
                                    <option selected value="<?php echo $data['matcher'] ?>"><?php echo $data['matcher'] ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Group" class="col-sm-3 control-label">Group</label>
                            <div class="col-sm-9">
                                <select type="text" class="form-control input-sm" name="Group" id="Group" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'LD NOW' or $data['jenis_matching'] == 'Matching Development') {
                                                                                                                echo '';
                                                                                                            } else {
                                                                                                                echo 'disabled ';
                                                                                                            } ?>>
                                    <option selected value="<?php echo $data['grp'] ?>"><?php echo $data['grp'] ?></option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lampu" class="col-sm-3 control-label">Buyer</label>
                            <div class="col-sm-6">
                                <select name="Buyer" id="Buyer" class="form-control selectBuyer1" style="width: 100%;">
                                    <option value="<?php echo $data['buyer'] ?>" selected><?php echo $data['buyer'] ?></option>
                                    <!-- i do some magic here  -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lampu" class="col-sm-3 control-label">Lampu Buyer :</label>
                            <div class="col-sm-9" id="lampu-buyer1">
                                <!-- i do some magic here  -->
                                <?php $sqlLamp = mysqli_query($con, "SELECT * FROM vpot_lampbuy where buyer = '$data[buyer]'"); ?>
                                <?php while ($lamp = mysqli_fetch_array($sqlLamp)) { ?>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control input-sm" value="<?php echo $lamp['lampu'] ?>" readonly>
                                    </div>
                                <?php } ?>
                            </div>
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
                                <label for="Matching-ke" class="col-sm-2 control-label">Percobaan-ke</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" required id="Matching-ke" name="Matching-ke" maxlength="2" placeholder="Matching Ke">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Matching-ke" class="col-sm-2 control-label">Percobaan berapa kali</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" required id="howmany_Matching-ke" name="howmany_Matching-ke" maxlength="2" placeholder="Percobaan Berapa Kali">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="CocokWarna" class="col-sm-2 control-label">Cocok Warna</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="CocokWarna" id="CocokWarna" placeholder="Cocok Warna" value="<?php echo $data['cocok_warna'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Proses" class="col-sm-2 control-label">Proses</label>
                                <div class="col-sm-6">
                                    <select type="text" class="form-control input-sm selectProses1" name="Proses" id="Proses" placeholder="Proses">
                                        <option selected value="<?php echo $data['proses'] ?>"><?php echo $data['proses'] ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="BENANG-A" class="col-sm-2 control-label">BENANG-A</label>
                                <div class="col-sm-9">
                                    <textarea name="BENANG-A" id="BENANG-A" rows="2" class="form-control" placeholder="Benang Aktual.." required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="LEBAR-A" class="col-sm-2 control-label" style="margin-right: 15px;">LEBAR-A</label>
                                <div class="input-group col-sm-5">
                                    <input type="text" class="form-control" required id="LEBAR-A" name="LEBAR-A" placeholder="Lebar Aktual..">
                                    <div class="input-group-addon"><small>Inches</small></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="GRAMASI-A" class="col-sm-2 control-label" style="margin-right: 15px;">GRAMASI-A</label>
                                <div class="input-group col-sm-5">
                                    <input type="text" class="form-control" required id="GRAMASI-A" name="GRAMASI-A" placeholder="Gramasi Aktual...">
                                    <div class="input-group-addon"><small>Gr/M²</small></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="kadar_air_true" class="col-sm-2 control-label" style="margin-right: 15px;">Kadar Air</label>
                                <div class="input-group col-sm-5">
                                    <input type="text" class="form-control" id="kadar_air_true" name="kadar_air_true" placeholder="Kadar air...">
                                    <div class="input-group-addon">%</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="CIE_WI" class="col-sm-2 control-label">CIE WI</label>
                                <div class="col-sm-9">
                                    <input type="text" required class="form-control" name="CIE_WI" id="CIE_WI" placeholder="CIE WI">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="CIE_TINT" class="col-sm-2 control-label">CIE TINT</label>
                                <div class="col-sm-9">
                                    <input type="text" required class="form-control" name="CIE_TINT" id="CIE_TINT" placeholder="CIE TINT">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="YELLOWNESS" class="col-sm-2 control-label">YELLOWNESS</label>
                                <div class="col-sm-9">
                                    <input type="text" required class="form-control" name="YELLOWNESS" id="YELLOWNESS" placeholder="YELLOWNESS">
                                </div>
                            </div>
                            <!-- <div class="form-group"> -->
                            <!-- <label for="Spektro R" required class="col-sm-2 control-label">Spektro R</label> -->
                            <!-- <div class="col-sm-9"> -->
                            <input type="hidden" value="-" class="form-control" name="Spektro_R" id="Spektro_R" placeholder="Spektro Reading">
                            <!-- </div> -->
                            <!-- </div> -->
                            <div class="form-group">
                                <label for="Done_Matching" class="col-sm-2 control-label">Tgl Done Matching</label>
                                <div class="col-sm-9">
                                    <input type="text" disabled value="<?php echo $date; ?>" class="form-control date-picker" required name="Done_Matching" id="Done_Matching" placeholder="Tgl Selesai Matching">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Done_Matching" class="col-sm-2 control-label">Final Matcher</label>
                                <div class="col-sm-6">
                                    <select class="form-control select_Fmatcher" required name="f_matcher" id="f_matcher">
                                    </select>
                                </div>
                            </div>

                            <?php if ($_SESSION['jenis_matching'] == "LD NOW" || $_SESSION['jenis_matching'] == "L/D") { ?>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Create Resep</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_UserResep" required name="create_resep" id="create_resep">
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Tes Ulang OK</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" name="acc_ulang_ok" id="acc_ulang_ok">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Resep Pertama1</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="acc_resep1" id="acc_resep1">
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Resep Pertama2</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="acc_resep2" id="acc_resep2">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 1</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="koreksi_1" id="koreksi_1">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="koreksi_2" id="koreksi_2">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 2</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="koreksi_3" id="koreksi_3">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="koreksi_4" id="koreksi_4">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 3</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="koreksi_5" id="koreksi_5">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="koreksi_6" id="koreksi_6">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 4</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="koreksi_7" id="koreksi_7">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="koreksi_8" id="koreksi_8">
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 1</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="colorist_1" id="colorist_1">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="colorist_2" id="colorist_2">
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 2</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="colorist_3" id="colorist_3">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="colorist_4" id="colorist_4">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 3</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="colorist_5" id="colorist_5">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="colorist_6" id="colorist_6">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist 4</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="colorist_7" id="colorist_7">
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control select_Koreksi" name="colorist_8" id="colorist_8">
                                        </select>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Create Resep</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_UserResep" required name="create_resep" id="create_resep">
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Tes Ulang OK</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" name="acc_ulang_ok" id="acc_ulang_ok">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Resep Pertama1</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="acc_resep1" id="acc_resep1">
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Acc Resep Pertama2</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" required name="acc_resep2" id="acc_resep2">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 1</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" name="koreksi_1" id="koreksi_1">
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep 2</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" name="koreksi_2" id="koreksi_2">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist1</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" name="colorist_1" id="colorist_1">
                                        </select>
                                    </div>
                                    <label for="Done_Matching" class="col-sm-2 control-label">Colorist2</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select_Koreksi" name="colorist_2" id="colorist_2">
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                            <!-- <div class="form-group">
                                <label for="Done_Matching" class="col-sm-2 control-label">Penanggung Jawab</label>
                                <div class="col-sm-3">
                                    <select class="form-control select_pjawab" required name="penanggung_jawab" id="penanggung_jawab">
                                        <option value=""></option>
                                        <option value="Joni">Joni</option>
                                        <option value="Yana">Yana</option>
                                        <option value="Ganang">Ganang</option>
                                        <option value="Tidak Matching">Tidak Matching</option>
                                    </select>
                                </div>
                            </div> -->
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
                        <div class="col-lg-6" style="margin-bottom: 4px;">
                            <a id="import" href="#" data-toggle="modal" data-target="#DataUser" class="btn btn-success btn-xs"><i class="fa fa-cloud-download" aria-hidden="true"></i> Import Co-Power</a>
                        </div>
                        <div class="col-lg-6 align-right text-right" style="margin-bottom: 4px;">
                            <button type="button" id="plus_c1" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Conc</button>
                            <button type="button" id="minus_c1" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i> Conc</button>||
                            <button type="button" id="plus1" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Baris</button>
                            <button type="button" id="minus1" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i> Baris</button>
                        </div>
                    </div>
                    <div class="col-lg-12 overflow-auto table-responsive well" style="overflow-x: auto;">
                        <table id="lookupmodal1" class="lookupST display nowrap" width="110%" style="padding-right: 16px;">
                            <thead id="th-lookup1">
                                <tr>
                                    <th width="5px">#</th>
                                    <th width="60px" class="th_code">Code</th>
                                    <th width="60px" class="th_code_new">ERP Code</th>
                                    <th width="140px" class="th_name">Name</th>
                                    <th width="60px" class="th_conc" flag_th="1">LAB</th>
                                    <th width="140px" class="th_remark">Remark</th>
                                </tr>
                            </thead>
                            <tbody id="tb-lookup1">
                                <tr>
                                    <td align="center" class="nomor">1</td>
                                    <td>
                                        <select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here ..">
                                        </select>
                                    </td>
                                    <td><input style="width: 100%" readonly type="text" class="form-control input-xs new_code"></td>
                                    <td><input style="width: 100%" readonly type="text" class="form-control input-xs name"></td>
                                    <td flag_td="1"><input style="width: 100%" type="text" class="form-control input-xs conc"></td>
                                    <td><input style="width: 100%" type="text" class="form-control input-xs remark"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-11 well" style="margin-top: 10px;">
                        <div class="form-group">
                            <label for="L_R" class="col-sm-1 control-label">T-SIDE L:R :</label>
                            <div class="col-sm-2">
                                <select type="text" style="width: 100%;" class="form-control select2_lr" required name="L_R" id="L_R" placeholder="L_R">
                                    <option selected disabled value="">Pilih...</option>
                                    <option value="1:6">1:6</option>
                                    <option value="1:9">1:9</option>
                                    <option value="1:10">1:10</option>
                                    <option value="1:12">1:12</option>
                                    <option value="1:15">1:15</option>
                                </select>
                                <span></span>
                            </div>
                            <label for="L_R" class="col-sm-1 control-label">C-SIDE L:R :</label>
                            <div class="col-sm-2">
                                <select type="text" style="width: 100%;" class="form-control second_lr" required name="second_lr" id="second_lr" placeholder="second_lr">
                                    <option selected disabled value="0:0">Pilih...</option>
                                    <option value="1:6">1:6</option>
                                    <option value="1:9">1:9</option>
                                    <option value="1:10">1:10</option>
                                    <option value="1:12">1:12</option>
                                    <option value="1:15">1:15</option>
                                </select>
                                <span></span>
                            </div>
                            <!-- your work here -->
                        </div>
                        <div class="form-group">
                            <label for="kadar_air" class="col-sm-1 control-label">Ph :</label>
                            <div class="col-sm-3">
                                <input type="text" required class="form-control" name="kadar_air" id="kadar_air" placeholder="ph air ...">
                            </div>
                        </div>
                        <div class="col-md-12 well" style="margin-top: 20px;">
                            <?php if (substr($data['idm'], 0, 2) == 'D2' or substr($data['idm'], 0, 1) == 'C') { ?>
                                <div class="form-group">
                                    <label for="tside_c" class="col-sm-2 control-label">T-SIDE :</label>
                                    <div class="col-sm-2">
                                        <input type="text" width="100%" class="form-control" required name="tside_c" value="0" id="tside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" name="tside_min" value="0" id="tside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for=" RC" class="col-sm-2 control-label" align="left">RC</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="RC_Suhu" value="0" required name="RC_Suhu" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="RCWaktu" value="0" required name="RCWaktu" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for=" RC" class="col-sm-2 control-label" align="left">Bleaching</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="bleaching_sh" value="0" required name="bleaching_sh" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="bleaching_tm" value="0" required name="bleaching_tm" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                            <?php } else if (substr($data['idm'], 0, 1) == 'R' or substr($data['idm'], 0, 1) == 'A') { ?>
                                <div class="form-group">
                                    <label for="cside_c" class="col-sm-2 control-label">C-SIDE :</label>
                                    <div class="col-sm-2">
                                        <input type="text" width="100%" class="form-control" value="0" required name="cside_c" id="cside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" value="0" name="cside_min" id="cside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <!-- SOAPING -->
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">SOAPING / CUCI PANAS</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" required id="soapingSuhu" value="0" name="soapingSuhu" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" required id="soapingWaktu" value="0" name="soapingWaktu" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <!-- //SOAPING -->
                            <?php } elseif (substr($data['idm'], 0, 2) == 'DR') { ?>
                                <div class="form-group">
                                    <label for="tside_c" class="col-sm-2 control-label">T-SIDE :</label>
                                    <div class="col-sm-2">
                                        <input type="text" width="100%" class="form-control" value="0" required name="tside_c" id="tside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" value="0" name="tside_min" id="tside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cside_c" class="col-sm-2 control-label">C-SIDE :</label>
                                    <div class="col-sm-2">
                                        <input type="text" width="100%" class="form-control" value="0" required name="cside_c" id="cside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" name="cside_min" value="0" id="cside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <!-- SOAPING -->
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">SOAPING / CUCI PANAS</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" required id="soapingSuhu" value="0" name="soapingSuhu" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" required id="soapingWaktu" value="0" name="soapingWaktu" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <!-- //SOAPING -->
                                <!-- RC -->
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for=" RC" class="col-sm-2 control-label" align="left">RC</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" required id="RC_Suhu" value="0" name="RC_Suhu" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" required id="RCWaktu" value="0" name="RCWaktu" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for=" RC" class="col-sm-2 control-label" align="left">Bleaching</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="bleaching_sh" value="0" required name="bleaching_sh" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="bleaching_tm" value="0" required name="bleaching_tm" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <!-- //RC -->
                            <?php } else if (substr($data['idm'], 0, 2) == 'OB') { ?>
                                <!-- echoing nothing -->
                                <br />
                                <div class="form-group">
                                    <label for="tside_c" class="col-sm-2 control-label">T/C-side :</label>
                                    <div class="col-sm-2">
                                        <input type="text" width="100%" class="form-control" value="0" required name="tside_c" id="tside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" value="0" name="tside_min" id="tside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <p style="font-style: italic; font-weight: bold;">Field Rc and Soaping not avaliable at O+B matching !</p>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label for="tside_c" class="col-sm-2 control-label">T-SIDE :</label>
                                    <div class="col-sm-2">
                                        <input type="text" width="100%" class="form-control" value="0" required name="tside_c" id="tside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" value="0" name="tside_min" id="tside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cside_c" class="col-sm-2 control-label">C-SIDE :</label>
                                    <div class="col-sm-2">
                                        <input type="text" width="100%" class="form-control" value="0" required name="cside_c" id="cside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" value="0" name="cside_min" id="cside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <!-- SOAPING -->
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">SOAPING / CUCI PANAS</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="soapingSuhu" value="0" name="soapingSuhu" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="soapingWaktu" value="0" name="soapingWaktu" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <!-- //SOAPING -->
                                <!-- RC -->
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for=" RC" class="col-sm-2 control-label" align="left">RC</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="RC_Suhu" value="0" name="RC_Suhu" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="RCWaktu" value="0" name="RCWaktu" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for=" RC" class="col-sm-2 control-label" align="left">Bleaching</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="bleaching_sh" value="0" required name="bleaching_sh" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="bleaching_tm" value="0" required name="bleaching_tm" placeholder="Waktu/Menit">
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
    <style>
        .box {
            position: relative;
            background: #ffffff;
            width: 100%;
        }

        .box-header {
            color: #444;
            display: block;
            padding: 10px;
            position: relative;
            border-bottom: 1px solid #f4f4f4;
            margin-bottom: 10px;
        }

        .box-tools {
            position: absolute;
            right: 10px;
            top: 5px;
        }

        .dropzone-wrapper {
            border: 2px dashed #91b0b3;
            color: #92b0b3;
            position: relative;
            height: 300px;
        }

        .dropzone-desc {
            position: absolute;
            margin: 0 auto;
            left: 0;
            right: 0;
            text-align: center;
            width: 40%;
            top: 50px;
            font-size: 16px;
        }

        .dropzone,
        .dropzone:focus {
            position: absolute;
            outline: none !important;
            width: 100%;
            height: 300px;
            cursor: pointer;
            opacity: 0;
        }

        .dropzone-wrapper:hover,
        .dropzone-wrapper.dragover {
            background: #ecf0f5;
        }

        .preview-zone {
            text-align: center;
        }

        .preview-zone .box {
            box-shadow: none;
            border-radius: 0;
            margin-bottom: 0;
        }
    </style>
    <div class="modal fade modal-super-scaled" id="DataUser" data-backdrop="static" data-keyboard="true" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:75%">
            <div class="modal-content">
                <form action="index1.php?p=upload_copower" method="POST" enctype="multipart/form-data" class="form-horizontal">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Import Data From Co-Power <i class="fa fa-cloud-download" aria-hidden="true"></i></h4>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Upload File</label>
                                    <input type="hidden" name="id_matching" id="id_matching" value="<?php echo $data['id'] ?>" readonly="true">
                                    <input type="hidden" name="id_status" id="id_status" value="<?php echo $data['id_status'] ?>" readonly="true">
                                    <div class="dropzone-wrapper">
                                        <div class="dropzone-desc">
                                            <i class="glyphicon glyphicon-download-alt"></i>
                                            <p>Choose an .txt file or drag it here &amp; Make sure the format in lowercase (.txt)</p>
                                        </div>
                                        <input type="file" id="file" name="file" class="dropzone" required="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <button type="submit" name="submit" value="submit" class="btn btn-primary col-lg-3"><strong>Upload</strong></button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
                <script>
                    $(function() {
                        $('input[type=file]').change(function() {
                            var t = $(this).val();
                            var labelText = 'Choosed file : ' + t.substr(12, t.length);
                            $('.dropzone-desc p').text(labelText);
                        })
                    });
                </script>
            </div>
        </div>
    </div>
</body>

<!-- SPINNER LOADING FOR SHOW LOADER ON AJAX PROCESS // THIS VERY IMPORTANT to PREVENT DATA NOT SENDED ! -->
<script type="text/javascript">
    $(document).ready(function() {
        const myTable = $('#Table-sm').DataTable({
            "ordering": false,
            "pageLength": 20
        })
    });

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
            window.location.href = 'index1.php?p=Status-Matching';
        }, 4000);
    }
</script>

<!-- ALL ABOUT HOLD HERE ! -->
<script>
    $(document).ready(function() {
        $('#hold').click(function() {
            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: "Untuk Hold Resep dengan R-code : <?php echo $data['idm'] ?>!",
                icon: 'warning',
                allowOutsideClick: false,
                showCancelButton: true,
                confirmButtonColor: '#5cb85c',
                cancelButtonColor: '#292b2c',
                confirmButtonText: 'Yes, Hold <?php echo $data['idm'] ?>'
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
            Update_StatusMatching_ToHold($("#id_matching").val(), $("#id_status").val(), $("#idm").val(),
                $('#Matching-ke').val(), $('#howmany_Matching-ke').val(), $('#BENANG-A').val(), $("#LEBAR-A").val(),
                $("#GRAMASI-A").val(), $("#L_R").find('option:selected').val(), $("#kadar_air").val(), RC_Suhu,
                RCWaktu, soapingSuhu, soapingWaktu, $("#CIE_WI").val(), $("#CIE_TINT").val(), $("#YELLOWNESS").val(),
                $("#Spektro_R").val(), $("#Done_Matching").val(), $("#keterangan").val(), $("#tgl_buat_status").val(),
                tside_c, tside_min, cside_c, cside_min, $('#kadar_air_true').val(), $('#CocokWarna').val(),
                $("#f_matcher").find('option:selected').val(), $("#koreksi_1").find('option:selected').val(),
                $("#koreksi_2").find('option:selected').val(),
                $("#koreksi_3").find('option:selected').val(), $("#koreksi_4").find('option:selected').val(),
                $("#koreksi_5").find('option:selected').val(), $("#koreksi_6").find('option:selected').val(),
                $("#koreksi_7").find('option:selected').val(), $("#koreksi_8").find('option:selected').val(),
                "",
                $("#create_resep").find('option:selected').val(), $("#acc_ulang_ok").find('option:selected').val(),
                $("#acc_resep1").find('option:selected').val(), $("#acc_resep2").find('option:selected').val(),
                $("#colorist_1").find('option:selected').val(), $("#colorist_2").find('option:selected').val(),
                $("#colorist_3").find('option:selected').val(), $("#colorist_4").find('option:selected').val(),
                $("#colorist_5").find('option:selected').val(), $("#colorist_6").find('option:selected').val(),
                $("#colorist_7").find('option:selected').val(), $("#colorist_8").find('option:selected').val(),
                $("#Proses").find('option:selected').val(), $("#item").val(), $("#recipe_code").val(), $('#no_warna').val(),
                $('#warna').val(), $('#Kain').val(), $('#Benang').val(), $('#Lebar').val(), $('#Gramasi').val(),
                $('#Tgl_delivery ').val(), $('#Order').val(), $('#po_greige').val(), $('#QtyOrder').val(),
                $('#Matcher').find('option:selected').val(), $('#Group').find('option:selected').val(),
                $("#Buyer").find('option:selected').val(), bleaching_sh, bleaching_tm, $('#second_lr').find(':selected').val())
        }

        function Update_StatusMatching_ToHold(id_matching, id_status, idm, matching_ke,
            howmany_Matching_ke, benang_a, lebar_a, gramasi_a, l_R, kadar_air, RC_Suhu,
            RCWaktu, soapingSuhu, soapingWaktu, cie_wi, cie_tint, yellowness, Spektro_R,
            Done_Matching, keterangan, tgl_buat_status, tside_c, tside_min, cside_c, cside_min,
            kadar_air_true, cocok_warna, final_matcher, koreksi_resep, koreksi_resep2,
            koreksi_resep3, koreksi_resep4, koreksi_resep5, koreksi_resep6, koreksi_resep7, koreksi_resep8,
            penanggung_jawab, create_resep, acc_ulang_ok, acc_resep1, acc_resep2, colorist1, colorist2,
            colorist3, colorist4, colorist5, colorist6, colorist7, colorist8,
            proses, item, recipe_code, no_warna, warna, Kain, Benang, Lebar, Gramasi, Tgl_delivery,
            Order, po_greige, QtyOrder, Matcher, Group, Buyer, bleaching_sh, bleaching_tm, second_lr) {
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
                    tside_c: tside_c,
                    tside_min: tside_min,
                    cside_c: cside_c,
                    cside_min: cside_min,
                    kadar_air_true: kadar_air_true,
                    cocok_warna: cocok_warna,
                    final_matcher: final_matcher,
                    koreksi_resep: koreksi_resep,
                    koreksi_resep2: koreksi_resep2,
                    koreksi_resep3: koreksi_resep3,
                    koreksi_resep4: koreksi_resep4,
                    koreksi_resep5: koreksi_resep5,
                    koreksi_resep6: koreksi_resep6,
                    koreksi_resep7: koreksi_resep7,
                    koreksi_resep8: koreksi_resep8,
                    penanggung_jawab: penanggung_jawab,
                    create_resep: create_resep,
                    acc_ulang_ok: acc_ulang_ok,
                    acc_resep1: acc_resep1,
                    acc_resep2: acc_resep2,
                    colorist1: colorist1,
                    colorist2: colorist2,
                    colorist3: colorist3,
                    colorist4: colorist4,
                    colorist5: colorist5,
                    colorist6: colorist6,
                    colorist7: colorist7,
                    colorist8: colorist8,
                    proses: proses,
                    item: item,
                    recipe_code: recipe_code,
                    no_warna: no_warna,
                    warna: warna,
                    Kain: Kain,
                    Benang: Benang,
                    Lebar: Lebar,
                    Gramasi: Gramasi,
                    Tgl_delivery: Tgl_delivery,
                    Order: Order,
                    po_greige: po_greige,
                    QtyOrder: QtyOrder,
                    Matcher: Matcher,
                    Group: Group,
                    Buyer: Buyer,
                    bleaching_sh: bleaching_sh,
                    bleaching_tm: bleaching_tm,
                    second_lr: second_lr
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
</script>

<!-- Jquery validation, alert if leave here ! -->
<script>
    $(document).ready(function() {
        // $(window).bind("beforeunload", function(event) {
        //     return confirm('You have some unsaved changes');
        // });
        $("#lookupmodal1").DataTable({
            ordering: false,
            searching: false,
            "lengthChange": false,
            "paging": false,
            "bInfo": false,
            // responsive: true
            // "scrollX": true
        })

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
                        var conc = $(this).find('td:eq(4) input').val();
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
                        var conc = $(this).find('td:eq(4) input').val();
                        var conc1 = $(this).find('td:eq(5) input').val();
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
                        var conc = $(this).find('td:eq(4) input').val();
                        var conc1 = $(this).find('td:eq(5) input').val();
                        var conc2 = $(this).find('td:eq(6) input').val();
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
                        var conc = $(this).find('td:eq(4) input').val();
                        var conc1 = $(this).find('td:eq(5) input').val();
                        var conc2 = $(this).find('td:eq(6) input').val();
                        var conc3 = $(this).find('td:eq(7) input').val();
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
                        var conc = $(this).find('td:eq(4) input').val();
                        var conc1 = $(this).find('td:eq(5) input').val();
                        var conc2 = $(this).find('td:eq(6) input').val();
                        var conc3 = $(this).find('td:eq(7) input').val();
                        var conc4 = $(this).find('td:eq(8) input').val();
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
                        var conc = $(this).find('td:eq(4) input').val();
                        var conc1 = $(this).find('td:eq(5) input').val();
                        var conc2 = $(this).find('td:eq(6) input').val();
                        var conc3 = $(this).find('td:eq(7) input').val();
                        var conc4 = $(this).find('td:eq(8) input').val();
                        var conc5 = $(this).find('td:eq(9) input').val();
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
                        var conc = $(this).find('td:eq(4) input').val();
                        var conc1 = $(this).find('td:eq(5) input').val();
                        var conc2 = $(this).find('td:eq(6) input').val();
                        var conc3 = $(this).find('td:eq(7) input').val();
                        var conc4 = $(this).find('td:eq(8) input').val();
                        var conc5 = $(this).find('td:eq(9) input').val();
                        var conc6 = $(this).find('td:eq(10) input').val();
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
                        var conc = $(this).find('td:eq(4) input').val();
                        var conc1 = $(this).find('td:eq(5) input').val();
                        var conc2 = $(this).find('td:eq(6) input').val();
                        var conc3 = $(this).find('td:eq(7) input').val();
                        var conc4 = $(this).find('td:eq(8) input').val();
                        var conc5 = $(this).find('td:eq(9) input').val();
                        var conc6 = $(this).find('td:eq(10) input').val();
                        var conc7 = $(this).find('td:eq(11) input').val();
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
                        var conc = $(this).find('td:eq(4) input').val();
                        var conc1 = $(this).find('td:eq(5) input').val();
                        var conc2 = $(this).find('td:eq(6) input').val();
                        var conc3 = $(this).find('td:eq(7) input').val();
                        var conc4 = $(this).find('td:eq(8) input').val();
                        var conc5 = $(this).find('td:eq(9) input').val();
                        var conc6 = $(this).find('td:eq(10) input').val();
                        var conc7 = $(this).find('td:eq(11) input').val();
                        var conc8 = $(this).find('td:eq(12) input').val();
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
                        var conc = $(this).find('td:eq(4) input').val();
                        var conc1 = $(this).find('td:eq(5) input').val();
                        var conc2 = $(this).find('td:eq(6) input').val();
                        var conc3 = $(this).find('td:eq(7) input').val();
                        var conc4 = $(this).find('td:eq(8) input').val();
                        var conc5 = $(this).find('td:eq(9) input').val();
                        var conc6 = $(this).find('td:eq(10) input').val();
                        var conc7 = $(this).find('td:eq(11) input').val();
                        var conc8 = $(this).find('td:eq(12) input').val();
                        var conc9 = $(this).find('td:eq(13) input').val();
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
            insertInto_StatusMatching_DetailMatching($("#id_matching").val(), $("#id_status").val(), $("#idm").val(),
                $('#Matching-ke').val(), $('#BENANG-A').val(), $("#LEBAR-A").val(), $("#GRAMASI-A").val(),
                $("#L_R").find('option:selected').val(), $("#kadar_air").val(),
                RC_Suhu, RCWaktu, soapingSuhu, soapingWaktu,
                $("#CIE_WI").val(), $("#CIE_TINT").val(), $("#YELLOWNESS").val(),
                $("#Spektro_R").val(), $("#Done_Matching").val(), $("#keterangan").val(),
                $("#tgl_buat_status").val(), cside_c, cside_min, tside_c, tside_min,
                $('#kadar_air_true').val(), $('#CocokWarna').val(),
                $("#f_matcher").find('option:selected').val(), $("#koreksi_1").find('option:selected').val(),
                $("#koreksi_2").find('option:selected').val(),
                $("#koreksi_3").find('option:selected').val(),
                $("#koreksi_4").find('option:selected').val(),
                $("#koreksi_5").find('option:selected').val(),
                $("#koreksi_6").find('option:selected').val(),
                $("#koreksi_7").find('option:selected').val(),
                $("#koreksi_8").find('option:selected').val(),
                $("#create_resep").find('option:selected').val(), $("#acc_ulang_ok").find('option:selected').val(),
                $("#acc_resep1").find('option:selected').val(), $("#acc_resep2").find('option:selected').val(),
                $("#colorist_1").find('option:selected').val(), $("#colorist_2").find('option:selected').val(),
                $("#colorist_3").find('option:selected').val(),
                $("#colorist_4").find('option:selected').val(),
                $("#colorist_5").find('option:selected').val(),
                $("#colorist_6").find('option:selected').val(),
                $("#colorist_7").find('option:selected').val(),
                $("#colorist_8").find('option:selected').val(),
                $("#Proses").find('option:selected').val(), $("#item").val(), $("#recipe_code").val(),
                $('#no_warna').val(), $('#warna').val(), $('#Kain').val(), $('#Benang').val(), $('#Lebar').val(),
                $('#Gramasi').val(), $('#Tgl_delivery').val(), $('#Order').val(), $('#po_greige').val(),
                $('#QtyOrder').val(), $('#Matcher').find('option:selected').val(), $('#Group').find('option:selected').val(),
                $("#Buyer").find('option:selected').val(), bleaching_sh, bleaching_tm, $('#second_lr').find(':selected').val())
        }

        function insertInto_StatusMatching_DetailMatching(id_matching, id_status, idm,
            matching_ke, benang_a, lebar_a, gramasi_a, l_R, kadar_air, RC_Suhu, RCWaktu, soapingSuhu,
            soapingWaktu, cie_wi, cie_tint, yellowness, Spektro_R, Done_Matching, keterangan,
            tgl_buat_status, cside_c, cside_min, tside_c, tside_min, kadar_air_true, cocok_warna,
            final_matcher, koreksi_resep, koreksi_resep2, koreksi_resep3, koreksi_resep4, koreksi_resep5,
            koreksi_resep6, koreksi_resep7, koreksi_resep8, create_resep, acc_ulang_ok, acc_resep1, acc_resep2, colorist1, colorist2,
            colorist3, colorist4, colorist5, colorist6, colorist7, colorist8, proses, item, recipe_code, no_warna, warna, Kain,
            Benang, Lebar, Gramasi, Tgl_delivery, Order, po_greige, QtyOrder,
            Matcher, Group, Buyer, bleaching_sh, bleaching_tm, second_lr) {
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
                    cocok_warna: cocok_warna,
                    final_matcher: final_matcher,
                    koreksi_resep: koreksi_resep,
                    koreksi_resep2: koreksi_resep2,
                    koreksi_resep3: koreksi_resep3,
                    koreksi_resep4: koreksi_resep4,
                    koreksi_resep5: koreksi_resep5,
                    koreksi_resep6: koreksi_resep6,
                    koreksi_resep7: koreksi_resep7,
                    koreksi_resep8: koreksi_resep8,
                    create_resep: create_resep,
                    acc_ulang_ok: acc_ulang_ok,
                    acc_resep1: acc_resep1,
                    acc_resep2: acc_resep2,
                    colorist1: colorist1,
                    colorist2: colorist2,
                    colorist3: colorist3,
                    colorist4: colorist4,
                    colorist5: colorist5,
                    colorist6: colorist6,
                    colorist7: colorist7,
                    colorist8: colorist8,
                    proses: proses,
                    item: item,
                    recipe_code: recipe_code,
                    no_warna: no_warna,
                    warna: warna,
                    Kain: Kain,
                    Benang: Benang,
                    Lebar: Lebar,
                    Gramasi: Gramasi,
                    Tgl_delivery: Tgl_delivery,
                    Order: Order,
                    po_greige: po_greige,
                    QtyOrder: QtyOrder,
                    Matcher: Matcher,
                    Group: Group,
                    Buyer: Buyer,
                    bleaching_sh: bleaching_sh,
                    bleaching_tm: bleaching_tm,
                    second_lr: second_lr
                },
                success: function(response) {
                    if (response.session == "LIB_SUCCSS") {
                        console.log(response)
                        Insert_dataTableResep_toDB();
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
        //             message_text: '<php echo $_SESSION["userLAB"] ?> Telah mengajukan Resep dengan Rcode : ' + idm + ' dan menunggu untuk di Approve oleh Leader, Mohon untuk Check resep yang telah diajukan tersebut untuk menentukan langkah selanjutnya.',
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

        function Insert_dataTableResep_toDB() {
            if ($("#lookupmodal1 thead tr th:last").prev().attr('flag_th') == 1) {
                $('#lookupmodal1 tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
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
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
                    var conc1 = $(this).find('td:eq(5) input').val();
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
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
                    var conc1 = $(this).find('td:eq(5) input').val();
                    var conc2 = $(this).find('td:eq(6) input').val();
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
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
                    var conc1 = $(this).find('td:eq(5) input').val();
                    var conc2 = $(this).find('td:eq(6) input').val();
                    var conc3 = $(this).find('td:eq(7) input').val();
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
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
                    var conc1 = $(this).find('td:eq(5) input').val();
                    var conc2 = $(this).find('td:eq(6) input').val();
                    var conc3 = $(this).find('td:eq(7) input').val();
                    var conc4 = $(this).find('td:eq(8) input').val();
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
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
                    var conc1 = $(this).find('td:eq(5) input').val();
                    var conc2 = $(this).find('td:eq(6) input').val();
                    var conc3 = $(this).find('td:eq(7) input').val();
                    var conc4 = $(this).find('td:eq(8) input').val();
                    var conc5 = $(this).find('td:eq(9) input').val();
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
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
                    var conc1 = $(this).find('td:eq(5) input').val();
                    var conc2 = $(this).find('td:eq(6) input').val();
                    var conc3 = $(this).find('td:eq(7) input').val();
                    var conc4 = $(this).find('td:eq(8) input').val();
                    var conc5 = $(this).find('td:eq(9) input').val();
                    var conc6 = $(this).find('td:eq(10) input').val();
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
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
                    var conc1 = $(this).find('td:eq(5) input').val();
                    var conc2 = $(this).find('td:eq(6) input').val();
                    var conc3 = $(this).find('td:eq(7) input').val();
                    var conc4 = $(this).find('td:eq(8) input').val();
                    var conc5 = $(this).find('td:eq(9) input').val();
                    var conc6 = $(this).find('td:eq(10) input').val();
                    var conc7 = $(this).find('td:eq(11) input').val();
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
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
                    var conc1 = $(this).find('td:eq(5) input').val();
                    var conc2 = $(this).find('td:eq(6) input').val();
                    var conc3 = $(this).find('td:eq(7) input').val();
                    var conc4 = $(this).find('td:eq(8) input').val();
                    var conc5 = $(this).find('td:eq(9) input').val();
                    var conc6 = $(this).find('td:eq(10) input').val();
                    var conc7 = $(this).find('td:eq(11) input').val();
                    var conc8 = $(this).find('td:eq(12) input').val();
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
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
                    var conc1 = $(this).find('td:eq(5) input').val();
                    var conc2 = $(this).find('td:eq(6) input').val();
                    var conc3 = $(this).find('td:eq(7) input').val();
                    var conc4 = $(this).find('td:eq(8) input').val();
                    var conc5 = $(this).find('td:eq(9) input').val();
                    var conc6 = $(this).find('td:eq(10) input').val();
                    var conc7 = $(this).find('td:eq(11) input').val();
                    var conc8 = $(this).find('td:eq(12) input').val();
                    var conc9 = $(this).find('td:eq(13) input').val();
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
                success: function(response) {
                    $(getTr).find("td:eq(3)").find('input').val(response.Product_Name);
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
                            $(getTr).find("input.form-control.input-xs.conc").val('');
                            $(getTr).find("td:last").find('input').val("");
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
                url: "pages/ajax/tabledyestuff/GetNewCodeFcode.php",
                data: {
                    code: select_selected
                },
                success: function(response1) {
                    $(getTr).find("td:eq(2)").find('input').val(response1);
                    if (response == "-----------------------") {
                        $(getTr).find("input.form-control.input-xs.conc").val(0);
                        $(getTr).find("input.form-control.input-xs.conc").prop('disabled', true);
                        $(getTr).find("td:last").find('input').val("-----------------------");
                        $(getTr).find("td:last").find('input').prop('disabled', true);
                    } else {
                        $(getTr).find("input.form-control.input-xs.conc").prop('disabled', false);
                        $(getTr).find("td:last").find('input').prop('disabled', false);
                        $(getTr).find("input.form-control.input-xs.conc").val('');
                        $(getTr).find("td:last").find('input').val("");
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

        $('#plus_c1_now').click(function() {
            var attribute = $("#th-lookup1_now tr th:last").prev();
            var attri = attribute.attr('flag_th')
            var goesto = $('#tb-lookup1_now tr td:last').prev();
            var goes = goesto.attr('flag_td');
            if (attri == undefined) {
                var flag = 1;
            } else if (attri == '10') {
                toastr.error('Adjust maximal in 9 column !');
            } else {
                var flag = parseInt(attri) + 1;
                var flag_td = parseInt(attri) + 1;
                $("#th-lookup1_now th:last").before('<th width="60px" class="th_conc" flag_th="' + flag + '">Adjust-' + parseInt(flag - 1) + '</th>');
                $("#tb-lookup1_now tr").each(function() {
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

        $('#minus_c1_now').click(function() {
            var attribute = $("#th-lookup1_now tr th:last").prev();
            var flag_th = attribute.attr('flag_th')
            var goesto = $('#tb-lookup1_now tr td:last').prev();
            var flag_td = goesto.attr('flag_td');
            if (flag_th == '1') {
                toastr.error('You cannot delete entire Concentrate column !')
            } else {
                $(attribute).remove();
                $("#tb-lookup1_now tr").each(function() {
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
                $('#tb-lookup1 tr:last').find('td').find('input.form-control.input-xs.conc').prop('disabled', false)
                $('#tb-lookup1 tr:last td:last').find('input').prop('disabled', false)
                $('#tb-lookup1 tr:last').find('td').find('input').val("")
            }
        })

        $('#plus1_now').click(function() {
            let getno = $('#tb-lookup1_now tr:last td:first').html();
            if (getno == undefined) {
                var nomor = 1;
            } else if (getno == '26') {
                toastr.error('Maximal column is 26 row !')
            } else {
                var nomor = parseInt(getno) + 1;
                $("#tb-lookup1_now").append(
                    '<tr id="srow' + nomor + '">' + $('#tb-lookup1_now tr:last').html() + '</tr>'
                );
                $('#tb-lookup1_now tr:last td:first').html(nomor)
                $('#tb-lookup1_now tr:last td:eq(1)').html('<select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here .."></select>')
                $('#tb-lookup1_now tr:last').find('td').find('input.form-control.input-xs.conc').prop('disabled', false)
                $('#tb-lookup1_now tr:last').find('td').find('input').val("")
                $('#tb-lookup1_now tr:last td:last').html("<a href='#" + nomor + "' onclick='hapusElemen(\"#srow" + nomor + "\"); return false;'>Hapus</a>")

            }
        })

        $('#minus1').click(function() {
            if ($('#tb-lookup1').find('tr').length == 1) {
                toastr.error('You cannot delete entire table !')
            } else {
                $('#tb-lookup1 tr:last').remove();
            }
        })

        $('#minus1_now').click(function() {
            if ($('#tb-lookup1_now').find('tr').length == 1) {
                toastr.error('You cannot delete entire table !')
            } else {
                $('#tb-lookup1_now tr:last').remove();
            }
        })

        $('#lookupmodal1_now').on("keydown", function(e) {
            if (e.which == 13) {
                let getno = $('#tb-lookup1_now tr:last td:first').html();
                if (getno == undefined) {
                    var nomor = 1;
                } else if (getno == '26') {
                    toastr.error('Maximal column is 26 row !')
                } else {
                    var nomor = parseInt(getno) + 1;
                    $("#tb-lookup1_now").append(
                        '<tr id="srow' + nomor + '">' + $('#tb-lookup1_now tr:last').html() + '</tr>'
                    );
                    $('#tb-lookup1_now tr:last td:first').html(nomor)
                    $('#tb-lookup1_now tr:last td:eq(1)').html('<select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here .."></select>')
                    $('#tb-lookup1_now tr:last').find('td').find('input.form-control.input-xs.conc').prop('disabled', false)
                    $('#tb-lookup1_now tr:last').find('td').find('input').val('')
                    $('#tb-lookup1_now tr:last td:last').find('input').prop('disabled', false)
                }
            }
            if (e.which == 220) {
                if ($('#tb-lookup1_now').find('tr').length == 1) {
                    toastr.error('You cannot delete entire table !')
                } else {
                    $('#tb-lookup1_now tr:last').remove();
                }
            }
        })

        $('#before').click(function() {
            $("#row_flag").html('');
            $("#lookupmodal1_now tbody tr").each(function() {
                $("#row_flag").append('<li><a href="javascript:void(0)" class="selected_before"> Before ' + $(this).find('td:eq(0)').html() + '</a></li>');
            })
        })

        $(document).on('click', '.selected_before', function() {
            // SpinnerShow()
            var flag = $(this).html().substring(8);
            $("#lookupmodal1_now tbody tr").each(function() {
                if ($(this).find('td:eq(0)').html() == flag) {
                    $(this).before('<tr>' + $(this).html() + '</tr>')
                    $(this).find('td:eq(0)').html(parseInt($(this).find('td:eq(0)').html()) + 1)
                } else {
                    if (parseInt($(this).find('td:eq(0)').html()) > flag) {
                        $(this).find('td:eq(0)').html(parseInt($(this).find('td:eq(0)').html()) + 1)
                    }
                }
            })
            $("#lookupmodal1_now tbody tr").each(function() {
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
    });

    function hapusElemen(nomor) {
        $(nomor).remove();
    }
</script>



<script>
    $(document).ready(function() {
        $('.select2_lr').select2({
            placeholder: "Pilih...",
            tags: true
        });
        $('#second_lr').select2({
            placeholder: "Pilih...",
            tags: true
        });
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
                    $('#tb-lookup1 tr:last td:last').find('input').prop('disabled', false)
                    $('#tb-lookup1 tr:last').find('td').find('input').val("")
                }
            }
            if (e.which == 220) {
                if ($('#tb-lookup1').find('tr').length == 1) {
                    toastr.error('You cannot delete entire table !')
                } else {
                    $('#tb-lookup1 tr:last').remove();
                }
            }
        })

        $('#lookupmodal1_now').on("keydown", function(e) {
            if (e.which == 13) {
                let getno = $('#tb-lookup1_now tr:last td:first').html();
                if (getno == undefined) {
                    var nomor = 1;
                } else if (getno == '26') {
                    toastr.error('Maximal column is 26 row !')
                } else {
                    var nomor = parseInt(getno) + 1;
                    $("#tb-lookup1_now").append(
                        '<tr>' + $('#tb-lookup1_now tr:last').html() + '</tr>'
                    );
                    $('#tb-lookup1_now tr:last td:first').html(nomor)
                    $('#tb-lookup1_now tr:last td:eq(1)').html('<select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here .."></select>')
                    $('#tb-lookup1_now tr:last').find('td').find('input.form-control.input-xs.conc').prop('disabled', false)
                    $('#tb-lookup1_now tr:last td:last').find('input').prop('disabled', false)
                    $('#tb-lookup1_now tr:last').find('td').find('input').val("")
                    $('#tb-lookup1_now tr:last td:last').html("<a href='#" + nomor + "' onclick='hapusElemen(\"#srow" + nomor + "\"); return false;'>Hapus</a>")
                }
            }
            if (e.which == 220) {
                if ($('#tb-lookup1_now').find('tr').length == 1) {
                    toastr.error('You cannot delete entire table !')
                } else {
                    $('#tb-lookup1_now tr:last').remove();
                }
            }
            if (e.which == 46) {
                // let getno = $('#tb-lookup1_now tr:last td:first').html();
                $(getno).remove();
                // alert("test".getno)
            }
        })
    })
</script>

<!-- /////////script ajax select 2 -->
<script>
    $(document).ready(function() {
        $('.select_Fmatcher').select2({
            minimumInputLength: 0,
            allowClear: true,
            placeholder: 'Select matcher',
            ajax: {
                dataType: 'json',
                url: 'pages/ajax/Get_Matcher_select2.php',
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
        $('.select_UserResep').select2({
            minimumInputLength: 0,
            allowClear: true,
            placeholder: 'Select UserResep',
            ajax: {
                dataType: 'json',
                url: 'pages/ajax/Get_UserResep_select2.php',
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
        $('.select_Koreksi').select2({
            minimumInputLength: 0,
            allowClear: true,
            placeholder: 'Select Colorist',
            ajax: {
                dataType: 'json',
                url: 'pages/ajax/Get_Colorist_select2.php',
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

        $('.selectProses1').select2({
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


        $('.selectBuyer1').select2({
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
                        $('#lampu-buyer1').append('<div class="col-sm-3"><input class="form-control" value="' + value + '" readonly></div>')
                    });
                },
                error: function() {
                    alert("Hubungi Departement DIT !");
                }
            });
        });
    })
</script>
<!-- ///////script ajax select 2 -->
<script>
    $(document).ready(function() {
        // Menambahkan event listener untuk setiap select colorist
        $('select[name^="colorist_"]').change(function() {
            var id = $(this).attr('id').replace('colorist_', ''); // Mendapatkan angka dari ID colorist
            var koreksi_id = '#koreksi_' + id; // ID dari select koreksi yang sesuai

            if ($(this).val() !== '') {
                $(koreksi_id).prop('required', true); // Jika colorist dipilih, koreksi resep menjadi required
            } else {
                $(koreksi_id).prop('required', false); // Jika colorist tidak dipilih, koreksi resep tidak required
            }
        });

        // Menambahkan event listener untuk setiap select koreksi
        $('select[name^="koreksi_"]').change(function() {
            var id = $(this).attr('id').replace('koreksi_', ''); // Mendapatkan angka dari ID koreksi
            var colorist_id = '#colorist_' + id; // ID dari select colorist yang sesuai

            if ($(this).val() !== '') {
                $(colorist_id).prop('required', true); // Jika koreksi dipilih, colorist menjadi required
            } else {
                $(colorist_id).prop('required', false); // Jika koreksi tidak dipilih, colorist tidak required
            }
        });
    });
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