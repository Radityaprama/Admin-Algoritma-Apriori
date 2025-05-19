<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $stok = (int) $_POST['stok'];
    $harga = (int) $_POST['harga'];

    $query = "INSERT INTO barang (nama_barang, stok, harga) VALUES ('$nama', $stok, $harga)";
    mysqli_query($conn, $query);
}

header('Location: data_barang.php');
exit();
?>