<?php
$pageTitle = 'الرئيسية - ماجيك ماج';
require_once '../includes/header.php';

// جلب المنتجات المميزة
$featuredProducts = fetchAll("SELECT * FROM products WHERE featured = 1 LIMIT 6");

// جلب أحدث الآراء
$latestReviews = fetchAll("
    SELECT r.*, u.full_name, p.name as product_name, p.image 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    JOIN products p ON r.product_id = p.id 
    ORDER BY r.created_at DESC 
    LIMIT 4
");
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 gradient-bg opacity-10"></div>
    <div class="absolute top-20 right-10 w-72 h-72 bg-primary/30 rounded-full blur-3xl floating"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-secondary/30 rounded-full blur-3xl floating" style="animation-delay: 1s;"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right">
                <div class="inline-block px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-semibold mb-6">
                    ✨ مجات فاخرة بتصاميم فريدة
                </div>
                <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6">
                    ابدأ يومك بـ <span class="gradient-text">كوب مميز</span> يعكس شخصيتك
                </h1>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    اكتشف تشكيلتنا الواسعة من المجات الفاخرة المصنوعة من أجود الخامات. تصاميم عصرية تناسب كل الأذواق.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="products.php" class="gradient-bg text-white px-8 py-4 rounded-full btn-glow font-bold text-lg inline-flex items-center">
                        تسوق الآن
                        <i class="fas fa-arrow-left mr-2"></i>
                    </a>
                    <a href="about.php" class="px-8 py-4 rounded-full border-2 border-primary text-primary font-bold hover:bg-primary hover:text-white transition">
                        اعرف أكثر
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 mt-12">
                    <div>
                        <div class="text-3xl font-bold gradient-text">+5000</div>
                        <div class="text-gray-600">عميل سعيد</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold gradient-text">+200</div>
                        <div class="text-gray-600">منتج متنوع</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold gradient-text">4.9</div>
                        <div class="text-gray-600">تقييم العملاء</div>
                    </div>
                </div>
            </div>
            
            <div class="relative" data-aos="fade-left">
    <div class="relative z-10 floating animation-delay-500">
        <!-- الحاوية الدائرية -->
        <div class="w-80 h-80 md:w-96 md:h-96 mx-auto rounded-full overflow-hidden border-8 border-white/30 shadow-2xl">
            <img src="../img/WhatsApp Image 2026-03-30 at 6.30.49 PM.jpeg" 
                 alt="مج فاخر" 
                 class="w-full h-full object-cover">
        </div>
    </div>
    
    <!-- Decorative elements -->
    <div class="absolute -top-5 -right-5 w-32 h-32 bg-yellow-400 rounded-full opacity-80 blur-2xl"></div>
    <div class="absolute -bottom-5 -left-5 w-40 h-40 bg-primary rounded-full opacity-60 blur-2xl"></div>
    <div class="absolute top-1/2 -right-10 w-24 h-24 bg-secondary rounded-full opacity-40 blur-xl"></div>
</div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold mb-4">منتجاتنا <span class="gradient-text">المميزة</span></h2>
            <p class="text-gray-600 text-lg">اختر من مجموعتنا المختارة بعناية من أفضل المجات</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- منتج 1 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover group" data-aos="fade-up" data-aos-delay="0">
                <div class="relative overflow-hidden h-64">
                    <!-- ضيفي الصورة هنا -->
                    <img src="../img/black.jpeg" width="20px" alt="مج أسود كلاسيكي" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                        مخزون محدود
                    </div>
                    
                    <form method="POST" action="cart.php" class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                        <input type="hidden" name="product_id" value="1">
                        <button type="submit" name="add_to_cart" class="w-full gradient-bg text-white py-3 rounded-xl font-bold btn-glow">
                            <i class="fas fa-cart-plus ml-2"></i> أضف للسلة
                        </button>
                    </form>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-800">مج أسود كلاسيكي</h3>
                        <span class="text-2xl font-bold gradient-text">75 ج.م</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">مج سيراميك عالي الجودة باللون الأسود، مثالي للقهوة والشاي</p>
                    <div class="flex items-center justify-between">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <a href="product-detail.php?id=1" class="text-primary hover:text-secondary font-semibold">
                            التفاصيل <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- منتج 2 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover group" data-aos="fade-up" data-aos-delay="100">
                <div class="relative overflow-hidden h-64">
                    <!-- ضيفي الصورة هنا -->
                    <img src="../img/white.jpeg" alt="مج أبيض أنيق" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <form method="POST" action="cart.php" class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                        <input type="hidden" name="product_id" value="2">
                        <button type="submit" name="add_to_cart" class="w-full gradient-bg text-white py-3 rounded-xl font-bold btn-glow">
                            <i class="fas fa-cart-plus ml-2"></i> أضف للسلة
                        </button>
                    </form>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-800">مج أبيض أنيق</h3>
                        <span class="text-2xl font-bold gradient-text">65 ج.م</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">مج أبيض ناصع بتصميم عصري يناسب جميع الأذواق</p>
                    <div class="flex items-center justify-between">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <a href="product-detail.php?id=2" class="text-primary hover:text-secondary font-semibold">
                            التفاصيل <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- منتج 3 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover group" data-aos="fade-up" data-aos-delay="200">
                <div class="relative overflow-hidden h-64">
                    <!-- ضيفي الصورة هنا -->
                    <img src="../img/colorfull.jpeg" alt="مج بألوان متدرجة" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="absolute top-4 right-4 bg-primary text-white px-3 py-1 rounded-full text-sm font-bold">
                        مميز
                    </div>
                    
                    <form method="POST" action="cart.php" class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                        <input type="hidden" name="product_id" value="3">
                        <button type="submit" name="add_to_cart" class="w-full gradient-bg text-white py-3 rounded-xl font-bold btn-glow">
                            <i class="fas fa-cart-plus ml-2"></i> أضف للسلة
                        </button>
                    </form>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-800">مج بألوان متدرجة</h3>
                        <span class="text-2xl font-bold gradient-text">95 ج.م</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">مج بتصميم جريديانت ألوان موف وأزرق فاتح</p>
                    <div class="flex items-center justify-between">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <a href="product-detail.php?id=3" class="text-primary hover:text-secondary font-semibold">
                            التفاصيل <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- منتج 4 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover group" data-aos="fade-up" data-aos-delay="300">
                <div class="relative overflow-hidden h-64">
                    <img src="../img/gold.jpeg" alt="مج ذهبي فاخر" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <form method="POST" action="cart.php" class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                        <input type="hidden" name="product_id" value="4">
                        <button type="submit" name="add_to_cart" class="w-full gradient-bg text-white py-3 rounded-xl font-bold btn-glow">
                            <i class="fas fa-cart-plus ml-2"></i> أضف للسلة
                        </button>
                    </form>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-800">مج ذهبي فاخر</h3>
                        <span class="text-2xl font-bold gradient-text">120 ج.م</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">مج بطلاء ذهبي أنيق للمناسبات الخاصة</p>
                    <div class="flex items-center justify-between">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <a href="product-detail.php?id=4" class="text-primary hover:text-secondary font-semibold">
                            التفاصيل <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- منتج 5 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover group" data-aos="fade-up" data-aos-delay="400">
                <div class="relative overflow-hidden h-64">
                    <img src="../img/print.jpeg" alt="مج مطبوع" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                        جديد
                    </div>
                    
                    <form method="POST" action="cart.php" class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                        <input type="hidden" name="product_id" value="5">
                        <button type="submit" name="add_to_cart" class="w-full gradient-bg text-white py-3 rounded-xl font-bold btn-glow">
                            <i class="fas fa-cart-plus ml-2"></i> أضف للسلة
                        </button>
                    </form>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-800">مج مطبوع</h3>
                        <span class="text-2xl font-bold gradient-text">85 ج.م</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">مج يمكن طباعة الصور والتصاميم عليه</p>
                    <div class="flex items-center justify-between">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <a href="product-detail.php?id=5" class="text-primary hover:text-secondary font-semibold">
                            التفاصيل <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- منتج 6 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover group" data-aos="fade-up" data-aos-delay="500">
                <div class="relative overflow-hidden h-64">
                    <img src="../img/heat.jpeg" alt="مج سحري" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="absolute top-4 right-4 bg-purple-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                        الأكثر مبيعاً
                    </div>
                    
                    <form method="POST" action="cart.php" class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                        <input type="hidden" name="product_id" value="6">
                        <button type="submit" name="add_to_cart" class="w-full gradient-bg text-white py-3 rounded-xl font-bold btn-glow">
                            <i class="fas fa-cart-plus ml-2"></i> أضف للسلة
                        </button>
                    </form>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-800">مج سحري</h3>
                        <span class="text-2xl font-bold gradient-text">110 ج.م</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">مج يتغير لونه عند سكب المشروب الساخن</p>
                    <div class="flex items-center justify-between">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <a href="product-detail.php?id=6" class="text-primary hover:text-secondary font-semibold">
                            التفاصيل <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-12">
            <a href="products.php" class="inline-flex items-center px-8 py-4 border-2 border-primary text-primary rounded-full font-bold hover:bg-primary hover:text-white transition">
                عرض جميع المنتجات
                <i class="fas fa-arrow-left mr-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center p-6 bg-white rounded-2xl shadow-lg card-hover" data-aos="fade-up" data-aos-delay="0">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">شحن سريع</h3>
                <p class="text-gray-600 text-sm">توصيل خلال 2-3 أيام لجميع المحافظات</p>
            </div>
            
            <div class="text-center p-6 bg-white rounded-2xl shadow-lg card-hover" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl">
                    <i class="fas fa-medal"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">جودة مضمونة</h3>
                <p class="text-gray-600 text-sm">منتجات أصلية 100% مع ضمان سنة</p>
            </div>
            
            <div class="text-center p-6 bg-white rounded-2xl shadow-lg card-hover" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl">
                    <i class="fas fa-undo"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">إرجاع سهل</h3>
                <p class="text-gray-600 text-sm">إرجاع مجاني خلال 14 يوم</p>
            </div>
            
            <div class="text-center p-6 bg-white rounded-2xl shadow-lg card-hover" data-aos="fade-up" data-aos-delay="300">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="text-lg font-bold mb-2">دعم 24/7</h3>
                <p class="text-gray-600 text-sm">فريق دعم جاهز لمساعدتك دائماً</p>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Preview -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold mb-4">آراء <span class="gradient-text">عملائنا</span></h2>
            <p class="text-gray-600 text-lg">شاهد ما يقوله عملاؤنا عن تجربتهم معنا</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($latestReviews as $index => $review): ?>
            <div class="bg-gray-50 p-6 rounded-2xl card-hover" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <div class="flex items-center mb-4">
                    <img src="../img/hob.jpg" width="48" height="48" class="w-12 h-12 rounded-full object-cover ml-3" alt="photo">
                    <div>
                        <div class="font-bold"><?php echo $review['full_name']; ?></div>
                        <div class="text-xs text-gray-500"><?php echo $review['product_name']; ?></div>
                    </div>
                </div>
                <div class="flex text-yellow-400 text-sm mb-3">
                    <?php for($i=0; $i<$review['rating']; $i++): ?>
                    <i class="fas fa-star"></i>
                    <?php endfor; ?>
                </div>
                <p class="text-gray-600 text-sm">"<?php echo $review['comment']; ?>"</p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 relative overflow-hidden">
    <div class="absolute inset-0 gradient-bg"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
    
    <div class="relative max-w-4xl mx-auto text-center px-4" data-aos="zoom-in">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">جاهز لتحصل على مجك المميز؟</h2>
        <p class="text-xl text-white/80 mb-8">انضم لآلاف العملاء السعداء واكتشف تشكيلتنا الفريدة اليوم</p>
        <a href="products.php" class="inline-block bg-white text-primary px-10 py-4 rounded-full font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition">
            ابدأ التسوق الآن
        </a>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>