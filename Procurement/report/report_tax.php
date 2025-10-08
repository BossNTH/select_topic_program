<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
    header("Location: " . dirname(__DIR__,2) . "/login.php");
    exit();
}
require_once dirname(__DIR__,2) . "/connect.php";

$vat_rate = 0.07;
$start = $_GET['start'] ?? date('Y-m-01');
$end   = $_GET['end']   ?? date('Y-m-t');

$sql = "
  SELECT o.po_no, o.order_date, s.supplier_name, o.total_amount
  FROM purchase_orders o
  JOIN suppliers s ON s.supplier_id = o.supplier_id
  WHERE o.order_date BETWEEN ? AND ?
  ORDER BY o.order_date DESC
";
$stmt = $conn->prepare($sql);
if (!$stmt) die('SQL Error: '.$conn->error);
$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$rows = $stmt->get_result();

$total = 0; $vat = 0; $grand = 0;
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายงานภาษี</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>รายงานภาษี (VAT <?= $vat_rate*100 ?>%)</h2>

  <form method="get" class="row g-2 mb-3">
    <div class="col-auto"><label class="form-label">จาก</label><input type="date" name="start" value="<?= $start ?>" class="form-control"></div>
    <div class="col-auto"><label class="form-label">ถึง</label><input type="date" name="end" value="<?= $end ?>" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-primary">แสดง</button></div>
  </form>

  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>เลขที่ PO</th><th>วันที่</th><th>ผู้ขาย</th>
        <th class="text-end">มูลค่าสินค้า</th>
        <th class="text-end">VAT</th>
        <th class="text-end">รวม</th>
      </tr>
    </thead>
    <tbody>
    <?php while($r = $rows->fetch_assoc()):
      $sub = $r['total_amount'];
      $vat_amt = $sub * $vat_rate;
      $sum = $sub + $vat_amt;
      $total += $sub; $vat += $vat_amt; $grand += $sum;
    ?>
      <tr>
        <td><?= htmlspecialchars($r['po_no']) ?></td>
        <td><?= htmlspecialchars($r['order_date']) ?></td>
        <td><?= htmlspecialchars($r['supplier_name']) ?></td>
        <td class="text-end"><?= number_format($sub,2) ?></td>
        <td class="text-end"><?= number_format($vat_amt,2) ?></td>
        <td class="text-end"><?= number_format($sum,2) ?></td>
      </tr>
    <?php endwhile; ?>
      <tr class="fw-bold table-secondary">
        <td colspan="3" class="text-end">รวม</td>
        <td class="text-end"><?= number_format($total,2) ?></td>
        <td class="text-end"><?= number_format($vat,2) ?></td>
        <td class="text-end"><?= number_format($grand,2) ?></td>
      </tr>
    </tbody>
  </table>
  <a href="../Empbuy.php" class="btn btn-secondary">กลับ</a>
</body>
</html>
