<?php
include "../config/db.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_peminjaman = $_GET['id'];

$q = mysqli_query($koneksi, "
    SELECT p.*, 
           a.nama AS nama_anggota,
           ps.nama AS nama_pustakawan
    FROM peminjaman p
    JOIN anggota a ON p.id_anggota = a.id_anggota
    JOIN pustakawan ps ON p.id_pustakawan = ps.id_pustakawan
    WHERE p.id_peminjaman = '$id_peminjaman'
");

$header = mysqli_fetch_assoc($q);

$detail = mysqli_query($koneksi, "
    SELECT d.*, b.judul, b.penulis
    FROM detail_peminjaman d
    JOIN buku b ON d.id_buku = b.id_buku
    WHERE d.id_peminjaman = '$id_peminjaman'
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="container p-4">

    <h3 class="mb-4">Detail Peminjaman</h3>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong>Peminjaman #<?= $header['id_peminjaman'] ?></strong>
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Nama Anggota:</strong><br><?= $header['nama_anggota'] ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Pustakawan:</strong><br><?= $header['nama_pustakawan'] ?></p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <p><strong>Tanggal Pinjam:</strong><br><?= $header['tanggal_pinjam'] ?></p>
                </div>
                <div class="col-md-4">
                    <p><strong>Jatuh Tempo:</strong><br><?= $header['tanggal_jatuh_tempo'] ?></p>
                </div>
                <div class="col-md-4">
                    <p><strong>Status:</strong><br>
                        <span class="badge bg-<?= 
                            $header['status'] == 'dipinjam' ? 'warning' : 'success'
                        ?>">
                            <?= ucfirst($header['status']) ?>
                        </span>
                    </p>
                </div>
            </div>

        </div>
    </div>

    <h5 class="mb-3">Daftar Buku yang Dipinjam</h5>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID Detail</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            while ($d = mysqli_fetch_assoc($detail)) {
                $total += $d['jumlah'];
            ?>
            <tr>
                <td><?= $d['id_detail'] ?></td>
                <td><?= $d['judul'] ?></td>
                <td><?= $d['penulis'] ?></td>
                <td><?= $d['jumlah'] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <p><strong>Total Item:</strong> <?= $total ?></p>

    <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>

</div>

</body>
</html>
