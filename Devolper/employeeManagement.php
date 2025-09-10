<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include("../connect.php");

// ดึงข้อมูลพนักงานจากฐานข้อมูล
$sql = "SELECT 	employee_id,employee_code, full_name,phone,email,status,department_id FROM employees ORDER BY employee_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการพนักงาน</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>จัดการพนักงาน</h2>
    <a href="employee_add.php" class="btn btn-success">
      <i class="bi bi-person-plus"></i> เพิ่มพนักงาน
    </a>
  </div>

  <table class="table table-striped table-bordered align-middle">
    <thead class="table-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">ชื่อผู้ใช้</th>
        <th scope="col">เบอร์โทร</th>
        <th scope="col">email</th>
        <th scope="col">status</th>
        <th scope="col">department_id</th>
        <th scope="col" class="text-center">การจัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['employee_code']; ?></td>
            <td><?= htmlspecialchars($row['full_name']); ?></td>
            <td><?= $row['phone']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['status']; ?></td>
            <td><?= $row['department_id']; ?></td>
            <td class="text-center">
              <a href="employee_edit.php?id=<?= $row['employee_id']; ?>" class="btn btn-sm btn-warning">แก้ไข</a>
              <a href="employee_delete.php?id=<?= $row['employee_id']; ?>" 
                 onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบพนักงานคนนี้?');"
                 class="btn btn-sm btn-danger">ลบ</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center text-muted">ไม่พบข้อมูลพนักงาน</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="dashboard.php" class="btn btn-secondary mt-3">ย้อนกลับ</a>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</body>
</html>
