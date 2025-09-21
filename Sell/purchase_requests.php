
<?php
// purchase_requests.php : แสดงรายการขอซื้อ (PR) สำหรับผู้ขาย

// ตัวอย่างการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$db   = 'purchase';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ดึงรายการขอซื้อ (PR) ที่สถานะ submitted หรือ approved
$sql = "SELECT pr.pr_no, pr.request_date, pr.need_by_date, pr.status, e.full_name AS requester
        FROM purchase_requisitions pr
        JOIN employees e ON pr.requester_id = e.employee_id
        WHERE pr.status IN ('submitted', 'approved')
        ORDER BY pr.request_date DESC";
$stmt = $pdo->query($sql);
$prs = $stmt->fetchAll();

require_once __DIR__ . "/partials/seller_header.php";
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการขอซื้อ (PR) - ผู้ขาย</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div>
    <h3 class="fw-bold mb-3">รายการขอซื้อ (Purchase Requisition)</h3>
    <?php if (count($prs) === 0): ?>
        <div class="alert alert-info">ยังไม่มีรายการขอซื้อที่รอเสนอราคา</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-primary">
                <tr>
                    <th>เลขที่ขอซื้อ</th>
                    <th>วันที่ขอซื้อ</th>
                    <th>วันที่ต้องการ</th>
                    <th>ผู้ขอซื้อ</th>
                    <th>สถานะ</th>
                    <th>ดูรายละเอียด</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prs as $pr): ?>
                <tr>
                    <td><?= htmlspecialchars($pr['pr_no']) ?></td>
                    <td><?= htmlspecialchars($pr['request_date']) ?></td>
                    <td><?= htmlspecialchars($pr['need_by_date']) ?></td>
                    <td><?= htmlspecialchars($pr['requester']) ?></td>
                    <td>
                        <?php
                        $status = [
                            'submitted' => 'รอเสนอราคา',
                            'approved' => 'อนุมัติแล้ว'
                        ];
                        echo $status[$pr['status']] ?? $pr['status'];
                        ?>
                    </td>
                    <td>
                        <a href="pr_detail.php?pr_no=<?= urlencode($pr['pr_no']) ?>" class="btn btn-sm btn-outline-primary">
                            รายละเอียด
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>
</div>

<?php require_once __DIR__ . "/partials/seller_footer.php"; ?>