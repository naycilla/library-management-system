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
    <title>Data Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="p-4">
    <div class="container">
        <h3 class="mb-3">Data Buku</h3>
    
        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Buku</a>
    
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th width="70">ID</th>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>ISBN</th>
                    <th>Stok</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($koneksi, "
                    SELECT b.*, k.nama_kategori 
                    FROM buku b
                    LEFT JOIN kategori_buku k ON b.id_kategori = k.id_kategori
                    ORDER BY b.id_buku 
                ");
    
                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                <tr>
                    <td><?= $row['id_buku'] ?></td>
                    <td><?= $row['nama_kategori'] ?></td>
                    <td><?= $row['judul'] ?></td>
                    <td><?= $row['penulis'] ?></td>
                    <td><?= $row['penerbit'] ?></td>
                    <td><?= $row['tahun_terbit'] ?></td>
                    <td><?= $row['isbn'] ?></td>
                    <td>
                        <span class="badge bg-<?= $row['stok'] <= 3 ? 'danger' : 'success' ?>">
                            <?= $row['stok'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id_buku'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus.php?id=<?= $row['id_buku'] ?>" 
                           onclick="return confirm('Yakin ingin menghapus buku ini?')"
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
