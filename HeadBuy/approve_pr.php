<?php
require_once __DIR__ . "/../auth_roles.php"; require_roles(['admin','manager']);
require_once __DIR__ . "/../Emp/partials/staff_header.php"; // ใช้ Sidebar เดียวกัน
$rs = $conn->query("SELECT pr_id, pr_no, request_date, need_by_date, status FROM purchase_requisitions WHERE status='submitted' ORDER BY pr_id ASC");
?>
<h4 class="fw-bold mb-3"><i class="bi bi-patch-check me-2"></i>อนุมัติ PR</h4>
<div class="card"><table class="table table-hover align-middle mb-0">
<thead class="table-light"><tr><th>PR</th><th>วันที่</th><th>Need by</th><th>สถานะ</th><th class="text-end">การอนุมัติ</th></tr></thead>
<tbody>
<?php if($rs->num_rows): while($r=$rs->fetch_assoc()): ?>
<tr>
  <td class="fw-semibold"><?= htmlspecialchars($r['pr_no']) ?></td>
  <td><?= htmlspecialchars($r['request_date']) ?></td>
  <td><?= htmlspecialchars($r['need_by_date']) ?></td>
  <td><span class="badge bg-primary"><?= htmlspecialchars($r['status']) ?></span></td>
  <td class="text-end">
    <a class="btn btn-sm btn-success" href="approve_pr_action.php?id=<?= (int)$r['pr_id'] ?>&act=approve">อนุมัติ</a>
    <a class="btn btn-sm btn-outline-danger" href="approve_pr_action.php?id=<?= (int)$r['pr_id'] ?>&act=reject">ไม่อนุมัติ</a>
  </td>
</tr>
<?php endwhile; else: ?><tr><td colspan="5" class="text-center text-muted py-4">— ไม่มีรายการ —</td></tr><?php endif; ?>
</tbody></table></div>
<?php require_once __DIR__ . "/../Emp/partials/staff_footer.php"; ?>
