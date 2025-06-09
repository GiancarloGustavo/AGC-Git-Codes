<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'database/db.php'; // Database connection

// Solution : séparer les vérifications
if (isset($_SESSION['admin_logged_in'])) {
    echo "<script> console.log('you are the owner of this website, you got the access!'); </script>";
}
elseif (!isset($_SESSION['user'])) {
    header('Location: auth/login-user.php');
    exit();
}

$css_link_url = 'assets/css/user.css';
$title = $_SESSION['user']['username'] . ' On Git-Codes';

$currentUser = $_SESSION["user"];

if (isset($_GET['id']) && $_GET['id'] != $currentUser['id']) {
    $targetId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$targetId]);
    $viewedUser = $stmt->fetch();

    if (!$viewedUser) {
        echo "User Not found";
        exit();
    }

    $isOwner = false;
} else {
    $viewedUser = $currentUser;
    $isOwner = true;
}

$defaultAvatar = 'images/default_admin.jpg';


// Section "Codes visités"
$visitedCodes = [];
if (isset($_SESSION['user']['id'])) {
    $stmt = $pdo->prepare("
        SELECT c.*, v.visited_at as visit_date 
        FROM codes c
        JOIN user_visited_codes v ON c.id = v.code_id
        WHERE v.user_id = ?
        ORDER BY v.visited_at DESC
        LIMIT 10
    ");
    $stmt->execute([$_SESSION['user']['id']]);
    $visitedCodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>

<?php
$count = 0;
$link = '#';

if (isset($_SESSION['user'])) {
    $uid = $_SESSION['user']['id'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications_users WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$uid]);
    $count = $stmt->fetchColumn();
    $link = "notifications_users.php?id=$uid";
}


$title = 'Account of : ' . $_SESSION['user']['username'];
$css_link_url = 'assets/css/user.css';
$index_link = 'index.php';

?>


<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<body>
    <?php include 'includes/headerPageTrue.php'; ?>

    <main>

    <!-- for the notification -->

    <div class="notification-container" onclick="window.location.href='<?= $link ?>'">
        <button class="notification-btn">
            <i class="fa-solid fa-bell"></i>
        </button>
        <div class="notification-count">
            <?= $count ?>
        </div>
    </div>


        <div class="container">
            <section id="info-user">
                <div class="container-info-user">
                    <div class="user-profile-container">
                        <div class="user-profile" style="background-image: url(<?= $viewedUser['profile_url'] ?? 'images/default_admin.jpg' ?>);"
                            onclick="window.location.href='<?= $viewedUser['profile_url'] ?? 'images/default_admin.jpg' ?>'">
                        </div>
                    </div>
                    <div class="username-email-bio-container">
                        <div class="username-email-bio-button">
                            <div class="username-email-bio">
                                <div class="username">
                                    <span id="username" style="font-weight: bold; font-size: clamp(15px, 3.5vw, 20px); color: #17fff3;"><?= $viewedUser['username'] ?></span>
                                </div>
                                <div class="email">
                                    <span id="email-user" style="font-size: 14px;"><?= $viewedUser['email'] ?></span>
                                </div>
                                <div class="bio">
                                    <span id="bio-user"><?= $viewedUser['bio'] ?? 'No bio found.' ?></span>
                                </div>
                            </div>

                            <!-- ------------ -------------- ----- -->
                            <?php if($isOwner == true): ?> 
                                <div class="button-user-container">
                                    <div class="edit-button-container">
                                        <button id="edit-profile" onclick="editProfile();" style="cursor: pointer;">Edit profile</button>
                                    </div>

                                    <div class="contact-btn">
                                        <button id="btn-contact" onclick="window.location.href = 'contact_users.php'" style="cursor: pointer;">
                                            Contact AGC
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- section code visited -->

            <section id="code-visited">
                <div class="container">
                    <div class="visited-codes-section">
                        <h3 style="text-decoration: underline;">Recently Visited Codes</h3>
                        <?php if (!empty($visitedCodes)): ?>
                            <div id="code-list-item">
                                <ul class="visited-codes-list">
                                    <?php foreach ($visitedCodes as $visited): 
                                        $visitDate = $visited['visit_date'] ?? $visited['visited_at'] ?? null;
                                        $formattedDate = $visitDate ? date('d/m/Y à H:i', strtotime($visitDate)) : 'Date inconnue';
                                    ?>
                                        <li class="visited-code-item" onclick="window.location.href = 'code-details.php?id=<?= $visited['id'] ?>'">
                                            <span class="code-title"><?= htmlspecialchars($visited['title'] ?? '') ?></span>
                                            <span class="visit-date">
                                                <?= $formattedDate ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php else: ?>
                            <p class="no-visits-message">No codes visited yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- <script src="notification.js" defer></script> -->
    <script src="assets/js/theme-no-button.js" defer></script>

    <script>
        function editProfile(){
            window.location.href = 'pages/edit_profile.php';
        }
    </script>

    <script>
        if ('serviceWorker' in navigator && 'Notification' in window) {
            navigator.serviceWorker.register('sw.js')
                .then(function(reg) {
                    console.log('✔ Service Worker install successfully!');

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
</body>
</html>