<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
include '../includes/db.php'; 

$api_url = 'http://127.0.0.1:5000/api/apriori';

$response_json = @file_get_contents($api_url);

$frequent_itemsets = [];
$association_rules = [];
$min_support = 0.1;
$min_confidence = 0.5;
$total_transactions = 0;

if ($response_json === FALSE) {
    echo '<div class="alert alert-danger" role="alert">Error: Gagal terhubung ke layanan Apriori (pastikan server Python berjalan di ' . $api_url . ').</div>';
} else {
    $api_results = json_decode($response_json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo '<div class="alert alert-danger" role="alert">Error: Gagal mengurai respons JSON dari layanan Apriori.</div>';
        error_log("JSON Decode Error: " . json_last_error_msg() . " - Response: " . $response_json);
    } elseif (isset($api_results['error'])) {
        echo '<div class="alert alert-danger" role="alert">Error dari layanan Apriori: ' . htmlspecialchars($api_results['error']) . '</div>';
    } else {
        $frequent_itemsets = $api_results['frequent_itemsets'] ?? [];
        $association_rules = $api_results['association_rules'] ?? [];
        $min_support = $api_results['min_support'] ?? 0.1;
        $min_confidence = $api_results['min_confidence'] ?? 0.5;
        $total_transactions = $api_results['total_transactions'] ?? 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Apriori</title>
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
        .result-section {
            margin-bottom: 3rem;
            border: 1px solid #ddd;
            padding: 1.5rem;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">Admin Panel</h4>
        <a href="dashboard.php"><i class="bi bi-speedometer"></i> Dashboard</a>
        <a href="data_barang.php"><i class="bi bi-box"></i> Data Barang</a>
        <a href="data_transaksi.php"><i class="bi bi-journal"></i> Data Transaksi</a>
        <a href="data_apriori.php" class="active"><i class="bi bi-graph-up"></i> Data Apriori</a>
        <a href="user.php"><i class="bi bi-people"></i> Halaman User</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
    <div class="main-content">
        <h2>Hasil Analisis Algoritma Apriori</h2>
        <hr>

        <div class="result-section mb-4">
            <h4>Parameter Algoritma:</h4>
            <p>Minimum Support: <strong><?php echo isset($min_support) ? number_format($min_support * 100, 2) . '%' : 'N/A'; ?></strong></p>
            <p>Minimum Confidence: <strong><?php echo isset($min_confidence) ? number_format($min_confidence * 100, 2) . '%' : 'N/A'; ?></strong></p>
        </div>

        <h3>Frequent Itemsets</h3>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Itemset</th>
                    <th>Support (Count)</th>
                    <th>Support (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($frequent_itemsets) && is_array($frequent_itemsets)) {
                    $no = 1;
                    foreach ($frequent_itemsets as $itemset_key => $support_ratio) {
                        $itemset_display = explode(',', $itemset_key);
                        sort($itemset_display);
                        $itemset_display_str = implode(', ', $itemset_display);

                        $support_count = ($total_transactions > 0) ? round($support_ratio * $total_transactions) : 'N/A';
                        $support_percentage = number_format($support_ratio * 100, 2) . '%';
                        
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>{" . $itemset_display_str . "}</td>";
                        echo "<td>" . $support_count . "</td>";
                        echo "<td>" . $support_percentage . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Tidak ada Frequent Itemsets ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3 class="mt-5">Association Rules</h3>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Rule (If... Then...)</th>
                    <th>Support (%)</th>
                    <th>Confidence (%)</th>
                    <th>Lift</th>
                    <th>Rekomendasi Display</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($association_rules) && is_array($association_rules)) {
                    $no = 1;
                    foreach ($association_rules as $rule) {
                        $antecedent_str = implode(', ', $rule['antecedent']);
                        $consequent_str = implode(', ', $rule['consequent']);
                        $support_perc = number_format($rule['support'] * 100, 2) . '%';
                        $confidence_perc = number_format($rule['confidence'] * 100, 2) . '%';
                        $lift_val = number_format($rule['lift'], 2);
                        $rekomendasi_display_str = htmlspecialchars($rule['rekomendasi_display'] ?? 'N/A');

                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>{" . $antecedent_str . "} &rArr; {" . $consequent_str . "}</td>";
                        echo "<td>" . $support_perc . "</td>";
                        echo "<td>" . $confidence_perc . "</td>";
                        echo "<td>" . $lift_val . "</td>";
                        echo "<td>" . $rekomendasi_display_str . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Tidak ada Association Rules ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>