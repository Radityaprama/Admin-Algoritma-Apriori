<?php
// transaksi_delete.php
include '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];

  $query = "DELETE FROM transaksi WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $id);
  $stmt->execute();

  header("Location: data_transaksi.php");
  exit();
}
?>
