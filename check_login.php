<?php
session_start();
include("connect.php");

// รับค่าและกันช่องว่างเผื่อไว้
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

// ดึงผู้ใช้ด้วย prepared statement
$sql  = "SELECT id, username, password, role FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('❌ ไม่พบผู้ใช้งานนี้'); window.location='login.php';</script>";
    exit();
}

$row = $result->fetch_assoc();
$stored = $row['password'];

// ตรวจว่าค่าใน DB เป็น hash หรือไม่ (bcrypt/argon)
$is_hash = preg_match('/^\$2y\$/', $stored) || preg_match('/^argon2/i', $stored);

// เทียบรหัสผ่าน (ถ้าเป็น hash ใช้ password_verify, ถ้ายังเป็น plain ก็เทียบตรง ๆ)
$ok = $is_hash ? password_verify($password, $stored) : hash_equals($stored, $password);

if (!$ok) {
    echo "<script>alert('❌ รหัสผ่านไม่ถูกต้อง'); window.location='login.php';</script>";
    exit();
}

// ถ้าล็อกอินผ่านและยังเป็น plain text ให้ "อัปเกรด" เป็น hash ทันที
if (!$is_hash) {
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $upd = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $upd->bind_param("si", $newHash, $row['id']);
    $upd->execute();
    $upd->close();
}

$_SESSION['username'] = $row['username'];
$_SESSION['role']     = $row['role'];

// ส่งไปหน้าเมนูตาม role
switch ($row['role']) {
    case 'admin':            header("Location: Devolper/dashboard.php"); break;
    case 'manager':          header("Location: Devolper/managerMenu.php"); break;
    case 'employee':         header("Location: Devolper/empMenu.php"); break;
    case 'seller':           header("Location: Sell/Sell.php"); break;
    case 'procurement':      header("Location: Devolper/procurementMenu.php"); break;
    case 'product_manager':  header("Location: Devolper/product_manager.php"); break;
    case 'project_manager':  header("Location: Devolper/proManagerMenu.php"); break;
    default:
        echo "<script>alert('ไม่พบสิทธิ์การใช้งาน (role) ที่รองรับ'); window.location='login.php';</script>";
}
exit();
