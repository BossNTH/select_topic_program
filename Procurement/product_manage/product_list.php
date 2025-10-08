<?php
// /ptj/ptj/EmpBuy/product_manage/product_list.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
    header("Location: /ptj/ptj/EmpBuy/login.php");
    exit();
}
require_once dirname(__DIR__, 2) . '/connect.php';

$res = $conn->query("SELECT p.product_id, p.product_name, p.unit, p.qty_on_hand, p.min_stock, p.unit_price,
                            c.category_name
                     FROM products p
                     LEFT JOIN product_categories c ON c.category_id = p.category_id
                     ORDER BY p.product_id DESC");
if (!$res) {
    die("Query failed: (" . $conn->errno . ") " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายการสินค้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>รายการสินค้า</h2>
    <div class="d-flex gap-2">
      <a href="/ptj1/ptj/EmpBuy/product_manage/product_add.php" class="btn btn-primary">+ เพิ่มสินค้า</a>
      <a href="/ptj1/ptj/EmpBuy/product_manage/product_category.php" class="btn btn-outline-secondary">จัดการประเภท</a>
    </div>
  </div>

  <?php if (isset($_GET['msg']) && $_GET['msg']==='created'): ?>
    <div class="alert alert-success">บันทึกสินค้าเรียบร้อย</div>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>ชื่อสินค้า</th>
          <th>ประเภท</th>
          <th>คงเหลือ</th>
          <th>ขั้นต่ำ</th>
          <th>หน่วย</th>
          <th>ราคา/หน่วย</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $res->fetch_assoc()): ?>
          <tr>
            <td><?= (int)$row['product_id'] ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['category_name'] ?? '-') ?></td>
            <td><?= (int)$row['qty_on_hand'] ?></td>
            <td><?= (int)$row['min_stock'] ?></td>
            <td><?= htmlspecialchars($row['unit']) ?></td>
            <td><?= number_format((float)$row['unit_price'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <a href="../empBuy.php" class="btn btn-secondary">← กลับหน้าหลัก</a>
</body>
</html>
