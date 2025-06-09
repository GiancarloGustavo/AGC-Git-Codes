<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


session_start();
require 'database/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$admin_id = 1;

// Récupérer tous les utilisateurs qui ont échangé des messages avec l’admin
$stmt = $pdo->prepare("
    SELECT u.id, u.username, 
           (SELECT COUNT(*) FROM messages WHERE sender_id = u.id AND receiver_id = ? AND is_read = 0) AS unread
    FROM users u
    WHERE EXISTS (
        SELECT 1 FROM messages 
        WHERE (sender_id = ? AND receiver_id = u.id) 
           OR (sender_id = u.id AND receiver_id = ?)
    )
");
$stmt->execute([$admin_id, $admin_id, $admin_id]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$title = 'Contact Dashbord of : ' . $_SESSION['admin_logged_in']['username'] ;
$css_link_url = 'assets/css/notifications.css';
$index_link = 'index.php';
?>

<!DOCTYPE html>
<html>
    <?php include 'includes/head.php'; ?>
<body>
    <?php include 'includes/headerPageTrue.php'; ?>

    <main>

        <!-- My overlay Shit -->
        <div class="overlay"></div>

        <!-- The rest of the codes -->

        <section class="container">
            <div class="notif-title">
                <h1>Admin - Contacts - Lists</h1>
            </div>

            <!-- All the contacts -->

            <div class="notif-container-list">
                <?php foreach ($users as $user): ?>
                    <div class="notification-line <?= $user['unread'] > 0 ? 'unread' : 'read' ?>"
                        data-id="<?= $user['id'] ?>" data-url="chat_admin.php?id=<?= $user['id'] ?>">
                        <strong><?= htmlspecialchars($user['username']) ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </main>

    <script>
        document.querySelectorAll('.notification-line').forEach(item => {
            item.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                const url = this.getAttribute('data-url');

                fetch('mark_message_read.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'type=admin&id=' + userId
                }).then(() => {
                    window.location.href = url;
                });
            });
        });
    </script>

    <script src="./assets/js/theme-no-button.js"></script>
</body>
</html>
