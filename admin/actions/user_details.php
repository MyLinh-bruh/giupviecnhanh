<?php
session_start();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../app/helpers/auth.php';

requireAdmin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = 'ID khách hàng không hợp lệ';
    header('Location: ../users.php');
    exit;
}

$id = $_GET['id'];
$database = new Database();
$db = $database->getConnection();

$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = 'Không tìm thấy khách hàng.';
    header('Location: ../users.php');
    exit;
}

$bookings = [];
try {
    $query = "SELECT b.*, s.name as service_name, st.name as staff_name 
              FROM bookings b 
              LEFT JOIN services s ON b.service_id = s.id 
              LEFT JOIN staff st ON b.staff_id = st.id 
              WHERE b.user_id = :uid 
              ORDER BY b.created_at DESC";
              
    $stmt = $db->prepare($query);
    $stmt->bindParam(':uid', $id);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Lỗi: " . $e->getMessage());
}

$latestBooking = $bookings[0] ?? null;

$displayPhone = 'Chưa có thông tin';
$displayAddress = 'Chưa có thông tin';

if ($latestBooking) {
    $displayPhone = $latestBooking['customer_phone'] ?? 'Chưa cập nhật';
    $displayAddress = $latestBooking['address'] ?? 'Chưa cập nhật';
}

$totalSpent = 0;

