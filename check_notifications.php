<?php
session_start();
require 'database/db.php';

$notifications = [];

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];
    $stmt = $pdo->prepare("SELECT * FROM notifications_users WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 3");
    $stmt->execute([$userId]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($notifications as &$notif) {
        $notif['type'] = 'user';
    }
}
elseif (isset($_SESSION['admin_logged_in'])) {
    $adminId = 1;
    $stmt = $pdo->prepare("SELECT * FROM notifications_admin WHERE admin_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 3");
    $stmt->execute([$adminId]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($notifications as &$notif) {
        $notif['type'] = 'admin';
    }
}

header('Content-Type: application/json');
echo json_encode($notifications);
?>