<?php
include '../db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['email'];
} else {
    header("Location: ../index.php");
    exit();
}

// تسجيل الخروج
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// إضافة تصنيف جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $category_image = null;

    // معالجة رفع الصورة
    if (!empty($_FILES['category_image']['name'])) {
        $target_dir = "../uploads/categories/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // إنشاء المجلد إذا لم يكن موجوداً
        }

        $target_file = $target_dir . basename($_FILES['category_image']['name']);
        $img = "uploads/categories/" . basename($_FILES['category_image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['category_image']['tmp_name'], $target_file)) {
                $category_image = $img;
            } else {
                $error_message = "حدث خطأ أثناء رفع الصورة.";
            }
        } else {
            $error_message = "فقط ملفات الصور (JPG, JPEG, PNG, GIF) مسموح بها.";
        }
    }

    if (empty($error_message)) {
        $insert_query = "INSERT INTO categories (category_name, category_image) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ss", $category_name, $category_image);
        $stmt->execute();
        $stmt->close();
        $success_message = "تمت إضافة التصنيف بنجاح.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $category_image = null;

    // معالجة رفع الصورة أثناء التعديل
    if (!empty($_FILES['category_image']['name'])) {
        $target_dir = "../uploads/categories/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES['category_image']['name']);
        $img = "uploads/categories/" . basename($_FILES['category_image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['category_image']['tmp_name'], $target_file)) {
                $category_image = $img;
            } else {
                $error_message = "حدث خطأ أثناء رفع الصورة.";
            }
        } else {
            $error_message = "فقط ملفات الصور (JPG, JPEG, PNG, GIF) مسموح بها.";
        }
    }

    if (empty($error_message)) {
        if ($category_image) {
            $update_query = "UPDATE categories SET category_name = ?, category_image = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssi", $category_name, $category_image, $category_id);
        } else {
            $update_query = "UPDATE categories SET category_name = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $category_name, $category_id);
        }
        $stmt->execute();
        $stmt->close();
        $success_message = "تم تعديل التصنيف بنجاح.";
    }
}

// حذف تصنيف
if (isset($_GET['delete_category'])) {
    $category_id = intval($_GET['delete_category']);
    $delete_query = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->close();
    $success_message = "تم حذف التصنيف بنجاح.";
}

// جلب التصنيفات
$categories = [];
$query = "SELECT * FROM categories";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// جلب الأجهزة
$devices = [];
$devices_query = "
    SELECT devices.*, categories.category_name 
    FROM devices 
    LEFT JOIN categories ON devices.category_id = categories.id";
$result_devices = $conn->query($devices_query);
while ($row = $result_devices->fetch_assoc()) {
    $devices[] = $row;
}

// جلب الطلبات
$purchase_requests = [];
$requests_query = "
    SELECT 
        purchase_requests.*, 
        devices.device_name 
    FROM purchase_requests 
    JOIN devices ON purchase_requests.device_id = devices.id";
$result_requests = $conn->query($requests_query);
while ($row = $result_requests->fetch_assoc()) {
    $purchase_requests[] = $row;
}
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
                <h1>إدارة الطلبات والتصنيفات</h1>
                
                <!-- إضافة تصنيف -->
            <form method="POST" enctype="multipart/form-data">
                <h3>إضافة تصنيف جديد</h3>
                <div class="mb-3">
                    <label for="category_name" class="form-label">اسم التصنيف</label>
                    <input type="text" name="category_name" id="category_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="category_image" class="form-label">صورة التصنيف</label>
                    <input type="file" name="category_image" id="category_image" class="form-control">
                </div>
                <button type="submit" name="add_category" class="btn btn-success">إضافة</button>
            </form>

                <!-- عرض التصنيفات -->
                <h3>التصنيفات</h3>
                <table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>اسم التصنيف</th>
            <th>صورة التصنيف</th>
            <th>الإجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category['id']; ?></td>
                <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                <td>
                    <?php if (!empty($category['category_image'])): ?>
                        <img src="<?php echo htmlspecialchars("../".$category['category_image']); ?>" alt="صورة التصنيف" width="50">
                    <?php else: ?>
                        لا توجد صورة
                    <?php endif; ?>
                </td>
                <td>
                    <form method="POST" enctype="multipart/form-data" style="display:inline-block;">
                        <!-- تعديل اسم التصنيف -->
                        <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                        <input type="text" name="category_name" value="<?php echo htmlspecialchars($category['category_name']); ?>" required>

                        <!-- رفع صورة جديدة -->
                        <input type="file" name="category_image" class="form-control" style="display:inline-block; width:auto;">

                        <!-- زر التعديل -->
                        <button type="submit" name="update_category" class="btn btn-primary btn-sm">تعديل</button>
                    </form>
                    
                    <!-- زر الحذف -->
                    <a href="?delete_category=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm">حذف</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


                <!-- عرض الأجهزة -->
                <h3>الأجهزة</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الجهاز</th>
                            <th>الوصف</th>
                            <th>التصنيف</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($devices as $device): ?>
                            <tr>
                                <td><?php echo $device['id']; ?></td>
                                <td><?php echo htmlspecialchars($device['device_name']); ?></td>
                                <td><?php echo htmlspecialchars($device['device_description']); ?></td>
                                <td><?php echo htmlspecialchars($device['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($device['price']); ?></td>
                                <td><?php echo htmlspecialchars($device['stock_quantity']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- عرض الطلبات -->
                <h3>الطلبات</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم العميل</th>
                            <th>اسم الجهاز</th>
                            <th>الكمية</th>
                            <th>الإجمالي</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($purchase_requests as $request): ?>
                            <tr>
                                <td><?php echo $request['id']; ?></td>
                                <td><?php echo htmlspecialchars($request['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($request['device_name']); ?></td>
                                <td><?php echo htmlspecialchars($request['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($request['total_price']); ?></td>
                                <td>
                                    <?php if ($request['status'] === 'pending'): ?>
                                        <span class="badge bg-warning">معلقة</span>
                                    <?php elseif ($request['status'] === 'approved'): ?>
                                        <span class="badge bg-success">مقبولة</span>
                                    <?php elseif ($request['status'] === 'rejected'): ?>
                                        <span class="badge bg-danger">مرفوضة</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

            <!-- Main Content -->
            
        </div>
    </div>
</body>

</html>
