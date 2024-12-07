<?php
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
echo '<pre>';
echo '</pre>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Homepage</title>
    <!-- الخطوط -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- استدعاء ملف CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- الهيدر مع الفيديو كخلفية -->
 <!-- الهيدر مع الفيديو كخلفية -->
<header class="video-header">

    <!-- فيديو الخلفية -->
    <video autoplay loop muted playsinline class="background-video">
        <source src="vido/Comp-1.mp4" type="video/mp4">
    </video>

        <!-- محتوى الهيدر -->
        <div class="header-content">
            <!-- القائمة العلوية -->
            <nav>
                <ul class="nav-list">
                    <li>About us</li>
                    <li>Solutions</li>
                    <li>Partners</li>
                    <li>Our Clients</li>
                    <li>Contact</li>
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
 
<section id="about-section" class="about-section">
    <div class="container">
        <h3 class="section-title">KNOW US</h3>
        <h2 class="main-title">Who We Are</h2>
        <p class="description">
            Terma Medical is the leading healthcare solutions provider in Khartoum Sudan. Over the years, we have partnered with the world’s most renowned healthcare companies that offer the best-in-class solutions and finest technology. Through ongoing and sustainable improvements, we can provide solutions that generate significant value for healthcare providers and their patients.
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

    <!-- قسم الفيديو (Hero Section) -->
     

  

   

    <!-- قسم الحلول -->
    <div id="section1" class="section">
        <h2>Our Solutions</h2>
        <div class="grid">
            <div class="card">Medical Supplies</div>
            <div class="card">Dental Solutions</div>
            <div class="card">Diagnostic Solutions</div>
            <div class="card">Medical Equipment</div>
        </div>
    </div>




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
            Our partners are the backbone of our business, providing quality and innovative solutions that help us serve our clients better.
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

   

    <footer id ="footer" class="footer">
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

</html>
