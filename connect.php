<?php
    $conn = new mysqli('localhost','root','','purchase');

    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>