<?php
$pageTitle = 'آراء العملاء - ماجيك ماج';
require_once '../includes/header.php';

// مصفوفة الصور اليدوية (نفسها في كل الصفحات)
$manualImages = [
    1 => '../img/black.jpeg',
    2 => '../img/white.jpeg',
    3 => '../img/colorfull.jpeg',
    4 => '../img/gold.jpeg',
    5 => '../img/print.jpeg',
    6 => '../img/heat.jpeg',
];

// معالجة إضافة تقييم
if (isset($_POST['submit_review']) && isLoggedIn()) {
    $productId = intval($_POST['product_id']);
    $rating = intval($_POST['rating'] ?? 5);
    $comment = clean($_POST['comment']);
    
    // التحقق من البيانات
    if (empty($comment)) {
        showMessage('يرجى كتابة تعليق', 'error');
        redirect('product-detail.php?id=' . $productId);
    }
    
    // حفظ التقييم في الداتا بيز
    query("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)",
          [$_SESSION['user_id'], $productId, $rating, $comment]);
    
    showMessage('تم إضافة تقييمك بنجاح!');
    redirect('reviews.php'); // ← يروح لصفحة الآراء بعد الإضافة
    exit;
}

// جلب جميع الآراء مع بيانات المنتج
$reviews = fetchAll("
    SELECT r.*, u.full_name, p.name as product_name, p.id as product_id 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    JOIN products p ON r.product_id = p.id 
    ORDER BY r.created_at DESC
");
?>

<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl font-bold mb-4">آراء عملائنا</h1>
            <p class="text-gray-600">شاهد ما يقوله عملاؤنا عن تجربتهم مع منتجاتنا</p>
        </div>

        <!-- Stats -->
        <div class="grid md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white p-6 rounded-2xl shadow-lg text-center card-hover" data-aos="fade-up" data-aos-delay="0">
                <div class="text-4xl font-bold gradient-text mb-2">4.9</div>
                <div class="text-yellow-400 text-sm mb-1">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <div class="text-gray-500 text-sm">متوسط التقييم</div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-lg text-center card-hover" data-aos="fade-up" data-aos-delay="100">
                <div class="text-4xl font-bold gradient-text mb-2"><?php echo count($reviews); ?></div>
                <div class="text-gray-500 text-sm">تقييم إجمالي</div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-lg text-center card-hover" data-aos="fade-up" data-aos-delay="200">
                <div class="text-4xl font-bold gradient-text mb-2">98%</div>
                <div class="text-gray-500 text-sm">نسبة التوصية</div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-lg text-center card-hover" data-aos="fade-up" data-aos-delay="300">
                <div class="text-4xl font-bold gradient-text mb-2">+5000</div>
                <div class="text-gray-500 text-sm">عميل سعيد</div>
            </div>
        </div>

        <!-- Reviews Grid -->
        <?php if (count($reviews) > 0): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($reviews as $index => $review): 
                // استخدام الصورة اليدوية حسب product_id
                $productImage = $manualImages[$review['product_id']] ?? '../assets/images/mugs/default.jpg';
            ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3) * 100; ?>">
                <!-- صورة المنتج -->
                <div class="relative h-48 overflow-hidden">
                    <img src="<?php echo $productImage; ?>" alt="<?php echo $review['product_name']; ?>" class="w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                        <span class="text-white font-bold text-sm"><?php echo $review['product_name']; ?></span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center text-white font-bold text-lg ml-3">
                            <?php echo substr($review['full_name'], 0, 1); ?>
                        </div>
                        <div>
                            <div class="font-bold"><?php echo $review['full_name']; ?></div>
                            <div class="text-xs text-gray-500"><?php echo date('Y-m-d', strtotime($review['created_at'])); ?></div>
                        </div>
                    </div>
                    
                    <div class="flex text-yellow-400 text-sm mb-3">
                        <?php for($i=1; $i<=5; $i++): ?>
                        <i class="fas fa-star <?php echo $i <= $review['rating'] ? '' : 'text-gray-300'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    
                    <p class="text-gray-600 leading-relaxed">"<?php echo $review['comment']; ?>"</p>
                    
                    <a href="product-detail.php?id=<?php echo $review['product_id']; ?>" class="inline-flex items-center mt-4 text-primary hover:text-secondary text-sm font-semibold">
                        شاهد المنتج <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-20 bg-white rounded-2xl shadow-lg" data-aos="fade-up">
            <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-600 mb-2">لا توجد تقييمات بعد</h3>
            <p class="text-gray-500 mb-6">كن أول من يقيّم منتجاتنا</p>
            <a href="products.php" class="inline-block gradient-bg text-white px-8 py-3 rounded-full font-bold btn-glow">
                تصفح المنتجات
            </a>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>