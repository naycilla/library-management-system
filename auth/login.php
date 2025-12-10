<?php
session_start();
include "../config/db.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($koneksi, "SELECT * FROM pustakawan WHERE username='$username'");
    $data = mysqli_fetch_assoc($result);

    if ($data && password_verify($password, $data['password_hash'])) {
        $_SESSION['login'] = true;
        $_SESSION['id_pustakawan'] = $data['id_pustakawan'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        header("Location: ../dashboard/dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Pustakawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-5 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white text-center">
                <h4>Login Pustakawan</h4>
            </div>
            <div class="card-body">

                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>

                <?php if (isset($_GET['success'])) { ?>
                    <div class="alert alert-success">Registrasi berhasil! Silakan login.</div>
                <?php } ?>

                <form method="POST">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" name="login" class="btn btn-success w-100">Login</button>
                    <a href="register.php" class="btn btn-link d-block text-center mt-2">Belum punya akun? Daftar</a>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>
