<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'database/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die("Accès refusé !");
}

$title = $_POST['title'];
$html_code = $_POST['html_code'];
$css_code = !empty($_POST['css_code']) ? $_POST['css_code'] : NULL;
$js_code = !empty($_POST['js_code']) ? $_POST['js_code'] : NULL;
$description = $_POST['description'];

// Insertion du code
$sql = "INSERT INTO codes (title, html_code, css_code, js_code, description) VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $html_code, $css_code, $js_code, $description]);
$codeId = $pdo->lastInsertId();

// ✨ Ajouter une notification à tous les utilisateurs
$title_notif = $title;
$body = 'A new code has been published';
$url = 'code-details.php?id=' . $codeId;
$is_read = 0;

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT id FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Insérer une notification pour chaque utilisateur
$notifStmt = $pdo->prepare("INSERT INTO notifications_users (user_id, admin_id, title, body, url, is_read) VALUES (?, 1, ?, ?, ?, ?)");

foreach ($users as $user) {
    $notifStmt->execute([$user['id'], $title_notif, $body, $url, $is_read]);
}

header("Location: code-details.php?id=$codeId");
exit();
?>
