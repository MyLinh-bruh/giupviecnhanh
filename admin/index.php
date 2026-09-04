<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';

if (file_exists(__DIR__ . '/../app/models/User.php')) {
    require_once __DIR__ . '/../app/models/User.php';
}

if (function_exists('requireAdmin')) {
    requireAdmin();
} else {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: /GiupViecNhanh/public/index.php?page=auth&action=login');
        exit;
    }
}

$database = new Database();
$db = $database->getConnection();

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$totalCustomers = 0;
$totalBookings = 0;
$totalServices = 0;
$newCustomersThisWeek = 0;
$recentBookings = [];
$dbError = null;

try {
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'user' OR role = 'customer'");
    $stmt->execute();
    $totalCustomers = $stmt->fetchColumn();

    $stmt = $db->prepare("SELECT COUNT(*) FROM bookings");
    $stmt->execute();
    $totalBookings = $stmt->fetchColumn();

    $stmt = $db->prepare("SELECT COUNT(*) FROM services");
    $stmt->execute();
    $totalServices = $stmt->fetchColumn();

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $sevenDaysAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE (role = 'user' OR role = 'customer') AND created_at >= :sevenDaysAgo");
    $stmt->bindParam(':sevenDaysAgo', $sevenDaysAgo);
    $stmt->execute();
    $newCustomersThisWeek = $stmt->fetchColumn();

    $query = "SELECT b.id, b.booking_date, b.start_time, b.address, b.status, 
                      u.name as user_name, b.customer_phone as customer_phone, 
                      s.name as service_name,
                      st.name as staff_name,
                      b.total_amount
             FROM bookings b 
             LEFT JOIN users u ON b.user_id = u.id 
             LEFT JOIN services s ON b.service_id = s.id 
             LEFT JOIN staff st ON b.staff_id = st.id
             ORDER BY b.created_at DESC LIMIT 10";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $recentBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $dbError = $e->getMessage();
    error_log("Admin Index Error: " . $dbError);
}

