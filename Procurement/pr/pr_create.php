<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
  header("Location: ../login.php"); exit();
}
require_once dirname(__DIR__, 2) . '/connect.php';

// ดึงรายการสินค้าไว้ให้เลือก
$products = $conn->query("SELECT product_id, product_name, unit, unit_price FROM products ORDER BY product_name ASC");

// สร้างเลขที่ PR (ฝั่งฟอร์มโชว์ไว้ เผื่ออยากแก้เป็น Auto ใน save ก็ได้)
function suggest_pr_no(): string {
  return 'PR-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(2)));
}
$pr_no = suggest_pr_no();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>สร้างใบขอซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2><i class="bi bi-journal-plus"></i> สร้างใบขอซื้อ</h2>

  <form action="pr_save.php" method="post" id="prForm">
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">เลขที่ PR</label>
        <input type="text" name="pr_no" class="form-control" value="<?= htmlspecialchars($pr_no) ?>" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">ต้องการใช้วันที่</label>
        <input type="date" name="needed_date" class="form-control" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">สถานะเริ่มต้น</label>
        <select name="status" class="form-select" required>
          <option value="draft">draft</option>
          <option value="pending">pending</option>
        </select>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>รายการสินค้า</strong>
        <button type="button" class="btn btn-sm btn-primary" id="btnAddRow">
          <i class="bi bi-plus-lg"></i> เพิ่มแถว
        </button>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0 align-middle" id="itemsTable">
            <thead class="table-light">
              <tr>
                <th style="min-width: 260px;">สินค้า</th>
                <th style="width: 120px;">จำนวน</th>
                <th style="width: 140px;">ราคา/หน่วย (อ้างอิง)</th>
                <th style="width: 100px;">หน่วย</th>
                <th class="text-end" style="width: 60px;">ลบ</th>
              </tr>
            </thead>
            <tbody>
              <!-- แถวแรก -->
              <tr>
                <td>
                  <select name="items[0][product_id]" class="form-select product-select" required>
                    <option value="">-- เลือกสินค้า --</option>
                    <?php
                      mysqli_data_seek($products, 0);
                      while($p = $products->fetch_assoc()):
                    ?>
                      <option value="<?= $p['product_id'] ?>"
                              data-unit="<?= htmlspecialchars($p['unit']) ?>"
                              data-price="<?= $p['unit_price'] ?>">
                        <?= htmlspecialchars($p['product_name']) ?>
                      </option>
                    <?php endwhile; ?>
                  </select>
                </td>
                <td><input type="number" name="items[0][qty]" class="form-control" value="1" min="1" required></td>
                <td><input type="number" step="0.01" name="items[0][unit_price]" class="form-control unit-price" placeholder="อ้างอิง" ></td>
                <td><input type="text" name="items[0][unit]" class="form-control unit-field" placeholder="หน่วย"></td>
                <td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm btnRemoveRow"><i class="bi bi-x-lg"></i></button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="mt-3 d-flex gap-2">
      <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle"></i> บันทึก PR</button>
      <a href="pr_list1.php" class="btn btn-secondary">ยกเลิก</a>
    </div>
  </form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let rowIdx = 1;
const productsCache = (function() {
  // เก็บ options เป็น HTML string เพื่อ clone ง่าย ๆ
  const select = document.querySelector('.product-select');
  return select ? select.innerHTML : '';
})();

document.getElementById('btnAddRow').addEventListener('click', () => {
  const tbody = document.querySelector('#itemsTable tbody');
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>
      <select name="items[${rowIdx}][product_id]" class="form-select product-select" required>
        ${productsCache}
      </select>
    </td>
    <td><input type="number" name="items[${rowIdx}][qty]" class="form-control" value="1" min="1" required></td>
    <td><input type="number" step="0.01" name="items[${rowIdx}][unit_price]" class="form-control unit-price" placeholder="อ้างอิง"></td>
    <td><input type="text" name="items[${rowIdx}][unit]" class="form-control unit-field" placeholder="หน่วย"></td>
    <td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm btnRemoveRow"><i class="bi bi-x-lg"></i></button></td>
  `;
  tbody.appendChild(tr);
  rowIdx++;
});

// auto-fill หน่วย/ราคา จาก option data-*
document.addEventListener('change', (e) => {
  if (!e.target.classList.contains('product-select')) return;
  const opt = e.target.selectedOptions[0];
  const tr  = e.target.closest('tr');
  if (!opt || !tr) return;
  const unit = opt.getAttribute('data-unit') || '';
  const price= opt.getAttribute('data-price') || '';
  tr.querySelector('.unit-field').value = unit;
  tr.querySelector('.unit-price').value = price;
});

// remove row
document.addEventListener('click', (e) => {
  if (!e.target.closest('.btnRemoveRow')) return;
  const tr = e.target.closest('tr');
  const tbody = tr.parentElement;
  if (tbody.children.length > 1) tr.remove();
});
</script>
</body>
</html>
