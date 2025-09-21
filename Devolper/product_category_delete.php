<?php
// product_category_delete.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once("../connect.php");

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // ตรวจสอบว่าประเภทสินค้ามีสินค้าใช้งานอยู่หรือไม่
    $check_sql = "SELECT product_id FROM products WHERE category_id = $id";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        $_SESSION['message'] = "ไม่สามารถลบได้ เนื่องจากมีสินค้าในประเภทนี้อยู่";
        $_SESSION['message_type'] = "danger";
    } else {
        $sql = "DELETE FROM product_categories WHERE category_id = $id";
        
        if ($conn->query($sql)) {
            $_SESSION['message'] = "ลบประเภทสินค้าเรียบร้อยแล้ว";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "เกิดข้อผิดพลาดในการลบ: " . $conn->error;
            $_SESSION['message_type'] = "danger";
        }
    }
    
    header("Location: product_category_add.php");
    exit();
} else {
    header("Location: product_category_add.php");
    exit();
}
?>