<?php
$current_page = basename($_SERVER['PHP_SELF']);
function isActive($page_name, $current_page) {
    if ($current_page === $page_name) return 'bg-brand-600 text-white shadow-lg shadow-brand-900/20';
    return 'hover:bg-slate-800 hover:text-white text-slate-300';
}
?>
<aside class="w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col hidden md:flex z-50 shadow-xl h-screen">
    
    <div class="p-6 flex items-center border-b border-slate-800 h-16 flex-shrink-0">
         <div class="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center text-white text-sm mr-3 shadow-lg shadow-brand-500/50">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h2 class="text-lg font-bold text-white tracking-wide">QUẢN TRỊ VIÊN</h2>
    </div>

    <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
        <p class="text-[10px] font-bold text-slate-500 uppercase mb-3 px-2 mt-2">Quản lý chung</p>
        <nav class="space-y-1">
            <a href="/GiupViecNhanh/admin/index.php" class="flex items-center px-4 py-3 rounded-lg transition group <?php echo isActive('index.php', $current_page); ?>">
                <i class="fa-solid fa-chart-pie w-6"></i> Tổng quan
            </a>
            <a href="/GiupViecNhanh/admin/services.php" class="flex items-center px-4 py-3 rounded-lg transition group <?php echo isActive('services.php', $current_page); ?>">
                <i class="fa-solid fa-list-check w-6"></i> Dịch vụ
            </a>
            <a href="/GiupViecNhanh/admin/bookings.php" class="flex items-center px-4 py-3 rounded-lg transition group <?php echo isActive('bookings.php', $current_page); ?>">
                <i class="fa-solid fa-calendar-check w-6"></i> Đặt lịch
            </a>
            <a href="/GiupViecNhanh/admin/users.php" class="flex items-center px-4 py-3 rounded-lg transition group <?php echo isActive('users.php', $current_page); ?>">
                <i class="fa-solid fa-users w-6"></i> Khách hàng
            </a>
        </nav>
    </div>

    <div class="p-4 border-t border-slate-800 bg-slate-900 flex-shrink-0">
         <a href="/GiupViecNhanh/public/index.php" class="flex items-center px-4 py-2 text-slate-400 hover:text-white transition mb-1 group">
             <i class="fa-solid fa-house w-6 group-hover:-translate-x-1 transition"></i> Về trang chủ
         </a>
         <a href="/GiupViecNhanh/public/index.php?page=auth&action=logout" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');" class="flex items-center px-4 py-2 text-red-400 hover:text-red-300 transition group">
             <i class="fa-solid fa-arrow-right-from-bracket w-6 group-hover:-translate-x-1 transition"></i> Đăng xuất
         </a>
    </div>
</aside>