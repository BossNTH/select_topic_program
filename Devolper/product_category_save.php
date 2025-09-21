<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require_once("../connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['category_name']);
    if ($name !== '') {
        $stmt = $conn->prepare("INSERT INTO product_categories (category_name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }
}
header("Location: product_category_add.php");
exit();
?>