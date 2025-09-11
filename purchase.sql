-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2025 at 09:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `purchase`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(10) UNSIGNED NOT NULL,
  `department_name` varchar(100) NOT NULL COMMENT 'ชื่อแผนก',
  `head_employee_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'รหัสพนักงานที่เป็นหัวหน้า (FK ไป employees ภายหลัง)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `head_employee_id`) VALUES
(1, 'ทั่วไป', NULL),
(2, 'บัญชี', NULL),
(3, 'คลังสินค้า', NULL),
(4, 'การตลาด', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(150) NOT NULL COMMENT 'ชื่อพนักงาน',
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT 'สถานะพนักงาน',
  `department_id` int(10) UNSIGNED NOT NULL,
  `employee_code` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `full_name`, `phone`, `email`, `status`, `department_id`, `employee_code`) VALUES
(3, 'ทำไป ทำไม', '0982363025', 'thampai.m@gmail.com', 'active', 1, 'EMP-003'),
(4, 'ทำแล้ว ไม่ได้', '09986520125', 'thamleaw.m@gnail.com', 'active', 1, 'EMP-004'),
(5, 'ง่วงนอน หลับในคาบ', '0816352585', 'nhuangnon.nh@gmail.com', 'inactive', 2, 'EMP-005');

-- --------------------------------------------------------

--
-- Table structure for table `po_items`
--

CREATE TABLE `po_items` (
  `po_no` varchar(30) NOT NULL,
  `line_no` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` varchar(150) NOT NULL COMMENT 'ชื่อสินค้า',
  `description` text DEFAULT NULL COMMENT 'รายละเอียดสินค้า',
  `qty_on_hand` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'จำนวนคงเหลือ',
  `min_stock` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'สินค้าคงคลังขั้นต่ำ',
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'ราคาต่อหน่วย',
  `unit` varchar(50) NOT NULL COMMENT 'หน่วยนับ',
  `category_id` int(10) UNSIGNED NOT NULL COMMENT 'รหัสประเภทสินค้า*'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL COMMENT 'ชื่อประเภท'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pr_items`
--

CREATE TABLE `pr_items` (
  `pr_no` varchar(30) NOT NULL,
  `line_no` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `qty_requested` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `po_no` varchar(30) NOT NULL COMMENT 'เลขที่สั่งซื้อ',
  `order_date` date NOT NULL COMMENT 'วันที่สั่งซื้อ',
  `created_by_id` int(10) UNSIGNED NOT NULL COMMENT 'รหัสพนักงานผู้ออกใบสั่งซื้อ',
  `supplier_id` int(10) UNSIGNED NOT NULL COMMENT 'รหัสผู้ขาย*',
  `approver_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'รหัสหัวหน้าที่อนุมัติ*',
  `quote_no` varchar(30) DEFAULT NULL COMMENT 'อ้างถึงใบเสนอซื้อ (ถ้าเลือกจากใบเสนอราคา)',
  `total_amount` decimal(14,2) NOT NULL DEFAULT 0.00 COMMENT 'ยอดรวมราคา',
  `status` enum('draft','sent','approved','rejected','cancelled','received','closed') NOT NULL DEFAULT 'draft' COMMENT 'สถานะสั่งซื้อ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_quotes`
--

