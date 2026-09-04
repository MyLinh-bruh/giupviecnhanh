</main> 

    <footer class="bg-slate-900 text-slate-300 pt-16 pb-8 mt-auto">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

                <div>
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 mr-2 flex-shrink-0">
                            <img src="assets/images/logo.png" alt="Logo Footer" class="w-full h-full object-contain">
                        </div>
                        <span class="text-xl font-bold text-white">GiupViecNhanh</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Nền tảng kết nối người giúp việc chuyên nghiệp. Chúng tôi mang đến sự an tâm và sạch sẽ cho ngôi nhà của bạn.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-brand-600 transition text-white transition-transform hover:-translate-y-1">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-brand-600 transition text-white transition-transform hover:-translate-y-1">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-brand-600 transition text-white transition-transform hover:-translate-y-1">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Dịch vụ</h4>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a href="index.php?page=services" class="hover:text-brand-400 transition flex items-center group">
                                <i class="fa-solid fa-chevron-right text-xs mr-2 text-slate-600 group-hover:text-brand-500"></i> Dọn dẹp nhà cửa
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=services" class="hover:text-brand-400 transition flex items-center group">
                                <i class="fa-solid fa-chevron-right text-xs mr-2 text-slate-600 group-hover:text-brand-500"></i> Nấu ăn gia đình
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=services" class="hover:text-brand-400 transition flex items-center group">
                                <i class="fa-solid fa-chevron-right text-xs mr-2 text-slate-600 group-hover:text-brand-500"></i> Giặt ủi cao cấp
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=services" class="hover:text-brand-400 transition flex items-center group">
                                <i class="fa-solid fa-chevron-right text-xs mr-2 text-slate-600 group-hover:text-brand-500"></i> Vệ sinh sofa
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Hỗ trợ</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-brand-400 transition">Trung tâm trợ giúp</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-brand-400 transition">Điều khoản sử dụng</a></li>
                        
                    <li>
                        <a href="/giupviecnhanh/admin/index.php" class="text-emerald-500 font-bold hover:text-emerald-400 transition flex items-center">
                            <i class="fa-solid fa-user-shield mr-2"></i> Khu vực Quản trị
                        </a>
                    </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Liên hệ</h4>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start">
                            <i class="fa-solid fa-location-dot mt-1 mr-3 text-brand-500"></i>
                            <span>Tầng 12, Tòa nhà Landmark, TP.Đà Nẵng</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-phone mr-3 text-brand-500"></i>
                            <span class="text-lg font-bold text-white">1900 6868</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-envelope mr-3 text-brand-500"></i>
                            <span>contact giupviecnhanh@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500">
                <p>&copy; <?php echo date("Y"); ?> GiupViecNhanh Corp. All rights reserved.</p>
                <p>Designed for Professional Use.</p>
            </div>
        </div>
    </footer>

    <?php 
        if(function_exists('renderNotification')) {
            renderNotification(); 
        }
    ?>

    <script src="assets/js/main.js"></script>
</body>
</html>