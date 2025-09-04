<?php
session_start();
include("connect.php");

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['username'] = $username;
    header("Location: Devolper/adMenu.php");
} else {
    echo "<script>alert('❌ Username หรือ Password ไม่ถูกต้อง'); window.location='Devolper/login.php';</script>";
}
?>