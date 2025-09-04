<?php
// employee.php : ระบบจัดซื้อสำหรับพนักงานแผนก
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดซื้อ - พนักงานแผนก</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="employee.php">ระบบจัดซื้อ (พนักงาน)</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- เมนู -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">

                <!-- จัดการขอซื้อสินค้า -->
                <li class="nav-item">
                    <a class="nav-link" href="#">จัดการขอซื้อสินค้า</a>
                </li>

                <!-- ดูรายการสินค้าต่ำกว่าคงคลังขั้นต่ำ -->
                <li class="nav-item">
                    <a class="nav-link" href="#">ดูสินค้าต่ำกว่าคงคลังขั้นต่ำ</a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- เนื้อหาหลัก -->
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white fw-bold">
            ยินดีต้อนรับ พนักงานแผนก
        </div>
        <div class="card-body">
            <p class="text-muted">กรุณาเลือกเมนูด้านบนเพื่อทำรายการ</p>
            <ul>
                <li><strong>จัดการขอซื้อสินค้า:</strong> สำหรับสร้างคำขอซื้อใหม่ หรือแก้ไข/ติดตามคำขอที่ทำไปแล้ว</li>
                <li><strong>ดูสินค้าต่ำกว่าคงคลังขั้นต่ำ:</strong> ตรวจสอบว่าสินค้าใดใกล้หมดและควรทำการขอซื้อ</li>
            </ul>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
