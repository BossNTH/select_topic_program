<?php
include("../connect.php") ;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ระบบสั่งซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<h1 class="text-center">Procurement Menu</h1>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="procerementMenu.php">หน้าหลัก</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <!-- สินค้า -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="employeeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            จัดการสินค้า
          </a>
          <ul class="dropdown-menu" aria-labelledby="employeeDropdown">
            <li><a class="dropdown-item" href="#">เพิ่มพนักงาน</a></li>
            <li><a class="dropdown-item" href="#">แก้ไขพนักงาน</a></li>
            <li><a class="dropdown-item" href="#">ลบพนักงาน</a></li>
          </ul>
        </li>

        <!-- ใบขอซื้อ -->
        <li class="nav-item">
          <a class="nav-link" href="#" id="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            ใบขอซื้อสินค้า
          </a>
          <!-- <ul class="dropdown-menu" aria-labelledby="departmentDropdown">
            <li><a class="dropdown-item" href="#">เพิ่มแผนก</a></li>
            <li><a class="dropdown-item" href="#">แก้ไขแผนก</a></li>
            <li><a class="dropdown-item" href="#">ลบแผนก</a></li>
          </ul> -->
        </li>

        <!-- เปรียบเทียบใบเสนอราคา -->
        <li class="nav-item">
          <a class="nav-link" href="#" id="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            เปรียบเทียบใบเสนอราคา
          </a>
          <!-- <ul class="dropdown-menu" aria-labelledby="productTypeDropdown">
            <li><a class="dropdown-item" href="#">เพิ่มประเภทสินค้า</a></li>
            <li><a class="dropdown-item" href="#">แก้ไข</a></li>
            <li><a class="dropdown-item" href="#">ลบ</a></li>
          </ul> -->
        </li>

        <!-- ใบสั่งซื้อ -->
        <li class="nav-item">
          <a class="nav-link" href="#" id="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            ใบสั่งซื้อ
          </a>
          <!-- <ul class="dropdown-menu" aria-labelledby="paymentTypeDropdown">
            <li><a class="dropdown-item" href="#">เพิ่มประเภทการจ่าย</a></li>
            <li><a class="dropdown-item" href="#">แก้ไข</a></li>
            <li><a class="dropdown-item" href="#">ลบ</a></li>
          </ul> -->
        </li>

        <!-- รายงาน -->
        <li class="nav-item">
          <a class="nav-link" href="#" id="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            รายงานภาษีซื้อ
          </a>
          <!-- <ul class="dropdown-menu" aria-labelledby="sellerDropdown">
            <li><a class="dropdown-item" href="#">เพิ่มผู้ขาย</a></li>
            <li><a class="dropdown-item" href="#">แก้ไขผู้ขาย</a></li>
            <li><a class="dropdown-item" href="#">ลบผู้ขาย</a></li>
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
