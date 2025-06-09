<?php
session_start();
include 'database/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user']['id'];
$code_id = $_POST['code_id'] ?? null;

if (!$code_id) {
    echo json_encode(['success' => false, 'message' => 'Missing code_id']);
    exit();
}

// Vérifie si le code est déjà sauvegardé
$stmt = $pdo->prepare("SELECT * FROM saved WHERE user_id = ? AND code_id = ?");
$stmt->execute([$user_id, $code_id]);
$already_saved = $stmt->fetch();

if ($already_saved) {
    // Supprimer la sauvegarde
    $stmt = $pdo->prepare("DELETE FROM saved WHERE user_id = ? AND code_id = ?");
    $stmt->execute([$user_id, $code_id]);
    echo json_encode(['success' => true, 'saved' => false]);
} else {
    // Ajouter la sauvegarde
    $stmt = $pdo->prepare("INSERT INTO saved (user_id, code_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $code_id]);
    echo json_encode(['success' => true, 'saved' => true]);
}

?>