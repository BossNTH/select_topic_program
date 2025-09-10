<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include("../connect.php");
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เพิ่มแผนก</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>เพิ่มแผนก</h2>
  <form action="department_save.php" method="POST">
    <div class="mb-3">
      <label class="form-label">ชื่อแผนก</label>
      <input type="text" name="dept_name" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">บันทึก</button>
    <a href="dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
  </form>
</body>
</html>
