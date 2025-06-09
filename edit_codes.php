<?php 

session_start();

include 'database/db.php';

if(!isset($_SESSION['admin_logged_in'])){
    header("Location: login.php");
    exit();
}

// Initialisation
$code_id = null;
$code = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le code_id dans tous les cas (formulaire ou bouton d'accès)
    if (isset($_POST['code_id'])) {
        $code_id = $_POST['code_id'];
    }

    // Si c'est la soumission du formulaire d'édition
    if (
        isset($_POST['html-code'], $_POST['css-code'], $_POST['js-code'], $_POST['title'], $_POST['description'])
    ) {
        $html = trim($_POST['html-code']);
        $css = trim($_POST['css-code']);
        $js = trim($_POST['js-code']);
        $titleCode = trim($_POST['title']);
        $descriptionCode = trim($_POST['description']);

        if (empty($html) || empty($css) || empty($js) || empty($titleCode) || empty($descriptionCode)) {
            echo "<script>alert('All fields must be filled!');</script>";
        } else {
            $stmt = $pdo->prepare("UPDATE codes SET title = ?, description = ?, html_code = ?, css_code = ?, js_code = ?, created_at = NOW() WHERE id = ?");
            $stmt->execute([$titleCode, $descriptionCode, $html, $css, $js, $code_id]);
            header("Location: code-details.php?id=$code_id");
            exit();
        }
    }

    // Récupération des données du code pour affichage
    if ($code_id) {
        $stmt = $pdo->prepare("SELECT * FROM codes WHERE id = ?");
        $stmt->execute([$code_id]);
        $code = $stmt->fetch();
    }
}

// Si code non trouvé (accès direct sans POST), on redirige
if (!$code) {
    header("Location: index.php");
    exit();
}




$title = 'Edit Code';
$favicon_96_link = './assets/images/favicon-96x96.png';
$faviconSvg_link = './assets/images/favicon.svg';
$faviconIco_link = './assets/images/favicon.ico';
$apple_icon_link_180 = './assets/images/apple-touch-icon.png';
$css_link_url = './assets/css/edit-code.css';
$index_link = './index.php';
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/headPageTrue.php'; ?>
<body>
    <?php include 'includes/headerPageTrue.php'; ?>
    
    <main>
        <div class="container">
            <div class="title-code">
                <h2><?= $code['title'] ?></h2>
            </div>

            <!--  -->

            <div class="code-container">
                <form action="edit_codes.php" method="post">
                    <input type="hidden" name="code_id" value="<?= $code['id'] ?>">
                    <div class="code-title">
                        <input type="text" name="title" id="title" value="<?= htmlspecialchars($code['title']) ?>" required>
                    </div>

                    <div class="code-description">
                        <textarea name="description" id="description" required><?= htmlspecialchars($code['description']) ?></textarea>
                    </div>


                    <p class="prediction">This is the most important part</p>
                    <div class="code-type-html">
                        <textarea name="html-code" id="html-code" required><?= htmlspecialchars($code['html_code']) ?></textarea>
                    </div>

                    <div class="code-type-css">
                        <textarea name="css-code" id="css-code" required><?= htmlspecialchars($code['css_code']) ?></textarea>
                    </div>

                    <div class="code-type-js">
                        <textarea name="js-code" id="js-code" required><?= htmlspecialchars($code['js_code']) ?></textarea>
                    </div>

                    <div class="button-submit">
                        <button type="submit">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- the script -->
     <script src="assets/js/theme-no-button.js"></script>
</body>
</html>