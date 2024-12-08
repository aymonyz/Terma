<?php
// بدء الجلسة
session_start();
include '../db.php';

// تحقق من تسجيل الدخول
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit();
}

// جلب user_id من الجلسة
$user_id = $_SESSION['user_id'];

// معالجة طلب الإلغاء
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];
    
    // تحقق من أن الطلب يخص المستخدم
    $check_query = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // تحديث حالة الطلب إلى "ملغى"
        $update_query = "UPDATE orders SET status = '2' WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("i", $order_id);
        $update_stmt->execute();
        $update_stmt->close();
        $success_message = "تم إلغاء الطلب بنجاح.";
    } else {
        $error_message = "الطلب غير موجود أو لا يخصك.";
    }

    $stmt->close();
}

// جلب الطلبات الخاصة بالمستخدم
$query = "SELECT orders.*, devices.device_name FROM orders 
          JOIN devices ON orders.device_id = devices.id 
          WHERE orders.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلباتي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }
        .order-status {
            font-weight: bold;
        }
        .status-pending {
            color: orange;
        }
        .status-completed {
            color: green;
        }
        .status-canceled {
            color: red;
        }
        
        .link-control {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .link-control a {
            text-decoration: none;
            color: white;
            background: #0d6efd;
            padding: 8px 10px;
            border-radius: 18px;
        }
    </style>
</head>
<body>
<div class="link-control">
    <a href="profail.php">الملف الشخصي</a>
    <a href="rest_password.php">تغيير كلمة السر</a>
    <a href="orders.php">الطلبات</a>
    <a href="../index.php">الرئيسة</a>
</div>
<div class="container mt-5">
    <h1 class="text-center">طلباتي</h1>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if (!empty($orders)): ?>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الجهاز</th>
                    <th>الكمية</th>
                    <th>السعر الإجمالي</th>
                    <th>الحالة</th>
                    <th>تاريخ الطلب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['device_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_price']); ?> ريال</td>
                        <td class="order-status <?php echo 'status-' . strtolower($order['status']); ?>">
                            <?php 
                                if ($order['status'] == '0') echo 'قيد الانتظار';
                                elseif ($order['status'] == '1') echo 'تم بنجاح';
                                elseif ($order['status'] == '2') echo 'تم الإلغاء';
                                else echo 'غير معروف';
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td>
                            <?php if ($order['status'] == '0'): ?>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <button type="submit" name="cancel_order" class="btn btn-danger btn-sm">إلغاء</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">لا يمكن الإلغاء</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">لا توجد طلبات حتى الآن.</p>
    <?php endif; ?>
</div>
</body>
</html>
