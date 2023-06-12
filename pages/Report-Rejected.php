<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Status Matching</title>
</head>
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

    #Table-sm td,
    #Table-sm th {
        border: 0.1px solid #ddd;
    }

    #Table-sm th {
        color: black;
        background: #4CAF50;
    }

    #Table-sm tr:hover {
        background-color: rgb(151, 170, 212);
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
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="container-fluid">
                        <form class="form-inline" method="POST" action="">
                            <div class="form-group mb-2">
                                <input type="text" class="form-control input-sm date-picker" name="date_start" id="date_start" value="<?php
                                                                                                                                        if ($_POST['submit']) {
                                                                                                                                            echo $_POST['date_start'];
                                                                                                                                        } else {
                                                                                                                                            echo date('Y-m-d');
                                                                                                                                        } ?>">
                            </div>
                            <div class="form-group mb-2">
                                <i class="fa fa-share" aria-hidden="true"></i>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <input type="text" class="form-control input-sm date-picker" name="date_end" id="date_end" value="<?php
                                                                                                                                    if ($_POST['submit']) {
                                                                                                                                        echo $_POST['date_end'];
                                                                                                                                    } else {
                                                                                                                                        echo date('Y-m-d');
                                                                                                                                    } ?>">
                            </div>
                            <button type="submit" name="submit" value="search" class="btn btn-danger btn-sm mb-2"><i class="fa fa-search text-black" aria-hidden="true"></i>
                            </button>
                        </form>
                        <hr />
                    </div>
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                        <table id="Table-sm" class="table table-sm display compact" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Status</th>
                                    <th>Ket.St</th>
                                    <th>Grp</th>
                                    <th>Matcher</th>
                                    <th>Rcode</th>
                                    <th>No.Order</th>
                                    <th>Langganan</th>
                                    <th>Warna</th>
                                    <th>No.Warna</th>
                                    <th>Jenis Kain</th>
                                    <th>No.Item</th>
                                    <th>timer</th>
                                    <th>tgl_buat</th>
                                    <th>tgl_mulai</th>
                                    <th>created_by</th>
                                    <th>status_created_by</th>
                                    <th>tgl_selesai</th>
                                    <th>jenis_matching</th>
                                    <th>PO greige</th>
                                    <th>jenis_kain</th>
                                    <th>benang</th>
                                    <th>lebar</th>
                                    <th>gramasi</th>
                                    <th>lebara</th>
                                    <th>gramasia</th>
                                    <th>cek_warna</th>
                                    <th>cek_dye</th>
                                    <th>koreksi_resep</th>
                                    <th>cocok_warna</th>
                                    <th>qty_order</th>
                                    <th>tgl_delivery</th>
                                    <th>L:R</th>
                                    <th>ph</th>
                                    <th>ket</th>
                                    <th>Lampu</th>
                                    <th>Proses</th>
                                    <th>id_status</th>
                                    <th>Text_status</th>
                                    <th>id_matching</th>
                                    <th>Benang Aktual</th>
                                    <th>Detail</th>
                                    <th>Why_batal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $date = date('Y-m-d');
                                $date_s = $_POST['date_start'];
                                $date_e = $_POST['date_end'];

                                if (empty($_POST['submit'])) {
                                    $sql = mysqli_query($con,"SELECT *,  a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by
                  FROM tbl_status_matching a
                  INNER JOIN tbl_matching b ON a.idm = b.no_resep
                  where a.status = 'tutup' and a.approve = 'NONE' AND DATE_FORMAT(a.tutup_at,'%Y-%m-%d') = '$date'
                  ORDER BY a.id desc limit 20");
                                } else {
                                    $sql = mysqli_query($con,"SELECT *,  a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by
                  FROM tbl_status_matching a
                  INNER JOIN tbl_matching b ON a.idm = b.no_resep
                  where a.status = 'tutup' and a.approve = 'NONE' and
                  DATE_FORMAT(a.tutup_at,'%Y-%m-%d') >= '$date_s' AND DATE_FORMAT(a.tutup_at,'%Y-%m-%d') <= '$date_e'
                  ORDER BY a.id desc");
                                }
                                while ($r = mysqli_fetch_array($sql)) {
                                    $no++;
                                    $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
                                ?>
                                    <tr>
                                        <td valign="center" class="details-control">
                                            <!-- plush icon here -->
                                        </td>
                                        <td valign="center" align="center"><span class="
    <?php if ($r['status'] == "buka" and $r['tgl_mulai'] == "") {
                                        echo "label label-warning";
                                    } elseif ($r['status'] == "mulai") {
                                        echo "label label-info";
                                    } elseif ($r['status'] == "hold") {
                                        echo "label bg-purple";
                                    } elseif ($r['status'] == "batal") {
                                        echo "label label-danger blink_me";
                                    } elseif ($r['status'] == "selesai") {
                                        echo "label label-primary";
                                    } elseif ($r['status'] == "buka" and $r['tgl_mulai'] != "") {
                                        echo "label label-success";
                                    } else {
                                        echo "label label-default";
                                    } ?>"> <?php echo $r['status'] ?> </span>
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
                                            <?php echo $r['matcher'] . ' <strong> / </strong> ' . date('Y-m-d', strtotime($r['tutup_at'])) ?>
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
                                            echo $r['timer']
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
                                        <td class="32"><?php echo $r['lr'] ?></td>
                                        <td class="33"><?php echo $r['ph'] ?></td>
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
                                        <td class="38"><?php echo $r['status'] ?></td>
                                        <td class="39"><?php echo $r['id'] ?></td>
                                        <td class="40"><?php echo $r['benang_aktual'] ?></td>
                                        <td class="41">
                                            <li><a href="?p=Detail-status-rejected&idm=<?php echo $r['id_status'] ?>" class="btn btn-xs btn-primary">Detail <i class="fa fa-fw fa-search-plus"></i></a></li>
                                            <li><button type="button" style="color: black;" class="btn btn-xs btn-danger delete" id_status="<?php echo $r['id_status'] ?>" id_matching="<?php echo $r['id'] ?>" idm="<?php echo $r['idm'] ?>" no_order="<?php echo $r['no_order'] ?>" why_batal=<?php echo $r['why_batal'] ?>>Delete <i class="fa fa-trash"></i></button></li>
                                        </td>
                                        <td class="42"> <?php echo $r['why_batal'] ?> </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
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
            window.location.href = 'index1.php?p=Wait-approval';
        }, 1000);
    }
