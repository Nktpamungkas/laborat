<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Form Cycle Time</title>
</head>
<!-- style tabel checklist -->
<style>
    #Table-sm td,
    #Table-sm th {
        border: 1px solid #ddd;
        vertical-align: middle;
        text-align: center;
    }

    #Table-sm th {
        color: black;
        background: #4CAF50;
    }

    .lookupST td,
    .lookupST th {
        border: 1px solid black;
        padding: 2px;
    }


    .lookupST th {
        padding-top: 5px;
        padding-bottom: 5px;
        text-align: center;
        background-color: #4CAF50;
        color: white;
    }
</style>
<!-- style tabel sm ct -->
<style>
    td.details-control {
        background: url('bower_components/DataTable/img/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('bower_components/DataTable/img/details_close.png') no-repeat center center;
    }

    th {
        font-size: 10pt;
    }

    td {
        font-size: 10pt;
    }

    #Table-sm-ct td,
    #Table-sm-ct th {
        border: 0.1px solid #ddd;
        vertical-align: middle;
        text-align: center;
    }

    #Table-sm-ct th {
        color: black;
        background: #4CAF50;
    }

    #Table-sm-ct tr:hover {
        background-color: rgb(151, 170, 212);
    }

    #Table-sm-ct>thead>tr>td {
        border: 1px solid #ddd;
    }
</style>
<?php
    require_once 'koneksi.php';
    $dataMainCycletime = mysqli_query($con, "SELECT * FROM tbl_cycletime WHERE id = '$_GET[id]'");
    $rowMainCycletime = mysqli_fetch_assoc($dataMainCycletime);

    if ($_GET['status'] == 'Normal') {
        $andstatus = "AND `status` = 'Normal'";
    } else {
        $andstatus = "AND `status` = 'Urgent'";
    }

    $dataMainCycletime_detail = mysqli_query($con, "SELECT * FROM tbl_cycletime_detail WHERE id_cycletime = '$_GET[id]' $andstatus ORDER BY id DESC LIMIT 1");
    $rowMainCycletime_detail = mysqli_fetch_assoc($dataMainCycletime_detail);
?>
<input type="hidden" value="<?= $rowMainCycletime_detail['start_number']; ?>" id="start_number">
<input type="hidden" value="<?= ($rowMainCycletime_detail['end_number'] == 0) ? $rowMainCycletime_detail['start_number'] : ($rowMainCycletime_detail['end_number']); ?>" id="end_number">

