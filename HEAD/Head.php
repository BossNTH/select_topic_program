<?php
// ตัวอย่างระบบจัดซื้อ: เมนูสำหรับหัวหน้าแผนก
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดซื้อ - หัวหน้าแผนก</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">ระบบจัดซื้อสำหรับหัวหน้าแผนก</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- เมนู -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">

                <!-- อนุมัติคำขอ -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="approveDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        อนุมัติคำขอ
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="approveDropdown">
                        <li><a class="dropdown-item" href="pending.php">คำขอรออนุมัติ</a></li>
                        <li><a class="dropdown-item" href="history.php">ประวัติการอนุมัติ</a></li>
                        <li><a class="dropdown-item" href="rejected.php">คำขอที่ไม่อนุมัติ</a></li>
                    </ul>
                </li>

                <!-- รายงาน -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        รายงาน
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="reportDropdown">
                        <li><a class="dropdown-item" href="report_approve.php">รายงานการอนุมัติ</a></li>
                        <li><a class="dropdown-item" href="report_expense.php">รายงานการใช้จ่าย</a></li>
                        <li><a class="dropdown-item" href="report_department.php">รายงานตามแผนก</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2>ยินดีต้อนรับ หัวหน้าแผนก</h2>
    <p>กรุณาเลือกเมนูด้านบนเพื่อทำรายการ</p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
