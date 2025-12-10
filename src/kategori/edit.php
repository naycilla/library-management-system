<?php
include "../config/db.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}


$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kategori_buku WHERE id_kategori=$id"));

if (isset($_POST['update'])) {
    $nama = $_POST['nama_kategori'];

    $query = "UPDATE kategori_buku SET nama_kategori='$nama' WHERE id_kategori=$id";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal mengupdate: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "../layout/header.php"; ?>
<?php include "../layout/sidebar.php"; ?>

<div class="p-4">
    <div class="container col-md-6">
        <h3 class="mb-3">Edit Kategori</h3>
    
        <form method="POST">
            <div class="mb-3">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control"
                       value="<?php echo $data['nama_kategori']; ?>" required>
            </div>
    
            <button type="submit" name="update" class="btn btn-warning">Update</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>    

</body>
</html>
