<?php
session_start();
require 'database/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: auth/login-user.php');
    exit();
}

$connected_user_id = $_SESSION['user']['id'];

if (!isset($_GET['id']) || $_GET['id'] != $connected_user_id) {
    header('Location: https://fr.wikipedia.org/wiki/Cybercriminalité');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM notifications_users WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$connected_user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$title = 'Notifications of : ' . $_SESSION['user']['username'];
$css_link_url = 'assets/css/notifications.css';
$index_link = 'index.php';
?>


<!DOCTYPE html>
<html>
<?php include 'includes/head.php'; ?>
<body>

    <?php include 'includes/headerPageTrue.php'; ?>
    <main>
        <!-- Overlay over main image b. -->
        <div class="overlay"></div>

        <!-- the rest of the code -->

        <section class="container">
            <div class="notif-title">
                <h1>Notifications</h1>
            </div>

            <!-- All the notifications -->

            <div class="notif-container-list">
                <ul>
                    <?php foreach ($notifications as $notif): ?>
                        <li class="notification-line <?= $notif['is_read'] == 0 ? 'unread' : 'read' ?>" 
                            data-id="<?= $notif['id'] ?>"
                            data-url="<?= htmlspecialchars($notif['url']) ?>">
                            <strong><?= htmlspecialchars($notif['title']) ?></strong><br>
                            <?= htmlspecialchars($notif['body']) ?><br>
                            <small><?= $notif['created_at'] ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </main>

    <script>
        document.querySelectorAll('.notification-line').forEach(item => {
            item.addEventListener('click', function () {
                const notifId = this.getAttribute('data-id');
                const url = this.getAttribute('data-url');

                fetch('mark_notification_read.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'type=user&id=' + notifId
                }).then(() => {
                    window.location.href = url;
                });
            });
        });
    </script>

    <script src="./assets/js/theme-no-button.js"></script>
</body>
</html>
