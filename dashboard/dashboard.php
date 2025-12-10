<?php

include '../config/db.php';

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$q_buku = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku");
$buku = mysqli_fetch_assoc($q_buku)['total'];

$q_anggota = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM anggota");
$anggota = mysqli_fetch_assoc($q_anggota)['total'];

$q_pinjam = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM peminjaman WHERE status='dipinjam'");
$peminjaman = mysqli_fetch_assoc($q_pinjam)['total'];

$q_overdue = mysqli_query($koneksi, "
    SELECT p.id_peminjaman, a.nama, p.tanggal_pinjam, p.tanggal_jatuh_tempo
    FROM peminjaman p
    JOIN anggota a ON p.id_anggota = a.id_anggota
    WHERE p.status='dipinjam' AND p.tanggal_jatuh_tempo < CURDATE()
    ORDER BY p.tanggal_jatuh_tempo ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>


<div class="container mt-4">

    <h3 class="mb-4 fw-bold">Dashboard Perpustakaan</h3>

    <!-- CARD Summary -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3">
                <h5 class="fw-bold text-primary">Jumlah Buku</h5>
                <h2 class="fw-bold"><?= $buku ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3">
                <h5 class="fw-bold text-success">Jumlah Anggota</h5>
                <h2 class="fw-bold"><?= $anggota ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3">
                <h5 class="fw-bold text-danger">Peminjaman Aktif</h5>
                <h2 class="fw-bold"><?= $peminjaman ?></h2>
            </div>
        </div>
    </div>

    <!-- OVERDUE TABLE -->
    <div class="card mt-5 shadow-sm">
        <div class="card-header bg-danger text-white fw-bold">
            Peminjaman Overdue
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Anggota</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Terlambat (hari)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($q_overdue) === 0): ?>
                        <tr>
                            <td colspan="5" class="text-center py-3">Tidak ada peminjaman overdue.</td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($q_overdue)) : 
                            $late = (strtotime(date("Y-m-d")) - strtotime($row['tanggal_jatuh_tempo'])) / 86400;
                        ?>
                            <tr>
                                <td><?= $row['id_peminjaman'] ?></td>
                                <td><?= $row['nama'] ?></td>
                                <td><?= $row['tanggal_pinjam'] ?></td>
                                <td class="text-danger fw-bold"><?= $row['tanggal_jatuh_tempo'] ?></td>
                                <td class="fw-bold text-danger"><?= $late ?> hari</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
