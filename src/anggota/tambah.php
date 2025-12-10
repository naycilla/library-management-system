<?php 
include "../config/db.php";

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}


if (isset($_POST['simpan'])) {
    $nama    = $_POST['nama'];
    $alamat  = $_POST['alamat'];
    $no_hp   = $_POST['no_hp'];
    $email   = $_POST['email'];
    $status  = $_POST['status'];

    $query = "INSERT INTO anggota (nama, alamat, no_hp, email, status, created_at)
              VALUES ('$nama', '$alamat', '$no_hp', '$email', '$status', NOW())";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal menyimpan: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body >

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="p-4">
    <div class="container col-md-7">
        <h3 class="mb-3">Tambah Anggota</h3>
    
        <form method="POST">
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
    
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" required></textarea>
            </div>
    
            <div class="mb-3">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" required>
            </div>
    
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
    
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select" required>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
    
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

</body>
</html>
