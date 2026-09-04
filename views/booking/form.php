<?php
$user = function_exists('currentUser') ? currentUser() : null;
$default_name = $user['name'] ?? '';

$selectedStaff = $staff ?? null;

$staffName = $selectedStaff ? htmlspecialchars($selectedStaff['name']) : null;
$staffPrice = $selectedStaff ? number_format((float)$selectedStaff['price'], 0, ',', '.') : null;

if (!function_exists('h')) {
    function h($str) {
        return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
    }
}
?>
<div id="booking-wrapper" class="bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row border border-slate-100">
            
            <div class="md:w-5/12 bg-slate-900 text-white p-8 md:p-12 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-brand-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

                 <div class="relative z-10">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-3 p-1">
                            <img src="assets/images/logo.png" alt="Logo GiupViecNhanh" 
                                 class="w-full h-full object-contain"
                                 onerror="this.onerror=null; this.src='https://placehold.co/48x48/FFFFFF/000000?text=GVN';"> 
                        </div>
                        <span class="font-bold text-xl tracking-wide">GiupViecNhanh</span>
                    </div>

                    <h3 class="text-3xl font-bold mb-4 leading-tight">Đặt lịch dễ dàng,<br>Sống thảnh thơi.</h3>
                    <p class="text-slate-400 text-sm mb-8 leading-relaxed">
                        Chỉ mất 2 phút để điền thông tin. Đội ngũ CSKH sẽ gọi lại xác nhận trong vòng 10 phút làm việc.
                    </p>
                    
                    <?php if ($selectedStaff): ?>
                    <div class="bg-brand-600/50 backdrop-blur rounded-xl p-4 border border-brand-700 mb-6 shadow-lg shadow-brand-900/20">
                        <p class="text-xs text-slate-300 uppercase font-bold tracking-wider mb-2">Đang chọn nhân viên</p>
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-xl text-white"><?php echo $staffName; ?></h4>
                            <span class="font-bold text-xl text-yellow-300"><?php echo $staffPrice; ?>đ</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1 italic">
                            Giá này là giá cơ bản của nhân viên.
                        </p>
                    </div>
                    <?php endif; ?>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center mr-4 flex-shrink-0 border border-slate-700">
                                <i class="fa-solid fa-phone text-brand-400"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Hotline hỗ trợ</p>
                                <p class="font-bold text-lg text-white">1900 6868</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center mr-4 flex-shrink-0 border border-slate-700">
                                <i class="fa-solid fa-envelope text-brand-400"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Email liên hệ</p>
                                <p class="font-medium text-slate-300">giupviecnhanh@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-12 pt-8 border-t border-slate-800">
                    <div class="bg-slate-800/50 backdrop-blur rounded-xl p-4 border border-slate-700">
                        <div class="flex text-yellow-400 mb-2 text-xs">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-xs text-slate-300 italic mb-3">"Tôi rất hài lòng với thái độ phục vụ của nhân viên. Nhà cửa sạch sẽ tinh tươm, giá cả lại rất hợp lý."</p>
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full bg-brand-500 text-xs flex items-center justify-center mr-2 font-bold">H</div>
                            <span class="text-xs font-bold text-white">Chị Hương - Thanh Khê Phường2 </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:w-7/12 p-8 md:p-12 bg-white relative">
                
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Thông tin đặt dịch vụ</h2>
                <p class="text-slate-500 text-sm mb-8">Vui lòng điền đầy đủ thông tin bên dưới.</p>
                
                <?php 
                if (function_exists('getFlash')) {
                    $success_message = getFlash('success');
                    if ($success_message): ?>
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-start" role="alert">
                            <i class="fa-solid fa-circle-check mt-1 mr-3"></i>
                            <div>
                                <p class="font-bold">Thành công!</p>
                                <p class="text-sm"><?php echo htmlspecialchars($success_message); ?></p>
                            </div>
                        </div>
                    <?php endif;
                } 
                ?>

                <form action="index.php?page=booking&action=store" method="POST" class="space-y-5">
                    
                    <?php if ($selectedStaff): ?>
                        <input type="hidden" name="staff_id" value="<?php echo $selectedStaff['id']; ?>">
                        <div class="bg-teal-50 border border-teal-100 text-teal-700 p-3 rounded-lg text-sm font-medium">
                            Đơn này sẽ được nhận bởi nhân viên cho <?php echo $staffName; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="group">
                            <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Họ và tên <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-regular fa-user"></i>
                                </span>
                                <input type="text" name="customer_name" value="<?php echo htmlspecialchars($default_name); ?>" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition placeholder-slate-400" placeholder="Nguyễn Văn A" required>
                            </div>
                        </div>
                        <div class="group">
                            <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Số điện thoại <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-phone"></i>
                                </span>
                                <input type="tel" name="customer_phone" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition placeholder-slate-400" placeholder="09xxxxxxx" required>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Loại dịch vụ <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-list-check"></i>
                            </span>
                            <select name="service_id" class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition appearance-none cursor-pointer">
                                <option value="" disabled selected>-- Chọn dịch vụ --</option>
                                <?php if (!empty($services)): ?>
                                    <?php foreach($services as $s): 
                                        $selected = (isset($_GET['service_id']) && $_GET['service_id'] == $s['id']) ? 'selected' : '';
                                        $priceDisplay = !empty($s['price_text']) ? " ({$s['price_text']})" : "";
                                    ?>
                                        <option value="<?php echo $s['id']; ?>" <?php echo $selected; ?>>
                                            <?php echo htmlspecialchars($s['name']) . $priceDisplay; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>
                                        <?php echo $selectedStaff ? 'Không có dịch vụ phù hợp' : 'Chưa có dịch vụ nào'; ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        <?php if ($selectedStaff): ?>
                        <p class="text-xs text-slate-400 mt-1 ml-1 italic">
                            Các dịch vụ mà <?php echo $staffName; ?> có thể nhận.
                        </p>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Ngày làm việc <span class="text-red-500">*</span></label>
                            <input type="date" name="booking_date" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition cursor-pointer text-slate-600" required>
                        </div>
                        <div>
                            <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Giờ bắt đầu <span class="text-red-500">*</span></label>
                            <input type="time" name="start_time" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition cursor-pointer text-slate-600" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-600 text-xs font-bold uppercase tracking-wider mb-2 ml-1">Địa chỉ chi tiết <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute top-3 left-3 text-slate-400">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <textarea name="address" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition h-24 resize-none placeholder-slate-400" placeholder="Số nhà, tên đường, phường/xã, quận/huyện..." required></textarea>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-brand-600 text-white font-bold py-4 rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-500/30 transform active:scale-[0.98] flex items-center justify-center group">
                            Xác nhận đặt lịch
                            <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                        <p class="text-center text-xs text-slate-400 mt-4">
                            Bằng việc đặt lịch, bạn đồng ý với <a href="#" class="text-brand-600 hover:underline">Điều khoản dịch vụ</a> của chúng tôi.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>