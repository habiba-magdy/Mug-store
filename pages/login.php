<?php
$pageTitle = 'تسجيل الدخول - ماجيك ماج';
require_once '../includes/header.php';

if (isLoggedIn()) {
    header('Location: home.php');
    exit;
}

if (isset($_POST['login'])) {
    $email = clean($_POST['email']);
    $password = $_POST['password'];
    
    $user = fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        
        // نقل السلة من السيشن للداتابيز
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                addToCart($productId, $quantity);
            }
            unset($_SESSION['cart']);
        }
        
        showMessage('تم تسجيل الدخول بنجاح!');
        header('Location: home.php');
        exit;
    } else {
        showMessage('البريد الإلكتروني أو كلمة المرور غير صحيحة', 'error');
    }
}

if (isset($_POST['register'])) {
    $name = clean($_POST['full_name']);
    $email = clean($_POST['email']);
    $phone = clean($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    try {
        query("INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)",
              [$name, $email, $phone, $password]);
        showMessage('تم إنشاء الحساب بنجاح! يمكنك الآن تسجيل الدخول');
    } catch(PDOException $e) {
        showMessage('البريد الإلكتروني مستخدم بالفعل', 'error');
    }
}
?>

<section class="py-12 bg-gray-50 min-h-screen flex items-center">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2">
                <!-- Image Side -->
                <div class="hidden md:block gradient-bg p-12 text-white flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-mug-hot text-4xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold mb-4">أهلاً بعودتك!</h2>
                        <p class="text-white/80">سجل دخولك للوصول إلى حسابك ومشاهدة طلباتك المفضلة</p>
                    </div>
                </div>

                <!-- Form Side -->
                <div class="p-8 md:p-12">
                    <!-- Tabs -->
                    <div class="flex mb-8 border-b">
                        <button onclick="showTab('login')" id="tab-login" class="flex-1 pb-4 text-center font-bold border-b-2 border-primary text-primary">
                            تسجيل الدخول
                        </button>
                        <button onclick="showTab('register')" id="tab-register" class="flex-1 pb-4 text-center font-bold border-b-2 border-transparent text-gray-400">
                            إنشاء حساب
                        </button>
                    </div>

                    <!-- Login Form -->
                    <form id="form-login" method="POST" class="space-y-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">البريد الإلكتروني</label>
                            <input type="email" name="email" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">كلمة المرور</label>
                            <input type="password" name="password" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <label class="flex items-center">
                                <input type="checkbox" class="ml-2"> تذكرني
                            </label>
                            <a href="#" class="text-primary hover:underline">نسيت كلمة المرور؟</a>
                        </div>
                        <button type="submit" name="login" class="w-full gradient-bg text-white py-4 rounded-xl font-bold btn-glow text-lg">
                            تسجيل الدخول
                        </button>
                    </form>

                    <!-- Register Form -->
                    <form id="form-register" method="POST" class="space-y-4 hidden">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">الاسم الكامل</label>
                            <input type="text" name="full_name" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">البريد الإلكتروني</label>
                            <input type="email" name="email" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">رقم الهاتف</label>
                            <input type="tel" name="phone" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">كلمة المرور</label>
                            <input type="password" name="password" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                        </div>
                        <button type="submit" name="register" class="w-full gradient-bg text-white py-4 rounded-xl font-bold btn-glow text-lg">
                            إنشاء حساب
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function showTab(tab) {
    document.getElementById('form-login').classList.toggle('hidden', tab !== 'login');
    document.getElementById('form-register').classList.toggle('hidden', tab !== 'register');
    
    document.getElementById('tab-login').classList.toggle('border-primary', tab === 'login');
    document.getElementById('tab-login').classList.toggle('text-primary', tab === 'login');
    document.getElementById('tab-login').classList.toggle('border-transparent', tab !== 'login');
    document.getElementById('tab-login').classList.toggle('text-gray-400', tab !== 'login');
    
    document.getElementById('tab-register').classList.toggle('border-primary', tab === 'register');
    document.getElementById('tab-register').classList.toggle('text-primary', tab === 'register');
    document.getElementById('tab-register').classList.toggle('border-transparent', tab !== 'register');
    document.getElementById('tab-register').classList.toggle('text-gray-400', tab !== 'register');
}
</script>

<?php require_once '../includes/footer.php'; ?>