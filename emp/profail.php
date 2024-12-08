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
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <!-- خطوط وأيقونات -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- استدعاء Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* تنسيق الصفحة */
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            padding: 20px;
        }

        /* تنسيق لوحة التحكم */
        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* الروابط في لوحة التحكم */
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

        .link-control a i {
            margin-left: 10px;
        }

        h1 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .link-control {
                flex-direction: column;
                align-items: center;
            }

            .link-control a {
                width: 80%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <!-- لوحة التحكم -->
    <div class="dashboard">
        <h1>لوحة التحكم</h1>
        <div class="link-control">
            <a href="profail.php"><i class="fas fa-user"></i> الملف الشخصي</a>
            <a href="password.php"><i class="fas fa-lock"></i> تعديل كلمة السر</a>
            <a href="device.php"><i class="fas fa-laptop"></i> الأجهزة</a>
            <a href="request.php"><i class="fas fa-clipboard-list"></i> الطلبات</a>
            <a href="../index.php"><i class="fas fa-home"></i> الرئيسية</a>
        </div>
    </div>

    <!-- تحديث بيانات الموظف -->
    <div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="text-center mb-0">تحديث بيانات الموظف</h4>
        </div>
        <div class="card-body">
            <form method="POST" class="p-4">
                <div class="mb-3">
                    <label for="first_name" class="form-label">الاسم الأول</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="أدخل الاسم الأول" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">الاسم الأخير</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="أدخل الاسم الأخير" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="أدخل البريد الإلكتروني" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">رقم الهاتف</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="أدخل رقم الهاتف" value="<?php echo htmlspecialchars($employee['phone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">المدينة</label>
                    <input type="text" name="city" id="city" class="form-control" placeholder="أدخل المدينة" value="<?php echo htmlspecialchars($employee['city']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="job_title" class="form-label">المسمى الوظيفي</label>
                    <input type="text" name="job_title" id="job_title" class="form-control" placeholder="أدخل المسمى الوظيفي" value="<?php echo htmlspecialchars($employee['job_title']); ?>" required>
                </div>
                <button type="submit" name="update_profile" class="btn btn-success w-100">تحديث البيانات</button>
            </form>
        </div>
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success m-4"><?php echo $success_message; ?></div>
        <?php endif; ?>
    </div>
</div>

    <!-- استدعاء Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>