<?php
$currentPage = $_GET['page'] ?? 'home';

$isLoggedIn = function_exists('isLoggedIn') && isLoggedIn();
$currentUser = $isLoggedIn ? currentUser() : null;

$userName = !empty($currentUser['name']) ? htmlspecialchars($currentUser['name']) : 'Thành viên';
$isAdmin = $isLoggedIn && isset($currentUser['role']) && $currentUser['role'] === 'admin';

$adminLink = "/GiupViecNhanh/admin/index.php"; 

function isActive($page, $currentPage) {
    return $page === $currentPage ? 'text-brand-600 bg-brand-50' : '';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giúp Việc Nhanh - Giải pháp Sạch & Xanh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 700: '#047857', 900: '#064e3b' }
                    },
                    fontFamily: { sans: ['Be Vietnam Pro', 'sans-serif'] }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Be Vietnam Pro', sans-serif; }
        .glass-effect { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <nav class="glass-effect sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <a href="index.php?page=home" class="flex items-center cursor-pointer group">
                    <div class="w-10 h-10 mr-3 flex-shrink-0">
                        <img src="assets/images/logo.png" alt="Logo GiupViecNhanh" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="text-xl font-bold text-slate-800 tracking-tight">GiupViec<span class="text-brand-600">Nhanh</span></span>
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-semibold">Premium Service</p>
                    </div>
                </a>
>
                <div class="hidden md:flex items-center space-x-1">
                    <a href="index.php?page=home" class="px-4 py-2 text-slate-600 hover:text-brand-600 font-medium rounded-lg hover:bg-brand-50 transition <?php echo isActive('home', $currentPage); ?>">Trang chủ</a>
                    <a href="index.php?page=services" class="px-4 py-2 text-slate-600 hover:text-brand-600 font-medium rounded-lg hover:bg-brand-50 transition <?php echo isActive('services', $currentPage); ?>">Dịch vụ</a>
                    <a href="index.php?page=booking" class="px-4 py-2 text-slate-600 hover:text-brand-600 font-medium rounded-lg hover:bg-brand-50 transition <?php echo isActive('booking', $currentPage); ?>">Đặt lịch</a>
                    <a href="index.php?page=staff" class="px-4 py-2 rounded-lg transition flex items-center <?php echo isActive('staff', $currentPage); ?>"><i class="fa-solid fa-magnifying-glass mr-2 text-xs"></i> Tìm người giúp việc</a>
                </div>

                <div class="hidden md:flex items-center space-x-3">
                    <?php if ($isLoggedIn): ?>
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden lg:block leading-tight">
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Xin chào</p>
                                <p class="text-sm font-bold text-brand-700 truncate max-w-[150px]">
                                    <?php echo $userName; ?>
                                </p>
                            </div>
                            
                            <?php if($isAdmin): ?>
                                <a href="<?php echo $adminLink; ?>" class="bg-slate-800 text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-slate-700 transition shadow-lg shadow-slate-300">
                                    <i class="fa-solid fa-shield-halved mr-1"></i> Admin
                                </a>
                            <?php endif; ?>

                            <a href="index.php?page=auth&action=logout" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-red-50 hover:text-red-500 transition" title="Đăng xuất">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="index.php?page=login" class="text-slate-600 hover:text-brand-700 font-semibold px-4 py-2 transition <?php echo isActive('login', $currentPage); ?>">Đăng nhập</a>
                        <a href="index.php?page=register" class="bg-slate-900 text-white px-6 py-2.5 rounded-full hover:bg-slate-800 transition shadow-lg text-sm font-bold flex items-center">
                            Đăng ký
                        </a>
                    <?php endif; ?>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-slate-600 hover:bg-slate-100 p-2 rounded-lg transition focus:outline-none">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-slate-100 p-4 shadow-xl absolute w-full left-0 top-20 z-40">
            <div class="flex flex-col space-y-2">
                <a href="index.php?page=home" class="block py-3 px-4 hover:bg-brand-50 rounded-lg text-slate-600 font-medium <?php echo isActive('home', $currentPage); ?>">Trang chủ</a>
                <a href="index.php?page=services" class="block py-3 px-4 hover:bg-brand-50 rounded-lg text-slate-600 font-medium <?php echo isActive('services', $currentPage); ?>">Dịch vụ</a>
                <a href="index.php?page=booking" class="block py-3 px-4 hover:bg-brand-50 rounded-lg text-slate-600 font-medium <?php echo isActive('booking', $currentPage); ?>">Đặt lịch</a>
                
                <div class="border-t border-slate-100 my-2 pt-2">
                    <?php if ($isLoggedIn): ?>
                        <div class="px-4 py-2 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-slate-500 uppercase">Xin chào</p>
                                <p class="font-bold text-brand-600"><?php echo $userName; ?></p>
                            </div>
                            <a href="index.php?page=auth&action=logout" class="text-red-500 text-sm font-bold">Đăng xuất</a>
                        </div>
                        <?php if($isAdmin): ?>
                            <a href="<?php echo $adminLink; ?>" class="block py-3 px-4 text-brand-600 font-bold hover:bg-brand-50 rounded-lg">Truy cập Admin</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="index.php?page=login" class="block py-3 px-4 text-slate-600 font-medium <?php echo isActive('login', $currentPage); ?>">Đăng nhập</a>
                        <a href="index.php?page=register" class="block py-3 px-4 text-brand-600 font-bold <?php echo isActive('register', $currentPage); ?>">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main id="app-content" class="flex-grow fade-in">