<?php
$pageTitle = 'تأكيد الطلب - ماجيك ماج';
require_once '../includes/header.php';

// مصفوفة الصور اليدوية
$manualImages = [
    1 => '../img/WhatsApp Image 2026-03-30 at 6.30.49 PM.jpeg',
    2 => '../img/صورة-ثانية.jpeg',
    3 => '../img/صورة-ثالثة.jpeg',
    4 => '../img/صورة-رابعة.jpeg',
    5 => '../img/صورة-خامسة.jpeg',
    6 => '../img/صورة-سادسة.jpeg',
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

// التحقق من تسجيل الدخول
if (!isLoggedIn()) {
    showMessage('يجب تسجيل الدخول لإتمام الطلب', 'error');
    redirect('login.php');
}

// ============================================
// جلب محتويات السلة أولاً (قبل أي حاجة)
// ============================================
$cartItems = [];

if (isLoggedIn()) {
    $dbItems = fetchAll("
        SELECT c.*, p.name, p.price, p.stock, p.id as pid
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ", [$_SESSION['user_id']]);
    
    // ربط الصور اليدوية
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

// ============================================
// حساب الإجمالي (قبل معالجة الطلب)
// ============================================
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// ============================================
// التحقق من السلة فارغة
// ============================================
if (count($cartItems) == 0) {
    redirect('cart.php');
}

$user = getUser();

// ============================================
// معالجة إرسال الطلب (بعد ما $total اتحسب)
// ============================================
if (isset($_POST['place_order'])) {
    $address = clean($_POST['address']);
    $phone = clean($_POST['phone']);
    
    // التحقق من البيانات
    if (empty($address) || empty($phone)) {
        showMessage('يرجى ملء جميع الحقول المطلوبة', 'error');
        redirect('checkout.php');
    }
    
    // إنشاء الطلب
    query("INSERT INTO orders (user_id, total_amount, shipping_address, phone, status) 
           VALUES (?, ?, ?, ?, 'pending')", 
          [$_SESSION['user_id'], $total, $address, $phone]);
    
    $orderId = $pdo->lastInsertId();
    
    // إضافة تفاصيل الطلب
    foreach ($cartItems as $item) {
        query("INSERT INTO order_items (order_id, product_id, quantity, price) 
               VALUES (?, ?, ?, ?)",
              [$orderId, $item['product_id'], $item['quantity'], $item['price']]);
        
        // تحديث المخزون
        query("UPDATE products SET stock = stock - ? WHERE id = ?", 
              [$item['quantity'], $item['product_id']]);
    }
    
    // تفريغ السلة
    query("DELETE FROM cart WHERE user_id = ?", [$_SESSION['user_id']]);
    
    showMessage('تم إرسال طلبك بنجاح! رقم الطلب: #' . $orderId);
    redirect('home.php');
}
?>
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8" data-aos="fade-up">تأكيد الطلب</h1>

        <form method="POST" class="grid lg:grid-cols-2 gap-8">
            <!-- Shipping Info -->
            <div data-aos="fade-right">
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-6 flex items-center">
                        <span class="w-8 h-8 gradient-bg rounded-full flex items-center justify-center text-white text-sm ml-3">1</span>
                        معلومات الشحن
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">الاسم الكامل</label>
                            <input type="text" value="<?php echo $user['full_name'] ?? ''; ?>" readonly
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">رقم الهاتف *</label>
                            <input type="tel" name="phone" value="<?php echo $user['phone'] ?? ''; ?>" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary"
                                placeholder="01xxxxxxxxx">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">عنوان الشحن *</label>
                            <textarea name="address" rows="3" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary"
                                placeholder="المدينة، الحي، الشارع، رقم المنزل..."><?php echo $user['address'] ?? ''; ?></textarea>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">ملاحظات إضافية</label>
                            <textarea name="notes" rows="2"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary"
                                placeholder="أي تعليمات خاصة بالتوصيل..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-6 flex items-center">
                        <span class="w-8 h-8 gradient-bg rounded-full flex items-center justify-center text-white text-sm ml-3">2</span>
                        طريقة الدفع
                    </h2>

                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-primary rounded-xl cursor-pointer bg-primary/5 transition hover:shadow-md">
                            <input type="radio" name="payment" value="cod" checked class="ml-3 w-5 h-5 text-primary">
                            <div class="flex-1 mr-3">
                                <div class="font-bold text-lg">الدفع عند الاستلام</div>
                                <div class="text-sm text-gray-500">ادفع نقداً عند استلام طلبك</div>
                            </div>
                            <i class="fas fa-money-bill-wave text-3xl text-primary"></i>
                        </label>

                        <!-- بطاقة ائتمانية -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-primary transition">
                            <input type="radio" name="payment" value="card" class="ml-3 w-5 h-5 text-primary">
                            <div class="flex-1 mr-3">
                                <div class="font-bold text-lg">بطاقة ائتمانية</div>
                                <div class="text-sm text-gray-500">Visa, MasterCard, Meeza</div>
                            </div>
                            <i class="fab fa-cc-visa text-3xl text-primary ml-1"></i>
                            <i class="fab fa-cc-mastercard text-3xl text-primary ml-1"></i>
                        </label>

                        <!-- محفظة إلكترونية -->
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-primary transition">
                            <input type="radio" name="payment" value="wallet" class="ml-3 w-5 h-5 text-primary">
                            <div class="flex-1 mr-3">
                                <div class="font-bold text-lg">محفظة إلكترونية</div>
                                <div class="text-sm text-gray-500">Vodafone Cash, Etisalat Cash, Orange Money</div>
                            </div>
                            <i class="fas fa-mobile-alt text-3xl text-primary"></i>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div data-aos="fade-left">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-6 flex items-center">
                        <span class="w-8 h-8 gradient-bg rounded-full flex items-center justify-center text-white text-sm ml-3">3</span>
                        ملخص الطلب
                    </h2>

                    <div class="space-y-4 mb-6 max-h-80 overflow-y-auto pr-2">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="flex gap-4 pb-4 border-b border-gray-100 last:border-0">
                                <div class="relative">
                                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="w-20 h-20 object-cover rounded-xl">
                                    <span class="absolute -top-2 -right-2 w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold">
                                        <?php echo $item['quantity']; ?>
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm mb-1"><?php echo $item['name']; ?></h4>
                                    <p class="text-gray-500 text-xs mb-1"><?php echo $item['price']; ?> ج.م / قطعة</p>
                                    <p class="text-primary font-bold"><?php echo $item['quantity'] * $item['price']; ?> ج.م</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 mb-6 space-y-3">
                        <div class="flex justify-between text-gray-600">
                            <span>المجموع الفرعي</span>
                            <span><?php echo $total; ?> ج.م</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>الشحن</span>
                            <span class="text-green-600 font-bold">مجاني</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>الضريبة (14%)</span>
                            <span>شامل</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 flex justify-between text-xl font-bold text-gray-800">
                            <span>الإجمالي</span>
                            <span class="gradient-text"><?php echo $total; ?> ج.م</span>
                        </div>
                    </div>

                    <button type="submit" name="place_order" class="w-full gradient-bg text-white py-4 rounded-xl font-bold btn-glow text-lg flex items-center justify-center gap-2 hover:shadow-xl transition">
                        <i class="fas fa-check-circle"></i>
                        تأكيد الطلب
                    </button>

                    <div class="mt-4 flex items-center justify-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-shield-alt text-green-500"></i>
                        <span>طلبك آمن ومشفر</span>
                    </div>

                    <a href="cart.php" class="block text-center mt-4 text-gray-500 hover:text-primary font-semibold transition">
                        <i class="fas fa-arrow-right ml-1"></i> العودة للسلة
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>