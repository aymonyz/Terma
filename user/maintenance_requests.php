<?php
session_start();
include '../db.php';
include 'nav.php';
// إضافة طلب صيانة
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $device_name = $conn->real_escape_string($_POST['device_name']);
    $issue_description = $conn->real_escape_string($_POST['issue_description']);
    $user_name = $conn->real_escape_string($_POST['user_name']);
    $user_contact = $conn->real_escape_string($_POST['user_contact']);

    $sql = "INSERT INTO maintenance_requests (device_name, issue_description, user_name, user_contact) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $device_name, $issue_description, $user_name, $user_contact);

    if ($stmt->execute()) {
        $success_message = "تم إرسال طلب الصيانة بنجاح!";
    } else {
        $error_message = "حدث خطأ أثناء إرسال الطلب. الرجاء المحاولة مرة أخرى.";
    }
    $stmt->close();
}

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
    </style>
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center mb-4">طلبات الصيانة</h1>

    <!-- نموذج إضافة طلب صيانة -->
    <div class="form-container">
        <h4>إضافة طلب صيانة</h4>
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="device_name" class="form-label">اسم الجهاز</label>
                <input type="text" class="form-control" id="device_name" name="device_name" required>
            </div>
            <div class="mb-3">
                <label for="issue_description" class="form-label">وصف المشكلة</label>
                <textarea class="form-control" id="issue_description" name="issue_description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="user_name" class="form-label">اسم المستخدم</label>
                <input type="text" class="form-control" id="user_name" name="user_name" required>
            </div>
            <div class="mb-3">
                <label for="user_contact" class="form-label">رقم التواصل</label>
                <input type="text" class="form-control" id="user_contact" name="user_contact" required>
            </div>
            <button type="submit" class="btn btn-submit">إرسال الطلب</button>
        </form>
    </div>

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
                    <td><?php echo htmlspecialchars($request['created_at']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
