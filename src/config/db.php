<?php
    $host = "db";
    $user = "root";
    $pass = "root";
    $db   = "perpus";

    $koneksi = mysqli_connect($host, $user, $pass, $db);

    if (!$koneksi) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }
?>
