<?php
error_reporting(E_ALL); // عرض جميع الأخطاء
ini_set('display_errors', 1); // تمكين عرض الأخطاء
ini_set('display_startup_errors', 1); 
session_start();
include 'db.php';

// التحقق من حالة تسجيل الدخول
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
// echo $logged_in;
// إذا كان المستخدم مسجلاً الدخول، جلب تفاصيل المستخدم
if ($logged_in) {
    $account_type = $_SESSION['account_type']; 
    $email = $_SESSION['email'];
    $user_id = $_SESSION['user_id'];
} else {
    
    $_SESSION['loggedin'] = false;
}


// لطباعة الجلسة للتحقق
$query = "SELECT category_name, category_image FROM categories";
$result = $conn->query($query);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Homepage</title>
    <!-- الخطوط -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- استدعاء ملف CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/cart.css">
</head>

<body>
    <!-- الهيدر مع الفيديو كخلفية -->
    <header class="video-header">
        <!-- فيديو الخلفية -->
        <video autoplay loop muted playsinline class="background-video">
            <source src="vido/Comp-1.mp4" type="video/mp4">
        </video>

        <!-- محتوى الهيدر -->
        <div class="header-content">
            <!-- القائمة العلوية -->
   


                <ul class="nav-list">
                    <li>About us</li>
                    <li>Solutions</li>
                    <li>Partners</li>
                    <li>Our Clients</li>
                    <li>Contact</li>
                    <!-- <?php echo $logged_in;?> -->
                    <?php if ($logged_in): ?>
                    <?php if ($account_type === 'customer'): ?>
                    <li class="nav-item"><a href="user/profail.php" class="nav-link">Profile</a></li>
                    <li class="nav-item"
                        style="padding: 20px;background: #df6451;position: fixed;bottom: 20px;right: 60px;border-radius: 34px;">
                        <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#cartModal">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>
                    </li>
                    <?php elseif ($account_type === 'employee'): ?>
                    <li class="nav-item"><a href="emp/profail.php" class="nav-link">Control</a></li>
                    <?php elseif ($account_type === 'admin'): ?>
                    <li class="nav-item"><a href="admin/admin_dashboard.php" class="nav-link">Admin Panel</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="pages/logout.php" class="nav-link">Logout</a></li>
                    <?php else: ?>
                    <!-- خيار تسجيل الدخول إذا لم يكن مسجلاً -->
                    <li class="nav-item"><a href="pages/login.php" class="nav-link">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>


            <!-- النص الرئيسي في الهيدر -->
            <div class="hero-text">
                <h1>Terma Medical</h1>
                <p>Supplies Co. Ltd</p>
            </div>
        </div>
    </header>
    <!-- قسم الفيديو (Hero Section) -->



    <!-- قسم التعريف بالشركة -->
    <section class="about-section">
        <div class="container">
            <h3 class="section-title">KNOW US</h3>
            <h2 class="main-title">Who We Are</h2>
            <p class="description">
                Terma Medical is the leading healthcare solutions provider in Khartoum Sudan. Over the years, we have partnered with the world’s most renowned healthcare companies that offer the best-in-class solutions and finest technology. Through ongoing and sustainable improvements, we can provide solutions that generate significant value for healthcare providers and their patients.

                Terma Medical is the leading healthcare solutions provider in Khartoum Sudan. Over the years, we have
                partnered with the world’s most renowned healthcare companies that offer the best-in-class solutions and
                finest technology. Through ongoing and sustainable improvements, we can provide solutions that generate
                significant value for healthcare providers and their patients.
            </p>
            <div class="stats">
                <div class="stat">
                    <img src="icom/count-icon-3.webp" alt="Partners Icon" class="icon">
                    <h3>+200</h3>
                    <p>Employees</p>
                </div>
                <div class="stat">
                    <img src="icom/count-icon-2.webp" alt="Employees Icon" class="icon">
                    <h3>+600</h3>
                    <p>Products</p>
                </div>
                <div class="stat">
                    <img src="icom/count-icon-1-150x103.webp" alt="Products Icon" class="icon">
                    <h3>+70,000</h3>
                    <p>Partners</p>
                </div>
            </div>
        </div>
    </section>

   



    <!-- قسم الحلول -->
    <div id="section1" class="section">
    <h2>How We Can Help You</h2>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($categories as $category): ?>
                <div class="swiper-slide">
                    <a href="<?php echo htmlspecialchars($category['category_name']); ?>" class="no-lightbox">
                        <img loading="lazy" decoding="async" width="372" height="483" 
                            src="<?php echo htmlspecialchars($category['category_image']); ?>" 
                            alt="<?php echo htmlspecialchars($category['category_name']); ?>" 
                            class="category-img">
                    </a>
                    <div class="card-title"><?php echo htmlspecialchars($category['category_name']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- أزرار التنقل -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!-- نقاط التنقل -->
        <div class="swiper-pagination"></div>
    </div>
</div>

    <!-- <div class="elementor-element elementor-element-e8d69c6 elementor-widget elementor-widget-theme-post-featured-image elementor-widget-image"
        data-id="e8d69c6" data-element_type="widget" data-widget_type="theme-post-featured-image.default">
        <div class="elementor-widget-container">
            <a href="https://aljeel.com/medical-equipment/" class="no-lightbox">
                <img loading="lazy" decoding="async" width="372" height="483"
                    src="https://aljeel.com/wp-content/uploads/2022/11/Medical-Equipment.png"
                    class="attachment-full size-full wp-image-2165" alt=""
                    srcset="https://aljeel.com/wp-content/uploads/2022/11/Medical-Equipment.png 372w, https://aljeel.com/wp-content/uploads/2022/11/Medical-Equipment-231x300.png 231w"
                    sizes="(max-width: 372px) 100vw, 372px"> </a>
        </div>
    </div> -->



    <!-- قسم عملاؤنا -->
    <section id="clients-section" class="clients-section">
        <div class="container">
            <h3 class="section-title">OUR CLIENTS</h3>
            <h2 class="main-title">Trusted By</h2>
            <p class="description">
                We are proud to serve many clients across various sectors who trust our expertise and solutions.
            </p>
            <div class="clients-logos">
                <div class="client-logo">
                    <img src="img/clients-section/snibe.jpg" alt="Client 1">
                </div>
                <div class="client-logo">
                    <img src="img/clients-section/facebook.jpg" alt="Client 2">
                </div>
                <div class="client-logo">
                    <img src="img/clients-section/1.-MACB-Logo-e1661311662815.png" alt="Client 3">
                </div>
                <div class="client-logo">
                    <img src="img/clients-section/IMG-20240118-WA0093.jpg" alt="Client 4">
                </div>
                <div class="client-logo">
                    <img src="img/clients-section/download.png" alt="Client 5">
                </div>
            </div>
        </div>
    </section>




    <img src="img/s.png">

    <!-- قسم شركاؤنا -->
    <section id="partners-section" class="partners-section">
        <div class="container">
            <h3 class="section-title">OUR PARTNERS</h3>
            <h2 class="main-title">Quality & Trusted Partners</h2>
            <p class="description">
                Our partners are the backbone of our business, providing quality and innovative solutions that help us
                serve our clients better.
            </p>
            <div class="partners-logos">
                <div class="partner-logo">
                    <img src="img/partners-section/Logos2-94.png" alt="Partner 1">
                </div>
                <div class="partner-logo">
                    <img src="img/partners-section/Logos2-95.png" alt="Partner 2">
                </div>
                <div class="partner-logo">
                    <img src="img/partners-section/Logos2-96.png" alt="Partner 3">
                </div>
                <div class="partner-logo">
                    <img src="img/partners-section/Logos2-98.png" alt="Partner 4">
                </div>
                <div class="partner-logo">
                    <img src="img/partners-section/Logos2-99.png" alt="Partner 5">
                </div>
            </div>
        </div>
    </section>

    <!-- الفوتر (Footer) -->




    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h4>Contact Us</h4>
                <p>Headquarters</p>
                <p>Khartoum Sudan , Khartoum, Sudan, 11111</p>
                <p>Tel. +249 91 007 0078</p>

            </div>
            <div class="footer-column">
                <h4>About Us</h4>
                <p>Who We Are</p>
                <p>CEO’s Message</p>
                <p>Business Methodology</p>
            </div>
            <div class="footer-column">
                <h4>Quick Links</h4>
                <p>Our Solutions</p>
                <p>Our Partners</p>
                <p>Our Clients</p>
                <p>Careers</p>
            </div>
            <div class="footer-column">
                <h4>Support</h4>
                <p>Contact Us</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Terma Medical Supplies Co.Ltd
                © All Rights Reserved</p>
        </div>
    </footer>

</body>



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
                <?php if (!empty($cart_items)): ?>
                <ul class="list-group">
                    <?php foreach ($cart_items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($item['name']); ?>
                        <span
                            class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($item['quantity']); ?></span>
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
</script>
