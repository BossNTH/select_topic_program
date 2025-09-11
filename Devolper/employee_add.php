<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include("../connect.php");

/* ดึงรายการแผนกมาใส่ select */
$departments = [];
$dept_sql = "SELECT department_id, department_name FROM departments ORDER BY department_name ASC";
if ($res = $conn->query($dept_sql)) {
  while ($row = $res->fetch_assoc()) $departments[] = $row;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เพิ่มพนักงาน</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{ background:#f6f7fb; }
    .page-head{
      background: linear-gradient(135deg,#0d6efd,#5b9dff);
      color:#fff; border-radius:18px; padding:20px 22px;
      box-shadow:0 10px 25px rgba(13,110,253,.25);
    }
    .card{ border:0; border-radius:18px; box-shadow:0 10px 25px rgba(0,0,0,.06); }
    .input-group-text{ background:#f1f4f9; border:none; }
    .form-control, .form-select{ border:none; background:#fff; }
    .form-control:focus, .form-select:focus{
      box-shadow:0 0 0 .25rem rgba(13,110,253,.15);
    }
    .required:after{ content:" *"; color:#dc3545; }
  </style>
</head>
<body class="container py-4">

  <!-- Header -->
  <div class="page-head d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1"><i class="bi bi-person-plus me-2"></i>เพิ่มพนักงาน</h2>
      <div class="small opacity-75">กรอกข้อมูลพื้นฐานของพนักงานให้ครบถ้วน</div>
    </div>
    <div class="d-flex gap-2">
      <a href="employeeManagement.php" class="btn btn-light btn-sm">
        <i class="bi bi-arrow-left me-1"></i> กลับหน้าจัดการพนักงาน
      </a>
      <a href="dashboard.php" class="btn btn-outline-light btn-sm">
        <i class="bi bi-speedometer2 me-1"></i> แดชบอร์ด
      </a>
    </div>
  </div>

  <!-- Form Card -->
  <div class="card">
    <div class="card-body">
      <form action="employee_save.php" method="POST" novalidate>
        <div class="row g-3">

          <!-- ชื่อพนักงาน -->
          <div class="col-md-6">
            <label class="form-label required">ชื่อพนักงาน</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="full_name" class="form-control" placeholder="กรอกชื่อ-นามสกุล" required>
            </div>
          </div>

          <!-- อีเมล -->
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" class="form-control" placeholder="name@example.com">
            </div>
          </div>

          <!-- เบอร์โทร -->
          <div class="col-md-6">
            <label class="form-label">เบอร์โทร</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-telephone"></i></span>
              <input type="tel" name="phone" class="form-control"
                     placeholder="เช่น 08x-xxx-xxxx"
                     pattern="^[0-9+\-\s()]{8,20}$">
            </div>
            <div class="form-text">อนุญาตตัวเลข วงเล็บ ช่องว่าง และเครื่องหมาย + -</div>
          </div>

          <!-- แผนก -->
          <div class="col-md-6">
            <label class="form-label required">แผนก (Department)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-building"></i></span>
              <select name="department_id" class="form-select" required>
                <option value="" selected disabled>-- เลือกแผนก --</option>
                <?php foreach ($departments as $d): ?>
                  <option value="<?= htmlspecialchars($d['department_id']) ?>">
                    <?= htmlspecialchars($d['department_id'].' - '.$d['department_name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- สถานะ -->
          <div class="col-md-6">
            <label class="form-label required">สถานะพนักงาน</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-toggle2-on"></i></span>
              <select name="status" class="form-select" required>
                <option value="active" selected>ใช้งาน (Active)</option>
                <option value="inactive">ปิดใช้งาน (Inactive)</option>
              </select>
            </div>
          </div>

        </div>

        <hr class="my-4">
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> บันทึก
          </button>
          <button type="reset" class="btn btn-outline-secondary">
            ล้างฟอร์ม
          </button>
          <a href="employeeManagement.php" class="btn btn-light">ยกเลิก</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap client-side validation -->
  <script>
    (() => {
      const forms = document.querySelectorAll('form[novalidate]');
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', e => {
          if (!form.checkValidity()) {
            e.preventDefault(); e.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>
</body>
</html>
