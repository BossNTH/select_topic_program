<?php
// partials/seller_header.php

$current = basename($_SERVER['PHP_SELF']); // ใช้ทำเมนู active
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>ระบบจัดซื้อ - ผู้ขาย</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root{
      --sb-bg:#0f172a; --sb-text:#e5e7eb; --sb-muted:#94a3b8; --sb-hover:#1f2a44; --sb-active:#4f46e5;
    }
    html,body{ height:100%; }
    body{ margin:0; background:#f7f8fc; }

    /* Layout */
    .layout{ min-height:100vh; display:flex; }

    /* Sidebar only */
    .sidebar{
      width:260px; flex:0 0 260px;
      background:var(--sb-bg); color:var(--sb-text);
      display:flex; flex-direction:column;
      position:sticky; top:0; height:100vh;
    }
    .brand{ padding:18px 16px; border-bottom:1px solid rgba(255,255,255,.06); display:flex; gap:10px; align-items:center; }
    .brand .title{ font-weight:700; font-size:1.05rem; }
    .menu{ padding:12px 10px; overflow-y:auto; }
    .menu .nav-link{
      color:var(--sb-text); border-radius:10px; padding:10px 12px; display:flex; gap:10px; align-items:center;
      font-weight:500; margin:4px 6px;
    }
    .menu .nav-link:hover{ background:var(--sb-hover); color:#fff; }
    .menu .nav-link.active{ background:rgba(79,70,229,.15); border:1px solid rgba(79,70,229,.4); color:#fff; }
    .section-label{ color:var(--sb-muted); text-transform:uppercase; font-size:.75rem; margin:8px 12px 4px; }

    .sidebar .bottom{ margin-top:auto; padding:12px; border-top:1px solid rgba(255,255,255,.06); }
    .logout-btn{ width:100%; color:#fff; border:1px solid rgba(255,255,255,.2); background:transparent; }
    .logout-btn:hover{ background:rgba(255,255,255,.08); color:#fff; }

    /* Content */
    .content{ flex:1; padding:24px; }

    /* Responsive: ถ้าจอแคบมาก sidebar จะกินเต็มความกว้างด้านบน แล้ว content อยู่ด้านล่าง */
    @media (max-width: 768px){
      .layout{ flex-direction:column; }
      .sidebar{ width:100%; height:auto; position:relative; }
      .content{ padding:16px; }
    }
  </style>
</head>
<body>
<div class="layout">

  <!-- Sidebar เท่านั้น -->
  <aside class="sidebar">
    <div class="brand">
      <i class="bi bi-bag-check-fill fs-4 text-primary"></i>
      <div class="title">ระบบจัดซื้อ (ผู้ขาย)</div>
    </div>

    <nav class="menu nav flex-column">
      <a class="nav-link <?= $current==='Sell.php'?'active':''; ?>" href="Sell.php">
        <i class="bi bi-house-door"></i> หน้าหลัก
      </a>

      <div class="section-label">เมนู</div>
      <a class="nav-link <?= $current==='purchase_requests.php'?'active':''; ?>" href="purchase_requests.php">
        <i class="bi bi-list-check"></i> ดูรายการขอซื้อ (PR)
      </a>
      <a class="nav-link <?= $current==='quotation.php'?'active':''; ?>" href="quotation.php">
        <i class="bi bi-receipt-cutoff"></i> จัดการใบเสนอราคา
      </a>
    </nav>

    <div class="bottom">
      <a href="../logout.php" class="btn logout-btn">
        <i class="bi bi-box-arrow-right me-1"></i> ออกจากระบบ
      </a>
    </div>
  </aside>

  <!-- เนื้อหาเพจจะเริ่มตรงนี้ -->
  <main class="content">
