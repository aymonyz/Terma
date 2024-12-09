<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <!-- Custom Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* التمحور في الأعلى */
            height: 100vh;
            padding-top: 50px; /* إضافة مسافة من الأعلى */
            color: #ffffff;
        }
        .link-control {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 90%;
            max-width: 500px;
        }
        .link-control h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
        }
        .link-control a {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #ffffff;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            padding: 15px 25px;
            margin: 15px 0;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .link-control a:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #2575fc, #6a11cb);
        }
        .link-control a i {
            margin-right: 10px;
            font-size: 20px;
        }
        .link-control a span {
            text-align: center;
        }
                
        .link-control a {
            text-decoration: none;
            color: white;
            background: #0d6efd;
            padding: 8px 10px;
            border-radius: 18px;
        }
    </style>
</head>
<body>
    <div class="link-control">
        <h1>لوحة التحكم</h1>
        <a href="profail.php"><i class="fas fa-user"></i> <span>الملف الشخصي</span></a>
        <a href="rest_password.php"><i class="fas fa-lock"></i> <span>تغيير كلمة السر</span></a>
        <a href="orders.php"><i class="fas fa-clipboard-list"></i> <span>الطلبات</span></a>
        <a href="../index.php"><i class="fas fa-home"></i> <span>الرئيسة</span></a>
    </div>
</body>
</html>
