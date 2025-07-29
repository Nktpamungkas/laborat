<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
$ip = $_SERVER['REMOTE_ADDR'];
$os = $_SERVER['HTTP_USER_AGENT'];
// var_dump($_SESSION);
// die;
?>
<?php
if ($_POST) { //login user
	extract($_POST);
	$username = mysqli_real_escape_string($con,$_POST['username']);
	$password = mysqli_real_escape_string($con,$_POST['password']);
	$sql = mysqli_query($con,"select * from tbl_user where username='$username' and password='$password' limit 1");
	if (mysqli_num_rows($sql) > 0) {
		$_SESSION['userLAB'] = $username;
		$_SESSION['passLAB'] = $password;
		$r = mysqli_fetch_array($sql);
		$_SESSION['id'] = $r['id'];
		$_SESSION['lvlLAB'] = $r['level'];
		$_SESSION['statusLAB'] = $r['status'];
		$_SESSION['mamberLAB'] = $r['mamber'];
		$_SESSION['fotoLAB'] = $r['foto'];
		$_SESSION['jabatanLAB'] = $r['jabatan'];
		$_SESSION['os'] = $os;
		$_SESSION['ip'] = $ip;
		// 1 == admin
		// 2 == spv
		// 3 == user
		//login_validate();
		mysqli_query($con,"INSERT into tbl_log SET `what` = 'login',
				`what_do` = 'login into laborat',
				`do_by` = '$_SESSION[userLAB]',
				`do_at` = '$time',
				`ip` = '$ip',
				`os` = '$os',
				`remark`='$_SESSION[jabatanLAB]'");
		echo "<script>window.location='index1.php?p=Home';</script>";
	} else {
		echo "<script>alert('Login Gagal!! $username');window.location='index.php';</script>";
	}
} elseif ($_GET['act'] == "logout") { //logout user

	if (isset($_SESSION['is_locked_owner']) && $_SESSION['is_locked_owner'] === true) {
		$lock_file = __DIR__ . '/access.lock';
		if (file_exists($lock_file)) {
			unlink($lock_file);
		}
	}

	mysqli_query($con,"INSERT into tbl_log SET
	`what` = 'Logout',
	`what_do` = 'Logout from laborat',
	`do_by` = '$_SESSION[userLAB]',
	`do_at` = '$time',
	`ip` = '$ip',
	`os` = '$os',
	`remark`='$_SESSION[jabatanLAB]'");
	session_destroy();
	echo "<script>window.location='login';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login Laborat</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="login_assets/images/icons/ITTI_Logo index.ico" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_assets/vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_assets/fonts/iconic/css/material-design-iconic-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_assets/vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_assets/vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_assets/vendor/animsition/css/animsition.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_assets/vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_assets/vendor/daterangepicker/daterangepicker.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="login_assets/css/main.css">
	<!--===============================================================================================-->
</head>

<body>

	<div class="limiter">
		<div class="container-login100" style="background-image: url('login_assets/images/247191809.jpg');">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="POST" action="">
					<span class="login100-form-logo">
						<img src="login_assets/logo-itti.png" alt="" width="80%" style="border-radius: 50%;">
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						LABORAT RESEP
					</span>

					<div class="wrap-input100 validate-input" data-validate="Enter username">
						<input class="input100" type="text" name="username" placeholder="Username" autofocus>
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit">
							Login
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!--===============================================================================================-->
	<script src="login_assets/vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="login_assets/vendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script src="login_assets/vendor/bootstrap/js/popper.js"></script>
	<script src="login_assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="login_assets/vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
	<script src="login_assets/vendor/daterangepicker/moment.min.js"></script>
	<script src="login_assets/vendor/daterangepicker/daterangepicker.js"></script>
	<!--===============================================================================================-->
	<script src="login_assets/vendor/countdowntime/countdowntime.js"></script>
	<!--===============================================================================================-->
	<script src="login_assets/js/main.js"></script>

</body>

</html>