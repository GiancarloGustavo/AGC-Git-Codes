<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/db.php';


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification de l'existence de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user'] = $user;
        header('Location: ../user.php');
        exit();
    } else {
        echo "<script>alert('Invalid email or password. Please try again.');</script>";
    }

}

?>

<?php
$title = 'Login to AGC-Git-Codes';
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
                <h2>Login to AGC-Git-Codes</h2>
            </div>
            <div class="form" id="form">
                <form action="login-user.php" method="post" enctype="application/x-www-form-urlencoded">
                    <div class="email">
                        <label for="email">Email </label> 
                        <input type="email" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="password">
                        <label for="password">Password </label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>
                    <div class="button-submit">
                        <input type="submit" value="Login">
                    </div>
                    <div class="button-submit">
                        <p>Don't have an account? <a href="user-signup.php" style="font-size: clamp(17px, 3.5vw, 22px); color: #fff; margin-left: 4px;">Signup here.</a></p>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="../assets/js/theme-no-button.js"></script>
</body>
</html>