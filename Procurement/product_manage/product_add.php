<?php
// /ptj/ptj/EmpBuy/product_manage/product_add.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
    header("Location: /ptj/ptj/EmpBuy/login.php");
    exit();
}
require_once dirname(__DIR__, 2) . '/connect.php';

// ดึงประเภทสินค้า
$categories = $conn->query("SELECT category_id, category_name FROM product_categories ORDER BY category_name ASC");
if (!$categories) {
    die("Query categories failed: (" . $conn->errno . ") " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เพิ่มสินค้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2 class="mb-3">เพิ่มสินค้าใหม่</h2>

  <?php if (isset($_GET['err'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['err']); ?></div>
  <?php endif; ?>

  <form action="../product_manage/product_save.php" method="post" novalidate>
    <div class="mb-3">
      <label class="form-label">ชื่อสินค้า</label>
      <input type="text" name="product_name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">รายละเอียด</label>
      <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">ประเภทสินค้า</label>
      <div class="d-flex gap-2">
        <select name="category_id" class="form-select" required>
          <option value="">-- เลือกประเภท --</option>
          <?php while($c = $categories->fetch_assoc()): ?>
            <option value="<?= (int)$c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
          <?php endwhile; ?>
        </select>
        <a href="../product_manage/product_category.php" class="btn btn-outline-secondary">จัดการประเภท</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">คงเหลือ</label>
        <input type="number" name="qty_on_hand" class="form-control" value="0" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">ขั้นต่ำ</label>
        <input type="number" name="min_stock" class="form-control" value="0" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">หน่วย</label>
        <input type="text" name="unit" class="form-control" placeholder="เช่น ชิ้น, กล่อง" required>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">ราคา/หน่วย</label>
      <input type="number" step="0.01" name="unit_price" class="form-control" value="0.00" required>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-success">บันทึก</button>
      <a href="../product_manage/product_list.php" class="btn btn-secondary">ยกเลิก</a>
    </div>
  </form>
</body>
</html>
