<?php
// Devolper/dashboard.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require_once("../connect.php");

/**
 * === ตั้งชื่อ table/column ให้ตรง DB ของคุณ ===
 * $TABLE_USERS          : ตารางผู้ใช้ (มีคอลัมน์ role)
 * $TABLE_DEPARTMENTS    : ตารางแผนก
 * $TABLE_PRODUCT_TYPES  : ตารางประเภทสินค้า
 * $TABLE_SALES          : ตารางยอดขาย (มีคอลัมน์ total_amount เป็นมูลค่าใบขาย/ออเดอร์)
 * ปรับชื่อให้ตรงแล้วใช้งานได้เลย
 */
$TABLE_USERS         = "employees"; // ถ้าสมาชิกคือ users ทั้งหมด ให้ใช้ "users"
$TABLE_DEPARTMENTS   = "departments";
$TABLE_PRODUCT_TYPES = "product_categories";
$TABLE_SALES         = "sales";        // ถ้าไม่มีตารางนี้ จะถูกจับ error และแสดง 0 อัตโนมัติ
$SALES_AMOUNT_COL    = "total_amount"; // ชื่อคอลัมน์ยอดเงินรวมในตารางขาย

// ฟังก์ชันช่วยให้ query แล้วได้เลขจำนวน/ผลรวมแบบปลอดภัย (ถ้า error ให้คืน 0)
function scalar_or_zero(mysqli $conn, string $sql): float {
    $res = $conn->query($sql);
    if ($res && ($row = $res->fetch_row())) {
        return (float)$row[0];
    }
    return 0;
}

// ===== ดึงตัวเลขสรุป =====
// จำนวน "พนักงานทั้งหมด" : ถ้าในระบบคุณนับเฉพาะ role='employee' ให้เปลี่ยน WHERE ตามต้องการ
$totalEmployees = scalar_or_zero($conn, "SELECT COUNT(*) FROM {$TABLE_USERS}");

// จำนวนแผนก
$totalDepartments = scalar_or_zero($conn, "SELECT COUNT(*) FROM {$TABLE_DEPARTMENTS}");

// จำนวนประเภทสินค้า
$totalProductTypes = scalar_or_zero($conn, "SELECT COUNT(*) FROM {$TABLE_PRODUCT_TYPES}");

// จำนวนสมาชิกทั้งหมด (ถ้าคือ users ทั้งหมด ให้ใช้ COUNT(*) ตรง ๆ)
// ถ้าสมาชิกคือกลุ่ม role ใด ๆ เฉพาะ ให้ใส่ WHERE role IN (...)
$totalMembers = scalar_or_zero($conn, "SELECT COUNT(*) FROM {$TABLE_USERS}");

// ยอดขายรวม (ถ้าไม่มีตาราง/คอลัมน์นี้จะได้ 0 อัตโนมัติ)
$totalSales = scalar_or_zero($conn, "SELECT COUNT(*) FROM {$TABLE_USERS}");
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body { font-family: 'Sarabun', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; }
    .sidebar {
      height: 100vh; background: #0d6efd; color: #fff; padding-top: 20px;
      position: sticky; top: 0;
    }
    .sidebar a { color: #fff; text-decoration: none; display: block; padding: 10px 20px; border-radius: 8px; }
    .sidebar a:hover { background: rgba(255,255,255,0.18); }
    .content { padding: 20px; }
    .card { border: 0; border-radius: 16px; }
    .card .icon { font-size: 28px; opacity: .9; }
    .stretched-link { position: relative; z-index: 1; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <nav class="col-md-2 d-none d-md-block sidebar">
      <h4 class="text-center mb-4 pt-2"><i class="fa fa-gauge-high me-2"></i>Dashboard</h4>
      <a href="employeeManagement.php"><i class="fa fa-users me-2"></i>พนักงาน</a>
      <a href="department_add.php"><i class="fa fa-building me-2"></i>แผนก</a>
      <a href="product_type_add.php"><i class="fa fa-box me-2"></i>ประเภทสินค้า</a>
      <a href="payment_type_add.php"><i class="fa fa-money-bill me-2"></i>ประเภทการจ่าย</a>
      <a href="member_list.php"><i class="fa fa-user-tie me-2"></i>สมาชิก</a>
      <hr>
      <a href="../logout.php" class="text-warning"><i class="fa fa-sign-out-alt me-2"></i>ออกจากระบบ</a>
    </nav>

    <!-- Main -->
    <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 content">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">สวัสดี, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>
        <span class="text-muted">อัปเดตล่าสุด: <?php echo date('d/m/Y H:i'); ?></span>
      </div>

      <!-- Summary Cards -->
      <div class="row g-3">

        <div class="col-sm-6 col-lg-3">
          <div class="card text-bg-primary shadow h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <div class="small">จำนวนพนักงานทั้งหมด</div>
                  <h3 class="mb-0"><?php echo number_format($totalEmployees); ?></h3>
                </div>
                <i class="fa fa-users icon"></i>
              </div>
              <a href="employee_add.php" class="stretched-link"></a>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3">
          <div class="card text-bg-success shadow h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <div class="small">จำนวนแผนก</div>
                  <h3 class="mb-0"><?php echo number_format($totalDepartments); ?></h3>
                </div>
                <i class="fa fa-building icon"></i>
              </div>
              <a href="department_add.php" class="stretched-link"></a>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3">
          <div class="card text-bg-warning shadow h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <div class="small">จำนวนประเภทสินค้า</div>
                  <h3 class="mb-0"><?php echo number_format($totalProductTypes); ?></h3>
                </div>
                <i class="fa fa-box icon"></i>
              </div>
              <a href="product_type_add.php" class="stretched-link"></a>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3">
          <div class="card text-bg-dark shadow h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <div class="small">จำนวนสมาชิก</div>
                  <h3 class="mb-0"><?php echo number_format($totalMembers); ?></h3>
                </div>
                <i class="fa fa-user-friends icon"></i>
              </div>
              <a href="member_list.php" class="stretched-link"></a>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-lg-3">
          <div class="card text-bg-danger shadow h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <div class="small">ยอดขายรวม</div>
                  <h3 class="mb-0"><?php echo number_format($totalSales, 2); ?></h3>
                </div>
                <i class="fa fa-sack-dollar icon"></i>
              </div>
              <!-- ลิ้งก์ไปหน้ารายงานขาย (ใส่ไฟล์จริงของคุณ) -->
              <a href="sales_report.php" class="stretched-link"></a>
            </div>
          </div>
        </div>

      </div>

    </main>
  </div>
</div>

</body>
</html>
