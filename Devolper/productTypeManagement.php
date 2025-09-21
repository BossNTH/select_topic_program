<?php
// Developer/product_categories.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require_once("../connect.php");

// ดึงข้อมูลประเภทสินค้า
$sql = "SELECT category_id, category_name FROM product_categories ORDER BY category_id ASC";
$res = $conn->query($sql);
$categories = [];
while ($row = $res->fetch_assoc()) $categories[] = $row;

require __DIR__ . '/partials/admin_header.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการประเภทสินค้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>

  <h2 class="mb-3">จัดการประเภทสินค้า</h2>

  <!-- ปุ่มเพิ่ม -->
  <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-circle"></i> เพิ่มประเภทสินค้า
  </button>

  <!-- ตาราง -->
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered table-hover">
        <thead class="table-primary">
          <tr>
            <th>รหัส</th>
            <th>ชื่อประเภทสินค้า</th>
            <th class="text-center">การจัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($categories): ?>
            <?php foreach ($categories as $cat): ?>
              <tr>
                <td><?= htmlspecialchars($cat['category_id']) ?></td>
                <td><?= htmlspecialchars($cat['category_name']) ?></td>
                <td class="text-center">
                  <!-- ปุ่มแก้ไข -->
                  <button class="btn btn-sm btn-warning" 
                          data-bs-toggle="modal" 
                          data-bs-target="#editModal<?= $cat['category_id'] ?>">
                    <i class="bi bi-pencil-square"></i> แก้ไข
                  </button>

                  <!-- ปุ่มลบ -->
                  <a href="product_category_delete.php?id=<?= (int)$cat['category_id'] ?>"
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('ต้องการลบประเภทสินค้านี้หรือไม่?');">
                    <i class="bi bi-trash"></i> ลบ
                  </a>
                </td>
              </tr>

              <!-- Modal แก้ไข -->
              <div class="modal fade" id="editModal<?= $cat['category_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="product_category_edit.php" method="POST">
                      <div class="modal-header bg-warning">
                        <h5 class="modal-title">แก้ไขประเภทสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $cat['category_id'] ?>">
                        <div class="mb-3">
                          <label class="form-label">ชื่อประเภทสินค้า</label>
                          <input type="text" name="category_name" class="form-control"
                                 value="<?= htmlspecialchars($cat['category_name']) ?>" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="3" class="text-center text-muted">ไม่มีข้อมูล</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal เพิ่ม -->
  <div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="product_category_save.php" method="POST">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">เพิ่มประเภทสินค้า</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">ชื่อประเภทสินค้า</label>
              <input type="text" name="category_name" class="form-control" required maxlength="100">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
