<?php
$pageTitle = 'تواصل معنا - ماجيك ماج';
require_once '../includes/header.php';

if (isset($_POST['send_message'])) {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $subject = clean($_POST['subject']);
    $message = clean($_POST['message']);
    
    // هنا يمكنك إضافة كود إرسال البريد أو حفظ الرسالة في قاعدة البيانات
    showMessage('تم إرسال رسالتك بنجاح! سنتواصل معك قريباً');
}
?>

<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl font-bold mb-4">تواصل معنا</h1>
            <p class="text-gray-600">نحن هنا لمساعدتك! أرسل لنا رسالتك وسنرد عليك في أقرب وقت</p>
        </div>

        <div class="grid md:grid-cols-2 gap-12">
            <!-- Contact Info -->
            <div data-aos="fade-right">
                <div class="bg-white rounded-2xl shadow-lg p-8 h-full">
                    <h2 class="text-2xl font-bold mb-6">معلومات التواصل</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center text-white ml-4 flex-shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h3 class="font-bold mb-1">العنوان</h3>
                                <p class="text-gray-600">123 شارع التحرير، القاهرة، مصر</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center text-white ml-4 flex-shrink-0">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h3 class="font-bold mb-1">الهاتف</h3>
                                <p class="text-gray-600">01234567890</p>
                                <p class="text-gray-600">01234567891</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center text-white ml-4 flex-shrink-0">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h3 class="font-bold mb-1">البريد الإلكتروني</h3>
                                <p class="text-gray-600">info@magicmug.com</p>
                                <p class="text-gray-600">support@magicmug.com</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center text-white ml-4 flex-shrink-0">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h3 class="font-bold mb-1">ساعات العمل</h3>
                                <p class="text-gray-600">السبت - الخميس: 9:00 ص - 9:00 م</p>
                                <p class="text-gray-600">الجمعة: مغلق</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social -->
                    <div class="mt-8 pt-8 border-t">
                        <h3 class="font-bold mb-4">تابعنا على</h3>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center text-white hover:opacity-80 transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center text-white hover:opacity-80 transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center text-white hover:opacity-80 transition">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center text-white hover:opacity-80 transition">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div data-aos="fade-left">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold mb-6">أرسل رسالة</h2>
                    
                    <form method="POST" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">الاسم *</label>
                                <input type="text" name="name" required 
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">البريد الإلكتروني *</label>
                                <input type="email" name="email" required 
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">الموضوع *</label>
                            <input type="text" name="subject" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">الرسالة *</label>
                            <textarea name="message" rows="5" required 
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary"></textarea>
                        </div>
                        
                        <button type="submit" name="send_message" class="w-full gradient-bg text-white py-4 rounded-xl font-bold btn-glow text-lg">
                            <i class="fas fa-paper-plane ml-2"></i> إرسال الرسالة
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="mt-12 rounded-2xl overflow-hidden shadow-lg h-96" data-aos="fade-up">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3453.658243839!2d31.2357!3d30.0444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzDCsDAyJzQwLjAiTiAzMcKwMTQnMDguNSJF!5e0!3m2!1sen!2seg!4v1234567890" 
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>