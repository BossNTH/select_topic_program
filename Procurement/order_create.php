<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
    header("Location: ../login.php"); exit();
}
require '../connect.php';

// CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// ดึง supplier / payment type / product
$suppliers = $conn->query("SELECT supplier_id, supplier_name FROM suppliers WHERE status='active' ORDER BY supplier_name");
$payments  = $conn->query("SELECT payment_type_id, payment_type_name FROM payment_types ORDER BY payment_type_name");
$products  = $conn->query("SELECT product_id, product_name, unit, unit_price FROM products ORDER BY product_name");

// helper สร้างเลขที่ PO
function suggest_po_no(): string {
    return 'PO-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(2)));
}
$po_no = suggest_po_no();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ออกใบสั่งซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2><i class="bi bi-journal-plus"></i> ออกใบสั่งซื้อ (PO)</h2>

  <form action="order_save.php" method="post" id="poForm" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">เลขที่ PO</label>
  <input type="text" name="po_no" class="form-control" value="<?= htmlspecialchars($po_no) ?>" required maxlength="30" pattern="PO-[0-9]{4}-[A-Z0-9]{4}">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">วันที่สั่งซื้อ</label>
  <input type="date" name="order_date" class="form-control" value="<?= date('Y-m-d') ?>" required max="<?= date('Y-m-d') ?>">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">กำหนดส่งของ</label>
  <input type="date" name="due_date" class="form-control" required min="<?= date('Y-m-d') ?>">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">ผู้ขาย (Supplier)</label>
        <select name="supplier_id" class="form-select" required>
          <option value="">-- เลือกผู้ขาย --</option>
          <?php while($s = $suppliers->fetch_assoc()): ?>
            <option value="<?= $s['supplier_id'] ?>"><?= htmlspecialchars($s['supplier_name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">วิธีชำระเงิน</label>
        <select name="payment_type_id" class="form-select" required>
          <option value="">-- เลือกวิธีชำระ --</option>
          <?php while($p = $payments->fetch_assoc()): ?>
            <option value="<?= $p['payment_type_id'] ?>"><?= htmlspecialchars($p['payment_type_name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between">
        <strong>รายการสินค้า</strong>
        <button type="button" class="btn btn-sm btn-primary" id="btnAddRow"><i class="bi bi-plus-lg"></i> เพิ่มแถว</button>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0 align-middle" id="itemsTable">
            <thead class="table-light">
              <tr>
                <th style="min-width:260px;">สินค้า</th>
                <th style="width:120px;">จำนวน</th>
                <th style="width:140px;">ราคา/หน่วย</th>
                <th style="width:100px;">หน่วย</th>
                <th class="text-end" style="width:60px;">ลบ</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <select name="items[0][product_id]" class="form-select product-select" required>
                    <option value="">-- เลือกสินค้า --</option>
                    <?php
                      mysqli_data_seek($products, 0);
                      while($pr = $products->fetch_assoc()):
                    ?>
                      <option value="<?= $pr['product_id'] ?>"
                              data-unit="<?= htmlspecialchars($pr['unit']) ?>"
                              data-price="<?= $pr['unit_price'] ?>">
                        <?= htmlspecialchars($pr['product_name']) ?>
                      </option>
                    <?php endwhile; ?>
                  </select>
                </td>
                <td><input type="number" name="items[0][qty]" class="form-control" value="1" min="1" required></td>
                <td><input type="number" step="0.01" name="items[0][unit_price]" class="form-control unit-price" placeholder="อ้างอิง"></td>
                <td><input type="text" name="items[0][unit]" class="form-control unit-field" placeholder="หน่วย"></td>
                <td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm btnRemoveRow"><i class="bi bi-x-lg"></i></button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="mt-3 d-flex gap-2">
      <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle"></i> บันทึก PO</button>
      <a href="order_history.php" class="btn btn-secondary">ยกเลิก</a>
      <a href="Empbuy.php" class="btn btn-secondary">กลับหน้าหลัก</a>
    </div>
    <div class="alert alert-info mt-3" id="formMsg" style="display:none"></div>
    </div>
  </form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let rowIdx = 1;
const optionsHTML = document.querySelector('.product-select').innerHTML;

document.getElementById('btnAddRow').addEventListener('click', () => {
    const tbody = document.querySelector('#itemsTable tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><select name="items[${rowIdx}][product_id]" class="form-select product-select" required>${optionsHTML}</select></td>
      <td><input type="number" name="items[${rowIdx}][qty]" class="form-control" value="1" min="1" required></td>
      <td><input type="number" step="0.01" name="items[${rowIdx}][unit_price]" class="form-control unit-price" placeholder="อ้างอิง"></td>
      <td><input type="text" name="items[${rowIdx}][unit]" class="form-control unit-field" placeholder="หน่วย"></td>
      <td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm btnRemoveRow"><i class="bi bi-x-lg"></i></button></td>
    `;
    tbody.appendChild(tr);
    rowIdx++;
});

document.addEventListener('change', (e) => {
    if (!e.target.classList.contains('product-select')) return;
    const opt = e.target.selectedOptions[0];
    const tr  = e.target.closest('tr');
    tr.querySelector('.unit-field').value = opt.getAttribute('data-unit') || '';
    tr.querySelector('.unit-price').value = opt.getAttribute('data-price') || '';
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.btnRemoveRow')) return;
    const tr = e.target.closest('tr');
    const tbody = tr.parentElement;
    if (tbody.children.length > 1) tr.remove();
});

// UX: show message if form invalid
document.getElementById('poForm').addEventListener('submit', function(e) {
    if (!this.checkValidity()) {
        e.preventDefault();
        document.getElementById('formMsg').textContent = 'กรุณากรอกข้อมูลให้ครบถ้วน';
        document.getElementById('formMsg').style.display = 'block';
        window.scrollTo({top:0, behavior:'smooth'});
    }
});
</script>
</body>
</html>
