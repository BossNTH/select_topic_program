<?php
session_start();
require_once "connect.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn->set_charset('utf8mb4');

function inpost($k){ return trim($_POST[$k] ?? ''); }

// users
$username = inpost('username');
$password = inpost('password');
$confirm  = inpost('confirm_password');
$role     = 'seller'; // บังคับเป็นผู้ขาย

// suppliers
$supplier_name = inpost('supplier_name');
$contact_info  = inpost('contact_info');
$address       = inpost('address');
$phone         = inpost('phone');
$email         = inpost('email');

// validate
if ($password !== $confirm) {
  echo "<script>alert('❌ รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน'); history.back();</script>"; exit;
}
if (strlen($password) < 6) {
  echo "<script>alert('❌ รหัสผ่านต้องอย่างน้อย 6 ตัวอักษร'); history.back();</script>"; exit;
}
if ($supplier_name === '') {
  echo "<script>alert('❌ กรุณากรอกชื่อผู้ขาย/ชื่อร้าน'); history.back();</script>"; exit;
}

try {
  // ตรวจซ้ำ username ใน users
  $chkU = $conn->prepare("SELECT 1 FROM users WHERE username=? LIMIT 1");
  $chkU->bind_param("s", $username);
  $chkU->execute(); $chkU->store_result();
  if ($chkU->num_rows > 0) { echo "<script>alert('❌ ชื่อผู้ใช้นี้ถูกใช้แล้ว'); history.back();</script>"; exit; }
  $chkU->close();

  // ตรวจซ้ำ email ใน suppliers (ถ้าอยากบังคับ unique)
  if ($email !== '') {
    $chkE = $conn->prepare("SELECT 1 FROM suppliers WHERE email=? LIMIT 1");
    $chkE->bind_param("s", $email);
    $chkE->execute(); $chkE->store_result();
    if ($chkE->num_rows > 0) { echo "<script>alert('❌ อีเมลนี้ถูกใช้แล้ว'); history.back();</script>"; exit; }
    $chkE->close();
  }

  $conn->begin_transaction();

  // hash รหัสผ่านครั้งเดียว ใช้ทั้งสองตาราง (ตาม schema suppliers ของคุณมี password_hash)
  $hash = password_hash($password, PASSWORD_DEFAULT);

  // 1) เพิ่มผู้ใช้ใน users
  $insU = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
  $insU->bind_param("sss", $username, $hash, $role);
  $insU->execute();

  // 2) เพิ่มผู้ขายใน suppliers
  $status = 'active'; // เริ่มต้นเป็น active
  $insS = $conn->prepare("
      INSERT INTO suppliers (supplier_name, contact_info, address, phone, email, password_hash, status)
      VALUES (?, ?, ?, ?, ?, ?, ?)
  ");
  $insS->bind_param("sssssss", $supplier_name, $contact_info, $address, $phone, $email, $hash, $status);
  $insS->execute();

  $conn->commit();
  $insU->close(); $insS->close();

  echo "<script>alert('✅ สมัครสมาชิกผู้ขายเรียบร้อย'); window.location='login.php';</script>";
  exit;

} catch (Throwable $e) {
  if ($conn->errno) { $conn->rollback(); }
  // ใน production ไม่ควร echo error จริง
  echo "<script>alert('❌ สมัครสมาชิกไม่สำเร็จ กรุณาลองใหม่'); window.location='register.php';</script>";
  exit;
}
$conn->close();
?>
