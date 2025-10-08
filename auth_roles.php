<?php
// auth_roles.php
if (session_status() === PHP_SESSION_NONE) session_start();

function require_roles(array $allowed){
  if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed, true)) {
    header("Location: ../login.php"); exit();
  }
}

function role_flags(): array {
  $role = $_SESSION['role'] ?? '';
  return [
    'isAdmin'         => $role === 'admin',
    'isManager'       => $role === 'manager',
    'isEmployee'      => $role === 'employee',
    'isSeller'        => $role === 'seller',
    'isProcurement'   => $role === 'procurement',
    'isProductMgr'    => $role === 'product_manager',
    'isProjectMgr'    => $role === 'project_manager',
  ];
}
