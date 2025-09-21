<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'seller') {
  header("Location: ../login.php"); exit();
}
require_once __DIR__ . "/../connect.php";
require_once __DIR__ . "/partials/seller_header.php";
 ?>

<h3 class="fw-bold mb-1">ยินดีต้อนรับ ผู้ขาย</h3>
<p class="text-muted">เลือกเมนูทางซ้ายเพื่อดูคำขอซื้อหรือจัดทำใบเสนอราคา</p>

<div class="row g-3 mt-2">
  <div class="col-lg-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title"><i class="bi bi-list-check me-1"></i> ดูรายการขอซื้อ (PR)</h5>
        <p class="card-text text-muted">ตรวจสอบรายการขอซื้อที่ลูกค้าส่งเข้ามา และดูรายละเอียดแต่ละรายการ</p>
        <a href="purchase_requests.php" class="btn btn-primary">ดูรายการขอซื้อ</a>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title"><i class="bi bi-receipt-cutoff me-1"></i> จัดการใบเสนอราคา</h5>
        <p class="card-text text-muted">สร้างและจัดการใบเสนอราคาสำหรับแต่ละคำขอซื้อที่ได้รับ</p>
        <a href="quotation.php" class="btn btn-success">จัดการใบเสนอราคา</a>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . "/partials/seller_footer.php"; ?>
