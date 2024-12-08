<?php
session_start();
include 'db.php'; // الاتصال بقاعدة البيانات
print_r($_SESSION);
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: index.php'); // إعادة التوجيه إذا كانت السلة فارغة
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'];
    $status = 0; // الحالة الافتراضية للطلب
    $created_at = date('Y-m-d H:i:s'); // تاريخ الطلب

    foreach ($_SESSION['cart'] as $item) {
        $device_id = $item['id'];
        $quantity = $item['quantity'];
        $total_price = $item['price'] * $quantity;
        $id=$_SESSION['user_id'];
        $insert_query = "INSERT INTO orders (customer_name, device_id, quantity, total_price, status, created_at,user_id) VALUES (?, ?, ?, ?, ?, ?,?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("siidsss", $customer_name, $device_id, $quantity, $total_price, $status, $created_at,$id);
        $stmt->execute();
    }

    // تفريغ السلة بعد إتمام الطلب
    $_SESSION['cart'] = [];
    $_SESSION['customer_name'] = $customer_name;
    $success_message = "تم إتمام الطلب بنجاح!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إتمام الشراء</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>إتمام الشراء</h1>
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
            <a href="index.php" class="btn btn-primary">العودة إلى الصفحة الرئيسية</a>
        <?php else: ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="customer_name" class="form-label">اسم العميل</label>
                    <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">إتمام الشراء</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
