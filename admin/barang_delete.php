<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $query = "DELETE FROM barang WHERE id=$id";
    mysqli_query($conn, $query);
}

header('Location: data_barang.php');
exit();
?>