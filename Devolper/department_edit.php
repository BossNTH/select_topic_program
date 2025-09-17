<?php
// Devolper/department_edit.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit();
}
require_once("../connect.php");

// รับ id แผนก
$deptId = (int)($_GET['id'] ?? 0);
if ($deptId <= 0) {
    echo "<script>alert('ไม่พบรหัสแผนก'); window.location='departmentManagement.php';</script>";
    exit();
}

// ตรวจว่ามีคอลัมน์ head_employee_id ไหม
$hasHeadCol = false;
if ($col = $conn->query("SHOW COLUMNS FROM departments LIKE 'head_employee_id'")) {
  $hasHeadCol = $col->num_rows > 0;
}

// ดึงข้อมูลแผนก
$sqlDept = "
  SELECT department_id, department_name
  ".($hasHeadCol ? ", head_employee_id" : ", NULL AS head_employee_id")."
  FROM departments
  WHERE department_id = ?
  LIMIT 1";
$stmt = $conn->prepare($sqlDept);
$stmt->bind_param("i", $deptId);
$stmt->execute();
$deptRes = $stmt->get_result();
if (!$deptRes || $deptRes->num_rows === 0) {
    echo "<script>alert('ไม่พบข้อมูลแผนกนี้'); window.location='departmentManagement.php';</script>";
    exit();
}
$dept = $deptRes->fetch_assoc();
$headId = (int)($dept['head_employee_id'] ?? 0);

// ดึงพนักงานในแผนกนี้
$sqlEmp = "SELECT employee_id, employee_code, full_name, email, phone
           FROM employees
           WHERE department_id = ?
           ORDER BY full_name ASC";
$emps = [];
if ($st = $conn->prepare($sqlEmp)) {
  $st->bind_param("i", $deptId);
  $st->execute();
  $rs = $st->get_result();
  while ($row = $rs->fetch_assoc()) $emps[] = $row;
  $st->close();
}
$stmt->close();

require __DIR__ . '/partials/admin_header.php';

?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แก้ไขแผนก</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap 5 + Icons -->
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
    .form-control:focus, .form-select:focus{ box-shadow:0 0 0 .25rem rgba(13,110,253,.15); }
    .table thead th { background:#0d6efd; color:#fff; vertical-align:middle; }
    .table thead th.sticky { position:sticky; top:0; z-index:2; }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="page-head d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1"><i class="bi bi-building-gear me-2"></i>แก้ไขแผนก</h2>
      <div class="small opacity-75">ปรับชื่อแผนก และตั้งหัวหน้าแผนกจากรายชื่อพนักงาน</div>
    </div>
  </div>

  <!-- Form -->
  <div class="card mb-3">
    <div class="card-body">
      <form action="department_update.php" method="POST" id="deptForm" novalidate>
        <input type="hidden" name="department_id" value="<?= (int)$dept['department_id'] ?>">

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">ชื่อแผนก <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-building"></i></span>
              <input type="text"
                     name="dept_name"
                     class="form-control"
                     value="<?= htmlspecialchars($dept['department_name']) ?>"
                     maxlength="100"
                     required>
            </div>
            <div class="form-text">ไม่เกิน 100 ตัวอักษร</div>
          </div>

          <?php if ($hasHeadCol): ?>
          <div class="col-md-6">
            <label class="form-label">หัวหน้าแผนก</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
              <select name="head_employee_id" id="headSelect" class="form-select">
                <option value="">— ยังไม่กำหนด —</option>
                <?php foreach ($emps as $e): ?>
                  <option value="<?= (int)$e['employee_id'] ?>" <?= ($headId === (int)$e['employee_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['full_name']) ?> (<?= htmlspecialchars($e['employee_code']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-text">เลือกจากพนักงานในแผนกนี้</div>
          </div>
          <?php else: ?>
          <div class="col-12">
            <div class="alert alert-warning mb-0">
              <i class="bi bi-exclamation-triangle me-1"></i>
              ตาราง <code>departments</code> ยังไม่รองรับหัวหน้าแผนก (ไม่มีคอลัมน์ <code>head_employee_id</code>) —
              หากต้องการใช้งาน ให้เพิ่มคอลัมน์ตาม SQL ด้านล่างไฟล์ <code>department_update.php</code>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <hr class="my-4">
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> บันทึกการเปลี่ยนแปลง
          </button>
          <a href="departmentManagement.php" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Employee list in this department -->
  <div class="card">
    <div class="card-body">
      <h5 class="mb-3"><i class="bi bi-people me-2"></i>พนักงานในแผนกนี้ (<?= count($emps) ?> คน)</h5>

      <?php if (count($emps)): ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th class="sticky">รหัส</th>
              <th class="sticky">ชื่อ-สกุล</th>
              <th class="sticky">อีเมล</th>
              <th class="sticky">เบอร์โทร</th>
              <?php if ($hasHeadCol): ?><th class="sticky text-center">ตั้งเป็นหัวหน้า</th><?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($emps as $e): ?>
              <tr>
                <td class="text-primary fw-semibold"><?= htmlspecialchars($e['employee_code']) ?></td>
                <td><?= htmlspecialchars($e['full_name']) ?></td>
                <td><?= htmlspecialchars($e['email']) ?></td>
                <td><?= htmlspecialchars($e['phone']) ?></td>
                <?php if ($hasHeadCol): ?>
                <td class="text-center">
                  <button type="button"
                          class="btn btn-sm btn-outline-primary"
                          onclick="document.getElementById('headSelect').value='<?= (int)$e['employee_id'] ?>';">
                    <i class="bi bi-check2-circle me-1"></i> เลือก
                  </button>
                </td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <div class="text-muted">ยังไม่มีพนักงานในแผนกนี้</div>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Bootstrap client-side validation
    (function(){
      const form = document.getElementById('deptForm');
      form.addEventListener('submit', function(e){
        const input = form.querySelector('input[name="dept_name"]');
        input.value = (input.value || '').trim();
        if (!form.checkValidity() || input.value === '') { e.preventDefault(); e.stopPropagation(); }
        form.classList.add('was-validated');
      });
    })();
  </script>
</body>
</html>
