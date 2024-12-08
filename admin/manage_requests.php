<?php
include '../db.php';
include 'amin-Header.php';

session_start();

// جلب الطلبات من قاعدة البيانات
$purchase_requests = [];
$requests_query = "
    SELECT 
        purchase_requests.*, 
        devices.device_name 
    FROM purchase_requests 
    JOIN devices ON purchase_requests.device_id = devices.id";
$result_requests = $conn->query($requests_query);

if ($result_requests && $result_requests->num_rows > 0) {
    while ($row = $result_requests->fetch_assoc()) {
        $purchase_requests[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الإدارة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .btn-primary,
        .btn-success,
        .btn-danger {
            font-weight: bold;
            border-radius: 8px;
        }

        table {
            margin-top: 20px;
        }

        table img {
            border-radius: 5px;
        }

        .badge {
            font-size: 0.9rem;
        }

        h3 {
            margin-top: 30px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #343a40;
        }

        form input,
        form button {
            border-radius: 8px;
        }

        .form-label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center text-primary">إدارة الطلبات</h1>

        <!-- عرض الطلبات -->
        <div class="card p-4 mt-4">
            <h3>الطلبات</h3>
            <?php if (!empty($purchase_requests)) : ?>
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>اسم العميل</th>
                            <th>اسم الجهاز</th>
                            <th>الكمية</th>
                            <th>الإجمالي</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($purchase_requests as $request) : ?>
                            <tr>
                                <td><?php echo $request['id']; ?></td>
                                <td><?php echo htmlspecialchars($request['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($request['device_name']); ?></td>
                                <td><?php echo htmlspecialchars($request['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($request['total_price']); ?></td>
                                <td>
                                    <?php if ($request['status'] === 'pending') : ?>
                                        <span class="badge bg-warning">معلقة</span>
                                    <?php elseif ($request['status'] === 'approved') : ?>
                                        <span class="badge bg-success">مقبولة</span>
                                    <?php elseif ($request['status'] === 'rejected') : ?>
                                        <span class="badge bg-danger">مرفوضة</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p class="text-center text-danger">لا توجد طلبات لعرضها.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>