<?php
include '../db.php';
include 'amin-Header.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['email'];
} else {
    header("Location: ../admin.php");
    exit();
}

// تسجيل الخروج
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

$user_query = "SELECT COUNT(*) as total_user FROM user";
$result_user = $conn->query($user_query);
$user = $result_user->fetch_assoc()['total_user'];

$emp_query = "SELECT COUNT(*) as total_emp FROM emp";
$result_emp = $conn->query($emp_query);
$total_emp = $result_emp->fetch_assoc()['total_emp'];

// // جلب عدد الوظائف
$device = "SELECT COUNT(*) as device_jobs FROM devices";
$result_device = $conn->query($device);
$total_device = $result_device->fetch_assoc()['device_jobs'];

// // جلب عدد الإعلانات
// $ads_query = "SELECT COUNT(*) as total_ads FROM ads";
// $result_ads = $conn->query($ads_query);
// $total_ads = $result_ads->fetch_assoc()['total_ads'];

// // جلب عدد الطلبات المعلقة
// $pending_requests_query = "SELECT COUNT(*) as pending_requests FROM job_applications WHERE status = 0";
// $result_pending_requests = $conn->query($pending_requests_query);
// $pending_requests = $result_pending_requests->fetch_assoc()['pending_requests'];

// // جلب عدد الطلبات المقبولة
// $approved_requests_query = "SELECT COUNT(*) as approved_requests FROM job_applications WHERE status = 1";
// $result_approved_requests = $conn->query($approved_requests_query);
// $approved_requests = $result_approved_requests->fetch_assoc()['approved_requests'];

// // جلب عدد الطلبات المرفوضة
// $rejected_requests_query = "SELECT COUNT(*) as rejected_requests FROM job_applications WHERE status = 2";
// $result_rejected_requests = $conn->query($rejected_requests_query);
// $rejected_requests = $result_rejected_requests->fetch_assoc()['rejected_requests'];

// // حساب العدد الكلي للمستخدمين
$total_users = $user + $total_emp;
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة القيادة</title>
    <!-- استدعاء Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
            font-family: 'Tajawal', sans-serif;
            margin: 0;
            padding: 0;
        }

        

        /* المحتوى الرئيسي */
        .main-content {
            padding: 20px;
            margin-top: 15px;
        }

        .card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card h5 {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .card p {
            margin: 5px 0;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .navbar a {
                font-size: 0.9rem;
                margin-right: 10px;
            }

            .logout-btn {
                margin-left: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- الشريط العلوي -->
    
<!-- إضافة Bootstrap وأيقونات FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



    <!-- المحتوى الرئيسي -->
    <div class="main-content">
        <h2 class="mb-4 text-center">لوحة القيادة</h2>
        <div class="row g-4">
            <!-- بطاقة الأجهزة -->
            <div class="col-md-4">
                <div class="card text-center">
                    <h5 class="text-primary">الأجهزة</h5>
                    <p>عدد الأجهزة: <strong><?php echo $total_device; ?></strong></p>
                    <a href="manage_requests.php" class="text-primary">عرض التفاصيل</a>
                </div>
            </div>
            <!-- بطاقة المستخدمين -->
            <div class="col-md-4">
                <div class="card text-center">
                    <h5 class="text-success">المستخدمون</h5>
                    <p>عدد المستخدمين: <strong><?php echo $total_users; ?></strong></p>
                    <p>عدد العملاء: <strong><?php echo $user; ?></strong></p>
                    <p>عدد الموظفين: <strong><?php echo $total_emp; ?></strong></p>
                    <a href="admin_user.php" class="text-success">عرض التفاصيل</a>
                </div>
            </div>
            <!-- بطاقة الطلبات -->
            <div class="col-md-4">
                <div class="card text-center">
                    <h5 class="text-danger">الطلبات</h5>
                    <p>الطلبات المعلقة: <strong><?php echo $pending_requests; ?></strong></p>
                    <p>الطلبات المقبولة: <strong><?php echo $approved_requests; ?></strong></p>
                    <p>الطلبات المرفوضة: <strong><?php echo $rejected_requests; ?></strong></p>
                    <a href="manage_requests.php" class="text-danger">عرض التفاصيل</a>
                </div>
            </div>
        </div>
    </div>

    <!-- الفوتر -->
    <div class="footer">
        <p>© 2024 تصميم لوحة القيادة | جميع الحقوق محفوظة</p>
    </div>

    <!-- استدعاء Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

