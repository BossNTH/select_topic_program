<?php
// /ptj/ptj/EmpBuy/product_manage/product_save.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'procurement') {
    header("Location: /ptj/ptj/EmpBuy/login.php");
    exit();
}
require_once dirname(__DIR__, 2) . '/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $qty_on_hand = intval($_POST['qty_on_hand'] ?? 0);
    $min_stock = intval($_POST['min_stock'] ?? 0);
    $unit = trim($_POST['unit'] ?? '');
    $unit_price = floatval($_POST['unit_price'] ?? 0);

    // Validate required fields
    if ($product_name === '' || $category_id === 0 || $unit === '') {
        header('Location: product_add.php?err=' . urlencode('กรุณากรอกข้อมูลให้ครบถ้วน'));
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO products (product_name, description, qty_on_hand, min_stock, unit_price, unit, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        header('Location: product_add.php?err=' . urlencode('เตรียมคำสั่งล้มเหลว: ' . $conn->error));
        exit();
    }
    $stmt->bind_param('ssiidsi', $product_name, $description, $qty_on_hand, $min_stock, $unit_price, $unit, $category_id);
    if ($stmt->execute()) {
        header('Location: product_list.php');
        exit();
    } else {
        header('Location: product_add.php?err=' . urlencode('บันทึกข้อมูลล้มเหลว: ' . $stmt->error));
        exit();
    }
} else {
    header('Location: product_add.php');
    exit();
}
