<?php
// D:\xampp\htdocs\website_penyewaan\admin\proses_apriori.php

// Pastikan koneksi database TIDAK diperlukan di sini jika tujuannya hanya memanggil API.
// Jika ada keperluan lain untuk koneksi DB di script ini, biarkan saja include '../includes/db.php';

// URL API Python Anda
$api_url = 'http://127.0.0.1:5000/api/apriori';

// Opsional: Anda bisa menambahkan parameter min_support dan min_confidence
// Jika Anda ingin memastikan Apriori di-refresh dengan kriteria tertentu setiap kali
// transaksi baru ditambahkan. Ini penting jika nilai default di API terlalu tinggi
// sehingga tidak menghasilkan aturan jika data masih sedikit.
// Contoh:
// $api_url .= '?min_support=0.01&min_confidence=0.1'; 

// Lakukan request GET ke API Python
// Gunakan @ untuk menekan peringatan PHP jika API tidak dapat dijangkau
$response_json = @file_get_contents($api_url);

// --- DEBUGGING: Tulis respons API ke log error PHP ---
error_log("Proses Apriori: Memanggil API Python. Respons: " . ($response_json !== FALSE ? $response_json : "Failed to get response"));

// Tidak perlu memproses hasilnya di sini, karena tujuan utama adalah memicu API
// untuk memproses ulang data. Halaman data_apriori.php akan memuat data terbaru
// saat diakses.

echo "Proses Apriori berhasil dipicu (API Python telah dipanggil).";

?>