<?php include "../config/db.php"; ?>

<?php
if (isset($_POST['register'])) {
    $nama      = $_POST['nama'];
    $username  = $_POST['username'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role      = $_POST['role'];

    $query = "INSERT INTO pustakawan (nama, username, password_hash, role, created_at)
              VALUES ('$nama', '$username', '$password', '$role', NOW())";

    if (mysqli_query($koneksi, $query)) {
        header("Location: login.php?success=1");
        exit;
    } else {
        echo "Gagal mendaftar: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Pustakawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h4>Register Pustakawan</h4>
            </div>
            <div class="card-body">

                <form method="POST">
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="pustakawan">Pustakawan</option>
                        </select>
                    </div>

                    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                    <a href="login.php" class="btn btn-link d-block text-center mt-2">Sudah punya akun? Login</a>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>
