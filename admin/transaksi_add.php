<?php
// transaksi_add.php
include '../includes/db.php'; 
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST Data Received in transaksi_add.php: " . print_r($_POST, true)); 

    // Untuk tanggal_transaksi (DATE):
    // Jika kolom ini NOT NULL di DB dan form WAJIB, kita harus memastikan nilainya ada.
    // Jika $_POST['tanggal_sewa'] kosong, ini akan tetap menjadi string kosong,
    // yang akan diubah oleh MySQL menjadi '0000-00-00' atau error jika mode SQL ketat.
    // Cara yang lebih aman adalah dengan menggunakannya langsung jika tidak kosong,
    // atau memberikan tanggal default jika benar-benar kosong dari form (misal tanggal hari ini).
    $tanggal_transaksi = isset($_POST['tanggal_sewa']) && !empty($_POST['tanggal_sewa']) ? $_POST['tanggal_sewa'] : date('Y-m-d'); // <-- PERUBAHAN UTAMA DI SINI

    // Untuk hari (VARCHAR): ambil sebagai string
    $hari = isset($_POST['hari']) && !empty($_POST['hari']) ? $_POST['hari'] : '0'; 

    $jenis_transaksi = isset($_POST['jenis_transaksi']) ? $_POST['jenis_transaksi'] : '';
    $nama_barang = isset($_POST['nama_barang']) ? $_POST['nama_barang'] : '';
    $jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 0; 
    $harga_per_hari = isset($_POST['harga_per_hari']) ? (float)$_POST['harga_per_hari'] : 0.0; 
    $durasi = isset($_POST['durasi']) ? (int)$_POST['durasi'] : 0; 
    $tanggal_kembali = isset($_POST['tanggal_kembali']) && !empty($_POST['tanggal_kembali']) ? $_POST['tanggal_kembali'] : NULL; 
    $total_harga = isset($_POST['total_harga']) ? (float)$_POST['total_harga'] : 0.0; 

    $query = "INSERT INTO transaksi (tanggal_transaksi, hari, jenis_transaksi, nama_barang, jumlah, harga_per_hari, durasi, tanggal_kembali, total_harga) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        $_SESSION['error_message'] = "Prepare statement gagal: " . $conn->error;
        header('Location: data_transaksi.php'); 
        exit(); 
    }

    $stmt->bind_param("sssssidsd", 
        $tanggal_transaksi, 
        $hari, 
        $jenis_transaksi, 
        $nama_barang, 
        $jumlah, 
        $harga_per_hari, 
        $durasi, 
        $tanggal_kembali, 
        $total_harga
    );

    if ($stmt->execute()) { // Baris 60 jika Anda tidak menambahkan komentar/baris kosong terlalu banyak
        $_SESSION['success_message'] = "Transaksi berhasil ditambahkan!";
        @include 'proses_apriori.php'; 
        header('Location: data_transaksi.php'); 
        exit(); 
    } else {
        $_SESSION['error_message'] = "Error saat menambahkan transaksi: " . $stmt->error;
        header('Location: data_transaksi.php'); 
        exit();
    }

    $stmt->close(); 
}

$conn->close(); 
?>