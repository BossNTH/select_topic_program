<?php
// ผู้ขายจัดการใบเสนอราคา
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'seller') {
  header("Location: ../login.php"); exit();
}
require_once __DIR__ . "/../connect.php";
require_once __DIR__ . "/partials/seller_header.php";

// รับ filter
$q = trim($_GET['q'] ?? '');
$status = trim($_GET['status'] ?? '');
$fromPr = trim($_GET['from_pr'] ?? ''); // กรณีมากดจาก PR เพื่อสร้างโควต

// ตัวอย่างตาราง/คอลัมน์ (เปลี่ยนให้ตรงสคีมาจริง):
// purchase_quotes(quote_no, pr_no, quote_date, total_amount, status)
$sql = "SELECT quote_no, pr_no, quote_date, total_amount, status
        FROM purchase_quotes
        WHERE 1=1";
$params = [];
$types = '';

if ($q !== '') { $sql.=" AND (quote_no LIKE ? OR pr_no LIKE ?)"; $params[]="%{$q}%"; $params[]="%{$q}%"; $types.="ss"; }
if ($status !== '') { $sql.=" AND status = ?"; $params[]=$status; $types.="s"; }

$sql .= " ORDER BY quote_date DESC";
$stmt = $conn->prepare($sql);
if ($params) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$rs = $stmt->get_result();
?>
<style>
  /* แถบเครื่องมือด้านบน */
  .q-toolbar{
    display: grid;
    grid-template-columns: minmax(260px, 2fr) minmax(200px, 1fr) auto auto;
    gap: 12px;
    align-items: stretch;
    margin-bottom: 12px;
  }
  .q-toolbar .form-control,
  .q-toolbar .form-select{
    height: 56px;              /* ให้สูงเท่ากันหมด */
    border-radius: 14px;
  }
  .btn-lg-square{
    width: 56px; height: 56px; /* ปุ่มค้นหาเป็นสี่เหลี่ยมจัตุรัส */
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 14px;
  }
  .btn-pill{
    border-radius: 14px;
    height: 56px;
    display: inline-flex; align-items: center; gap: 8px;
    white-space: nowrap;
  }

  /* การ์ดตาราง */
  .table-card{ border:1px solid #eaeef5; border-radius:16px; overflow:hidden; background:#fff; }
  .table thead th{ position: sticky; top: 0; background: #f8fafc; z-index: 1; }
  .table td, .table th{ vertical-align: middle; }
  .td-actions{ white-space: nowrap; }
  .table thead th:nth-child(1){ width: 180px; }
  .table thead th:nth-child(2){ width: 140px; }
  .table thead th:nth-child(3){ width: 160px; }
  .table thead th:nth-child(4){ width: 160px; }
  .table thead th:nth-child(5){ width: 140px; }
  .table thead th:nth-child(6){ width: 240px; }

  /* ระยะขอบรวมหน้า */
  .content{ padding: 24px; }

  /* Responsive */
  @media (max-width: 992px){
    .q-toolbar{
      grid-template-columns: 1fr 1fr;
    }
  }
  @media (max-width: 576px){
    .q-toolbar{
      grid-template-columns: 1fr;
    }
    .btn-lg-square{ width: 100%; }
    .btn-pill{ width: 100%; justify-content: center; }
  }
</style>


<div class="d-flex align-items-center justify-content-between mb-2">
  <h4 class="fw-bold mb-0"><i class="bi bi-receipt-cutoff me-2"></i>ใบเสนอราคา</h4>
</div>

<form method="get" class="q-toolbar">
  <input class="form-control" type="search" name="q"
         placeholder="ค้นหา Quote/PR..." value="<?= htmlspecialchars($q) ?>">
  <select class="form-select" name="status">
    <option value="">สถานะทั้งหมด</option>
    <?php
      $opt = [
        'draft'=>'ฉบับร่าง','sent'=>'ส่งแล้ว','received'=>'ลูกค้าได้รับ',
        'accepted'=>'ลูกค้าตอบรับ','rejected'=>'ถูกปฏิเสธ','expired'=>'หมดอายุ'
      ];
      foreach($opt as $k=>$v){
        $sel = $status===$k?'selected':'';
        echo "<option value='{$k}' {$sel}>{$v}</option>";
      }
    ?>
  </select>
  <button class="btn btn-primary btn-lg-square" type="submit">
    <i class="bi bi-search"></i>
  </button>

  <?php if (!empty($fromPr)): ?>
    <a class="btn btn-success btn-pill" href="quotation_create.php?pr=<?= urlencode($fromPr) ?>">
      <i class="bi bi-plus-lg"></i> สร้างจาก PR: <?= htmlspecialchars($fromPr) ?>
    </a>
  <?php else: ?>
    <a class="btn btn-success btn-pill" href="quotation_create.php">
      <i class="bi bi-plus-lg"></i> สร้างใบเสนอราคา
    </a>
  <?php endif; ?>
</form>


<div class="table-card">
  <table class="table table-hover align-middle mb-0">
    <thead class="table-light">
      <tr>
        <th>เลขที่ใบเสนอราคา</th>
        <th>อ้างอิง PR</th>
        <th>วันที่เสนอ</th>
        <th>ยอดรวม</th>
        <th>สถานะ</th>
        <th class="text-end">การจัดการ</th>
      </tr>
    </thead>
    <tbody>
      <!-- แถวข้อมูลของคุณ -->
    </tbody>
  </table>
</div>


<?php
$stmt->close();
require_once __DIR__ . "/partials/seller_footer.php";
