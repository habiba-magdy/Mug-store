    </div><!-- end of pt-20 from header -->

    <!-- Footer -->
    <footer class="bg-dark text-white pt-16 pb-8 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div data-aos="fade-up">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-mug-hot text-white"></i>
                        </div>
                        <span class="text-xl font-bold">ماجيك ماج</span>
                    </div>
                    <p class="text-gray-400 mb-4">وجهتك الأولى للمجات الفاخرة والتصاميم الفريدة. نقدم لك تجربة تسوق استثنائية مع منتجات عالية الجودة.</p>
                    <div class="flex space-x-4 space-x-reverse">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-lg font-bold mb-4 gradient-text">روابط سريعة</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="home.php" class="hover:text-white transition">الرئيسية</a></li>
                        <li><a href="products.php" class="hover:text-white transition">جميع المنتجات</a></li>
                        <li><a href="about.php" class="hover:text-white transition">من نحن</a></li>
                        <li><a href="contact.php" class="hover:text-white transition">تواصل معنا</a></li>
                        <li><a href="reviews.php" class="hover:text-white transition">آراء العملاء</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-lg font-bold mb-4 gradient-text">الأقسام</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="products.php?cat=classic" class="hover:text-white transition">المجات الكلاسيكية</a></li>
                        <li><a href="products.php?cat=modern" class="hover:text-white transition">المجات العصرية</a></li>
                        <li><a href="products.php?cat=luxury" class="hover:text-white transition">المجات الفاخرة</a></li>
                        <li><a href="products.php?cat=custom" class="hover:text-white transition">المجات المخصصة</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-lg font-bold mb-4 gradient-text">تواصل معنا</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt ml-2 text-primary"></i>
                            القاهرة، مصر
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone ml-2 text-primary"></i>
                            01234567890
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope ml-2 text-primary"></i>
                            info@magicmug.com
                        </li>
                    </ul>
                    
                    <!-- Newsletter -->
                    <div class="mt-4">
                        <p class="text-sm mb-2">اشترك في نشرتنا الإخبارية</p>
                        <form class="flex">
                            <input type="email" placeholder="بريدك الإلكتروني" class="flex-1 px-4 py-2 rounded-r-lg bg-white/10 border border-white/20 text-white placeholder-gray-500 focus:outline-none focus:border-primary">
                            <button type="submit" class="gradient-bg px-4 py-2 rounded-l-lg hover:opacity-90 transition">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">جميع الحقوق محفوظة &copy; <?php echo date('Y'); ?> ماجيك ماج</p>
                <div class="flex space-x-6 space-x-reverse mt-4 md:mt-0 text-sm text-gray-400">
                    <a href="#" class="hover:text-white transition">سياسة الخصوصية</a>
                    <a href="#" class="hover:text-white transition">الشروط والأحكام</a>
                </div>
            </div>
        </div>
    </footer>
    <?php ob_end_flush(); ?>

    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // تهيئة الأنيميشن
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-lg');
                navbar.querySelector('.glass-effect').style.background = 'rgba(255, 255, 255, 0.95)';
            } else {
                navbar.classList.remove('shadow-lg');
                navbar.querySelector('.glass-effect').style.background = 'rgba(255, 255, 255, 0.1)';
            }
        });

        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });

        // إخفاء رسالة التنبيه بعد 3 ثواني
        const alert = document.getElementById('alert');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        }
    </script>
</body>
</html>