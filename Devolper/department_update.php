<?php
// Devolper/department_update.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit();
}
require_once("../connect.php");

$deptId   = (int)($_POST['department_id'] ?? 0);
$deptName = trim($_POST['dept_name'] ?? '');
$headId   = isset($_POST['head_employee_id']) && $_POST['head_employee_id'] !== '' ? (int)$_POST['head_employee_id'] : null;

if ($deptId <= 0 || $deptName === '') {
    echo "<script>alert('ข้อมูลไม่ถูกต้อง'); window.location='departmentManagement.php';</script>";
    exit();
}

// ตรวจว่ามีคอลัมน์ head_employee_id ไหม
$hasHeadCol = false;
if ($col = $conn->query("SHOW COLUMNS FROM departments LIKE 'head_employee_id'")) {
  $hasHeadCol = $col->num_rows > 0;
}

try {
    $conn->begin_transaction();

    // ถ้าจะตั้งหัวหน้า ตรวจว่า headId อยู่ในแผนกนี้จริง
    if ($hasHeadCol && $headId) {
        $chk = $conn->prepare("SELECT 1 FROM employees WHERE employee_id=? AND department_id=? LIMIT 1");
        $chk->bind_param("ii", $headId, $deptId);
        $chk->execute(); $chk->store_result();
        if ($chk->num_rows === 0) {
            $chk->close();
            throw new Exception("พนักงานที่เลือกไม่ได้อยู่ในแผนกนี้");
        }
        $chk->close();
    }

    if ($hasHeadCol) {
        $sql = "UPDATE departments SET department_name = ?, head_employee_id = ".($headId ? "?" : "NULL")." WHERE department_id = ?";
        $stmt = $conn->prepare($sql);
        if ($headId) {
          $stmt->bind_param("sii", $deptName, $headId, $deptId);
        } else {
          $stmt->bind_param("si",  $deptName, $deptId);
        }
    } else {
        // ถ้าไม่มีคอลัมน์หัวหน้า ก็อัปเดตแค่ชื่อ
        $sql = "UPDATE departments SET department_name = ? WHERE department_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $deptName, $deptId);
    }

    $stmt->execute();
    $stmt->close();

    $conn->commit();
    echo "<script>alert('บันทึกการเปลี่ยนแปลงเรียบร้อย'); window.location='departmentManagement.php';</script>";
    exit();

} catch (Throwable $e) {
    $conn->rollback();
    $msg = addslashes($e->getMessage());
    echo "<script>alert('ไม่สามารถบันทึกได้: {$msg}'); history.back();</script>";
    exit();
}
