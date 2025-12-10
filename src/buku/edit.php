<?php 
include "../config/db.php"; 

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}


$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku=$id"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="p-4">
    <div class="container">

    <h3>Edit Buku</h3>

    <form method="POST">
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul" value="<?= $data['judul']; ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>Penulis</label>
            <input type="text" name="penulis" value="<?= $data['penulis']; ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>Penerbit</label>
            <input type="text" name="penerbit" value="<?= $data['penerbit']; ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>Tahun Terbit</label>
            <input type="number" name="tahun_terbit" value="<?= $data['tahun_terbit']; ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>ISBN</label>
            <input type="text" name="isbn" value="<?= $data['isbn']; ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" value="<?= $data['stok']; ?>" class="form-control">
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update</button>
    </form>
</div>

</div>    

</body>
</html>

<?php

if (isset($_POST['update'])) {

    mysqli_query($koneksi, "UPDATE buku SET 
            judul='$_POST[judul]',
            penulis='$_POST[penulis]',
            penerbit='$_POST[penerbit]',
            tahun_terbit='$_POST[tahun_terbit]',
            isbn='$_POST[isbn]',
            stok='$_POST[stok]'
        WHERE id_buku=$id
    ");

    echo "<script>alert('Data buku berhasil diupdate'); location='index.php';</script>";
}

?>
