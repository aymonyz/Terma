<?php
include '../db.php';
session_start();

// التحقق من تسجيل الدخول وصلاحيات الموظف
if (!isset($_SESSION['loggedin']) || $_SESSION['account_type'] !== 'employee') {
    header("Location: ../login.php");
    exit();
}

$emp_id = $_SESSION['user_id'];
$success_message = "";

// معالجة رفع الصورة
function uploadImage($file) {
    $target_dir = "../uploads/devices/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // إنشاء المجلد إذا لم يكن موجوداً
    }

    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // التحقق من نوع الملف
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        return ["error" => "فقط ملفات الصور (JPG, JPEG, PNG, GIF) مسموح بها."];
    }

    // رفع الصورة
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ["path" => "uploads/devices/" . basename($file["name"])];
    } else {
        return ["error" => "حدث خطأ أثناء رفع الصورة."];
    }
}

// إضافة جهاز جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_device'])) {
    $device_name = $_POST['device_name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $device_description = $_POST['device_description'];

    $image_path = null;
    if (!empty($_FILES['device_image']['name'])) {
        $upload_result = uploadImage($_FILES['device_image']);
        if (isset($upload_result['error'])) {
            $success_message = $upload_result['error'];
        } else {
            $image_path = $upload_result['path'];
        }
    }

    $insert_query = "INSERT INTO devices (emp_id, device_name, category_id, price, stock_quantity, device_description, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isidiss", $emp_id, $device_name, $category_id, $price, $stock_quantity, $device_description, $image_path);
    $stmt->execute();
    $stmt->close();

    $success_message = "تم إضافة الجهاز بنجاح.";
}

// تعديل جهاز
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_device'])) {
    $device_id = $_POST['device_id'];
    $device_name = $_POST['device_name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $device_description = $_POST['device_description'];

    $image_path = null;
    if (!empty($_FILES['device_image']['name'])) {
        $upload_result = uploadImage($_FILES['device_image']);
        if (isset($upload_result['error'])) {
            $success_message = $upload_result['error'];
        } else {
            $image_path = $upload_result['path'];
        }
    }

    $update_query = "UPDATE devices SET device_name = ?, category_id = ?, price = ?, stock_quantity = ?, device_description = ?";
    if ($image_path) {
        $update_query .= ", image_path = ?";
    }
    $update_query .= " WHERE id = ? AND emp_id = ?";

    $stmt = $conn->prepare($update_query);
    if ($image_path) {
        $stmt->bind_param("sidissii", $device_name, $category_id, $price, $stock_quantity, $device_description, $image_path, $device_id, $emp_id);
    } else {
        $stmt->bind_param("sidisii", $device_name, $category_id, $price, $stock_quantity, $device_description, $device_id, $emp_id);
    }
    $stmt->execute();
    $stmt->close();

    $success_message = "تم تعديل بيانات الجهاز بنجاح.";
}

// حذف جهاز
if (isset($_GET['delete_device'])) {
    $device_id = intval($_GET['delete_device']);

    $delete_query = "DELETE FROM devices WHERE id = ? AND emp_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $device_id, $emp_id);
    $stmt->execute();
    $stmt->close();

    $success_message = "تم حذف الجهاز بنجاح.";
}

// عرض الأجهزة
$devices_query = "SELECT devices.*, categories.category_name FROM devices LEFT JOIN categories ON devices.category_id = categories.id WHERE emp_id = ?";
$stmt = $conn->prepare($devices_query);
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$devices = $stmt->get_result();
$stmt->close();

$categories = [];
$categories_query = "SELECT * FROM categories";
$result = $conn->query($categories_query);
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأجهزة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Heebo', sans-serif;
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
        img {
            max-width: 50px;
            height: auto;
        }
    </style>
</head>

<body>
<div class="link-control">
    <a href="profail.php">الملف الشخصي</a>
    <a href="password.php"> تعديل كلمة السر </a>
    <a href="device.php"> الاجهزة</a>
    <a href="request.php"> الطلبات</a>
    <a href="../index.php"> الرئيسة</a>
</div>
    <div class="container mt-4">
        <h1>إدارة الأجهزة</h1>
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <h3>إضافة جهاز جديد</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="device_name" class="form-label">اسم الجهاز</label>
                <input type="text" name="device_name" id="device_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">التصنيف</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="" disabled selected>اختر التصنيف</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">السعر</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="stock_quantity" class="form-label">الكمية</label>
                <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="device_image" class="form-label">صورة الجهاز</label>
                <input type="file" name="device_image" id="device_image" class="form-control">
            </div>
            <div class="mb-3">
                <label for="device_description" class="form-label">الوصف</label>
                <textarea name="device_description" id="device_description" class="form-control" required></textarea>
            </div>
            <button type="submit" name="add_device" class="btn btn-success">إضافة</button>
        </form>

        <h3>قائمة الأجهزة</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>صورة</th>
                    <th>اسم الجهاز</th>
                    <th>التصنيف</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($devices as $device): ?>
                    <tr>
                        <td><?php echo $device['id']; ?></td>
                        <td>
                            <?php if ($device['image_path']): ?>
                                <img src="../<?php echo $device['image_path']; ?>" alt="صورة الجهاز">
                            <?php else: ?>
                                لا توجد صورة
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($device['device_name']); ?></td>
                        <td><?php echo htmlspecialchars($device['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($device['price']); ?></td>
                        <td><?php echo htmlspecialchars($device['stock_quantity']); ?></td>
                        <td>
                            <!-- زر التعديل -->
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editDeviceModal<?php echo $device['id']; ?>">تعديل</button>
                            <!-- زر الحذف -->
                            <a href="?delete_device=<?php echo $device['id']; ?>" class="btn btn-danger btn-sm">حذف</a>
                        </td>
                    </tr>

                    <!-- نافذة التعديل (Modal) -->
                    <div class="modal fade" id="editDeviceModal<?php echo $device['id']; ?>" tabindex="-1" aria-labelledby="editDeviceModalLabel<?php echo $device['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editDeviceModalLabel<?php echo $device['id']; ?>">تعديل الجهاز</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
                                        <div class="mb-3">
                                            <label for="device_name" class="form-label">اسم الجهاز</label>
                                            <input type="text" name="device_name" class="form-control" value="<?php echo htmlspecialchars($device['device_name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">التصنيف</label>
                                            <select name="category_id" class="form-control" required>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>" <?php echo $device['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($category['category_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="price" class="form-label">السعر</label>
                                            <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($device['price']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="stock_quantity" class="form-label">الكمية</label>
                                            <input type="number" name="stock_quantity" class="form-control" value="<?php echo htmlspecialchars($device['stock_quantity']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="device_image" class="form-label">صورة الجهاز</label>
                                            <input type="file" name="device_image" class="form-control">
                                            <?php if ($device['image_path']): ?>
                                                <p>الصورة الحالية: <img src="../<?php echo $device['image_path']; ?>" alt="صورة الجهاز" width="50"></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-3">
                                            <label for="device_description" class="form-label">الوصف</label>
                                            <textarea name="device_description" class="form-control" required><?php echo htmlspecialchars($device['device_description']); ?></textarea>
                                        </div>
                                        <button type="submit" name="update_device" class="btn btn-success">حفظ التعديلات</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
