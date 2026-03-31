<?php
require_once '../includes/header.php';

if (!isLoggedIn() || !isset($_GET['id'])) {
    header('Location: orders.php');
    exit;
}

$orderId = intval($_GET['id']);

// التحقق من أن الطلب للمستخدم وقيد الانتظار
$order = fetchOne("
    SELECT * FROM orders 
    WHERE id = ? AND user_id = ? AND status = 'pending'
", [$orderId, $_SESSION['user_id']]);

if ($order) {
    // إلغاء الطلب
    query("UPDATE orders SET status = 'cancelled' WHERE id = ?", [$orderId]);
    showMessage('تم إلغاء الطلب بنجاح');
} else {
    showMessage('لا يمكن إلغاء هذا الطلب', 'error');
}

header('Location: orders.php');
exit;