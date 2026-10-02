<?php
if (isset($_POST['submit'])) {
    $password = $mysqli->real_escape_string($_POST['password']);
    $konfirmasi_password = $mysqli->real_escape_string($_POST['konfirmasi_password']);

    if ($password == $konfirmasi_password) {
        if ($mysqli->query("UPDATE user SET password='$password' WHERE id=" . $_SESSION['user']['id'])) {
            echo "<script>alert('Berhasil mengganti password');</script>";
        } else echo "Error: " . $q . "<br>" . $mysqli->error;
    } else
        echo "<script>alert('Password tidak sama!');</script>";
}
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row justify-content-center mb-2">
            <div class="col-sm-6">
                <h1 class="text-center">Ganti Password</h1>
            </div>
        </div>
    </div>
</section>
<form action="" method="POST">
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="password">Password Baru</label>
                                <input type="password" class="form-control" name="password" id="password" required autofocus autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="konfirmasi_password">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="konfirmasi_password" id="konfirmasi_password" autocomplete="off" required>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary float-right" name="submit">Ganti Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>