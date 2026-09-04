<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $error_message = '';
    $success_message = '';

    if (isset($_SESSION['flash']['error'])) {
        $error_message = $_SESSION['flash']['error'];
        unset($_SESSION['flash']['error']);
    } elseif (isset($_SESSION['error'])) {
        $error_message = $_SESSION['error'];
        unset($_SESSION['error']);
    }

    if (isset($_SESSION['flash']['success'])) {
        $success_message = $_SESSION['flash']['success'];
        unset($_SESSION['flash']['success']);
    } elseif (isset($_SESSION['success'])) {
        $success_message = $_SESSION['success'];
        unset($_SESSION['success']);
    }
?>

<div id="auth-wrapper" class="min-h-screen flex items-center justify-center bg-slate-50 px-4 py-12">
    <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border border-slate-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-brand-400 to-teal-500"></div>
        
        <div class="text-center mb-8">
             <div class="w-12 h-12 bg-brand-100 rounded-xl flex items-center justify-center text-brand-600 text-xl mx-auto mb-4">
                <i class="fa-solid fa-user-lock"></i>
            </div>
            <h2 class="text-3xl font-bold text-slate-800">Chào mừng trở lại!</h2>
            <p class="text-slate-500 mt-2">Đăng nhập để quản lý lịch trình của bạn</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-medium flex items-center border border-red-100 animate-pulse">
                <i class="fa-solid fa-circle-exclamation mr-2 text-lg"></i>
                <span><?php echo $error_message; ?></span>
            </div>
        <?php endif; ?>

        <form action="index.php?page=auth&action=authenticate" method="POST" class="space-y-6">
            <div>
                <label class="block text-slate-600 text-sm font-bold mb-2 ml-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input type="email" name="email" class="w-full pl-11 pr-4 py-3.5 border border-slate-200 bg-slate-50 rounded-xl focus:bg-white focus:ring-4 focus:ring-brand-100 focus:border-brand-500 outline-none transition placeholder-slate-400" placeholder="name@example.com" required>
                </div>
            </div>
            <div>
                <label class="block text-slate-600 text-sm font-bold mb-2 ml-1">Mật khẩu</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" class="w-full pl-11 pr-4 py-3.5 border border-slate-200 bg-slate-50 rounded-xl focus:bg-white focus:ring-4 focus:ring-brand-100 focus:border-brand-500 outline-none transition placeholder-slate-400" placeholder="••••••••" required>
                </div>
            </div>
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-slate-600 cursor-pointer select-none">
                    <input type="checkbox" class="mr-2 rounded text-brand-600 focus:ring-brand-500 border-slate-300"> Ghi nhớ tôi
                </label>
                <a href="#" class="text-brand-600 font-bold hover:underline">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="w-full bg-brand-600 text-white font-bold py-3.5 rounded-xl hover:bg-brand-700 transition shadow-lg hover:shadow-brand-500/30 transform active:scale-[0.98]">
                Đăng nhập
            </button>
        </form>
        
        <p class="text-center mt-8 text-sm text-slate-600">
            Chưa có tài khoản? <a href="index.php?page=register" class="text-brand-600 font-bold hover:underline">Đăng ký ngay</a>
        </p>
    </div>
</div>