<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../includes/db.php';

// --- Ambil data dari API Apriori untuk Dashboard ---
$api_url = 'http://127.0.0.1:5000/api/apriori'; // Pastikan ini sesuai port API Python Anda

// Opsional: Anda bisa menambahkan parameter min_support dan min_confidence
// untuk memastikan API selalu mengembalikan hasil yang mungkin dihitung
// meskipun data transaksi masih sedikit.
// $api_url .= '?min_support=0.01&min_confidence=0.1';

$response_json_apriori = @file_get_contents($api_url); 
$apriori_count = 0; // Default value

if ($response_json_apriori !== FALSE) {
    $api_results_apriori = json_decode($response_json_apriori, true);
    if (json_last_error() === JSON_ERROR_NONE && !isset($api_results_apriori['error'])) {
        // Kita bisa hitung jumlah Frequent Itemsets atau Association Rules
        // Pilih salah satu yang ingin Anda tampilkan di dashboard
        $apriori_count = count($api_results_apriori['frequent_itemsets'] ?? []); // Menghitung Frequent Itemsets
        // Atau: $apriori_count = count($api_results_apriori['association_rules'] ?? []); // Menghitung Association Rules
    }
}
// --- Akhir Ambil data dari API Apriori ---

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            padding-top: 2rem;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 15px;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">Dashboard Toko Outdoor</h4>
        <a href="dashboard.php" class="active"><i class="bi bi-speedometer"></i> Dashboard</a>
        <a href="data_barang.php"><i class="bi bi-box"></i> Data Barang</a>
        <a href="data_transaksi.php"><i class="bi bi-journal"></i> Data Transaksi</a>
        <a href="data_apriori.php"><i class="bi bi-graph-up"></i> Data Apriori</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
    <div class="main-content">
        <h1>Hi, Welcome Sir</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Barang</h5>
                        <?php 
                            $barang = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM barang"));
                            echo "<p class='card-text fs-4'>" . $barang . "</p>";
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Transaksi</h5>
                        <?php 
                            $transaksi = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM transaksi"));
                            echo "<p class='card-text fs-4'>" . $transaksi . "</p>";
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Data Apriori (Itemsets)</h5>
                        <?php 
                            echo "<p class='card-text fs-4'>" . $apriori_count . "</p>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>