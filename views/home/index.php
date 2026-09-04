<?php
$featuredServices = [];
$allServices = $services ?? [];

if (!empty($allServices)) {
    $featuredServices = array_slice($allServices, 0, 4);
}

function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<section class="relative bg-slate-50 pt-16 pb-24 overflow-hidden">
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-brand-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    
    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="lg:w-1/2 slide-up">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-bold uppercase tracking-wide mb-6">
                    <span class="w-2 h-2 bg-brand-500 rounded-full mr-2 animate-pulse"></span> Số 1 về Uy tín
                </div>
                <h1 class="text-5xl lg:text-6xl font-bold text-slate-900 leading-tight mb-6">
                    Không gian sạch<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-teal-500">Sống trọn vẹn</span>
                </h1>
                <p class="text-lg text-slate-600 mb-8 leading-relaxed max-w-lg">
                    Trải nghiệm dịch vụ giúp việc chuyên nghiệp chuẩn 5 sao Nhân viên được đào tạo bài bản, lý lịch rõ ràng, tận tâm phục vụ
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="index.php?page=booking" class="px-8 py-4 bg-brand-600 text-white font-bold rounded-xl shadow-lg shadow-brand-200 hover:bg-brand-700 hover:shadow-xl transition transform hover:-translate-y-1 text-center">
                        Đặt lịch ngay
                    </a>
                    <a href="index.php?page=services" class="px-8 py-4 bg-white text-slate-700 font-bold rounded-xl border border-slate-200 hover:border-brand-300 hover:bg-slate-50 transition text-center flex items-center justify-center">
                        <i class="fa-solid fa-play-circle text-brand-600 mr-2 text-xl"></i> Xem bảng giá
                    </a>
                </div>
                <div class="mt-10 flex items-center gap-6 text-sm text-slate-500 font-medium">
                    <div class="flex items-center"><i class="fa-solid fa-check text-brand-500 mr-2"></i> Bảo hiểm đền bù</div>
                    <div class="flex items-center"><i class="fa-solid fa-check text-brand-500 mr-2"></i> Đào tạo bài bản</div>
                    <div class="flex items-center"><i class="fa-solid fa-check text-brand-500 mr-2"></i> Giá niêm yết</div>
                </div>
            </div>
            
            <div class="lg:w-1/2 relative fade-in">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white">
                    <img src="assets/images/hero_cleaner.jpg" alt="Cleaner" class="w-full h-auto object-cover transform hover:scale-105 transition duration-700" loading="lazy">
                    <div class="absolute bottom-6 left-6 right-6 bg-white/90 backdrop-blur-md p-4 rounded-xl shadow-lg border border-white/50 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                            <i class="fa-solid fa-user-check text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">10,000+ Khách hàng</p>
                            <p class="text-xs text-slate-500">Đã tin tưởng sử dụng dịch vụ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-brand-600 font-bold uppercase tracking-wider text-sm mb-2 block">Dịch vụ của chúng tôi</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Dịch vụ nổi bật</h2>
            <div class="w-20 h-1 bg-brand-500 mx-auto rounded-full mb-4"></div>
            <p class="text-slate-500 text-lg">Chọn gói dịch vụ phù hợp với nhu cầu của gia đình bạn</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php if (!empty($featuredServices)): ?>
                <?php foreach($featuredServices as $s): 
                    $bgColor = !empty($s['bg_color']) ? $s['bg_color'] : 'bg-green-50';
                    $textColor = !empty($s['text_color']) ? $s['text_color'] : 'text-green-600';
                    $icon = !empty($s['icon']) ? $s['icon'] : 'fa-star';
                    $displayPrice = !empty($s['price_text']) ? h($s['price_text']) : 'Liên hệ';
                    $desc = !empty($s['description']) ? h($s['description']) : 'Mô tả đang cập nhật';
                ?>
                    <div onclick="window.location.href='index.php?page=booking&service_id=<?= $s['id'] ?>'" class="group bg-white rounded-2xl p-8 border border-slate-100 hover:border-brand-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 cursor-pointer relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 <?= $bgColor ?> rounded-full opacity-50 transition-transform group-hover:scale-150"></div>

                        <div class="w-14 h-14 <?= $bgColor . ' ' . $textColor ?> rounded-xl flex items-center justify-center text-2xl mb-6 relative z-10">
                            <i class="fa-solid <?= $icon ?>"></i>
                        </div>

                        <h3 class="text-xl font-bold text-slate-800 mb-3 relative z-10 group-hover:text-brand-700 transition">
                            <?= h($s['name']) ?>
                        </h3>
                        <p class="text-slate-500 text-sm mb-6 leading-relaxed relative z-10 line-clamp-2 h-10 overflow-hidden">
                            <?= $desc ?>
                        </p>

                        <div class="flex items-center justify-between relative z-10 pt-4 border-t border-slate-50">
                            <span class="font-bold text-brand-600"><?= $displayPrice ?></span>
                            <span class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-brand-500 group-hover:text-white transition">
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-4 text-center py-10">
                    <p class="text-slate-500">Chưa có dịch vụ nào được cập nhật</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-20">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="bg-slate-900 rounded-3xl p-10 md:p-16 relative overflow-hidden text-center md:text-left flex flex-col md:flex-row items-center justify-between shadow-2xl">
             <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
             
             <div class="relative z-10 max-w-2xl mb-8 md:mb-0">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Sẵn sàng trải nghiệm dịch vụ?</h2>
                <p class="text-slate-400 text-lg">Đăng ký ngay hôm nay để nhận ưu đãi giảm giá 20% cho lần đặt lịch đầu tiên</p>
             </div>
             <div class="relative z-10">
                <a href="index.php?page=register" class="inline-block bg-brand-500 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-brand-400 transition shadow-lg shadow-brand-900/50 transform hover:-translate-y-1">
                    Đăng ký thành viên
                </a>
             </div>
        </div>
    </div>
</section>