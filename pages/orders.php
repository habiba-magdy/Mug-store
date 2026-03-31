<?php
$pageTitle = 'طلباتي - ماجيك ماج';
require_once '../includes/header.php';

// التحقق من تسجيل الدخول
if (!isLoggedIn()) {
    showMessage('يجب تسجيل الدخول لعرض طلباتك', 'error');
    header('Location: login.php');
    exit;
}

// مصفوفة الصور اليدوية
$manualImages = [
    1 => '../img/black.jpeg',
    2 => '../img/white.jpeg',
    3 => '../img/colorfull.jpeg',
    4 => '../img/gold.jpeg',
    5 => '../img/print.jpeg',
    6 => '../img/heat.jpeg',
];

// جلب طلبات المستخدم
$orders = fetchAll("
    SELECT o.*, 
           COUNT(oi.id) as items_count,
           GROUP_CONCAT(oi.product_id) as product_ids
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
", [$_SESSION['user_id']]);

// دالة لتحويل حالة الطلب للعربية
function getStatusLabel($status) {
    $labels = [
        'pending' => ['قيد الانتظار', 'bg-yellow-100 text-yellow-800'],
        'processing' => ['جاري التجهيز', 'bg-blue-100 text-blue-800'],
        'shipped' => ['تم الشحن', 'bg-purple-100 text-purple-800'],
        'delivered' => ['تم التوصيل', 'bg-green-100 text-green-800'],
        'cancelled' => ['ملغي', 'bg-red-100 text-red-800']
    ];
    return $labels[$status] ?? ['غير معروف', 'bg-gray-100 text-gray-800'];
}
?>

<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8" data-aos="fade-up">
            <h1 class="text-3xl font-bold mb-2">طلباتي</h1>
            <p class="text-gray-600">تتبعي طلباتك وشاهدي حالتها</p>
        </div>

        <?php if (count($orders) > 0): ?>
        
        <!-- Orders List -->
        <div class="space-y-6">
            <?php foreach ($orders as $index => $order): 
                $status = getStatusLabel($order['status']);
                $productIds = explode(',', $order['product_ids']);
                $firstProductId = $productIds[0] ?? 1;
                $orderImage = $manualImages[$firstProductId] ?? '../assets/images/mugs/default.jpg';
            ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <!-- Order Header -->
                <div class="p-6 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden">
                            <img src="<?php echo $orderImage; ?>" alt="طلب #<?php echo $order['id']; ?>" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">طلب #<?php echo $order['id']; ?></h3>
                            <p class="text-gray-500 text-sm"><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <span class="px-4 py-2 rounded-full text-sm font-bold <?php echo $status[1]; ?>">
                            <?php echo $status[0]; ?>
                        </span>
                        <span class="text-xl font-bold gradient-text"><?php echo $order['total_amount']; ?> ج.م</span>
                    </div>
                </div>
                
                <!-- Order Details -->
                <div class="p-6">
                    <div class="grid md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <p class="text-gray-500 text-sm mb-1">رقم الطلب</p>
                            <p class="font-bold">#<?php echo $order['id']; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm mb-1">عدد المنتجات</p>
                            <p class="font-bold"><?php echo $order['items_count']; ?> منتج</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm mb-1">عنوان الشحن</p>
                            <p class="font-bold text-sm truncate"><?php echo $order['shipping_address']; ?></p>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mt-6">
                        <div class="flex justify-between text-xs text-gray-500 mb-2">
                            <span>تم الطلب</span>
                            <span>جاري التجهيز</span>
                            <span>تم الشحن</span>
                            <span>تم التوصيل</span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <?php 
                            $progress = [
                                'pending' => 25,
                                'processing' => 50,
                                'shipped' => 75,
                                'delivered' => 100
                            ];
                            $width = $progress[$order['status']] ?? 25;
                            ?>
                            <div class="h-full gradient-bg transition-all duration-1000" style="width: <?php echo $width; ?>%"></div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-6 flex gap-3">
                        <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="flex-1 bg-primary/10 text-primary py-3 rounded-xl font-bold text-center hover:bg-primary hover:text-white transition">
                            تفاصيل الطلب
                        </a>
                        <?php if ($order['status'] == 'pending'): ?>
                        <button onclick="cancelOrder(<?php echo $order['id']; ?>)" class="px-6 py-3 border border-red-500 text-red-500 rounded-xl font-bold hover:bg-red-50 transition">
                            إلغاء
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php else: ?>
        
        <!-- Empty State -->
        <div class="text-center py-20 bg-white rounded-2xl shadow-lg" data-aos="fade-up">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-bag text-4xl text-gray-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-600 mb-4">لا توجد طلبات</h2>
            <p class="text-gray-500 mb-6">لم تقومي بأي طلبات بعد</p>
            <a href="products.php" class="inline-block gradient-bg text-white px-8 py-3 rounded-full font-bold btn-glow">
                ابدأي التسوق
            </a>
        </div>
        
        <?php endif; ?>
    </div>
</section>

<script>
function cancelOrder(orderId) {
    if (confirm('هل أنتي متأكدة من إلغاء الطلب؟')) {
        window.location.href = 'cancel-order.php?id=' + orderId;
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>