
<?php
// quotation.php : จัดการใบเสนอราคาสำหรับผู้ขาย

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

// ดึงใบเสนอราคาทั้งหมด (ตัวอย่าง: ทุก supplier)
$sql = "SELECT q.quote_no, q.quote_date, q.pr_no, q.total_amount, q.status, s.supplier_name
        FROM purchase_quotes q
        JOIN suppliers s ON q.supplier_id = s.supplier_id
        ORDER BY q.quote_date DESC";
$stmt = $pdo->query($sql);
$quotes = $stmt->fetchAll();

require_once __DIR__ . "/partials/seller_header.php";
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการใบเสนอราคา - ผู้ขาย</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<div>
    <h3 class="fw-bold mb-3">ใบเสนอราคาทั้งหมด</h3>
    <?php if (count($quotes) === 0): ?>
        <div class="alert alert-info">ยังไม่มีใบเสนอราคา</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-primary">
                <tr>
                    <th>เลขที่ใบเสนอราคา</th>
                    <th>วันที่เสนอราคา</th>
                    <th>เลขที่ขอซื้อ (PR)</th>
                    <th>ผู้ขาย</th>
                    <th>ยอดรวม</th>
                    <th>สถานะ</th>
                    <th>ดูรายละเอียด</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quotes as $q): ?>
                <tr>
                    <td><?= htmlspecialchars($q['quote_no']) ?></td>
                    <td><?= htmlspecialchars($q['quote_date']) ?></td>
                    <td><?= htmlspecialchars($q['pr_no']) ?></td>
                    <td><?= htmlspecialchars($q['supplier_name']) ?></td>
                    <td><?= number_format($q['total_amount'], 2) ?></td>
                    <td>
                        <?php
                        $status = [
                            'draft' => 'ร่าง',
                            'sent' => 'ส่งแล้ว',
                            'received' => 'ลูกค้าได้รับ',
                            'accepted' => 'ลูกค้าตกลง',
                            'rejected' => 'ถูกปฏิเสธ',
                            'expired' => 'หมดอายุ'
                        ];
                        echo $status[$q['status']] ?? $q['status'];
                        ?>
                    </td>
                    <td>
                        <a href="quote_detail.php?quote_no=<?= urlencode($q['quote_no']) ?>" class="btn btn-sm btn-outline-primary">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

<?php require_once __DIR__ . "/partials/seller_footer.php"; ?>