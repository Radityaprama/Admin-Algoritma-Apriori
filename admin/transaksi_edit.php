<?php
include '../includes/db.php';
session_start(); // Tetap butuh session untuk autentikasi

// Cek autentikasi admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil ID dari hidden input
    $id = (int) $_POST['id'];

    $tanggal_transaksi = mysqli_real_escape_string($conn, $_POST['tanggal_transaksi']);
    $hari              = mysqli_real_escape_string($conn, $_POST['hari']);
    $jenis_transaksi   = mysqli_real_escape_string($conn, $_POST['jenis_transaksi']);
    $nama_barang       = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jumlah            = (int) $_POST['jumlah'];
    $harga_per_hari    = (float) $_POST['harga_per_hari'];
    $durasi            = (int) $_POST['durasi'];
    
    // Tanggal kembali bisa NULL atau string kosong. Sesuaikan dengan kebutuhan DB Anda.
    // Jika kolom di DB memang nullable dan Anda ingin menyimpan NULL, 
    // pastikan string 'NULL' tanpa kutip dikirim ke query.
    $tanggal_kembali   = !empty($_POST['tanggal_kembali']) ? "'" . mysqli_real_escape_string($conn, $_POST['tanggal_kembali']) . "'" : 'NULL';
    
    $total_harga       = (float) $_POST['total_harga'];

    // Bangun query UPDATE langsung
    $query = "UPDATE transaksi SET 
                tanggal_transaksi = '$tanggal_transaksi', 
                hari = '$hari', 
                jenis_transaksi = '$jenis_transaksi', 
                nama_barang = '$nama_barang', 
                jumlah = $jumlah, 
                harga_per_hari = $harga_per_hari, 
                durasi = $durasi, 
                tanggal_kembali = $tanggal_kembali, 
                total_harga = $total_harga 
              WHERE id = $id";

    // Eksekusi query
    mysqli_query($conn, $query);
    // Tidak ada penanganan error eksplisit di sini, sama seperti barang_edit.php
}

// Redirect ke halaman data_transaksi.php setelah proses POST
header('Location: data_transaksi.php');
exit();

?>