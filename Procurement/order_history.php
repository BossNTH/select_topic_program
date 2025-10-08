<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
    header("Location: ../login.php");
    exit();
}

require_once '../connect.php';

/*$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
if (!$user_id) {
  // ถ้าไม่มี id ใน session ให้ redirect หรือแจ้งเตือน
  echo "<script>alert('Session หมดอายุหรือไม่มีข้อมูลผู้ใช้');window.location='../login.php';</script>";
  exit();
}*/

$sql = "
  SELECT o.po_no, o.order_date, s.supplier_name, o.total_amount, o.status
  FROM purchase_orders o
  JOIN suppliers s ON s.supplier_id = o.supplier_id
  WHERE o.created_by_id = ?
  ORDER BY o.order_date DESC
";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ประวัติการสั่งซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>ประวัติการสั่งซื้อ</h2>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>เลขที่ใบสั่งซื้อ</th>
        <th>วันที่</th>
        <th>ผู้ขาย</th>
        <th class="text-end">ยอดรวม (บาท)</th>
        <th>สถานะ</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['po_no']) ?></td>
        <td><?= htmlspecialchars($row['order_date']) ?></td>
        <td><?= htmlspecialchars($row['supplier_name']) ?></td>
        <td class="text-end"><?= number_format($row['total_amount'],2) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="empBuy.php" class="btn btn-secondary">กลับ</a>
</body>
</html>
