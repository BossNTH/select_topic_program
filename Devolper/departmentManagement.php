<?php
// Devolper/departmentManagement.php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include("../connect.php");

/* ดึงรายการแผนก + จำนวนพนักงานต่อแผนก */
$sql = "SELECT d.department_id, d.department_name,
               COUNT(e.employee_id) AS employee_count
        FROM departments d
        LEFT JOIN employees e ON e.department_id = d.department_id
        GROUP BY d.department_id, d.department_name
        ORDER BY d.department_name ASC";
$res = $conn->query($sql);
$departments = [];
while ($row = $res->fetch_assoc()) $departments[] = $row;
$totalDepartments = count($departments);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>จัดการแผนก</title>
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
        letter-spacing: .25px;
    }
    </style>
</head>

<body>

    <div class="container py-4">

        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2 class="page-title mb-0">จัดการแผนก</h2>
                <small class="text-muted">ทั้งหมด <?php echo number_format($totalDepartments); ?> แผนก</small>
            </div>
            <div class="toolbar">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDeptModal">
                <i class="bi bi-building-add me-1"></i> เพิ่มแผนก
                </button>


                <a href="dashboard.php" class="btn btn-outline-secondary">
                    <i class="bi bi-speedometer2 me-1"></i> กลับแดชบอร์ด
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-8 search-bar">
                        <label class="form-label mb-1">ค้นหา</label>
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="พิมพ์ชื่อแผนก หรือรหัสแผนก...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-1">ตัวเลือก</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="hasEmpOnly">
                            <label class="form-check-label" for="hasEmpOnly">
                                แสดงเฉพาะแผนกที่มีพนักงาน
                            </label>
                        </div>
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
                            <th class="sticky">รหัสแผนก</th>
                            <th class="sticky">ชื่อแผนก</th>
                            <th class="sticky text-center">จำนวนพนักงาน</th>
                            <th class="sticky text-center">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="deptTableBody">
                        <?php if ($totalDepartments): ?>
                        <?php foreach ($departments as $row): ?>
                        <tr data-empcount="<?= (int)$row['employee_count'] ?>">
                            <td class="code-pill text-primary"><?= htmlspecialchars($row['department_id']) ?></td>
                            <td><?= htmlspecialchars($row['department_name']) ?></td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">
                                    <?= number_format((int)$row['employee_count']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="department_edit.php?id=<?= (int)$row['department_id'] ?>"
                                    class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil-square"></i> แก้ไข
                                </a>
                                <a href="department_delete.php?id=<?= (int)$row['department_id'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('ต้องการลบแผนกนี้หรือไม่? การลบอาจกระทบพนักงานที่สังกัดอยู่');">
                                    <i class="bi bi-trash"></i> ลบ
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">ไม่พบข้อมูลแผนก</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tbody id="noResultRow" style="display:none;">
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">ไม่พบรายการที่ตรงกับการค้นหา/ตัวเลือก
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap Modal: เพิ่มแผนก -->
    <div class="modal fade" id="addDeptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <form id="addDeptForm" action="department_save.php" method="POST" novalidate>
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-building-add me-2"></i>เพิ่มแผนก</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">ชื่อแผนก <span class="text-danger">*</span></label>
                        <input type="text" name="dept_name" class="form-control"
                            placeholder="เช่น จัดซื้อ, การตลาด, คลังสินค้า" maxlength="100" required autofocus>
                        <div class="form-text">กำหนดชื่อไม่เกิน 100 ตัวอักษร</div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary" id="addDeptSubmitBtn">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="addDeptSpinner" role="status"
                                aria-hidden="true"></span>
                            <span class="btn-text">บันทึก</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
    (() => {
        // Helper
        const $ = (sel, root = document) => root.querySelector(sel);
        const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
        const debounce = (fn, delay = 150) => {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), delay);
            };
        };
        const normalize = (s) => (s || '').toString().toLowerCase().trim();

        // ====== ตัวกรองตารางแผนก ======
        const searchInput = $('#searchInput');
        const hasEmpOnly = $('#hasEmpOnly');
        const tbody = $('#deptTableBody');
        const noResultRow = $('#noResultRow');

        function filterRows() {
            if (!tbody) return;
            const q = normalize(searchInput ? searchInput.value : '');
            const onlyHasEmp = hasEmpOnly ? !!hasEmpOnly.checked : false;

            let visible = 0;
            [...tbody.rows].forEach(tr => {
                const text = normalize(tr.innerText);
                const count = parseInt(tr.dataset.empcount || '0', 10);

                const okSearch = q === '' || text.includes(q);
                const okCount = !onlyHasEmp || count > 0;

                const show = okSearch && okCount;
                tr.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            if (noResultRow) {
                noResultRow.style.display = (visible === 0) ? '' : 'none';
            }
        }

        if (searchInput) searchInput.addEventListener('input', debounce(filterRows, 150));
        if (hasEmpOnly) hasEmpOnly.addEventListener('change', filterRows);

        // เรียกครั้งแรกหลัง DOM พร้อม
        filterRows();

        // ====== ส่งฟอร์มโมดัลเพิ่มแผนก (ถ้ามีโมดัลในหน้า) ======
        const addDeptForm = $('#addDeptForm');
        const addDeptSpinner = $('#addDeptSpinner');
        const addDeptSubmit = $('#addDeptSubmitBtn');
        const addDeptModalEl = $('#addDeptModal');

        if (addDeptForm) {
            addDeptForm.addEventListener('submit', async (e) => {
                // client-side validate
                if (!addDeptForm.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    addDeptForm.classList.add('was-validated');
                    return;
                }
                e.preventDefault();

                // lock UI
                if (addDeptSpinner) addDeptSpinner.classList.remove('d-none');
                if (addDeptSubmit) addDeptSubmit.disabled = true;

                try {
                    const resp = await fetch(addDeptForm.action, {
                        method: 'POST',
                        body: new FormData(addDeptForm)
                    });

                    if (resp.ok) {
                        // ปิดโมดัล (Bootstrap 5)
                        if (addDeptModalEl && window.bootstrap) {
                            const modal = bootstrap.Modal.getInstance(addDeptModalEl) || new bootstrap
                                .Modal(addDeptModalEl);
                            modal.hide();
                        }
                        // รีเฟรชรายการ
                        location.reload();
                    } else {
                        alert('ไม่สามารถบันทึกได้ กรุณาลองใหม่');
                    }
                } catch (err) {
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                } finally {
                    if (addDeptSpinner) addDeptSpinner.classList.add('d-none');
                    if (addDeptSubmit) addDeptSubmit.disabled = false;
                }
            });
        }
    })();
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>