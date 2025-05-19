<?php
session_start();
include '../includes/db.php';

$type = $_POST['type'];

if ($type == 'login') {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = $user;
        if ($user['role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/index.php");
        }
    } else {
        echo "<script>alert('Login gagal!'); window.location='login.php';</script>";
    }
} else if ($type == 'register') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
    mysqli_query($conn, "INSERT INTO users(username, email, password) VALUES('$username', '$email', '$pass')");
    echo "<script>alert('Register berhasil! Silakan login.'); window.location='login.php';</script>";
}
?>
