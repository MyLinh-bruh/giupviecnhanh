<div class="bg-slate-50 py-16 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-brand-600 font-bold uppercase tracking-wider text-sm mb-2 block">Bảng giá dịch vụ</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Minh bạch - Chi tiết - Không phí ẩn</h2>
            <div class="w-20 h-1 bg-brand-500 mx-auto rounded-full mb-4"></div>
            <p class="text-slate-500 text-lg">Chọn gói dịch vụ phù hợp nhất với nhu cầu của gia đình bạn</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $index => $s): 
                    $bgColor = !empty($s['bg_color']) ? $s['bg_color'] : 'bg-green-50';
                    $textColor = !empty($s['text_color']) ? $s['text_color'] : 'text-green-600';
                    $icon = !empty($s['icon']) ? $s['icon'] : 'fa-star';

                    $priceValue = 'Liên hệ';
                    $priceUnit = '';
                    
                    if (!empty($s['price_text'])) {
                        $parts = explode('/', $s['price_text']);
                        $priceValue = $parts[0];
                        $priceUnit = isset($parts[1]) ? '/' . $parts[1] : '';
                    } elseif (isset($s['price'])) {
                        $priceValue = number_format($s['price'], 0, ',', '.') . 'đ';
                    }

                    $desc = !empty($s['description']) ? $s['description'] : 'Mô tả đang cập nhật';

                    $isPopular = ($index === 1); 

                    $cardClasses = $isPopular 
                        ? 'transform md:-translate-y-4 border-brand-200 ring-4 ring-brand-50 shadow-xl z-10' 
                        : 'border-slate-100 hover:shadow-xl hover:-translate-y-1 shadow-md';
                    
                    $btnClasses = $isPopular
                        ? 'bg-brand-600 text-white hover:bg-brand-700 shadow-lg shadow-brand-200'
                        : 'bg-white text-slate-700 border border-slate-200 hover:border-brand-300 hover:bg-slate-50';
                ?>
                    <div class="bg-white rounded-2xl overflow-hidden transition duration-300 flex flex-col border <?php echo $cardClasses; ?>">
                        <?php if ($isPopular): ?>
                            <div class="bg-brand-500 text-white text-center py-2 text-xs font-bold uppercase tracking-wider">Phổ biến nhất</div>
                        <?php endif; ?>
                        
                        <div class="p-8 flex-grow">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto <?php echo $bgColor . ' ' . $textColor; ?> rounded-full text-2xl mb-6 shadow-sm">
                                <i class="fa-solid <?php echo $icon; ?>"></i>
                            </div>

                            <h3 class="text-2xl font-bold text-center text-slate-800 mb-2"><?php echo htmlspecialchars($s['name']); ?></h3>
                            <p class="text-center text-slate-500 text-sm mb-6 min-h-[40px] line-clamp-2"><?php echo htmlspecialchars($desc); ?></p>

                            <div class="text-center mb-8 pb-8 border-b border-slate-100">
                                <span class="text-4xl font-bold text-slate-900"><?php echo $priceValue; ?></span>
                                <span class="text-slate-400 text-lg"><?php echo $priceUnit; ?></span>
                            </div>

                            <ul class="space-y-4 text-sm text-slate-600 mb-2">
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 text-green-500 flex items-center justify-center mt-0.5 mr-3">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </div>
                                    <span>Công cụ dụng cụ có sẵn</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 text-green-500 flex items-center justify-center mt-0.5 mr-3">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </div>
                                    <span>Nhân viên chuyên nghiệp</span>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 text-green-500 flex items-center justify-center mt-0.5 mr-3">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </div>
                                    <span>Hỗ trợ đổi người miễn phí</span>
                                </li>
                                <?php if($isPopular): ?>
                                    <li class="flex items-start">
                                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 text-green-500 flex items-center justify-center mt-0.5 mr-3">
                                            <i class="fa-solid fa-check text-xs"></i>
                                        </div>
                                        <span class="font-bold text-brand-700">Ưu tiên xếp lịch nhanh</span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        
                        <div class="p-4 bg-slate-50 border-t border-slate-100 mt-auto">
                            <a href="index.php?page=booking&service_id=<?php echo $s['id']; ?>" class="block text-center w-full py-3.5 rounded-xl font-bold transition transform active:scale-[0.98] <?php echo $btnClasses; ?>">
                                Chọn gói này
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-200 shadow-sm">
                    <i class="fa-solid fa-box-open text-4xl text-slate-300 mb-4"></i>
                    <p class="text-slate-500 text-lg font-medium">Hiện chưa có dịch vụ nào được cập nhật.</p>
                    <a href="index.php?page=home" class="text-brand-600 hover:underline mt-2 inline-block">Quay lại trang chủ</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="max-w-4xl mx-auto mt-20 text-center">
            <h3 class="text-xl font-bold text-slate-800 mb-4">Bạn cần tư vấn thêm?</h3>
            <p class="text-slate-500 mb-8">Đội ngũ hỗ trợ của chúng tôi luôn sẵn sàng giải đáp mọi thắc mắc của bạn.</p>
            <div class="flex justify-center gap-4">
                <a href="#" class="flex items-center px-6 py-3 bg-white border border-slate-200 rounded-lg text-slate-700 hover:border-brand-300 hover:text-brand-600 transition shadow-sm">
                    <i class="fa-solid fa-phone mr-2"></i> 1900 6868
                </a>
                <a href="#" class="flex items-center px-6 py-3 bg-white border border-slate-200 rounded-lg text-slate-700 hover:border-brand-300 hover:text-brand-600 transition shadow-sm">
                    <i class="fa-brands fa-facebook-messenger mr-2"></i> Chat ngay
                </a>
            </div>
        </div>
    </div>
</div>