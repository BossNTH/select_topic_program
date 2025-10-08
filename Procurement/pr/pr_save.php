<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
  header("Location: ../login.php"); exit();
}
require_once dirname(__DIR__, 2) . '/connect.php';


$pr_no       = trim($_POST['pr_no'] ?? '');
$needed_date = $_POST['needed_date'] ?? null;
$status      = $_POST['status'] ?? 'draft';
$items       = $_POST['items'] ?? [];

// เพิ่ม request_date (วันที่ขอซื้อ) เป็นวันนี้
$request_date = date('Y-m-d');
// หา requester_id จาก session (username = employee_code)
$requester_id = null;
if (isset($_SESSION['username'])) {
  $u = $_SESSION['username'];
  $q = $conn->prepare("SELECT employee_id FROM employees WHERE employee_code=? LIMIT 1");
  $q->bind_param("s", $u); $q->execute();
  $res = $q->get_result();
  if ($row = $res->fetch_assoc()) {
    $requester_id = $row['employee_id'];
  }
}

if (!$pr_no || !$needed_date || empty($items) || !$requester_id) {
  echo "<script>alert('ข้อมูลไม่ครบ หรือไม่พบผู้ขอซื้อ'); window.history.back();</script>"; exit();
}

$conn->begin_transaction();
try {
  // กันเลขซ้ำแบบง่าย (ถ้าจำเป็น)
  $chk = $conn->prepare("SELECT pr_no FROM purchase_requisitions WHERE pr_no=? LIMIT 1");
  $chk->bind_param("s", $pr_no); $chk->execute();
  if ($chk->get_result()->num_rows > 0) {
    throw new Exception("เลขที่ PR นี้มีอยู่แล้ว");
  }

  // Insert Header (ตามโครงสร้างฐานข้อมูล)
  $stmt = $conn->prepare("INSERT INTO purchase_requisitions (pr_no, request_date, need_by_date, requester_id, status) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssis", $pr_no, $request_date, $needed_date, $requester_id, $status);
  $stmt->execute();

  // Insert Items (pr_items)
  $iStmt = $conn->prepare("INSERT INTO pr_items (pr_no, line_no, product_id, qty_requested, unit_price, unit) VALUES (?, ?, ?, ?, ?, ?)");
  $line = 1;
  foreach ($items as $it) {
    $pid  = (int)($it['product_id'] ?? 0);
    $qty  = (int)($it['qty'] ?? 0);
    $unit = trim($it['unit'] ?? '');
    $price= (float)($it['unit_price'] ?? 0);
    if ($pid <= 0 || $qty <= 0) continue;
    $iStmt->bind_param("siidss", $pr_no, $line, $pid, $qty, $price, $unit);
    $iStmt->execute();
    $line++;
  }

  $conn->commit();
  header("Location: pr_view.php?id=".urlencode($pr_no));
  exit();

} catch (Throwable $e) {
  $conn->rollback();
  echo "<script>alert('บันทึกไม่สำเร็จ: ".$conn->real_escape_string($e->getMessage())."'); window.history.back();</script>";
}