foreach ($bookings as $b) {
    $totalSpent += (float)($b['total_amount'] ?? 0); 
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết khách hàng: <?php echo htmlspecialchars($user['name']); ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: { 500: '#10b981', 600: '#059669', 900: '#064e3b' } },
                    fontFamily: { sans: ['Be Vietnam Pro', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body{font-family:'Be Vietnam Pro',sans-serif} 
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; } 
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; } 
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

        .col-id { width: 8%; }
        .col-service { width: 30%; }
        .col-time { width: 12%; }
        .col-staff { width: 20%; }
        .col-total { width: 15%; }
        .col-status { width: 15%; }
        .history-table { min-width: 900px; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-6 lg:p-10">

    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <a href="../users.php" class="inline-flex items-center text-slate-500 hover:text-brand-600 transition group font-medium">
                <i class="fa-solid fa-arrow-left mr-2 group-hover:-translate-x-1 transition"></i> Quay lại danh sách
            </a>
            
             <a href="delete_user.php?id=<?php echo $user['id']; ?>" 
                onclick="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn xóa khách hàng này?')" 
                class="inline-flex items-center bg-white text-red-600 hover:bg-red-50 border border-red-200 px-4 py-2 rounded-lg font-bold transition shadow-sm">
                <i class="fa-solid fa-trash-can mr-2"></i> Xóa tài khoản
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden mb-8 relative">
            <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-r from-brand-500 to-teal-400 opacity-90"></div>
            
            <div class="p-8 relative z-10 flex flex-col md:flex-row items-center md:items-end gap-6 mt-12">
                <div class="w-32 h-32 rounded-full bg-white p-1 shadow-lg -mt-16">
                    <div class="w-full h-full rounded-full bg-brand-100 text-brand-600 flex items-center justify-center font-bold text-4xl border-4 border-white">
                        <?php echo strtoupper(mb_substr($user['name'], 0, 1)); ?>
                    </div>
                </div>
                
                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-col md:flex-row items-center gap-3 mb-2 justify-center md:justify-start">
                        <h1 class="text-3xl font-bold text-slate-800"><?php echo htmlspecialchars($user['name']); ?></h1>
                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide shadow-sm border border-blue-200">
                            <?php echo htmlspecialchars($user['role']); ?>
                        </span>
                    </div>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 text-slate-600 font-medium">
                        <div class="flex items-center">
                            <i class="fa-regular fa-envelope mr-2 text-brand-500"></i>
                            <?php echo htmlspecialchars($user['email']); ?>
                        </div>
                        <div class="flex items-center">
                            <i class="fa-solid fa-phone mr-2 text-brand-500"></i>
                            <?php echo htmlspecialchars($displayPhone); ?>
                        </div>
                    </div>
                    <?php if(!$latestBooking): ?>
                        <p class="text-xs text-orange-500 mt-2 italic">* Thông tin liên hệ sẽ được cập nhật tự động khi khách hàng đặt dịch vụ đầu tiên.</p>
                    <?php endif; ?>
                </div>
                <div class="flex gap-6 mt-6 md:mt-0">
                    <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="text-2xl font-bold text-brand-600"><?php echo count($bookings); ?></div>
                        <div class="text-xs font-bold text-slate-400 uppercase">Đơn đặt lịch</div>
                    </div>
                    <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-200">
                           <div class="text-2xl font-bold text-brand-600"><?php echo number_format($totalSpent, 0, ',', '.'); ?>đ</div>
                        <div class="text-xs font-bold text-slate-400 uppercase">Tổng chi tiêu</div>
                    </div>
                </div>
            </div>
            <div class="p-8 bg-slate-50 border-t border-slate-200 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Ngày tham gia</label>
                    <div class="text-base font-semibold text-slate-800">
                        <?php echo date('d/m/Y - H:i', strtotime($user['created_at'])); ?>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Địa chỉ (Từ đơn gần nhất)</label>
                    <div class="text-base font-semibold text-slate-800">
                        <?php echo htmlspecialchars($displayAddress); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-clock-rotate-left mr-3 text-brand-500 p-2 bg-brand-100 rounded-lg"></i>
                    Lịch sử hoạt động
                </h2>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-slate-500">Tổng: <?php echo count($bookings); ?> đơn</span>
                </div>
            </div>
            
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-sm history-table">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="p-5 pl-8 border-b border-slate-200 col-id">Mã đơn</th>
                            <th class="p-5 border-b border-slate-200 col-service">Dịch vụ</th>
                            <th class="p-5 border-b border-slate-200 col-time">Thời gian</th>
                            <th class="p-5 border-b border-slate-200 col-staff">Nhân viên</th> 
                            <th class="p-5 border-b border-slate-200 text-right col-total">Tổng tiền</th>
                            <th class="p-5 border-b border-slate-200 text-center col-status">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (count($bookings) > 0): ?>
                            <?php foreach ($bookings as $b): 
                                $st = $b['status'] ?? 'pending';
                                $statusBadge = '';
                                if($st === 'confirmed') 
                                    $statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">Đã duyệt</span>';
                                elseif($st === 'cancelled') 
                                    $statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">Đã hủy</span>';
                                else 
                                    $statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">Chờ xử lý</span>';
                            ?>
                            <tr class="hover:bg-slate-50 transition duration-150">
                                <td class="p-5 pl-8 font-mono font-bold text-brand-600 col-id">
                                    #<?php echo $b['id']; ?>
                                </td>
                                <td class="p-5 col-service">
                                    <div class="font-bold text-slate-800 text-base mb-1">
                                        <?php echo htmlspecialchars($b['service_name'] ?? 'Dịch vụ đã xóa'); ?>
                                    </div>
                                    <div class="text-slate-500 text-xs flex items-center">
                                        <i class="fa-solid fa-location-dot mr-1"></i>
                                        <span class="truncate max-w-xs" title="<?php echo htmlspecialchars($b['address']); ?>">
                                            <?php echo htmlspecialchars($b['address']); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="p-5 text-slate-600 col-time">
                                    <div class="font-medium text-slate-800 mb-1">
                                        <?php echo date('d/m/Y', strtotime($b['booking_date'])); ?>
                                    </div>
                                    <div class="text-xs">
                                        <?php echo date('H:i', strtotime($b['start_time'])); ?>
                                    </div>
                                </td>
                                <td class="p-5 col-staff">
                                    <?php if (!empty($b['staff_name'])): ?>
                                        <div class="font-semibold text-teal-600 flex items-center">
                                            <i class="fa-solid fa-user-check mr-1 text-teal-400"></i>
                                            <?php echo htmlspecialchars($b['staff_name']); ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-sm text-red-500 font-medium">
                                            Chưa gán
                                        </div>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="p-5 text-right font-bold text-slate-800 col-total">
                                    <?php echo number_format($b['total_amount'] ?? 0, 0, ',', '.'); ?>đ
                                </td>
                                <td class="p-5 text-center col-status">
                                    <?php echo $statusBadge; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="p-16 text-center text-slate-400 italic">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-link-slash text-4xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-600 mb-1">Chưa có lịch sử hoạt động</h3>
                                        <p class="max-w-md">
                                            Khách hàng này chưa có đơn hàng nào. Thông tin SĐT và Địa chỉ sẽ hiển thị sau khi họ đặt dịch vụ đầu tiên.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>