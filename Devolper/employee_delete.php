<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}
require_once "../connect.php";

$employee_id = intval($_GET['id'] ?? 0);
if ($employee_id <= 0) {
  echo "<script>alert('พารามิเตอร์ไม่ถูกต้อง'); window.location='employeeManagement.php';</script>";
  exit();
}

try {
  $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id=?");
  $stmt->bind_param("i", $employee_id);
  $ok = $stmt->execute();
  $stmt->close();

  if ($ok) {
    echo "<script>alert('✅ ลบพนักงานเรียบร้อย'); window.location='employeeManagement.php';</script>";
  } else {
    echo "<script>alert('❌ ลบไม่สำเร็จ'); window.location='employeeManagement.php';</script>";
  }
} catch (Throwable $e) {
  $msg = addslashes($e->getMessage());
  echo "<script>alert('❌ เกิดข้อผิดพลาด: {$msg}'); window.location='employeeManagement.php';</script>";
}
