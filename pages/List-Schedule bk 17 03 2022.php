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

    .btn-circle {
        border-radius: 10px;
        color: black;
        font-weight: 800;
    }

    .btn-grp>a,
    .btn-grp>button {
        margin-top: 2px;
    }
</style>

<body>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                        <?php
                        $sql = mysqli_query($con,"SELECT a.`id`, a.`no_resep`, a.`no_order`, a.`warna`, a.`no_warna`, a.`no_item`, a.`langganan`, a.`no_po`, a.`no_item` ,b.approve, a.jenis_matching, a.benang,
                                            b.`id` as id_status, b.status, a.status_bagi, ifnull(b.`ket`, a.note) as ket
                                            FROM tbl_matching a 
                                            left join tbl_status_matching b on a.`no_resep` = b.`idm`
                                            where b.approve_at is null
                                            order by a.id desc");
                        ?>
                        <table id="Table-sm" class="table table-sm display compact" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>No. Resep</th>
                                    <th>J. Matching</th>
                                    <th>No. Order</th>
                                    <th>Benang</th>
                                    <th>Warna</th>
                                    <th>No.warna</th>
                                    <th>Langganan</th>
                                    <th>No. Item</th>
                                    <th>Keterangan</th>
                                    <th>Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($li = mysqli_fetch_array($sql)) { ?>
                                    <tr>
                                        <td>
                                            <?php if ($li['status'] == null) { ?>
                                                <!-- status kosong -->
                                                <?php if ($li['status_bagi'] == 'siap bagi') { ?>
                                                    <button class="btn btn-circle btn-xs btn-success">Siap Bagi</button>
                                                <?php } else if ($li['status_bagi'] == 'tunggu') { ?>
                                                    <button class="btn btn-circle btn-xs btn-warning">tunggu</button>
                                                <?php } else { ?>
                                                    <button class="btn btn-circle btn-xs btn-primary">Belum Bagi</button>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php if ($li['status'] == 'buka') {
                                                    echo '<button class="btn btn-circle btn-xs btn-info">:: sedang jalan</button>';
                                                } else if ($li['status'] == 'selesai' && $li['approve'] == 'NONE') {
                                                    echo '<button class="btn btn-circle btn-xs bg-purple">:: Waiting Approval</button>';
                                                } else if ($li['status'] == 'selesai' && $li['approve'] == 'TRUE') {
                                                    echo '<button class="btn btn-circle btn-xs btn-default">:: Selesai</button>';
                                                } else {
                                                    echo '<button class="btn btn-circle btn-xs btn-default">:: ' . $li['status'] . '</button>';
                                                }
                                                ?>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $li['no_resep'] ?></td>
                                        <td><?php echo $li['jenis_matching'] ?></td>
                                        <td><?php echo $li['no_order'] ?></td>
                                        <td><?php echo $li['benang'] ?></td>
                                        <td><?php echo $li['warna'] ?></td>
                                        <td><?php echo $li['no_warna'] ?></td>
                                        <td><?php echo $li['langganan'] ?></td>
                                        <td><?php echo $li['no_item'] ?></td>
                                        <td width="150"><?php echo $li['ket'] ?></td>
                                        <td class="btn-grp">
                                            <!-- <div class="btn-group" role="group" aria-label="1"> -->
                                            <?php if ($li['status'] == null) { ?>
                                                <!-- status kosong -->
                                                <?php if ($li['status_bagi'] == 'siap bagi') { ?>
                                                    <a target="_blank" href="pages/cetak/matching.php?idkk=<?php echo $li['no_resep'] ?>" class="btn btn-xs btn-warning" title="print"><i class="fa fa-print"></i></a>
                                                    <button type="button" class="_tunggukan btn btn-xs btn-info" title="Tunggu"> <i class="fa fa-hourglass-half" aria-hidden="true"> </i></button>
													<a href="index1.php?p=edit_matching&rcode=<?php echo $li['no_resep'] ?>" class="_edit btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
                                                    <a href="javascript:void(0)" data-attribute="<?php echo $li['no_resep']; ?>" class="btn btn-default btn-xs _merge" title="Add Order"><i class="fa fa-link"></i></a>
                                                    <a href="javascript:void(0)" class="_hapus btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                                <?php } else if ($li['status_bagi'] == 'tunggu') { ?>
                                                    <a target="_blank" href="pages/cetak/matching.php?idkk=<?php echo $li['no_resep'] ?>" class="btn btn-xs btn-warning" title="print"><i class="fa fa-print"></i></a>
                                                    <button type="button" class="_bagikan btn btn-xs btn-success" title="Siap Bagi"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                                                    <a href="index1.php?p=edit_matching&rcode=<?php echo $li['no_resep'] ?>" class="_edit btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
                                                    <a href="javascript:void(0)" data-attribute="<?php echo $li['no_resep']; ?>" class="btn btn-default btn-xs _merge" title="Add Order"><i class="fa fa-link"></i></a>
                                                    <a href="javascript:void(0)" class="_hapus btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                                <?php } else { ?>
                                                    <a target="_blank" href="pages/cetak/matching.php?idkk=<?php echo $li['no_resep'] ?>" class="btn btn-xs btn-warning" title="print"><i class="fa fa-print"></i></a>
                                                    <button type="button" class="_bagikan btn btn-xs btn-success" title="Siap Bagi"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                                                    <button type="button" class="_tunggukan btn btn-xs btn-info" title="Tunggu"> <i class="fa fa-hourglass-half" aria-hidden="true"> </i></button>
                                                    <a href="index1.php?p=edit_matching&rcode=<?php echo $li['no_resep'] ?>" class="_edit btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
                                                    <a href="javascript:void(0)" data-attribute="<?php echo $li['no_resep']; ?>" class="btn btn-default btn-xs _merge" title="Add Order"><i class="fa fa-link"></i></a>
                                                    <a href="javascript:void(0)" class="_hapus btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php if ($li['status'] == 'buka') { ?>
                                                    <a target="_blank" href="pages/cetak/matching.php?idkk=<?php echo $li['no_resep'] ?>" class="btn btn-xs btn-warning" title="print">:: <i class="fa fa-print"></i></a>
                                                    <a href="javascript:void(0)" data-attribute="<?php echo $li['no_resep']; ?>" class="btn btn-default btn-xs _merge" title="Add Order"><i class="fa fa-link"></i></a>
                                                <?php } else { ?>
                                                    <button class="btn btn-xs">:: <i class="fa fa-check" aria-hidden="true"></i>
                                                    </button>
                                                <?php } ?>
                                            <?php } ?>
                                            <!-- </div> -->
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
    <div class="modal fade modal-3d-slit" id="ModalMergeOrderListSchedule" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div id="body_ModalMergeOrderListSchedule" class="modal-dialog" style="width:95%">

        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
        const myTable = $('#Table-sm').DataTable({
            "ordering": false,
            "pageLength": 20
        })

        $(document).on('click', '._hapus', function() {
            let rcode = $(this).closest('tr').find('td:eq(1)').text()
            let tr = $(this).closest('tr');

            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: `Untuk Menghapus matching dengan Kode ${rcode}`,
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
                        url: "pages/ajax/delete_schedule_matching.php",
                        data: {
                            rcode: rcode
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                Swal.fire(
                                    'Deleted!',
                                    'Your data has been deleted.',
                                    'success'
                                )
                                myTable.row(tr).remove().draw();;
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }
            })
        })

        $(document).on('click', '._bagikan', function() {
            let rcode = $(this).closest('tr').find('td:eq(1)').text()

            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: `untuk membagikan resep dengan kode ${rcode}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, bagikan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/bagikan_schedule_matching.php",
                        data: {
                            rcode: rcode
                        },
                        success: function(response) {
                            if (response.session == "LIB_SUCCSS") {
                                Swal.fire(
                                    'Berhasil!',
                                    'Data resep telah siap untuk di bagikan',
                                    'success'
                                )
                                setTimeout(function() {
                                    window.location.reload(1);
                                }, 1000);
                            } else {
                                toastr.error("ajax error !")
                            }
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }
            })
        })

        // $(document).on('click', '._tunggukan', function() {
        //     let rcode = $(this).closest('tr').find('td:eq(1)').text()

        //     Swal.fire({
        //         title: 'Apakah anda yakin ?',
        //         text: `untuk mengubah status menjadi tunggu ${rcode}`,
        //         icon: 'info',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, Tunggukan!'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 dataType: "json",
        //                 type: "POST",
        //                 url: "pages/ajax/tunggukan_schedule_matching.php",
        //                 data: {
        //                     rcode: rcode
        //                 },
        //                 success: function(response) {
        //                     if (response.session == "LIB_SUCCSS") {
        //                         Swal.fire(
        //                             'Berhasil!',
        //                             'Data resep telah berubah status menjadi tunggu',
        //                             'success'
        //                         )
        //                         setTimeout(function() {
        //                             window.location.reload(1);
        //                         }, 1000);
        //                     } else {
        //                         toastr.error("ajax error !")
        //                     }
        //                 },
        //                 error: function() {
        //                     alert("Error");
        //                 }
        //             });
        //         }
        //     })
        // })

        $(document).on('click', '._tunggukan', function() {
            let rcode = $(this).closest('tr').find('td:eq(1)').text()
            Swal.fire({
                title: "Status tunggu !",
                text: "Berikan alasan mengapa status > tunggu ",
                input: 'textarea',
                inputPlaceholder: 'Beri alasan kenapa status di ubah menjadi tunggu ...',
                showCancelButton: true,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        dataType: "json",
                        type: "POST",
                        url: "pages/ajax/tunggukan_schedule_matching.php",
                        data: {
                            rcode: rcode,
                            why: result.value
                        },
                        success: function(response) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Matching ' + rcode + ' telah di rubah menjadi Tunggu !',
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
                    Swal.fire('keterangan wajib di isi !')
                }
            });
        })

        $(document).on('click', '._merge', function(e) {
            var m = $(this).attr("data-attribute");
            $.ajax({
                url: "pages/ajax/merge_order_On_Unapproved.php",
                type: "GET",
                data: {
                    idm: m,
                },
                success: function(ajaxData) {
                    $("#body_ModalMergeOrderListSchedule").html(ajaxData);
                    $("#ModalMergeOrderListSchedule").modal('show', {
                        backdrop: 'false'
                    });
                }
            });
        });
    })
</script>