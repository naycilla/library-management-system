<?php 
include "../config/db.php"; 
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
    <title>Data Kategori Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body >

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="p-4">
    <div class="container">
        <h3 class="mb-3">Kategori Buku</h3>
        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Kategori</a>
    
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th width="80">ID</th>
                    <th>Nama Kategori</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($koneksi, "SELECT * FROM kategori_buku ORDER BY id_kategori");
                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                <tr>
                    <td><?php echo $row['id_kategori']; ?></td>
                    <td><?php echo $row['nama_kategori']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id_kategori']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus.php?id=<?php echo $row['id_kategori']; ?>" 
                           onclick="return confirm('Yakin ingin menghapus?')" 
                           class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    
    </div>

</div>

</body>
</html>
