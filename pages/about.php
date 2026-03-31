<?php
$pageTitle = 'من نحن - ماجيك ماج';
require_once '../includes/header.php';

// مصفوفة الصور اليدوية
$manualImages = [
    1 => '../img/print.jpeg',
    2 => '../img/white.jpeg',
    3 => '../img/colorfull.jpeg',
    4 => '../img/gold.jpeg',
];
?>

<section class="py-12">
    <!-- Hero -->
    <div class="relative bg-dark text-white py-20 mb-12">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        <div class="max-w-7xl mx-auto px-4 text-center relative" data-aos="fade-up">
            <h1 class="text-5xl font-bold mb-6">قصتنا</h1>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto">نؤمن بأن كل صباح يبدأ بكوب مميز، وكل لحظة هادئة تستحق مجاً يليق بها</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Story -->
        <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
            <div data-aos="fade-right">
                <img src="<?php echo $manualImages[1]; ?>" alt="قصتنا" class="rounded-3xl shadow-2xl w-full h-96 object-cover">
            </div>
            <div data-aos="fade-left">
                <h2 class="text-3xl font-bold mb-6">منذ البداية</h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    بدأت ماجيك ماج رحلتها في عام 2020 من ورشة صغيرة في القاهرة، بهدف واحد: تقديم مجات عالية الجودة بتصاميم فريدة تعكس شخصية كل عميل.
                </p>
                <p class="text-gray-600 leading-relaxed mb-4">
                    نحن نؤمن بأن المج ليس مجرد وعاء للمشروبات، بل هو جزء من روتينك اليومي، رفيق لحظات استرخائك، وشاهد على لحظاتك المميزة.
                </p>
                <div class="grid grid-cols-3 gap-6 mt-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold gradient-text">+5000</div>
                        <div class="text-gray-500 text-sm">عميل سعيد</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold gradient-text">+200</div>
                        <div class="text-gray-500 text-sm">تصميم فريد</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold gradient-text">3</div>
                        <div class="text-gray-500 text-sm">سنوات خبرة</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values -->
        <div class="mb-20">
            <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">قيمنا</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center card-hover" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">الجودة أولاً</h3>
                    <p class="text-gray-600">نختار موادنا بعناية فائقة لضمان منتج يدوم طويلاً ويحافظ على جودته</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">الإبداع</h3>
                    <p class="text-gray-600">نستمر في ابتكار تصاميم جديدة تلبي تطلعات عملائنا وتتجاوز توقعاتهم</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">الشغف</h3>
                    <p class="text-gray-600">نضع قلوبنا في كل منتج نصنعه، لأن سعادتكم هي مصدر إلهامنا</p>
                </div>
            </div>
        </div>

        <!-- قسم جديد: معرض المنتجات -->
        <div class="mb-20">
            <h2 class="text-3xl font-bold text-center mb-4" data-aos="fade-up">تشكيلتنا المميزة</h2>
            <p class="text-gray-600 text-center mb-12 max-w-2xl mx-auto">مجات فريدة بتصاميم عصرية تناسب كل الأذواق</p>
            
            <div class="grid md:grid-cols-4 gap-6">
                <?php foreach ($manualImages as $id => $image): ?>
                <div class="relative group overflow-hidden rounded-2xl shadow-lg" data-aos="fade-up" data-aos-delay="<?php echo ($id-1) * 100; ?>">
                    <img src="<?php echo $image; ?>" alt="منتج <?php echo $id; ?>" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                        <a href="products.php" class="text-white font-bold hover:text-primary transition">تسوق الآن <i class="fas fa-arrow-left mr-2"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- قسم جديد: شهادة صاحبة الموقع -->
        <div class="mb-20">
            <div class="bg-gradient-to-r from-primary to-secondary rounded-3xl p-12 text-white relative overflow-hidden" data-aos="fade-up">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
                
                <div class="relative z-10 text-center max-w-3xl mx-auto">
                    <i class="fas fa-quote-right text-6xl text-white/30 mb-6"></i>
                    <p class="text-2xl font-bold mb-6 leading-relaxed">
                        "بدأت ماجيك ماج بحلم بسيط: أن يبدأ كل يوم بابتسامة. كل مج نصنعه يحمل قصة، وكل عميل يصبح جزء من عائلتنا."
                    </p>
                    <div class="flex items-center justify-center gap-4">
                        <img src="<?php echo $manualImages[1]; ?>" alt="صاحبة الموقع" class="w-16 h-16 rounded-full border-4 border-white/30 object-cover">
                        <div class="text-right">
                            <div class="font-bold text-xl">[Habiba Magdy]</div>
                            <div class="text-white/80">مؤسسة ماجيك ماج</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- قسم جديد: تواصل معي مباشرة -->
        <div class="text-center mb-20" data-aos="fade-up">
            <h2 class="text-3xl font-bold mb-4">تواصلي معي مباشرة</h2>
            <p class="text-gray-600 mb-8">أسعد بسماع آرائكم واقتراحاتكم في أي وقت</p>
            
            <div class="flex flex-wrap justify-center gap-4">
                <a href="https://wa.me/201234567890" target="_blank" class="flex items-center gap-2 bg-green-500 text-white px-6 py-3 rounded-full hover:bg-green-600 transition shadow-lg">
                    <i class="fab fa-whatsapp text-xl"></i>
                    <span>واتساب</span>
                </a>
                <a href="mailto:info@magicmug.com" class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-full hover:bg-secondary transition shadow-lg">
                    <i class="fas fa-envelope text-xl"></i>
                    <span>بريد إلكتروني</span>
                </a>
                <a href="contact.php" class="flex items-center gap-2 bg-dark text-white px-6 py-3 rounded-full hover:bg-gray-800 transition shadow-lg">
                    <i class="fas fa-comment-dots text-xl"></i>
                    <span>تواصل معنا</span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>