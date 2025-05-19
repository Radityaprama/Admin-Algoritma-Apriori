<!-- dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit();
}
include '../includes/db.php';
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
    <h4 class="text-center">Admin Panel</h4>
    <a href="dashboard.php" class="active"><i class="bi bi-speedometer"></i> Dashboard</a>
    <a href="data_barang.php"><i class="bi bi-box"></i> Data Barang</a>
    <a href="data_transaksi.php"><i class="bi bi-journal"></i> Data Transaksi</a>
    <a href="data_apriori.php"><i class="bi bi-graph-up"></i> Data Apriori</a>
    <a href="user.php"><i class="bi bi-people"></i> Halaman User</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>
  <div class="main-content">
    <h1>Selamat Datang, Admin</h1>
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
            <h5 class="card-title">Data Apriori</h5>
            <?php 
              $apriori = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM apriori"));
              echo "<p class='card-text fs-4'>" . $apriori . "</p>";
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
