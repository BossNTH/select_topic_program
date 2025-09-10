<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
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
  $employee_id  = intval($_POST['employee_id'] ?? 0);
  $full_name    = trim($_POST['full_name'] ?? '');
  $phone        = trim($_POST['phone'] ?? '');
  $email        = trim($_POST['email'] ?? '');
  $department_id= intval($_POST['department_id'] ?? 0);
  $status       = $_POST['status'] ?? 'active';

  if ($employee_id <= 0 || $full_name === '' || $department_id <= 0) {
    echo "<script>alert('ข้อมูลไม่ครบถ้วน'); history.back();</script>";
    exit();
  }

  // (ถ้าต้องการ validate email)
  if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('รูปแบบอีเมลไม่ถูกต้อง'); history.back();</script>";
    exit();
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
  $stmt->close();
  $conn->close();
  exit();
}

/* โหมดแสดงฟอร์ม: โหลดข้อมูลพนักงาน */
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
  echo "<script>alert('ไม่พบพนักงานที่ระบุ'); window.location='employeeManagement.php';</script>";
  exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แก้ไขพนักงาน</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0"><i class="bi bi-pencil-square me-2"></i>แก้ไขพนักงาน</h2>
    <a href="employeeManagement.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> กลับ</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post" novalidate>
        <input type="hidden" name="employee_id" value="<?= htmlspecialchars($emp['employee_id']) ?>">

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">รหัสพนักงาน</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($emp['employee_code']) ?>" disabled>
            <div class="form-text">รหัสสร้างอัตโนมัติ (แก้ไขไม่ได้)</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">สถานะพนักงาน</label>
            <select name="status" class="form-select" required>
              <option value="active"   <?= $emp['status']==='active'?'selected':''; ?>>ใช้งาน (Active)</option>
              <option value="inactive" <?= $emp['status']==='inactive'?'selected':''; ?>>ปิดใช้งาน (Inactive)</option>
            </select>
          </div>

          <div class="col-12">
            <label class="form-label">ชื่อพนักงาน</label>
            <input type="text" name="full_name" class="form-control" required
                   value="<?= htmlspecialchars($emp['full_name']) ?>">
          </div>

          <div class="col-md-6">
            <label class="form-label">เบอร์โทร</label>
            <input type="tel" name="phone" class="form-control"
                   value="<?= htmlspecialchars($emp['phone']) ?>"
                   pattern="^[0-9+\-\s()]{8,20}$" placeholder="เช่น 08x-xxx-xxxx">
          </div>

          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($emp['email']) ?>" placeholder="name@example.com">
          </div>

          <div class="col-12">
            <label class="form-label">รหัสแผนก*</label>
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

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> บันทึกการแก้ไข
          </button>
          <a href="employeeManagement.php" class="btn btn-outline-secondary">ยกเลิก</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
