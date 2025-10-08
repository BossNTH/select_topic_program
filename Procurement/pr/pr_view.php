<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
  header("Location: ../login.php"); exit();
}
require_once dirname(__DIR__, 2) . '/connect.php';

$pr_id = (int)($_GET['id'] ?? 0);

// Header
$hStmt = $conn->prepare("SELECT pr_id, pr_no, needed_date, status, created_at
                         FROM purchase_requisitions WHERE pr_id=?");
$hStmt->bind_param("i", $pr_id); $hStmt->execute();
$header = $hStmt->get_result()->fetch_assoc();
if (!$header) { echo "ไม่พบข้อมูล PR"; exit; }

// Items + product name
$iSql = "SELECT i.id, i.product_id, p.product_name, i.qty, i.unit, i.unit_price
         FROM pr_items i
         JOIN products p ON p.product_id = i.product_id
         WHERE i.pr_id=?
         ORDER BY i.id ASC";
$iStmt = $conn->prepare($iSql);
$iStmt->bind_param("i", $pr_id); $iStmt->execute();
$items = $iStmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>PR #<?= htmlspecialchars($header['pr_no']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>รายละเอียดใบขอซื้อ: <?= htmlspecialchars($header['pr_no']) ?></h2>
  <div class="row g-3 mb-3">
    <div class="col-md-3"><strong>ต้องการใช้วันที่:</strong> <?= htmlspecialchars($header['needed_date']) ?></div>
    <div class="col-md-3"><strong>สถานะ:</strong> <span class="badge text-bg-secondary"><?= $header['status'] ?></span></div>
    <div class="col-md-3"><strong>สร้างเมื่อ:</strong> <?= htmlspecialchars($header['created_at']) ?></div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>สินค้า</th>
          <th class="text-end">จำนวน</th>
          <th>หน่วย</th>
          <th class="text-end">ราคา/หน่วย</th>
          <th class="text-end">รวม</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $n=1; $sum=0;
          while($it = $items->fetch_assoc()):
            $line = (float)$it['unit_price'] * (int)$it['qty'];
            $sum += $line;
        ?>
        <tr>
          <td><?= $n++ ?></td>
          <td><?= htmlspecialchars($it['product_name']) ?></td>
          <td class="text-end"><?= (int)$it['qty'] ?></td>
          <td><?= htmlspecialchars($it['unit']) ?></td>
          <td class="text-end"><?= number_format((float)$it['unit_price'],2) ?></td>
          <td class="text-end"><?= number_format($line,2) ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
          <th colspan="5" class="text-end">ยอดรวม</th>
          <th class="text-end"><?= number_format($sum,2) ?></th>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="d-flex gap-2">
    <a class="btn btn-secondary" href="pr_list.php">กลับรายการ PR</a>
    <!-- ปุ่มอนุมัติ/ปฏิเสธ ไว้ต่อยอด -->
    <!-- <a class="btn btn-success" href="pr_approve.php?id=<?= $header['pr_id'] ?>">อนุมัติ</a>
    <a class="btn btn-danger" href="pr_reject.php?id=<?= $header['pr_id'] ?>">ปฏิเสธ</a> -->
  </div>
</body>
</html>
