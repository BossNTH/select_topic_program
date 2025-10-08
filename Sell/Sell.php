<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'seller') {
  header("Location: ../login.php"); exit();
}
require_once __DIR__ . "/../connect.php";
require_once __DIR__ . "/partials/seller_header.php";
?>

<style>
  /* --- Styling เฉพาะหน้า Dashboard ผู้ขาย --- */
  .hero {
    border: 0;
    border-radius: 20px;
    background: linear-gradient(135deg, #4f46e5, #06b6d4);
    color: #fff;
    overflow: hidden;
  }
  .hero .hero-icon {
    width: 52px; height: 52px; 
    border-radius: 14px; 
    background: rgba(255,255,255,.18);
    display:flex; align-items:center; justify-content:center;
  }
  .action-card {
    border: 1px solid #eaeef5;
    border-radius: 16px;
    transition: transform .12s ease, box-shadow .2s ease, border-color .2s ease;
  }
  .action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(15,23,42,.08);
    border-color: #d7deea;
  }
  .stat {
    border-radius: 14px;
    background:#fff;
    border:1px solid #eaeef5;
  }
  .stat .num {
    font-weight: 800; 
    font-size: clamp(1.25rem, 2.8vw, 1.6rem);
  }
  .muted { color:#6b7280; }
</style>

<!-- Hero -->
<div class="card hero mb-4">
  <div class="card-body p-4 p-lg-5">
    <div class="d-flex align-items-center gap-3">
      <div class="hero-icon">
        <i class="bi bi-bag-check fs-4"></i>
      </div>
      <div>
        <h2 class="mb-1 fw-bold">สวัสดี, <?= htmlspecialchars($_SESSION['username'] ?? 'ผู้ขาย'); ?></h2>
        <div class="opacity-75">แดชบอร์ดสำหรับผู้ขาย — จัดการคำขอซื้อและใบเสนอราคาได้จากที่นี่</div>
      </div>
    </div>
  </div>
</div>

<!-- Quick Stats (ตัวอย่างตัวเลข placeholder) -->
<div class="row g-3 mb-3">
  <div class="col-6 col-lg-3">
    <div class="stat p-3">
      <div class="muted small mb-1"><i class="bi bi-inbox me-1"></i>PR รอพิจารณา</div>
      <div class="num">—</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat p-3">
      <div class="muted small mb-1"><i class="bi bi-receipt me-1"></i>ใบเสนอราคาที่ส่ง</div>
      <div class="num">—</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat p-3">
      <div class="muted small mb-1"><i class="bi bi-check2-circle me-1"></i>ที่ลูกค้าตอบรับ</div>
      <div class="num">—</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat p-3">
      <div class="muted small mb-1"><i class="bi bi-clock-history me-1"></i>รอผล</div>
      <div class="num">—</div>
    </div>
  </div>
</div>

<!-- Action Cards -->
<div class="row g-4">
  <div class="col-lg-6">
    <div class="card action-card h-100">
      <div class="card-body p-4">
        <div class="d-flex align-items-start gap-3">
          <div class="hero-icon" style="background:rgba(79,70,229,.12); color:#4f46e5;">
            <i class="bi bi-list-check fs-5"></i>
          </div>
          <div class="flex-grow-1">
            <h5 class="card-title mb-1">ดูรายการขอซื้อ (PR)</h5>
            <p class="card-text muted mb-3">ตรวจสอบคำขอซื้อที่ส่งเข้ามา ดูรายละเอียดและสถานะของแต่ละรายการ</p>
            <a href="purchase_requests.php" class="btn btn-primary">
              เปิดดู PR
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card action-card h-100">
      <div class="card-body p-4">
        <div class="d-flex align-items-start gap-3">
          <div class="hero-icon" style="background:rgba(16,185,129,.14); color:#10b981;">
            <i class="bi bi-receipt-cutoff fs-5"></i>
          </div>
          <div class="flex-grow-1">
            <h5 class="card-title mb-1">จัดการใบเสนอราคา</h5>
            <p class="card-text muted mb-3">สร้าง/แก้ไขใบเสนอราคาให้สอดคล้องกับ PR และส่งกลับให้ลูกค้า</p>
            <a href="quotation.php" class="btn btn-success">
              ไปที่ใบเสนอราคา
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tips -->
<div class="card mt-4 border-0" style="border-radius:16px; box-shadow:0 10px 24px rgba(15,23,42,.05);">
  <div class="card-body p-4">
    <h6 class="fw-bold mb-2"><i class="bi bi-lightbulb me-1 text-warning"></i> เคล็ดลับ</h6>
    <ul class="mb-0 text-muted small">
      <li>อัปเดตราคาสินค้าให้เป็นปัจจุบันก่อนส่งใบเสนอราคา</li>
      <li>ตรวจสอบวันหมดอายุของการเสนอราคาทุกครั้ง</li>
      <li>ตอบกลับ PR ให้เร็วที่สุดเพื่อโอกาสปิดงานที่มากขึ้น</li>
    </ul>
  </div>
</div>

<?php require_once __DIR__ . "/partials/seller_footer.php"; ?>
