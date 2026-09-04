<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function formatCurrency(float|int $amount): string {
    return number_format((float)$amount, 0, ',', '.') . 'đ';
}

function dd(mixed $data): void {
    echo '<pre style="background:#1e293b; color:#4ade80; padding:20px; border-radius:8px; font-family:monospace; z-index:9999; position:relative; white-space: pre-wrap;">';
    print_r($data);
    echo '</pre>';
    die();
}

function setFlash(string $key, string $message): void {
    $_SESSION['flash'][$key] = $message;
}

function getFlash(string $key): ?string {
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return (string)$message;
    }
    return null;
}

function renderNotification(): void {
    $success = getFlash('success');
    $error   = getFlash('error');
    $msg  = $success ?? $error;
    $type = $success ? 'success' : ($error ? 'error' : null);

    if ($msg) {
        $bgColor = $type === 'success' ? 'bg-slate-900' : 'bg-red-600';
        $iconBox = $type === 'success' ? 'bg-green-500 text-white' : 'bg-white text-red-600';
        $icon    = $type === 'success' ? 'fa-check' : 'fa-exclamation';

        $safeMsg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');

        echo <<<HTML
        <div id="notification-toast" class="fixed top-6 right-6 {$bgColor} text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center animate-bounce transition-all duration-500" style="min-width: 300px;">
            <div class="w-8 h-8 {$iconBox} rounded-full flex items-center justify-center mr-3 shadow-md">
                <i class="fa-solid {$icon}"></i>
            </div>
            <span class="font-medium">{$safeMsg}</span>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('notification-toast');
                if(toast) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-20px)';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 3000);
        </script>
HTML;
    }
}
?>