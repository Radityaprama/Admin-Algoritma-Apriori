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
        <h2>Data Apriori</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Itemset</th>
                    <th>Support</th>
                    <th>Confidence</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $query = mysqli_query($conn, "SELECT * FROM apriori");
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>{$row['itemset']}</td>
                            <td>{$row['support']}</td>
                            <td>{$row['confidence']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm editBtn' data-id='{$row['id']}' data-itemset='{$row['itemset']}' data-support='{$row['support']}' data-confidence='{$row['confidence']}'>Edit</button>
                                <button class='btn btn-danger btn-sm deleteBtn' data-id='{$row['id']}'>Hapus</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Edit Apriori -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="apriori_edit.php" method="post" class="modal-content">
                <input type="hidden" name="id" id="editId">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Apriori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="itemset" id="editItemset" class="form-control mb-2" required>
                    <input type="number" name="support" id="editSupport" class="form-control mb-2" required>
                    <input type="number" name="confidence" id="editConfidence" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Apriori -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="apriori_delete.php" method="post" class="modal-content">
                <input type="hidden" name="id" id="deleteId">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menghapus itemset ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('editId').value = btn.dataset.id;
                document.getElementById('editItemset').value = btn.dataset.itemset;
                document.getElementById('editSupport').value = btn.dataset.support;
                document.getElementById('editConfidence').value = btn.dataset.confidence;
                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
        });
        document.querySelectorAll('.deleteBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('deleteId').value = btn.dataset.id;
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            });
        });
    </script>
</body>
</html>
