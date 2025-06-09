<?php
require 'database/db.php';

$type = $_POST['type'] ?? null;
$id = intval($_POST['id'] ?? 0);

if (!$type || !$id) exit();

$table = $type === 'admin' ? 'notifications_admin' : 'notifications_users';

$stmt = $pdo->prepare("UPDATE $table SET is_read = 1 WHERE id = ?");
$stmt->execute([$id]);

echo 'OK';
?>
