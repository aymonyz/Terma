<?php
include '../db.php';
include 'amin-Header.php';

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
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الإدارة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .btn-primary,
        .btn-success,
        .btn-danger {
            font-weight: bold;
            border-radius: 8px;
        }

        table {
            margin-top: 20px;
        }

        table img {
            border-radius: 5px;
        }

        .badge {
            font-size: 0.9rem;
        }

        h3 {
            margin-top: 30px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #343a40;
        }

        form input,
        form button {
            border-radius: 8px;
        }

        .form-label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center text-primary"> إدارة التصنيفات</h1>

        <!-- إضافة تصنيف جديد -->
        <div class="card p-4 mt-4">
            <h3>إضافة تصنيف جديد</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="category_name" class="form-label">اسم التصنيف</label>
                    <input type="text" name="category_name" id="category_name" class="form-control" placeholder="أدخل اسم التصنيف" required>
                </div>
                <div class="mb-3">
                    <label for="category_image" class="form-label">صورة التصنيف</label>
                    <input type="file" name="category_image" id="category_image" class="form-control">
                </div>
                <button type="submit" name="add_category" class="btn btn-success w-100">إضافة التصنيف</button>
            </form>
        </div>

        <!-- عرض التصنيفات -->
        <div class="card p-4 mt-4">
            <h3>التصنيفات</h3>
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
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
                                    <span>لا توجد صورة</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" enctype="multipart/form-data" class="d-inline">
                                    <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                    <input type="text" name="category_name" value="<?php echo htmlspecialchars($category['category_name']); ?>" class="form-control d-inline w-50" required>
                                    <input type="file" name="category_image" class="form-control d-inline w-25">
                                    <button type="submit" name="update_category" class="btn btn-primary btn-sm">تعديل</button>
                                </form>
                                <a href="?delete_category=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm">حذف</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- عرض الطلبات -->
       
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

