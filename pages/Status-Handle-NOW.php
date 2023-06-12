<?php
    ini_set("error_reporting", 1);
    session_start();
    include "koneksi.php";
    $date = date('Y-m-d');
    $sql = mysqli_query($con,"SELECT a.id as id_status, a.idm, a.flag, a.grp, a.matcher, a.cek_warna, a.cek_dye, a.status, a.kt_status, a.koreksi_resep,
                                a.percobaan_ke, a.benang_aktual, a.lebar_aktual, a.gramasi_aktual, a.soaping_sh, a.soaping_tm, a.rc_sh, a.rc_tm, a.lr, a.cie_wi, a.cie_tint, 
                                a.spektro_r, a.ket, a.created_at as tgl_buat_status, a.created_by as status_created_by, a.edited_at, a.edited_by, a.target_selesai, 
                                a.mulai_by, a.mulai_at, a.selesai_by, a.selesai_at, a.approve_by, a.approve_at, a.approve, b.id, b.no_resep, b.no_order, b.no_po, b.langganan, b.no_item,
                                b.jenis_kain, b.benang, b.cocok_warna, b.warna, b.no_warna, b.lebar, b.gramasi, b.qty_order, b.tgl_in, b.tgl_out, b.proses, b.buyer,
                                b.tgl_delivery, b.note, b.jenis_matching, b.tgl_buat, b.tgl_update, b.created_by, a.bleaching_sh, a.bleaching_tm,b.color_code,b.recipe_code
                                FROM tbl_status_matching a
                                INNER JOIN tbl_matching b ON a.idm = b.no_resep
                                where a.id = '$_GET[idm]'
                                ORDER BY a.id desc limit 1");
    $data = mysqli_fetch_array($sql); 
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
<?php $sufix = strtoupper($data['idm']); ?>
<body>
    <div class="container col-md-12">
        <button class="btn btn-xs pull-right" style="background-color: grey; color: white; margin-bottom: 10px;"><?php echo $data['idm']; ?> </button>
    </div>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li id="tab1" class="active"><a data-toggle="tab" href="#input-status"><b>Basic Info</b></a></li>
            <li id="tab3"><a data-toggle="tab" href="#resep_now"><b>RESEP NOW</b></a></li>
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
                                <input type="text" value="<?php echo $data['no_item'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development') {
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
                                <input type="text" value="<?php echo $data['color_code'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development' or $data['jenis_matching'] == 'Matching Ulang') {
                                                                                                echo '';
                                                                                            } else {
                                                                                                echo 'readonly ';
                                                                                            } ?> class="form-control input-sm" name="color_code" id="color_code" placeholder="Color Code" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="no_warna" class="col-sm-3 control-label">No.warna</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?php echo $data['no_warna'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development' or $data['jenis_matching'] == 'Matching Ulang') {
                                                                                                echo '';
                                                                                            } else {
                                                                                                echo 'readonly ';
                                                                                            } ?> class="form-control input-sm" name="no_warna" id="no_warna" placeholder="no_warna">
                            </div>
                        </div>
						<div class="form-group">
                            <label for="warna" class="col-sm-3 control-label">Warna</label>
                            <div class="col-sm-9">
                                <input type="text" value="<?php echo $data['warna'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development' or $data['jenis_matching'] == 'Matching Ulang') {
                                                                                            echo '';
                                                                                        } else {
                                                                                            echo 'readonly ';
                                                                                        } ?> class="form-control input-sm" name="warna" id="warna" placeholder="warna">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Kain" class="col-sm-3 control-label">Kain</label>
                            <div class="col-sm-9">
                                <textarea class="form-control input-sm" name="Kain" id="Kain" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development') {
                                                                                                    echo '';
                                                                                                } else {
                                                                                                    echo 'readonly ';
                                                                                                } ?> rows="2"><?php echo $data['jenis_kain'] ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Benang" class="col-sm-3 control-label">Benang</label>
                            <div class="col-sm-9">
                                <textarea class="form-control input-sm" name="Benang" id="Benang" rows="3" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development' or $data['jenis_matching'] == 'Matching Ulang') {
                                                                                                                echo '';
                                                                                                            } else {
                                                                                                                echo 'readonly ';
                                                                                                            } ?>><?php echo $data['benang'] ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Gramasi" class="col-sm-3 control-label">Gramasi</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm" name="Lebar" id="Lebar" placeholder="Inch" value="<?php echo $data['lebar'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development') { echo '';  } else { echo 'readonly '; } ?>>
                                <div class="input-group-addon"><small>Inch</small></div>
                            </div>
                            <div class="col-sm-1">
                                <button type="button" class="btn btn-dark"> <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm" name="Gramasi" id="Gramasi" placeholder="Gr/M2" value="<?php echo $data['gramasi'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development') {  echo ''; } else { echo 'readonly '; } ?>>
                                <div class="input-group-addon"><small>Gr/M²</small></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Delivery" class="col-sm-3 control-label">Tgl Delivery</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control date-picker input-sm" name="Tgl_delivery" id="Tgl_delivery" placeholder="Tgl delivery" value="<?php echo $data['tgl_delivery'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development') { echo ''; } else { echo 'disabled '; } ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Order" class="col-sm-3 control-label"> <?php if ($data['jenis_matching'] != 'L/D') {
                                                                                    echo 'No. Order';
                                                                                } else {
                                                                                    echo 'Request No';
                                                                                }   ?></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" name="Order" id="Order" placeholder="Order" value="<?php echo $data['no_order'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development'){ echo ''; } else { echo 'readonly ';} ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Order" class="col-sm-3 control-label">PO.Greige</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" name="Order" id="Order" placeholder="Order" value="<?php echo $data['no_po'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development') { echo '';} else { echo 'readonly ';} ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="QtyOrder" class="col-sm-3 control-label">Qty Order</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" name="QtyOrder" id="QtyOrder" placeholder="Qty Order" value="<?php echo $data['qty_order'] ?>" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development') { echo '';} else { echo 'readonly ';  } ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Matcher" class="col-sm-3 control-label">Matcher Awal</label>
                            <div class="col-sm-9">
                                <select type="text" class="form-control input-sm select_Fmatcher" name="Matcher" id="Matcher" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development') {
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
                                <select type="text" class="form-control input-sm" name="Group" id="Group" <?php if ($data['jenis_matching'] == 'L/D' or $data['jenis_matching'] == 'Matching Development') {
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
                                <?php $sqlLamp = mysqli_query($con,"SELECT * FROM vpot_lampbuy where buyer = '$data[buyer]'"); ?>
                                <?php while ($lamp = mysqli_fetch_array($sqlLamp)) { ?>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control input-sm" value="<?php echo $lamp['lampu'] ?>" readonly>
                                    </div>
                                <?php } ?>
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
                            <div class="form-group">
                                <label for="Done_Matching" class="col-sm-2 control-label">Koreksi Resep</label>
                                <div class="col-sm-6">
                                    <select class="form-control select_Koreksi" required name="koreksi" id="koreksi">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Done_Matching" class="col-sm-2 control-label">Colorist1</label>
                                <div class="col-sm-3">
                                    <select class="form-control select_Koreksi" required name="colorist_1" id="colorist_1">
                                    </select>
                                </div>
                                <label for="Done_Matching" class="col-sm-1 control-label">Colorist2</label>
                                <div class="col-sm-3">
                                    <select class="form-control select_Koreksi" required name="colorist_2" id="colorist_2">
                                    </select>
                                </div>
                            </div>
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
            <!-- RESEP NOW -->
            <div id="resep_now" class="tab-pane fade">
                <br />
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-6" style="margin-bottom: 4px;">
                            <a id="import" href="#" data-toggle="modal" data-target="#DataResepNow" class="btn btn-success btn-xs"><i class="fa fa-cloud-download" aria-hidden="true"></i> Select Recipe From NOW</a>
                        </div>
                        <div class="col-lg-6 align-right text-right" style="margin-bottom: 4px;">
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
                            <button type="button" id="plus_c1_now" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Conc</button>
                            <button type="button" id="minus_c1_now" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i> Conc</button>||
                            <button type="button" id="plus1_now" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Baris</button>
                            <button type="button" id="minus1_now" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i> Baris</button>
                        </div>
                    </div>
                    <div class="col-lg-12 overflow-auto table-responsive well" style="overflow-x: auto;">
                        <table id="lookupmodal1_now" class="lookupST display nowrap" width="110%" style="padding-right: 16px;">
                            <thead id="th-lookup1_now">
                                <tr>
                                    <th width="5px">#</th>
                                    <th width="60px" class="th_code">Code</th>
                                    <th width="60px" class="th_code_new">ERP Code</th>
                                    <th width="140px" class="th_name">Name</th>
                                    <th width="60px" class="th_conc" flag_th="1">LAB</th>
                                    <th width="140px" class="th_remark">Remark</th>
                                </tr>
                            </thead>
                            <tbody id="tb-lookup1_now">
                                <?php 
                                    $query_recipe_cmp = "TRIM(r.ITEMTYPEAFICODE),
                                                                    TRIM(r.SUBCODE01) || '-' ||	TRIM(r.SUBCODE02) || '-' ||	TRIM(r.SUBCODE03) AS CODECMP,
                                                                    TRIM(p.LONGDESCRIPTION) AS DESKRIPSI,
                                                                    CONSUMPTION
                                                                FROM 
                                                                RECIPECOMPONENT r 
                                                                RIGHT JOIN PRODUCT p ON p.ITEMTYPECODE = r.ITEMTYPEAFICODE 
                                                                                    AND p.SUBCODE01 = r.SUBCODE01 
                                                                                    AND p.SUBCODE02 = r.SUBCODE02 
                                                                                    AND p.SUBCODE03 = r.SUBCODE03
                                                                WHERE"; 
                                // TRIM(r.ITEMTYPEAFICODE) = 'DYC AND' 
                                ?>
                                
                                <?php if (substr($sufix, 0, 2) == 'D2' or substr($sufix, 0, 1) == 'C') : ?>
                                    <?php 
                                        $recipeNumberD2CD = $_GET['D2CD'];
                                        $query_rcmpD2CD = db2_exec($conn1, "SELECT $query_recipe_cmp RECIPENUMBERID = '$recipeNumberD2CD'");
                                        $no = 1;
                                        while ($dt_rcmpD2CD = db2_fetch_assoc($query_rcmpD2CD)){
                                    ?>
                                    <tr>
                                        <td align="center" class="nomor"><?= $no++; ?></td>
                                        <td>
                                            <select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here ..">
                                                <option value="<?= $dt_rcmpD2CD['CODECMP']; ?>" selected><?= $dt_rcmpD2CD['CODECMP']; ?></option>
                                            </select>
                                        </td>
                                        <td><input style="width: 100%" type="text" class="form-control input-xs new_code" value="<?= $dt_rcmpD2CD['CODECMP']; ?>"></td>
                                        <td><input style="width: 100%" type="text" class="form-control input-xs name" value="<?= $dt_rcmpD2CD['DESKRIPSI']; ?>"></td>
                                        <td flag_td="1"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?= $dt_rcmpD2CD['CONSUMPTION']; ?>"></td>
                                        <td><input style="width: 100%" type="text" class="form-control input-xs remark"></td>
                                    </tr>
                                    <?php } ?>
                                <?php elseif (substr($sufix, 0, 1) == 'R' or substr($sufix, 0, 1) == 'A') : ?>
                                    <?php 
                                        $recipeNumberRA = $_GET['RA'];
                                        $query_rcmpRA = db2_exec($conn1, "SELECT $query_recipe_cmp RECIPENUMBERID = '$recipeNumberRA'");
                                        $no = 1;
                                        while ($dt_rcmpRA = db2_fetch_assoc($query_rcmpRA)){
                                    ?>
                                    <tr>
                                        <td align="center" class="nomor"><?= $no++; ?></td>
                                        <td>
                                            <select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here ..">
                                                <option value="<?= $dt_rcmpRA['CODECMP']; ?>" selected><?= $dt_rcmpRA['CODECMP']; ?></option>
                                            </select>
                                        </td>
                                        <td><input style="width: 100%" type="text" class="form-control input-xs new_code" value="<?= $dt_rcmpRA['CODECMP']; ?>"></td>
                                        <td><input style="width: 100%" type="text" class="form-control input-xs name" value="<?= $dt_rcmpRA['DESKRIPSI']; ?>"></td>
                                        <td flag_td="1"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?= $dt_rcmpRA['CONSUMPTION']; ?>"></td>
                                        <td><input style="width: 100%" type="text" class="form-control input-xs remark"></td>
                                    </tr>
                                    <?php } ?>
                                <?php elseif(substr($sufix, 0, 2) == 'DR') : ?>
                                    <?php if($_GET['D'] && !($_GET['R'])) : ?>
                                        <?php 
                                            $recipeNumberD = $_GET['D'];
                                            $query_rcmpD = db2_exec($conn1, "SELECT $query_recipe_cmp RECIPENUMBERID = '$recipeNumberD'");
                                            $no = 1;
                                            while ($dt_rcmpD = db2_fetch_assoc($query_rcmpD)){
                                        ?>
                                        <tr>
                                            <td align="center" class="nomor"><?= $no++; ?></td>
                                            <td>
                                                <select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here ..">
                                                    <option value="<?= $dt_rcmpD['CODECMP']; ?>" selected><?= $dt_rcmpD['CODECMP']; ?></option>
                                                </select>
                                            </td>
                                            <td><input style="width: 100%" type="text" class="form-control input-xs new_code" value="<?= $dt_rcmpD['CODECMP']; ?>"></td>
                                            <td><input style="width: 100%" type="text" class="form-control input-xs name" value="<?= $dt_rcmpD['DESKRIPSI']; ?>"></td>
                                            <td flag_td="1"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?= $dt_rcmpD['CONSUMPTION']; ?>"></td>
                                            <td><input style="width: 100%" type="text" class="form-control input-xs remark"></td>
                                        </tr>
                                        <?php } ?>
                                    <?php elseif($_GET['R'] && !($_GET['D'])) : ?>
                                        <?php 
                                            $recipeNumberR = $_GET['R'];
                                            $query_rcmpR = db2_exec($conn1, "SELECT $query_recipe_cmp RECIPENUMBERID = '$recipeNumberR'");
                                            $no = 1;
                                            while ($dt_rcmpR = db2_fetch_assoc($query_rcmpR)){
                                        ?>
                                        <tr>
                                            <td align="center" class="nomor"><?= $no++; ?></td>
                                            <td>
                                                <select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here ..">
                                                    <option value="<?= $dt_rcmpR['CODECMP']; ?>" selected><?= $dt_rcmpR['CODECMP']; ?></option>
                                                </select>
                                            </td>
                                            <td><input style="width: 100%" type="text" class="form-control input-xs new_code" value="<?= $dt_rcmpR['CODECMP']; ?>"></td>
                                            <td><input style="width: 100%" type="text" class="form-control input-xs name" value="<?= $dt_rcmpR['DESKRIPSI']; ?>"></td>
                                            <td flag_td="1"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?= $dt_rcmpR['CONSUMPTION']; ?>"></td>
                                            <td><input style="width: 100%" type="text" class="form-control input-xs remark"></td>
                                        </tr>
                                        <?php } ?>
                                    <?php elseif($_GET['D'] && $_GET['R']) : ?>
                                        <?php 
                                            $recipeNumberD = $_GET['D'];
                                            $recipeNumberR = $_GET['R'];
                                            $query_rcmpDR = db2_exec($conn1, "SELECT $query_recipe_cmp RECIPENUMBERID = '$recipeNumberD' OR RECIPENUMBERID = '$recipeNumberR'");
                                            $no = 1;
                                            while ($dt_rcmpDR = db2_fetch_assoc($query_rcmpDR)){
                                        ?>
                                        <tr>
                                            <td align="center" class="nomor"><?= $no++; ?></td>
                                            <td>
                                                <select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here ..">
                                                    <option value="<?= $dt_rcmpDR['CODECMP']; ?>" selected><?= $dt_rcmpDR['CODECMP']; ?></option>
                                                </select>
                                            </td>
                                            <td><input style="width: 100%" type="text" class="form-control input-xs new_code" value="<?= $dt_rcmpDR['CODECMP']; ?>"></td>
                                            <td><input style="width: 100%" type="text" class="form-control input-xs name" value="<?= $dt_rcmpDR['DESKRIPSI']; ?>"></td>
                                            <td flag_td="1"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?= $dt_rcmpDR['CONSUMPTION']; ?>"></td>
                                            <td><input style="width: 100%" type="text" class="form-control input-xs remark"></td>
                                        </tr>
                                        <?php } ?>
                                    <?php endif; ?>
                                <?php elseif(substr($sufix, 0, 2) == 'OB') : ?>
                                    <?php 
                                        $recipeNumberOB = $_GET['OB'];
                                        $query_rcmpOB = db2_exec($conn1, "SELECT $query_recipe_cmp RECIPENUMBERID = '$recipeNumberOB'");
                                        $no = 1;
                                        while ($dt_rcmpOB = db2_fetch_assoc($query_rcmpOB)){
                                    ?>
                                    <tr>
                                        <td align="center" class="nomor"><?= $no++; ?></td>
                                        <td>
                                            <select style="width: 100%" type="text" class="form-control input-xs code" placeholder="type code here ..">
                                                <option value="<?= $dt_rcmpOB['CODECMP']; ?>" selected><?= $dt_rcmpOB['CODECMP']; ?></option>
                                            </select>
                                        </td>
                                        <td><input style="width: 100%" type="text" class="form-control input-xs new_code" value="<?= $dt_rcmpOB['CODECMP']; ?>"></td>
                                        <td><input style="width: 100%" type="text" class="form-control input-xs name" value="<?= $dt_rcmpOB['DESKRIPSI']; ?>"></td>
                                        <td flag_td="1"><input style="width: 100%" type="text" class="form-control input-xs conc" value="<?= $dt_rcmpOB['CONSUMPTION']; ?>"></td>
                                        <td><input style="width: 100%" type="text" class="form-control input-xs remark"></td>
                                    </tr>
                                    <?php } ?>
                                <?php else : ?>
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
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-11 well" style="margin-top: 10px;">
                    <?php
                        $suhumenit = "CASE
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 11,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 9,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 11,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 9,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 11,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 9,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 11,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 9,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 11,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 9,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 11,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 9,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 11,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 9,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 11,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 9,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 11,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 9,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 11,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 9,3)
                                    ELSE 
                                        SUBSTR(TRIM(COMMENTLINE), 9,2)
                                END AS SUHU_SOAPING,
                                CASE
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 18,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 16,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 18,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 16,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 18,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 16,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 18,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 16,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 18,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 16,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 18,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 16,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 18,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 16,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 18,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 16,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 18,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 16,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 18,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 16,3)
                                    ELSE
                                        SUBSTR(TRIM(COMMENTLINE), 16,2)
                                END AS MENIT_SOAPING,
                                -----------------------------------------------------------------------------------
                                CASE
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    ELSE 
                                        SUBSTR(TRIM(COMMENTLINE), 11,2)
                                END AS SUHU_BLEACHING,
                                CASE
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 20,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 18,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 20,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 18,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 20,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 18,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 20,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 18,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 20,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 18,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 20,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 18,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 20,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 18,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 20,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 18,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 20,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 18,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 20,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 18,3)
                                    ELSE
                                        SUBSTR(TRIM(COMMENTLINE), 18,2)
                                END AS MENIT_BLEACHING,
                                -----------------------------------------------------------------------------------
                                CASE
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 6,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 4,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 6,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 4,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 6,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 4,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 6,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 4,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 6,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 4,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 6,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 4,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 6,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 4,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 6,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 4,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 6,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 4,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 6,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 4,3)
                                    ELSE 
                                        SUBSTR(TRIM(COMMENTLINE), 4,2)
                                END AS SUHU_RC,
                                CASE
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    WHEN SUBSTR(TRIM(COMMENTLINE), 13,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 11,3)
                                    ELSE
                                        SUBSTR(TRIM(COMMENTLINE), 11,2)
                                END AS MENIT_RC";
                    ?>
                        <div class="form-group">
                            <label for="L_R" class="col-sm-1 control-label">T-SIDE L:R : </label>
                            <div class="col-sm-2">
                                <?php if (substr($sufix, 0, 2) == 'D2' or substr($sufix, 0, 1) == 'C') : ?>
                                    <?php
                                        $numbidD2CD = $_GET['D2CD'];
                                        $query_recipeD2CD = db2_exec($conn1, "SELECT FLOOR(LIQUORRATIO) AS LIQUORRATIO FROM RECIPE WHERE TRIM(ITEMTYPECODE) = 'RFD' AND NUMBERID = '$numbidD2CD'");
                                        $dt_recipeD2CD = db2_fetch_assoc($query_recipeD2CD);
                                    ?>
                                    <?php $tSide = $dt_recipeD2CD['LIQUORRATIO']; ?>
                                <?php elseif(substr($sufix, 0, 2) == 'DR') : ?>
                                    <?php
                                        $numbidD = $_GET['D'];
                                        $query_recipeD = db2_exec($conn1, "SELECT FLOOR(LIQUORRATIO) AS LIQUORRATIO FROM RECIPE WHERE TRIM(ITEMTYPECODE) = 'RFD' AND NUMBERID = '$numbidD'");
                                        $dt_recipeD = db2_fetch_assoc($query_recipeD);
                                        
                                        $numbidR = $_GET['R'];
                                        $query_recipeR = db2_exec($conn1, "SELECT FLOOR(LIQUORRATIO) AS LIQUORRATIO FROM RECIPE WHERE TRIM(ITEMTYPECODE) = 'RFD' AND NUMBERID = '$numbidR'");
                                        $dt_recipeR = db2_fetch_assoc($query_recipeR);
                                    ?>
                                    <?php if($_GET['D'] && !($_GET['R'])) : ?>
                                        <?php $tSide = $dt_recipeD['LIQUORRATIO']; ?>
                                    <?php elseif($_GET['R'] && !($_GET['D'])) : ?>
                                        <?php $tSide = ''; ?>
                                    <?php elseif($_GET['D'] && $_GET['R']) : ?>
                                        <?php $tSide = $dt_recipeD['LIQUORRATIO']; ?>
                                    <?php endif; ?>
                                <?php elseif(substr($sufix, 0, 2) == 'OB') : ?>
                                    <?php
                                        $numbidOB = $_GET['OB'];
                                        $query_recipeOB = db2_exec($conn1, "SELECT FLOOR(LIQUORRATIO) AS LIQUORRATIO FROM RECIPE WHERE TRIM(ITEMTYPECODE) = 'RFD' AND NUMBERID = '$numbidOB'");
                                        $dt_recipeOB = db2_fetch_assoc($query_recipeOB);
                                    ?>
                                    <?php $tSide = $dt_recipeOB['LIQUORRATIO']; ?>
                                <?php endif; ?>
                                <select type="text" style="width: 100%;" class="form-control select2_lr" required name="L_R" id="L_R" placeholder="L_R">
                                    <option selected disabled>Pilih...</option>
                                    <option value="1:6">1:6</option>
                                    <option value="1:9">1:9</option>
                                    <option value="1:10">1:10</option>
                                    <option value="1:12">1:12</option>
                                    <option selected value="1:<?= $tSide; ?>">1:<?= $tSide; ?></option>
                                </select>
                                <span></span>
                            </div>
                            <label for="L_R" class="col-sm-1 control-label">C-SIDE L:R :</label>
                            <div class="col-sm-2">
                                <?php if (substr($sufix, 0, 1) == 'R' or substr($sufix, 0, 1) == 'A') : ?>
                                    <?php
                                        $numbidRA = $_GET['RA'];
                                        $query_recipeRA = db2_exec($conn1, "SELECT FLOOR(LIQUORRATIO) AS LIQUORRATIO FROM RECIPE WHERE TRIM(ITEMTYPECODE) = 'RFD' AND NUMBERID = '$numbidRA'");
                                        $dt_recipeRA = db2_fetch_assoc($query_recipeRA);
                                    ?>
                                    <?php $cSide = $dt_recipeRA['LIQUORRATIO']; ?>
                                <?php elseif(substr($sufix, 0, 2) == 'DR') : ?>
                                    <?php
                                        $numbidD = $_GET['D'];
                                        $query_recipeD = db2_exec($conn1, "SELECT FLOOR(LIQUORRATIO) AS LIQUORRATIO FROM RECIPE WHERE TRIM(ITEMTYPECODE) = 'RFD' AND NUMBERID = '$numbidD'");
                                        $dt_recipeD = db2_fetch_assoc($query_recipeD);
                                        
                                        $numbidR = $_GET['R'];
                                        $query_recipeR = db2_exec($conn1, "SELECT FLOOR(LIQUORRATIO) AS LIQUORRATIO FROM RECIPE WHERE TRIM(ITEMTYPECODE) = 'RFD' AND NUMBERID = '$numbidR'");
                                        $dt_recipeR = db2_fetch_assoc($query_recipeR);
                                    ?>
                                    <?php if($_GET['D'] && !($_GET['R'])) : ?>
                                        <?php $cSide = ''; ?>
                                    <?php elseif($_GET['R'] && !($_GET['D'])) : ?>
                                        <?php $cSide = $dt_recipeR['LIQUORRATIO']; ?>
                                    <?php elseif($_GET['D'] && $_GET['R']) : ?>
                                        <?php $cSide = $dt_recipeR['LIQUORRATIO']; ?>
                                    <?php endif; ?>
                                <?php elseif(substr($sufix, 0, 2) == 'OB') : ?>              
                                <?php endif; ?>
                                <select type="text" style="width: 100%;" class="form-control second_lr" required name="second_lr" id="second_lr" placeholder="second_lr">
                                    <option selected disabled>Pilih...</option>
                                    <option value="1:6">1:6</option>
                                    <option value="1:9">1:9</option>
                                    <option value="1:10">1:10</option>
                                    <option value="1:12">1:12</option>
                                    <option selected value="1:<?= $cSide; ?>">1:<?= $cSide; ?></option>
                                </select>
                                <span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kadar_air" class="col-sm-1 control-label">Ph :</label>
                            <div class="col-sm-3">
                                <input type="text" required class="form-control" name="kadar_air" id="kadar_air" placeholder="ph air ...">
                            </div>
                        </div>
                        <div class="col-md-12 well" style="margin-top: 20px;">
                            <?php if (substr($sufix, 0, 2) == 'D2' or substr($sufix, 0, 1) == 'C') { ?>
                                <div class="form-group">
                                    <label for="tside_c" class="col-sm-2 control-label">T-SIDE :</label>
                                    <div class="col-sm-2">
                                        <?php
                                            $numbidD2CD = $_GET['D2CD'];
                                            $query_tSideD2CD = db2_exec($conn1, "SELECT TRIM(COMMENTLINE) AS COMMENTLINE,
                                                                                CASE
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    ELSE 
                                                                                        SUBSTR(TRIM(COMMENTLINE), 1,2)
                                                                                END AS SUHU,
                                                                                CASE
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    ELSE
                                                                                        SUBSTR(TRIM(COMMENTLINE), 8,2)
                                                                                END AS MENIT
                                                                                FROM RECIPECOMPONENT 
                                                                                WHERE TRIM(RECIPEITEMTYPECODE) = 'RFD' 
                                                                                        AND RECIPENUMBERID = '$numbidD2CD' 
                                                                                        AND NOT COMMENTLINE LIKE '%BLEACHING%'
                                                                                        AND NOT COMMENTLINE LIKE '%SOAPING%'
                                                                                        AND NOT COMMENTLINE LIKE '%RC%'
                                                                                        AND NOT COMMENTLINE = ''");
                                            $dt_tSideD2CD = db2_fetch_assoc($query_tSideD2CD);
                                        ?>
                                        <input type="text" width="100%" class="form-control" required name="tside_c" value="<?= $dt_tSideD2CD['SUHU']; ?>" id="tside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" name="tside_min" value="<?= $dt_tSideD2CD['MENIT']; ?>" id="tside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <?php
                                    $numbidD2CD = $_GET['D2CD'];
                                    $query_D2CD = db2_exec($conn1, "SELECT TRIM(COMMENTLINE) AS COMMENTLINE, $suhumenit FROM RECIPECOMPONENT 
                                                                    WHERE TRIM(RECIPEITEMTYPECODE) = 'RFD' 
                                                                        AND RECIPENUMBERID = '$numbidD2CD'");
                                    while($dt_D2CD = db2_fetch_assoc($query_D2CD)){
                                        if(str_contains($dt_D2CD['COMMENTLINE'], 'SOAPING')){
                                            $soapingSuhu = $dt_D2CD['SUHU_SOAPING'];
                                            $soapingMenit = $dt_D2CD['MENIT_SOAPING'];
                                        }
                                        if(str_contains($dt_D2CD['COMMENTLINE'], 'RC')){
                                            $rcSuhu = $dt_D2CD['SUHU_RC'];
                                            $rcMenit = $dt_D2CD['MENIT_RC'];
                                        }
                                        if(str_contains($dt_D2CD['COMMENTLINE'], 'BLEACHING')){
                                            $bleachingSuhu = $dt_D2CD['SUHU_BLEACHING'];
                                            $bleachingMenit = $dt_D2CD['MENIT_BLEACHING'];
                                        }
                                    }
                                ?>
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for=" RC" class="col-sm-2 control-label" align="left">RC</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" required id="RC_Suhu" name="RC_Suhu" value="<?= $rcSuhu; ?>" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="RCWaktu" required name="RCWaktu" value="<?= $rcMenit; ?>" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for=" RC" class="col-sm-2 control-label" align="left">Bleaching</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="bleaching_sh" value="<?php 
                                            if($data['bleaching_sh']){
                                                if (floatval($data['bleaching_sh']) != 0) {
                                                    echo floatval($data['bleaching_sh']);
                                                }
                                            }else{
                                                echo $bleachingSuhu; 
                                            }
                                        ?>" required name="bleaching_sh" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="bleaching_tm" value="<?php 
                                        if (floatval($data['bleaching_tm']) != 0) {
                                            echo floatval($data['bleaching_tm']); 
                                            }else{
                                                echo $bleachingMenit;
                                            } ?>" required name="bleaching_tm" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                            <?php } elseif (substr($sufix, 0, 1) == 'R' or substr($sufix, 0, 1) == 'A') { ?>
                                <div class="form-group">
                                    <label for="cside_c" class="col-sm-2 control-label">C-SIDE :</label>
                                    <div class="col-sm-2">
                                        <?php
                                            $numbidRA = $_GET['RA'];
                                            $query_cSideRA = db2_exec($conn1, "SELECT TRIM(COMMENTLINE) AS COMMENTLINE,
                                                                                CASE
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    ELSE 
                                                                                        SUBSTR(TRIM(COMMENTLINE), 1,2)
                                                                                END AS SUHU,
                                                                                CASE
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    ELSE
                                                                                        SUBSTR(TRIM(COMMENTLINE), 8,2)
                                                                                END AS MENIT
                                                                                FROM RECIPECOMPONENT 
                                                                                WHERE TRIM(RECIPEITEMTYPECODE) = 'RFD' 
                                                                                        AND RECIPENUMBERID = '$numbidRA' 
                                                                                        AND NOT COMMENTLINE LIKE '%BLEACHING%'
                                                                                        AND NOT COMMENTLINE LIKE '%SOAPING%'
                                                                                        AND NOT COMMENTLINE LIKE '%RC%'
                                                                                        AND NOT COMMENTLINE = ''");
                                            $dt_cSideRA = db2_fetch_assoc($query_cSideRA);
                                        ?>
                                        <input type="text" width="100%" class="form-control" required value="<?= $dt_cSideRA['SUHU']; ?>" name="cside_c" id="cside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" name="cside_min" value="<?= $dt_cSideRA['MENIT']; ?>" id="cside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <!-- SOAPING -->
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">SOAPING</label>
                                    <div class="input-group col-md-5">
                                        <?php
                                            $numbidRA = $_GET['RA'];
                                            $query_RA = db2_exec($conn1, "SELECT TRIM(COMMENTLINE) AS COMMENTLINE, $suhumenit FROM RECIPECOMPONENT 
                                                                            WHERE TRIM(RECIPEITEMTYPECODE) = 'RFD' 
                                                                                AND RECIPENUMBERID = '$numbidRA'");
                                            
                                             while($dt_RA = db2_fetch_assoc($query_RA)){
                                                if(str_contains($dt_RA['COMMENTLINE'], 'SOAPING')){
                                                    $soapingSuhu = $dt_RA['SUHU_SOAPING'];
                                                    $soapingMenit = $dt_RA['MENIT_SOAPING'];
                                                }
                                            }
                                        ?>
                                        <input type="text" class="form-control" required id="soapingSuhu" value="<?= $soapingSuhu; ?>" name="soapingSuhu" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" required id="soapingWaktu" value="<?= $soapingMenit; ?>" name="soapingWaktu" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <!-- //SOAPING -->
                            <?php } elseif (substr($sufix, 0, 2) == 'DR') { ?>
                                <div class="form-group">
                                    <label for="tside_c" class="col-sm-2 control-label">T-SIDE :</label>
                                    <div class="col-sm-2">
                                        <?php
                                            $numbidD = $_GET['D'];
                                            $query_tSideD = db2_exec($conn1, "SELECT TRIM(COMMENTLINE) AS COMMENTLINE,
                                                                                CASE
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    ELSE 
                                                                                        SUBSTR(TRIM(COMMENTLINE), 1,2)
                                                                                END AS SUHU,
                                                                                CASE
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    ELSE
                                                                                        SUBSTR(TRIM(COMMENTLINE), 8,2)
                                                                                END AS MENIT	
                                                                                FROM RECIPECOMPONENT 
                                                                                WHERE TRIM(RECIPEITEMTYPECODE) = 'RFD' 
                                                                                        AND RECIPENUMBERID = '$numbidD' 
                                                                                        AND NOT COMMENTLINE LIKE '%BLEACHING%'
                                                                                        AND NOT COMMENTLINE LIKE '%SOAPING%'
                                                                                        AND NOT COMMENTLINE LIKE '%RC%'
                                                                                        AND NOT COMMENTLINE = ''");
                                            $dt_tSideD = db2_fetch_assoc($query_tSideD);
                                        ?>
                                        <input type="text" width="100%" class="form-control" required name="tside_c" value="<?= $dt_tSideD['SUHU']; ?>" id="tside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" name="tside_min" value="<?= $dt_tSideD['MENIT']; ?>" id="tside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cside_c" class="col-sm-2 control-label">C-SIDE :</label>
                                    <div class="col-sm-2">
                                        <?php
                                            $numbidR = $_GET['R'];
                                            $query_tSideR = db2_exec($conn1, "SELECT TRIM(COMMENTLINE) AS COMMENTLINE,
                                                                                CASE
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    ELSE 
                                                                                        SUBSTR(TRIM(COMMENTLINE), 1,2)
                                                                                END AS SUHU,
                                                                                CASE
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    ELSE
                                                                                        SUBSTR(TRIM(COMMENTLINE), 8,2)
                                                                                END AS MENIT	
                                                                                FROM RECIPECOMPONENT 
                                                                                WHERE TRIM(RECIPEITEMTYPECODE) = 'RFD' 
                                                                                        AND RECIPENUMBERID = '$numbidR' 
                                                                                        AND NOT COMMENTLINE LIKE '%BLEACHING%'
                                                                                        AND NOT COMMENTLINE LIKE '%SOAPING%'
                                                                                        AND NOT COMMENTLINE LIKE '%RC%'
                                                                                        AND NOT COMMENTLINE = ''");
                                            $dt_tSideR = db2_fetch_assoc($query_tSideR);
                                        ?>
                                        <input type="text" width="100%" class="form-control" value="<?= $dt_tSideR['SUHU']; ?>" required name="cside_c" id="cside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" value="<?= $dt_tSideR['MENIT']; ?>" name="cside_min" id="cside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <!-- SOAPING -->
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">SOAPING</label>
                                    <div class="input-group col-md-5">
                                        <?php
                                            $numbidD = $_GET['D'];
                                            $numbidR = $_GET['R'];
                                            if($numbidD && !($numbidR)) {
                                                $query_SRB = db2_exec($conn1, "SELECT TRIM(COMMENTLINE) AS COMMENTLINE, $suhumenit FROM RECIPECOMPONENT 
                                                                                WHERE TRIM(RECIPEITEMTYPECODE) = 'RFD' 
                                                                                    AND RECIPENUMBERID = '$numbidD'");
                                            }else if($numbidR && !($numbidD)){
                                                $query_SRB = db2_exec($conn1, "SELECT TRIM(COMMENTLINE) AS COMMENTLINE, $suhumenit FROM RECIPECOMPONENT 
                                                                                WHERE TRIM(RECIPEITEMTYPECODE) = 'RFD' 
                                                                                    AND RECIPENUMBERID = '$numbidR'");
                                            }else{
                                                $query_SRB = db2_exec($conn1, "SELECT TRIM(COMMENTLINE) AS COMMENTLINE, $suhumenit FROM RECIPECOMPONENT 
                                                                                WHERE TRIM(RECIPEITEMTYPECODE) = 'RFD' 
                                                                                    AND RECIPENUMBERID = '$numbidD' OR RECIPENUMBERID = '$numbidR'");
                                            }
                                            
                                             while($dt_SRB = db2_fetch_assoc($query_SRB)){
                                                if(str_contains($dt_SRB['COMMENTLINE'], 'SOAPING')){
                                                    $soapingSuhu = $dt_SRB['SUHU_SOAPING'];
                                                    $soapingMenit = $dt_SRB['MENIT_SOAPING'];
                                                }
                                                if(str_contains($dt_SRB['COMMENTLINE'], 'RC')){
                                                    $rcSuhu = $dt_SRB['SUHU_RC'];
                                                    $rcMenit = $dt_SRB['MENIT_RC'];
                                                }
                                                if(str_contains($dt_SRB['COMMENTLINE'], 'BLEACHING')){
                                                    $bleachingSuhu = $dt_SRB['SUHU_BLEACHING'];
                                                    $bleachingMenit = $dt_SRB['MENIT_BLEACHING'];
                                                }
                                            }
                                        ?>
                                        <input type="text" class="form-control" required id="soapingSuhu" value="<?= $soapingSuhu; ?>" name="soapingSuhu" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" required id="soapingWaktu" value="<?= $soapingMenit; ?>" name="soapingWaktu" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <!-- RC -->
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for=" RC" class="col-sm-2 control-label" align="left">RC</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" required id="RC_Suhu" name="RC_Suhu" value="<?= $rcSuhu; ?>" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" required id="RCWaktu" name="RCWaktu" value="<?= $rcMenit; ?>" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <!-- Bleaching -->
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for=" RC" class="col-sm-2 control-label" align="left">Bleaching</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="bleaching_sh" value="<?= $bleachingSuhu; ?>" required name="bleaching_sh" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="bleaching_tm" value="<?php if (floatval($data['bleaching_tm']) != 0){ echo floatval($data['bleaching_tm']); }else { echo $bleachingMenit; } ?>" required name="bleaching_tm" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <!-- //RC -->
                            <?php } elseif (substr($sufix, 0, 2) == 'OB') { ?> 
                                <!-- echoing nothing -->
                                <br />
                                <div class="form-group">
                                    <label for="tside_c" class="col-sm-2 control-label">T/C-side :</label>
                                    <div class="col-sm-2">
                                        <?php
                                            $numbidOB = $_GET['OB'];
                                            $query_tSideOB = db2_exec($conn1, "SELECT TRIM(COMMENTLINE) AS COMMENTLINE,
                                                                                CASE
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 3,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 1,3)
                                                                                    ELSE 
                                                                                        SUBSTR(TRIM(COMMENTLINE), 1,2)
                                                                                END AS SUHU,
                                                                                CASE
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '0' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '1' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '2' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '3' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '4' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '5' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '6' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '7' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '8' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    WHEN SUBSTR(TRIM(COMMENTLINE), 10,1) = '9' THEN SUBSTR(TRIM(COMMENTLINE), 9,2)
                                                                                    ELSE
                                                                                        SUBSTR(TRIM(COMMENTLINE), 8,2)
                                                                                END AS MENIT
                                                                                FROM RECIPECOMPONENT 
                                                                                WHERE TRIM(RECIPEITEMTYPECODE) = 'RFD' 
                                                                                        AND RECIPENUMBERID = '$numbidOB' 
                                                                                        AND NOT COMMENTLINE LIKE '%BLEACHING%'
                                                                                        AND NOT COMMENTLINE LIKE '%SOAPING%'
                                                                                        AND NOT COMMENTLINE LIKE '%RC%'
                                                                                        AND NOT COMMENTLINE = ''");
                                            $dt_tSideOB = db2_fetch_assoc($query_tSideOB);
                                        ?>
                                        <input type="text" width="100%" class="form-control" required value="<?= $dt_tSideOB['SUHU']; ?>" name="tside_c" id="tside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" name="tside_min" value="<?= $dt_tSideOB['MENIT']; ?>" id="tside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <p style="font-style: italic; font-weight: bold;">Field Rc and Soaping not avaliable at O+B matching !</p>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label for="tside_c" class="col-sm-2 control-label">T-SIDE :</label>
                                    <div class="col-sm-2">
                                        <input type="text" width="100%" class="form-control" required name="tside_c" id="tside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" name="tside_min" id="tside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cside_c" class="col-sm-2 control-label">C-SIDE :</label>
                                    <div class="col-sm-2">
                                        <input type="text" width="100%" class="form-control" required name="cside_c" id="cside_c" placeholder="C°...">
                                    </div>
                                    <label for="tside_min" style="width: 10px;" class="col-sm-1 control-label"><i class="fa fa-times" aria-hidden="true"></i>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" required class="form-control" name="cside_min" id="cside_min" placeholder="Minute ...">
                                    </div>
                                </div>
                                <!-- SOAPING -->
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">SOAPING</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="soapingSuhu" name="soapingSuhu" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="SOAPING" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="soapingWaktu" name="soapingWaktu" placeholder="Waktu/Menit">
                                        <div class="input-group-addon">Menit</div>
                                    </div>
                                </div>
                                <!-- //SOAPING -->
                                <!-- RC -->
                                <div class="form-group" style="margin-top: 10px; padding: 5px;">
                                    <label for=" RC" class="col-sm-2 control-label" align="left">RC</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="RC_Suhu" name="RC_Suhu" placeholder="Suhu">
                                        <div class="input-group-addon">°C</div>
                                    </div>
                                    <label for="RC" class="col-sm-2 control-label" align="left">-</label>
                                    <div class="input-group col-md-5">
                                        <input type="text" class="form-control" id="RCWaktu" name="RCWaktu" placeholder="Waktu/Menit">
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
                        </div> resep
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
    <div class="modal fade modal-super-scaled" id="DataResepNow" data-backdrop="static" data-keyboard="true" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:75%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Select Recipe From NOW <i class="fa fa-cloud-download" aria-hidden="true"></i></h4>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="col-md-12">
                            <div class="form-group">
                                <table id="Table-sm" class="table table-sm display compact" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Select</th>
                                            <th>Recipe Code</th>
                                            <th>Suffix</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if ($data['jenis_matching'] == "L/D" OR $data['jenis_matching'] == "LD NOW"){
                                                $no_warna = strtoupper($data['no_warna']);
                                                $sql_recipe = db2_exec($conn1, "SELECT 
                                                                                RIGHT(TRIM(SUBCODE01), 1) AS CODE_SUFFIX,	
                                                                                NUMBERID,
                                                                                TRIM( SUBCODE01 ) AS RECIPE_CODE,
                                                                                TRIM( SUFFIXCODE ) AS SUFIX,
                                                                                TRIM( LONGDESCRIPTION ) AS DESKRIPSI 
                                                                            FROM RECIPE WHERE ITEMTYPECODE = 'RFD' AND TRIM(SUFFIXCODE) = '001' AND SUBCODE01 LIKE '%$no_warna%'");
                                            }else{
                                                if (strtoupper(substr($data['idm'], 0,2)) == 'D2' OR strtoupper(substr($data['idm'], 0,2)) == 'R2' OR strtoupper(substr($data['idm'], 0,2)) == 'A2') {
                                                    $sufix_number = substr($data['idm'], 1).'L';
                                                }else if (strtoupper(substr($data['idm'], 0,2)) == 'DR' OR strtoupper(substr($data['idm'], 0,2)) == 'OB' OR strtoupper(substr($data['idm'], 0,2)) == 'CD'){
                                                    $sufix_number = substr($data['idm'], 2).'L';
                                                }else {
                                                    echo "testaa";
                                                    $sufix_number = substr($data['idm'], 2).'L';
                                                }
                                                $sql_recipe = db2_exec($conn1, "SELECT 
                                                                                RIGHT(TRIM(SUBCODE01), 1) AS CODE_SUFFIX,	
                                                                                NUMBERID,
                                                                                TRIM( SUBCODE01 ) AS RECIPE_CODE,
                                                                                TRIM( SUFFIXCODE ) AS SUFIX,
                                                                                TRIM( LONGDESCRIPTION ) AS DESKRIPSI 
                                                                            FROM RECIPE WHERE ITEMTYPECODE = 'RFD' AND TRIM(SUFFIXCODE) = '$sufix_number'");
                                            }
                                            $no = 1; 
                                            while ($r_recipe = db2_fetch_assoc($sql_recipe)) { ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <?php if (substr($sufix, 0, 2) == 'D2' or substr($sufix, 0, 1) == 'C') : ?>
                                                        <?php $link = '&D2CD='.$r_recipe['NUMBERID']; ?>
                                                    <?php elseif (substr($sufix, 0, 1) == 'R' or substr($sufix, 0, 1) == 'A') : ?>
                                                        <?php $link = '&RA='.$r_recipe['NUMBERID']; ?>
                                                    <?php elseif(substr($sufix, 0, 2) == 'DR') : ?>
                                                        <?php 
                                                            if($_GET['D']){ 
                                                                $link = '&D='.$_GET['D'].'&'.$r_recipe['CODE_SUFFIX'].'='.$r_recipe['NUMBERID'];
                                                            } else if($_GET['R']) { 
                                                                $link = '&R='.$_GET['R'].'&'.$r_recipe['CODE_SUFFIX'].'='.$r_recipe['NUMBERID'];
                                                            } else {
                                                                $link = '&'.$r_recipe['CODE_SUFFIX'].'='.$r_recipe['NUMBERID'];
                                                            }
                                                        ?>
                                                    <?php elseif(substr($sufix, 0, 2) == 'OB') : ?>
                                                        <?php $link = '&OB='.$r_recipe['NUMBERID']; ?>
                                                    <?php else : ?>
                                                        <?php $link = ''; ?>
                                                    <?php endif; ?>
                                                    <td><a href='?p=Status-Handle-NOW&idm=<?= $_GET['idm']; ?><?= $link; ?>
                                                    '><i class="fa fa-check-square-o"></i></a></td>
                                                    <td><?= $r_recipe['RECIPE_CODE']; ?></td>
                                                    <td><?= $r_recipe['SUFIX']; ?></td>
                                                    <td><?= $r_recipe['DESKRIPSI']; ?></td>
                                                </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
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
                text: "Untuk Hold Resep dengan R-code : <?php echo $sufix ?>!",
                icon: 'warning',
                allowOutsideClick: false,
                showCancelButton: true,
                confirmButtonColor: '#5cb85c',
                cancelButtonColor: '#292b2c',
                confirmButtonText: 'Yes, Hold <?php echo $sufix ?>'
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
            Update_StatusMatching_ToHold($("#id_matching").val(), $("#id_status").val(), $("#idm").val(), $('#Matching-ke').val(), $('#BENANG-A').val(), $("#LEBAR-A").val(), $("#GRAMASI-A").val(), $("#L_R").find('option:selected').val(), $("#kadar_air").val(), RC_Suhu, RCWaktu, soapingSuhu, soapingWaktu, $("#CIE_WI").val(), $("#CIE_TINT").val(), $("#Spektro_R").val(), $("#Done_Matching").val(), $("#keterangan").val(), $("#tgl_buat_status").val(), tside_c, tside_min, cside_c, cside_min, $('#kadar_air_true').val(), $('#CocokWarna').val(),
                $("#f_matcher").find('option:selected').val(), $("#koreksi").find('option:selected').val(), $("#colorist_1").find('option:selected').val(),
                $("#colorist_2").find('option:selected').val(), $("#Proses").find('option:selected').val(), $("#item").val(), $("#recipe_code").val(), $('#no_warna').val(), $('#warna').val(), $('#Kain').val(), $('#Benang').val(), $('#Lebar').val(), $('#Gramasi').val(), $('#Tgl_delivery ').val(), $('#Order').val(), $('#po_greige').val(), $('#QtyOrder').val(), $('#Matcher').find('option:selected').val(), $('#Group').find('option:selected').val(), $("#Buyer").find('option:selected').val(), bleaching_sh, bleaching_tm, $('#second_lr').find(':selected').val())
        }

        function Update_StatusMatching_ToHold(id_matching, id_status, idm, matching_ke, benang_a, lebar_a, gramasi_a, l_R, kadar_air, RC_Suhu, RCWaktu, soapingSuhu, soapingWaktu, cie_wi, cie_tint, Spektro_R, Done_Matching, keterangan, tgl_buat_status, tside_c, tside_min, cside_c, cside_min, kadar_air_true, cocok_warna, final_matcher, koreksi_resep, colorist1, colorist2, proses, item, recipe_code, no_warna, warna, Kain, Benang, Lebar, Gramasi, Tgl_delivery, Order, po_greige, QtyOrder, Matcher, Group, Buyer, bleaching_sh, bleaching_tm, second_lr) {
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
                    cside_min: cside_min,
                    kadar_air_true: kadar_air_true,
                    cocok_warna: cocok_warna,
                    final_matcher: final_matcher,
                    koreksi_resep: koreksi_resep,
                    colorist1: colorist1,
                    colorist2: colorist2,
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
            var count = $("#lookupmodal1_now tbody tr").length;
            var id_matching = $("#id_matching").val();
            var id_status = $("#id_status").val();
            if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 1) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 2) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 3) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 4) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 5) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 6) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 7) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 8) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 9) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
                    if (parseInt(index + 1) == count) {
                        SpinnerHide();
                    } else {
                        console.log(parseInt(index + 1))
                    }
                });
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 10) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
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
        $("#lookupmodal1_now").DataTable({
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
                var count = $("#lookupmodal1_now tbody tr").length;
                if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 1) {
                    $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        if (code){
                            var code = $(this).find('td:eq(1)').find('option:selected').val();
                        }else{
                            var code = $(this).find('td:eq(2) input').val();
                        }
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
                } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 2) {
                    $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        if (code){
                            var code = $(this).find('td:eq(1)').find('option:selected').val();
                        }else{
                            var code = $(this).find('td:eq(2) input').val();
                        }
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
                } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 3) {
                    $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        if (code){
                            var code = $(this).find('td:eq(1)').find('option:selected').val();
                        }else{
                            var code = $(this).find('td:eq(2) input').val();
                        }
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
                } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 4) {
                    $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        if (code){
                            var code = $(this).find('td:eq(1)').find('option:selected').val();
                        }else{
                            var code = $(this).find('td:eq(2) input').val();
                        }
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
                } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 5) {
                    $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        if (code){
                            var code = $(this).find('td:eq(1)').find('option:selected').val();
                        }else{
                            var code = $(this).find('td:eq(2) input').val();
                        }
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
                } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 6) {
                    $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        if (code){
                            var code = $(this).find('td:eq(1)').find('option:selected').val();
                        }else{
                            var code = $(this).find('td:eq(2) input').val();
                        }
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
                } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 7) {
                    $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        if (code){
                            var code = $(this).find('td:eq(1)').find('option:selected').val();
                        }else{
                            var code = $(this).find('td:eq(2) input').val();
                        }
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
                } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 8) {
                    $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        if (code){
                            var code = $(this).find('td:eq(1)').find('option:selected').val();
                        }else{
                            var code = $(this).find('td:eq(2) input').val();
                        }
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
                } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 9) {
                    $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        if (code){
                            var code = $(this).find('td:eq(1)').find('option:selected').val();
                        }else{
                            var code = $(this).find('td:eq(2) input').val();
                        }
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
                } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 10) {
                    $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                        if (code){
                            var code = $(this).find('td:eq(1)').find('option:selected').val();
                        }else{
                            var code = $(this).find('td:eq(2) input').val();
                        }
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
            insertInto_StatusMatching_DetailMatching($("#id_matching").val(), $("#id_status").val(), $("#idm").val(), $('#Matching-ke').val(), $('#BENANG-A').val(), $("#LEBAR-A").val(), $("#GRAMASI-A").val(), $("#L_R").find('option:selected').val(), $("#kadar_air").val(), RC_Suhu, RCWaktu, soapingSuhu, soapingWaktu, $("#CIE_WI").val(), $("#CIE_TINT").val(), $("#Spektro_R").val(), $("#Done_Matching").val(), $("#keterangan").val(), $("#tgl_buat_status").val(), cside_c, cside_min, tside_c, tside_min, $('#kadar_air_true').val(), $('#CocokWarna').val(),
                $("#f_matcher").find('option:selected').val(), $("#koreksi").find('option:selected').val(), $("#colorist_1").find('option:selected').val(),
                $("#colorist_2").find('option:selected').val(), $("#Proses").find('option:selected').val(), $("#item").val(), $("#recipe_code").val(), $('#no_warna').val(), $('#warna').val(), $('#Kain').val(), $('#Benang').val(), $('#Lebar').val(), $('#Gramasi').val(), $('#Tgl_delivery').val(), $('#Order').val(), $('#po_greige').val(), $('#QtyOrder').val(), $('#Matcher').find('option:selected').val(), $('#Group').find('option:selected').val(), $("#Buyer").find('option:selected').val(), bleaching_sh, bleaching_tm, $('#second_lr').find(':selected').val())
        }

        function insertInto_StatusMatching_DetailMatching(id_matching, id_status, idm, matching_ke, benang_a, lebar_a, gramasi_a, l_R, kadar_air, RC_Suhu, RCWaktu, soapingSuhu, soapingWaktu, cie_wi, cie_tint, Spektro_R, Done_Matching, keterangan, tgl_buat_status, cside_c, cside_min, tside_c, tside_min, kadar_air_true, cocok_warna, final_matcher, koreksi_resep, colorist1, colorist2, proses, item, recipe_code, no_warna, warna, Kain, Benang, Lebar, Gramasi, Tgl_delivery, Order, po_greige, QtyOrder, Matcher, Group, Buyer, bleaching_sh, bleaching_tm, second_lr) {
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
                    tside_min: tside_min,
                    kadar_air_true: kadar_air_true,
                    cocok_warna: cocok_warna,
                    final_matcher: final_matcher,
                    koreksi_resep: koreksi_resep,
                    colorist1: colorist1,
                    colorist2: colorist2,
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
            if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 1) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    if (code){
                        var code = $(this).find('td:eq(1)').find('option:selected').val(); 
                    }else{
                        var code = $(this).find('td:eq(2) input').val(); //NOW
                    }
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
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 2) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    if (code){
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                    }else{
                        var code = $(this).find('td:eq(2) input').val();
                    }
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
                    var conc1 = $(this).find('td:eq(5) input').val();
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
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 3) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    if (code){
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                    }else{
                        var code = $(this).find('td:eq(2) input').val();
                    }
                    var desc_code = $(this).find('td:eq(3) input').val();
                    var conc = $(this).find('td:eq(4) input').val();
                    var conc1 = $(this).find('td:eq(5) input').val();
                    var conc2 = $(this).find('td:eq(6) input').val();
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
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 4) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    if (code){
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                    }else{
                        var code = $(this).find('td:eq(2) input').val();
                    }
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
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 5) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    if (code){
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                    }else{
                        var code = $(this).find('td:eq(2) input').val();
                    }
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
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 6) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    if (code){
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                    }else{
                        var code = $(this).find('td:eq(2) input').val();
                    }
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
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 7) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    if (code){
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                    }else{
                        var code = $(this).find('td:eq(2) input').val();
                    }
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
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 8) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    if (code){
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                    }else{
                        var code = $(this).find('td:eq(2) input').val();
                    }
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
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 9) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    if (code){
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                    }else{
                        var code = $(this).find('td:eq(2) input').val();
                    }
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
            } else if ($("#lookupmodal1_now thead tr th:last").prev().attr('flag_th') == 10) {
                $('#lookupmodal1_now tbody tr').each(function(index, tr) {
                    var flag = $(this).find('td:eq(0)').text();
                    var id_matching = $("#id_matching").val();
                    var id_status = $("#id_status").val();
                    var code = $(this).find('td:eq(1)').find('option:selected').val();
                    if (code){
                        var code = $(this).find('td:eq(1)').find('option:selected').val();
                    }else{
                        var code = $(this).find('td:eq(2) input').val();
                    }
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
                    $(getTr).find("td:eq(3)").find('input').val(response);
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
        $('#lookupmodal1_now').on("keydown", function(e) {
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