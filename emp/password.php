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
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تغيير كلمة المرور</title>
    <!-- استدعاء Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- استدعاء Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* تنسيق عام */
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            padding: 20px;
        }
        h1 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* لوحة التحكم */
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

        h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            width: 100%;
            font-size: 1.2rem;
            padding: 10px;
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
            <a href="requests.php"><i class="fas fa-clipboard-list"></i> الطلبات</a>
            <a href="maintenance_requests.php"><i class="fas fa-clipboard-list"></i>طلبات الصيانة</a>

            <a href="../index.php"><i class="fas fa-home"></i> الرئيسية</a>
        </div>
    </div>
    <!-- نموذج تغيير كلمة المرور -->
    <div class="container">
        <h2>تغيير كلمة المرور</h2>

        <!-- رسالة نجاح -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- رسالة خطأ -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- النموذج -->
        <form method="POST">
            <div class="mb-4">
                <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                <input type="password" name="current_password" id="current_password" class="form-control"
                    placeholder="أدخل كلمة المرور الحالية" required>
            </div>
            <div class="mb-4">
                <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                <input type="password" name="new_password" id="new_password" class="form-control"
                    placeholder="أدخل كلمة المرور الجديدة" required>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="form-label">تأكيد كلمة المرور الجديدة</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                    placeholder="أعد إدخال كلمة المرور الجديدة" required>
            </div>
            <button type="submit" class="btn btn-primary">تغيير كلمة المرور</button>
        </form>
    </div>

    <!-- استدعاء Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

