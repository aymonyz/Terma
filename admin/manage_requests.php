<?php
include '../db.php';
include 'amin-Header.php';

session_start();

// تحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// عدد الطلبات لكل صفحة
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// البحث
$search = isset($_GET['search']) ? $_GET['search'] : '';

// جلب الطلبات
$requests_query = "
    SELECT 
        orders.*, 
        devices.device_name 
    FROM orders 
    JOIN devices ON orders.device_id = devices.id 
    WHERE customer_name LIKE ? 
    LIMIT $start, $limit";

$stmt = $conn->prepare($requests_query);
$search_param = "%$search%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result_requests = $stmt->get_result();

$purchase_requests = [];
while ($row = $result_requests->fetch_assoc()) {
    $purchase_requests[] = $row;
}

// إجمالي الطلبات للحصول على عدد الصفحات
$total_query = "SELECT COUNT(*) as total FROM orders WHERE customer_name LIKE ?";
$stmt_total = $conn->prepare($total_query);
$stmt_total->bind_param("s", $search_param);
$stmt_total->execute();
$total_result = $stmt_total->get_result();
$total = $total_result->fetch_assoc()['total'];
$pages = ceil($total / $limit);

// تحديث الحالة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $update_query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($update_query);

    if (!$stmt_update) {
        die("خطأ في الاستعلام: " . $conn->error);
    }

    $stmt_update->bind_param("si", $status, $id);

    if ($stmt_update->execute()) {
        header("Location: manage_requests.php");
        exit();
    } else {
        die("فشل في تحديث الحالة: " . $stmt_update->error);
    }
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> الشراء إدارة الطلبات</title>
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

        .badge {
            font-size: 0.9rem;
        }

        .pagination {
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center text-primary">إدارة طلبات الشراء</h1>

        <!-- بحث -->
        <form method="GET" class="d-flex mb-4">
            <input type="text" name="search" class="form-control me-2" placeholder="ابحث عن اسم العميل" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">بحث</button>
        </form>

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
                            <th>تحديث الحالة</th>
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
                                    <span class="badge 
                                        <?php echo $request['status'] === 'pending' ? 'bg-warning' : ($request['status'] === 'approved' ? 'bg-success' : 'bg-danger'); ?>">
                                        <?php echo htmlspecialchars($request['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="id" value="<?php echo $request['id']; ?>">
                                        <select name="status" onchange="this.form.submit()" class="form-select">
                                            <option value="pending" <?php echo $request['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="approved" <?php echo $request['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                            <option value="rejected" <?php echo $request['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p class="text-center text-danger">لا توجد طلبات لعرضها.</p>
            <?php endif; ?>

            <!-- Pagination -->
            <nav>
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $pages; $i++) : ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
