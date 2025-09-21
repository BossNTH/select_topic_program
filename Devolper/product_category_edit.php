<?php
// product_category_edit.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once("../connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['category_name'])) {
    $id = (int)$_POST['id'];
    $category_name = trim($conn->real_escape_string($_POST['category_name']));
    
    if (!empty($category_name)) {
        $sql = "UPDATE product_categories SET category_name = '$category_name' WHERE category_id = $id";
        
        if ($conn->query($sql)) {
            $_SESSION['message'] = "อัปเดตประเภทสินค้าเรียบร้อยแล้ว";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "เกิดข้อผิดพลาดในการอัปเดต: " . $conn->error;
            $_SESSION['message_type'] = "danger";
        }
    } else {
        $_SESSION['message'] = "กรุณากรอกชื่อประเภทสินค้า";
        $_SESSION['message_type'] = "warning";
    }
    
    header("Location: product_category_add.php");
    exit();
} else {
    header("Location: product_categories_add.php");
    exit();
}
?>