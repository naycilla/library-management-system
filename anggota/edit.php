<?php
include "../config/db.php";

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}


$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM anggota WHERE id_anggota=$id"));

if (isset($_POST['update'])) {
    $nama    = $_POST['nama'];
    $alamat  = $_POST['alamat'];
    $no_hp   = $_POST['no_hp'];
    $email   = $_POST['email'];
    $status  = $_POST['status'];

    $query = "UPDATE anggota SET 
                nama='$nama',
                alamat='$alamat',
                no_hp='$no_hp',
                email='$email',
                status='$status'
              WHERE id_anggota=$id";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal mengupdate: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="p-4">
    <div class="container col-md-7">
        <h3 class="mb-3">Edit Anggota</h3>
    
        <form method="POST">
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control"
                       value="<?= $data['nama'] ?>" required>
            </div>
    
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" required><?= $data['alamat'] ?></textarea>
            </div>
    
            <div class="mb-3">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control"
                       value="<?= $data['no_hp'] ?>" required>
            </div>
    
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= $data['email'] ?>" required>
            </div>
    
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select" required>
                    <option value="aktif" <?= $data['status'] == "aktif" ? "selected" : "" ?>>Aktif</option>
                    <option value="nonaktif" <?= $data['status'] == "nonaktif" ? "selected" : "" ?>>Nonaktif</option>
                </select>
            </div>
    
            <button type="submit" name="update" class="btn btn-warning">Update</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    
    </div>

</div>

</body>
</html>
