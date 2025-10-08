<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
  header("Location: ../login.php"); exit();
}
require_once dirname(__DIR__, 2) . '/connect.php';

$sql = "SELECT pr_id, pr_no, needed_date, status, created_at 
        FROM purchase_requisitions 
        ORDER BY pr_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ดูใบขอซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2><i class="bi bi-inbox"></i> รายการใบขอซื้อ</h2>
  <a href="pr_create.php" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> สร้างใบขอซื้อ</a>
  <table class="table table-bordered table-hover align-middle">
    <thead class="table-dark">
      <tr>
        <th>เลขที่ PR</th>
        <th>วันที่ต้องการ</th>
        <th>สถานะ</th>
        <th>สร้างเมื่อ</th>
        <th class="text-center">การดำเนินการ</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['pr_no']) ?></td>
          <td><?= $row['needed_date'] ?></td>
          <td><span class="badge bg-info"><?= $row['status'] ?></span></td>
          <td><?= $row['created_at'] ?></td>
          <td class="text-center">
            <a href="pr_view.php?id=<?= $row['pr_id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> ดู</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="staff_purchase.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> กลับหน้าหลัก</a>
</body>
</html>
