<?php
session_start();
require 'database/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: auth/login-user.php');
    exit();
}

$user_id = $_SESSION['user']['id'];
$admin_id = 1;

// Vérifier s’il y a un message non lu de l’admin
$stmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE sender_id = ? AND receiver_id = ? AND is_read = 0");
$stmt->execute([$admin_id, $user_id]);
$unread = $stmt->fetchColumn();

$is_unread_class = $unread > 0 ? 'unread' : 'read';

?>

<?php
$title = 'Contact Dashbord of : ' . $_SESSION['user']['username'];
$css_link_url = 'assets/css/notifications.css';
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

        <!-- the rest of the code -->
         
        <section class="container">
            <div class="notif-title">
                <h1>Contact AGC</h1>
            </div>

            <!-- FOR THE CONTACT  -->
             <div class="notif-container-list">
                <div class="notification-line <?= $is_unread_class ?>" data-id="<?= $admin_id ?>" data-url="chat_user.php">
                    <strong>AGC - Technic Support</strong><br>
                    Click here to talk with US.
                </div>
             </div>
        </section>
    </main>

    <script>
        document.querySelectorAll('.notification-line').forEach(item => {
            item.addEventListener('click', function () {
                const adminId = this.getAttribute('data-id');
                const url = this.getAttribute('data-url');

                fetch('mark_message_read.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'type=user&id=' + adminId
                }).then(() => {
                    window.location.href = url;
                });
            });
        });
    </script>

    <script src="./assets/js/theme-no-button.js"></script>
</body>
</html>
