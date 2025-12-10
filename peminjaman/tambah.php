<?php
include "../config/db.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $id_anggota = mysqli_real_escape_string($koneksi, $_POST['id_anggota']);
    $id_pustakawan = mysqli_real_escape_string($koneksi, $_POST['id_pustakawan']); // ambil dari select/atau session
    $tanggal_pinjam = mysqli_real_escape_string($koneksi, $_POST['tanggal_pinjam']);
    $tanggal_jatuh_tempo = mysqli_real_escape_string($koneksi, $_POST['tanggal_jatuh_tempo']);

    $id_buku_array = $_POST['id_buku'] ?? [];
    $jumlah_array = $_POST['jumlah'] ?? [];

    if (count($id_buku_array) == 0) {
        $error = "Pilih minimal 1 buku.";
    } else {
        mysqli_autocommit($koneksi, FALSE);
        $ok = true;
        $msg = "";

        $r = mysqli_query($koneksi, "SELECT COALESCE(MAX(id_peminjaman),0) + 1 AS next_id FROM peminjaman");
        $row = mysqli_fetch_assoc($r);
        $next_peminjaman = $row['next_id'];

        for ($i = 0; $i < count($id_buku_array); $i++) {
            $id_buku = intval($id_buku_array[$i]);
            $jumlah = intval($jumlah_array[$i]);

            if ($jumlah <= 0) {
                $ok = false;
                $msg = "Jumlah harus lebih dari 0.";
                break;
            }

            $q = mysqli_query($koneksi, "SELECT stok, judul FROM buku WHERE id_buku = $id_buku");
            if (!$q || mysqli_num_rows($q) == 0) {
                $ok = false;
                $msg = "Buku dengan ID $id_buku tidak ditemukan.";
                break;
            }
            $d = mysqli_fetch_assoc($q);
            if ($jumlah > $d['stok']) {
                $ok = false;
                $msg = "Stok tidak cukup untuk buku '{$d['judul']}' (stok: {$d['stok']}, diminta: $jumlah).";
                break;
            }
        }

        if ($ok) {
            $tgl_pinjam_sql = "'$tanggal_pinjam'";
            $tgl_tempo_sql = "'$tanggal_jatuh_tempo'";
            $sql_header = "INSERT INTO peminjaman (id_peminjaman, id_anggota, id_pustakawan, tanggal_pinjam, tanggal_jatuh_tempo, status)
                           VALUES ($next_peminjaman, '$id_anggota', '$id_pustakawan', $tgl_pinjam_sql, $tgl_tempo_sql, 'dipinjam')";
            if (!mysqli_query($koneksi, $sql_header)) {
                $ok = false;
                $msg = "Gagal insert peminjaman: " . mysqli_error($koneksi);
            } else {
                $r2 = mysqli_query($koneksi, "SELECT COALESCE(MAX(id_detail),0) + 1 AS next_detail FROM detail_peminjaman");
                $row2 = mysqli_fetch_assoc($r2);
                $next_detail = intval($row2['next_detail']);

                for ($i = 0; $i < count($id_buku_array); $i++) {
                    $id_buku = intval($id_buku_array[$i]);
                    $jumlah = intval($jumlah_array[$i]);

                    $sql_detail = "INSERT INTO detail_peminjaman (id_detail, id_peminjaman, id_buku, jumlah)
                                   VALUES ($next_detail, $next_peminjaman, $id_buku, $jumlah)";
                    if (!mysqli_query($koneksi, $sql_detail)) {
                        $ok = false;
                        $msg = "Gagal insert detail: " . mysqli_error($koneksi);
                        break;
                    }

                    if (!mysqli_query($koneksi, "UPDATE buku SET stok = stok - $jumlah WHERE id_buku = $id_buku")) {
                        $ok = false;
                        $msg = "Gagal update stok: " . mysqli_error($koneksi);
                        break;
                    }

                    $next_detail++;
                }
            }
        }

        if ($ok) {
            mysqli_commit($koneksi);
            mysqli_autocommit($koneksi, TRUE);
            $success = "Peminjaman berhasil disimpan.";
            header("Location: index.php");
            exit;
        } else {
            mysqli_rollback($koneksi);
            mysqli_autocommit($koneksi, TRUE);
            $error = $msg ?: "Terjadi kesalahan. Transaksi dibatalkan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body >

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="container p-4">
    <h3 class="mb-3">Tambah Peminjaman</h3>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" id="formPeminjaman" class="card p-3">

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Anggota</label>
                <select name="id_anggota" class="form-select" required>
                    <option value="">-- Pilih Anggota --</option>
                    <?php
                    $qa = mysqli_query($koneksi, "SELECT id_anggota, nama FROM anggota ORDER BY nama ASC");
                    while ($a = mysqli_fetch_assoc($qa)) {
                        echo "<option value='{$a['id_anggota']}'>{$a['nama']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6">
                <label>Pustakawan</label>
                <select name="id_pustakawan" class="form-select" required>
                    <option value="">-- Pilih Pustakawan --</option>
                    <?php
                    $qs = mysqli_query($koneksi, "SELECT id_pustakawan, nama FROM pustakawan ORDER BY nama ASC");
                    while ($s = mysqli_fetch_assoc($qs)) {
                        echo "<option value='{$s['id_pustakawan']}'>{$s['nama']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-6">
                <label>Tanggal Jatuh Tempo</label>
                <input type="date" name="tanggal_jatuh_tempo" class="form-control" required>
            </div>
        </div>

        <hr>

        <h5>Daftar Buku</h5>

        <table class="table table-sm" id="tblDetail">
            <thead>
                <tr>
                    <th>Buku</th>
                    <th width="120">Jumlah</th>
                    <th width="80">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="id_buku[]" class="form-select" required>
                            <option value="">-- Pilih Buku --</option>
                            <?php
                            $qb = mysqli_query($koneksi, "SELECT id_buku, judul, stok FROM buku ORDER BY judul ASC");
                            while ($b = mysqli_fetch_assoc($qb)) {
                                echo "<option value='{$b['id_buku']}' data-stok='{$b['stok']}'>{$b['judul']} (stok: {$b['stok']})</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td><input type="number" name="jumlah[]" class="form-control" min="1" value="1" required></td>
                    <td><button type="button" class="btn btn-danger btn-sm btnRemove">-</button></td>
                </tr>
            </tbody>
        </table>

        <div class="mb-3">
            <button type="button" id="btnAdd" class="btn btn-secondary btn-sm">Tambah Baris Buku</button>
        </div>

        <div>
            <button type="submit" name="simpan" class="btn btn-primary">Simpan Peminjaman</button>
            <a href="daftar.php" class="btn btn-secondary">Batal</a>
        </div>

    </form>
</div>

<script>
// Tambah/Remove baris detail peminjaman (simple)
document.getElementById('btnAdd').addEventListener('click', function(){
    const tbody = document.querySelector('#tblDetail tbody');
    const row = document.querySelector('#tblDetail tbody tr').cloneNode(true);
    // reset selected & jumlah
    row.querySelectorAll('select, input').forEach(el => {
        if (el.tagName.toLowerCase() === 'select') el.selectedIndex = 0;
        if (el.tagName.toLowerCase() === 'input') el.value = 1;
    });
    tbody.appendChild(row);
});

// delegate remove buttons
document.querySelector('#tblDetail').addEventListener('click', function(e){
    if (e.target && e.target.classList.contains('btnRemove')) {
        const tbody = document.querySelector('#tblDetail tbody');
        if (tbody.querySelectorAll('tr').length > 1) {
            e.target.closest('tr').remove();
        } else {
            // jika tinggal 1 baris, kosongkan isinya
            e.target.closest('tr').querySelector('select').selectedIndex = 0;
            e.target.closest('tr').querySelector('input').value = 1;
        }
    }
});
</script>

</body>
</html>
