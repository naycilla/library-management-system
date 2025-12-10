<?php
include "../config/db.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$detail = mysqli_query($koneksi, "
    SELECT d.*, b.judul 
    FROM detail_peminjaman d
    JOIN buku b ON d.id_buku = b.id_buku
    WHERE d.id_detail NOT IN (SELECT id_detail FROM pengembalian)
    ORDER BY d.id_detail DESC
");

if (isset($_POST['simpan'])) {

    $id_detail = $_POST['id_detail'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $denda = $_POST['denda'];
    $kondisi = $_POST['kondisi_buku'];
    $ket = $_POST['keterangan'];

    $q = mysqli_query($koneksi, "
        SELECT id_buku, jumlah 
        FROM detail_peminjaman 
        WHERE id_detail = '$id_detail'
    ");
    $d = mysqli_fetch_assoc($q);

    $id_buku = $d['id_buku'];
    $jumlah_kembali = $d['jumlah'];

    mysqli_query($koneksi, "
        UPDATE buku 
        SET stok = stok + $jumlah_kembali
        WHERE id_buku = '$id_buku'
    ");

    mysqli_query($koneksi, "
        INSERT INTO pengembalian
        (id_pengembalian, id_detail, tanggal_kembali, denda, kondisi_buku, keterangan)
        VALUES
        ('$id_pengembalian','$id_detail','$tanggal_kembali','$denda','$kondisi','$ket')
    ");

    mysqli_query($koneksi, "
        UPDATE peminjaman p 
        SET status = 'sudah_dikembalikan'
        WHERE p.id_peminjaman = (
            SELECT id_peminjaman FROM detail_peminjaman WHERE id_detail = '$id_detail'
        )
        AND NOT EXISTS (
            SELECT * FROM detail_peminjaman dp
            WHERE dp.id_peminjaman = p.id_peminjaman
            AND dp.id_detail NOT IN (SELECT id_detail FROM pengembalian)
        )
    ");

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengembalian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="container p-4">
    <h3 class="mb-3">Tambah Pengembalian</h3>

    <form method="post" class="card p-4 shadow-sm">

        <div class="mb-3">
            <label>Pilih Detail Peminjaman</label>
            <select name="id_detail" class="form-select" required>
                <option value="">-- pilih detail --</option>
                <?php while($d = mysqli_fetch_assoc($detail)) { ?>
                    <option value="<?= $d['id_detail'] ?>">
                        <?= $d['id_detail'] ?> - <?= $d['judul'] ?> (Jumlah: <?= $d['jumlah'] ?>)
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Denda</label>
            <input type="number" name="denda" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label>Kondisi Buku</label>
            <select name="kondisi_buku" class="form-select">
                <option value="baik">Baik</option>
                <option value="rusak">Rusak</option>
                <option value="hilang">Hilang</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>

        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>

    </form>
</div>

</body>
</html>
