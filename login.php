<?php
session_start();
require 'database/db.php'; // Connexion BDD

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminName = trim($_POST['adminName']);
    $adminEmail = trim($_POST['adminEmail']);
    $adminPassword = $_POST['adminPassword'];

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE adminName = ? AND adminEmail = ?");
    $stmt->execute([$adminName, $adminEmail]);
    $admin = $stmt->fetch();

    if($admin && password_verify($adminPassword, $admin['adminPassword'])){
        $_SESSION['admin_logged_in'] = [
            'id' => $admin['id'],
            'username' => $admin['adminName'],
            'email' => $admin['adminEmail']
        ];

        header("Location: admin.php");
        exit();
    }else{
        $error = "Email or password incorrects.";
    }
}
?>

<?php 
$title = 'Login To Git-code';
$css_link_url = 'assets/css/login-admin.css';
$index_link = 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/headerPageTrue.php'; ?>

    <main>
        <div class="container">
            <div class="title-h1">
                <h1>Login to Git-Code</h1>
            </div>

            <div class="form-group">
                <div class="message-error">
                    <?php if (isset($error)): ?>
                        <div class="message"><p style="color: red;"><?= $error ?></p></div>
                    <?php endif; ?>
                </div>

                <form method="POST">
                    <input type="text" name="adminName" placeholder="your adminName" required id="adminName">
                    <input type="email" name="adminEmail" placeholder="your adminEmail" required id="adminEmail">
                    <input type="password" name="adminPassword" placeholder="your password admin" required id="adminPassword">
                    <div class="button">
                        <button type="submit" id="submit">Se connecter</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const theme = localStorage.getItem('theme') || 'light';
            const body = document.body;

            if (theme === 'dark') {
                body.classList.add('dark');
                body.classList.remove('light');
            }else {
                body.classList.remove('dark');
                body.classList.add('light');
            }

        })   
    </script>
</body>
</html>
