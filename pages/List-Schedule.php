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
<?php
$Nowarna	= isset($_POST['nowarna']) ? $_POST['nowarna'] : '';
$Item	    = isset($_POST['item']) ? $_POST['item'] : '';
$RCode	    = isset($_POST['rcode']) ? $_POST['rcode'] : '';
$Warna	    = isset($_POST['warna']) ? $_POST['warna'] : '';
$JMatching	= isset($_POST['jmatching']) ? $_POST['jmatching'] : '';
$Order	    = isset($_POST['order']) ? $_POST['order'] : '';	
?>
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
                                <input name="rcode" type="text" class="form-control pull-right" id="rcode" placeholder="RCode" value="<?php echo $RCode;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="nowarna" type="text" class="form-control pull-right" id="nowarna" placeholder="No Warna" value="<?php echo $Nowarna;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="item" type="text" class="form-control pull-right" id="item" placeholder="No Item" value="<?php echo $Item;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="warna" type="text" class="form-control pull-right" id="warna" placeholder="Warna" value="<?php echo $Warna;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="jmatching" type="text" class="form-control pull-right" id="jmatching" placeholder="Jenis Matching" value="<?php echo $JMatching;  ?>" autocomplete="off"/>
                            </div>
                            <div class="col-sm-2">
                                <input name="order" type="text" class="form-control pull-right" id="order" placeholder="No Order" value="<?php echo $Order;  ?>" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="save" style="width: 60%">Search <i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                        <?php
                        if($Nowarna!="" or $Item!="" or $RCode!="" or $Warna!="" or $JMatching!="" or $Order!=""){
                        $sql = mysqli_query($con,"SELECT a.`id`, a.`no_resep`, a.`no_order`, a.`warna`, a.`no_warna`, a.`no_item`, a.`langganan`, a.`no_po`, a.`no_item` ,b.approve, a.jenis_matching, a.benang,
                                                        b.`id` as id_status, b.status, a.status_bagi, ifnull(b.`ket`, a.note) as ket
                                                        FROM tbl_matching a 
                                                        left join tbl_status_matching b on a.`no_resep` = b.`idm`
                                                        where b.approve_at is null AND a.no_resep LIKE '%$RCode%' AND a.no_warna LIKE '%$Nowarna%' AND a.no_item LIKE '%$Item%' AND a.warna LIKE '%$Warna%' AND a.no_order LIKE '%$Order%' AND a.jenis_matching LIKE '%$JMatching%'
                                                        order by a.id desc");
                        }else{
                        $sql = mysqli_query($con,"SELECT a.`id`, a.`no_resep`, a.`no_order`, a.`warna`, a.`no_warna`, a.`no_item`, a.`langganan`, a.`no_po`, a.`no_item` ,b.approve, a.jenis_matching, a.benang,
                                                        b.`id` as id_status, b.status, a.status_bagi, ifnull(b.`ket`, a.note) as ket, a.tgl_update
                                                        FROM tbl_matching a 
                                                        left join tbl_status_matching b on a.`no_resep` = b.`idm`
                                                        where b.approve_at is null
                                                        order by a.id desc");
                        }
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
                                    <th>Tgl Update</th>
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
                                        <td><?php echo $li['tgl_update'] ?></td>
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
                                                    <a href="javascript:void(0)" data-attribute="<?php echo $li['no_resep']; ?>" class="btn btn-default btn-xs _merge" title="Add Order"><i class="fa fa-link"></i></a>
											        <a href="index1.php?p=edit_matching&rcode=<?php echo $li['no_resep'] ?>" class="_edit btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
													<a target="_blank" href="pages/cetak/matching.php?idkk=<?php echo $li['no_resep'] ?>" class="btn btn-xs btn-warning" title="print">:: <i class="fa fa-print"></i></a>
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
            dom: 'Bfrtip',
            buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
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

        // with input
        // $(document).on('click', '._bagikan', function () {
        //     let rcode = $(this).closest('tr').find('td:eq(1)').text();

        //     $.ajax({
        //         dataType: "json",
        //         type: "POST",
        //         url: "pages/ajax/cek_temp_code.php",
        //         data: { rcode: rcode },
        //         success: function (response) {
        //             if (response.needInput) {
        //                 // CASE: rcode diawali DR → 2 input
        //                 if (response.isDR) {
        //                     Swal.fire({
        //                         title: 'Masukkan Temp Code',
        //                         html:
        //                             `<input id="temp_code1" class="swal2-input" placeholder="Temp Code 1">` +
        //                             `<div id="product_info" style="font-size: 0.9em; margin-top: -10px; color: #333;"></div>` +
        //                             `<input id="temp_code2" class="swal2-input" placeholder="Temp Code 2">`,
        //                         focusConfirm: false,
        //                         showCancelButton: true,
        //                         confirmButtonText: 'Simpan & Bagikan',
        //                         didOpen: () => {
        //                             const input1 = document.getElementById('temp_code1');
        //                             const infoDiv = document.getElementById('product_info');
        //                             let timeout = null;

        //                             input1.addEventListener('input', () => {
        //                                 clearTimeout(timeout);
        //                                 const code = input1.value.trim();
        //                                 infoDiv.innerHTML = '⏳ Memeriksa kode...';

        //                                 if (code) {
        //                                     timeout = setTimeout(() => {
        //                                         fetch(`pages/ajax/get_program_by_code.php?code=${encodeURIComponent(code)}`)
        //                                             .then(res => res.json())
        //                                             .then(data => {
        //                                                 if (data.status === 'success') {
        //                                                     infoDiv.innerHTML = `${data.product_name}`;
        //                                                 } else {
        //                                                     infoDiv.innerHTML = `<span style="color: red;">❌ ${data.message || 'Kode tidak valid'}</span>`;
        //                                                 }
        //                                             })
        //                                             .catch(() => {
        //                                                 infoDiv.innerHTML = `<span style="color: red;">⚠ Gagal menghubungi server</span>`;
        //                                             });
        //                                     }, 500);
        //                                 } else {
        //                                     infoDiv.innerHTML = '';
        //                                 }
        //                             });
        //                         },
        //                         preConfirm: async () => {
        //                             const temp1 = document.getElementById('temp_code1').value.trim();
        //                             const temp2 = document.getElementById('temp_code2').value.trim();

        //                             if (!temp1 || !temp2) {
        //                                 Swal.showValidationMessage('Kedua temp_code harus diisi');
        //                                 return false;
        //                             }

        //                             try {
        //                                 const res = await fetch(`pages/ajax/get_program_by_code.php?code=${encodeURIComponent(temp1)}`);
        //                                 const data = await res.json();

        //                                 if (data.status === 'success') {
        //                                     await Swal.fire({
        //                                         title: 'Valid!',
        //                                         html: `${data.product_name}`,
        //                                         icon: 'info',
        //                                         showCancelButton: true,
        //                                         confirmButtonText: 'Simpan & Bagikan'
        //                                     });

        //                                     // Lanjut submit data
        //                                     $.ajax({
        //                                         dataType: "json",
        //                                         type: "POST",
        //                                         url: "pages/ajax/bagikan_schedule_matching.php",
        //                                         data: {
        //                                             rcode: rcode,
        //                                             temp_code: temp1,
        //                                             temp_code2: temp2
        //                                         },
        //                                         success: handleSuccess,
        //                                         error: () => alert("Terjadi error saat kirim data.")
        //                                     });

        //                                     return false; // Jangan auto-close Swal
        //                                 } else {
        //                                     Swal.fire({
        //                                         icon: 'error',
        //                                         title: 'Kode Tidak Ditemukan',
        //                                         text: data.message || 'Kode tidak valid'
        //                                     });
        //                                     return false;
        //                                 }
        //                             } catch (e) {
        //                                 Swal.fire('Error', 'Gagal mengambil data program.', 'error');
        //                                 return false;
        //                             }
        //                         }
        //                     });

        //                 } else {
        //                     // CASE: rcode bukan DR → 1 input
        //                     Swal.fire({
        //                         title: 'Masukkan Temp Code',
        //                         html:
        //                             `<input id="temp_code_single" class="swal2-input" placeholder="Temp Code">` +
        //                             `<div id="product_info_single" style="font-size: 0.9em; margin-top: -10px; color: #333;"></div>`,
        //                         showCancelButton: true,
        //                         confirmButtonText: 'Simpan & Bagikan',
        //                         focusConfirm: false,
        //                         didOpen: () => {
        //                             const input = document.getElementById('temp_code_single');
        //                             const infoDiv = document.getElementById('product_info_single');
        //                             let timeout = null;

        //                             input.addEventListener('input', () => {
        //                                 clearTimeout(timeout);
        //                                 const code = input.value.trim();
        //                                 infoDiv.innerHTML = '⏳ Memeriksa kode...';

        //                                 if (code) {
        //                                     timeout = setTimeout(() => {
        //                                         fetch(`pages/ajax/get_program_by_code.php?code=${encodeURIComponent(code)}`)
        //                                             .then(res => res.json())
        //                                             .then(data => {
        //                                                 if (data.status === 'success') {
        //                                                     infoDiv.innerHTML = `${data.product_name}`;
        //                                                 } else {
        //                                                     infoDiv.innerHTML = `<span style="color: red;">❌ ${data.message || 'Kode tidak valid'}</span>`;
        //                                                 }
        //                                             })
        //                                             .catch(() => {
        //                                                 infoDiv.innerHTML = `<span style="color: red;">⚠ Gagal menghubungi server</span>`;
        //                                             });
        //                                     }, 500);
        //                                 } else {
        //                                     infoDiv.innerHTML = '';
        //                                 }
        //                             });
        //                         },
        //                         preConfirm: async () => {
        //                             const value = document.getElementById('temp_code_single').value.trim();
        //                             if (!value) {
        //                                 Swal.showValidationMessage('Temp code tidak boleh kosong');
        //                                 return false;
        //                             }

        //                             try {
        //                                 const res = await fetch(`pages/ajax/get_program_by_code.php?code=${encodeURIComponent(value)}`);
        //                                 const data = await res.json();

        //                                 if (data.status === 'success') {
        //                                     const result2 = await Swal.fire({
        //                                         title: 'Valid!',
        //                                         html: `<b>Produk:</b> ${data.product_name}<br><b>Kode:</b> ${value}`,
        //                                         icon: 'info',
        //                                         showCancelButton: true,
        //                                         confirmButtonText: 'Simpan & Bagikan'
        //                                     });

        //                                     if (result2.isConfirmed) {
        //                                         $.ajax({
        //                                             dataType: "json",
        //                                             type: "POST",
        //                                             url: "pages/ajax/bagikan_schedule_matching.php",
        //                                             data: {
        //                                                 rcode: rcode,
        //                                                 temp_code: value
        //                                             },
        //                                             success: handleSuccess,
        //                                             error: () => alert("Terjadi error saat kirim data.")
        //                                         });
        //                                     }
        //                                 } else {
        //                                     Swal.fire({
        //                                         icon: 'error',
        //                                         title: 'Kode Tidak Ditemukan',
        //                                         text: data.message || 'Kode tidak valid'
        //                                     });
        //                                     return false;
        //                                 }
        //                             } catch {
        //                                 Swal.fire('Error', 'Gagal menghubungi server.', 'error');
        //                                 return false;
        //                             }
        //                         }
        //                     });
        //                 }

        //             } else {
        //                 // CASE: tidak butuh input temp_code
        //                 Swal.fire({
        //                     title: 'Apakah anda yakin?',
        //                     text: `Untuk membagikan resep dengan kode ${rcode}`,
        //                     icon: 'question',
        //                     showCancelButton: true,
        //                     confirmButtonText: 'Ya, Bagikan!',
        //                     cancelButtonText: 'Batal'
        //                 }).then((result) => {
        //                     if (result.isConfirmed) {
        //                         $.ajax({
        //                             dataType: "json",
        //                             type: "POST",
        //                             url: "pages/ajax/bagikan_schedule_matching.php",
        //                             data: { rcode: rcode },
        //                             success: handleSuccess,
        //                             error: () => alert("Terjadi error saat kirim data.")
        //                         });
        //                     }
        //                 });
        //             }
        //         }
        //     });

        //     function handleSuccess(response) {
        //         if (response.session === "LIB_SUCCSS") {
        //             Swal.fire('Berhasil!', 'Resep berhasil dibagikan.', 'success');
        //             setTimeout(() => window.location.reload(), 1000);
        //         } else {
        //             toastr.error("Gagal memperbarui/bagikan resep");
        //         }
        //     }
        // });

        // with select
        $(document).on('click', '._bagikan', function () {
            let rcode = $(this).closest('tr').find('td:eq(1)').text();

            $.ajax({
                dataType: "json",
                type: "POST",
                url: "pages/ajax/cek_temp_code.php",
                data: { rcode: rcode },
                success: function (response) {
                    if (response.needInput) {
                        fetch("pages/ajax/get_temp_code_options.php")
                            .then(res => res.json())
                            .then(options => {

                                const generateSelect = (id) => {
                                    let html = `<select id="${id}" class="form-control" style="width: 100%; margin-top: 2px;">`;
                                    html += `<option value="">Pilih...</option>`;
                                    options.forEach(opt => {
                                        html += `<option value="${opt.code}">${opt.label}</option>`;
                                    });
                                    html += `</select>`;
                                    return html;
                                };

                                if (response.isDR) {
                                    // CASE: DR → 2 dropdown
                                    Swal.fire({
                                        title: 'Apakah anda yakin?',
                                        icon: 'question',
                                        html: `
                                            <p>Untuk membagikan resep dengan kode <b>${rcode}</b></p>
                                            <label>Temp:</label>
                                            ${generateSelect('temp_code1')}
                                            <label style="margin-top:10px;">Temp 2:</label>
                                            ${generateSelect('temp_code2')}
                                        `,
                                        showCancelButton: true,
                                        confirmButtonText: 'Simpan & Bagikan',
                                        focusConfirm: false,
                                        preConfirm: () => {
                                            const temp1 = document.getElementById('temp_code1').value;
                                            const temp2 = document.getElementById('temp_code2').value;

                                            if (!temp1 || !temp2) {
                                                Swal.showValidationMessage('Kedua temp harus dipilih');
                                                return false;
                                            }

                                            $.ajax({
                                                dataType: "json",
                                                type: "POST",
                                                url: "pages/ajax/bagikan_schedule_matching.php",
                                                data: {
                                                    rcode: rcode,
                                                    temp_code: temp1,
                                                    temp_code2: temp2
                                                },
                                                success: handleSuccess,
                                                error: () => alert("Terjadi error saat kirim data.")
                                            });

                                            return false;
                                        }
                                    });
                                } else {
                                    // CASE: Non-DR → 1 dropdown
                                    Swal.fire({
                                        title: 'Apakah anda yakin?',
                                        icon: 'question',
                                        html: `
                                            <p>Untuk membagikan resep dengan kode <b>${rcode}</b></p>
                                            <label>Temp:</label>
                                            ${generateSelect('temp_code_single')}
                                        `,
                                        showCancelButton: true,
                                        confirmButtonText: 'Simpan & Bagikan',
                                        focusConfirm: false,
                                        preConfirm: () => {
                                            const value = document.getElementById('temp_code_single').value;
                                            if (!value) {
                                                Swal.showValidationMessage('Temp code harus dipilih');
                                                return false;
                                            }

                                            $.ajax({
                                                dataType: "json",
                                                type: "POST",
                                                url: "pages/ajax/bagikan_schedule_matching.php",
                                                data: {
                                                    rcode: rcode,
                                                    temp_code: value
                                                },
                                                success: handleSuccess,
                                                error: () => alert("Terjadi error saat kirim data.")
                                            });

                                            return false;
                                        }
                                    });
                                }
                            });
                    } else {
                        // CASE: Tidak butuh temp_code
                        Swal.fire({
                            title: 'Apakah anda yakin?',
                            text: `Untuk membagikan resep dengan kode ${rcode}`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Bagikan!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    dataType: "json",
                                    type: "POST",
                                    url: "pages/ajax/bagikan_schedule_matching.php",
                                    data: { rcode: rcode },
                                    success: handleSuccess,
                                    error: () => alert("Terjadi error saat kirim data.")
                                });
                            }
                });
            }
        }
    });

    function handleSuccess(response) {
        if (response.session === "LIB_SUCCSS") {
            Swal.fire('Berhasil!', 'Resep berhasil dibagikan.', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            toastr.error("Gagal memperbarui/bagikan resep");
        }
    }
});

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