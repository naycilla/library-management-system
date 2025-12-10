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
    <title>Data Pengembalian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="container p-4">
    <h3 class="mb-3">Data Pengembalian</h3>

    <a href="tambah.php" class="btn btn-primary mb-3">Tambah Pengembalian</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID Pengembalian</th>
                <th>ID Detail</th>
                <th>Buku</th>
                <th>Peminjam</th>
                <th>Tgl Kembali</th>
                <th>Denda</th>
                <th>Kondisi</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
        <?php
            $sql = "
                SELECT 
                    pg.*,
                    d.id_peminjaman,
                    b.judul,
                    a.nama AS nama_anggota
                FROM pengembalian pg
                JOIN detail_peminjaman d ON pg.id_detail = d.id_detail
                JOIN buku b ON d.id_buku = b.id_buku
                JOIN peminjaman p ON d.id_peminjaman = p.id_peminjaman
                JOIN anggota a ON p.id_anggota = a.id_anggota
                ORDER BY pg.id_pengembalian DESC
            ";

            $query = mysqli_query($koneksi, $sql);

            while ($row = mysqli_fetch_assoc($query)) {
        ?>
            <tr>
                <td><?= $row['id_pengembalian'] ?></td>
                <td><?= $row['id_detail'] ?></td>
                <td><?= $row['judul'] ?></td>
                <td><?= $row['nama_anggota'] ?></td>
                <td><?= $row['tanggal_kembali'] ?></td>
                <td>Rp <?= number_format($row['denda'],0,',','.') ?></td>
                <td>
                    <span class="badge bg-<?=
                        $row['kondisi_buku'] == 'baik' ? 'success' : 
                        ($row['kondisi_buku'] == 'rusak' ? 'warning' : 'danger')
                    ?>">
                        <?= ucfirst($row['kondisi_buku']) ?>
                    </span>
                </td>
                <td><?= $row['keterangan'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>

</body>
</html>
