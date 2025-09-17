<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include("../connect.php");

/* ดึงรายการพนักงาน (join แผนก ถ้ามี) */
$sql = "SELECT e.employee_id, e.employee_code, e.full_name, e.phone, e.email, e.status,
               e.department_id, d.department_name
        FROM employees e
        LEFT JOIN departments d ON e.department_id = d.department_id
        ORDER BY e.employee_id DESC";
$res = $conn->query($sql);
$employees = [];
while ($row = $res->fetch_assoc()) $employees[] = $row;
$totalEmployees = count($employees);

/* สำหรับตัวกรองแผนก */
$departments = [];
if ($dres = $conn->query("SELECT department_id, department_name FROM departments ORDER BY department_name")) {
  while ($d = $dres->fetch_assoc()) $departments[] = $d;
}

require __DIR__ . '/partials/admin_header.php';

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>จัดการพนักงาน</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
    body {
        background: #f6f7fb;
    }

    .page-title {
        font-weight: 700;
    }

    .card {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, .06);
    }

    .table thead th {
        background: #0d6efd;
        color: #fff;
        vertical-align: middle;
    }

    .table thead th.sticky {
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .badge-status {
        font-size: .85rem;
    }

    .search-bar .form-control {
        border-radius: 10px;
    }

    .toolbar .btn {
        border-radius: 10px;
    }

    .table-hover tbody tr:hover {
        background: #f0f6ff;
    }

    .code-pill {
        font-weight: 600;
        letter-spacing: .5px;
    }
    </style>
</head>

<body>

    <div class="container py-4">

        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2 class="page-title mb-0">จัดการพนักงาน</h2>
                <small class="text-muted">ทั้งหมด <?php echo number_format($totalEmployees); ?> คน</small>
            </div>
            <div class="toolbar">
                <a href="employee_add.php" class="btn btn-success">
                    <i class="bi bi-person-plus me-1"></i> เพิ่มพนักงาน
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-6 search-bar">
                        <label class="form-label mb-1">ค้นหา</label>
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="พิมพ์ชื่อ, รหัสพนักงาน, อีเมล หรือเบอร์โทร..." />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1">สถานะ</label>
                        <select id="statusFilter" class="form-select">
                            <option value="">ทั้งหมด</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1">แผนก</label>
                        <select id="deptFilter" class="form-select">
                            <option value="">ทั้งหมด</option>
                            <?php foreach ($departments as $d): ?>
                            <option value="<?= (int)$d['department_id'] ?>">
                                <?= htmlspecialchars($d['department_name'] ?: ('แผนก #' . $d['department_id'])) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="sticky">รหัส</th>
                            <th class="sticky">ชื่อ-สกุล</th>
                            <th class="sticky">เบอร์โทร</th>
                            <th class="sticky">อีเมล</th>
                            <th class="sticky">แผนก</th>
                            <th class="sticky">สถานะ</th>
                            <th class="sticky text-center">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="empTableBody">
                        <?php if ($totalEmployees): ?>
                        <?php foreach ($employees as $row): ?>
                        <?php
                $deptName = $row['department_name'] ?: ('— (ID '.$row['department_id'].')');
                $status = strtolower($row['status'] ?? '');
                $badgeClass = ($status === 'active') ? 'bg-success' : 'bg-secondary';
              ?>
                        <tr data-status="<?= htmlspecialchars($status) ?>"
                            data-dept="<?= (int)$row['department_id'] ?>">
                            <td class="code-pill text-primary"><?= htmlspecialchars($row['employee_code']) ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($deptName) ?></td>
                            <td>
                                <span class="badge badge-status <?= $badgeClass ?>">
                                    <?= $status ? ucfirst($status) : '—' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="employee_edit.php?id=<?= (int)$row['employee_id'] ?>"
                                    class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil-square"></i> แก้ไข
                                </a>
                                <a href="employee_delete.php?id=<?= (int)$row['employee_id'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบพนักงานคนนี้?');">
                                    <i class="bi bi-trash"></i> ลบ
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">ไม่พบข้อมูลพนักงาน</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tbody id="noResultRow" style="display:none;">
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">ไม่พบรายการที่ตรงกับการค้นหา/ตัวกรอง
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const deptFilter = document.getElementById('deptFilter');
    const tbody = document.getElementById('empTableBody');
    const noResultRow = document.getElementById('noResultRow');

    function normalize(str) {
        return (str || '').toString().toLowerCase().trim();
    }

    function filterRows() {
        const q = normalize(searchInput.value);
        const st = normalize(statusFilter.value);
        const dept = deptFilter.value;

        let visible = 0;
        [...tbody.rows].forEach(tr => {
            const text = normalize(tr.innerText);
            const rowStatus = normalize(tr.dataset.status);
            const rowDept = tr.dataset.dept || '';

            const okSearch = q === '' || text.includes(q);
            const okStatus = st === '' || rowStatus === st;
            const okDept = dept === '' || rowDept === dept;

            const show = okSearch && okStatus && okDept;
            tr.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        noResultRow.style.display = (visible === 0) ? '' : 'none';
    }

    searchInput.addEventListener('input', filterRows);
    statusFilter.addEventListener('change', filterRows);
    deptFilter.addEventListener('change', filterRows);
    </script>

</body>

</html>
<?php require __DIR__ . '/partials/admin_footer.php'; ?>