<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
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
    <title>Halaman User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h4 class="text-center">User  Panel</h4>
        <a href="user.php" class="active">Daftar Barang</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="main-content">
        <h2>Daftar Barang</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Harga Sewa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $query = mysqli_query($conn, "SELECT * FROM barang");
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>{$row['nama_barang']}</td>
                            <td>{$row['stok']}</td>
                            <td>Rp " . number_format($row['harga']) . "</td>
                            <td>
                                <button class='btn btn-primary btn-sm sewaBtn' data-id='{$row['id']}' data-nama='{$row['nama_barang']}' data-harga='{$row['harga']}'>Sewa</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Sewa Barang -->
    <div class="modal fade" id="sewaModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="sewa_barang.php" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sewa Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="barang_id" id="barang-id">
                    <p>Anda ingin menyewa <span id="barang-nama"></span> dengan harga <span id="barang-harga"></span>?</p>
                    <input type="date" name="tanggal_sewa" class="form-control mb-2" required>
                    <input type="date" name="tanggal_kembali" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Sewa</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sewaBtns = document.querySelectorAll('.sewaBtn');
        sewaBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('barang-id').value = btn.dataset.id;
                document.getElementById('barang-nama').innerText = btn.dataset.nama;
                document.getElementById('barang-harga').innerText = 'Rp ' + btn.dataset.harga;
                new bootstrap.Modal(document.getElementById('sewaModal')).show();
            });
        });
    </script>
</body>
</html>
