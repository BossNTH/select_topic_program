<?php
session_start();
include("../connect.php");

$dept_name = $_POST['dept_name'];

$sql = "INSERT INTO departments (dept_name) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dept_name);

if ($stmt->execute()) {
    echo "<script>alert('✅ เพิ่มแผนกเรียบร้อย'); window.location='dashboard.php';</script>";
} else {
    echo "<script>alert('❌ เกิดข้อผิดพลาด'); window.location='department_add.php';</script>";
}
$stmt->close();
$conn->close();
?>
