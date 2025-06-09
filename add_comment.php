<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();
require 'database/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: auth/login-user.php');
    exit;
}

$code_id = $_POST['code_id'];
$user_id = $_SESSION['user']['id'];
$content = trim($_POST['content']);

// Insertion du commentaire
$stmt = $pdo->prepare("INSERT INTO comments (code_id, user_id, content) VALUES (?, ?, ?)");
$stmt->execute([$code_id, $user_id, $content]);

$comment_id = $pdo->lastInsertId(); // on récupère l'ID du commentaire

// Récupérer le nom de l'utilisateur
$user_stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user = $user_stmt->fetch();

// Notification à l’admin (id = 1 ici)
$notif = $pdo->prepare("INSERT INTO notifications_admin (admin_id, user_id, title, body, url, is_read, created_at)
                        VALUES (?, ?, ?, ?, ?, 0, NOW())");
$notif->execute([
    1,
    $user_id,
    "New comment",
    $user['username'] . " add a new comment",
    "code-details.php?id=$code_id#comment-id=" . $comment_id
]);

header("Location: code-details.php?id=$code_id#comment-id=$comment_id");
exit;
?>