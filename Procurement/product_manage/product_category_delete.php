<?php
// /ptj/ptj/EmpBuy/product_manage/product_category_delete.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
    header("Location: /ptj/ptj/EmpBuy/login.php");
    exit();
}
require_once dirname(__DIR__, 2) . '/connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: /ptj/ptj/EmpBuy/product_manage/product_category.php");
    exit();
}

// ถ้ามี FK จาก products -> product_categories การลบจะล้มเหลว (1451)
// ควรบังคับให้เปลี่ยน/ลบสินค้าในหมวดนั้นก่อน
$stmt = $conn->prepare("DELETE FROM product_categories WHERE category_id=?");
if (!$stmt) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
}

header("Location: /ptj/ptj/EmpBuy/product_manage/product_category.php?msg=deleted");
exit();
