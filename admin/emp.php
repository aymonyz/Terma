<?php
session_start();
ob_start();
include '../db.php';
include 'amin-Header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// تسجيل الخروج
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// جلب بيانات المستخدمين
$users = [];
$employees = [];

try {
    // جلب المستخدمين
    $userQuery = $conn->query("SELECT * FROM user");
    while ($row = $userQuery->fetch_assoc()) {
        $users[] = $row;
    }

    // جلب الموظفين
    $employeeQuery = $conn->query("SELECT * FROM emp");
    while ($row = $employeeQuery->fetch_assoc()) {
        $employees[] = $row;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// حذف مستخدم
if (isset($_GET['delete_user'])) {
    $id = intval($_GET['delete_user']);
    $conn->query("DELETE FROM user WHERE id = $id");
    header("Location: admin_user.php");
    exit();
}

// حذف موظف
if (isset($_GET['delete_employee'])) {
    $id = intval($_GET['delete_employee']);
    $conn->query("DELETE FROM emp WHERE id = $id");
    header("Location: admin_user.php");
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    $id = intval($_POST['id']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $city = $conn->real_escape_string($_POST['city']);
    $job_title = $conn->real_escape_string($_POST['job_title']);

    $conn->query("UPDATE emp SET first_name = '$first_name', last_name = '$last_name', email = '$email', phone = '$phone', city = '$city', job_title = '$job_title' WHERE id = $id");
    header("Location: emp.php");
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $city = $conn->real_escape_string($_POST['city']);
    $job_title = $conn->real_escape_string($_POST['job_title']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);
    $created_by = $_SESSION['user_id'];

    $conn->query("INSERT INTO emp (first_name, last_name, email, phone, city, job_title, password, created_by) VALUES ('$first_name', '$last_name', '$email', '$phone', '$city', '$job_title', '$password', '$created_by')");
    header("Location: emo.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين والموظفين</title>
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
            position: fixed;
            top: 0;
            left: 0;
            width: 18%;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            margin-bottom: 15px;
        }

        .sidebar a:hover {
            color: #fff;
        }

        .main-content {
            margin-left: 20%;
            padding: 20px;
        }

        .table-container {
            margin-top: 20px;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #fff;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }
        .sidebar .username {
            margin-bottom: 20px;
            font-size: 1.2rem;
            color: #ffc107;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    

    <!-- Main Content -->
    <div class="main-content">
        <h2>إدارة  الموظفين</h2>

        <!-- Users Section -->

  

        <!-- Employees Section -->
        <div class="table-container">
            
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">إضافة موظف جديد</button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم الأول</th>
                        <th>الاسم الأخير</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>المدينة</th>
                        <th>الوظيفة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?= $employee['id']; ?></td>
                            <td><?= htmlspecialchars($employee['first_name']); ?></td>
                            <td><?= htmlspecialchars($employee['last_name']); ?></td>
                            <td><?= htmlspecialchars($employee['email']); ?></td>
                            <td><?= htmlspecialchars($employee['phone']); ?></td>
                            <td><?= htmlspecialchars($employee['city']); ?></td>
                            <td><?= htmlspecialchars($employee['job_title']); ?></td>
                            <td>
                                <a href="?delete_employee=<?= $employee['id']; ?>" class="btn btn-delete btn-sm">حذف</a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editEmployeeModal<?= $employee['id']; ?>">تعديل</button>
                            </td>
                        </tr>

                        <!-- Edit Employee Modal -->
                        <div class="modal fade" id="editEmployeeModal<?= $employee['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">تعديل بيانات الموظف</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST">
                                            <input type="hidden" name="id" value="<?= $employee['id']; ?>">
                                            <div class="mb-3">
                                                <label>الاسم الأول</label>
                                                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($employee['first_name']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>الاسم الأخير</label>
                                                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($employee['last_name']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>البريد الإلكتروني</label>
                                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($employee['email']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>رقم الهاتف</label>
                                                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($employee['phone']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>المدينة</label>
                                                <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($employee['city']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>الوظيفة</label>
                                                <input type="text" name="job_title" class="form-control" value="<?= htmlspecialchars($employee['job_title']); ?>" required>
                                            </div>
                                            <button type="submit" name="update_employee" class="btn btn-primary">حفظ التعديلات</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Add Employee Modal -->
        <div class="modal fade" id="addEmployeeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">إضافة موظف جديد</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label>الاسم الأول</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>الاسم الأخير</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>رقم الهاتف</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>المدينة</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>الوظيفة</label>
                                <input type="text" name="job_title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>كلمة المرور</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" name="add_employee" class="btn btn-success">إضافة</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
