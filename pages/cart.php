<?php
$pageTitle = 'سلة المشتريات - ماجيك ماج';
require_once '../includes/header.php';

// مصفوفة الصور اليدوية
$manualImages = [
    1 => '../img/black.jpeg',
    2 => '../img/white.jpeg',
    3 => '../img/colorfull.jpeg',
    4 => '../img/gold.jpeg',
    5 => '../img/print.jpeg',
    6 => '../img/heat.jpeg',
];

// بيانات المنتجات يدوياً
$manualProducts = [
    1 => ['name' => 'مج أسود كلاسيكي', 'price' => 75, 'stock' => 10],
    2 => ['name' => 'مج أبيض أنيق', 'price' => 65, 'stock' => 15],
    3 => ['name' => 'مج بألوان متدرجة', 'price' => 95, 'stock' => 8],
    4 => ['name' => 'مج ذهبي فاخر', 'price' => 120, 'stock' => 5],
    5 => ['name' => 'مج مطبوع', 'price' => 85, 'stock' => 12],
    6 => ['name' => 'مج سحري', 'price' => 110, 'stock' => 7],
];

// دالة مساعدة للـ redirect
function redirect($url) {
    if (!headers_sent()) {
        header('Location: ' . $url);
    } else {
        echo '<script>window.location.href = "' . $url . '";</script>';
    }
    exit;
}

