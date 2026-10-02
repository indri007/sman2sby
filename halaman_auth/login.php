<?php
session_start();
include_once "../database/koneksi.php";
include_once "../utils/utils.php";

if (isset($_POST['submit'])) {
	$username = $mysqli->real_escape_string($_POST['username']);
	$password = $mysqli->real_escape_string($_POST['password']);

	$sql = "
		SELECT 
			user.*, 
			pegawai.id AS id_pegawai, 
			pegawai.nama, 
			pegawai.gambar 
		FROM 
			user 
		LEFT JOIN 
			pegawai 
		ON 
			user.id=pegawai.id_user 
		WHERE 
			user.username='$username' 
			AND 
			user.password='$password'";
	if ($result = $mysqli->query($sql)) {
		if ($result->num_rows) {
			$userData = $result->fetch_assoc();
			
			// Deteksi IP dan Perangkat
			$clientIP = getClientIP();
			$deviceInfo = getDeviceInfo();
			$summaryDevice = $deviceInfo['summary'];
			$browserName = $deviceInfo['browser'];
			$userId = $userData['id'];

			// Update riwayat IP dan Device login terakhir di DB
			$updateSql = "UPDATE user SET 
				ip_address = '$clientIP', 
				device_info = '$summaryDevice', 
				browser_info = '$browserName', 
				last_login = NOW() 
				WHERE id = '$userId'";
			$mysqli->query($updateSql);

			$userData['ip_address'] = $clientIP;
			$userData['device_info'] = $summaryDevice;
			$userData['browser_info'] = $browserName;
			$userData['last_login'] = date('Y-m-d H:i:s');

			$_SESSION['user'] = $userData;
			echo "<script>window.location.replace('../index.php');</script>";
		} else
			echo "<script>alert('Username atau Password Salah!');</script>";
	} else echo "Error: " . $sql . "<br>" . $mysqli->error;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Halaman Login</title>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/adminlte.min.css">
	<style>
		.btn-primary {
			background-color: #00640E !important;
			border: #00640E 1px solid;
		}

		.btn-primary:hover {
			background-color: #00500b !important;
			border: #00500b 1px solid;
		}

		.btn-outline-success {
			color: #00640E !important;
			border-color: #00640E !important;
		}

		.btn-outline-success:hover {
			background-color: #00640E !important;
			color: #ffffff !important;
		}
	</style>
</head>

<body class="hold-transition login-page">
	<div class="login-box">
		<div class="card">
			<div class="card-body login-card-body">
				<div class="d-flex flex-column align-items-center justify-content-center mb-3">
					<img src="../assets/img/logo.png" class="mb-3" width="150" height="150">
					<p class="login-box-msg" style="font-weight: bold;">APLIKASI ABSENSI</p>
				</div>
				<form action="" method="POST">
					<div class="input-group mb-3">
						<input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" autofocus>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-envelope"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" class="form-control" name="password" placeholder="Password">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
						</div>
					</div>
				</form>

				<div class="text-center mt-3 pt-3" style="border-top: 1px solid #e9ecef;">
					<a href="https://elearning.sman2sby.com/" target="_blank" class="btn btn-outline-success btn-block" style="font-weight: 600; padding: 8px 12px;">
						<i class="fas fa-graduation-cap mr-1"></i> Menuju E-Learning SMAN 2 Surabaya
					</a>
				</div>
			</div>
		</div>
	</div>

	<script src="../assets/plugins/jquery/jquery.min.js"></script>
	<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="../assets/js/adminlte.min.js"></script>
</body>

</html>