$page_title = "Tổng quan";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Giúp Việc Nhanh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: { colors: { brand: { 500: '#10b981', 600: '#059669', 900: '#064e3b' } }, fontFamily: { sans: ['Be Vietnam Pro', 'sans-serif'] } }
            }
        }
    </script>
    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .table-container { overflow-x: auto; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="flex-1 flex flex-col min-w-0 bg-white">
         <?php include __DIR__ . '/includes/header.php'; ?>
        <main class="flex-1 overflow-y-auto p-6 lg:p-8 bg-slate-50">
            <?php if ($dbError): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md flex items-center">
                    <i class="fa-solid fa-triangle-exclamation mr-3 text-2xl"></i> 
                    <div>
                        <p class="font-bold">LỖI KHI LẤY ĐƠN ĐẶT LỊCH GẦN ĐÂY:</p>
                        <p class="text-sm break-all"><?php echo htmlspecialchars($dbError); ?></p>
                        <p class="text-xs mt-1 font-medium">Vui lòng kiểm tra lại cấu trúc bảng `staff` và `bookings`.</p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-inner"><i class="fa-solid fa-cart-shopping"></i></div>
                        <div>
                            <p class="text-slate-500 text-sm font-bold uppercase tracking-wider mb-1">Tổng đơn đặt lịch</p>
                            <h3 class="text-3xl font-extrabold text-slate-800"><?php echo number_format($totalBookings); ?></h3>
                            <p class="text-green-500 text-xs font-bold mt-1 flex items-center"><i class="fa-solid fa-arrow-up mr-1"></i> +12.5%</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-2xl shadow-inner"><i class="fa-solid fa-broom"></i></div>
                        <div>
                            <p class="text-slate-500 text-sm font-bold uppercase tracking-wider mb-1">Dịch vụ</p>
                            <h3 class="text-3xl font-extrabold text-slate-800"><?php echo number_format($totalServices); ?></h3>
                            <p class="text-slate-400 text-xs font-bold mt-1">Đang hoạt động</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl shadow-inner"><i class="fa-solid fa-user-group"></i></div>
                        <div>
                            <p class="text-slate-500 text-sm font-bold uppercase tracking-wider mb-1">Khách hàng mới</p>
                            <h3 class="text-3xl font-extrabold text-slate-800"><?php echo number_format($newCustomersThisWeek); ?></h3>
                            <p class="text-slate-400 text-xs font-bold mt-1">Tuần này</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-lg">Đơn đặt lịch gần đây</h3>
                    <a href="/GiupViecNhanh/admin/bookings.php" class="text-brand-600 text-sm font-bold hover:text-brand-700 hover:underline transition">Xem tất cả</a>
                </div>
                
                <div class="table-container">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <tr>
                                <th class="p-4 border-b border-slate-100">ID</th>
                                <th class="p-4 border-b border-slate-100">Khách hàng</th>
                                <th class="p-4 border-b border-slate-100">Dịch vụ</th>
                                <th class="p-4 border-b border-slate-100">Thời gian</th>
                                <th class="p-4 border-b border-slate-100">Địa chỉ</th>
                                
                                <th class="p-4 border-b border-slate-100 w-40">Nhân viên</th> 
                                
                                <th class="p-4 border-b border-slate-100 text-center">Trạng thái</th>
                                
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php if (count($recentBookings) > 0): ?>
                                <?php foreach ($recentBookings as $row): 
                                    $displayName = !empty($row['user_name']) ? $row['user_name'] : 'Khách vãng lai';
                                    
                                    $rawStatus = $row['status'] ?? 'pending';
                                    $dbStatus = strtolower(trim($rawStatus));
                                    
                                    $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                    $statusLabel = 'Chờ xử lý';

                                    if ($dbStatus == 'confirmed') {
                                        $statusClass = 'bg-green-100 text-green-800 border-green-200';
                                        $statusLabel = 'Đã duyệt';
                                    } elseif ($dbStatus == 'cancelled') {
                                        $statusClass = 'bg-red-100 text-red-800 border-red-200';
                                        $statusLabel = 'Đã hủy';
                                    }
                
                                    $staffName = $row['staff_name'] ?? null;
                                    $totalAmount = number_format((float)($row['total_amount'] ?? 0), 0, ',', '.') . 'đ';
                                ?>
                                    <tr class="hover:bg-slate-50 transition duration-150">
                                        <td class="p-4 font-medium text-slate-900">#<?php echo $row['id']; ?></td>
                                        <td class="p-4">
                                            <div class="font-bold text-slate-700"><?php echo htmlspecialchars($displayName); ?></div>
                                            <?php if(!empty($row['customer_phone'])): ?>
                                                <div class="text-xs text-slate-500 mt-0.5"><?php echo htmlspecialchars($row['customer_phone']); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-4 text-slate-600">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-50 text-brand-700 border border-brand-100">
                                                <?php echo htmlspecialchars($row['service_name'] ?? 'Dịch vụ đã xóa'); ?>
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <div class="text-slate-700 font-medium"><?php echo isset($row['booking_date']) ? date('d/m/Y', strtotime($row['booking_date'])) : '--'; ?></div>
                                            <div class="text-xs text-slate-500 mt-0.5"><i class="fa-regular fa-clock mr-1"></i><?php echo isset($row['start_time']) ? date('H:i', strtotime($row['start_time'])) : '--:--'; ?></div>
                                        </td>
                                        <td class="p-4 text-slate-600 truncate max-w-xs" title="<?php echo htmlspecialchars($row['address'] ?? ''); ?>">
                                            <?php echo htmlspecialchars($row['address'] ?? ''); ?>
                                        </td>
                                        
                                        <td class="p-4 text-xs">
                                            <?php if (!empty($staffName)): ?>
                                                <div class="text-sm font-bold text-teal-700 mb-1 flex items-center">
                                                    <i class="fa-solid fa-user-check mr-1 text-teal-500"></i> <?php echo htmlspecialchars($staffName); ?>
                                                </div>
                                                <div class="text-xs font-mono text-slate-600">
                                                    Giá tiền: <?php echo $totalAmount; ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-xs text-red-500 font-medium">
                                                    Chưa gán NV
                                                </div>
                                                <div class="text-xs font-mono text-slate-600">
                                                    Giá tiền: <?php echo $totalAmount; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="p-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border <?php echo $statusClass; ?>">
                                                <?php echo htmlspecialchars($statusLabel); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="p-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fa-solid fa-inbox text-4xl text-slate-300 mb-3"></i>
                                            <p>Chưa có đơn đặt lịch nào.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>