</script>
<script>
    $(document).ready(function() {
        var table = $('#Table-sm').DataTable({
            // select: true,
            // "scrollX": true,
            // "scrollY": true,
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
                    "targets": [2, 10, 13, 14, 15, 16, 17, 18, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 42],
                    "visible": false
                },
                {
                    "targets": [0, 1, 2],
                    "orderable": false
                },
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData[38] == 'batal') {
                    $('td', nRow).css('background-color', '#ff9494');
                    $('td', nRow).css('color', 'black');
                } else {
                    $('td', nRow).css('color', 'black');
                }
            },
        });

        $('#Table-sm tbody').on('click', 'td.details-control', function() {
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
            if (d[38] == 'batal') {
                fn_getResep_by_idstatus_idmatching(d[37], d[39])
                return '<div class="col-md-12" style="background: #ff9494;">' +
                    '<div class="container-fluid">' +
                    '<table class="table table-striped table-bordered" id="tableee" width="100%" style="margin-top: 10px;">' +
                    '<tbody>' +
                    // 1
                    '<tr>' +
                    '<th>Alasan di batalakan :</th>' +
                    '<td>' + d[42] + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<th style="width:100px">Jenis Matching :</th>' +
                    '<td>' + d[18] + ' (' + d[5] + ')</td>' +
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
                    '<th>L:R</th>' +
                    '<td>' + d[32] + '</td>' +
                    '<th>Kadar Ph :</th>' +
                    '<td>' + d[33] + '</td>' +
                    '</tr>' +
                    // 6
                    '<tr>' +
                    '<th>Benang Aktual :</th>' +
                    '<td colspan="1">' + d[40] + '</td>' +
                    '<th>Keterangan :</th>' +
                    '<td colspan="5">' + d[34] + '</td>' +
                    // '<td align="center"><a href="pages/cetak/matching.php?idkk=' + d[5] + '" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Print</a></td>' +
                    // '<td align="center"><a href="?p=Status-Handle&idm=' + d[37] + '" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Handle</a></td>' +
                    '</tr>' +
                    '</tbody>' +
                    '</table>' +
                    '<hr />' +
                    '<table class="table table-sm table-bordered">' +
                    '<tr>' +
                    '<th>#</th>' +
                    '<th>Kode</th>' +
                    '<th>Desc Kode</th>' +
                    '<th>Lab</th>' +
                    '<th>Adjust-1</th>' +
                    '<th>Adjust-2</th>' +
                    '<th>Adjust-3</th>' +
                    '<th>Adjust-4</th>' +
                    '<th>Adjust-5</th>' +
                    '<th>Adjust-6</th>' +
                    '<th>Adjust-7</th>' +
                    '<th>Adjust-8</th>' +
                    '<th>Adjust-9</th>' +
                    '</tr>' +
                    '<tbody id="' + 'rowresep_' + d[37] + '">' +
                    // APPEND HERE BUDS
                    '</tbody>' +
                    '</table>' +
                    '</div>' +
                    '</div>';
            } else {
                fn_getResep_by_idstatus_idmatching(d[37], d[39])
                return '<div class="col-md-12" style="background: #0275d8;">' +
                    '<div class="container-fluid">' +
                    '<table class="table table-striped table-bordered" id="tableee" width="100%" style="margin-top: 10px;">' +
                    '<tbody>' +
                    // 1
                    '<th style="width:100px">Jenis Matching :</th>' +
                    '<td>' + d[18] + ' (' + d[5] + ')</td>' +
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
                    '<th>L:R</th>' +
                    '<td>' + d[32] + '</td>' +
                    '<th>Kadar Ph :</th>' +
                    '<td>' + d[33] + '</td>' +
                    '</tr>' +
                    // 6
                    '<tr>' +
                    '<th>Benang Aktual :</th>' +
                    '<td colspan="1">' + d[40] + '</td>' +
                    '<th>Keterangan :</th>' +
                    '<td colspan="5">' + d[34] + '</td>' +
                    // '<td align="center"><a href="pages/cetak/matching.php?idkk=' + d[5] + '" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print"></i> Print</a></td>' +
                    // '<td align="center"><a href="?p=Status-Handle&idm=' + d[37] + '" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Handle</a></td>' +
                    '</tr>' +
                    '</tbody>' +
                    '</table>' +
                    '<hr />' +
                    '<table class="table table-sm table-bordered">' +
                    '<tr>' +
                    '<th>#</th>' +
                    '<th>Kode</th>' +
                    '<th>Desc Kode</th>' +
                    '<th>Lab</th>' +
                    '<th>Adjust-1</th>' +
                    '<th>Adjust-2</th>' +
                    '<th>Adjust-3</th>' +
                    '<th>Adjust-4</th>' +
                    '<th>Adjust-5</th>' +
                    '<th>Adjust-6</th>' +
                    '<th>Adjust-7</th>' +
                    '<th>Adjust-8</th>' +
                    '<th>Adjust-9</th>' +
                    '</tr>' +
                    '<tbody id="' + 'rowresep_' + d[37] + '">' +
                    // APPEND HERE BUDS
                    '</tbody>' +
                    '</table>' +
                    '</div>' +
                    '</div>';
            }
        }

        function fn_getResep_by_idstatus_idmatching(id_status, id_matching) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: 'pages/ajax/fn_getResep_by_idstatus_idmatching.php',
                data: {
                    id_status: id_status,
                    id_matching: id_matching
                },
                success: function(response) {
                    var lastitem = '0';
                    var i;
                    var tr = $("#rowresep_" + id_status);
                    tr.empty();
                    $.each(response, function(index, item) {
                        tr.append(
                            '<tr>' +
                            '<td>' + item[0] + '</td>' +
                            '<td>' + item[1] + '</td>' +
                            '<td>' + item[12] + '</td>' +
                            '<td class="' + item[2] + '">' + item[2] + '</td>' +
                            '<td class="' + item[3] + '">' + item[3] + '</td>' +
                            '<td class="' + item[4] + '">' + item[4] + '</td>' +
                            '<td class="' + item[5] + '">' + item[5] + '</td>' +
                            '<td class="' + item[6] + '">' + item[6] + '</td>' +
                            '<td class="' + item[7] + '">' + item[7] + '</td>' +
                            '<td class="' + item[8] + '">' + item[8] + '</td>' +
                            '<td class="' + item[9] + '">' + item[9] + '</td>' +
                            '<td class="' + item[10] + '">' + item[10] + '</td>' +
                            '<td class="' + item[11] + '">' + item[11] + '</td>' +
                            '</tr>'
                        )
                    });
                },
                error: function() {
                    alert("Error");
                }
            });
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('.btn.btn-xs.btn-danger.delete').click(function() {
            var idm = $(this).attr('idm');
            var id_matching = $(this).attr('id_matching');
            var id_status = $(this).attr('id_status');
            var no_order = $(this).attr('no_order');
            if ($(this).attr('why_batal') == "") {
                var why_batal = 'Rejected by leader/SPV';
            } else {
                var why_batal = $(this).attr('why_batal');
            }
            console.log(why_batal)
            Swal.fire({
                title: "Autentikasi",
                text: "Apakah anda yakin untuk menghapus resep ini ",
                showCancelButton: true,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/Delete_by_3ID.php",
                        data: {
                            idm: idm,
                            id_matching: id_matching,
                            id_status: id_status,
                            no_order: no_order,
                            why_batal: why_batal
                        },
                        success: function(response) {
                            console.log(response)
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Matching ' + idm + ' berhasil di hapus !',
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
                    console.log('button cancel clicked !')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Password yang anda masukan salah !',
                    })
                }
            });
        })
    })
</script>

</html>