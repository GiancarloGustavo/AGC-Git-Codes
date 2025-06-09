<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// delete_comment.php
session_start();
require 'database/db.php';

$comment_id = $_POST['comment_id'] ?? null;
$code_id = $_POST['code_id'] ?? null;

if (!$comment_id || !$code_id) exit;

// On vérifie si c’est bien le propriétaire
$stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
$stmt->execute([$comment_id]);
$comment = $stmt->fetch();

if (!$comment) exit;

$user_id = $_SESSION['user']['id'] ?? null;
$admin_id = $_SESSION['admin_logged_in']['id'] ?? null;

if ($comment['user_id'] == $user_id || $comment['admin_id'] == $admin_id) {
    $del = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $del->execute([$comment_id]);
}

header("Location: code-details.php?id=$code_id");
exit;
?>