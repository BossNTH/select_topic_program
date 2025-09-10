<?php require_once "connect.php"; ?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>สมัครสมาชิกผู้ขาย</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="regis.css">
</head>
<body>
  <div class="register-container">
    <h2>สมัครสมาชิกผู้ขาย (Seller)</h2>

    <form action="register_process.php" method="post" novalidate>
      <!-- ผู้ใช้ (สำหรับตาราง users) -->
      <div class="mb-3 text-start">
        <label for="username" class="form-label">ชื่อผู้ใช้ (Username)</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" class="form-control" id="username" name="username" placeholder="กรอกชื่อผู้ใช้" required>
        </div>
      </div>

      <div class="mb-3 text-start">
        <label for="password" class="form-label">รหัสผ่าน</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control" id="password" name="password" placeholder="กรอกรหัสผ่าน" minlength="6" required>
        </div>
        <div class="form-text">รหัสผ่านอย่างน้อย 6 ตัวอักษร</div>
      </div>

      <div class="mb-3 text-start">
        <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="ยืนยันรหัสผ่าน" required>
        </div>
      </div>

      <!-- ข้อมูลผู้ขาย (สำหรับตาราง suppliers) -->
      <hr class="my-4">
      <h5 class="text-start mb-3"><i class="fa fa-store me-2"></i>ข้อมูลผู้ขาย</h5>

      <div class="mb-3 text-start">
        <label for="supplier_name" class="form-label">ชื่อผู้ขาย/ชื่อร้าน</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-shop"></i></span>
          <input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="เช่น ร้านสมชายการค้า" required>
        </div>
      </div>

      <div class="mb-3 text-start">
        <label for="contact_info" class="form-label">ข้อมูลติดต่อเพิ่มเติม</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-id-card"></i></span>
          <input type="text" class="form-control" id="contact_info" name="contact_info" placeholder="เช่น ชื่อผู้ติดต่อ, Line, เลขผู้เสียภาษี ฯลฯ">
        </div>
      </div>

      <div class="mb-3 text-start">
        <label for="email" class="form-label">อีเมล</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" class="form-control" id="email" name="email" placeholder="กรอกอีเมล" required>
        </div>
      </div>

      <div class="mb-3 text-start">
        <label for="phone" class="form-label">เบอร์โทร</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-phone"></i></span>
          <input type="text" class="form-control" id="phone" name="phone" placeholder="เช่น 08x-xxx-xxxx">
        </div>
      </div>

      <div class="mb-3 text-start">
        <label for="address" class="form-label">ที่อยู่</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-location-dot"></i></span>
          <textarea class="form-control" id="address" name="address" rows="2" placeholder="ที่อยู่สำหรับติดต่อ/ออกเอกสาร"></textarea>
        </div>
      </div>

      <!-- บังคับเป็น Seller -->
      <input type="hidden" name="role" value="seller">

      <button type="submit" class="btn-register">สมัครสมาชิก</button>
    </form>

    <div class="text-link">
      <span>มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></span>
    </div>
  </div>
</body>
</html>
