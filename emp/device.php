<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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
        mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
    }

    $target_file = $target_dir . time() . "_" . basename($file["name"]); // Unique name for the file
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        return ["error" => "فقط ملفات الصور (JPG, JPEG, PNG, GIF) مسموح بها."];
    }

    // Check and upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ["path" => "uploads/devices/" . basename($target_file)];
    } else {
        return ["error" => "حدث خطأ أثناء رفع الصورة. تأكد من صلاحيات المجلد."];
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

    // Only execute query if no upload error
    if ($image_path || empty($_FILES['device_image']['name'])) {
        $insert_query = "INSERT INTO devices (emp_id, device_name, category_id, price, stock_quantity, device_description, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("isidiss", $emp_id, $device_name, $category_id, $price, $stock_quantity, $device_description, $image_path);
        $stmt->execute();
        $stmt->close();

        $success_message = "تم إضافة الجهاز بنجاح.";
    }
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
$devices_query = "
    SELECT 
        devices.*, 
        categories.category_name, 
        CONCAT(emp.first_name, ' ', emp.last_name) AS added_by
    FROM devices
    LEFT JOIN categories ON devices.category_id = categories.id
    LEFT JOIN emp ON devices.emp_id = emp.id
    WHERE devices.emp_id = ?";
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
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأجهزة</title>
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

    <!-- إضافة جهاز جديد -->
    <div class="container mt-4">
        <h2 class="mb-4 text-center">إدارة الأجهزة</h2>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <h3 class="mb-3">إضافة جهاز جديد</h3>
        <form method="POST" enctype="multipart/form-data" class="mb-5 p-4 bg-white shadow-sm rounded">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="device_name" class="form-label">اسم الجهاز</label>
                    <input type="text" name="device_name" id="device_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
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
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">السعر</label>
                    <input type="number" name="price" id="price" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="stock_quantity" class="form-label">الكمية</label>
                    <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="device_image" class="form-label">صورة الجهاز</label>
                    <input type="file" name="device_image" id="device_image" class="form-control">
                </div>
                <div class="col-12 mb-3">
                    <label for="device_description" class="form-label">الوصف</label>
                    <textarea name="device_description" id="device_description" class="form-control" rows="4" required></textarea>
                </div>
            </div>
            <button type="submit" name="add_device" class="btn btn-success">إضافة الجهاز</button>
        </form>

        <!-- قائمة الأجهزة -->
        <h3 class="mb-3">قائمة الأجهزة</h3>
        <table class="table table-striped">
        <thead class="table-primary">
    <tr>
        <th>#</th>
        <th>صورة</th>
        <th>اسم الجهاز</th>
        <th>التصنيف</th>
        <th>السعر</th>
        <th>الكمية</th>
        <th>الموظف</th>
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
                    <span>لا توجد صورة</span>
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($device['device_name']); ?></td>
            <td><?php echo htmlspecialchars($device['category_name']); ?></td>
            <td><?php echo htmlspecialchars($device['price']); ?></td>
            <td><?php echo htmlspecialchars($device['stock_quantity']); ?></td>
        
            <td><?php echo htmlspecialchars($device['added_by'] ?? 'غير معروف'); ?></td>

            <!-- اسم الموظف -->
            <td>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editDeviceModal<?php echo $device['id']; ?>">
    تعديل
</button>

                <a href="?delete_device=<?php echo $device['id']; ?>" class="btn btn-danger btn-sm">حذف</a>
            </td>
        </tr>



        <!-- نافذة منبثقة للتعديل -->
<div class="modal fade" id="editDeviceModal<?php echo $device['id']; ?>" tabindex="-1" aria-labelledby="editDeviceModalLabel<?php echo $device['id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDeviceModalLabel<?php echo $device['id']; ?>">تعديل بيانات الجهاز</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- حقل مخفي لرقم الجهاز -->
                    <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">

                    <!-- اسم الجهاز -->
                    <div class="mb-3">
                        <label for="device_name" class="form-label">اسم الجهاز</label>
                        <input type="text" name="device_name" class="form-control" value="<?php echo htmlspecialchars($device['device_name']); ?>" required>
                    </div>

                    <!-- التصنيف -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">التصنيف</label>
                        <select name="category_id" class="form-control" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $device['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- السعر -->
                    <div class="mb-3">
                        <label for="price" class="form-label">السعر</label>
                        <input type="number" name="price" class="form-control" value="<?php echo $device['price']; ?>" required>
                    </div>

                    <!-- الكمية -->
                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label">الكمية</label>
                        <input type="number" name="stock_quantity" class="form-control" value="<?php echo $device['stock_quantity']; ?>" required>
                    </div>

                    <!-- صورة جديدة -->
                    <div class="mb-3">
                        <label for="device_image" class="form-label">تحديث الصورة</label>
                        <input type="file" name="device_image" class="form-control">
                    </div>

                    <!-- الوصف -->
                    <div class="mb-3">
                        <label for="device_description" class="form-label">الوصف</label>
                        <textarea name="device_description" class="form-control" rows="3" required><?php echo htmlspecialchars($device['device_description']); ?></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="submit" name="update_device" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
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

