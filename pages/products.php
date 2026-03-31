<?php
$pageTitle = 'المنتجات - ماجيك ماج';
require_once '../includes/header.php';

// مصفوفة الصور اليدوية (نفس الصور اللي في الهوم وصفحة التفاصيل)
$manualImages = [
    1 => '../img/black.jpeg',  // منتج 1
    2 => '../img/white.jpeg',                               // منتج 2
    3 => '../img/colorfull.jpeg',                               // منتج 3
    4 => '../img/gold.jpeg',                               // منتج 4
    5 => '../img/print.jpeg',                               // منتج 5
    6 => '../img/heat.jpeg',                               // منتج 6
];

// بيانات المنتجات يدوياً
$manualProducts = [
    1 => ['id' => 1, 'name' => 'مج أسود كلاسيكي', 'price' => 75, 'category' => 'classic', 'stock' => 10, 'featured' => true, 'description' => 'مج سيراميك عالي الجودة باللون الأسود'],
    2 => ['id' => 2, 'name' => 'مج أبيض أنيق', 'price' => 65, 'category' => 'classic', 'stock' => 15, 'featured' => false, 'description' => 'مج أبيض ناصع بتصميم عصري'],
    3 => ['id' => 3, 'name' => 'مج بألوان متدرجة', 'price' => 95, 'category' => 'modern', 'stock' => 8, 'featured' => true, 'description' => 'مج بتصميم جريديانت ألوان موف'],
    4 => ['id' => 4, 'name' => 'مج ذهبي فاخر', 'price' => 120, 'category' => 'luxury', 'stock' => 5, 'featured' => false, 'description' => 'مج بطلاء ذهبي أنيق'],
    5 => ['id' => 5, 'name' => 'مج مطبوع', 'price' => 85, 'category' => 'custom', 'stock' => 12, 'featured' => true, 'description' => 'مج يمكن طباعة الصور عليه'],
    6 => ['id' => 6, 'name' => 'مج سحري', 'price' => 110, 'category' => 'special', 'stock' => 7, 'featured' => true, 'description' => 'مج يتغير لونه عند السكب'],
];

// معالجة الفلترة
$category = isset($_GET['cat']) ? clean($_GET['cat']) : '';
$search = isset($_GET['search']) ? clean($_GET['search']) : '';
$sort = isset($_GET['sort']) ? clean($_GET['sort']) : 'newest';

// فلترة المنتجات يدوياً
$products = $manualProducts;

// فلترة حسب القسم
if ($category) {
    $products = array_filter($products, function($p) use ($category) {
        return $p['category'] == $category;
    });
}

// فلترة حسب البحث
if ($search) {
    $products = array_filter($products, function($p) use ($search) {
        return strpos(strtolower($p['name']), strtolower($search)) !== false || 
               strpos(strtolower($p['description']), strtolower($search)) !== false;
    });
}

// الترتيب
switch($sort) {
    case 'price_low': 
        usort($products, function($a, $b) { return $a['price'] - $b['price']; });
        break;
    case 'price_high': 
        usort($products, function($a, $b) { return $b['price'] - $a['price']; });
        break;
    case 'popular': 
        usort($products, function($a, $b) { return $b['stock'] - $a['stock']; });
        break;
    default: 
        // الأحدث (حسب الـ ID)
        usort($products, function($a, $b) { return $b['id'] - $a['id']; });
}

// التصنيفات الفريدة
$categories = array_unique(array_column($manualProducts, 'category'));
?>

<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl font-bold mb-4">منتجاتنا</h1>
            <p class="text-gray-600">اكتشف تشكيلتنا الواسعة من المجات الفاخرة</p>
        </div>

        <!-- Filters -->
        <div class="bg-white p-6 rounded-2xl shadow-lg mb-8" data-aos="fade-up">
            <form method="GET" class="grid md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="relative">
                    <input type="text" name="search" value="<?php echo $search; ?>" 
                           placeholder="ابحث عن منتج..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>

                <!-- Category -->
                <select name="cat" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                    <option value="">جميع الأقسام</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat; ?>" <?php echo $category == $cat ? 'selected' : ''; ?>>
                        <?php echo $cat; ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <!-- Sort -->
                <select name="sort" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                    <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>الأحدث</option>
                    <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>السعر: من الأقل للأعلى</option>
                    <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>السعر: من الأعلى للأقل</option>
                    <option value="popular" <?php echo $sort == 'popular' ? 'selected' : ''; ?>>الأكثر مبيعاً</option>
                </select>

                <!-- Submit -->
                <button type="submit" class="gradient-bg text-white py-3 rounded-xl font-bold hover:opacity-90 transition">
                    <i class="fas fa-filter ml-2"></i> تصفية
                </button>
            </form>
        </div>

        <!-- Products Grid -->
        <?php if (count($products) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php 
            $index = 0;
            foreach ($products as $product): 
                $imagePath = $manualImages[$product['id']] ?? '../assets/images/mugs/default.jpg';
            ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover group" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4) * 100; ?>">
                <div class="relative overflow-hidden h-56">
                    <!-- الصورة اليدوية -->
                    <img src="<?php echo $imagePath; ?>" alt="<?php echo $product['name']; ?>" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    <?php if ($product['featured']): ?>
                    <div class="absolute top-4 right-4 bg-primary text-white px-3 py-1 rounded-full text-xs font-bold">
                        مميز
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($product['stock'] < 10): ?>
                    <div class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                        مخزون محدود
                    </div>
                    <?php endif; ?>
                    
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                        <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-primary hover:bg-primary hover:text-white transition">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form method="POST" action="cart.php" class="inline">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="add_to_cart" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-primary hover:bg-primary hover:text-white transition">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg"><?php echo $product['name']; ?></h3>
                    </div>
                    <p class="text-gray-500 text-sm mb-3 line-clamp-2"><?php echo $product['description']; ?></p>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold gradient-text"><?php echo $product['price']; ?> ج.م</span>
                        <span class="text-xs px-2 py-1 bg-gray-100 rounded-full text-gray-600"><?php echo $product['category']; ?></span>
                    </div>
                </div>
            </div>
            <?php 
            $index++;
            endforeach; 
            ?>
        </div>
        <?php else: ?>
        <div class="text-center py-20 bg-white rounded-2xl shadow-lg">
            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-600 mb-2">لا توجد منتجات</h3>
            <p class="text-gray-500 mb-6">جرب البحث بكلمات مختلفة أو غير الفلاتر</p>
            <a href="products.php" class="gradient-bg text-white px-6 py-3 rounded-xl font-bold inline-block">
                عرض جميع المنتجات
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>