<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/app.php'; 
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/helpers/utils.php';

requireAdmin(); 

$showDeletedAlert = $_SESSION['deleted_booking'] ?? false;
unset($_SESSION['deleted_booking']);

$db = (new Database())->getConnection();
$bookings = [];
$page_title = "Quản lý Đặt lịch";

$statusFilter = $_GET['status'] ?? '';
$filters = [
    'confirmed' => "WHERE b.status = 'confirmed'",
    'cancelled' => "WHERE b.status = 'cancelled'",
    'pending'   => "WHERE (b.status = 'pending' OR b.status IS NULL)"
];
$whereSQL = $filters[$statusFilter] ?? "";

$badgeConfig = [
    'confirmed' => ['label' => 'Đã duyệt',  'class' => 'bg-green-100 text-green-800 border-green-200'],
    'cancelled' => ['label' => 'Đã hủy',    'class' => 'bg-red-100 text-red-800 border-red-200'],
    'pending'   => ['label' => 'Chờ xử lý', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200']
];

try {
    $query = "SELECT b.*, 
                     s.name as service_name, 
                     u.name as user_name,
                     st.name as staff_name 
              FROM bookings b 
              LEFT JOIN services s ON b.service_id = s.id 
              LEFT JOIN users u ON b.user_id = u.id
              LEFT JOIN staff st ON b.staff_id = st.id
              $whereSQL
              ORDER BY b.created_at DESC";
            
    $bookings = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
}

function getBtnClass($current, $target) {
    return $current === $target 
        ? 'bg-slate-800 text-white border-slate-800' 
        : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-100'; 
}
function getColorBtnClass($current, $target, $color) {
    return $current === $target
        ? "bg-$color-600 text-white border-$color-600"
        : "bg-white text-$color-600 border-$color-200 hover:bg-$color-50";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đặt lịch - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { brand: { 500: '#10b981', 600: '#059669', 900: '#064e3b' } }, fontFamily: { sans: ['Be Vietnam Pro', 'sans-serif'] } } }
        }
    </script>
    <style> 
        body { font-family: 'Be Vietnam Pro', sans-serif; } 
        .table-container { overflow-x: auto; }
        .table-container::-webkit-scrollbar { height: 8px; }
        .table-container::-webkit-scrollbar-track { background: #f1f5f9; }
        .table-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        @keyframes bounce-in { 0% { transform: translateY(-20px); opacity: 0; } 50% { transform: translateY(0); opacity: 1; } 70% { transform: translateY(-5px); } 100% { transform: translateY(0); } }
        .animate-bounce-in { animation: bounce-in 0.5s ease-out; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="flex-1 flex flex-col min-w-0 bg-white">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="flex-1 overflow-y-auto p-6 lg:p-8 bg-slate-50">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Danh sách đơn đặt lịch</h1>
                    <p class="text-slate-500 text-sm mt-1">Theo dõi và xử lý các yêu cầu dịch vụ từ khách hàng</p>
                </div>
            </div>
            
            <div class="flex justify-end gap-2 mb-6">
                <a href="bookings.php" class="px-4 py-2.5 rounded-xl text-sm font-bold transition flex items-center shadow-md border <?php echo getBtnClass($statusFilter, ''); ?>">
                   <i class="fa-solid fa-list-ul mr-2"></i> Tất cả
                </a>
                <a href="bookings.php?status=confirmed" class="px-4 py-2.5 rounded-xl text-sm font-bold transition flex items-center shadow-md border <?php echo getColorBtnClass($statusFilter, 'confirmed', 'green'); ?>">
                   <i class="fa-solid fa-check mr-2"></i> Đơn đã duyệt
                </a>
                <a href="bookings.php?status=pending" class="px-4 py-2.5 rounded-xl text-sm font-bold transition flex items-center shadow-md border <?php echo ($statusFilter == 'pending') ? 'bg-yellow-500 text-white border-yellow-500' : 'bg-white text-yellow-600 border-yellow-200 hover:bg-yellow-50'; ?>">
                   <i class="fa-solid fa-clock mr-2"></i> Chờ xử lý
                </a>
                <a href="bookings.php?status=cancelled" class="px-4 py-2.5 rounded-xl text-sm font-bold transition flex items-center shadow-md border <?php echo getColorBtnClass($statusFilter, 'cancelled', 'red'); ?>">
                   <i class="fa-solid fa-xmark fa-lg mr-2"></i> Đơn đã hủy
                </a>
            </div>

            <?php if ($showDeletedAlert): ?>
                <div id="deleted-alert" class="fixed top-4 right-4 bg-slate-800 text-white font-bold px-4 py-3 rounded-lg shadow-xl z-50 flex items-center animate-bounce-in">
                    <i class="fa-solid fa-trash-can mr-2 text-red-400"></i> Đã xóa đơn đặt lịch thành công!
                </div>
                <script>setTimeout(() => { document.getElementById('deleted-alert')?.remove() }, 3500);</script>
            <?php endif; ?>

            <?php foreach (['success' => 'circle-check', 'error' => 'triangle-exclamation'] as $type => $icon): ?>
                <?php if (function_exists('getFlash') && $msg = getFlash($type)): ?>
                    <div class="bg-<?php echo ($type=='success'?'green':'red'); ?>-50 border-l-4 border-<?php echo ($type=='success'?'green':'red'); ?>-500 text-<?php echo ($type=='success'?'green':'red'); ?>-700 p-4 mb-6 rounded shadow-sm flex items-center">
                        <i class="fa-solid fa-<?php echo $icon; ?> mr-2"></i> <?php echo $msg; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
                    <i class="fa-solid fa-circle-check mr-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="table-container">
                    <table class="w-full text-left border-collapse min-w-[1200px]">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <tr>
                                <th class="p-4 pl-8 border-b border-slate-100 w-16">ID</th>
                                <th class="p-4 border-b border-slate-100">Khách hàng</th>
                                <th class="p-4 border-b border-slate-100">SĐT</th>
                                <th class="p-4 border-b border-slate-100">Dịch vụ</th>
                                <th class="p-4 border-b border-slate-100">Thời gian</th>
                                <th class="p-4 border-b border-slate-100 w-64">Địa chỉ</th>
                                <th class="p-4 border-b border-slate-100 w-64">Nhân viên</th>
                                <th class="p-4 border-b border-slate-100 text-center">Trạng thái</th>
                                <th class="p-4 pr-8 border-b border-slate-100 text-right sticky right-0 bg-slate-50">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php if (empty($bookings)): ?>
                                <tr>
                                    <td colspan="9" class="p-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fa-solid fa-filter-circle-xmark text-3xl text-slate-300 mb-3"></i>
                                            <p class="font-medium">Không tìm thấy đơn đặt lịch nào</p>
                                            <?php if ($statusFilter): ?>
                                                <a href="bookings.php" class="mt-2 text-brand-600 hover:underline text-sm font-medium">Xem tất cả đơn</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: foreach ($bookings as $b): 
                                $statusKey   = strtolower(trim($b['status'] ?? 'pending'));
                                $badgeData   = $badgeConfig[$statusKey] ?? $badgeConfig['pending'];
                                $displayName = $b['customer_name'] ?: ($b['user_name'] ?? 'Khách vãng lai');
                                $price       = number_format((float)($b['total_amount'] ?? 0), 0, ',', '.') . 'đ';
                            ?>
                                <tr class="hover:bg-slate-50 transition duration-150 group">
                                    <td class="p-4 pl-8 font-medium text-slate-900">#<?php echo $b['id']; ?></td>
                                    
                                    <td class="p-4"><div class="font-bold text-slate-800 text-base"><?php echo htmlspecialchars($displayName); ?></div></td>
                                    
                                    <td class="p-4 text-slate-600 font-mono"><?php echo htmlspecialchars($b['customer_phone'] ?? ''); ?></td>
                                    
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            <?php echo htmlspecialchars($b['service_name'] ?? 'Đã xóa'); ?>
                                        </span>
                                    </td>
                                    
                                    <td class="p-4 text-slate-600">
                                        <div class="font-medium"><?php echo date('d/m/Y', strtotime($b['booking_date'])); ?></div>
                                        <div class="text-xs text-slate-400 mt-0.5"><?php echo date('H:i', strtotime($b['start_time'])); ?></div>
                                    </td>
                                    
                                    <td class="p-4 text-slate-500 text-xs truncate max-w-xs" title="<?php echo htmlspecialchars($b['address']); ?>">
                                        <?php echo htmlspecialchars($b['address']); ?>
                                    </td>
                                    
                                    <td class="p-4">
                                        <?php if (!empty($b['staff_name'])): ?>
                                            <div class="text-sm font-bold text-teal-700 mb-1 flex items-center">
                                                <i class="fa-solid fa-user-check mr-1 text-teal-500"></i> <?php echo htmlspecialchars($b['staff_name']); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-xs text-red-500 font-medium mb-1">Chưa gán NV</div>
                                        <?php endif; ?>
                                        <div class="text-xs font-mono text-slate-600">Giá: <?php echo $price; ?></div>
                                    </td>
                                    
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?php echo $badgeData['class']; ?>">
                                            <?php echo $badgeData['label']; ?>
                                        </span>
                                    </td>

                                    <td class="p-4 pr-8 text-right sticky right-0 bg-white group-hover:bg-slate-50">
                                        <div class="flex justify-end gap-2">
                                            <?php if ($statusKey !== 'confirmed'): ?>
                                                <a href="actions/approve_booking.php?id=<?php echo $b['id']; ?>" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition border border-blue-100" title="Duyệt" onclick="return confirm('Duyệt đơn này?');"><i class="fa-solid fa-check"></i></a>
                                            <?php endif; ?>
                                            
                                            <?php if ($statusKey !== 'cancelled'): ?>
                                                <a href="actions/reject_booking.php?id=<?php echo $b['id']; ?>" class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition border border-red-100" title="Hủy" onclick="return confirm('Hủy đơn này?');"><i class="fa-solid fa-xmark"></i></a>
                                            <?php endif; ?>
                                            
                                            <a href="/GiupViecNhanh/admin/actions/delete_booking.php?id=<?php echo $b['id']; ?>" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition ml-1" title="Xóa" onclick="return confirm('Xóa đơn #<?php echo $b['id']; ?>?');"><i class="fa-solid fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>