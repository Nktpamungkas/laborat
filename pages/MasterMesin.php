<?PHP
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<style>
    #masterMesinTable {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        font-size: 10pt !important;
    }

    #masterMesinTable td,
    #masterMesinTable th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #masterMesinTable tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #masterMesinTable tr:hover {
        background-color: rgb(151, 170, 212);
    }

    #masterMesinTable th {
        padding-top: 10px;
        padding-bottom: 10px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
</style>

<body>   

    <div class="row">
        <div class="col-xs-8 col-xs-offset-2">
            <div class="box">

                <div class="box-header">
                    <a href="#" data-toggle="modal" data-target="#addModal" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i> Add</a>
                </div>
                <div class="box-body">
                    <table width="100%" class="table table-bordered table-hover display" id="masterMesinTable" style="border: 1px solid #595959; padding:5px;">
                        <thead class="btn-primary">
                            <tr>
                                <th><div align="center">No.</div></th>
                                <th><div align="center">No. Machine</div></th>
                                <th><div align="center">Suhu</div></th>
                                <th>Program</th>
                                <th>Keterangan</th>
                                <th><div align="center">Actions</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for Add New Master Mesin -->
    <div class="modal fade" tabindex="-1" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Master Mesin</h4>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="addForm">
                        <div class="form-group">
                            <label for="no_machine">No. Machine</label>
                            <input type="text" id="no_machine" name="no_machine" class="form-control" placeholder="Masukan No. Machine" required>
                        </div>

                        <div class="form-group">
                            <label for="suhu">Suhu</label>
                            <div class="input-group">
                                <input type="number" id="suhu" name="suhu" class="form-control" placeholder="Masukan Suhu">
                                <span class="input-group-addon">°C</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="program">Program</label>
                            <select id="program" name="program" class="form-control" required>
                                <option value="">== Pilih Program ==</option>
                                <option value="KONSTAN">KONSTAN</option>
                                <option value="RAISING">RAISING</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <!-- <input type="text" id="keterangan" name="keterangan" class="form-control" required> -->
                             <select id="keterangan" name="keterangan" class="form-control" required>
                                <option value="">== Pilih Keterangan ==</option>
                                <option value="POLY">POLY</option>
                                <option value="COTTON">COTTON</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for Edit Master Mesin -->
    <div class="modal fade" tabindex="-1" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Master Mesin</h4>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="edit_id" name="id">

                        <div class="form-group">
                            <label for="edit_no_machine">No. Machine</label>
                            <input type="text" id="edit_no_machine" name="no_machine" class="form-control" placeholder="Masukan No. Machine" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_suhu">Suhu</label>
                            <div class="input-group">
                                <input type="number" id="edit_suhu" name="suhu" class="form-control" placeholder="Masukan Suhu">
                                <span class="input-group-addon">°C</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_program">Program</label>
                            <select id="edit_program" name="program" class="form-control" required>
                                <option value="">== Pilih Program ==</option>
                                <option value="KONSTAN">KONSTAN</option>
                                <option value="RAISING">RAISING</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_keterangan">Keterangan</label>
                            <select id="edit_keterangan" name="keterangan" class="form-control" required>
                                <option value="">== Pilih Keterangan ==</option>
                                <option value="POLY">POLY</option>
                                <option value="COTTON">COTTON</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#masterMesinTable').DataTable({
                "pageLength": 50,
                "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "ALL"] ],
                "ajax": "pages/ajax/fetch_master_mesin.php",
                "columns": [
                    {
                        "data": null,
                        "className": "text-center",
                        "render": function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { "data": "no_machine", "className": "text-center" },
                    { 
                        "data": "suhu",
                        "className": "text-center",
                        "render": function(data, type, row, meta) {
                            return data ? data : '-';
                        }
                    },
                    { "data": "program" },
                    { "data": "keterangan" },
                    {
                        data: 'id',
                        "className": "text-center",
                        render: function(data, type, row, meta) {
                            return `
                                    <button class="btn btn-warning btn-sm" onclick="editData(${data})"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteData(${data})"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                `;
                        }
                    }
                ],
                "language": {
                    "emptyTable": "No Data."
                }
            });

            $('#addModal').on('hidden.bs.modal', function () {
                $('#addForm')[0].reset();
            });
        });

        // Handling the modal form submission
        // $('#addForm').on('submit', function(event) {
        //     event.preventDefault();

        //     const formData = $(this).serialize();

        //     $.ajax({
        //         url: 'pages/ajax/add_master_suhu.php',
        //         method: 'POST',
        //         data: formData,
        //         success: function(response) {
        //             $('#addModal').modal('hide');
        //              Swal.fire({
        //                 icon: response.status === 'success' ? 'success' : 'error',
        //                 title: response.status === 'success' ? 'Berhasil' : 'Gagal',
        //                 text: response.message,
        //                 timer: 2000,
        //                 showConfirmButton: false
        //             });
        //             if (response.status === 'success') {
        //                 $('#masterMesinTable').DataTable().ajax.reload();
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Error',
        //                 text: 'Terjadi kesalahan saat mengirim data.',
        //                 footer: `<pre>${xhr.responseText}</pre>`
        //             });
        //         }
        //     });
        // });

        $('#addForm').on('submit', function(event) {
            event.preventDefault();

            const formData = $(this).serialize();
            const noMachine = $('#no_machine').val().trim();

            $.ajax({
                url: 'pages/ajax/check_no_machine_exists.php',
                method: 'GET',
                data: { no_machine: noMachine },
                success: function(response) {
                    console.log(response);
                    
                    if (response.status === 'exists') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Nomor Machine sudah ada di database!'
                        });
                    } else {
                        // Jika no_machine belum ada, lanjutkan submit form
                        $.ajax({
                            url: 'pages/ajax/add_master_mesin.php',
                            method: 'POST',
                            data: formData,
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data berhasil ditambahkan.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                $('#addModal').modal('hide');
                                $('#masterMesinTable').DataTable().ajax.reload();

                                // Reset input setelah submit
                                $('#no_machine').val('');
                                $('#suhu').val('');
                                $('#program').val('');
                                $('#keterangan').val('');
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menyimpan data.'
                                });
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Status: " + status);
                    console.log("Error: " + error);
                    console.log("Response: " + xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat pengecekan nama produk.'
                    });
                }
            });
        });

        function deleteData(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'pages/ajax/Delete_MasterMesin.php',
                        method: 'POST',
                        data: { id: id },
                        dataType: 'json',
                        success: function(response) {
                            Swal.fire({
                                icon: response.status,
                                title: response.status === 'success' ? 'Berhasil' : 'Gagal',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            if (response.status === 'success') {
                                $('#masterMesinTable').DataTable().ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan Server',
                                text: 'Gagal menghapus data.',
                                footer: `<pre>${xhr.responseText}</pre>`
                            });
                        }
                    });
                }
            });
        }

        function editData(id) {
            $.ajax({
                url: 'pages/ajax/get_master_mesin.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        var suhu = data.suhu ? data.suhu.replace(/[^0-9.]/g, '') : '';

                        $('#edit_id').val(data.id);
                        $('#edit_no_machine').val(data.no_machine);
                        $('#edit_suhu').val(suhu);
                        $('#edit_program').val(data.program);
                        $('#edit_keterangan').val(data.keterangan);
                        $('#editModal').modal('show');
                    }
                },
                error: function() {
                    alert('Gagal mengambil data.');
                }
            });
        }


        $('#editForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'pages/ajax/update_master_mesin.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        $('#editModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data berhasil diperbarui.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#masterMesinTable').DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: res.error || 'Terjadi kesalahan saat menyimpan data.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Tidak dapat terhubung ke server.'
                    });
                }
            });
        });

    </script>

    <script>
        document.getElementById('suhu').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9.]/g, '');
        });
    </script>

</body>
