<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
} else {
    // User is logged in, proceed with the rest of the page
    include '../includes/db.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Penyewaan Alat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background-color: #ff4757;
            color: white;
            padding: 20px;
        }
        .product-card {
            border: 1px solid #dee2e6;
            margin: 10px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .product-img {
            width: 100%;
            height: auto;
        }
        .total-summary {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <h1>Aplikasi Penyewaan Alat</h1>
        <p>Selamat Datang, <?php echo $_SESSION['user']['username']; ?></p>
    </div>
    
    <div class="container mt-4">
        <h2>SILAHKAN PILIH MENU BARANG</h2>
        <div class="row">
            <!-- Contoh Produk -->
            <div class="col-md-3">
                <div class="product-card">
                    <img src="path_to_image.jpg" class="product-img" alt="Nama Produk">
                    <div class="p-2">
                        <h5>Nama Barang</h5>
                        <p>Rp. 21.000</p>
                        <button class="btn btn-primary">Pilih</button>
                    </div>
                </div>
            </div>
            <!-- Tambahkan lebih banyak produk sesuai kebutuhan -->
        </div>

        <div class="mt-5 total-summary">
            <h4>Pesanan Anda</h4>
            <p>Kode Pemesanan: PSN1711200001</p>
            <p>Jumlah Barang: 9</p>
            <p>Total: Rp. 36.000</p>
            <button class="btn btn-success">Simpan Pesanan</button>
            <button class="btn btn-warning">Update Pesanan</button>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
