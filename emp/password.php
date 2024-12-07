<?php
include '../db.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['account_type'] !== 'employee') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error_message = "كلمة المرور الجديدة وتأكيد كلمة المرور غير متطابقتين.";
    } else {
        $query = "SELECT password FROM emp WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!password_verify($current_password, $user['password'])) {
            $error_message = "كلمة المرور الحالية غير صحيحة.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE emp SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $hashed_password, $user_id);
            if ($stmt->execute()) {
                $success_message = "تم تحديث كلمة المرور بنجاح.";
            } else {
                $error_message = "حدث خطأ أثناء تحديث كلمة المرور.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تغيير كلمة المرور</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    <a href="password.php"> تعديل كلمة السر </a>
    <a href="device.php"> الاجهزة</a>
    <a href="request.php"> الطلبات</a>
    <a href="../index.php"> الرئيسة</a>
</div>
<div class="container">
    <h2 class="mb-4">تغيير كلمة المرور</h2>

    <!-- رسالة نجاح أو خطأ -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">تأكيد كلمة المرور الجديدة</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">تغيير كلمة المرور</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
