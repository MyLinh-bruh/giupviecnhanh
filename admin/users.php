<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';

requireAdmin(); 

$database = new Database();
$db = $database->getConnection();

$users = [];

try {
    $query = "SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $users = [];
}

if (file_exists(__DIR__ . '/../app/helpers/utils.php')) {
    require_once __DIR__ . '/../app/helpers/utils.php';
}

$page_title = "Quản lý khách hàng"; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Khách hàng - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 500: '#10b981', 600: '#059669', 900: '#064e3b' }
                    },
                    fontFamily: { sans: ['Be Vietnam Pro', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 bg-white">
        <?php include __DIR__ . '/includes/header.php'; ?>
        
        <main class="flex-1 overflow-y-auto p-6 lg:p-8 bg-slate-50">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Danh sách khách hàng</h1>
                    <p class="text-slate-500 text-sm mt-1">Quản lý thông tin tài khoản người dùng</p>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
                    <i class="fa-solid fa-circle-check mr-2"></i> 
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <tr>
                                <th class="p-4 border-b border-slate-100 w-20">ID</th>
                                <th class="p-4 border-b border-slate-100">Thông tin khách hàng</th>
                                <th class="p-4 border-b border-slate-100">Vai trò</th>
                                <th class="p-4 border-b border-slate-100">Ngày tham gia</th>
                                <th class="p-4 border-b border-slate-100 text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php if (count($users) > 0): ?>
                                <?php foreach ($users as $u): 
                                    $firstLetter = mb_substr($u['name'] ?? 'U', 0, 1);
                                ?>
                                    <tr class="hover:bg-slate-50 transition duration-150 group">
                                        <td class="p-4 font-medium text-slate-900">#<?php echo $u['id']; ?></td>
                                        <td class="p-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold mr-3 border border-slate-200">
                                                    <?php echo strtoupper($firstLetter); ?>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-800 text-base"><?php echo htmlspecialchars($u['name']); ?></div>
                                                    <div class="text-xs text-slate-500"><?php echo htmlspecialchars($u['email']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span>
                                                <?php echo htmlspecialchars($u['role']); ?>
                                            </span>
                                        </td>
                                        <td class="p-4 text-slate-500">
                                            <?php echo date('d/m/Y', strtotime($u['created_at'])); ?>
                                        </td>
                                        <td class="p-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="actions/user_details.php?id=<?php echo $u['id']; ?>" 
                                                   class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition" 
                                                   title="Xem chi tiết">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="actions/delete_user.php?id=<?php echo $u['id']; ?>" 
                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng <?php echo htmlspecialchars($u['name']); ?>? Hành động này không thể hoàn tác!');" 
                                                   class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition ml-1" 
                                                   title="Xóa">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fa-solid fa-user-slash text-4xl text-slate-300 mb-3"></i>
                                            <p>Chưa có khách hàng nào đăng ký.</p>
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