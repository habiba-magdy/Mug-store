<?php
$pageTitle = 'تفاصيل المنتج - ماجيك ماج';
require_once '../includes/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// مصفوفة الصور اليدوية (نفس الصور اللي في الهوم)
$manualImages = [
    1 => '../img/black.jpeg',  // منتج 1
    2 => '../img/white.jpeg',                            
    3 => '../img/colorfull.jpeg',                               
    4 => '../img/gold.jpeg',                               
    5 => '../img/print.jpeg',                               
    6 => '../img/heat.jpeg',                              
];

// بيانات المنتجات يدوياً (مؤقتاً لحد ما تشتغلي الداتا بيز)
$manualProducts = [
    1 => ['name' => 'مج أسود كلاسيكي', 'price' => 75, 'category' => 'classic', 'stock' => 10, 'description' => 'مج سيراميك عالي الجودة باللون الأسود، مثالي للقهوة والشاي'],
    2 => ['name' => 'مج أبيض أنيق', 'price' => 65, 'category' => 'classic', 'stock' => 15, 'description' => 'مج أبيض ناصع بتصميم عصري يناسب جميع الأذواق'],
    3 => ['name' => 'مج بألوان متدرجة', 'price' => 95, 'category' => 'modern', 'stock' => 8, 'description' => 'مج بتصميم جريديانت ألوان موف وأزرق فاتح'],
    4 => ['name' => 'مج ذهبي فاخر', 'price' => 120, 'category' => 'luxury', 'stock' => 5, 'description' => 'مج بطلاء ذهبي أنيق للمناسبات الخاصة'],
    5 => ['name' => 'مج مطبوع', 'price' => 85, 'category' => 'custom', 'stock' => 12, 'description' => 'مج يمكن طباعة الصور والتصاميم عليه'],
    6 => ['name' => 'مج سحري', 'price' => 110, 'category' => 'special', 'stock' => 7, 'description' => 'مج يتغير لونه عند سكب المشروب الساخن'],
];

// استخدام البيانات اليدوية لو موجودة، لو لا استخدم الداتا بيز
if (isset($manualProducts[$id])) {
    $product = $manualProducts[$id];
    $product['id'] = $id;
    $imagePath = $manualImages[$id] ?? '../assets/images/mugs/default.jpg';
} else {
    // الرجوع للداتا بيز لو الـ ID مش في القائمة اليدوية
    $product = fetchOne("SELECT * FROM products WHERE id = ?", [$id]);
    $imagePath = '../assets/images/mugs/' . ($product['image'] ?? 'default.jpg');
}

if (!$product) {
    header('Location: products.php');
    exit;
}

