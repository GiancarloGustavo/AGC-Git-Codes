<?php
session_start();
require 'database/db.php'; // Connexion BDD

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$index_link = 'index.php';
$css_link_url = 'assets/css/admin.css';
?>

<?php

$count = 0;
$link = '#';

if (isset($_SESSION['admin_logged_in'])) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM notifications_admin WHERE admin_id = 1 AND is_read = 0");
    $count = $stmt->fetchColumn();
    $link = "notifications_admin.php";
}
?>


<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/headerPageTrue.php'; ?>

    <main>
        <div class="container">

             <!-- for my notifications -->
            <div class="notification-container" onclick="window.location.href='<?= $link ?>'">
                <button class="notification-btn">
                        <i class="fa-solid fa-bell"></i>
                </button>
                <div class="notification-count">
                    <?= $count ?>
                </div>
            </div>

            <div class="dashboard">
                <div class="dashboard-title">
                    <h2>Admin Dashboard</h2>
                </div>

                <div class="button-messages" onclick="window.location.href = 'contact_admin.php'">
                    <button id="button-msg"><i class="fa-solid fa-message"></i></button>
                </div>

                <div class="all-users">
                    <?php 
                        $stmt = $pdo->prepare("SELECT * FROM users ORDER BY created_at DESC");
                        $stmt->execute();
                        $users = $stmt->fetchAll();
                    ?>

                    <div class="users">
                        <?php foreach($users as $user) :?>
                            <div class="user-item">
                                <div class="user-profile">
                                    <div class="user-image" style="background-image: url(<?= $user['profile_url'] ?? 'images/default_admin.jpg' ?>);"
                                    onclick="
                                    window.location.href = '<?= $user['profile_url'] ?? 'images/default_admin.jpg' ?>'
                                    "
                                    ></div>
                                    <div class="user-name"><span><?= $user['username'] ?></span></div>
                                </div>
                                <div class="bio-user"><span><?php echo $user['bio'] ?></span></div>
                                <div class="user-email"><span><?= $user['email'] ?></span></div>
                                <div class="date-join"><span><?= $user['created_at'] ?></span></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="button-to-publish">
                    <button onclick="activePanel();"><i class="fa-solid fa-cross"></i></button>
                </div>

                <div class="publish-codes">
                    <div class="close">
                        <div class="width"></div>
                        <div class="close-i" onclick="activePanel();"><i class="fa-solid fa-close" style="font-size: 30px; font-weight: bold;"></i></div>
                    </div>

                    <!-- Now the important part -->

                    <div class="form-group">
                        <form action="publish.php" method="POST">
                            <input type="text" name="title" placeholder="Titre du code" required>
                            <textarea name="description" placeholder="Description (servira de miniature)" required></textarea>
                            <textarea name="html_code" placeholder="Code HTML" required class="text-t"></textarea>
                            <textarea name="css_code" placeholder="Code CSS (optionnel)" class="text-t"></textarea>
                            <textarea name="js_code" placeholder="Code JavaScript (optionnel)" class="text-t"></textarea>
                            <button type="submit" id="submit"><i class="fa-regular fa-paper-plane" style="font-size: 18px; font-weight: bold;"></i></button>
                        </form>
                    </div>
                </div>

                <!-- Now for all the codes that i published -->

                <div class="show-codes-admin"><button onclick="showCodes();"><i class="fa-solid fa-eye"></i></button></div>

                <div class="admin-all-codes">
                    <div class="close-code">
                        <div class="width-code"></div>
                        <div class="close-i-code" onclick="showCodes();"><i class="fa-solid fa-close" style="font-size: 30px; font-weight: bold;"></i></div>
                    </div>

                    <!-- THE CODES -->

                    <?php 
                        $stmt = $pdo->prepare("SELECT * FROM codes ORDER BY created_at DESC");
                        $stmt->execute();
                        $codes = $stmt->fetchAll();
                    ?>

                    <div class="codes-container">
                        <div class="codes-list">
                            <?php foreach($codes as $code): ?>
                                <div class="code-item" onclick="window.location.href = 'code-details.php?id=<?= $code['id'] ?>'">
                                    <div class="code-title">
                                        <?= $code['title'] ?>
                                    </div>
                                    <div class="code-date">
                                        <?= $code['created_at'] ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="assets/js/theme-no-button.js" defer></script>

    <script>
if ('serviceWorker' in navigator && 'Notification' in window) {
    navigator.serviceWorker.register('sw.js')
        .then(function(reg) {
            console.log('✔ Service Worker enregistré !');

            // Demander la permission
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    // Lancer le check toutes les 1 ms (1 seul cycle)
                    setTimeout(() => {
                        fetch('check_notifications.php')
                            .then(res => res.json())
                            .then(data => {
                                if (data.length > 0) {
                                    data.forEach(notif => {
                                        reg.showNotification(notif.title, {
                                            body: notif.body,
                                            icon: 'assets/images/notification-icon.png',
                                            badge: 'assets/images/badge-icon.png',
                                            data: {
                                                url: notif.url,
                                                id: notif.id,
                                                type: notif.type
                                            }
                                        });
                                    });
                                }
                            });
                    }, 1);
                }
            });
        });
}
</script>

<script>
    function activePanel(){
        let panel = document.querySelector('.publish-codes');
        panel.classList.toggle('active');
    }

    function showCodes(){
        let panelCodes = document.querySelector('.admin-all-codes');
        panelCodes.classList.toggle('hide');
    }
</script>

</body>
</html>