<body>
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li><a href="#tab_1" data-toggle="tab">Detail Cycle Time</a></li>
                    <li class="active"><a href="#tab_2" data-toggle="tab">Input Cyle Time</a></li>
                    <li class="pull-right">
                        <?php 
                            if($rowMainCycletime_detail){
                                echo '<button type="button" class="btn btn-block btn-social btn-linkedin" id="saveButton">Simpan <i class="fa fa-save"></i></button>';
                            }else {
                                echo '<button type="button" class="btn btn-block btn-social btn-linkedin" id="saveButton_open">Simpan <i class="fa fa-save"></i></button>';
                            }
                        ?>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="tab_1">
                        <form class="form-horizontal">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="order" class="col-sm-2 control-label">Group Matching</label>
                                    <div class="col-sm-2">
                                        <select type="text" class="form-control" name="grp_matching" id="grp_matching" required>
                                            <option value="" selected disabled>Pilih...</option>
                                            <?php
                                            $dataGrpMatching = mysqli_query($con, "SELECT
                                                                                    a.grp,
                                                                                    COUNT(a.grp) AS jumlahdata
                                                                                FROM
                                                                                    tbl_status_matching a
                                                                                    JOIN tbl_matching b ON a.idm = b.no_resep 
                                                                                WHERE
                                                                                    a.STATUS IN ( 'buka', 'mulai', 'hold', 'revisi', 'tunggu' ) 
                                                                                GROUP BY
                                                                                    a.grp 
                                                                                ORDER BY
                                                                                    a.grp ASC");
                                            while ($rowGrpMatching = mysqli_fetch_array($dataGrpMatching)) {
                                            ?>
                                                <option value="<?= $rowGrpMatching['grp'] ?>" <?php if ($rowGrpMatching['grp'] == $rowMainCycletime['group_matching']) {
                                                                                                    echo "SELECTED";
                                                                                                } ?>><?= $rowGrpMatching['grp'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="order" class="col-sm-2 control-label">Shift</label>
                                    <div class="col-sm-2">
                                        <select type="text" class="form-control" name="shift" id="shift" required>
                                            <option value="" selected disabled>Pilih...</option>
                                            <option value="1" <?php if ('1' == $rowMainCycletime['shift']) {
                                                                    echo "SELECTED";
                                                                } ?>>Shift 1</option>
                                            <option value="2" <?php if ('2' == $rowMainCycletime['shift']) {
                                                                    echo "SELECTED";
                                                                } ?>>Shift 2</option>
                                            <option value="3" <?php if ('3' == $rowMainCycletime['shift']) {
                                                                    echo "SELECTED";
                                                                } ?>>Shift 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="order" class="col-sm-2 control-label">Nama Matcher</label>
                                    <div class="col-sm-2">
                                        <select type="text" class="form-control selectMatcher" name="nama_matcher" id="nama_matcher" required>
                                            <option value="" selected disabled>Pilih...</option>
                                            <?php
                                            $dataMatcher = mysqli_query($con, "SELECT * FROM tbl_matcher WHERE status = 'Aktif' ORDER BY nama ASC");
                                            while ($rowMatcher = mysqli_fetch_array($dataMatcher)) {
                                            ?>
                                                <option value="<?= $rowMatcher['nama'] ?>" <?php if ($rowMatcher['nama'] == $rowMainCycletime['nama_matcher']) {
                                                                                                echo "SELECTED";
                                                                                            } ?>><?= $rowMatcher['nama'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="col-sm-2">
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                                    <table id="Table-sm-ct" class="table table-sm-ct display compact" style="width: 100%;">
                                        <thead>
                                            <tr class="alert-success" style="border: 1px solid #ddd;">
                                                <th style="border: 1px solid #ddd;">#</th>
                                                <th style="border: 1px solid #ddd;">Stts</th>
                                                <th style="border: 1px solid #ddd;">Ket.St</th>
                                                <th style="border: 1px solid #ddd;">Grp</th>
                                                <th style="border: 1px solid #ddd;">Matcher</th>
                                                <th style="border: 1px solid #ddd;">Rcode</th>
                                                <th style="border: 1px solid #ddd;">No.Order</th>
                                                <th style="border: 1px solid #ddd;">Langganan</th>
                                                <th style="border: 1px solid #ddd;">Warna</th>
                                                <th style="border: 1px solid #ddd;">No.Warna</th>
                                                <th style="border: 1px solid #ddd;">Jenis Kain</th>
                                                <th style="border: 1px solid #ddd;">No.Item</th>
                                                <th style="border: 1px solid #ddd;">timer</th>
                                                <th style="border: 1px solid #ddd;">tgl_buat</th>
                                                <th style="border: 1px solid #ddd;">tgl_mulai</th>
                                                <th style="border: 1px solid #ddd;">created_by</th>
                                                <th style="border: 1px solid #ddd;">status_created_by</th>
                                                <th style="border: 1px solid #ddd;">tgl_selesai</th>
                                                <th style="border: 1px solid #ddd;">jenis_matching</th>
                                                <th style="border: 1px solid #ddd;">no_po</th>
                                                <th style="border: 1px solid #ddd;">jenis_kain</th>
                                                <th style="border: 1px solid #ddd;">benang</th>
                                                <th style="border: 1px solid #ddd;">lebar</th>
                                                <th style="border: 1px solid #ddd;">gramasi</th>
                                                <th style="border: 1px solid #ddd;">lebara</th>
                                                <th style="border: 1px solid #ddd;">gramasia</th>
                                                <th style="border: 1px solid #ddd;">cek_warna</th>
                                                <th style="border: 1px solid #ddd;">cek_dye</th>
                                                <th style="border: 1px solid #ddd;">koreksi_resep</th>
                                                <th style="border: 1px solid #ddd;">cocok_warna</th>
                                                <th style="border: 1px solid #ddd;">qty_order</th>
                                                <th style="border: 1px solid #ddd;">tgl_delivery</th>
                                                <th style="border: 1px solid #ddd;">tgl_in</th>
                                                <th style="border: 1px solid #ddd;">tgl_out</th>
                                                <th style="border: 1px solid #ddd;">ket</th>
                                                <th style="border: 1px solid #ddd;">Lampu</th>
                                                <th style="border: 1px solid #ddd;">Proses</th>
                                                <th style="border: 1px solid #ddd;">id_status</th>
                                                <th style="border: 1px solid #ddd;">Handle</th>
                                                <th style="border: 1px solid #ddd;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = mysqli_query($con, "SELECT
                                                                        *,
                                                                        a.id AS id_status,
                                                                        a.created_at AS tgl_buat_status,
                                                                        a.created_by AS status_created_by 
                                                                    FROM
                                                                        tbl_status_matching a
                                                                        JOIN tbl_matching b ON a.idm = b.no_resep 
                                                                    WHERE
                                                                        a.STATUS IN ( 'buka', 'mulai', 'hold', 'revisi', 'tunggu' ) 
                                                                        AND a.grp = '$rowMainCycletime[group_matching]' 
                                                                        AND a.kt_status = '$_GET[status]'
                                                                    GROUP BY
                                                                        a.idm,
                                                                        b.no_resep 
                                                                    ORDER BY
                                                                        a.id DESC");
                                            while ($r = mysqli_fetch_array($sql)) {
                                                $no++;
                                                $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
                                            ?>
                                                <tr>
                                                    <td valign="center" class="details-control">
                                                        <!-- plush icon here -->
                                                    </td>
                                                    <td valign="center" align="center">
                                                        <span class="label
                                                                    <?php if ($r['status'] == "buka" and $r['tgl_mulai'] == "") {
                                                                        echo "label-warning";
                                                                    } elseif ($r['status'] == "mulai") {
                                                                        echo "label-info";
                                                                    } elseif ($r['status'] == "hold") {
                                                                        echo "bg-purple";
                                                                    } elseif ($r['status'] == "batal") {
                                                                        echo "label-danger";
                                                                    } elseif ($r['status'] == "selesai") {
                                                                        echo "label-danger blink_me";
                                                                    } elseif ($r['status'] == "buka" and $r['tgl_mulai'] != "") {
                                                                        echo "label-success";
                                                                    } else {
                                                                        echo "label-default";
                                                                    } ?>"> <?php echo $r['status'] ?>
                                                        </span>

                                                        <hr class="divider"> <span <?php if ($r['status'] == 'batal') echo "style='display: none;'" ?> class="label 
                                                    <?php if ($r['kt_status'] == "Urgent") {
                                                        echo "label-warning blink_me";
                                                    } else {
                                                        echo "label-success blink_me";
                                                    } ?>">
                                                            <?php echo $r['kt_status']; ?></span>
                                                    </td>
                                                    <td valign="center" align="center"><span class="label 
                                                    <?php if ($r['kt_status'] == "Urgent") {
                                                        echo "label-warning blink_me";
                                                    } else {
                                                        echo "label-success";
                                                    } ?>">
                                                            <?php echo $r['kt_status']; ?></span>
                                                    </td>
                                                    <td valign="center">
                                                        <?php echo $r['grp']; ?>
                                                    </td>
                                                    <td valign="center">
                                                        <?php echo $r['matcher']; ?>
                                                    </td>
                                                    <td valign="center">
                                                        <?php echo $r['idm']; ?>
                                                    </td>
                                                    <td valign="center">
                                                        <?php echo $r['no_order']; ?>
                                                    </td>
                                                    <td valign="center" align="left">
                                                        <?php echo $r['langganan']; ?></td>
                                                    <td valign="center">
                                                        <?php echo $r['warna']; ?>
                                                    </td>
                                                    <td valign="center">
                                                        <?php echo $r['no_warna']; ?>
                                                    </td>
                                                    <td valign="center">
                                                        <?php echo $r['jenis_kain']; ?>
                                                    </td>
                                                    <td valign="center">
                                                        <?php echo $r['no_item']; ?>
                                                    </td>
                                                    <td valign="center" align="center">
                                                        <?php
                                                        $awal  = strtotime($r['tgl_buat_status']);
                                                        $akhir = strtotime(date('Y-m-d H:i:s'));
                                                        $diff  = $akhir - $awal;

                                                        $hari  = floor($diff / (60 * 60 * 24));
                                                        $jam   = floor(($diff - ($hari * (60 * 60 * 24))) / (60 * 60));
                                                        $menit = ($diff - ($hari * (60 * 60 * 24))) - (($jam) * (60 * 60));

                                                        echo "<span>" . $hari . " Hari</span> : <span>" . $jam . " Jam</span> : <span>" . floor($menit / 60) . " Menit</span>";
                                                        ?>

                                                    </td>
                                                    <td valign="center" class="13"><?php echo $r['tgl_buat'] ?></td>
                                                    <td class="14"><?php echo $r['tgl_buat_status'] ?></td>
                                                    <td class="15"><?php echo $r['created_by'] ?></td>
                                                    <td class="16"><?php echo $r['status_created_by'] ?></td>
                                                    <td class="17"><?php echo $r['tgl_selesai'] ?></td>
                                                    <td class="18"><?php echo $r['jenis_matching'] ?></td>
                                                    <td class="19"><?php echo $r['no_po'] ?></td>
                                                    <td class="20"><?php echo $r['jenis_kain'] ?></td>
                                                    <td class="21"><?php echo $r['benang'] ?></td>
                                                    <td class="22"><?php echo $r['lebar'] ?></td>
                                                    <td class="23"><?php echo $r['gramasi'] ?></td>
                                                    <td class="24"><?php echo floatval($r['lebar_aktual']) ?></td>
                                                    <td class="25"><?php echo floatval($r['gramasi_aktual']) ?></td>
                                                    <td class="26"><?php echo $r['cek_warna'] ?></td>
                                                    <td class="27"><?php echo $r['cek_dye'] ?></td>
                                                    <td class="28"><?php echo $r['koreksi_resep'] ?></td>
                                                    <td class="29"><?php echo $r['cocok_warna'] ?></td>
                                                    <td class="30"><?php echo $r['qty_order'] ?></td>
                                                    <td class="31"><?php echo $r['tgl_delivery'] ?></td>
                                                    <td class="32"><?php echo $r['tgl_in'] ?></td>
                                                    <td class="33"><?php echo $r['tgl_out'] ?></td>
                                                    <td class="34"><?php echo $r['ket'] ?></td>
                                                    <td class="35"><?php
                                                                    if ($r['ck_d65'] == 1) {
                                                                        echo 'd65 - ';
                                                                    }
                                                                    if ($r['ck_f02'] == 1) {
                                                                        echo 'f02 - ';
                                                                    }
                                                                    if ($r['ck_f11'] == 1) {
                                                                        echo 'f11 - ';
                                                                    }
                                                                    if ($r['ck_u35'] == 1) {
                                                                        echo 'u35 - ';
                                                                    }
                                                                    if ($r['ck_a'] == 1) {
                                                                        echo 'A - ';
                                                                    }
                                                                    if ($r['ck_rlight'] == 1) {
                                                                        echo 'rlight - ';
                                                                    }
                                                                    if ($r['ck_tl83'] == 1) {
                                                                        echo 'tl83 - ';
                                                                    }
                                                                    ?></td>
                                                    <td class="36"><?php
                                                                    if ($r['ck_greige'] == 1) {
                                                                        echo 'Greige - ';
                                                                    }
                                                                    if ($r['ck_bleaching'] == 1) {
                                                                        echo 'Bleaching Lab - ';
                                                                    }
                                                                    if ($r['ck_bleaching_dye'] == 1) {
                                                                        echo 'Bleaching Dye - ';
                                                                    }
                                                                    if ($r['ck_preset'] == 1) {
                                                                        echo 'Preset - ';
                                                                    }
                                                                    if ($r['ck_npreset'] == 1) {
                                                                        echo 'Non Preset - ';
                                                                    }
                                                                    if ($r['ck_nh2o2'] == 1) {
                                                                        echo 'Non h2o2 - ';
                                                                    }
                                                                    if ($r['ck_tarik'] == 1) {
                                                                        echo 'Peach - ';
                                                                    }
                                                                    ?>
                                                    </td>
                                                    <td class="37"><?php echo $r['id_status'] ?></td>
                                                    <?php if ($r['status'] == 'batal') { ?>
                                                        <td class="38"><span class="btn bg-black btn-sm blink_me"><i class="fa fa-ban"></i>BATAL</span></td>
                                                    <?php } else if ($r['status'] == 'tunggu') { ?>
                                                        <td class="38">
                                                            <li style="font-weight: bold; color: black;"><a href="#" class="btn btn-xs btn-primary _lanjutkan" attribute="<?php echo $r['id_status'] ?>" codem="<?php echo $r['idm'] ?>">Lanjutkan <i class="fa fa-play" aria-hidden="true"></i>
                                                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                                </a>
                                                            </li>
                                                            <br>
                                                            <?php $sqlWait = mysqli_query($con, "SELECT max(id) as maxid, `info` from log_status_matching where ids = '$r[id_status]'");
                                                            $Wait = mysqli_fetch_array($sqlWait);
                                                            echo '<span class="badge">' . $Wait['info'] . '</span>';
                                                            ?>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td class="38">
                                                            <div class="btn-group-vertical">
                                                                <a style="color: black;" target="_blank" href="pages/cetak/matching.php?idkk=<?php echo $r['idm'] ?>" class="btn btn-xs btn-warning">Print ! &nbsp;<i class="fa fa-print"></i></a>
                                                                <?php if ($r['status'] == 'hold' or $r['status'] == 'revisi') { ?>
                                                                    <a href="?p=Hold-Handle&idm=<?php echo $r['id_status'] ?>" class="btn btn-xs bg-purple">Lanjut <i class="fa fa-edit"></i></a>
                                                                <?php } else { ?></php>
                                                                    <a style="color: white;" href="?p=Status-Handle&idm=<?php echo $r['id_status'] ?>" class="btn btn-xs btn-success">Resep! <i class="fa fa-pencil"></i></a>
                                                                    <a href="#" class="btn btn-xs btn-danger _ketstatus" value="<?= $r['kt_status'] ?>" attribute="<?= $r['id_status'] ?>" codem="<?= $r['idm'] ?>">Ket. Status <i class="fa fa-exchange" aria-hidden="true"></i>
                                                                <?php } ?>
                                                                    <a href="#" class="btn btn-xs btn-info _tunggu" attribute="<?php echo $r['id_status'] ?>" codem="<?php echo $r['idm'] ?>">Tunggu <i class="fa fa-clock-o" aria-hidden="true"></i></a>
                                                            </div>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="39"><?php echo $r['status'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane active" id="tab_2">
                        <div class="row">
                            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
                                <div class="box-body">
                                    <div class=" form-group">
                                        <label for="order" class="col-sm-2 control-label">Status</label>
                                        <div class="col-sm-2">
                                            <input type="hidden" value="<?= $rowMainCycletime_detail['id']; ?>" id="id">
                                            <input type="hidden" value="<?= $_GET['id']; ?>" id="id_cycletime">
                                            <select type="text" class="form-control" name="status" id="status" required>
                                                <option value="" selected disabled>Pilih...</option>
                                                <option value="Normal" <?php if ($_GET['status'] == 'Normal') {
                                                                            echo "SELECTED";
                                                                        } ?>>Normal</option>
                                                <option value="Urgent" <?php if ($_GET['status'] == 'Urgent') {
                                                                            echo "SELECTED";
                                                                        } ?>>Urgent</option>
                                            </select>
                                        </div>
                                        <?php 
                                            if($rowMainCycletime_detail){
                                                echo '<input type="hidden" value="EndProses" id="prosesCycleTime">';
                                                echo '<button class="btn btn-xs" style="background-color: Red; color: white; margin-bottom: 10px;">End Proses </button>';
                                            }else {
                                                echo '<input type="hidden" value="StartProses" id="prosesCycleTime">';
                                                echo '<button class="btn btn-xs" style="background-color: Green; color: white; margin-bottom: 10px;">Start Proses </button>';
                                            }
                                        ?>
                                    </div>
                                    <div class=" form-group">
                                        <label for="order" class="col-sm-2 control-label">...</label>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-social" id="resetButton">Reset Checkbox <i class="fa fa-refresh"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table id="lookupmodal1" class="lookupST display nowrap" width="50%" style="padding-right: 16px;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>#</th>
                                                    <th>No Cycle</th>
                                                    <th>Keterangan</th>
                                                    <th>Point</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $dataMasterCycletime      = "SELECT * FROM master_cycletime ORDER BY id ASC";
                                                $resultMasterCycletime    = mysqli_query($con, $dataMasterCycletime);
                                                $rowCount = 0;  
                                                $colors = ['#b4c6e7', '#d0e4f7', '#f7e4e4'];

                                                while ($rowMasterCycletime = mysqli_fetch_array($resultMasterCycletime)) {
                                                    $bgColor = $colors[intval($rowCount / 8) % 8];
                                                ?>
                                                    <tr style="background-color: <?= $bgColor; ?>; color: black;">
                                                        <td align="Center"><?= $rowMasterCycletime['id']; ?></td>
                                                        <td align="Center">
                                                            <input type="checkbox" class="row-checkbox" data-id="<?= $rowMasterCycletime['id']; ?>" data-point="<?= $rowMasterCycletime['point']; ?>">
                                                        </td>
                                                        <td align="Center"><?= $rowMasterCycletime['no']; ?></td>
                                                        <td><?= $rowMasterCycletime['keterangan']; ?></td>
                                                        <td align="Center"><?= $rowMasterCycletime['point']; ?></td>
                                                    </tr>
                                                <?php $rowCount++; } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="summary-row">
                                                    <td colspan="4" align="right">Total Point:</td>
                                                    <td id="totalPoints" align="Center">0</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-3d-slit" id="confirmationModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Konfirmasi !</h4>
                </div>
                <div class="modal-body">
                    <span style="font-size: 15px;">Jika Anda menutup cycletime ini, data akan dipindahkan ke arsip.</span>
                    <br><br>
                    <button type="button" class="btn btn-danger" id="closeCycleTime">Tutup cycletime ini</button>
                    <button type="button" class="btn btn-secondary" id="keepOpen">Tidak, biarkan cycletime tetap terbuka untuk pengisian data yang urgent</button>
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
<script>
    $(document).ready(function() {
        $("#lookupmodal1").DataTable({
            ordering: false,
            searching: false,
            "lengthChange": false,
            "paging": false,
            "bInfo": false,
            // responsive: true
            // "scrollX": true
        })

        $('#grp_matching').prop("disabled", true);
        $('#shift').prop("disabled", true);
        $('#nama_matcher').prop("disabled", true);
        $('#status').prop("disabled", true);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const totalPointsDisplay = document.getElementById('totalPoints');
        const resetButton = document.getElementById('resetButton');
        const changeStatus = document.getElementById('grp_matching');
        let firstChecked = null;
        let lastChecked = null;
        let startnumber = document.getElementById('start_number').value - 1;
        let endnumber = document.getElementById('end_number').value - 1;

        // Fungsi untuk mengatur checkbox berdasarkan data dari database
        function setInitialCheckedStates() {
            checkboxes.forEach((checkbox, index) => {
                // yg di select 2 - 5. tapi 2 jadi 1 & 5 jadi 4 
                if (index >= startnumber && index <= endnumber) {
                    checkbox.checked = true; // Centang checkbox
                }
            });
            updateTotalPoints(); // Hitung total poin setelah mengatur checkbox yang dicentang
        }

        // Panggil fungsi setInitialCheckedStates saat halaman dimuat
        setInitialCheckedStates();

        // Event listener untuk klik checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('click', function() {
                if (!firstChecked) {
                    firstChecked = this;
                }

                if (firstChecked && this === firstChecked) {
                    lastChecked = null; // Reset jika checkbox yang sama diklik
                    return;
                }

                lastChecked = this;

                let start = Array.from(checkboxes).indexOf(firstChecked);
                let end = Array.from(checkboxes).indexOf(lastChecked);

                if (start > end) {
                    [start, end] = [end, start];
                }

                checkboxes.forEach((cb, index) => {
                    if (index >= start && index <= end) {
                        cb.checked = true; // Centang semua checkbox di antara
                    }
                });

                firstChecked = null; // Reset firstChecked
                lastChecked = null; // Reset lastChecked

                updateTotalPoints();
            });
        });

        // Event listener untuk perubahan pada checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotalPoints);
        });

        // Fungsi untuk mengupdate total poin
        function updateTotalPoints() {
            let totalPoints = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    totalPoints += parseFloat(checkbox.getAttribute('data-point'));
                }
            });
            totalPointsDisplay.textContent = totalPoints.toFixed(1);
        }

        // Event listener untuk tombol reset
        resetButton.addEventListener('click', function() {
            checkboxes.forEach(checkbox => {
                let firstChecked = null;
                let lastChecked = null;
                checkbox.checked = false;
            });
            updateTotalPoints(); // Reset total points to 0
        });
    });

    $('#saveButton_open').click(function(e) {
        e.preventDefault();
        Preparation_insert_cycletime("Open");
    });
    
    $('#saveButton').click(function(e) {
        e.preventDefault();
        $('#confirmationModal').modal('show');
    });

    $('#closeCycleTime').click(function() {
        $('#confirmationModal').modal('hide');
        // Panggil fungsi untuk menyimpan data dengan status "Closed"
        Preparation_insert_cycletime("Closed");
    });

    $('#keepOpen').click(function() {
        $('#confirmationModal').modal('hide');
        // Panggil fungsi untuk menyimpan data dengan status "Open"
        Preparation_insert_cycletime("Open");
    });

    function Preparation_insert_cycletime(statusCycleTime) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        var status = document.getElementById('status').value;
        var proses_CycleTime = document.getElementById('prosesCycleTime').value;
        var id = document.getElementById('id').value;
        var idcycletime = document.getElementById('id_cycletime').value;
        let startNumber = null;
        let endNumber = null;
        let totalPoint = 0;
        let checkedCount = 0; // Variabel untuk menghitung jumlah checkbox yang dicentang

        checkboxes.forEach((checkbox, index) => {
            if (checkbox.checked) {
                checkedCount++; // Menambahkan jumlah checkbox yang dicentang
                if (startNumber === null) {
                    startNumber = index + 1; // Menyimpan nomor baris pertama yang dicentang
                }
                endNumber = index + 1; // Update endNumber ke indeks checkbox yang dicentang terakhir
                totalPoint += parseFloat(checkbox.dataset.point);
            }
        });

        // Jika hanya satu checkbox yang dicentang, set endNumber menjadi null
        if (checkedCount === 1) {
            endNumber = null;
        }

        if (status === null || status.trim() === '') {
            toastr.error('Status wajib diisi!');
            return false;
        } else {
            if (proses_CycleTime == 'StartProses' && startNumber == null) {
                toastr.error('Harap pilih cycle time MULAI anda!');
                return false;
            }else if(proses_CycleTime == 'EndProses' && endNumber == null){
                toastr.error('Harap pilih cycle time SELESAI anda!');
                return false;
            } else {
                insertInto_cycletime(id, idcycletime, status, startNumber, endNumber, totalPoint, statusCycleTime);
            }
        }
    }

    function insertInto_cycletime(id, idcycletime, status, startNumber, endNumber, totalPoint, statusCycleTime) {
        // SpinnerShow()
        $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/ajax/Insert_cycletime.php",
            data: {
                id: id,
                id_cycletime: idcycletime,
                status: status,
                start_number: startNumber,
                end_number: endNumber,
                total_point: totalPoint,
                status_cycletime: statusCycleTime
            },
            success: function(response) {
                if (response.session == "LIB_SUCCESS") {
                    toastr.success('Data berhasil di' + response.exp, 'Berhasil!');
                    // console.log(response);
                    setTimeout(function() {
                        window.location.href = '?p=Cycle-Time';
                    }, 1500)
                } else if (response.session == "LIB_UPDATED") {
                    toastr.info('Data berhasil di' + response.exp, 'Berhasil!');
                    // console.log(response);
                    setTimeout(function() {
                        window.location.href = '?p=Cycle-Time';
                    }, 1500)
                } else if (response.session == "LIB_DELETED") {
                    toastr.error('Data berhasil di' + response.exp, 'Terhapus!');
                    // console.log(response);
                    setTimeout(function() {
                        window.location.href = '?p=Cycle-Time';
                    }, 2000)
                } else if (response.session == "LIB_INSERT_STATUS") {
                    toastr.success('Data berhasil di' + response.exp + ' dengan status yang berbeda', 'Berhasil!');
                    // console.log(response);
                    setTimeout(function() {
                        window.location.href = '?p=Cycle-Time';
                    }, 2000)
                } else {
                    toastr.error("ajax error !")
                }
            },
            error: function() {
                alert("Error");
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        var table = $('#Table-sm-ct').DataTable({
            select: true,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            "columnDefs": [{
                    "className": "align-center",
                    "targets": [0, 3, 12, 38]
                },
                {
                    "targets": [2, 10, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 39],
                    "visible": false
                },
                {
                    "targets": [0, 1, 2],
                    "orderable": false
                },
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData[39] == 'revisi') {
                    $('td', nRow).css('background-color', '#f9ff8f');
                    $('td', nRow).css('color', 'black');
                } else if (aData[39] == 'tunggu') {
                    $('td', nRow).css('background-color', '#f55b5b');
                    $('td', nRow).css('color', 'black');
                } else {
                    $('td', nRow).css('color', 'black');
                }
            },
            "pageLength": 5
        });

        new $.fn.dataTable.FixedHeader(table);

        $('#Table-sm-ct tbody').on('click', 'td.details-control', function() {
            var tr = $(this).parents('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row (the format() function would return the data to be shown)
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });

        function format(d) {
            return '<div class="col-md-12" style="background: #247fff;">' +
                '<div class="container-fluid">' +
                '<table class="table table-striped table-bordered" id="tableee" width="100%" style="margin-top: 10px;">' +
                '<tbody>' +
                // 1
                '<tr>' +
                '<th style="width:100px">Jenis Matching :</th>' +
                '<td>' + d[18] + '</td>' +
                '<th style="width:90px">PO Greige :</th>' +
                '<td colspan="5">' + d[19] + '</td>' +
                '</tr>' +
                // 2
                '<tr>' +
                '<th>Jenis Kain :</th>' +
                '<td>' + d[20] + '</td>' +
                '<th>Benang :</th>' +
                '<td colspan="5">' + d[21] + '</td>' +
                '</tr>' +
                // 4
                '<tr>' +
                '<th>Lampu :</th>' +
                '<td colspan="1">' + d[35] + '</td>' +
                '<th>Proses :</th>' +
                '<td colspan="5">' + d[36] + '</td>' +
                '</tr>' +
                // 
                '<tr>' +
                '<th>Tgl Generate Kartu Matching :</th>' +
                '<td>' + d[13] + '</td>' +
                '<th>Generate Kartu by :</th>' +
                '<td>' + d[15] + '</td>' +
                '<th>Tgl Mulai :</th>' +
                '<td>' + d[14] + '</td>' +
                '<th>Mulai By :</th>' +
                '<td>' + d[16] + '</td>' +
                '</tr>' +
                // 3
                '<tr>' +
                '<th>Lebar :</th>' +
                '<td>' + d[22] + '</td>' +
                '<th>Gramasi :</th>' +
                '<td>' + d[23] + '</td>' +
                '<th>Lebar Aktual :</th>' +
                '<td>' + d[24] + '</td>' +
                '<th>Gramasi Aktual :</th>' +
                '<td>' + d[25] + '</td>' +
                '</tr>' +
                // 5                
                '<tr>' +
                '<th>Qty Order :</th>' +
                '<td>' + d[30] + '</td>' +
                '<th>tgl delivery :</th>' +
                '<td>' + d[31] + '</td>' +
                '<th>Tanggal in :</th>' +
                '<td>' + d[32] + '</td>' +
                '<th>Tanggal out :</th>' +
                '<td>' + d[33] + '</td>' +
                '</tr>' +
                // 6
                '<tr>' +
                '<th>Keterangan :</th>' +
                '<td colspan="7">' + d[34] + '</td>' +
                // '<td align="center"><a href="pages/cetak/matching.php?idkk=' + d[5] + '" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Print</a></td>' +
                // '<td align="center"><a href="?p=Status-Handle&idm=' + d[37] + '" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Handle</a></td>' +
                '</tr>' +
                '</tbody>' +
                '</table>' +
                '</div>' +
                '</div>';
        }
    });
