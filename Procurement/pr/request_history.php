<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
  header("Location: ../login.php");
  exit();
}
require_once dirname(__DIR__, 2) . '/connect.php';

$user = $_SESSION['username'];

// ดึง employee_id จาก employee_code (ตรงกับ username)
$sqlEmp = "SELECT employee_id FROM employees WHERE employee_code = ?";
$stmtEmp = $conn->prepare($sqlEmp);
$stmtEmp->bind_param("s", $user);
$stmtEmp->execute();
$resEmp = $stmtEmp->get_result();
$emp = $resEmp->fetch_assoc();
$stmtEmp->close();

$empId = $emp ? $emp['employee_id'] : 0;

// ดึงประวัติ PR
$sql = "SELECT pr_no, request_date, need_by_date, status
        FROM purchase_requisitions
        WHERE requester_id = ?
        ORDER BY request_date DESC";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $empId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ประวัติใบขอซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2 class="mb-3"><i class="bi bi-clock-history"></i> ประวัติใบขอซื้อของฉัน</h2>

  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>เลขที่ PR</th>
        <th>วันที่ขอซื้อ</th>
        <th>วันที่ต้องการ</th>
        <th>สถานะ</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while($r = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($r['pr_no']) ?></td>
            <td><?= htmlspecialchars($r['request_date']) ?></td>
            <td><?= htmlspecialchars($r['need_by_date']) ?></td>
            <td><span class="badge bg-info"><?= htmlspecialchars($r['status']) ?></span></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" class="text-center text-muted">ยังไม่มีประวัติใบขอซื้อ</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="../empBuy.php" class="btn btn-secondary mt-3">
    <i class="bi bi-arrow-left"></i> กลับ
  </a>
</body>
</html>
<?php
$stmt->close();
$conn->close();
