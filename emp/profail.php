<?php
include '../db.php';
session_start();

// التحقق من تسجيل الدخول وصلاحيات الموظف
if (!isset($_SESSION['loggedin']) || $_SESSION['account_type'] !== 'employee') {
    header("Location: ../login.php");
    exit();
}

$employee_id = $_SESSION['user_id'];
$success_message = "";

// تحديث بيانات الموظف
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $job_title = $_POST['job_title'];

    $update_query = "UPDATE emp SET first_name = ?, last_name = ?, email = ?, phone = ?, city = ?, job_title = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone, $city, $job_title, $employee_id);
    if ($stmt->execute()) {
        $success_message = "تم تحديث بياناتك بنجاح.";
    }
    $stmt->close();
}

// جلب بيانات الموظف
$query = "SELECT * FROM emp WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$employee = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - الموظف</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }

        .btn-primary {
            margin-top: 10px;
        }

        .container {
            margin-top: 30px;
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
<div class="container mt-4">
    <h1>لوحة التحكم</h1>

    <!-- رسالة نجاح -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- تحديث البيانات الشخصية -->
    <h3>تحديث بيانات الموظف</h3>
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="first_name" class="form-label">الاسم الأول</label>
            <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">الاسم الأخير</label>
            <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">رقم الهاتف</label>
            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($employee['phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">المدينة</label>
            <input type="text" name="city" id="city" class="form-control" value="<?php echo htmlspecialchars($employee['city']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="job_title" class="form-label">المسمى الوظيفي</label>
            <input type="text" name="job_title" id="job_title" class="form-control" value="<?php echo htmlspecialchars($employee['job_title']); ?>" required>
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary">تحديث</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
