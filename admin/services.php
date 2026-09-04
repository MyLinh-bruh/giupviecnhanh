<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/app.php'; 
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/helpers/utils.php';

requireAdmin(); 

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM services ORDER BY id DESC";
$stmt = $db->query($query);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

$baseUrl = defined('APP_URL') ? APP_URL : '/GiupViecNhanh/public/';

$page_title = "Quản lý Dịch vụ";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Dịch vụ - Admin</title>
    
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
    <style> body { font-family: 'Be Vietnam Pro', sans-serif; } </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 bg-white">
         <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="flex-1 overflow-y-auto p-6 lg:p-8 bg-slate-50">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Danh sách dịch vụ</h1>
                    <p class="text-slate-500 text-sm mt-1">Quản lý các gói dịch vụ hiển thị trên trang chủ</p>
                </div>
                <a href="actions/add_service.php" class="bg-brand-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-brand-700 transition shadow-lg shadow-brand-200 flex items-center transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-plus mr-2"></i> Thêm dịch vụ
                </a>
            </div>

            <?php if ($msg = getFlash('success')): ?>
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
                    <i class="fa-solid fa-circle-check mr-2"></i> <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if ($msg = getFlash('error')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <tr>
                                <th class="p-4 pl-8 border-b border-slate-100 w-20">ID</th>
                                <th class="p-4 border-b border-slate-100 w-20">Icon</th>
                                <th class="p-4 border-b border-slate-100">Tên dịch vụ</th>
                                <th class="p-4 border-b border-slate-100">Giá niêm yết</th>
                                <th class="p-4 border-b border-slate-100 hidden md:table-cell">Mô tả</th>
                                <th class="p-4 pr-8 border-b border-slate-100 text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php if (count($services) > 0): ?>
                                <?php foreach ($services as $s): 
                                    $bgColor = !empty($s['bg_color']) ? $s['bg_color'] : 'bg-gray-100';
                                    $textColor = !empty($s['text_color']) ? $s['text_color'] : 'text-gray-600';
                                    $icon = !empty($s['icon']) ? $s['icon'] : 'fa-cube';

                                    $displayPrice = 'Liên hệ';
                                    if (!empty($s['price_text'])) {
                                        $displayPrice = $s['price_text'];
                                    } elseif (isset($s['price'])) {
                                        $displayPrice = number_format((float)$s['price'], 0, ',', '.') . 'đ';
                                    }
                                ?>
                                    <tr class="hover:bg-slate-50 transition duration-150 group">
                                        <td class="p-4 pl-8 font-medium text-slate-900">#<?php echo $s['id']; ?></td>
                                        <td class="p-4">
                                            <div class="w-10 h-10 rounded-lg <?php echo $bgColor . ' ' . $textColor; ?> flex items-center justify-center text-lg shadow-sm group-hover:scale-110 transition">
                                                <i class="fa-solid <?php echo htmlspecialchars($icon); ?>"></i>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="font-bold text-slate-800 text-base"><?php echo htmlspecialchars($s['name']); ?></div>
                                        </td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                <?php echo htmlspecialchars($displayPrice); ?>
                                            </span>
                                        </td>
                                        <td class="p-4 text-slate-500 max-w-xs truncate hidden md:table-cell" title="<?php echo htmlspecialchars($s['description'] ?? ''); ?>">
                                            <?php echo htmlspecialchars($s['description'] ?? ''); ?>
                                        </td>
                                        <td class="p-4 pr-8 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="actions/edit_service.php?id=<?php echo $s['id']; ?>" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition" title="Sửa">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <a href="actions/delete_service.php?id=<?php echo $s['id']; ?>" class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này?');">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="p-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fa-solid fa-box-open text-4xl text-slate-300 mb-3"></i>
                                            <p>Chưa có dịch vụ nào trong hệ thống.</p>
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