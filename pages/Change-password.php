<form class="form-horizontal" method="POST" action="">
    <div class="col-lg-6">
        <div class="form-group">
            <label class="col-lg-6">Password</label>
            <input type="password" class="form-control" required autocomplete="off" name="oldpw" placeholder="Password...." aria-describedby="sizing-addon1">
        </div>
        <div class="form-group">
            <label class="col-lg-6">New-password</label>
            <input type="password" class="form-control" required autocomplete="off" name="newpw" placeholder="Password...." aria-describedby="sizing-addon1">
        </div>
        <div class="form-group">
            <label class="col-lg-6">Retype New-password</label>
            <input type="password" minlength="5" class="form-control" required autocomplete="off" name="newpw2" placeholder="Password...." aria-describedby="sizing-addon1">
        </div>
        <div class="form-group">
            <div class="col-sm-6">
                <button type="submit" minlength="5" name="submit" value="submit" class="btn btn-primary">Change!</button>
            </div>
        </div>
    </div>
</form>

<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
if ($_POST['submit'] == 'submit') {
    if ($_POST['oldpw'] == $_SESSION['passLAB']) {
        if ($_POST['newpw'] == $_POST['newpw2']) {
            mysqli_query($con,"UPDATE tbl_user SET `password` = '$_POST[newpw]' where id = '$_SESSION[id]'");
            mysqli_query($con,"INSERT into tbl_log SET `what` = '$_SESSION[id]',
            `what_do` = 'Update tbl_user',
            `do_by` = '$_SESSION[userLAB]',
            `do_at` = '$time',
            `ip` = '$_SESSION[ip]',
            `os` = '$_SESSION[os]',
            `remark`='Change Password'");
            echo '<script>window.location.href = "logout"</script>';
        } else {
            echo '<script>alert("Password tidak sesuai !")</script>';
        }
    } else {
        echo '<script>alert("Password yang anda masukan salah !")</script>';
    }
}
?>