// معالجة إضافة للسلة
if (isset($_POST['add_to_cart'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if (isLoggedIn()) {
        // للمستخدم المسجل - حفظ في قاعدة البيانات
        $existing = fetchOne("SELECT * FROM cart WHERE user_id = ? AND product_id = ?", 
            [$_SESSION['user_id'], $productId]);
        
        if ($existing) {
            query("UPDATE cart SET quantity = quantity + ? WHERE id = ?", 
                [$quantity, $existing['id']]);
        } else {
            query("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)", 
                [$_SESSION['user_id'], $productId, $quantity]);
        }
    } else {
        // للزوار - حفظ في السيشن
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }
    
    showMessage('تم إضافة المنتج للسلة بنجاح!');
    redirect($_SERVER['HTTP_REFERER'] ?? 'products.php');
}

// معالجة تحديث الكمية (من زر التحديث)
if (isset($_POST['update_cart'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    if ($quantity < 1) $quantity = 1;
    
    if (isLoggedIn()) {
        if ($quantity > 0) {
            query("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?", 
                [$quantity, $_SESSION['user_id'], $productId]);
        }
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
    
    redirect('cart.php');
}

// معالجة الحذف
if (isset($_GET['remove'])) {
    $productId = intval($_GET['remove']);
    
    if (isLoggedIn()) {
        query("DELETE FROM cart WHERE user_id = ? AND product_id = ?", 
            [$_SESSION['user_id'], $productId]);
    } else {
        unset($_SESSION['cart'][$productId]);
    }
    
    redirect('cart.php');
}

// معالجة تفريغ السلة
if (isset($_GET['clear'])) {
    if (isLoggedIn()) {
        query("DELETE FROM cart WHERE user_id = ?", [$_SESSION['user_id']]);
    } else {
        $_SESSION['cart'] = [];
    }
    
    redirect('cart.php');
}

// جلب محتويات السلة
$cartItems = [];
$total = 0;

if (isLoggedIn()) {
    $dbItems = fetchAll("
        SELECT c.*, p.name, p.price, p.stock, p.id as pid
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ", [$_SESSION['user_id']]);
    
    foreach ($dbItems as $item) {
        $item['image'] = $manualImages[$item['pid']] ?? '../assets/images/mugs/default.jpg';
        $cartItems[] = $item;
    }
} elseif (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $quantity) {
        if (isset($manualProducts[$id])) {
            $cartItems[] = [
                'id' => $id,
                'product_id' => $id,
                'name' => $manualProducts[$id]['name'],
                'price' => $manualProducts[$id]['price'],
                'image' => $manualImages[$id] ?? '../assets/images/mugs/default.jpg',
                'quantity' => $quantity,
                'stock' => $manualProducts[$id]['stock']
            ];
        }
    }
}

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold" data-aos="fade-up">سلة المشتريات</h1>
            <?php if (count($cartItems) > 0): ?>
            <a href="?clear=1" class="text-red-500 hover:text-red-600 font-semibold" onclick="return confirm('هل أنت متأكد من تفريغ السلة؟')">
                <i class="fas fa-trash-alt ml-1"></i> تفريغ السلة
            </a>
            <?php endif; ?>
        </div>

        <?php if (count($cartItems) > 0): ?>
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4" data-aos="fade-up">
                <?php foreach ($cartItems as $item): ?>
                <div class="bg-white rounded-2xl shadow-lg p-6 flex gap-4" id="item-<?php echo $item['product_id']; ?>">
                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="w-24 h-24 object-cover rounded-xl">
                    
                    <div class="flex-1">
                        <h3 class="font-bold text-lg mb-2"><?php echo $item['name']; ?></h3>
                        <p class="gradient-text font-bold text-xl mb-2"><?php echo $item['price']; ?> ج.م</p>
                        
                        <form method="POST" class="flex items-center gap-4" id="form-<?php echo $item['product_id']; ?>">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            
                            <!-- أزرار +/- -->
                            <div class="flex items-center border border-gray-300 rounded-lg bg-white">
                                <button type="button" 
                                        onclick="updateQuantity(<?php echo $item['product_id']; ?>, -1, <?php echo $item['stock']; ?>)"
                                        class="px-4 py-2 hover:bg-gray-100 text-lg font-bold text-gray-600 transition">
                                    -
                                </button>
                                
                                <input type="number" 
                                       name="quantity" 
                                       id="qty-<?php echo $item['product_id']; ?>"
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" 
                                       max="<?php echo $item['stock']; ?>" 
                                       class="w-16 text-center border-none text-lg font-bold focus:outline-none"
                                       readonly>
                                
                                <button type="button" 
                                        onclick="updateQuantity(<?php echo $item['product_id']; ?>, 1, <?php echo $item['stock']; ?>)"
                                        class="px-4 py-2 hover:bg-gray-100 text-lg font-bold text-gray-600 transition">
                                    +
                                </button>
                            </div>
                            
                            <!-- زر التحديث المخفي -->
                            <button type="submit" name="update_cart" id="submit-<?php echo $item['product_id']; ?>" class="hidden">
                                تحديث
                            </button>
                            
                            <!-- السعر الإجمالي -->
                            <span class="font-bold text-lg mr-auto">
                                <span id="total-<?php echo $item['product_id']; ?>">
                                    <?php echo $item['price'] * $item['quantity']; ?>
                                </span> ج.م
                            </span>
                        </form>
                    </div>
                    
                    <!-- زر الحذف -->
                    <div class="flex flex-col justify-between items-end">
                        <a href="?remove=<?php echo $item['product_id']; ?>" 
                           class="text-red-500 hover:text-red-600 hover:bg-red-50 p-2 rounded-full transition"
                           onclick="return confirm('هل تريد حذف هذا المنتج؟')">
                            <i class="fas fa-trash text-lg"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <a href="products.php" class="inline-flex items-center text-primary hover:text-secondary font-semibold mt-4">
                    <i class="fas fa-arrow-right ml-2"></i> مواصلة التسوق
                </a>
            </div>

            <!-- Summary -->
            <div data-aos="fade-left">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-6">ملخص الطلب</h2>
                    
                    <div class="space-y-3 mb-6 text-gray-600">
                        <?php foreach ($cartItems as $item): ?>
                        <div class="flex justify-between text-sm">
                            <span><?php echo $item['name']; ?> (<?php echo $item['quantity']; ?>x)</span>
                            <span><?php echo $item['price'] * $item['quantity']; ?> ج.م</span>
                        </div>
                        <?php endforeach; ?>
                        
                        <hr class="my-3">
                        
                        <div class="flex justify-between">
                            <span>المجموع الفرعي</span>
                            <span id="subtotal"><?php echo $total; ?> ج.م</span>
                        </div>
                        <div class="flex justify-between">
                            <span>الشحن</span>
                            <span class="text-green-600">مجاني</span>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between text-xl font-bold">
                            <span>الإجمالي</span>
                            <span class="gradient-text" id="grand-total"><?php echo $total; ?> ج.م</span>
                        </div>
                    </div>
                    
                    <a href="checkout.php" class="block w-full gradient-bg text-white text-center py-4 rounded-xl font-bold btn-glow text-lg">
                        إتمام الطلب
                    </a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="text-center py-20 bg-white rounded-2xl shadow-lg" data-aos="fade-up">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-600 mb-4">السلة فارغة</h2>
            <p class="text-gray-500 mb-6">لم تقم بإضافة أي منتجات للسلة بعد</p>
            <a href="products.php" class="inline-block gradient-bg text-white px-8 py-3 rounded-full font-bold btn-glow">
                تصفح المنتجات
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
// تحديث الكمية تلقائياً
function updateQuantity(productId, change, maxStock) {
    const input = document.getElementById('qty-' + productId);
    let newValue = parseInt(input.value) + change;
    
    // التحقق من الحدود
    if (newValue < 1) newValue = 1;
    if (newValue > maxStock) {
        alert('الكمية المطلوبة غير متوفرة في المخزن');
        return;
    }
    
    // تحديث القيمة
    input.value = newValue;
    
    // إرسال النموذج تلقائياً
    document.getElementById('submit-' + productId).click();
}

// تحديث الأسعار بدون تحميل الصفحة (اختياري)
document.querySelectorAll('input[name="quantity"]').forEach(input => {
    input.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>