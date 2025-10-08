<?php
// announcement.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
  header("Location: ../login.php"); 
  exit();
}

require_once dirname(__DIR__, 2) . '/connect.php';

// ---- Debug flag ----
$DEBUG = false; // ถ้าอยากเห็น error ของ SQL ให้เปลี่ยนเป็น true

// ---- Query ----
$sql = "SELECT pr_id, pr_no, needed_date, status 
        FROM purchase_requisitions 
        WHERE status IN ('draft','pending') 
        ORDER BY pr_id DESC";

$prs = $conn->query($sql);

if ($prs === false && $DEBUG) {
  die("SQL Error: " . htmlspecialchars($conn->error));
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เปิดประกาศ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2 class="mb-3"><i class="bi bi-megaphone"></i> เปิดประกาศเชิญ Supplier</h2>
  <p class="text-muted">เลือกใบขอซื้อ (PR) ที่ต้องการเปิดประกาศ</p>

  <?php if ($prs === false): ?>
    <div class="alert alert-danger">
      ไม่สามารถดึงข้อมูลได้
      <?php if ($DEBUG): ?>
        <div class="small text-muted"><?= htmlspecialchars($conn->error) ?></div>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>เลขที่ PR</th>
          <th>วันที่ต้องการ</th>
          <th>สถานะ</th>
          <th class="text-center">ดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($prs->num_rows > 0): ?>
          <?php while($pr = $prs->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($pr['pr_no']) ?></td>
            <td><?= htmlspecialchars($pr['needed_date']) ?></td>
            <td><span class="badge bg-warning"><?= htmlspecialchars($pr['status']) ?></span></td>
            <td class="text-center">
              <a href="announcement_open.php?id=<?= urlencode($pr['pr_id']) ?>" 
                 class="btn btn-sm btn-success">
                 <i class="bi bi-bullhorn"></i> เปิดประกาศ
              </a>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center text-muted py-3">ไม่มี PR ที่รอการเปิดประกาศ</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <a href="../empBuy.php" class="btn btn-secondary mt-3">
    <i class="bi bi-arrow-left"></i> กลับ
  </a>
</body>
</html>
<?php
if ($prs instanceof mysqli_result) {
  $prs->free();
}
$conn->close();
