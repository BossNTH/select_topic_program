<?php
// staff_purchase.php : ระบบจัดซื้อสำหรับพนักงานจัดซื้อ
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดซื้อ - พนักงานจัดซื้อ</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="staff_purchase.php">ระบบจัดซื้อ (พนักงาน)</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">

                <!-- จัดการสินค้า -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="productDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        จัดการสินค้า
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="product_list.php">รายการสินค้า</a></li>
                        <li><a class="dropdown-item" href="product_add.php">เพิ่มสินค้าใหม่</a></li>
                        <li><a class="dropdown-item" href="product_category.php">จัดการประเภทสินค้า</a></li>
                    </ul>
                </li>

                <!-- ใบขอซื้อ -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="requestDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        ใบขอซื้อ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="request_list.php">ดูใบขอซื้อ</a></li>
                        <li><a class="dropdown-item" href="announcement.php">เปิดประกาศ</a></li>
                        <li><a class="dropdown-item" href="request_history.php">ประวัติใบขอซื้อ</a></li>
                    </ul>
                </li>

                <!-- สั่งซื้อ -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="orderDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        สั่งซื้อ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="order_create.php">ออกใบสั่งซื้อ</a></li>
                        <li><a class="dropdown-item" href="order_history.php">ประวัติการสั่งซื้อ</a></li>
                    </ul>
                </li>

                <!-- รายงาน -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        รายงาน
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="report_tax.php">รายงานภาษี</a></li>
                        <li><a class="dropdown-item" href="report_purchase.php">รายงานการจัดซื้อ</a></li>
                        <li><a class="dropdown-item" href="report_vendor.php">รายงานผู้ขาย</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- เนื้อหาหน้าแรก -->
<div class="container mt-4">
    <h3 class="fw-bold">ยินดีต้อนรับ พนักงานจัดซื้อ</h3>
    <p class="text-muted">กรุณาเลือกเมนูด้านบนเพื่อทำรายการ</p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
