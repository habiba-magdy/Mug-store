<?php
$pageTitle = 'حسابي - ماجيك ماج';
require_once '../includes/header.php';

// التحقق من تسجيل الدخول
if (!isLoggedIn()) {
    showMessage('يجب تسجيل الدخول لعرض حسابك', 'error');
    header('Location: login.php');
    exit;
}

// جلب بيانات المستخدم
$user = getUser();

// معالجة تحديث البيانات
if (isset($_POST['update_profile'])) {
    $fullName = clean($_POST['full_name']);
    $phone = clean($_POST['phone']);
    $address = clean($_POST['address']);
    $email = clean($_POST['email']);
    
    // التحقق من البيانات
    if (empty($fullName) || empty($phone) || empty($address)) {
        showMessage('يرجى ملء جميع الحقول المطلوبة', 'error');
    } else {
        // تحديث البيانات
        query("UPDATE users SET full_name = ?, phone = ?, address = ?, email = ? WHERE id = ?", 
              [$fullName, $phone, $address, $email, $_SESSION['user_id']]);
        
        // تحديث الاسم في السيشن
        $_SESSION['user_name'] = $fullName;
        
        showMessage('تم تحديث البيانات بنجاح!');
        header('Location: profile.php');
        exit;
    }
}

// معالجة تغيير كلمة المرور
if (isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // التحقق من كلمة المرور الحالية
    $userData = fetchOne("SELECT password FROM users WHERE id = ?", [$_SESSION['user_id']]);
    
    if (!password_verify($currentPassword, $userData['password'])) {
        showMessage('كلمة المرور الحالية غير صحيحة', 'error');
    } elseif ($newPassword != $confirmPassword) {
        showMessage('كلمة المرور الجديدة غير متطابقة', 'error');
    } elseif (strlen($newPassword) < 6) {
        showMessage('كلمة المرور يجب أن تكون 6 أحرف على الأقل', 'error');
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        query("UPDATE users SET password = ? WHERE id = ?", [$hashedPassword, $_SESSION['user_id']]);
        showMessage('تم تغيير كلمة المرور بنجاح!');
        header('Location: profile.php');
        exit;
    }
}

// معالجة رفع الصورة
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['profile_image']['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (in_array($ext, $allowed)) {
        $newName = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $uploadPath = '../uploads/profiles/' . $newName;
        
        // إنشاء المجلد لو مش موجود
        if (!file_exists('../uploads/profiles/')) {
            mkdir('../uploads/profiles/', 0777, true);
        }
        
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
            // حذف الصورة القديمة
            $oldImage = $user['profile_image'] ?? '';
            if ($oldImage && file_exists('../uploads/profiles/' . $oldImage)) {
                unlink('../uploads/profiles/' . $oldImage);
            }
            
            query("UPDATE users SET profile_image = ? WHERE id = ?", [$newName, $_SESSION['user_id']]);
            showMessage('تم تحديث الصورة بنجاح!');
            header('Location: profile.php');
            exit;
        }
    } else {
        showMessage('صيغة الملف غير مدعومة', 'error');
    }
}

// صورة المستخدم
$profileImage = $user['profile_image'] ?? '';
$imagePath = $profileImage ? '../uploads/profiles/' . $profileImage : '../assets/images/default-avatar.png';

// إحصائيات المستخدم
$ordersCount = fetchOne("SELECT COUNT(*) as count FROM orders WHERE user_id = ?", [$_SESSION['user_id']])['count'] ?? 0;
$totalSpent = fetchOne("SELECT SUM(total_amount) as total FROM orders WHERE user_id = ?", [$_SESSION['user_id']])['total'] ?? 0;
?>

<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-bold mb-8" data-aos="fade-up">حسابي</h1>
        
        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Sidebar -->
            <div data-aos="fade-right">
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center mb-6">
                    <div class="relative w-32 h-32 mx-auto mb-4">
                        <img src="<?php echo $imagePath; ?>" alt="صورتي" class="w-full h-full object-cover rounded-full border-4 border-primary/20">
                        <label for="imageUpload" class="absolute bottom-0 right-0 w-10 h-10 gradient-bg rounded-full flex items-center justify-center text-white cursor-pointer hover:shadow-lg transition">
                            <i class="fas fa-camera"></i>
                        </label>
                        <form method="POST" enctype="multipart/form-data" class="hidden">
                            <input type="file" id="imageUpload" name="profile_image" accept="image/*" onchange="this.form.submit()">
                        </form>
                    </div>
                    
                    <h2 class="text-xl font-bold mb-1"><?php echo $user['full_name']; ?></h2>
                    <p class="text-gray-500 text-sm mb-4"><?php echo $user['email']; ?></p>
                    
                    <div class="flex justify-center gap-2">
                        <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold">
                            عضو منذ <?php echo date('Y', strtotime($user['created_at'] ?? 'now')); ?>
                        </span>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <div class="text-2xl font-bold gradient-text"><?php echo $ordersCount; ?></div>
                            <div class="text-gray-500 text-sm">طلب</div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <div class="text-2xl font-bold gradient-text"><?php echo $totalSpent; ?></div>
                            <div class="text-gray-500 text-sm">ج.م إجمالي</div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold mb-4">روابط سريعة</h3>
                    <div class="space-y-2">
                        <a href="orders.php" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition">
                            <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <span>طلباتي</span>
                        </a>
                        <a href="reviews.php" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600">
                                <i class="fas fa-star"></i>
                            </div>
                            <span>تقييماتي</span>
                        </a>
                        <a href="home.php" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                <i class="fas fa-store"></i>
                            </div>
                            <span>تسوق</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6" data-aos="fade-left">
                
                <!-- Personal Info -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold mb-6 flex items-center">
                        <i class="fas fa-user-circle text-primary ml-2"></i>
                        البيانات الشخصية
                    </h2>
                    
                    <form method="POST" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">الاسم الكامل *</label>
                                <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" required
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">البريد الإلكتروني</label>
                                <input type="email" name="email" value="<?php echo $user['email']; ?>"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">رقم الهاتف *</label>
                            <input type="tel" name="phone" value="<?php echo $user['phone']; ?>" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">عنوان الشحن *</label>
                            <textarea name="address" rows="3" required
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary"><?php echo $user['address']; ?></textarea>
                        </div>
                        
                        <button type="submit" name="update_profile" class="gradient-bg text-white px-8 py-3 rounded-xl font-bold btn-glow">
                            <i class="fas fa-save ml-2"></i> حفظ التغييرات
                        </button>
                    </form>
                </div>
                
                <!-- Change Password -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold mb-6 flex items-center">
                        <i class="fas fa-lock text-primary ml-2"></i>
                        تغيير كلمة المرور
                    </h2>
                    
                    <form method="POST" class="space-y-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">كلمة المرور الحالية</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">كلمة المرور الجديدة</label>
                                <input type="password" name="new_password" required minlength="6"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">تأكيد كلمة المرور</label>
                                <input type="password" name="confirm_password" required
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                            </div>
                        </div>
                        
                        <button type="submit" name="change_password" class="bg-dark text-white px-8 py-3 rounded-xl font-bold hover:bg-gray-800 transition">
                            <i class="fas fa-key ml-2"></i> تغيير كلمة المرور
                        </button>
                    </form>
                </div>
                
            </div>
        </div>
        
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>