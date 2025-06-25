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
                    <th>Tanggal Transaksi</th>
                    <th>Hari</th>
                    <th>Jenis Transaksi</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Per Hari</th>
                    <th>Durasi</th>
                    <th>Tanggal Kembali</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($conn, "SELECT
                                                id,
                                                tanggal_transaksi,
                                                hari,
                                                jenis_transaksi,
                                                nama_barang,
                                                jumlah,
                                                harga_per_hari,
                                                durasi,
                                                tanggal_kembali,
                                                total_harga
                                              FROM transaksi");

                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) {
                    // Format harga_per_hari dan total_harga ke Rupiah
                    $harga_per_hari_formatted = "Rp. " . number_format($row['harga_per_hari'], 0, ',', ',');
                    $total_harga_formatted = "Rp. " . number_format($row['total_harga'], 0, ',', ',');

                    echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>{$row['tanggal_transaksi']}</td>
                            <td>{$row['hari']}</td>
                            <td>{$row['jenis_transaksi']}</td>
                            <td>{$row['nama_barang']}</td>
                            <td>{$row['jumlah']}</td>
                            <td>{$harga_per_hari_formatted}</td> <td>{$row['durasi']}</td>
                            <td>{$row['tanggal_kembali']}</td>
                            <td>{$total_harga_formatted}</td> <td>
                                <button class='btn btn-warning btn-sm editBtn'
                                        data-id='{$row['id']}'
                                        data-tanggal_transaksi='{$row['tanggal_transaksi']}'
                                        data-hari='{$row['hari']}'
                                        data-jenis_transaksi='{$row['jenis_transaksi']}'
                                        data-nama_barang='{$row['nama_barang']}'
                                        data-jumlah='{$row['jumlah']}'
                                        data-harga_per_hari='{$row['harga_per_hari']}' data-durasi='{$row['durasi']}'
                                        data-tanggal_kembali='{$row['tanggal_kembali']}'
                                        data-total_harga='{$row['total_harga']}' data-bs-toggle='modal' data-bs-target='#editModal'>Edit</button>
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

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="transaksi_add.php" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="date" name="tanggal_transaksi" class="form-control mb-2" required>
                    <input type="text" name="hari" class="form-control mb-2" placeholder="Contoh: Senin" required>
                    <input type="text" name="jenis_transaksi" class="form-control mb-2" placeholder="Contoh: Sewa" required>
                    <input type="text" name="nama_barang" class="form-control mb-2" placeholder="Nama Barang" required>
                    <input type="number" name="jumlah" class="form-control mb-2" placeholder="Jumlah" required>
                    <input type="number" step="0.01" name="harga_per_hari" class="form-control mb-2" placeholder="Harga per Hari" required>
                    <input type="number" name="durasi" class="form-control mb-2" placeholder="Durasi (Hari)" required>
                    <input type="date" name="tanggal_kembali" class="form-control mb-2" required>
                    <input type="number" step="0.01" name="total_harga" class="form-control mb-2" placeholder="Total Harga" readonly>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="transaksi_edit.php" method="post" class="modal-content">
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="date" name="tanggal_transaksi" id="edit-tanggal_transaksi" class="form-control mb-2" required>
                    <input type="text" name="hari" id="edit-hari" class="form-control mb-2" required>
                    <input type="text" name="jenis_transaksi" id="edit-jenis_transaksi" class="form-control mb-2" required>
                    <input type="text" name="nama_barang" id="edit-nama_barang" class="form-control mb-2" required>
                    <input type="number" name="jumlah" id="edit-jumlah" class="form-control mb-2" required>
                    <input type="number" step="0.01" name="harga_per_hari" id="edit-harga_per_hari" class="form-control mb-2" required>
                    <input type="number" name="durasi" id="edit-durasi" class="form-control mb-2" required>
                    <input type="date" name="tanggal_kembali" id="edit-tanggal_kembali" class="form-control mb-2" required>
                    <input type="number" step="0.01" name="total_harga" id="edit-total_harga" class="form-control mb-2" readonly>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>

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
                document.getElementById('edit-tanggal_transaksi').value = btn.dataset.tanggal_transaksi;
                document.getElementById('edit-hari').value = btn.dataset.hari;
                document.getElementById('edit-jenis_transaksi').value = btn.dataset.jenis_transaksi;
                document.getElementById('edit-nama_barang').value = btn.dataset.nama_barang;
                document.getElementById('edit-jumlah').value = btn.dataset.jumlah;
                document.getElementById('edit-harga_per_hari').value = btn.dataset.harga_per_hari; // Nilai asli
                document.getElementById('edit-durasi').value = btn.dataset.durasi;
                document.getElementById('edit-tanggal_kembali').value = btn.dataset.tanggal_kembali;
                document.getElementById('edit-total_harga').value = btn.dataset.total_harga; // Nilai asli
            });
        });

        deleteBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('delete-id').value = btn.dataset.id;
            });
        });

        function calculateTotalPrice(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            const jumlahInput = modal.querySelector('input[name="jumlah"]');
            const hargaPerHariInput = modal.querySelector('input[name="harga_per_hari"]');
            const durasiInput = modal.querySelector('input[name="durasi"]');
            const totalHargaInput = modal.querySelector('input[name="total_harga"]');

            if (!jumlahInput || !hargaPerHariInput || !durasiInput || !totalHargaInput) {
                console.warn(`Missing input elements in modal ${modalId}. Total price calculation skipped.`);
                return;
            }

            const inputs = [jumlahInput, hargaPerHariInput, durasiInput];

            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    const jumlah = parseFloat(jumlahInput.value) || 0;
                    const hargaPerHari = parseFloat(hargaPerHariInput.value) || 0;
                    const durasi = parseFloat(durasiInput.value) || 0;
                    const total = jumlah * hargaPerHari * durasi;
                    totalHargaInput.value = total.toFixed(2); // Tetap dalam format angka untuk perhitungan
                });
            });
        }

        calculateTotalPrice('addModal');
        calculateTotalPrice('editModal');

        document.getElementById('editModal').addEventListener('shown.bs.modal', () => {
            const jumlahInput = document.getElementById('edit-jumlah');
            if (jumlahInput) {
                jumlahInput.dispatchEvent(new Event('input'));
            }
        });
    </script>
</body>
</html>