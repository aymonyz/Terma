<?php
include '../db.php';
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
    header("Location: ../admin.php");
    exit();
}

$user_query = "SELECT COUNT(*) as total_user FROM user";
$result_user = $conn->query($user_query);
$user = $result_user->fetch_assoc()['total_user'];

$emp_query = "SELECT COUNT(*) as total_emp FROM emp";
$result_emp = $conn->query($emp_query);
$total_emp = $result_emp->fetch_assoc()['total_emp'];

// // جلب عدد الوظائف
// $jobs_query = "SELECT COUNT(*) as total_jobs FROM jobs";
// $result_jobs = $conn->query($jobs_query);
// $total_jobs = $result_jobs->fetch_assoc()['total_jobs'];

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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            padding: 20px;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .sidebar a:hover {
            color: #fff;
        }

        .sidebar .username {
            margin-bottom: 20px;
            font-size: 1.2rem;
            color: #ffc107;
        }

        .logout-btn {
            background-color: #dc3545;
            border: none;
            padding: 10px 20px;
            color: #fff;
            border-radius: 5px;
            font-size: 0.9rem;
            margin-top: 20px;
            display: block;
            text-align: center;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .main-content {
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s;
            height: 150px; /* تثبيت الارتفاع */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        p {
            margin: 2px;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <h4 class="text-white mb-4">القائمة</h4>
                <div class="username">مرحباً، المدير </div>
                <a href="admin_dashboard.php">الصفحة الرئيسية</a>
                <a href="admin_user.php">إدارة المستخدمين</a>
                <a href="manage_requests.php">إدارة الطلبات</a>
                <a href="../index.php">العودة</a>
                <!-- زر تسجيل الخروج -->
                <a href="?logout=true" class="logout-btn">تسجيل الخروج</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <h2 class="mb-4">لوحة القيادة</h2>
                <div class="row">
                    <!-- Card 1 -->
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">الوظائف والإعلانات</h5>
                                <p class="card-text">عدد الوظائف: <strong><?php  ?></strong></p>
                                <p class="card-text">عدد الإعلانات: <strong><?php  ?></strong></p>
                                <a href="manage_requests.php" class="text-white">عرض التفاصيل</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">المستخدمين</h5>
                                <p>عدد المستخدمين: <strong><?php  echo $total_users ?></strong></p>
                                <p>عدد العملاء: <strong><?php echo $user?></strong></p>
                                <p>عدد الموظفين: <strong><?php echo $total_emp; ?></strong></p>
                                <a href="admin_user.php" class="text-white">عرض التفاصيل</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">الطلبات</h5>
                                <p class="card-text">الطلبات المعلقة: <strong><?php  ?></strong></p>
                                <p class="card-text">الطلبات المقبولة: <strong><?php  ?></strong></p>
                                <p class="card-text">الطلبات المرفوضة: <strong><?php  ?></strong></p>
                                <a href="manage_requests.php" class="text-white">عرض التفاصيل</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <p>© 2024 تصميم لوحة القيادة | جميع الحقوق محفوظة</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
