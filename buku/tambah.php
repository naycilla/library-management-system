<?php 
include "../config/db.php"; 
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori_buku ORDER BY nama_kategori ASC");
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="p-4">
    <div class="container">
        <h3 class="mb-3">Tambah Buku</h3>
    
        <form method="POST">
    
            <div class="mb-3">
                <label>Kategori Buku</label>
                <select name="id_kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                        <option value="<?= $k['id_kategori'] ?>">
                            <?= $k['nama_kategori'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
    
            <div class="mb-3">
                <label>Judul Buku</label>
                <input type="text" name="judul" class="form-control" required>
            </div>
    
            <div class="mb-3">
                <label>Penulis</label>
                <input type="text" name="penulis" class="form-control">
            </div>
    
            <div class="mb-3">
                <label>Penerbit</label>
                <input type="text" name="penerbit" class="form-control">
            </div>
    
            <div class="mb-3">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="form-control">
            </div>
    
            <div class="mb-3">
                <label>ISBN</label>
                <input type="text" name="isbn" class="form-control">
            </div>
    
            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" required>
            </div>
    
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
    
        </form>
    </div>

</div>

</body>
</html>

<?php
if (isset($_POST['simpan'])) {

    mysqli_query($koneksi, "INSERT INTO buku (id_kategori, judul, penulis, penerbit, tahun_terbit, isbn, stok)
                            VALUES (
                                '$_POST[id_kategori]',
                                '$_POST[judul]',
                                '$_POST[penulis]',
                                '$_POST[penerbit]',
                                '$_POST[tahun_terbit]',
                                '$_POST[isbn]',
                                '$_POST[stok]'
                            )");

    echo "<script>alert('Buku berhasil ditambahkan');location='index.php';</script>";
}
?>