// باقي الكود يفضل زي ما هو...
$reviews = fetchAll("
    SELECT r.*, u.full_name 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.product_id = ? 
    ORDER BY r.created_at DESC
", [$id]);

$avgRating = fetchOne("SELECT AVG(rating) as avg FROM reviews WHERE product_id = ?", [$id]);
$avgRating = round($avgRating['avg'] ?? 0, 1);

// منتجات مشابهة (نفس الفئة)
$related = array_filter($manualProducts, function($p, $key) use ($id, $product) {
    return $key != $id && $p['category'] == $product['category'];
}, ARRAY_FILTER_USE_BOTH);
?>

<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8 text-sm text-gray-600">
            <a href="home.php" class="hover:text-primary">الرئيسية</a>
            <i class="fas fa-chevron-left mx-2 text-xs"></i>
            <a href="products.php" class="hover:text-primary">المنتجات</a>
            <i class="fas fa-chevron-left mx-2 text-xs"></i>
            <span class="text-primary"><?php echo $product['name']; ?></span>
        </nav>

        <div class="grid md:grid-cols-2 gap-12 bg-white rounded-3xl shadow-xl p-8">
            <!-- Image -->
            <div data-aos="fade-right">
                <div class="relative rounded-2xl overflow-hidden bg-gray-100">
                    <!-- هنا الصورة اليدوية -->
                    <img src="<?php echo $imagePath; ?>" alt="<?php echo $product['name']; ?>" 
                         class="w-full h-96 object-cover">
                    
                    <?php if ($product['stock'] < 10): ?>
                    <div class="absolute top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-full font-bold">
                        متبقي <?php echo $product['stock']; ?> فقط!
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Details -->
            <div data-aos="fade-left">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-sm"><?php echo $product['category']; ?></span>
                    <div class="flex text-yellow-400 text-sm">
                        <?php for($i=1; $i<=5; $i++): ?>
                        <i class="fas fa-star <?php echo $i <= $avgRating ? '' : 'text-gray-300'; ?>"></i>
                        <?php endfor; ?>
                        <span class="text-gray-500 mr-2">(<?php echo count($reviews); ?> تقييم)</span>
                    </div>
                </div>

                <h1 class="text-4xl font-bold mb-4"><?php echo $product['name']; ?></h1>
                <p class="text-3xl font-bold gradient-text mb-6"><?php echo $product['price']; ?> ج.م</p>
                
                <p class="text-gray-600 mb-8 leading-relaxed"><?php echo $product['description']; ?></p>

                <!-- Add to Cart -->
                <form method="POST" action="cart.php" class="flex gap-4 mb-8">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="flex items-center border border-gray-300 rounded-xl">
                        <button type="button" onclick="this.parentElement.querySelector('input').value > 1 && this.parentElement.querySelector('input').value--" class="px-4 py-3 hover:bg-gray-100">-</button>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="w-16 text-center border-none focus:outline-none">
                        <button type="button" onclick="this.parentElement.querySelector('input').value < <?php echo $product['stock']; ?> && this.parentElement.querySelector('input').value++" class="px-4 py-3 hover:bg-gray-100">+</button>
                    </div>
                    
                    <button type="submit" name="add_to_cart" class="flex-1 gradient-bg text-white py-4 rounded-xl font-bold btn-glow text-lg">
                        <i class="fas fa-cart-plus ml-2"></i> أضف للسلة
                    </button>
                </form>

                <!-- Features -->
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 ml-2"></i>
                        متوفر في المخزن (<?php echo $product['stock']; ?> قطعة)
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-truck text-primary ml-2"></i>
                        شحن مجاني للطلبات +500 ج.م
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-primary ml-2"></i>
                        ضمان سنة كاملة
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-undo text-primary ml-2"></i>
                        إرجاع خلال 14 يوم
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-12 bg-white rounded-3xl shadow-xl p-8">
            <h2 class="text-2xl font-bold mb-8">آراء العملاء</h2>
            
            <?php if (isLoggedIn()): ?>
            <form method="POST" action="reviews.php" class="mb-8 p-6 bg-gray-50 rounded-2xl">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <h3 class="font-bold mb-4">أضف تقييمك</h3>
                <div class="flex gap-2 mb-4">
                    <?php for($i=1; $i<=5; $i++): ?>
                    <label class="cursor-pointer text-2xl text-gray-300 hover:text-yellow-400 transition star-rating">
                        <input type="radio" name="rating" value="<?php echo $i; ?>" class="hidden">
                        <i class="fas fa-star"></i>
                    </label>
                    <?php endfor; ?>
                </div>
                <textarea name="comment" rows="3" placeholder="اكتب رأيك في المنتج..." class="w-full p-4 border border-gray-200 rounded-xl mb-4 focus:outline-none focus:border-primary"></textarea>
                <button type="submit" name="submit_review" class="gradient-bg text-white px-6 py-3 rounded-xl font-bold">إرسال التقييم</button>
            </form>
            <?php else: ?>
            <div class="mb-8 p-6 bg-gray-50 rounded-2xl text-center">
                <p class="text-gray-600">سجل دخولك لإضافة تقييم</p>
                <a href="login.php" class="text-primary font-bold hover:underline">تسجيل الدخول</a>
            </div>
            <?php endif; ?>

            <div class="space-y-6">
                <?php foreach ($reviews as $review): ?>
                <div class="border-b border-gray-100 pb-6">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center">
                            <div class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center text-white font-bold ml-3">
                                <?php echo substr($review['full_name'], 0, 1); ?>
                            </div>
                            <div>
                                <div class="font-bold"><?php echo $review['full_name']; ?></div>
                                <div class="text-xs text-gray-500"><?php echo date('Y-m-d', strtotime($review['created_at'])); ?></div>
                            </div>
                        </div>
                        <div class="flex text-yellow-400">
                            <?php for($i=1; $i<=5; $i++): ?>
                            <i class="fas fa-star <?php echo $i <= $review['rating'] ? '' : 'text-gray-300'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p class="text-gray-600 mr-13"><?php echo $review['comment']; ?></p>
                </div>
                <?php endforeach; ?>
                
                <?php if (count($reviews) == 0): ?>
                <div class="text-center py-8 text-gray-500">
                    لا توجد تقييمات بعد. كن أول من يقيّم هذا المنتج!
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (count($related) > 0): ?>
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">منتجات مشابهة</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php 
                $relatedIds = array_keys($related);
                foreach (array_slice($relatedIds, 0, 4) as $relatedId): 
                    $relatedProduct = $manualProducts[$relatedId];
                    $relatedImage = $manualImages[$relatedId];
                ?>
                <a href="product-detail.php?id=<?php echo $relatedId; ?>" class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                    <img src="<?php echo $relatedImage; ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-bold mb-2"><?php echo $relatedProduct['name']; ?></h3>
                        <p class="gradient-text font-bold"><?php echo $relatedProduct['price']; ?> ج.م</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
// Star rating interaction
document.querySelectorAll('.star-rating').forEach((star, index) => {
    star.addEventListener('click', function() {
        document.querySelectorAll('.star-rating').forEach((s, i) => {
            if (i <= index) {
                s.classList.add('text-yellow-400');
                s.classList.remove('text-gray-300');
            } else {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300');
            }
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>