<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php"); exit();
}
require_once "../connect.php";

$employee_id = intval($_GET['id'] ?? 0);

/* โหลดข้อมูลแผนกทั้งหมด */
$departments = [];
$deptSql = "SELECT department_id, department_name FROM departments ORDER BY department_name";
if ($res = $conn->query($deptSql)) {
  while ($r = $res->fetch_assoc()) $departments[] = $r;
}

/* โหมดบันทึก */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $employee_id   = intval($_POST['employee_id'] ?? 0);
  $full_name     = trim($_POST['full_name'] ?? '');
  $phone         = trim($_POST['phone'] ?? '');
  $email         = trim($_POST['email'] ?? '');
  $department_id = intval($_POST['department_id'] ?? 0);
  $status        = $_POST['status'] ?? 'active';

  if ($employee_id <= 0 || $full_name === '' || $department_id <= 0) {
    echo "<script>alert('ข้อมูลไม่ครบถ้วน'); history.back();</script>"; exit();
  }
  if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('รูปแบบอีเมลไม่ถูกต้อง'); history.back();</script>"; exit();
  }

  $sql = "UPDATE employees
          SET full_name=?, phone=?, email=?, department_id=?, status=?
          WHERE employee_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssisi", $full_name, $phone, $email, $department_id, $status, $employee_id);

  if ($stmt->execute()) {
    echo "<script>alert('✅ อัปเดตข้อมูลเรียบร้อย'); window.location='employeeManagement.php';</script>";
  } else {
    $msg = addslashes($stmt->error);
    echo "<script>alert('❌ ไม่สามารถอัปเดตข้อมูลได้: {$msg}'); history.back();</script>";
  }
  $stmt->close(); $conn->close(); exit();
}

/* โหมดแสดงฟอร์ม */
$empSql = "SELECT e.employee_id, e.employee_code, e.full_name, e.phone, e.email,
                  e.department_id, e.status
           FROM employees e
           WHERE e.employee_id = ?";
$stmt = $conn->prepare($empSql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$emp = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$emp) {
  echo "<script>alert('ไม่พบพนักงานที่ระบุ'); window.location='employeeManagement.php';</script>"; exit();
}

require __DIR__ . '/partials/admin_header.php';

?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แก้ไขพนักงาน</title>
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
    .required:after{ content:" *"; color:#dc3545; }
    .badge-status{ font-size:.85rem; }
    .copy-btn{ white-space:nowrap; }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="page-head d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1"><i class="bi bi-pencil-square me-2"></i>แก้ไขพนักงาน</h2>
      <div class="small opacity-75">ปรับข้อมูลพนักงานและแผนกที่สังกัด</div>
    </div>
    
  </div>

  <!-- Main form -->
  <div class="card">
    <div class="card-body">
      <form method="post" id="empEditForm" novalidate>
        <input type="hidden" name="employee_id" value="<?= htmlspecialchars($emp['employee_id']) ?>">

        <div class="row g-3">
          <!-- Employee code (readonly + copy) -->
          <div class="col-md-6">
            <label class="form-label">รหัสพนักงาน</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
              <input type="text" class="form-control" id="empCode" value="<?= htmlspecialchars($emp['employee_code']) ?>" disabled>
              <button class="btn btn-outline-secondary copy-btn" type="button" id="btnCopyCode" title="คัดลอกรหัส">
                <i class="bi bi-clipboard"></i>
              </button>
            </div>
            <div class="form-text">รหัสสร้างอัตโนมัติ (แก้ไขไม่ได้)</div>
          </div>

          <!-- Status -->
          <div class="col-md-6">
            <label class="form-label required">สถานะพนักงาน</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-toggle2-on"></i></span>
              <select name="status" class="form-select" required>
                <option value="active"   <?= $emp['status']==='active'?'selected':''; ?>>ใช้งาน (Active)</option>
                <option value="inactive" <?= $emp['status']==='inactive'?'selected':''; ?>>ปิดใช้งาน (Inactive)</option>
              </select>
            </div>
          </div>

          <!-- Full name -->
          <div class="col-12">
            <label class="form-label required">ชื่อพนักงาน</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="full_name" class="form-control" required
                     placeholder="กรอกชื่อ-นามสกุล"
                     value="<?= htmlspecialchars($emp['full_name']) ?>">
            </div>
          </div>

          <!-- Phone -->
          <div class="col-md-6">
            <label class="form-label">เบอร์โทร</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-telephone"></i></span>
              <input type="tel" name="phone" class="form-control"
                     value="<?= htmlspecialchars($emp['phone']) ?>"
                     pattern="^[0-9+\-\s()]{8,20}$"
                     placeholder="เช่น 08x-xxx-xxxx">
            </div>
            <div class="form-text">อนุญาตตัวเลข วงเล็บ ช่องว่าง และเครื่องหมาย + -</div>
          </div>

          <!-- Email -->
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" class="form-control"
                     value="<?= htmlspecialchars($emp['email']) ?>"
                     placeholder="name@example.com">
            </div>
          </div>

          <!-- Department -->
          <div class="col-12">
            <label class="form-label required">แผนก</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-building"></i></span>
              <select name="department_id" class="form-select" required>
                <option value="" disabled>-- เลือกแผนก --</option>
                <?php foreach ($departments as $d): ?>
                  <option value="<?= $d['department_id']; ?>"
                    <?= ($emp['department_id']==$d['department_id'])?'selected':''; ?>>
                    <?= htmlspecialchars($d['department_id'].' - '.$d['department_name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <hr class="my-4">
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> บันทึกการแก้ไข
          </button>
          <a href="employeeManagement.php" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  (function(){
    const form      = document.getElementById('empEditForm');
    const btnCopy   = document.getElementById('btnCopyCode');
    const empCodeEl = document.getElementById('empCode');

    // คัดลอกรหัสพนักงาน
    btnCopy?.addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText(empCodeEl.value || '');
        btnCopy.innerHTML = '<i class="bi bi-clipboard-check"></i>';
        setTimeout(() => btnCopy.innerHTML = '<i class="bi bi-clipboard"></i>', 1200);
      } catch {
        alert('คัดลอกไม่สำเร็จ');
      }
    });

    // Client-side validation + trim
    form?.addEventListener('submit', (e) => {
      const nameInput = form.querySelector('input[name="full_name"]');
      const emailInput = form.querySelector('input[name="email"]');
      if (nameInput) nameInput.value = (nameInput.value || '').trim();
      if (emailInput) emailInput.value = (emailInput.value || '').trim();

      if (!form.checkValidity() || (nameInput && nameInput.value === '')) {
        e.preventDefault(); e.stopPropagation();
      }
      form.classList.add('was-validated');
    });

    // เตือนถ้าแก้ไขแล้วออกหน้า (unsaved changes)
    let dirty = false;
    form?.addEventListener('input', ()=> dirty = true);
    window.addEventListener('beforeunload', (e) => {
      if (dirty) { e.preventDefault(); e.returnValue=''; }
    });
    form?.addEventListener('submit', ()=> { dirty = false; });
  })();
  </script>
</body>
</html>
<?php require __DIR__ . '/partials/admin_footer.php'; ?>