
<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../database/db.php';

if (!isset($_SESSION['user']['id'])) {
    header('Location: ../auth/login-user.php');
    exit();
}

$userId = $_SESSION['user']['id'];

try {
    $pdo->beginTransaction();

    // 1. Supprimer tous ses messages (envoyés ou reçus)
    $stmt = $pdo->prepare("DELETE FROM messages WHERE sender_id = ? OR receiver_id = ?");
    $stmt->execute([$userId, $userId]);

    // 2. Supprimer tous ses commentaires
    $stmt = $pdo->prepare("DELETE FROM comments WHERE user_id = ?");
    $stmt->execute([$userId]);

    // 3. Supprimer tous ses enregistrements (saved)
    $stmt = $pdo->prepare("DELETE FROM saved WHERE user_id = ?");
    $stmt->execute([$userId]);

    // 4. Supprimer toutes ses notifications
    $stmt = $pdo->prepare("DELETE FROM notifications_users WHERE user_id = ?");
    $stmt->execute([$userId]);

    // 5. Supprimer ses abonnements (déjà présent, mais on le remet au cas où il suit ou est suivi)
    $stmt = $pdo->prepare("DELETE FROM user_follows WHERE follower_id = ? OR followed_id = ?");
    $stmt->execute([$userId, $userId]);

    $stmt = $pdo->prepare("DELETE FROM user_visited_codes WHERE user_id = ?");
    $stmt->execute([$userId]);

    // 6. Supprimer l'utilisateur
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("L'utilisateur n'existe pas ou a déjà été supprimé.");
    }

    $pdo->commit();

    // Déconnexion propre
    session_unset();
    session_destroy();

    // Redirection
    header("Location: ../index.php?account_deleted=1");
    exit();

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
    header("Location: ../account_settings.php");
    exit();
}

?>
