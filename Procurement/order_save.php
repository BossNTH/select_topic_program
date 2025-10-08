<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
  header("Location: ../login.php"); exit();
}
require '../connect.php';

$po_no          = trim($_POST['po_no'] ?? '');
$order_date     = $_POST['order_date'] ?? null;
$due_date       = $_POST['due_date'] ?? null;
$supplier_id    = (int)($_POST['supplier_id'] ?? 0);
$payment_type_id= (int)($_POST['payment_type_id'] ?? 0);
$items          = $_POST['items'] ?? [];
$status         = 'open'; // เริ่มต้นสถานะ open

if (!$po_no || !$order_date || !$due_date || $supplier_id<=0 || $payment_type_id<=0 || empty($items)) {
  echo "<script>alert('ข้อมูลไม่ครบถ้วน'); window.history.back();</script>"; exit();
}

$conn->begin_transaction();
try {
  // กันเลขซ้ำคร่าว ๆ
  $chk = $conn->prepare("SELECT po_id FROM purchase_orders WHERE po_no=?");
  $chk->bind_param("s", $po_no); $chk->execute();
  if ($chk->get_result()->num_rows > 0) throw new Exception("เลขที่ PO ซ้ำ");

  // Header
  $h = $conn->prepare("INSERT INTO purchase_orders (po_no, supplier_id, order_date, due_date, payment_type_id, status, created_at) 
                       VALUES (?,?,?,?,?,? , NOW())");
  $h->bind_param("sissis", $po_no, $supplier_id, $order_date, $due_date, $payment_type_id, $status);
  $h->execute();
  $po_id = $h->insert_id;

  // Items
  $i = $conn->prepare("INSERT INTO po_items (po_id, product_id, qty, unit, unit_price) VALUES (?,?,?,?,?)");
  foreach ($items as $it) {
    $pid  = (int)($it['product_id'] ?? 0);
    $qty  = (int)($it['qty'] ?? 0);
    $unit = trim($it['unit'] ?? '');
    $price= (float)($it['unit_price'] ?? 0);
    if ($pid<=0 || $qty<=0) continue;
    $i->bind_param("iiisd", $po_id, $pid, $qty, $unit, $price);
    $i->execute();
  }

  $conn->commit();
  header("Location: order_view.php?id=".$po_id);
  exit();

} catch (Throwable $e) {
  $conn->rollback();
  echo "<script>alert('บันทึกไม่สำเร็จ: ".$conn->real_escape_string($e->getMessage())."'); window.history.back();</script>";
}
