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
    <title>Data Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="p-4">
    <div class="container">
        <h3 class="mb-3">Data Anggota</h3>
    
        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Anggota</a>
    
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th width="70">ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($koneksi, "SELECT * FROM anggota ORDER BY id_anggota DESC");
                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                <tr>
                    <td><?= $row['id_anggota'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['alamat'] ?></td>
                    <td><?= $row['no_hp'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td>
                        <span class="badge bg-<?= $row['status'] == 'aktif' ? 'success' : 'secondary' ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id_anggota'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus.php?id=<?= $row['id_anggota'] ?>" 
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
