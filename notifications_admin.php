<?php
session_start();
require 'database/db.php'; 

// Vérifie que l'admin est connecté
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_logged_in']['username'];

$admin_id = 1; // Admin ID fixe comme convenu

// Récupérer les notifications pour l’admin
$stmt = $pdo->prepare("SELECT * FROM notifications_admin WHERE admin_id = ? ORDER BY created_at DESC");
$stmt->execute([$admin_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$title = 'Notifications of : ' . $admin_name ;
$css_link_url = 'assets/css/notifications.css';
$index_link = 'index.php';
?>


<!DOCTYPE html>
<html>
<?php include 'includes/head.php'; ?>
<body>
    <?php include 'includes/headerPageTrue.php'; ?>

    <main>
        <!-- Overlay main -->
         <div class="overlay"></div>

         <!-- the rest of the code -->

         <section class="container">
            <div class="notif-title">
                <h1>Admin Notifications</h1>
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
                    body: 'type=admin&id=' + notifId
                }).then(() => {
                    window.location.href = url;
                });
            });
        });
    </script>
    <script src="./assets/js/theme-no-button.js"></script>
</body>
</html>
