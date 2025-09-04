<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>จัดการสินค้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="adMenu.php">หน้าหลัก</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" href="product_manage.php">จัดการสินค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="product_type.php">ประเภทสินค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="employee_manage.php">พนักงาน</a>
        </li>
        <!-- เพิ่มเมนูอื่น ๆ ตามต้องการ -->
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php"><i class="fa fa-sign-out"></i> ออกจากระบบ</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2>จัดการสินค้า</h2>
  <a href="product_add.php" class="btn btn-success mb-3"><i class="fas fa-plus"></i> เพิ่มสินค้าใหม่</a>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>รหัสสินค้า</th>
        <th>ชื่อสินค้า</th>
        <th>ประเภทสินค้า</th>
        <th>ราคา</th>
        <th>จำนวนคงเหลือ</th>
        <th>จัดการ</th>
      </tr>
    </thead>
    <tbody>
      <!-- ตัวอย่างข้อมูลสินค้า -->
      <tr>
        <td>PRD001</td>
        <td>สินค้า A</td>
        <td>ประเภท 1</td>
        <td>150 บาท</td>
        <td>50</td>
        <td>
          <a href="product_edit.php?id=PRD001" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
          <a href="product_delete.php?id=PRD001" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจที่จะลบสินค้านี้หรือไม่?')"><i class="fas fa-trash"></i></a>
        </td>
      </tr>
      <!-- เพิ่มข้อมูลจริงจากฐานข้อมูลได้ -->
    </tbody>
  </table>
</div>

</body>
</html>
