<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = intval($_POST['request_id']);
    $status = $conn->real_escape_string($_POST['status']);

    // تحديث الحالة في قاعدة البيانات
    $update_query = "UPDATE maintenance_requests SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $status, $request_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "تم تحديث حالة الطلب بنجاح.";
    } else {
        $_SESSION['error_message'] = "حدث خطأ أثناء تحديث حالة الطلب.";
    }

    $stmt->close();
    $conn->close();

    header("Location: maintenance_requests.php");
    exit();
}
?>
