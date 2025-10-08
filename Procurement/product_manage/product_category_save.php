<?php
// /ptj/ptj/EmpBuy/product_manage/product_category_save.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
    header("Location: /ptj/ptj/EmpBuy/login.php");
    exit();
}
require_once dirname(__DIR__, 2) . '/connect.php';

$id   = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null;
$name = trim($_POST['category_name'] ?? '');

if ($name === '') {
    $msg = urlencode('กรุณากรอกชื่อประเภทสินค้า');
    header("Location: /ptj/ptj/EmpBuy/product_manage/product_category.php?err={$msg}");
    exit();
}

if ($id) {
    $stmt = $conn->prepare("UPDATE product_categories SET category_name=? WHERE category_id=?");
    if (!$stmt) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    $stmt->bind_param("si", $name, $id);
} else {
    $stmt = $conn->prepare("INSERT INTO product_categories (category_name) VALUES (?)");
    if (!$stmt) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    $stmt->bind_param("s", $name);
}
if (!$stmt->execute()) {
    die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
}

header("Location: /ptj/ptj/EmpBuy/product_manage/product_category.php?msg=saved");
exit();
