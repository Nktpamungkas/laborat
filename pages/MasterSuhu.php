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
    #masterSuhuTable {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        font-size: 10pt !important;
    }

    #masterSuhuTable td,
    #masterSuhuTable th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #masterSuhuTable tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #masterSuhuTable tr:hover {
        background-color: rgb(151, 170, 212);
    }

    #masterSuhuTable th {
        padding-top: 10px;
        padding-bottom: 10px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
</style>

<body>   

    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-header">
                    <a href="#" data-toggle="modal" data-target="#addModal" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i> Add</a>
                </div>
                <div class="box-body">
                    <table width="100%" class="table table-bordered table-hover display" id="masterSuhuTable" style="border: 1px solid #595959; padding:5px;">
                        <thead class="btn-primary">
                            <tr>
                                <th>No.</th>
                                <th>Code</th>
                                <th>Group</th>
                                <th>Product Name</th>
                                <th>Program</th>
                                <th>Keterangan</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for Add New Master Suhu -->
    <div class="modal" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add New Master Suhu</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="addForm">
                        <div class="form-group">
                            <label for="product_name">Product Name</label>
                            <input type="text" id="product_name" name="product_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="program">Program</label>
                            <select id="program" name="program" class="form-control" required>
                                <option value="KONSTAN">KONSTAN</option>
                                <option value="RAISING">RAISING</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <!-- <input type="text" id="keterangan" name="keterangan" class="form-control" required> -->
                             <select id="keterangan" name="keterangan" class="form-control" required>
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

    <script>
        $(document).ready(function() {
            $('#masterSuhuTable').DataTable({
                "ajax": "pages/ajax/fetch_master_suhu.php",
                "columns": [
                    {
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { "data": "code" },
                    { "data": "group" },
                    { "data": "product_name" },
                    { "data": "program" },
                    { "data": "keterangan" },
                    {
                        data: 'id',
                        render: function(data, type, row, meta) {
                            return `<button class="btn btn-danger btn-sm" onclick="deleteData(${data})">Delete</button>`;
                        }
                    }
                ]
            });
        });

        // Handling the modal form submission
        $('#addForm').on('submit', function(event) {
            event.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                url: 'pages/ajax/add_master_suhu.php',
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#addModal').modal('hide');
                     Swal.fire({
                        icon: response.status === 'success' ? 'success' : 'error',
                        title: response.status === 'success' ? 'Berhasil' : 'Gagal',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    if (response.status === 'success') {
                        $('#masterSuhuTable').DataTable().ajax.reload();
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengirim data.',
                        footer: `<pre>${xhr.responseText}</pre>`
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
                        url: 'pages/ajax/Delete_MasterSuhu.php',
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
                                $('#masterSuhuTable').DataTable().ajax.reload();
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

    </script>

</body>
