<?php
// pr_list1.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
  header("Location: ../login.php");
  exit();
}

// ---- ตั้งค่า ----
$DEBUG = false; // เปลี่ยนเป็น true ชั่วคราวเวลาอยากเห็นข้อความ error ของ SQL

// ---- เชื่อมต่อฐานข้อมูล ----
// ใช้ไฟล์ connect.php ที่สร้าง $conn = new mysqli(...);
require_once dirname(__DIR__, 2) . '/connect.php';
if ($conn->connect_error) {
  // ถ้าคอนเนกต์ไม่ได้ ให้บอกชัดเจน
  die("Database connection failed: " . htmlspecialchars($conn->connect_error));
}

// ---- สร้างคำสั่ง SQL ----
// หมายเหตุ: ตรวจให้ตรงกับ schema จริงของคุณว่ามีคอลัมน์ pr_id, pr_no, needed_date, status, created_at หรือไม่
$sql = "SELECT pr_id, pr_no, needed_date, status, created_at
        FROM purchase_requisitions
        ORDER BY pr_id DESC";

$prs = $conn->query($sql);

// ถ้า query fail ให้ขึ้นข้อความ (ถ้าเปิดโหมด DEBUG)
if ($prs === false && $DEBUG) {
  die("SQL Error: " . htmlspecialchars($conn->error));
}

// สำหรับแสดง badge สีตามสถานะ
function statusBadgeClass(string $status): string {
  $map = [
    'draft'    => 'secondary',
    'pending'  => 'warning',
    'approved' => 'success',
    'rejected' => 'danger',
  ];
  return $map[$status] ?? 'secondary';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ใบขอซื้อ (PR)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">
      <i class="bi bi-file-earmark-text"></i> ใบขอซื้อ (PR)
    </h2>
    <div class="d-flex gap-2">
      <a href="pr_create.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> สร้างใบขอซื้อ
      </a>
      <a href="../empBuy.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> กลับหน้าหลัก
      </a>
    </div>
  </div>

  <?php if ($prs === false): ?>
    <!-- กรณี query ล้มเหลว แต่ไม่ได้เปิด DEBUG ให้แสดงข้อความสวยๆ -->
    <div class="alert alert-danger">
      ไม่สามารถดึงข้อมูลใบขอซื้อได้ในขณะนี้
      <?php if ($DEBUG): ?>
        <div class="mt-2 small text-muted">รายละเอียด: <?= htmlspecialchars($conn->error) ?></div>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th style="width: 180px;">เลขที่ PR</th>
            <th style="width: 180px;">ต้องการใช้วันที่</th>
            <th style="width: 160px;">สถานะ</th>
            <th style="width: 220px;">สร้างเมื่อ</th>
            <th class="text-center" style="width: 140px;">จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($prs->num_rows > 0): ?>
            <?php while ($r = $prs->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($r['pr_no'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['needed_date'] ?? '') ?></td>
                <td>
                  <?php
                    $status = (string)($r['status'] ?? '');
                    $cls = statusBadgeClass($status);
                  ?>
                  <span class="badge text-bg-<?= $cls ?>"><?= htmlspecialchars($status) ?></span>
                </td>
                <td><?= htmlspecialchars($r['created_at'] ?? '') ?></td>
                <td class="text-center">
                  <a class="btn btn-sm btn-outline-primary" href="pr_view.php?id=<?= urlencode((string)($r['pr_id'] ?? '')) ?>">
                    <i class="bi bi-eye"></i> ดู
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">
                ไม่มีข้อมูลใบขอซื้อ
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <!-- Bootstrap JS (ถ้าจำเป็น) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// ปิดการเชื่อมต่อเมื่อเสร็จ (ไม่บังคับ แต่เป็นมารยาทที่ดี)
if (isset($prs) && $prs instanceof mysqli_result) {
  $prs->free();
}
$conn->close();
