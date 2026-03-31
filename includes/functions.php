<?php
// includes/functions.php

session_start();

// دالة التحقق من تسجيل الدخول
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// دالة جلب معلومات المستخدم
function getUser() {
    if (isLoggedIn()) {
        return fetchOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
    }
    return null;
}

// دالة إضافة للسلة
function addToCart($productId, $quantity = 1) {
    if (isLoggedIn()) {
        // حفظ في قاعدة البيانات للمستخدم المسجل
        $existing = fetchOne(
            "SELECT * FROM cart WHERE user_id = ? AND product_id = ?", 
            [$_SESSION['user_id'], $productId]
        );
        
        if ($existing) {
            query(
                "UPDATE cart SET quantity = quantity + ? WHERE id = ?", 
                [$quantity, $existing['id']]
            );
        } else {
            query(
                "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)", 
                [$_SESSION['user_id'], $productId, $quantity]
            );
        }
    } else {
        // حفظ في السيشن للزوار
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }
}

// دالة حساب عدد عناصر السلة
function getCartCount() {
    $count = 0;
    if (isLoggedIn()) {
        $result = fetchOne("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?", [$_SESSION['user_id']]);
        $count = $result['total'] ?? 0;
    } elseif (isset($_SESSION['cart'])) {
        $count = array_sum($_SESSION['cart']);
    }
    return $count;
}

// دالة حساب السعر الإجمالي
function calculateTotal($cartItems) {
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// دالة تنظيف المدخلات
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// دالة إظهار رسالة
function showMessage($message, $type = 'success') {
    $_SESSION['message'] = ['text' => $message, 'type' => $type];
}

// دالة جلب رسالة
function getMessage() {
    if (isset($_SESSION['message'])) {
        $msg = $_SESSION['message'];
        unset($_SESSION['message']);
        return $msg;
    }
    return null;
}

// function redirect($url) {
//     if (!headers_sent()) {
//         header('Location: ' . $url);
//     } else {
//         echo '<script>window.location.href = "' . $url . '";</script>';
//     }
//     exit;
// }
?>