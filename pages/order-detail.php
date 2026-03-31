<?php
$pageTitle = 'تفاصيل الطلب - ماجيك ماج';
require_once '../includes/header.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$orderId = intval($_GET['id'] ?? 0);

// جلب تفاصيل الطلب
$order = fetchOne("
    SELECT o.* 
    FROM orders o 
    WHERE o.id = ? AND o.user_id = ?
", [$orderId, $_SESSION['user_id']]);

if (!$order) {
    showMessage('الطلب غير موجود', 'error');
    header('Location: orders.php');
    exit;
}

// جلب منتجات الطلب
$items = fetchAll("
    SELECT oi.*, p.name, p.id as pid
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
", [$orderId]);

// مصفوفة الصور
$manualImages = [
    1 => '../img/black.jpeg',
    2 => '../img/white.jpeg',
    3 => '../img/colorfull.jpeg',
    4 => '../img/gold.jpeg',
    5 => '../img/print.jpeg',
    6 => '../img/heat.jpeg',
];

function getStatusLabel($status) {
    $labels = [
        'pending' => ['قيد الانتظار', 'bg-yellow-100 text-yellow-800'],
        'processing' => ['جاري التجهيز', 'bg-blue-100 text-blue-800'],
        'shipped' => ['تم الشحن', 'bg-purple-100 text-purple-800'],
        'delivered' => ['تم التوصيل', 'bg-green-100 text-green-800'],
    ];
    return $labels[$status] ?? ['غير معروف', 'bg-gray-100 text-gray-800'];
}

$status = getStatusLabel($order['status']);
?>

<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back -->
        <a href="orders.php" class="inline-flex items-center text-gray-600 hover:text-primary mb-6">
            <i class="fas fa-arrow-right ml-2"></i> العودة للطلبات
        </a>
        
        <!-- Order Info -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-6" data-aos="fade-up">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold mb-2">طلب #<?php echo $order['id']; ?></h1>
                    <p class="text-gray-500"><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-bold <?php echo $status[1]; ?>">
                    <?php echo $status[0]; ?>
                </span>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-bold text-gray-700 mb-2">عنوان الشحن</h3>
                    <p class="text-gray-600"><?php echo nl2br($order['shipping_address']); ?></p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-700 mb-2">رقم الهاتف</h3>
                    <p class="text-gray-600"><?php echo $order['phone']; ?></p>
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-6" data-aos="fade-up">
            <h2 class="text-xl font-bold mb-6">المنتجات</h2>
            
            <div class="space-y-4">
                <?php foreach ($items as $item): 
                    $itemImage = $manualImages[$item['pid']] ?? '../assets/images/mugs/default.jpg';
                ?>
                <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                    <img src="<?php echo $itemImage; ?>" class="w-20 h-20 object-cover rounded-lg">
                    <div class="flex-1">
                        <h3 class="font-bold"><?php echo $item['name']; ?></h3>
                        <p class="text-gray-500 text-sm"><?php echo $item['quantity']; ?> × <?php echo $item['price']; ?> ج.م</p>
                    </div>
                    <div class="font-bold gradient-text">
                        <?php echo $item['quantity'] * $item['price']; ?> ج.م
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="border-t mt-6 pt-6">
                <div class="flex justify-between text-xl font-bold">
                    <span>الإجمالي</span>
                    <span class="gradient-text"><?php echo $order['total_amount']; ?> ج.م</span>
                </div>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="bg-white rounded-2xl shadow-lg p-8" data-aos="fade-up">
            <h2 class="text-xl font-bold mb-6">متابعة الطلب</h2>
            
            <div class="relative">
                <div class="absolute right-8 top-0 bottom-0 w-1 bg-gray-200"></div>
                
                <?php 
                $steps = [
                    ['pending', 'تم الطلب', 'تم استلام طلبك بنجاح'],
                    ['processing', 'جاري التجهيز', 'نقوم بتجهيز منتجاتك الآن'],
                    ['shipped', 'تم الشحن', 'طلبك في الطريق إليك'],
                    ['delivered', 'تم التوصيل', 'تم توصيل طلبك بنجاح'],
                ];
                
                $currentStep = array_search($order['status'], array_column($steps, 0));
                ?>
                
                <?php foreach ($steps as $index => $step): 
                    $isActive = $index <= $currentStep;
                    $isCurrent = $index == $currentStep;
                ?>
                <div class="relative flex items-center gap-4 mb-8 last:mb-0">
                    <div class="w-16 flex justify-center">
                        <div class="w-4 h-4 rounded-full <?php echo $isActive ? 'gradient-bg' : 'bg-gray-300'; ?> relative z-10">
                            <?php if ($isCurrent): ?>
                            <div class="absolute inset-0 rounded-full gradient-bg animate-ping"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="<?php echo $isActive ? 'text-gray-800' : 'text-gray-400'; ?>">
                        <h4 class="font-bold"><?php echo $step[1]; ?></h4>
                        <p class="text-sm"><?php echo $step[2]; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>