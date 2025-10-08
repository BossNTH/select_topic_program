<?php
require_once __DIR__ . "/../../auth_roles.php";
require_roles(['admin','manager','employee','product_manager','project_manager']); // ฝั่งพนักงาน

$current = basename($_SERVER['PHP_SELF']);
$R = role_flags();

$isAdmin=$R['isAdmin']; $isMgr=$R['isManager']; $isEmp=$R['isEmployee'];
$isProd=$R['isProductMgr']; $isProj=$R['isProjectMgr'];

// badge ตัวอย่าง
$pendingPr = ($isAdmin||$isMgr) ? (int)$conn->query("SELECT COUNT(*) c FROM purchase_requisitions WHERE status='submitted'")->fetch_assoc()['c'] : 0;
?>
<!doctype html><html lang="th"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<title>พนักงาน</title>
<style>
:root{--sb-bg:#0f172a;--sb-text:#e5e7eb;--sb-muted:#94a3b8;--sb-hover:#1f2a44}
body{background:#f7f8fc}.layout{min-height:100vh;display:flex}
.sidebar{width:260px;flex:0 0 260px;background:var(--sb-bg);color:var(--sb-text);display:flex;flex-direction:column;position:sticky;top:0;height:100vh}
.brand{padding:18px 16px;border-bottom:1px solid rgba(255,255,255,.06);display:flex;gap:10px;align-items:center}
.menu{padding:12px 10px;overflow-y:auto}
.menu .nav-link{color:var(--sb-text);border-radius:10px;padding:10px 12px;display:flex;gap:10px;align-items:center;font-weight:500;margin:4px 6px}
.menu .nav-link:hover{background:var(--sb-hover);color:#fff}
.menu .nav-link.active{background:rgba(79,70,229,.15);border:1px solid rgba(79,70,229,.4);color:#fff}
.section-label{color:var(--sb-muted);text-transform:uppercase;font-size:.75rem;margin:8px 12px 4px}
.bottom{margin-top:auto;padding:12px;border-top:1px solid rgba(255,255,255,.06)}
.logout-btn{width:100%;color:#fff;border:1px solid rgba(255,255,255,.2);background:transparent}
.logout-btn:hover{background:rgba(255,255,255,.08);color:#fff}
.content{flex:1;padding:24px}
@media(max-width:768px){.layout{flex-direction:column}.sidebar{width:100%;height:auto;position:relative}.content{padding:16px}}
</style>
</head><body><div class="layout">
<aside class="sidebar">
  <div class="brand"><i class="bi bi-diagram-3 fs-4 text-primary"></i><div class="title">งานพนักงาน</div></div>
  <nav class="menu nav flex-column">
    <a class="nav-link <?= $current==='staff_dashboard.php'?'active':''; ?>" href="../Emp/staff_dashboard.php"><i class="bi bi-house-door"></i> หน้าหลัก</a>

    <div class="section-label">ทั่วไป</div>
    <?php if ($isEmp || $isProj || $isMgr || $isAdmin): ?>
      <a class="nav-link <?= $current==='pr_create.php'?'active':''; ?>" href="../Emp/pr_create.php"><i class="bi bi-file-plus"></i> ขอซื้อ (PR)</a>
      <a class="nav-link <?= $current==='pr_my.php'?'active':''; ?>" href="../Emp/pr_my.php"><i class="bi bi-list-check"></i> PR ของฉัน</a>
    <?php endif; ?>

    <?php if ($isProd || $isAdmin): ?>
      <div class="section-label">สินค้า/คงคลัง</div>
      <a class="nav-link <?= $current==='products.php'?'active':''; ?>" href="../Emp/products.php"><i class="bi bi-box-seam"></i> จัดการสินค้า</a>
      <a class="nav-link <?= $current==='inventory.php'?'active':''; ?>" href="../Emp/inventory.php"><i class="bi bi-archive"></i> คงคลัง</a>
    <?php endif; ?>

    <?php if ($isMgr || $isAdmin): ?>
      <div class="section-label">หัวหน้า</div>
      <a class="nav-link" href="../HeadBuy/approve_pr.php"><i class="bi bi-patch-check"></i> อนุมัติ PR
        <?php if($pendingPr>0): ?><span class="badge bg-danger ms-auto"><?= $pendingPr ?></span><?php endif; ?>
      </a>
      <a class="nav-link" href="../HeadBuy/approve_po.php"><i class="bi bi-journal-check"></i> อนุมัติ PO</a>
    <?php endif; ?>

    <div class="section-label">จัดซื้อ</div>
    <a class="nav-link <?= $current==='quotes_compare.php'?'active':''; ?>" href="../EmpBuy/quotes_compare.php"><i class="bi bi-clipboard-data"></i> เปรียบเทียบใบเสนอราคา</a>
    <?php if ($R['isProcurement'] || $isAdmin): ?>
      <a class="nav-link" href="../EmpBuy/po_manage.php"><i class="bi bi-receipt"></i> ออก PO</a>
      <a class="nav-link" href="../EmpBuy/vendor_manage.php"><i class="bi bi-people"></i> ผู้ขาย</a>
    <?php endif; ?>
  </nav>
  <div class="bottom"><a href="../logout.php" class="btn logout-btn"><i class="bi bi-box-arrow-right me-1"></i> ออกจากระบบ</a></div>
</aside>
<main class="content">
