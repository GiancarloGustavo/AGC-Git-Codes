<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();
require 'database/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in']['id'] != 1) {
    header('Location: login.php');
    exit;
}

$code_id = $_POST['code_id'];
$parent_id = $_POST['parent_id'];
$content = trim($_POST['content']);
$admin_id = 1;

// Insertion de la réponse de l’admin
$stmt = $pdo->prepare("INSERT INTO comments (code_id, admin_id, parent_id, content) VALUES (?, ?, ?, ?)");
$stmt->execute([$code_id, $admin_id, $parent_id, $content]);

$reply_comment_id = $pdo->lastInsertId(); // ID de la réponse

// Trouver à qui appartient le commentaire initial
$parent_stmt = $pdo->prepare("SELECT user_id FROM comments WHERE id = ?");
$parent_stmt->execute([$parent_id]);
$parent_comment = $parent_stmt->fetch();

if ($parent_comment && $parent_comment['user_id']) {
    // Notification au user
    $notif_user = $pdo->prepare("INSERT INTO notifications_users (user_id, admin_id, title, body, url, is_read, created_at)
                                VALUES (?, ?, ?, ?, ?, 0, NOW())");
    $notif_user->execute([
        $parent_comment['user_id'],
        $admin_id,
        "New comment posted",
        "AGC reply to your comment",
        "code-details.php?id=$code_id#reply-comment-id=" . $reply_comment_id
    ]);
}

header("Location: code-details.php?id=$code_id#reply-comment-id=$reply_comment_id");
exit;
?>