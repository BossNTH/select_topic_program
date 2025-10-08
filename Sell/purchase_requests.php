<?php
// ผู้ขายดูรายการขอซื้อ (PR)
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'seller') {
  header("Location: ../login.php"); exit();
}
require_once __DIR__ . "/../connect.php";
require_once __DIR__ . "/partials/seller_header.php";

// รับคีย์เวิร์ด/สถานะจากฟอร์มค้นหา
$q = trim($_GET['q'] ?? '');
$status = trim($_GET['status'] ?? '');

// ตัวอย่างตาราง/คอลัมน์ (เปลี่ยนให้ตรงระบบจริง):
// purchase_requisitions(pr_no, request_date, need_by_date, status)
$sql = "SELECT pr_no, request_date, need_by_date, status
        FROM purchase_requisitions
        WHERE 1=1";
$params = [];
$types  = '';

if ($q !== '') {
  $sql .= " AND (pr_no LIKE ?)";
  $params[] = "%{$q}%";
  $types   .= "s";
}
if ($status !== '') {
  $sql .= " AND status = ?";
  $params[] = $status;
  $types   .= "s";
}
$sql .= " ORDER BY request_date DESC";

$stmt = $conn->prepare($sql);
if ($params) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$rs = $stmt->get_result();
?>
<style>
  .table-card{ border:1px solid #eaeef5; border-radius:16px; overflow:hidden; }
  .badge-status{ text-transform:capitalize; }
</style>

<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
  <h4 class="fw-bold mb-2"><i class="bi bi-list-check me-2"></i>รายการขอซื้อ (PR)</h4>
  <form class="d-flex gap-2" method="get">
    <input class="form-control" type="search" name="q" placeholder="ค้นหาเลขที่ PR..." value="<?= htmlspecialchars($q) ?>">
    <select class="form-select" name="status">
      <option value="">สถานะทั้งหมด</option>
      <?php
        $opt = ['draft'=>'ฉบับร่าง','submitted'=>'ส่งแล้ว','approved'=>'อนุมัติ','rejected'=>'ปฏิเสธ','cancelled'=>'ยกเลิก','closed'=>'ปิดงาน'];
        foreach($opt as $k=>$v){
          $sel = $status===$k?'selected':'';
          echo "<option value='{$k}' {$sel}>{$v}</option>";
        }
      ?>
    </select>
    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
  </form>
</div>

<div class="table-card">
  <table class="table table-hover align-middle mb-0">
    <thead class="table-light">
      <tr>
        <th style="width:130px">เลขที่ PR</th>
        <th style="width:140px">วันที่ขอซื้อ</th>
        <th style="width:140px">ต้องการภายใน</th>
        <th style="width:140px">สถานะ</th>
        <th class="text-end" style="width:200px">การจัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($rs->num_rows): ?>
        <?php while($r = $rs->fetch_assoc()): ?>
          <?php
            $badge = [
              'draft'     =>'secondary',
              'submitted' =>'primary',
              'approved'  =>'success',
              'rejected'  =>'danger',
              'cancelled' =>'dark',
              'closed'    =>'info'
            ][$r['status']] ?? 'secondary';
          ?>
          <tr>
            <td class="fw-semibold"><?= htmlspecialchars($r['pr_no']) ?></td>
            <td><?= htmlspecialchars($r['request_date']) ?></td>
            <td><?= htmlspecialchars($r['need_by_date']) ?></td>
            <td><span class="badge badge-status bg-<?= $badge ?>"><?= htmlspecialchars($r['status']) ?></span></td>
            <td class="text-end">
              <a href="purchase_request_view.php?pr=<?= urlencode($r['pr_no']) ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-eye"></i> รายละเอียด
              </a>
              <a href="quotation.php?from_pr=<?= urlencode($r['pr_no']) ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg"></i> สร้างใบเสนอราคา
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center text-muted py-4">— ไม่พบข้อมูล —</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php
$stmt->close();
require_once __DIR__ . "/partials/seller_footer.php";
