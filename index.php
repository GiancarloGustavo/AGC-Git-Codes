<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'database/db.php'; // Connexion BDD
session_start();


$sql = "SELECT * FROM codes ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$codes = $stmt->fetchAll();

$title = "Git-Code - Home"; // Title of the page
$base_url = 'assets';

$css_link_url = 'assets/css/styles.css';

// Quand le bouton est cliqué
if (isset($_GET['mark_visited']) && isset($_SESSION['user']['id'])) {
    $codeId = (int)$_GET['mark_visited'];
    $userId = $_SESSION['user']['id'];
    
    try {
        // Insertion unique (grâce à UNIQUE KEY dans la table)
        $stmt = $pdo->prepare("INSERT IGNORE INTO user_visited_codes (user_id, code_id) VALUES (?, ?)");
        $stmt->execute([$userId, $codeId]);
    } catch (PDOException $e) {
        error_log("Erreur d'enregistrement: " . $e->getMessage());
    }
    
    // Redirection vers la page de détails
    header("Location: code-details.php?id=$codeId");
    exit();
}

?>


<?php
$count = 0;
$link = '#';
$isUser = false;

if (isset($_SESSION['user'])) {
    $uid = $_SESSION['user']['id'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications_users WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$uid]);
    $count = $stmt->fetchColumn();
    $link = "notifications_users.php?id=$uid";
    $isUser = true;
}

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
    <?php include 'includes/header.php'; ?>

    <main>

        <section class="presentation" id="presentation">
            <img src="assets/images/backgroundImage.webp" alt="Background image of agc-git-codes">
        </section>

        <section class="presentation-text">
            <p class="presentation-para">
                Welcome to Ansanm-Git-Code! 🚀
                Here, you can explore and download my latest code projects. I’m excited to share my work with you and help you level up your coding skills.
                Enjoy your time here and happy coding! 
            </p>
        </section>

        <!-- <div class="trigger-zone"></div> -->

        <section class="section-code">
            <div class="code-h1">
                <h1>My Shared codes</h1>
            </div>
            <div class="codes-list">
                <div class="code-list-items">
                    <?php foreach ($codes as $code): ?>
                        <div class="code-item" data-code-id="<?= $code['id'] ?>">
                            <div class="title-code">
                                <h2><?= htmlspecialchars($code['title']) ?></h2>
                            </div>
                            <div class="code-details">      
                                <?php
                                    $description = nl2br(htmlspecialchars($code['description']));
                                    $limitedDescription = strlen($description) > 70 ? substr($description, 0, 70) . '...' : $description;
                                ?>

                                <p><?= $limitedDescription ?></p>
                                    
                            </div>
                            <?php if(isset($_SESSION['user'])) : ?>
                                <div class="btn-href">
                                    <div class="code-button-author-container">
                                        <div class="code-author">
                                            <div class="code-author-image"></div>
                                            <div class="code-author-name">AGC</div>
                                        </div>
                                        <div class="code-btn">
                                            <button class="code-location"
                                                onclick="window.location.href='index.php?mark_visited=<?= $code['id'] ?>'" style="cursor: pointer;">
                                                Click to see
                                             </button>
                                        </div>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="btn-href">
                                    <div class="code-button-author-container">
                                        <div class="code-author">
                                            <div class="code-author-image"></div>
                                            <div class="code-author-name">AGC</div>
                                        </div>
                                        <div class="code-btn" style="cursor: pointer;">
                                            <button class="code-location"
                                                onclick="window.location.href='code-details.php?id=<?= $code['id'] ?>'" style="cursor: pointer;">
                                                 Click to see
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <script src="<?= $base_url ?>/js/scripts.js" defer></script>
    <script src="<?= $base_url ?>/js/theme.js" defer></script>
    <script src="./assets/js/search.js" defer></script>

<script>
if ('serviceWorker' in navigator && 'Notification' in window) {
    navigator.serviceWorker.register('sw.js')
        .then(function(reg) {
            console.log('✔ Service Worker installed successfuly dude!');

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