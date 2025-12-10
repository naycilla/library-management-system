<?php 
include "../config/db.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_kategori'];

    $query = "INSERT INTO kategori_buku (nama_kategori)
              VALUES ('$nama')";

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
    <title>Tambah Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body >

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="p-4">
    <div class="container col-md-6">
        <h3 class="mb-3">Tambah Kategori</h3>
    
        <form method="POST">
            <div class="mb-3">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" required>
            </div>
    
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>


</body>
</html>
