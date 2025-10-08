<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
  header("Location: ../login.php"); exit();
}
require '../connect.php';

$po_id = (int)($_GET['id'] ?? 0);

// header
$h = $conn->prepare("SELECT o.po_no, o.order_date, o.due_date, o.status, s.supplier_name
                     FROM purchase_orders o
                     JOIN suppliers s ON s.supplier_id=o.supplier_id
                     WHERE o.po_id=?");
$h->bind_param("i", $po_id); $h->execute();
$header = $h->get_result()->fetch_assoc();
if (!$header) { echo "ไม่พบ PO นี้"; exit; }

// items
$i = $conn->prepare("SELECT p.product_name, d.qty, d.unit, d.unit_price
                     FROM po_items d
                     JOIN products p ON p.product_id = d.product_id
                     WHERE d.po_id=?
                     ORDER BY d.id ASC");
$i->bind_param("i", $po_id); $i->execute();
$items = $i->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>PO #<?= htmlspecialchars($header['po_no']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>ใบสั่งซื้อ: <?= htmlspecialchars($header['po_no']) ?></h2>
  <div class="row g-3 mb-3">
    <div class="col-md-4"><strong>ผู้ขาย:</strong> <?= htmlspecialchars($header['supplier_name']) ?></div>
    <div class="col-md-4"><strong>วันที่สั่งซื้อ:</strong> <?= $header['order_date'] ?></div>
    <div class="col-md-4"><strong>กำหนดส่ง:</strong> <?= $header['due_date'] ?></div>
    <div class="col-md-4"><strong>สถานะ:</strong> <span class="badge bg-secondary"><?= $header['status'] ?></span></div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>สินค้า</th>
          <th class="text-end">จำนวน</th>
          <th>หน่วย</th>
          <th class="text-end">ราคา/หน่วย</th>
          <th class="text-end">รวม</th>
        </tr>
      </thead>
      <tbody>
        <?php $n=1; $sum=0; while($row=$items->fetch_assoc()): 
              $line = (float)$row['unit_price'] * (int)$row['qty']; $sum += $line; ?>
        <tr>
          <td><?= $n++ ?></td>
          <td><?= htmlspecialchars($row['product_name']) ?></td>
          <td class="text-end"><?= (int)$row['qty'] ?></td>
          <td><?= htmlspecialchars($row['unit']) ?></td>
          <td class="text-end"><?= number_format((float)$row['unit_price'],2) ?></td>
          <td class="text-end"><?= number_format($line,2) ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
          <th colspan="5" class="text-end">ยอดรวม</th>
          <th class="text-end"><?= number_format($sum,2) ?></th>
        </tr>
      </tbody>
    </table>
  </div>

  <a href="order_history.php" class="btn btn-secondary">กลับ</a>
</body>
</html>
