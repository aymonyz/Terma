<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['add_to_cart']) && $data['add_to_cart'] === true) {
        $product_id = $data['product_id'];
        $product_name = $data['product_name'];
        $product_price = $data['product_price'];

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        

        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity'=> 1,
        ];

        echo json_encode([
            'success' => true,
            'message' => 'تمت إضافة المنتج إلى السلة.',
            'cart_count' => count($_SESSION['cart']),
            'cart'=>$_SESSION['cart'],
        ]);
        exit;
    }

    if (isset($data['remove_from_cart']) && $data['remove_from_cart'] === true) {
        $product_id = $data['product_id'];

        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $index => $product) {
                if ($product['id'] == $product_id) {
                    unset($_SESSION['cart'][$index]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']); // إعادة ترتيب الفهرس
                    break;
                }
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'تمت إزالة المنتج من السلة.',
            'cart_count' => count($_SESSION['cart']),
            
        ]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'success' => true,
        'cart' => $_SESSION['cart'] ?? [],
    ]);
    exit;
}

// في حالة وجود خطأ
echo json_encode(['success' => false, 'message' => 'طلب غير صالح.']);
