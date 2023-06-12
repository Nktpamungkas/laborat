<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
session_start();
$data_status_sql = mysqli_query($con,"SELECT * from tbl_status_matching where id = '$_GET[idm]' LIMIT 1");
$data_status = mysqli_fetch_array($data_status_sql);
?>
<div class="modal-content">
    <div class="modal-body">
        <form action="#" class="form-horizontal" id="form-note">
            <input type="hidden" id="id_status" value="<?php echo $_GET['idm'] ?>">
            <div class="container-fluid">
                <h4><strong><i class="fa fa-plus"></i> Note <?php echo $data_status['idm'] ?></strong></h4>
                <div class="form-group">
                    <label for="Jenis_Matching" class="col-sm-2 control-label">Jenis Note</label>
                    <div class="col-sm-9">
                        <select type="text" class="form-control" name="Jenis_Note" id="Jenis_Note" placeholder="Jenis Matching">
                            <option disabled selected value="">-Pilih-</option>
                            <option value="Catatan">Catatan</option>
                            <option value="Himbauan">Himbauan</option>
                            <option value="Peringatan">Peringatan</option>
                        </select>
                        <div class="invalid-feedback text-danger" style="display:none;">
                            This field is required !
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Jenis_Matching" class="col-sm-2 control-label">Note</label>
                    <div class="col-sm-9">
                        <textarea rows="5" type="text" class="form-control" name="note" id="note" placeholder="Masukan Catatan ... "></textarea>
                        <div class="invalid-feedback text-danger" style="display: none;">
                            This field is required !
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid black; height: 45px;">
                <div class="pull-right">
                    <button type="button" class="btn btn-info" id="submit">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#submit').click(function() {
            let jenis_note = $('#Jenis_Note').find('option:selected').val();
            let note = $('#note').val();

            if (jenis_note == '') {
                $('#Jenis_Note').next().show()
            } else if (note == '') {
                $('#note').next().show()
            } else {
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    url: "pages/ajax/InsertNoteCelup.php",
                    data: {
                        id_status: $('#id_status').val(),
                        jenis_note: jenis_note,
                        note: note
                    },
                    success: function(response) {
                        $('#ModalAddNote').modal('hide');
                        toastr.success('Note telah di tambahkan dalam timeline')
                    },
                    error: function() {
                        alert("Hubungi Departement DIT !");
                    }
                });
            }
        })
    })
</script>