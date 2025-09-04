<?php
include("../connect.php") ;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>สมัครสมาชิก</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="register.css">
</head>
<body>
  <div class="register-container">
    <h2>สมัครสมาชิก</h2>
    <form action="register_process.php" method="post">
      <div class="mb-3 text-start">
        <label for="username" class="form-label">ชื่อผู้ใช้ (Username)</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" class="form-control" id="username" name="username" placeholder="กรอกชื่อผู้ใช้" required />
        </div>
      </div>

      <div class="mb-3 text-start">
        <label for="email" class="form-label">อีเมล</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" class="form-control" id="email" name="email" placeholder="กรอกอีเมล" required />
        </div>
      </div>

      <div class="mb-3 text-start">
        <label for="password" class="form-label">รหัสผ่าน</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control" id="password" name="password" placeholder="กรอกรหัสผ่าน" required />
        </div>
      </div>

      <div class="mb-3 text-start">
        <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="ยืนยันรหัสผ่าน" required />
        </div>
      </div>

      <button type="submit" class="btn-register">สมัครสมาชิก</button>
    </form>

    <div class="text-link">
      <span>มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></span>
    </div>
  </div>

</body>
</html>
