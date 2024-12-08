<?php
// إعدادات الاتصال بقاعدة البيانات
$servername = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = "terma";

// إنشاء اتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من وجود أخطاء في الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// تعيين مجموعة الأحرف إلى utf8
$conn->set_charset("utf8");

?>