</script>

<script>
  $(document).ready(function() {
    $(document).on('click', '._ketstatus', function() {
      var code = $(this).attr('codem');
      let previousStatus = this.getAttribute('value'); // Mengambil keterangan status
      Swal.fire({
        title: "Keterangan Status !",
        text: "Ubah keterangan status anda",
        input: 'select',  // Mengubah tipe input menjadi 'select'
        inputOptions: {  // Mendefinisikan opsi untuk dropdown
            'Normal': 'Normal',
            'Urgent': 'Urgent'
        },
        inputValue: previousStatus,  // Pra-pilih status sebelumnya
        inputPlaceholder: 'Pilih Keterangan anda ...',  // Memperbarui teks placeholder
        showCancelButton: true,
        allowOutsideClick: false,
      }).then((result) => {
        if (result.value) {
          $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/ajax/ChangeKetStatus.php",
            data: {
              id_status: $(this).attr('attribute'),
              idm: $(this).attr('codem'),
              newStatus: result.value
            },
            success: function(response) {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Status Matching ' + code + ' telah di rubah. !',
                showConfirmButton: false,
                timer: 1500
              })
              setTimeout(function() {
                location.reload();
              }, 1505);
            },
            error: function() {
              alert("Error");
            }
          });
        } else if (result.value !== "") {
          consol.log('button cancel clicked !')
        } else {
          Swal.fire('Status Tidak di pilih !')
        }
      });
    })
    
    $(document).on('click', '._tunggu', function() {
      var code = $(this).attr('codem');
      Swal.fire({
        title: "Keterangan Status tunggu !",
        text: "Berikan Keterangan anda",
        input: 'textarea',
        inputPlaceholder: 'Ketikan Keterangan anda ...',
        showCancelButton: true,
        allowOutsideClick: false,
      }).then((result) => {
        if (result.value) {
          $.ajax({
            dataType: "json",
            type: "POST",
            url: "pages/ajax/Wait_byID.php",
            data: {
              id_status: $(this).attr('attribute'),
              why: result.value
            },
            success: function(response) {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Matching ' + code + ' telah di rubah menjadi Tunggu !',
                showConfirmButton: false,
                timer: 1500
              })
              setTimeout(function() {
                location.reload();
              }, 1505);
            },
            error: function() {
              alert("Error");
            }
          });
        } else if (result.value !== "") {
          consol.log('button cancel clicked !')
        } else {
          Swal.fire('Status Tidak di pilih !')
        }
      });
    })
  })
</script>

</html>