<?php
session_start();
include("../connect.php");

/* ===== รับค่าจากฟอร์ม =====
   ถ้าฟอร์มเดิมมีแค่ username/password/role ให้คงค่า default ไว้ได้
   (ปรับชื่อฟิลด์ตามฟอร์มของคุณได้เลย) */
$username      = trim($_POST['username'] ?? '');
$password_raw  = $_POST['password'] ?? '';
$role          = $_POST['role'] ?? 'employee';

$full_name     = trim($_POST['full_name'] ?? $username); // ถ้าไม่มี ให้ใช้ username แทนชั่วคราว
$phone         = trim($_POST['phone'] ?? '');
$email         = trim($_POST['email'] ?? '');
$department_id = intval($_POST['department_id'] ?? 0);   // ถ้าไม่มีส่งมา จะเป็น 0
$status        = $_POST['status'] ?? 'active';

// if ($username === '' || $password_raw === '') {
//   echo "<script>alert('❌ ข้อมูลไม่ครบถ้วน'); window.location='employee_add.php';</script>";
//   exit();
// }    

$password = password_hash($password_raw, PASSWORD_DEFAULT);

try {
  $conn->begin_transaction();

  /* 1) INSERT users */
//   $sqlUser = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
//   $stmtUser = $conn->prepare($sqlUser);
//   $stmtUser->bind_param("sss", $username, $password, $role);
//   $stmtUser->execute();
//   $userId = $conn->insert_id; // id ของ users

  /* 2) INSERT employees (ยังไม่ใส่ employee_code) 
        - ถ้าตาราง employees ของคุณไม่มีคอลัมน์ user_id ให้ตัดออกจาก SQL/params ด้านล่าง */
  $sqlEmp = "INSERT INTO employees (full_name, phone, email, department_id, status)
           VALUES (?, ?, ?, ?, ?)";
  $stmtEmp = $conn->prepare($sqlEmp);
  $stmtEmp->bind_param("sssis", $full_name, $phone, $email, $department_id, $status);
  $stmtEmp->execute();
  $empId = $conn->insert_id; // id ของ employees

  /* 3) สร้างรหัส EMP-xxx จาก employee_id แล้ว UPDATE กลับไปที่ employees */
  $empCode = sprintf("EMP-%03d", $empId);  // เปลี่ยน %03d -> %04d ถ้าต้องการ 4 หลัก
  $upd = $conn->prepare("UPDATE employees SET employee_code=? WHERE employee_id=?");
  $upd->bind_param("si", $empCode, $empId);
  $upd->execute();

  $conn->commit();
  echo "<script>alert('✅ เพิ่มพนักงานเรียบร้อย (รหัส: {$empCode})'); window.location='dashboard.php';</script>";
  exit();
} catch (Throwable $e) {
  $conn->rollback();
  $msg = addslashes($e->getMessage());
  echo "<script>alert('❌ เกิดข้อผิดพลาด: {$msg}'); window.location='employee_add.php';</script>";
  exit();
} finally {
  if (isset($stmtUser)) $stmtUser->close();
  if (isset($stmtEmp))  $stmtEmp->close();
  if (isset($upd))      $upd->close();
  $conn->close();
}
?>