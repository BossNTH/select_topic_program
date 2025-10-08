<?php
// staff_purchase.php : ระบบจัดซื้อสำหรับพนักงานจัดซื้อ (ตกแต่งสวย ใช้งานง่าย)
session_start();

// ตรวจสอบสิทธิ์ก่อนส่ง output ใด ๆ ออกไป
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
    header("Location: ../login.php"); // ถ้า login.php อยู่โฟลเดอร์เดียวกัน ให้ใช้แบบนี้
    exit();
}
require_once("../connect.php");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ระบบจัดซื้อ - พนักงานจัดซื้อ</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        :root {
            --brand-primary: #2563eb; /* blue-600 */
            --brand-secondary: #1e40af; /* blue-800 */
            --brand-accent: #22c55e; /* green-500 */
            --brand-soft: #e0e7ff; /* indigo-100 */
        }
        /* Navbar สวยแบบไล่เฉด */
        .navbar-gradient {
            background: linear-gradient(90deg, var(--brand-secondary) 0%, var(--brand-primary) 50%, #0ea5e9 100%);
        }
        .navbar .nav-link {
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .navbar .dropdown-menu {
            border-radius: 14px;
            padding: .5rem;
            border: 1px solid rgba(0,0,0,.05);
            box-shadow: 0 10px 25px rgba(2, 6, 23, 0.12);
        }
        .dropdown-item {
            border-radius: 10px;
            padding: .6rem .8rem;
        }
        .dropdown-item:hover {
            background: #f1f5f9;
        }
        /* Hero Section */
        .hero {
            position: relative;
            border-radius: 20px;
            background:
                radial-gradient(1200px 400px at 10% 0%, #e0f2fe 10%, transparent 60%),
                radial-gradient(800px 400px at 90% 0%, #dbeafe 0%, transparent 60%),
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            overflow: hidden;
        }
        .hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: url('https://cdn.jsdelivr.net/gh/tabler/tabler-icons/icons/box.svg') no-repeat right -60px top -60px / 320px auto;
            opacity: .06;
            pointer-events: none;
        }
        /* Card สวยๆ */
        .card-action {
            border: 1px solid rgba(0,0,0,.05);
            border-radius: 16px;
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .card-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 40px rgba(2, 6, 23, 0.12);
        }
        .icon-pill {
            width: 46px; height: 46px; display:flex; align-items:center; justify-content:center;
            border-radius: 12px; background: var(--brand-soft);
        }
        .stat {
            border-radius: 16px; background: linear-gradient(180deg, #fff, #f8fafc);
            border: 1px solid rgba(0,0,0,.06);
        }
        footer { color: #64748b; }
        .badge-soft { background: #eef2ff; color: #4f46e5; }
    </style>
</head>
<body class="bg-light min-vh-100 d-flex flex-column">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-gradient shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="empBuy.php">
            <i class="bi bi-cart-check"></i>
            ระบบจัดซื้อ (พนักงาน)
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto">
                <!-- จัดการสินค้า -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="productDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-box-seam me-1"></i> จัดการสินค้า
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="productDropdown">
                        <li><a class="dropdown-item" href="product_manage/product_list.php"><i class="bi bi-list-ul me-1"></i> รายการสินค้า</a></li>
                        <li><a class="dropdown-item" href="product_manage/product_add.php"><i class="bi bi-plus-square me-1"></i> เพิ่มสินค้าใหม่</a></li>
                        <li><a class="dropdown-item" href="product_manage/product_category.php"><i class="bi bi-tags me-1"></i> จัดการประเภทสินค้า</a></li>
                    </ul>
                </li>

                <!-- ใบขอซื้อ -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="requestDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-file-earmark-text me-1"></i> ใบขอซื้อ
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="requestDropdown">
                        <li><a class="dropdown-item" href="pr/pr_list1.php"><i class="bi bi-inbox me-1"></i> ดูใบขอซื้อ</a></li>
                        <li><a class="dropdown-item" href="pr/announcement.php"><i class="bi bi-megaphone me-1"></i> เปิดประกาศ</a></li>
                        <li><a class="dropdown-item" href="pr/request_history.php"><i class="bi bi-clock-history me-1"></i> ประวัติใบขอซื้อ</a></li>
                    </ul>
                </li>

                <!-- สั่งซื้อ -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="orderDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-clipboard2-check me-1"></i> สั่งซื้อ
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="orderDropdown">
                        <li><a class="dropdown-item" href="order_create.php"><i class="bi bi-journal-plus me-1"></i> ออกใบสั่งซื้อ</a></li>
                        <li><a class="dropdown-item" href="order_history.php"><i class="bi bi-journal-text me-1"></i> ประวัติการสั่งซื้อ</a></li>
                    </ul>
                </li>

                <!-- รายงาน -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-graph-up-arrow me-1"></i> รายงาน
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="reportDropdown">
                        <li><a class="dropdown-item" href="report/report_tax.php"><i class="bi bi-receipt me-1"></i> รายงานภาษี</a></li>
                        <li><a class="dropdown-item" href="report/report_purchase.php"><i class="bi bi-bar-chart-line me-1"></i> รายงานการจัดซื้อ</a></li>
                        <li><a class="dropdown-item" href="report/report_vendor.php"><i class="bi bi-people me-1"></i> รายงานผู้ขาย</a></li>
                    </ul>
                </li>
            </ul>

            <!-- ขวา: โปรไฟล์/ช่วยเหลือ -->
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item me-lg-2"><span class="badge rounded-pill text-bg-success">สถานะ: พร้อมทำงาน</span></li>
                <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#helpModal"><i class="bi bi-question-circle"></i> ช่วยเหลือ</a></li>
                <li class="nav-item dropdown ms-lg-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://i.pravatar.cc/32?img=12" alt="avatar" class="rounded-circle border border-light" width="28" height="28" />
                        <span class="d-none d-lg-inline">Purchasing</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><h6 class="dropdown-header">โปรไฟล์</h6></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person-gear me-1"></i> ตั้งค่า</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-shield-lock me-1"></i> เปลี่ยนรหัสผ่าน</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../logout.php"><i class="bi bi-box-arrow-right me-1"></i> ออกจากระบบ</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- เนื้อหาหลัก -->
<main class="container my-4 flex-grow-1">
    <!-- Hero / Welcome -->
    <section class="hero p-4 p-md-5 mb-4 shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">ยินดีต้อนรับ พนักงานจัดซื้อ</h1>
                <p class="text-secondary mb-3">เลือกเมนูด้านบนหรือใช้ปุ่มลัดด้านล่างเพื่อทำงานได้ไวขึ้น</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="order_create.php" class="btn btn-success d-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle"></i> ออกใบสั่งซื้อใหม่
                    </a>
                    <a href="request_list.php" class="btn btn-outline-primary d-flex align-items-center gap-2">
                        <i class="bi bi-inbox"></i> ตรวจใบขอซื้อ
                    </a>
                    <a href="report_purchase.php" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                        <i class="bi bi-bar-chart"></i> ดูรายงานล่าสุด
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat p-3 text-center">
                            <div class="small text-secondary">ใบขอซื้อค้างอนุมัติ</div>
                            <div class="fs-3 fw-bold">8</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat p-3 text-center">
                            <div class="small text-secondary">ผู้ขายรอตอบ</div>
                            <div class="fs-3 fw-bold">3</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="stat p-3 text-center">
                            <div class="small text-secondary">วงเงินที่ใช้เดือนนี้</div>
                            <div class="fs-4 fw-bold">฿ 128,500</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Action Cards -->
    <section class="mb-4">
        <div class="row g-3 g-md-4">
            <div class="col-md-6 col-xl-3">
                <a href="product_manage/product_list.php" class="text-decoration-none text-dark">
                    <div class="card card-action h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="icon-pill"><i class="bi bi-boxes fs-4"></i></div>
                            <div>
                                <div class="fw-semibold">รายการสินค้า</div>
                                <div class="text-secondary small">ค้นหา/แก้ไข สินค้า</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-secondary"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a href="pr/announcement.php" class="text-decoration-none text-dark">
                    <div class="card card-action h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="icon-pill"><i class="bi bi-megaphone fs-4"></i></div>
                            <div>
                                <div class="fw-semibold">เปิดประกาศ</div>
                                <div class="text-secondary small">ประกาศเชิญเสนอราคา</div>
                            </div>
                            <span class="badge badge-soft ms-auto">ใหม่</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a href="order_create.php" class="text-decoration-none text-dark">
                    <div class="card card-action h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="icon-pill"><i class="bi bi-clipboard2-plus fs-4"></i></div>
                            <div>
                                <div class="fw-semibold">ออกใบสั่งซื้อ</div>
                                <div class="text-secondary small">สร้างใบสั่งซื้อ (PO)</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-secondary"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a href="report_vendor.php" class="text-decoration-none text-dark">
                    <div class="card card-action h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="icon-pill"><i class="bi bi-people fs-4"></i></div>
                            <div>
                                <div class="fw-semibold">รายงานผู้ขาย</div>
                                <div class="text-secondary small">สรุปคู่ค้าและผลงาน</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-secondary"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- ตารางงานด่วน (ตัวอย่างข้อมูลจำลอง) -->
    <section class="mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <div class="fw-semibold"><i class="bi bi-lightning-charge me-1 text-warning"></i> งานด่วนวันนี้</div>
                <a href="request_list.php" class="btn btn-sm btn-outline-primary">ดูทั้งหมด</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">เลขที่</th>
                                <th>เรื่อง</th>
                                <th class="text-nowrap">ผู้ร้องขอ</th>
                                <th class="text-nowrap">สถานะ</th>
                                <th class="text-end text-nowrap">การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>REQ-2025-0098</td>
                                <td>จัดซื้อคอมพิวเตอร์สำหรับแผนก IT</td>
                                <td>สุชาดา อ.</td>
                                <td><span class="badge text-bg-warning">รอตรวจสอบ</span></td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-primary"><i class="bi bi-check2-circle me-1"></i> อนุมัติ</a>
                                </td>
                            </tr>
                            <tr>
                                <td>REQ-2025-0097</td>
                                <td>สั่งซื้อวัสดุสิ้นเปลืองสำนักงาน</td>
                                <td>ปรเมศวร์ พ.</td>
                                <td><span class="badge text-bg-info">รอใบเสนอราคา</span></td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-send me-1"></i> ส่งประกาศ</a>
                                </td>
                            </tr>
                            <tr>
                                <td>REQ-2025-0096</td>
                                <td>เปลี่ยนผู้อุปทานสำหรับหมึกพิมพ์</td>
                                <td>อังคณา ช.</td>
                                <td><span class="badge text-bg-success">เสร็จสิ้น</span></td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" data-bs-title="พิมพ์สรุป"><i class="bi bi-printer"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="mt-auto py-4">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="small">© <?php echo date('Y'); ?> Purchasing System • Build v1.0</div>
        <div class="small text-secondary">เคล็ดลัด: กด <kbd>Ctrl</kbd> + <kbd>K</kbd> เพื่อค้นหาเมนู</div>
    </div>
</footer>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helpModalLabel"><i class="bi bi-question-circle me-2"></i>ศูนย์ช่วยเหลือ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="mb-0">
            <li>เมนู <strong>ใบขอซื้อ</strong> ใช้ตรวจสอบ/อนุมัติคำขอจากหน่วยงาน</li>
            <li>ใช้ <strong>เปิดประกาศ</strong> เพื่อเชิญผู้ขายส่งใบเสนอราคา</li>
            <li>สร้าง <strong>ใบสั่งซื้อ (PO)</strong> ได้จากเมนูสั่งซื้อ</li>
            <li>ดู <strong>รายงาน</strong> เพื่อวิเคราะห์ค่าใช้จ่ายและประสิทธิภาพคู่ค้า</li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
        <a href="report_purchase.php" class="btn btn-primary">ไปที่รายงาน</a>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS (Bundle รวม Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // เปิดใช้งาน Tooltip
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el))

  // คีย์ลัด Ctrl+K เปิด Search (เดโม่: โฟกัสไปที่เมนูใบขอซื้อ)
  document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
      e.preventDefault();
      const link = document.querySelector('a[href="request_list.php"]');
      if (link) link.focus();
    }
  });
</script>
</body>
</html>
