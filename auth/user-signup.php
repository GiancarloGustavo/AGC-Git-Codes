<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/db.php';


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // N'oubliez pas de récupérer le mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Vérification de l'unicité
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $check = $stmt->fetch(PDO::FETCH_ASSOC);

    if($check) {
        echo "<script>alert('Username or Email already exists. Please try again.');</script>";
    } else {
        try {
            // Commencer une transaction
            $pdo->beginTransaction();
            
            // 1. Insérer le nouvel utilisateur
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);
            
            // 2. Récupérer l'ID du nouvel utilisateur
            $new_user_id = $pdo->lastInsertId();
            
            // 3. ID de l'admin fixe (vous avez dit que ce sera toujours 1)
            $admin_id = 1;
            
            // 4. Créer la relation de suivi
            $stmt = $pdo->prepare("INSERT INTO user_follows (follower_id, followed_id) VALUES (?, ?)");
            $stmt->execute([$new_user_id, $admin_id]);
            
            // 5. Créer une notification pour l'admin
            $title = 'New user';
            $body = 'A new user is signup to the AGC';
            $url = 'user.php?id=' . $new_user_id;
            $is_read = 0;

            $stmt = $pdo->prepare("INSERT INTO notifications_admin (admin_id, user_id, title, body, url, is_read) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$admin_id, $new_user_id, $title, $body, $url, $is_read]);

            // Valider la transaction
            $pdo->commit();

            header('Location: login-user.php');
            exit();
            
        } catch (Exception $e) {
            // En cas d'erreur, annuler la transaction
            $pdo->rollBack();
            
            // Journaliser l'erreur (à adapter selon votre configuration)
            error_log("Erreur lors de l'inscription: " . $e->getMessage());
            
            echo "<script>alert('An error occurred during registration. Please try again.');</script>";
        }
    }
}

?>

<?php
$title = 'Signup to AGC-Git-Codes';
$favicon_96_link = '../assets/images/favicon-96x96.png';
$faviconSvg_link = '../assets/images/favicon.svg';
$faviconIco_link = '../assets/images/favicon.ico';
$apple_icon_link_180 = '../assets/images/apple-touch-icon.png';
$css_link_url = '../assets/css/stylesTrue.css';
$index_link = '../index.php';
?>

<!DOCTYPE html>
<html lang="en">
<?php include '../includes/headPageTrue.php'; ?>
<body>
    <?php include '../includes/headerPageTrue.php'; ?>
    <main>
        <div class="container">
            <div class="h2">
                <h2>Signup to Git-Codes</h2>
            </div>
            <div class="form" id="form">
                <form action="user-signup.php" method="post" enctype="application/x-www-form-urlencoded">
                    <div class="username">
                        <label for="username">Username </label>
                        <input type="text" name="username" id="username" placeholder="Enter your username" required>
                    </div>
                    <div class="email">
                        <label for="email">Email </label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="password">
                        <label for="password">Password </label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>
                    <div class="button-submit">
                        <input type="submit" value="Signup">
                    </div>
                    <div class="button-submit">
                        <p>Already have an account? <a href="login-user.php" style="font-size: clamp(17px, 3.5vw, 22px); color: #fff; margin-left: 4px;">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="../assets/js/theme-no-button.js"></script>
</body>
</html>