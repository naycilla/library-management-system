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
    <title>Data Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body >

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="container p-4">
    <h3 class="mb-3">Data Peminjaman</h3>

    <a href="tambah.php" class="btn btn-primary mb-3">Tambah Peminjaman</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Anggota</th>
                <th>Pustakawan</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "
                SELECT 
                    p.id_peminjaman,
                    p.tanggal_pinjam,
                    p.tanggal_jatuh_tempo,
                    p.status,
                    a.nama AS nama_anggota,
                    u.nama AS nama_pustakawan
                FROM peminjaman p
                JOIN anggota a ON p.id_anggota = a.id_anggota
                JOIN pustakawan u ON p.id_pustakawan = u.id_pustakawan
                ORDER BY p.id_peminjaman DESC
            ";
            $query = mysqli_query($koneksi, $sql);

            while ($row = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?= $row['id_peminjaman'] ?></td>
                <td><?= $row['nama_anggota'] ?></td>
                <td><?= $row['nama_pustakawan'] ?></td>
                <td><?= $row['tanggal_pinjam'] ?></td>
                <td><?= $row['tanggal_jatuh_tempo'] ?></td>
                <td>
                    <span class="badge bg-<?= $row['status']=='dipinjam' ? 'warning' : 'success' ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
                <td>
                    <a href="detail.php?id=<?= $row['id_peminjaman'] ?>" class="btn btn-info btn-sm">Detail</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</div>

</body>
</html>
