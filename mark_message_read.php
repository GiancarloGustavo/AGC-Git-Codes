<?php
session_start();
require 'database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $id = intval($_POST['id']);

    if ($type === 'user' && isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];
        $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?");
        $stmt->execute([1, $user_id]);
    }

    if ($type === 'admin' && isset($_SESSION['admin_logged_in'])) {
        $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = 1");
        $stmt->execute([$id]);
    }

    echo 'OK';
}
?>