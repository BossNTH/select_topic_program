<?php
include("../connect.php") ;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emp-Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- <link rel="stylesheet" href="styleEmp.css"> -->
</head>

<body>
<h1 class="text-center">Seller Menu</h1>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="sellerMenu.php">หน้าหลัก</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <!-- คำขอซื้อสินค้า -->
        <li class="nav-item">
          <a class="nav-link" href="#" id="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            รายการคำขอซื้อสินค้า
          </a>
          <!-- <ul class="dropdown-menu" aria-labelledby="employeeDropdown">
            <li><a class="dropdown-item" href="#">เพิ่มคำขอซื้อสินค้า</a></li>
            <li><a class="dropdown-item" href="#">แก้ไขคำขอซื้อสินค้า</a></li>
            <li><a class="dropdown-item" href="#">ลบคำขอซื้อสินค้า</a></li>
          </ul> -->
        </li>

        <!-- จัดการเสนอราคา -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            จัดการเสนอราคา
          </a>
          <ul class="dropdown-menu" aria-labelledby="departmentDropdown">
            <li><a class="dropdown-item" href="#">เพิ่มการเสนอราคา</a></li>
            <li><a class="dropdown-item" href="#">แก้ไขการเสนอราคา</a></li>
            <li><a class="dropdown-item" href="#">ลบการเสนอราคา</a></li>
          </ul>
        </li>

        <!-- ใบสั่งซื้อ -->
        <li class="nav-item">
          <a class="nav-link" href="#" id="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            รายการใบสั่งซื้อสินค้า
          </a>
          <!-- <ul class="dropdown-menu" aria-labelledby="employeeDropdown">
            <li><a class="dropdown-item" href="#">เพิ่มคำขอซื้อสินค้า</a></li>
            <li><a class="dropdown-item" href="#">แก้ไขคำขอซื้อสินค้า</a></li>
            <li><a class="dropdown-item" href="#">ลบคำขอซื้อสินค้า</a></li>
          </ul> -->
        </li>
      </ul>

      <!-- ออกจากระบบ -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" href="login.php">
            <i class="fa fa-sign-out"></i> ออกจากระบบ
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
</body>

</html>