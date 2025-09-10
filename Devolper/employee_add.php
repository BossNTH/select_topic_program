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
  while ($row = $res->fetch_assoc()) {
    $departments[] = $row;
  }
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
</head>
<body class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">
      <i class="bi bi-person-plus me-2"></i>เพิ่มพนักงาน
    </h2>
    <a href="employeeManagement.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> กลับหน้าจัดการพนักงาน
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="employee_save.php" method="POST" novalidate>
        <!-- รหัสพนักงาน -->
        <!-- <div class="mb-3">
          <label class="form-label">รหัสพนักงาน <span class="text-muted">(Employee Code)</span></label>
          <input type="text" name="employee_code" class="form-control" placeholder="เช่น EMP-001" required>
          <div class="form-text">กำหนดรูปแบบตามมาตรฐานที่องค์กรใช้ (สามารถซ้ำกับ Auto ID ภายในตาราง)</div>
        </div> -->

        <!-- ชื่อพนักงาน -->
        <div class="mb-3">
          <label class="form-label">ชื่อพนักงาน</label>
          <input type="text" name="full_name" class="form-control" placeholder="กรอกชื่อ-นามสกุล" required>
        </div>

        <!-- เบอร์โทร -->
        <div class="mb-3">
          <label class="form-label">เบอร์โทร</label>
          <input
            type="tel"
            name="phone"
            class="form-control"
            placeholder="เช่น 08x-xxx-xxxx"
            pattern="^[0-9+\-\s()]{8,20}$">
          <div class="form-text">อนุญาตตัวเลข วงเล็บ ช่องว่าง และเครื่องหมาย + -</div>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="name@example.com">
        </div>

        <!-- รหัสแผนก* -->
        <div class="mb-3">
          <label class="form-label">รหัสแผนก* (Department)</label>
          <select name="department_id" class="form-select" required>
            <option value="" selected disabled>-- เลือกแผนก --</option>
            <?php foreach ($departments as $d): ?>
              <option value="<?= htmlspecialchars($d['department_id']); ?>">
                <?= htmlspecialchars($d['department_id'] . " - " . $d['department_name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="form-text">จำเป็นต้องระบุแผนกของพนักงาน</div>
        </div>

        <!-- สถานะพนักงาน -->
        <div class="mb-3">
          <label class="form-label">สถานะพนักงาน</label>
          <select name="status" class="form-select" required>
            <option value="active" selected>ใช้งาน (Active)</option>
            <option value="inactive">ปิดใช้งาน (Inactive)</option>
          </select>
        </div>

        <!-- ปุ่มบันทึก/ยกเลิก -->
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> บันทึก
          </button>
          <a href="employeeManagement.php" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Helper: เปิดใช้ tooltip/validation ของ Bootstrap (ถ้าต้องการ) -->
  <script>
    (() => {
      const forms = document.querySelectorAll('form[novalidate]');
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', e => {
          if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>
</body>
</html>
