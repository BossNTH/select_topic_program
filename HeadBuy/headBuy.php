<?php
// purchase_manager.php : ระบบจัดซื้อสำหรับหัวหน้าแผนกจัดซื้อ
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดซื้อ - หัวหน้าแผนกจัดซื้อ</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="purchase_manager.php">ระบบจัดซื้อ (หัวหน้าแผนกจัดซื้อ)</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- เมนู -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">

                <!-- ตรวจสอบรายการ -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="checkDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        ตรวจสอบรายการ
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="checkDropdown">
                        <li><a class="dropdown-item" href="pending_purchase.php">รายการจัดซื้อรออนุมัติ</a></li>
                        <li><a class="dropdown-item" href="purchase_history.php">ประวัติการจัดซื้อ</a></li>
                        <li><a class="dropdown-item" href="all_purchase.php">รายการจัดซื้อทั้งหมด</a></li>
                    </ul>
                </li>

                <!-- รายงาน -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        รายงาน
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="reportDropdown">
                        <li><a class="dropdown-item" href="report_purchase.php">รายงานการจัดซื้อ</a></li>
                        <li><a class="dropdown-item" href="report_vendor.php">รายงานผู้ขาย</a></li>
                        <li><a class="dropdown-item" href="report_budget.php">รายงานงบประมาณ</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- เนื้อหา -->
<div class="container mt-4">
    <h2>ยินดีต้อนรับ หัวหน้าแผนกจัดซื้อ</h2>
    <p>กรุณาเลือกเมนูด้านบนเพื่อทำรายการ</p>
</div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
