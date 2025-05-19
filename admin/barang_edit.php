<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $stok = (int) $_POST['stok'];
    $harga = (int) $_POST['harga'];

    $query = "UPDATE barang SET nama_barang='$nama', stok=$stok, harga=$harga WHERE id=$id";
    mysqli_query($conn, $query);
}

header('Location: data_barang.php');
exit();
?>