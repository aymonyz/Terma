<?php
session_start();
include '../db.php';


// جلب الطلبات الحالية
$requests_query = "SELECT * FROM maintenance_requests ORDER BY created_at DESC";
$requests_result = $conn->query($requests_query);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات الصيانة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- استدعاء Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-submit {
            background-color: #007bff;
            color: #fff;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        /* تنسيق عام */
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

        h1 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table img {
            max-width: 50px;
            height: auto;
            border-radius: 5px;
        }

        .modal-header {
            background-color: #007bff;
            color: #fff;
        }

        .btn-success {
            width: 100%;
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
<div class="container mt-4">
  
    <div class="dashboard">
        <h1>لوحة التحكم</h1>
        <div class="link-control">
            <a href="profail.php"><i class="fas fa-user"></i> الملف الشخصي</a>
            <a href="password.php"><i class="fas fa-lock"></i> تعديل كلمة السر</a>
            <a href="device.php"><i class="fas fa-laptop"></i> الأجهزة</a>
            <a href="requests.php"><i class="fas fa-clipboard-list"></i> الطلبات</a>
            <a href="maintenance_requests.php"><i class="fas fa-tools"></i> طلبات الصيانة</a>

            <a href="../index.php"><i class="fas fa-home"></i> الرئيسية</a>
        </div>
    </div>
    <!-- نموذج إضافة طلب صيانة -->


    <!-- عرض الطلبات الحالية -->
    <div class="table-container">
        <h4>الطلبات الحالية</h4>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>اسم الجهاز</th>
                <th>وصف المشكلة</th>
                <th>اسم المستخدم</th>
                <th>رقم التواصل</th>
                <th>الحالة</th>
                <th>الإجراءات</th>

                <th>تاريخ الطلب</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($request = $requests_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $request['id']; ?></td>
                    <td><?php echo htmlspecialchars($request['device_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['issue_description']); ?></td>
                    <td><?php echo htmlspecialchars($request['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['user_contact']); ?></td>
                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                    <td>
    <form action="update_maintenance_request.php" method="POST" style="display:inline-block;">
        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
        <select name="status" class="form-select form-select-sm">
            <option value="جديد" <?php echo $request['status'] == 'جديد' ? 'selected' : ''; ?>>جديد</option>
            <option value="قيد التنفيذ" <?php echo $request['status'] == 'قيد التنفيذ' ? 'selected' : ''; ?>>قيد التنفيذ</option>
            <option value="مكتمل" <?php echo $request['status'] == 'مكتمل' ? 'selected' : ''; ?>>مكتمل</option>
        </select>
        <button type="submit" class="btn btn-sm btn-primary mt-2">تحديث</button>
    </form>
</td>

                    <td><?php echo htmlspecialchars($request['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
            <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?php
        echo $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger">
        <?php
        echo $_SESSION['error_message'];
        unset($_SESSION['error_message']);
        ?>
    </div>
<?php endif; ?>

        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
