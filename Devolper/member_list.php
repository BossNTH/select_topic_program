<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include("../connect.php");
$result = $conn->query("SELECT id, username, role FROM users");
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>สมาชิกทั้งหมด</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>สมาชิกทั้งหมด</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>รหัส</th>
        <th>ชื่อผู้ใช้</th>
        <th>สิทธิ์</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['username'] ?></td>
          <td><?= $row['role'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
</body>
</html>
