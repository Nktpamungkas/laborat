<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
$buyer = $_GET['id'];
$sqlFlag1 = mysqli_query($con,"SELECT lampu from vpot_lampbuy where buyer = '$buyer' and flag = 1 LIMIT 1 ");
$sqlFlag2 = mysqli_query($con,"SELECT lampu from vpot_lampbuy where buyer = '$buyer' and flag = 2 LIMIT 1 ");
$sqlFlag3 = mysqli_query($con,"SELECT lampu from vpot_lampbuy where buyer = '$buyer' and flag = 3 LIMIT 1 ");

$flag1 = mysqli_fetch_array($sqlFlag1);
$flag2 = mysqli_fetch_array($sqlFlag2);
$flag3 = mysqli_fetch_array($sqlFlag3);
?>
<div class="modal-content">
    <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=update_Vpot_lampu" enctype="multipart/form-data">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Data Lampu</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="code" class="col-md-3 control-label">Buyer</label>
                <div class="col-md-6">
                    <input type="text" readonly class="form-control" id="Buyer" name="Buyer" required value="<?php echo $buyer ?>">
                    <span class="help-block with-errors"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="Product_name" class="col-md-3 control-label">1. Lampu</label>
                <div class="col-md-6">
                    <?php $sqlLampu = mysqli_query($con,"SELECT nama_lampu from master_lampu"); ?>
                    <select style="width:300px" class="form-control selectLampu" name="lampu1">
                        <?php if (empty($flag1['lampu'])) { ?>
                            <option value="" selected disabled>pilih..</option>
                        <?php } else { ?>
                            <option selected value="<?php echo $flag1['lampu'] ?>" selected><?php echo $flag1['lampu'] ?></option>
                        <?php } ?>
                        <?php while ($lampu = mysqli_fetch_array($sqlLampu)) { ?>
                            <option value="<?php echo $lampu['nama_lampu'] ?>"><?php echo $lampu['nama_lampu'] ?></option>
                        <?php }  ?>
                    </select>
                    <span class="help-block with-errors"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="Product_name" class="col-md-3 control-label">2. Lampu</label>
                <div class="col-md-6">
                    <select style="width:300px" class="form-control selectLampu" name="lampu2">
                        <?php if (empty($flag2['lampu'])) { ?>
                            <option value="" selected disabled>pilih..</option>
                        <?php } else { ?>
                            <option selected value="<?php echo $flag2['lampu'] ?>" selected><?php echo $flag2['lampu'] ?></option>
                        <?php } ?>
                        <?php $sqlLampu = mysqli_query($con,"SELECT nama_lampu from master_lampu"); ?>
                        <?php while ($lampu = mysqli_fetch_array($sqlLampu)) { ?>
                            <option value="<?php echo $lampu['nama_lampu'] ?>"><?php echo $lampu['nama_lampu'] ?></option>
                        <?php }  ?>
                    </select>
                    <span class="help-block with-errors"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="Product_name" class="col-md-3 control-label">3. Lampu</label>
                <div class="col-md-6">
                    <select style="width:300px" class="form-control selectLampu" name="lampu3">
                        <?php if (empty($flag3['lampu'])) { ?>
                            <option value="" selected disabled>pilih..</option>
                        <?php } else { ?>
                            <option selected value="<?php echo $flag3['lampu'] ?>" selected><?php echo $flag3['lampu'] ?></option>
                        <?php } ?>
                        <?php $sqlLampu = mysqli_query($con,"SELECT nama_lampu from master_lampu"); ?>
                        <?php while ($lampu = mysqli_fetch_array($sqlLampu)) { ?>
                            <option value="<?php echo $lampu['nama_lampu'] ?>"><?php echo $lampu['nama_lampu'] ?></option>
                        <?php }  ?>
                    </select>
                    <span class="help-block with-errors"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>