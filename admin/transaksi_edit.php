<?php
// transaksi_edit.php
include '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $user_id = $_POST['user_id'];
  $barang_id = $_POST['barang_id'];
  $tanggal_sewa = $_POST['tanggal_sewa'];
  $tanggal_kembali = $_POST['tanggal_kembali'];

  $query = "UPDATE transaksi SET user_id = ?, barang_id = ?, tanggal_sewa = ?, tanggal_kembali = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("iissi", $user_id, $barang_id, $tanggal_sewa, $tanggal_kembali, $id);
  $stmt->execute();

  header("Location: data_transaksi.php");
  exit();
}
?>
