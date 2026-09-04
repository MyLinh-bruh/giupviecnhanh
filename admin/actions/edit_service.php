<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/db.php';
require_once '../../app/helpers/auth.php';
require_once '../../app/helpers/utils.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');

$query = "SELECT * FROM services WHERE id = ? LIMIT 0,1";
$stmt = $db->prepare($query);
$stmt->bindParam(1, $id);
$stmt->execute();
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    die('ERROR: Service not found.');
}

$current_theme = 'green';
if (strpos($service['bg_color'], 'blue') !== false) $current_theme = 'blue';
if (strpos($service['bg_color'], 'orange') !== false) $current_theme = 'orange';
if (strpos($service['bg_color'], 'purple') !== false) $current_theme = 'purple';
if (strpos($service['bg_color'], 'red') !== false) $current_theme = 'red';
if (strpos($service['bg_color'], 'teal') !== false) $current_theme = 'teal';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price_text = trim($_POST['price_text'] ?? '');
    $icon = trim($_POST['icon'] ?? 'fa-star');
    
    $color_theme = $_POST['color_theme'] ?? 'green';
    $bg_color = 'bg-green-500';
    $text_color = 'text-white';
    
    switch ($color_theme) {
        case 'blue': $bg_color = 'bg-blue-500'; break;
        case 'orange': $bg_color = 'bg-orange-500'; break;
        case 'purple': $bg_color = 'bg-purple-500'; break;
        case 'red': $bg_color = 'bg-red-500'; break;
        case 'teal': $bg_color = 'bg-teal-500'; break;
        default: $bg_color = 'bg-green-500'; break;
    }

    if (empty($name) || empty($price_text)) {
        $error = "Vui lòng nhập tên dịch vụ và giá!";
    } else {
        try {
            $query = "UPDATE services 
                      SET name = :name, 
                          description = :description, 
                          price_text = :price_text, 
                          icon = :icon, 
                          bg_color = :bg_color, 
                          text_color = :text_color 
                      WHERE id = :id";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price_text', $price_text);
            $stmt->bindParam(':icon', $icon);
            $stmt->bindParam(':bg_color', $bg_color);
            $stmt->bindParam(':text_color', $text_color);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                if(function_exists('setFlash')) setFlash('success', 'Cập nhật dịch vụ thành công!');
                header("Location: /GiupViecNhanh/admin/services.php");
                exit();
            } else {
                $error = "Có lỗi xảy ra khi cập nhật dữ liệu.";
            }
        } catch (PDOException $e) {
            $error = "Lỗi Database: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Dịch Vụ - Admin</title>
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
    <style> body { font-family: 'Be Vietnam Pro', sans-serif; } </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Header -->
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
            <div class="flex items-center">
                <!-- Sửa link quay lại -->
                <a href="/GiupViecNhanh/admin/services.php" class="mr-4 text-slate-400 hover:text-slate-600 transition">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <h2 class="text-xl font-bold text-slate-800">Chỉnh sửa dịch vụ</h2>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
                    <div class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center text-brand-700 font-bold mr-3 border border-brand-200">
                        A
                    </div>
                    <span class="font-medium text-sm text-slate-700 mr-2">
                        <?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Admin'); ?>
                    </span>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-3xl mx-auto">
                
                <?php if (isset($error)): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                        <p class="font-bold">Lỗi!</p>
                        <p><?php echo $error; ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" action="edit_service.php?id=<?php echo $id; ?>" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-8 space-y-6">
                        
                        <div>
                            <label class="block text-slate-700 font-bold mb-2 text-sm">Tên dịch vụ <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($service['name']); ?>" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-slate-700 font-bold mb-2 text-sm">Giá hiển thị <span class="text-red-500">*</span></label>
                                <input type="text" name="price_text" value="<?php echo htmlspecialchars($service['price_text']); ?>" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-slate-700 font-bold mb-2 text-sm">Biểu tượng (FontAwesome)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-icons"></i>
                                    </div>
                                    <select name="icon" class="w-full pl-10 pr-4 py-3 rounded-lg border border-slate-300 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition appearance-none bg-white">
                                        <?php 
                                            $icons = [
                                                'fa-broom' => 'Chổi (Dọn dẹp)',
                                                'fa-shirt' => 'Áo (Giặt ủi)',
                                                'fa-utensils' => 'Dụng cụ ăn (Nấu ăn)',
                                                'fa-couch' => 'Ghế Sofa',
                                                'fa-snowflake' => 'Tuyết (Máy lạnh)',
                                                'fa-bug' => 'Côn trùng',
                                                'fa-tree' => 'Cây cối',
                                                'fa-bolt' => 'Điện nước',
                                                'fa-star' => 'Ngôi sao (Mặc định)'
                                            ];
                                            foreach ($icons as $val => $label) {
                                                $selected = ($service['icon'] === $val) ? 'selected' : '';
                                                echo "<option value='$val' $selected>$label</option>";
                                            }
                                        ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-700 font-bold mb-3 text-sm">Màu sắc chủ đạo</label>
                            <div class="flex flex-wrap gap-4">
                                <?php 
                                    $colors = ['green', 'blue', 'orange', 'purple', 'red', 'teal'];
                                    foreach ($colors as $color): 
                                        $checked = ($current_theme === $color) ? 'checked' : '';
                                ?>
                                <label class="cursor-pointer">
                                    <input type="radio" name="color_theme" value="<?php echo $color; ?>" class="peer sr-only" <?php echo $checked; ?>>
                                    <div class="w-10 h-10 rounded-full bg-<?php echo $color; ?>-500 ring-2 ring-offset-2 ring-transparent peer-checked:ring-<?php echo $color; ?>-500 hover:scale-110 transition flex items-center justify-center text-white">
                                        <i class="fa-solid fa-check opacity-0 peer-checked:opacity-100"></i>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-700 font-bold mb-2 text-sm">Mô tả ngắn</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition resize-none"><?php echo htmlspecialchars($service['description']); ?></textarea>
                        </div>

                    </div>
                    
                    <div class="bg-slate-50 px-8 py-5 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="/GiupViecNhanh/admin/services.php" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-slate-200 transition">Hủy bỏ</a>
                        <button type="submit" class="bg-brand-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-brand-700 transition shadow-lg shadow-brand-200 transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-save mr-2"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>