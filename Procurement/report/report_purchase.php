<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
    header("Location: " . dirname(__DIR__,2) . "/login.php");
    exit();
}
require_once dirname(__DIR__,2) . "/connect.php";

$start = $_GET['start'] ?? date('Y-01-01');
$end   = $_GET['end']   ?? date('Y-m-t');

$sql = "
  SELECT DATE_FORMAT(order_date, '%Y-%m') AS month,
         COUNT(po_no) AS po_count,
         SUM(total_amount) AS total
  FROM purchase_orders
  WHERE order_date BETWEEN ? AND ?
  GROUP BY month
  ORDER BY month
";
$stmt = $conn->prepare($sql);
if (!$stmt) die('SQL Error: '.$conn->error);
$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายงานการจัดซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>รายงานการจัดซื้อ</h2>
  <form method="get" class="row g-2 mb-3">
    <div class="col-auto"><label class="form-label">จาก</label><input type="date" name="start" value="<?= $start ?>" class="form-control"></div>
    <div class="col-auto"><label class="form-label">ถึง</label><input type="date" name="end" value="<?= $end ?>" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-primary">แสดง</button></div>
  </form>

  <table class="table table-bordered">
    <thead class="table-dark">
      <tr><th>เดือน</th><th class="text-end">จำนวนใบสั่งซื้อ</th><th class="text-end">มูลค่ารวม</th></tr>
    </thead>
    <tbody>
      <?php while($r = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $r['month'] ?></td>
          <td class="text-end"><?= $r['po_count'] ?></td>
          <td class="text-end"><?= number_format($r['total'],2) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="../Empbuy.php" class="btn btn-secondary">กลับ</a>
</body>
</html>
