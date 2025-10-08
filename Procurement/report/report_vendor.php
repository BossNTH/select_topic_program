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
  SELECT s.supplier_name,
         COUNT(o.po_no) AS po_count,
         SUM(o.total_amount) AS total_amount,
         AVG(o.total_amount) AS avg_po
  FROM purchase_orders o
  JOIN suppliers s ON s.supplier_id = o.supplier_id
  WHERE o.order_date BETWEEN ? AND ?
  GROUP BY s.supplier_name
  ORDER BY total_amount DESC
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
  <title>รายงานผู้ขาย</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>รายงานผู้ขาย</h2>
  <form method="get" class="row g-2 mb-3">
    <div class="col-auto"><label class="form-label">จาก</label><input type="date" name="start" value="<?= $start ?>" class="form-control"></div>
    <div class="col-auto"><label class="form-label">ถึง</label><input type="date" name="end" value="<?= $end ?>" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-primary">แสดง</button></div>
  </form>

  <table class="table table-bordered">
    <thead class="table-dark">
      <tr><th>ผู้ขาย</th><th class="text-end">จำนวนใบสั่งซื้อ</th><th class="text-end">มูลค่ารวม</th><th class="text-end">เฉลี่ย/ใบ</th></tr>
    </thead>
    <tbody>
      <?php while($r = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($r['supplier_name']) ?></td>
          <td class="text-end"><?= $r['po_count'] ?></td>
          <td class="text-end"><?= number_format($r['total_amount'],2) ?></td>
          <td class="text-end"><?= number_format($r['avg_po'],2) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="../Empbuy.php" class="btn btn-secondary">กลับ</a>
</body>
</html>
