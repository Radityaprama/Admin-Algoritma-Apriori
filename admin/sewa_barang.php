<?php
include '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id'];
    $barang_id = $_POST['barang_id'];
    $tanggal_sewa = $_POST['tanggal_sewa'];
    $tanggal_kembali = $_POST['tanggal_kembali'];

    $query = "INSERT INTO transaksi (user_id, barang_id, tanggal_sewa, tanggal_kembali) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss", $user_id, $barang_id, $tanggal_sewa, $tanggal_kembali);
    $stmt->execute();

    header("Location: user.php");
    exit();
}
?>
