<?php
require_once __DIR__ . "/partials/staff_header.php";  // มี sidebar

// ให้แน่ใจว่ามี $conn ใช้งานได้
$db = $GLOBALS['conn'] ?? null;
if (!$db) {
  // กันกรณี header ยังไม่ได้ include connect.php
  require_once __DIR__ . "/../connect.php";
  $db = $conn;
}

// user id จาก session (ถ้ายังไม่ได้เซ็ต ให้ไปเซ็ตตอน login)
$uid = (int)($_SESSION['user_id'] ?? 0);

// ปลอดภัยด้วย prepared statement
$myTotal = 0; 
$myPending = 0;

if ($db instanceof mysqli) {
  $stmt = $db->prepare("SELECT COUNT(*) c FROM purchase_requisitions WHERE user_id=?");
  $stmt->bind_param("i", $uid);
  $stmt->execute();
  $myTotal = (int)$stmt->get_result()->fetch_assoc()['c'];
  $stmt->close();

  $stmt = $db->prepare("SELECT COUNT(*) c FROM purchase_requisitions WHERE user_id=? AND status='submitted'");
  $stmt->bind_param("i", $uid);
  $stmt->execute();
  $myPending = (int)$stmt->get_result()->fetch_assoc()['c'];
  $stmt->close();
}
?>
