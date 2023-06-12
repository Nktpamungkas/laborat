<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
$sql = mysqli_query($con,"SELECT * FROM `announcement`");
$announ = mysqli_fetch_array($sql);
?>
<div class="tab-content">
    <div class="tab-pane active" id="tab_1">
        <form class="form-horizontal" method="POST" action="">
            <div class="col-lg-12">
                <div class="form-group row">
                    <!-- <label class="col-lg-4">Announcement</label> -->
                    <div class="col-lg-9">
                        <textarea class="form-control" required autocomplete="off" name="announcement" id="announcement"><?php echo $announ['ann'] ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <!-- <label class="col-lg-4">Announcement</label> -->
                    <div class="col-lg-9">
                        <Select class="form-control" name="is_active">
                            <option <?php if ($announ['is_active'] == '1') echo 'selected' ?> value="1">Enabled</option>
                            <option <?php if ($announ['is_active'] == '0') echo 'selected' ?> value="0">Disabled</option>
                        </Select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block">Change!</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
if ($_POST['submit']) {
    $ann = mysqli_real_escape_string($con,$_POST['announcement']);
    mysqli_query($con,"UPDATE `announcement` SET 
    `is_active` = '$_POST[is_active]', 
    `ann` = '$ann' 
    where id = 1");
    echo '<script>window.location="index1.php?p=announcement"</script>';
}
?>