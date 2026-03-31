<?php
ob_start();  // ← أول سطر في الملف
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$cartCount = getCartCount();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'ماجيك ماج - متجر المجات الفاخرة'; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#7C3AED',
                        secondary: '#A855F7',
                        accent: '#C084FC',
                        dark: '#1E1B4B',
                    },
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        * { font-family: 'Cairo', sans-serif; }
        
        .gradient-bg {
            background: linear-gradient(135deg, #7C3AED 0%, #A855F7 50%, #C084FC 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #7C3AED, #C084FC);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(124, 58, 237, 0.3);
        }
        
        .btn-glow {
            box-shadow: 0 0 20px rgba(124, 58, 237, 0.5);
            transition: all 0.3s ease;
        }
        
        .btn-glow:hover {
            box-shadow: 0 0 40px rgba(124, 58, 237, 0.8);
            transform: scale(1.05);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .pulse-ring {
            position: relative;
        }
        
        .pulse-ring::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(124, 58, 237, 0.4);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }
    </style>
</head>
<body class="bg-gray-50 overflow-x-hidden">

    <!-- رسائل التنبيه -->
    <?php $msg = getMessage(); if ($msg): ?>
    <div id="alert" class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50 <?php echo $msg['type'] == 'success' ? 'bg-green-500' : 'bg-red-500'; ?> text-white px-6 py-3 rounded-full shadow-lg slide-in">
        <?php echo $msg['text']; ?>
    </div>
    <?php endif; ?>

    <!-- Navbar -->
    <nav class="fixed w-full z-40 transition-all duration-300" id="navbar">
        <div class="glass-effect px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="home.php" class="flex items-center space-x-2 space-x-reverse">
                    <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center">
                        <i class="fas fa-mug-hot text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold gradient-text mr-2">ماجيك ماج</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8 space-x-reverse">
                    <a href="home.php" class="nav-link text-gray-700 hover:text-primary font-semibold transition <?php echo $currentPage == 'home' ? 'text-primary' : ''; ?>">الرئيسية</a>
                    <a href="products.php" class="nav-link text-gray-700 hover:text-primary font-semibold transition <?php echo $currentPage == 'products' ? 'text-primary' : ''; ?>">المنتجات</a>
                    <a href="about.php" class="nav-link text-gray-700 hover:text-primary font-semibold transition <?php echo $currentPage == 'about' ? 'text-primary' : ''; ?>">من نحن</a>
                    <a href="reviews.php" class="nav-link text-gray-700 hover:text-primary font-semibold transition <?php echo $currentPage == 'reviews' ? 'text-primary' : ''; ?>">الآراء</a>
                    <a href="contact.php" class="nav-link text-gray-700 hover:text-primary font-semibold transition <?php echo $currentPage == 'contact' ? 'text-primary' : ''; ?>">تواصل معنا</a>
                </div>

                <!-- Icons -->
                <div class="flex items-center space-x-4 space-x-reverse">
                    <!-- Cart -->
                    <a href="cart.php" class="relative p-2 text-gray-700 hover:text-primary transition">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <?php if ($cartCount > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center pulse-ring">
                            <?php echo $cartCount; ?>
                        </span>
                        <?php endif; ?>
                    </a>

                    <!-- User -->
                    <?php if (isLoggedIn()): ?>
                    <div class="relative group">
                        <button class="flex items-center space-x-2 space-x-reverse text-gray-700 hover:text-primary">
                            <i class="fas fa-user-circle text-2xl"></i>
                            <span class="hidden sm:block"><?php echo $_SESSION['user_name']; ?></span>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                            <a href="profile.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">حسابي</a>
                            <a href="orders.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">طلباتي</a>
                            <hr class="my-1">
                            <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-50">تسجيل خروج</a>
                        </div>
                    </div>
                    <?php else: ?>
                    <a href="login.php" class="gradient-bg text-white px-6 py-2 rounded-full btn-glow font-semibold">
                        تسجيل الدخول
                    </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn" class="md:hidden p-2 text-gray-700">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden glass-effect border-t">
            <div class="px-4 py-4 space-y-3">
                <a href="home.php" class="block py-2 text-gray-700 hover:text-primary">الرئيسية</a>
                <a href="products.php" class="block py-2 text-gray-700 hover:text-primary">المنتجات</a>
                <a href="about.php" class="block py-2 text-gray-700 hover:text-primary">من نحن</a>
                <a href="reviews.php" class="block py-2 text-gray-700 hover:text-primary">الآراء</a>
                <a href="contact.php" class="block py-2 text-gray-700 hover:text-primary">تواصل معنا</a>
            </div>
        </div>
    </nav>

    <div class="pt-20"></div>