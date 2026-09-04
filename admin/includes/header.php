<header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 lg:px-8 z-10 sticky top-0">
    <div class="flex items-center">
        <button class="md:hidden text-slate-500 hover:text-slate-700 mr-4">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <h2 class="text-xl font-bold text-slate-800 hidden sm:block">
            <?php 
                echo isset($page_title) ? $page_title : 'Admin Dashboard'; 
            ?>
        </h2>
    </div>
    
    <div class="flex items-center gap-3">
        <div class="flex items-center bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
            <div class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center text-brand-700 font-bold mr-3 border border-brand-200">
                A
            </div>
            <span class="font-medium text-sm text-slate-700 mr-2">
                <?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Admin'); ?>
            </span>
        </div>
    </div>
</header>