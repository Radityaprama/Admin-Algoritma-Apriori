<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Penyewaan Alat</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../assets/images/bg-mount.jpg') no-repeat center center fixed;
      background-size: cover;
    }
    .card {
      backdrop-filter: blur(10px);
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 text-white bg-dark shadow-lg" style="width: 25rem;">
      <h3 class="text-center mb-3">Login Akun</h3>
      <form action="auth_process.php" method="POST">
        <input type="hidden" name="type" value="login">
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required />
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-success w-100">Login</button>
        <div class="text-center mt-2">
          <a href="register.php" class="text-light">Belum punya akun? Register</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
