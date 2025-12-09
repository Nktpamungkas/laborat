                                        <?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";

?>
<?php
$Nowarna    = isset($_POST['nowarna']) ? $_POST['nowarna'] : '';
$Item        = isset($_POST['item']) ? $_POST['item'] : '';
$Suffix        = isset($_POST['suffix']) ? $_POST['suffix'] : '';
$CounterNo    = isset($_POST['counterno']) ? $_POST['counterno'] : '';
$JnsTesting    = isset($_POST['jns_testing']) ? $_POST['jns_testing'] : '';
$Warna        = isset($_POST['warna']) ? $_POST['warna'] : '';


$role = $_SESSION['jabatanLAB']
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Status Testing QC Final</title>
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
</style>

<body>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> Filter Data</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-2">
                                <input name="suffix" type="text" class="form-control pull-right" id="suffix" placeholder="Suffix" value="<?php echo $Suffix;  ?>" autocomplete="off" />
                            </div>
                            <div class="col-sm-2">
                                <input name="counterno" type="text" class="form-control pull-right" id="counterno" placeholder="Counter" value="<?php echo $CounterNo;  ?>" autocomplete="off" />
                            </div>
                            <div class="col-sm-2">
                                <input name="jns_testing" type="text" class="form-control pull-right" id="jns_testing" placeholder="Jenis Testing" value="<?php echo $JnsTesting;  ?>" autocomplete="off" />
                            </div>
                            <div class="col-sm-2">
                                <input name="nowarna" type="text" class="form-control pull-right" id="nowarna" placeholder="No Warna" value="<?php echo $Nowarna;  ?>" autocomplete="off" />
                            </div>
                            <div class="col-sm-2">
                                <input name="warna" type="text" class="form-control pull-right" id="warna" placeholder="Nama Warna" value="<?php echo $Warna;  ?>" autocomplete="off" />
                            </div>
                            <div class="col-sm-2">
                                <input name="item" type="text" class="form-control pull-right" id="item" placeholder="Item" value="<?php echo $Item;  ?>" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="save" style="width: 50%">Search <i class="fa fa-search"></i></button>
                        </div>
                        <a href="?p=Form-Testing" class="btn btn-sm btn-success pull-right">
                            <span class="fa fa-plus"></span> Add Test</a>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>
    <form method="post" action="pages/cetak/form_permintaan.php" name="form2" id="form2" target="_blank">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Status Testing</h3>
                        <button type="submit" class="btn btn-sm btn-info pull-right">
                            <span class="fa fa-file"></span> Cetak Form Permintaan Test</button>
                        <br>
                        <br>
                        <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                            <table id="Table-sm" class="table table-sm display compact" style="width: 100%;">
                                <thead>
                                    <tr class="alert-success" style="border: 1px solid #ddd;">
                                        <th style="border: 1px solid #ddd;">#</th>
                                        <th style="border: 1px solid #ddd;">Suffix</th>
                                        <th style="border: 1px solid #ddd;">No Counter</th>
                                        <th style="border: 1px solid #ddd;">Jenis Testing</th>
                                        <th style="border: 1px solid #ddd;">Treatment</th>
                                        <th style="border: 1px solid #ddd;">Buyer</th>
                                        <th style="border: 1px solid #ddd;">No Warna</th>
                                        <th style="border: 1px solid #ddd;">Nama Warna</th>
                                        <th style="border: 1px solid #ddd;">Item</th>
                                        <th style="border: 1px solid #ddd;">Jenis Kain</th>
                                        <th style="border: 1px solid #ddd;">Personil Testing</th>
                                        <th style="border: 1px solid #ddd;">Timer</th>
                                        <th style="border: 1px solid #ddd;">Permintaan Testing</th>
                                        <th style="border: 1px solid #ddd;">Created By</th>
                                        <th style="border: 1px solid #ddd;">Status</th>
                                        <th style="border: 1px solid #ddd;">Status QC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if ($Nowarna != "" or $Item != "" or $Suffix != "" or $JnsTesting != "" or $Warna != "" or $CounterNo != "") {
                                        $sql = mysqli_query($con, "SELECT * FROM tbl_test_qc WHERE sts_laborat <> 'Approved Full' AND suffix LIKE '%$Suffix%' AND jenis_testing LIKE '%$JnsTesting%' AND no_warna LIKE '%$Nowarna%' AND warna LIKE '%$Warna%' AND no_item LIKE '%$Item%' AND no_counter LIKE '%$CounterNo%' AND (deleted_at IS NULL OR deleted_at = '') ORDER BY id ASC");
                                    } else {
                                        $sql = mysqli_query($con, "SELECT * FROM tbl_test_qc WHERE sts_laborat <> 'Approved Full' AND (deleted_at IS NULL OR deleted_at = '')  ORDER BY id ASC");
                                    }
                                    while ($r = mysqli_fetch_array($sql)) {
                                        $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
                                        $detail2 = explode(",", $r['permintaan_testing']);


                                        $tgl_buat =  $r['tgl_buat'];
                                        $tgl_terima = $r['tgl_terimakain'];
                                        $tgl_approve_qc = $r['tgl_approve_qc'];

                                        $now = new DateTime();

                                        $result = '';

                                        // Switch case untuk menentukan kondisi berdasarkan nilai tanggal
                                        switch (true) {
                                            case !empty($tgl_buat) && empty($tgl_terima) && empty($tgl_approve_qc):
                                                $tglBuat = new DateTime($tgl_buat);
                                                $diffBuat = $now->diff($tglBuat);

                                                $result =  $diffBuat->days . " hari : "
                                                    . $diffBuat->h . " jam : "
                                                    . $diffBuat->i . " menit";
                                                break;

                                            case !empty($tgl_buat) && !empty($tgl_terima) && empty($tgl_approve_qc):
                                                $tgTerima = new DateTime($tgl_terima);
                                                $diffTerima = $now->diff($tgTerima);

                                                $result = $diffTerima->days . " hari : "
                                                    . $diffTerima->h . " jam : "
                                                    . $diffTerima->i . " menit";
                                                break;

                                            case !empty($tgl_buat) && !empty($tgl_terima) && !empty($tgl_approve_qc):
                                                $tgApprove_qc = new DateTime($tgl_approve_qc);
                                                $diffApprove_qc = $now->diff($tgApprove_qc);

                                                $result = $diffApprove_qc->days . " hari : "
                                                    . $diffApprove_qc->h . " jam : "
                                                    . $diffApprove_qc->i . " menit";
                                                break;

                                            default:
                                                $result = "Semua tanggal kosong atau null.";
                                                break;
                                        }
                                    ?>
                                        <tr>
                                            <td valign="center">
                                                <?php echo $no++; ?>
                                            </td>
                                            <td valign="center" align="center"><?php echo $r['suffix']; ?>
                                                <hr class="divider">
                                                <input type="checkbox" name="cek[<?php echo $r['id']; ?>]" value="<?php echo $r['id']; ?>">
                                                <!--											<a href="pages/cetak/form_permintaan.php?idkk=<?php echo $r['id']; ?>" id='<?php echo $r['id'] ?>' class="btn btn-xs btn-primary"  target="_blank" title="Form Permintaan Test"> <i class="fa fa-file" aria-hidden="true"></i></a>-->
                                            </td>
                                            <td valign="center"><?php echo $r['no_counter']; ?><!--<hr class="divider"><div class="btn-group"><a href="pages/cetak/cetak_result_lab.php?idkk=<?php echo $r['id']; ?>&noitem=<?php echo $r['no_item']; ?>&nohanger=" id="<?php echo $r['id']; ?>" class="btn btn-xs btn-danger" target="_blank" title="Result"> <i class="fa fa-print" aria-hidden="true"></i></a><a href="pages/cetak/cetak_label.php?idkk=<?php echo $r['id']; ?>" id='<?php echo $r['id'] ?>' class="btn btn-xs btn-warning"  target="_blank" title="Label"> <i class="fa fa-file" aria-hidden="true"></i></a></div>--></td>
                                            <td valign="center"><?php echo $r['jenis_testing']; ?></td>
                                            <td valign="center"><?php echo $r['treatment']; ?></td>
                                            <td valign="center"><?php echo $r['buyer']; ?></td>
                                            <td valign="center" align="left"><?php echo $r['no_warna']; ?></td>
                                            <td valign="center"><?php echo $r['warna']; ?></td>
                                            <td valign="center"><?php echo $r['no_item']; ?></td>
                                            <td valign="center"><?php echo $r['jenis_kain']; ?></td>
                                            <td valign="center"><?php echo $r['nama_personil_test']; ?></td>
                                            <td style="width: 200px;" valign="center"><?php echo $result; ?></td>
                                            <td valign="center" align="left"><?php if ($r['permintaan_testing'] != "") {
                                                                                    echo $r['permintaan_testing'];
                                                                                } else {
                                                                                    echo "<span class='label label-danger blink_me'>FULL TEST</span>";
                                                                                } ?></td>
                                            <td valign="center" class="13"><?php echo $r['created_by']; ?>
                                                <hr class="divider"><span class="label <?php if ($r['sts'] == "normal") {
                                                                                            echo "label-warning";
                                                                                        } else {
                                                                                            echo "label-danger blink_me";
                                                                                        } ?>"><?php echo $r['sts']; ?></span>
                                            </td>
                                            <td valign="center" class="13"><span class="label <?php if ($r['sts_laborat'] == "Open") {
                                                                                                    echo "label-info";
                                                                                                } else if ($r['sts_laborat'] == "In Progress") {
                                                                                                    echo "label-success";
                                                                                                } else {
                                                                                                    echo "label-primary";
                                                                                                } ?>"><?php echo $r['sts_laborat']; ?></span>
                                                <hr class="divider">
                                                <p><strong><em><?php echo $r['sts']; ?></em></strong></p>
                                                <!-- <p><strong>Note lab :</strong> <em><?php echo $r['note_laborat']; ?></em></p>
                                                <a href="#" id='<?php echo $r['id'] ?>' class="note_laborat_edit"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> -->
                                                <hr class="divider">
                                                <div class="btn-group">
                                                    <?php if ($r['sts_qc'] == "Belum Terima Kain") { ?>
                                                        <a type="button" href="?p=Edit_Testing&id=<?php echo $r['id'] ?>" class="btn btn-xs btn-warning">Edit<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($r['sts_qc'] == "Belum Terima Kain" && $role != "Matcher") { ?>
                                                        <a href="javascript:void(0)" id="<?php echo $r['id'] ?>" no_counter="<?php echo $r['no_counter'] ?>" class="hapus_test btn btn-xs btn-danger">Delete<i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                            <td align="left" valign="middle" class="13"><span class="label <?php if ($r['sts_qc'] == "Tunggu Kain") {
                                                                                                                echo "label-primary";
                                                                                                            } else if ($r['sts_qc'] == "Sudah Terima Kain") {
                                                                                                                echo "label-success";
                                                                                                            } else if ($r['sts_qc'] == "Kain Sudah diTes") {
                                                                                                                echo "label-info";
                                                                                                            } else if ($r['sts_qc'] == "Kain Bisa Diambil") {
                                                                                                                echo "label-danger blink_me";
                                                                                                            } else {
                                                                                                                echo "label-warning";
                                                                                                            } ?>"><?php echo $r['sts_qc']; ?></span>
                                                <hr class="divider">
                                                <em>Pengirim: <strong><?php echo $r['diterima_oleh']; ?></strong><br>
                                                    Penerima: <strong><?php echo $r['nama_penerima']; ?></strong></em><br><em><?php echo $r['note_qc']; ?></em>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Modal Popup untuk Edit-->
    <div id="NoteLaboratEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    </div>

</body>

</html>
<script>
    $(document).ready(function() {
        var table = $('#Table-sm').DataTable({
            "ordering": false,
            "pageLength": 15,
            responsive: true,
            language: {
                searchPlaceholder: "Search..."
            },
            select: true,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],


        });
        new $.fn.dataTable.FixedHeader(table);

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


        $(document).on('click', '.hapus_test', function() {
            var id_tes = $(this).attr("id");

            var no_counter = $(this).attr("no_counter");

            // console.log(id_tes);

            Swal.fire({
                title: 'Hapus test',
                text: `Untuk menghapus tes ini ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/delete_test_qc.php",
                        data: {
                            id: id_tes,
                            no_counter: no_counter,
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                Swal.fire(
                                    'Deleted!',
                                    'Your data has been deleted.',
                                    'success'
                                )
                                location.reload();
                            } else {
                                Swal.fire(
                                    'Failed',
                                    'Your data not been deleted.',
                                    'success'
                                )
                                location.reload();
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }
            })
        })
    });
</script>