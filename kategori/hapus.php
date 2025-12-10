<?php
include "../config/db.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$id = $_GET['id'];

mysqli_query($koneksi, "DELETE FROM kategori_buku WHERE id_kategori=$id");

header("Location: index.php");
exit;
?>
