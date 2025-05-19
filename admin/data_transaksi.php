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
  <title>Data Transaksi</title>
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
    <a href="data_transaksi.php" class="active"><i class="bi bi-journal"></i> Data Transaksi</a>
    <a href="data_apriori.php"><i class="bi bi-graph-up"></i> Data Apriori</a>
    <a href="user.php"><i class="bi bi-people"></i> Halaman User</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>
  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Data Transaksi</h2>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus"></i> Tambah Transaksi</button>
    </div>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama User</th>
          <th>Barang</th>
          <th>Tanggal Sewa</th>
          <th>Tanggal Kembali</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php 
       $query = mysqli_query($conn, "SELECT t.*, u.username AS nama_user, b.nama_barang FROM transaksi t JOIN users u ON t.user_id = u.id JOIN barang b ON t.barang_id = b.id");
        $no = 1;
        while ($row = mysqli_fetch_assoc($query)) {
          echo "<tr>
                  <td>" . $no++ . "</td>
                  <td>{$row['nama_user']}</td>
                  <td>{$row['nama_barang']}</td>
                  <td>{$row['tanggal_sewa']}</td>
                  <td>{$row['tanggal_kembali']}</td>
                  <td>
                    <button class='btn btn-warning btn-sm editBtn' 
                            data-id='{$row['id']}' 
                            data-user='{$row['user_id']}' 
                            data-barang='{$row['barang_id']}' 
                            data-sewa='{$row['tanggal_sewa']}' 
                            data-kembali='{$row['tanggal_kembali']}'
                            data-bs-toggle='modal' data-bs-target='#editModal'>Edit</button>
                    <button class='btn btn-danger btn-sm deleteBtn' 
                            data-id='{$row['id']}' 
                            data-bs-toggle='modal' data-bs-target='#deleteModal'>Hapus</button>
                  </td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Modal Tambah Transaksi -->
  <div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
      <form action="transaksi_add.php" method="post" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Transaksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <select name="user_id" class="form-control mb-2" required>
            <option value="">Pilih User</option>
            <?php 
            $users = mysqli_query($conn, "SELECT * FROM users WHERE role='user'");
            while ($u = mysqli_fetch_assoc($users)) {
              echo "<option value='{$u['id']}'>{$u['nama']}</option>";
            }
            ?>
          </select>
          <select name="barang_id" class="form-control mb-2" required>
            <option value="">Pilih Barang</option>
            <?php 
            $barang = mysqli_query($conn, "SELECT * FROM barang");
            while ($b = mysqli_fetch_assoc($barang)) {
              echo "<option value='{$b['id']}'>{$b['nama_barang']}</option>";
            }
            ?>
          </select>
          <input type="date" name="tanggal_sewa" class="form-control mb-2" required>
          <input type="date" name="tanggal_kembali" class="form-control mb-2" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Edit Transaksi -->
  <div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
      <form action="transaksi_edit.php" method="post" class="modal-content">
        <input type="hidden" name="id" id="edit-id">
        <div class="modal-header">
          <h5 class="modal-title">Edit Transaksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <select name="user_id" id="edit-user" class="form-control mb-2" required>
            <?php 
            $users = mysqli_query($conn, "SELECT * FROM users WHERE role='user'");
            while ($u = mysqli_fetch_assoc($users)) {
              echo "<option value='{$u['id']}'>{$u['nama']}</option>";
            }
            ?>
          </select>
          <select name="barang_id" id="edit-barang" class="form-control mb-2" required>
            <?php 
            $barang = mysqli_query($conn, "SELECT * FROM barang");
            while ($b = mysqli_fetch_assoc($barang)) {
              echo "<option value='{$b['id']}'>{$b['nama_barang']}</option>";
            }
            ?>
          </select>
          <input type="date" name="tanggal_sewa" id="edit-sewa" class="form-control mb-2" required>
          <input type="date" name="tanggal_kembali" id="edit-kembali" class="form-control mb-2" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Update</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Hapus Transaksi -->
  <div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
      <form action="transaksi_delete.php" method="post" class="modal-content">
        <input type="hidden" name="id" id="delete-id">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Transaksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Yakin ingin menghapus transaksi ini?</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const editBtns = document.querySelectorAll('.editBtn');
    const deleteBtns = document.querySelectorAll('.deleteBtn');

    editBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('edit-id').value = btn.dataset.id;
        document.getElementById('edit-user').value = btn.dataset.user;
        document.getElementById('edit-barang').value = btn.dataset.barang;
        document.getElementById('edit-sewa').value = btn.dataset.sewa;
        document.getElementById('edit-kembali').value = btn.dataset.kembali;
      });
    });

    deleteBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('delete-id').value = btn.dataset.id;
      });
    });
  </script>
</body>
</html>
