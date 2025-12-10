<?php
include "../config/db.php";

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}


$id = $_GET['id'];

mysqli_query($koneksi, "DELETE FROM anggota WHERE id_anggota=$id");

header("Location: index.php");
exit;
?>
