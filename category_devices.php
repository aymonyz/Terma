<?php
session_start();
// تضمين الاتصال بقاعدة البيانات
include 'db.php';

// جلب جميع التصنيفات
$categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($categories_query);

// التحقق من وجود التصنيف المحدد
$category_id = null;
if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);

    // جلب بيانات التصنيف
    $category_query = "SELECT * FROM categories WHERE id = ?";
    $stmt = $conn->prepare($category_query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $category_result = $stmt->get_result();
    $category = $category_result->fetch_assoc();

    if (!$category) {
        die("التصنيف غير موجود.");
    }

    // جلب الأجهزة التابعة للتصنيف
    $devices_query = "SELECT * FROM devices WHERE category_id = ?";
    $stmt = $conn->prepare($devices_query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $devices_result = $stmt->get_result();
} else {
    // جلب جميع الأجهزة
    $devices_query = "SELECT devices.*, categories.category_name FROM devices 
                      LEFT JOIN categories ON devices.category_id = categories.id";
    $devices_result = $conn->query($devices_query);
}


// إنشاء السلة إذا لم تكن موجودة
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// إضافة منتج إلى السلة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_quantity = 1;

    // التحقق إذا كان المنتج موجودًا بالفعل في السلة
    $exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity']++;
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $product_quantity,
        ];
    }
    echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
    exit;
}
//  print_r($_SESSION);
// حذف منتج من السلة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $product_id = intval($_POST['product_id']);
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($product_id) {
        return $item['id'] != $product_id;
    });
    echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
    exit;
}
?>



<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأجهزة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>


    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Tajawal', sans-serif;
        }

        .category-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .category-buttons a {
            text-decoration: none;
            font-size: 14px;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .category-buttons .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .category-buttons .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .btn-add-to-cart {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 5px;
        }

        .btn-add-to-cart:hover {
            background-color: #218838;
        }

        .btn-back {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 20px;
            background-color: #343a40;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-back:hover {
            background-color: #23272b;
            color: #fff;
        }

        .no-devices {
            text-align: center;
            padding: 20px;
            font-size: 1.2rem;
            color: #6c757d;
            border: 2px dashed #ced4da;
            border-radius: 10px;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- قائمة التصنيفات -->
        <div class="category-buttons">
            <a href="category_devices.php" class="btn btn-primary">عرض جميع الأجهزة</a>
            <?php while ($category = $categories_result->fetch_assoc()): ?>
                <a href="category_devices.php?category_id=<?php echo $category['id']; ?>" 
                   class="btn btn-secondary">
                   <?php echo htmlspecialchars($category['category_name']); ?>
                </a>
            <?php endwhile; ?>
        </div>

        <!-- عرض الأجهزة -->
        <h1 class="mb-4">
            <?php if ($category_id): ?>
                الأجهزة في تصنيف: <?php echo htmlspecialchars($category['category_name']); ?>
            <?php else: ?>
                <h2> جميع الاجهزة</h2>
                <?php endif; ?>
        </h1>
<!-- المنتجات -->
<div class="row">
    <?php while ($device = $devices_result->fetch_assoc()): ?>
        <div class="col-md-4 mb-3">
            <div class="card">
                <img src="<?php echo htmlspecialchars($device['image_path'] ?? 'default-image.png'); ?>" 
                     class="card-img-top" 
                     alt="<?php echo htmlspecialchars($device['device_name']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($device['device_name']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($device['device_description']); ?></p>
                    <p class="card-text">السعر: <?php echo htmlspecialchars($device['price']); ?> ريال</p>
                    <button class="btn btn-add-to-cart" 
                            data-id="<?php echo $device['id']; ?>" 
                            data-name="<?php echo htmlspecialchars($device['device_name']); ?>" 
                            data-price="<?php echo htmlspecialchars($device['price']); ?>">
                            إضافة إلى السلة <i class="fa-solid fa-cart-shopping"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- زر لفتح السلة -->
<button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#cartModal">
    عرض السلة (<span id="cart-count"><?php echo count($_SESSION['cart'] ?? []); ?></span>)
   
</button>

<!-- نافذة السلة -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">سلة التسوق</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="cart-items" class="list-group">
                    <?php if (!empty($_SESSION['cart'])):  ?>
                        
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($item['name']); ?>
                                <span class="badge bg-primary rounded-pill"><?php echo $item['quantity'] ?? ''; ?></span>
                                <button class="btn btn-danger btn-sm remove-from-cart" 
                                        data-id="<?php echo $item['id']; ?>">حذف</button>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">السلة فارغة.</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>


        <!-- زر الرجوع -->
        <div>
            <a href="javascript:history.back()" class="btn-back">رجوع</a>
        </div>
    </div>


<!-- نافذة السلة (Modal) -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">سلة التسوق</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- محتوى السلة -->
                <?php if (!empty($_SESSION['cart'])): ?>
                <ul class="list-group">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($item['name']); ?>
                        <span
                            class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($item['quantity']); ?></span>
                        <button class="btn btn-danger btn-sm remove-from-cart" data-id="<?php echo $item['id']; ?>">
                            حذف
                        </button>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="text-center">السلة فارغة.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <a href="checkout.php" class="btn btn-primary">إتمام الشراء</a>
            </div>
        </div>
    </div>
</div>

<!-- إضافة Bootstrap CSS و JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</html>
<script>
    const swiper = new Swiper('.swiper-container', {
        loop: true, // إعادة التشغيل بشكل دائري
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        autoplay: {
            delay: 3000, // التمرير التلقائي كل 3 ثواني
            disableOnInteraction: false, // استمرار التمرير التلقائي بعد التفاعل
        },
        slidesPerView: 3, // عدد العناصر المرئية
        spaceBetween: 20, // المسافة بين العناصر
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            480: {
                slidesPerView: 1,
            },
        },
    });
    document.querySelectorAll('.btn-add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.getAttribute('data-id');
        const productName = button.getAttribute('data-name');
        const productPrice = button.getAttribute('data-price');

        fetch('cart.php', { // رابط ملف PHP المسؤول عن إضافة المنتج
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                add_to_cart: true,
                product_id: productId,
                product_name: productName,
                product_price: productPrice,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cart-count').innerText = data.cart_count;
                alert('تمت إضافة المنتج إلى السلة بنجاح!');
            } else {
                alert('حدث خطأ أثناء الإضافة.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
document.querySelectorAll('.remove-from-cart').forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.getAttribute('data-id');

        fetch('category_devices.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                remove_from_cart: true,
                product_id: productId,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cart-count').innerText = data.cart_count;
                button.closest('.list-group-item').remove();
                alert('تمت إزالة المنتج من السلة.');
            } else {
                alert('حدث خطأ أثناء الحذف.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
document.querySelectorAll('.remove-from-cart').forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.getAttribute('data-id');

        // إرسال طلب الحذف إلى cart.php
        fetch('cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                remove_from_cart: true,
                product_id: productId,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // تحديث عدد العناصر في السلة
                document.getElementById('cart-count').innerText = data.cart_count;

                // إزالة العنصر من قائمة العرض
                button.closest('.list-group-item').remove();

                // عرض رسالة نجاح
                alert('تمت إزالة المنتج من السلة.');
            } else {
                // عرض رسالة خطأ
                alert('حدث خطأ أثناء الحذف.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});

</script>

    
</body>
</html>
