<?php
include "../config/db.php";

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}


$id = $_GET['id'];

mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku = $id");

echo "<script>alert('Buku berhasil dihapus');location='index.php';</script>";
?>
