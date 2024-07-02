<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
$modal_id = $_GET['id'];
$modal = mysqli_query($con, "SELECT * FROM `tbl_test_qc` WHERE id='$modal_id' ");
while ($r = mysqli_fetch_array($modal)) {
?>
    <div class="modal-dialog ">
        <div class="modal-content">
            <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=edit_note_laborat" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Hapus Tes</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" value="<?php echo $r['id']; ?>">
                    <h5>Anda yakin menghapus tes ini ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-primary">Ya</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
<?php } ?>