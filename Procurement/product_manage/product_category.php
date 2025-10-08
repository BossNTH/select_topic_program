<?php
// /ptj/ptj/EmpBuy/product_manage/product_category.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
    header("Location: /ptj/ptj/EmpBuy/login.php");
    exit();
}
require_once dirname(__DIR__, 2) . '/connect.php';

$result = $conn->query("SELECT * FROM product_categories ORDER BY category_id DESC");
if (!$result) {
    die("Query failed: (" . $conn->errno . ") " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการประเภทสินค้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container mt-4">

  <h2 class="mb-3"><i class="bi bi-tags"></i> จัดการประเภทสินค้า</h2>
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
    <i class="bi bi-plus-circle"></i> เพิ่มประเภทสินค้า
  </button>

  <?php if (isset($_GET['msg']) && $_GET['msg']==='saved'): ?>
    <div class="alert alert-success">บันทึกประเภทสินค้าเรียบร้อย</div>
  <?php elseif (isset($_GET['msg']) && $_GET['msg']==='deleted'): ?>
    <div class="alert alert-warning">ลบประเภทสินค้าเรียบร้อย</div>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>ชื่อประเภทสินค้า</th>
          <th>จัดการ</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= (int)$row['category_id'] ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td>
              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal"
                      data-id="<?= (int)$row['category_id'] ?>" data-name="<?= htmlspecialchars($row['category_name']) ?>">
                <i class="bi bi-pencil"></i>
              </button>
              <a href="/ptj/ptj/EmpBuy/product_manage/product_category_delete.php?id=<?= (int)$row['category_id'] ?>"
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('ยืนยันการลบประเภทสินค้า?')">
                 <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <a href="../product_manage/product_add.php" class="btn btn-secondary">← กลับเพิ่มสินค้า</a>
  <a href="../Empbuy.php" class="btn btn-secondary">กลับหน้าหลัก</a>

  <!-- Modal เพิ่ม -->
  <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="/ptj/ptj/EmpBuy/product_manage/product_category_save.php" method="post">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-plus-circle"></i> เพิ่มประเภทสินค้า</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">ชื่อประเภทสินค้า</label>
              <input type="text" name="category_name" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button type="submit" class="btn btn-success">บันทึก</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal แก้ไข -->
  <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="/ptj/ptj/EmpBuy/product_manage/product_category_save.php" method="post">
          <input type="hidden" name="category_id" id="edit_id">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-pencil"></i> แก้ไขประเภทสินค้า</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">ชื่อประเภทสินค้า</label>
              <input type="text" name="category_name" id="edit_name" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button type="submit" class="btn btn-success">อัปเดต</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const editModal = document.getElementById('editCategoryModal');
editModal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  editModal.querySelector('#edit_id').value = button.getAttribute('data-id');
  editModal.querySelector('#edit_name').value = button.getAttribute('data-name');
});
</script>
</body>
</html>
