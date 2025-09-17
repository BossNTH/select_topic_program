<?php
// Devolper/partials/admin_header.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php"); exit();
}
require_once __DIR__ . "/../../connect.php";
date_default_timezone_set('Asia/Bangkok'); // GMT+7

// ตั้งค่าหน้าและเมนูที่ active จากเพจที่ include ไฟล์นี้
$page_title  = $page_title  ?? 'Admin';
$active_menu = $active_menu ?? ''; // ตัวอย่างค่า: 'dashboard','employees','departments',...

// helper
function active($key, $active_menu){ return $key === $active_menu ? 'active bg-white text-primary' : 'text-white-75'; }
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background:#f6f7fb; }
    .sidebar {
      min-height: 100vh;
      background: linear-gradient(180deg,#0d6efd,#2563eb);
      padding: 18px 12px; color:#fff;
      position: sticky; top:0;
    }
    .sidebar .brand {
      font-weight:700; display:flex; align-items:center; gap:.5rem;
      margin-bottom: 18px;
    }
    .sidebar .user {
      background: rgba(255,255,255,.15); border-radius:12px; padding:10px 12px; margin-bottom:12px;
    }
    .sidebar .nav-link {
      color:#fff; border-radius:12px; padding:.6rem .8rem; display:flex; gap:.6rem; align-items:center;
    }
    .sidebar .nav-link:hover { background: rgba(255,255,255,.15); }
    .sidebar .nav-link.active { font-weight:600; }
    .content { padding: 18px; }
    .topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <aside class="col-12 col-md-3 col-lg-2 sidebar">
      <div class="brand">
        <i class="bi bi-grid-1x2-fill"></i> <span>Admin Panel</span>
      </div>
      <div class="user small">
        <div><i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']) ?></div>
        <div class="opacity-75">อัปเดตล่าสุด: <?= date('d/m/Y H:i') ?></div>
      </div>

      <nav class="nav flex-column gap-1">
        <a class="nav-link <?= active('dashboard',$active_menu) ?>" href="../Devolper/dashboard.php">
          <i class="bi bi-speedometer2"></i> แดชบอร์ด
        </a>
        <a class="nav-link <?= active('employees',$active_menu) ?>" href="../Devolper/employeeManagement.php">
          <i class="bi bi-people"></i> จัดการพนักงาน
        </a>
        <a class="nav-link <?= active('departments',$active_menu) ?>" href="../Devolper/departmentManagement.php">
          <i class="bi bi-building"></i> จัดการแผนก
        </a>
        <a class="nav-link <?= active('product_types',$active_menu) ?>" href="../Devolper/productTypeManagement.php">
          <i class="bi bi-box-seam"></i> ประเภทสินค้า
        </a>
        <a class="nav-link <?= active('payment_types',$active_menu) ?>" href="../Devolper/paymentTypeManagement.php">
          <i class="bi bi-credit-card"></i> ประเภทการจ่าย
        </a>
        <a class="nav-link <?= active('suppliers',$active_menu) ?>" href="../Devolper/supplierManagement.php">
          <i class="bi bi-person-badge"></i> ผู้ขาย/สมาชิก
        </a>
        <hr class="border-light">
        <a class="nav-link" href="../logout.php">
          <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
        </a>
      </nav>
    </aside>

    <!-- Main -->
    <main class="col-12 col-md-9 col-lg-10 content">
      <div class="topbar">
        <h4 class="mb-0"><?= htmlspecialchars($page_title) ?></h4>
        <button class="btn btn-outline-primary btn-sm d-md-none" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
          <i class="bi bi-list"></i>
        </button>
      </div>
