<?php
// vendor.php : ระบบจัดซื้อสำหรับผู้ขาย
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดซื้อ - ผู้ขาย</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ปรับสี Navbar */
        .navbar-custom {
            background-color: #4a90e2; /* ฟ้าอมเทา */
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link,
        .navbar-custom .dropdown-toggle {
            color: #fff !important;
        }
        /* Hover แล้วเปลี่ยนเป็นสีชัด */
        .navbar-custom .nav-link:hover,
        .navbar-custom .dropdown-toggle:hover {
            color: #ffdd57 !important; /* เหลืองนุ่ม */
        }

        /* Dropdown */
        .dropdown-menu {
            border-radius: 10px;
        }
        .dropdown-menu .dropdown-item {
            color: #333; /* ตัวหนังสือสีเทาเข้ม */
        }
        .dropdown-menu .dropdown-item:hover {
            background-color: #e6f0ff; /* พื้นหลังฟ้าอ่อน */
            color: #003366; /* ตัวหนังสือสีกรม ไม่หาย */
        }
    </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="vendor.php">ระบบจัดซื้อ (ผู้ขาย)</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">

                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        จัดการสำหรับผู้ขาย
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="purchase_requests.php">ดูรายการขอซื้อ</a></li>
                        <li><a class="dropdown-item" href="quotation.php">จัดการเสนอราคา</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- เนื้อหา -->
<div class="container mt-4">
    <h3 class="fw-bold">ยินดีต้อนรับ ผู้ขาย</h3>
    <p class="text-muted">เลือกเมนู "จัดการสำหรับผู้ขาย" ด้านบนเพื่อดูคำขอซื้อหรือตอบกลับด้วยการเสนอราคา</p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