CREATE TABLE `purchase_quotes` (
  `quote_no` varchar(30) NOT NULL COMMENT 'เลขที่เสนอซื้อ',
  `pr_no` varchar(30) DEFAULT NULL COMMENT 'อ้างถึง PR (ถ้ามี)',
  `quote_date` date NOT NULL COMMENT 'วันที่เสนอซื้อ',
  `supplier_id` int(10) UNSIGNED NOT NULL COMMENT 'รหัสผู้ขาย*',
  `total_amount` decimal(14,2) NOT NULL DEFAULT 0.00 COMMENT 'รวมยอด',
  `status` enum('draft','sent','received','accepted','rejected','expired') NOT NULL DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisitions`
--

CREATE TABLE `purchase_requisitions` (
  `pr_no` varchar(30) NOT NULL COMMENT 'เลขที่ขอซื้อ',
  `request_date` date NOT NULL COMMENT 'วันที่ขอซื้อ',
  `need_by_date` date DEFAULT NULL COMMENT 'วันที่ต้องการ',
  `requester_id` int(10) UNSIGNED NOT NULL COMMENT 'รหัสพนักงานผู้ขอซื้อ*',
  `approver_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'รหัสหัวหน้าผู้อนุมัติ*',
  `status` enum('draft','submitted','approved','rejected','cancelled','closed') NOT NULL DEFAULT 'draft' COMMENT 'สถานะขอซื้อ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quote_items`
--

CREATE TABLE `quote_items` (
  `quote_no` varchar(30) NOT NULL,
  `line_no` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `supplier_name` varchar(150) NOT NULL COMMENT 'ชื่อผู้ขาย',
  `contact_info` varchar(255) DEFAULT NULL COMMENT 'ข้อมูลติดต่ออื่น ๆ',
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL COMMENT 'รหัสผ่าน (เก็บแบบแฮช)',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT 'สถานะผู้ขาย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `contact_info`, `address`, `phone`, `email`, `password_hash`, `status`) VALUES
(1, 'สมชายร้านค้า', 'สมชาย ใจสั่น', 'มหาสาคาม', '0987654321', 'somchai.j@gmail.com', '$2y$10$DDVGRBBNzS5dm8H90AUcB.Zp06lkMs/HJ4cCAP9H3MudHv0QxVz6G', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager','employee','seller','procurement','product_manager','project_manager') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$0/YpnZvZtYnZMvLnOJfo2eCnSKisTwtgqBGh2nGUo6xrvt05je9gG', 'admin'),
(2, 'iamboss', '$2y$10$DDVGRBBNzS5dm8H90AUcB.Zp06lkMs/HJ4cCAP9H3MudHv0QxVz6G', 'seller'),
(5, 'EMP-003', '$2y$10$BBZwYzVBPK9V5yTtsjjbUecys6DGnmk4EHGvBpFSifhpRKZ29wFHa', 'employee'),
(10, 'EMP-004', '$2y$10$x2L3zH8I4ISJb.d5iA45S.zRrXNSWaT5av8lXUpN4X7P2eBuASNSK', 'employee'),
(11, 'EMP-005', '$2y$10$glTwP0rKOgoJHXcorg/Yju6j3GJvwHheweslMZ5e1vSn5Moc/JrDe', 'employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `uq_departments_name` (`department_name`),
  ADD KEY `fk_departments_head_employee` (`head_employee_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `uq_employees_email` (`email`),
  ADD UNIQUE KEY `uq_employee_code` (`employee_code`),
  ADD KEY `fk_employees_department` (`department_id`);

--
-- Indexes for table `po_items`
--
ALTER TABLE `po_items`
  ADD PRIMARY KEY (`po_no`,`line_no`),
  ADD KEY `fk_po_items_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_products_category` (`category_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `uq_product_categories_name` (`category_name`);

--
-- Indexes for table `pr_items`
--
ALTER TABLE `pr_items`
  ADD PRIMARY KEY (`pr_no`,`line_no`),
  ADD KEY `fk_pr_items_product` (`product_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`po_no`),
  ADD KEY `fk_po_supplier` (`supplier_id`),
  ADD KEY `fk_po_created_by` (`created_by_id`),
  ADD KEY `fk_po_approver` (`approver_id`),
  ADD KEY `fk_po_quote` (`quote_no`);

--
-- Indexes for table `purchase_quotes`
--
ALTER TABLE `purchase_quotes`
  ADD PRIMARY KEY (`quote_no`),
  ADD KEY `fk_quotes_supplier` (`supplier_id`),
  ADD KEY `fk_quotes_pr` (`pr_no`);

--
-- Indexes for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD PRIMARY KEY (`pr_no`),
  ADD KEY `fk_pr_requester` (`requester_id`),
  ADD KEY `fk_pr_approver` (`approver_id`);

--
-- Indexes for table `quote_items`
--
ALTER TABLE `quote_items`
  ADD PRIMARY KEY (`quote_no`,`line_no`),
  ADD KEY `fk_quote_items_product` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `uq_suppliers_email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `fk_departments_head_employee` FOREIGN KEY (`head_employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `fk_employees_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON UPDATE CASCADE;

--
-- Constraints for table `po_items`
--
ALTER TABLE `po_items`
  ADD CONSTRAINT `fk_po_items_po` FOREIGN KEY (`po_no`) REFERENCES `purchase_orders` (`po_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_po_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `pr_items`
--
ALTER TABLE `pr_items`
  ADD CONSTRAINT `fk_pr_items_pr` FOREIGN KEY (`pr_no`) REFERENCES `purchase_requisitions` (`pr_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pr_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `fk_po_approver` FOREIGN KEY (`approver_id`) REFERENCES `employees` (`employee_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_po_created_by` FOREIGN KEY (`created_by_id`) REFERENCES `employees` (`employee_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_po_quote` FOREIGN KEY (`quote_no`) REFERENCES `purchase_quotes` (`quote_no`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_po_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_quotes`
--
ALTER TABLE `purchase_quotes`
  ADD CONSTRAINT `fk_quotes_pr` FOREIGN KEY (`pr_no`) REFERENCES `purchase_requisitions` (`pr_no`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quotes_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD CONSTRAINT `fk_pr_approver` FOREIGN KEY (`approver_id`) REFERENCES `employees` (`employee_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pr_requester` FOREIGN KEY (`requester_id`) REFERENCES `employees` (`employee_id`) ON UPDATE CASCADE;

--
-- Constraints for table `quote_items`
--
ALTER TABLE `quote_items`
  ADD CONSTRAINT `fk_quote_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quote_items_quote` FOREIGN KEY (`quote_no`) REFERENCES `purchase_quotes` (`quote_no`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
