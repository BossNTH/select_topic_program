<?php
  include("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');
  </style>
</head>
<body>

  <!-- ชื่อระบบ -->
  <div class="system-title">ระบบจัดซื้อสินค้าของบริษัทมหาชนจำกัด</div>

  <!-- แบบฟอร์ม Login -->
  <form action="check_login.php" method="post">
    <div class="login-container">
      <i class="fas fa-user-circle"></i>
      <h2>Sign In</h2>

      <div class="mb-3 text-start">
        <label for="username" class="form-label">Username</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
        </div>
      </div>

      <div class="mb-3 text-start">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>
      </div>

      <div class="mb-3 form-check text-start">
        <input type="checkbox" class="form-check-input" id="remember">
        <label class="form-check-label" for="remember">Remember me</label>
      </div>

      <button type="submit" class="btn btn-login">Login</button>
      
      <div class="mb-3 text-end">
      <a href="register.php" class="btn btn-link text-primary text-decoration-underline">register</a>
      </div>

    </div>
  </form>

</body>
</html>
