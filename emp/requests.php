<?php
include '../db.php';
session_start();

// التحقق من تسجيل الدخول وصلاحيات الموظف
if (!isset($_SESSION['loggedin']) || $_SESSION['account_type'] !== 'employee') {
    header("Location: ../login.php");
    exit();
}

$emp_id = $_SESSION['user_id'];

// معالجة تغيير حالة الطلب أو حذفه
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['order_id'])) {
        $order_id = intval($_POST['order_id']);
        $action = $_POST['action'];

        if ($action === 'accept') {
            $update_query = "UPDATE orders SET status = '1' WHERE id = ? AND device_id IN (SELECT id FROM devices WHERE emp_id = ?)";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ii", $order_id, $emp_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'cancel') {
            $update_query = "UPDATE orders SET status = '2' WHERE id = ? AND device_id IN (SELECT id FROM devices WHERE emp_id = ?)";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ii", $order_id, $emp_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'delete') {
            $delete_query = "DELETE FROM orders WHERE id = ? AND device_id IN (SELECT id FROM devices WHERE emp_id = ?)";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param("ii", $order_id, $emp_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// جلب الطلبات الخاصة بالأجهزة التي تخص الموظف
$orders_query = "
    SELECT orders.*, devices.device_name, devices.image_path 
    FROM orders
    JOIN devices ON orders.device_id = devices.id
    WHERE devices.emp_id = ?";
$stmt = $conn->prepare($orders_query);
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$orders = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الطلبات</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            padding: 20px;
        }

        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .link-control {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: space-around;
            margin-bottom: 30px;
        }

        .link-control a {
            text-decoration: none;
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            background-color: #007bff;
            color: #fff;
            transition: 0.3s ease;
        }

        .link-control a:hover {
            background-color: #0056b3;
        }

        table img {
            max-width: 50px;
            height: auto;
            border-radius: 5px;
        }

        .modal-header {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>

<body>
<div class="dashboard">
        <h1>لوحة التحكم</h1>
        <div class="link-control">
            <a href="profail.php"><i class="fas fa-user"></i> الملف الشخصي</a>
            <a href="password.php"><i class="fas fa-lock"></i> تعديل كلمة السر</a>
            <a href="device.php"><i class="fas fa-laptop"></i> الأجهزة</a>
            <a href="requests.php"><i class="fas fa-clipboard-list"></i> الطلبات</a>
            <a href="../index.php"><i class="fas fa-home"></i> الرئيسية</a>
        </div>
    </div>

    <div class="container mt-4">
        <h2 class="mb-4 text-center">طلبات العملاء للأجهزة الخاصة بك</h2>

        <table class="table table-striped">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>اسم الجهاز</th>
                    <th>صورة الجهاز</th>
                    <th>الكمية</th>
                    <th>السعر الإجمالي</th>
                    <th>الحالة</th>
                    <th>تاريخ الطلب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['device_name']); ?></td>
                        <td>
                            <img src="../<?php echo htmlspecialchars($order['image_path'] ?? 'default-image.png'); ?>" alt="صورة الجهاز">
                        </td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_price']); ?> ريال</td>
                        <td>
                            <?php
                            switch ($order['status']) {
                                case '0':
                                    echo '<span class="badge bg-warning">قيد الانتظار</span>';
                                    break;
                                case '1':
                                    echo '<span class="badge bg-success">تم بنجاح</span>';
                                    break;
                                case '2':
                                    echo '<span class="badge bg-danger">تم الإلغاء</span>';
                                    break;
                                default:
                                    echo '<span class="badge bg-secondary">غير معروف</span>';
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td>
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">قبول</button>
                                <button type="submit" name="action" value="cancel" class="btn btn-warning btn-sm">إلغاء</button>
                                <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
