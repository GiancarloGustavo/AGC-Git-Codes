<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();
require 'database/db.php';

if (!isset($_SESSION['admin_logged_in']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = 1;
$user_id = intval($_GET['id']);

// Récupérer le nom de l'utilisateur pour l'affichage
$user_stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);
$username = $user['username'] ?? 'Utilisateur #'.$user_id;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $message = trim($_POST['message']);

    // Enregistrer le message
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())");
    $stmt->execute([$admin_id, $user_id, $message]);

    $lastMessageId = $pdo->lastInsertId();

    $url = "chat_user.php?id=1#message-$lastMessageId";

    // Notification pour l’utilisateur
    $notif_stmt = $pdo->prepare("INSERT INTO notifications_users (user_id, admin_id, title, body, url, is_read, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW())");
    $notif_stmt->execute([
        $user_id,
        $admin_id,
        "New message from AGC",
        "AGC send you a new message",
        $url
    ]);
}

$messages = $pdo->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC");
$messages->execute([$user_id, $admin_id, $admin_id, $user_id]);
$data = $messages->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$title = 'Chat Admin';
$css_link_url = 'assets/css/messages.css';
$index_link = 'index.php';
?>

<!DOCTYPE html>
<html>
<?php include 'includes/head.php'; ?>
<body>
    <?php include 'includes/headerPageTrue.php'; ?>

    <main>
        <!-- For the overlay -->
        <div class="overlay"></div>

        <!-- The message container -->

        <section class="container">
            <div class="message-title">
                <h1><?= htmlspecialchars($username) ?></h1>
            </div>

            <!-- MEssage box container -->

            <div class="message-box-container" id="message-box-container">
                <?php foreach ($data as $msg): ?>
                    <div class="message <?= $msg['sender_id'] == $admin_id ? 'sent' : 'received' ?>" id="message-<?= $msg['id'] ?>">
                        <?= htmlspecialchars($msg['message']) ?><br>
                        <small><?= $msg['created_at'] ?></small>
                    </div>
                <?php endforeach; ?>

                <!-- <div id="bottom"></div> -->
            </div>

            <!-- Form to fill the message -->
            
            <div class="form-fill">
                <div class="leftAboveHeader"></div>
                <form method="POST">
                    <div class="chat-input">
                        <textarea name="message" placeholder="write a message..." required></textarea><br>
                    </div>

                    <div class="button">
                        <button type="submit"><i class="fa-regular fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>
        </section>

    </main>

    <script>
        // sw-notif.js
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js');
        }

        if ('Notification' in window && Notification.permission !== 'granted') {
            Notification.requestPermission();
        }

        function sendNotification(title, body) {
            if (Notification.permission === 'granted') {
                new Notification(title, { body });
            }
        }

    </script>

    <script>
        const headeFalse = document.querySelector('.message-title');
        const headerFalseHeightTrue = headeFalse.offsetHeight;

        const bodyFalse = document.querySelector('.message-box-container');

        bodyFalse.style.marginTop = `${headerFalseHeightTrue}px`;

        const header = document.querySelector('header');
        const aboveHeader = document.querySelector('.leftAboveHeader');
        const formFill = document.querySelector('form');


        // important
        let headerWidth = header.offsetWidth;

        aboveHeader.style.width = `${headerWidth}px`;
        headeFalse.style.marginLeft = `${headerWidth + 2.5}px`;

        let formFillHeight = formFill.offsetHeight;
        bodyFalse.style.paddingBottom = `${formFillHeight}px`;

    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const hash = window.location.hash; // exemple: "#message-42"
            const formContainer = document.querySelector('.form-fill');
            if (hash.startsWith("#message-")) {
                const target = document.querySelector(hash);
                if (target) {
                    formContainer.style.display = 'none';

                    // Ajout du style temporaire
                    target.style.transition = "background-color 0.3s ease";
                    target.style.backgroundColor = "#ffff99"; // Jaune clair
                    target.style.color = '#000';

                    // Retrait après 2.5s
                    setTimeout(() => {
                        target.style.backgroundColor = "";
                        target.style.color = '#fff';
                        formContainer.style.display = 'flex';
                    }, 2500);

                    // Scroll doux vers le message
                    target.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            }else{
                // Sinon, on scroll tout en bas du conteneur
                const container = document.querySelector("main");
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
</body>
</html>
