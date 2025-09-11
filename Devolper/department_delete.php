<?php
// Devolper/department_delete.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require_once("../connect.php");

// ให้ mysqli โยน exception เพื่อใช้ try/catch ได้สะดวก
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn->set_charset('utf8mb4');

$deptId = (int)($_GET['id'] ?? 0);
if ($deptId <= 0) {
    echo "<script>alert('❌ พารามิเตอร์ไม่ถูกต้อง'); window.location='departmentManagement.php';</script>";
    exit();
}

try {
    // 1) ตรวจว่าแผนกนี้มีอยู่จริง
    $qDept = $conn->prepare("SELECT department_id, department_name FROM departments WHERE department_id = ? LIMIT 1");
    $qDept->bind_param("i", $deptId);
    $qDept->execute();
    $deptRes = $qDept->get_result();
    if ($deptRes->num_rows === 0) {
        echo "<script>alert('❌ ไม่พบแผนกนี้'); window.location='departmentManagement.php';</script>";
        exit();
    }
    $dept = $deptRes->fetch_assoc();
    $qDept->close();

    // 2) เช็คว่ามีพนักงานอยู่ในแผนกนี้หรือไม่
    $qCount = $conn->prepare("SELECT COUNT(*) FROM employees WHERE department_id = ?");
    $qCount->bind_param("i", $deptId);
    $qCount->execute();
    $qCount->bind_result($empCount);
    $qCount->fetch();
    $qCount->close();

    if ((int)$empCount > 0) {
        // บล็อกการลบทันทีเพื่อความปลอดภัยของข้อมูล
        $name = addslashes($dept['department_name']);
        echo "<script>
            alert('⚠️ ไม่สามารถลบแผนก \\\"{$name}\\\" ได้ เพราะยังมีพนักงานจำนวน {$empCount} คนอยู่ในแผนกนี้\\n\\nกรุณาย้ายพนักงานไปแผนกอื่นก่อน แล้วจึงลองลบอีกครั้ง');
            window.location='departmentManagement.php';
        </script>";
        exit();
    }

    // 3) ไม่มีพนักงานแล้ว -> ลบได้
    $conn->begin_transaction();

    $del = $conn->prepare("DELETE FROM departments WHERE department_id = ?");
    $del->bind_param("i", $deptId);
    $del->execute();
    $del->close();

    $conn->commit();

    echo "<script>
        alert('✅ ลบแผนกเรียบร้อย');
        window.location='departmentManagement.php';
    </script>";
    exit();

} catch (Throwable $e) {
    if ($conn->errno) { $conn->rollback(); }
    // หมายเหตุ: ใน production ไม่ควรแสดงข้อความ error จริงออกไป
    $msg = addslashes($e->getMessage());
    echo "<script>
        alert('❌ ไม่สามารถลบได้: {$msg}');
        window.location='departmentManagement.php';
    </script>";
    exit();
}
