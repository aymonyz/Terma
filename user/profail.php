<?php
include '../db.php';
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['loggedin']) || $_SESSION['account_type'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = "";
$error_message = "";

// جلب بيانات المستخدم الحالية
$query = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// تحديث بيانات المستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $job_title = $_POST['job_title'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];

    // إذا لم يكن هناك أخطاء
    if (empty($error_message)) {
        // تحديث البيانات الأساسية
        $update_query = "UPDATE user SET first_name = ?, last_name = ?, email = ?, phone = ?, city = ?, job_title = ?, birth_date = ?, gender = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssssssi", $first_name, $last_name, $email, $phone, $city, $job_title, $birth_date, $gender, $user_id);

        if ($stmt->execute()) {
            $success_message = "تم تحديث بياناتك بنجاح.";
        } else {
            $error_message = "حدث خطأ أثناء تحديث البيانات.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل البيانات الشخصية</title>
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
    <a href="rest_password.php">تغيير كلمة السر</a>
    <a href="requests_job.php">الطلبات</a>
    <a href="../index.php">الرئيسة</a>
</div>
<div class="container">
    <h2 class="mb-4">تعديل البيانات الشخصية</h2>

    <!-- رسالة نجاح أو خطأ -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="first_name" class="form-label">الاسم الأول</label>
            <input type="text" name="first_name" id="first_name" class="form-control"
                   value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">الاسم الأخير</label>
            <input type="text" name="last_name" id="last_name" class="form-control"
                   value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">رقم الهاتف</label>
            <input type="text" name="phone" id="phone" class="form-control"
                   value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">المدينة</label>
            <input type="text" name="city" id="city" class="form-control"
                   value="<?php echo htmlspecialchars($user['city']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="job_title" class="form-label">المسمى الوظيفي</label>
            <input type="text" name="job_title" id="job_title" class="form-control"
                   value="<?php echo htmlspecialchars($user['job_title']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="birth_date" class="form-label">تاريخ الميلاد</label>
            <input type="date" name="birth_date" id="birth_date" class="form-control"
                   value="<?php echo htmlspecialchars($user['birth_date']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">الجنس</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="male" value="Male"
                    <?php echo ($user['gender'] === 'Male') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="male">ذكر</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="female" value="Female"
                    <?php echo ($user['gender'] === 'Female') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="female">أنثى</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
