<?php
if (!function_exists('h')) {
    function h($str) {
        return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
    }
}
?>

<div class="bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <h1 class="text-3xl font-bold text-center text-slate-800 mb-8 font-sans">Tìm Người Giúp Việc</h1>

        <form action="index.php" method="GET" class="max-w-5xl mx-auto bg-white p-3 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row gap-3 mb-10">
            <input type="hidden" name="page" value="staff">
            
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="search" value="<?php echo h($search ?? ''); ?>" 
                       class="w-full pl-11 pr-4 py-3 rounded-xl bg-transparent focus:outline-none text-slate-700 placeholder-slate-400 font-medium" 
                       placeholder="Tìm theo tên hoặc kỹ năng (VD: Nấu ăn, Dọn dẹp...)">
            </div>

            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar px-1">
                <?php 
                    $currGender = $gender ?? '';
                    $btnBase = "px-6 py-2.5 rounded-xl text-sm font-bold transition whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-1";
                    $btnActive = "bg-teal-700 text-white shadow-lg shadow-teal-200";
                    $btnInactive = "bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:border-slate-300";
                ?>
                
                <button type="submit" name="gender" value="" class="<?php echo $btnBase . ' ' . ($currGender == '' ? $btnActive : $btnInactive); ?>">
                    Tất cả
                </button>
                <button type="submit" name="gender" value="Nam" class="<?php echo $btnBase . ' ' . ($currGender == 'Nam' ? $btnActive : $btnInactive); ?>">
                    Nam
                </button>
                <button type="submit" name="gender" value="Nữ" class="<?php echo $btnBase . ' ' . ($currGender == 'Nữ' ? $btnActive : $btnInactive); ?>">
                    Nữ
                </button>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <?php if (!empty($staffList)): ?>
                <?php foreach ($staffList as $staff): 
                    $skills = !empty($staff['service_names']) ? explode(',', $staff['service_names']) : [];
                    
                    $isFemale = (trim($staff['gender']) === 'Nữ');
                    $genderPath = $isFemale ? 'women' : 'men';
                    $randId = ($staff['id'] * 7) % 70; 
                    $onlineAvatar = "https://randomuser.me/api/portraits/{$genderPath}/{$randId}.jpg";
                    
                    $localImage = "assets/images/" . h($staff['image']);
                ?>
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group flex flex-col h-full">

                        <div class="relative h-64 overflow-hidden bg-slate-100">
                            <img src="<?php echo $localImage; ?>" 
                                    alt="<?php echo h($staff['name']); ?>" 
                                    class="w-full h-full object-cover object-center transform group-hover:scale-105 transition duration-700"
                                    onerror="this.onerror=null; this.src='<?php echo $onlineAvatar; ?>';">

                            <div class="absolute top-4 right-4 bg-white px-2.5 py-1 rounded-lg shadow-sm flex items-center gap-1 text-xs font-bold text-slate-800 border border-slate-100">
                                <i class="fa-solid fa-star text-yellow-400"></i>
                                <span><?php echo $staff['rating']; ?></span>
                            </div>
                        </div>

                        <div class="p-6 flex flex-col flex-grow">
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-slate-900 mb-1 group-hover:text-teal-700 transition">
                                    <?php echo h($staff['name']); ?>
                                </h3>
                                <p class="text-slate-500 text-sm flex items-center gap-2">
                                    <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                                    <?php echo $staff['age']; ?> tuổi • <?php echo $staff['gender']; ?>
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-6">
                                <?php foreach ($skills as $skill): ?>
                                    <span class="px-3 py-1.5 rounded-full bg-teal-50 text-teal-800 text-xs font-medium border border-teal-100 hover:bg-teal-100 transition cursor-default">
                                        <?php echo h(trim($skill)); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>

                            <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100">
                                <div>
                                    <div class="text-teal-700 font-bold text-xl leading-none">
                                        <?php echo number_format((float)$staff['price'], 0, ',', '.'); ?> đ
                                    </div>
                                    <span class="text-xs text-slate-400 font-medium">/giờ</span>
                                </div>
                                <a href="index.php?page=booking&staff_id=<?php echo $staff['id']; ?>" 
                                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition shadow-md hover:shadow-lg hover:-translate-y-0.5 transform active:scale-95 flex items-center">
                                    Đặt lịch <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-20 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-50 mb-6 border border-slate-100">
                        <i class="fa-solid fa-magnifying-glass text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Không tìm thấy kết quả</h3>
                    <p class="text-slate-500 max-w-md mx-auto">Chúng tôi không tìm thấy nhân viên nào phù hợp với từ khóa "<strong><?php echo h($search); ?></strong>".</p>
                    <a href="index.php?page=staff" class="mt-8 inline-flex items-center px-6 py-3 bg-white border border-slate-200 rounded-full text-teal-700 font-bold hover:bg-teal-50 transition shadow-sm">
                        <i class="fa-solid fa-rotate-left mr-2"></i> Xóa bộ lọc
                    </a>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>