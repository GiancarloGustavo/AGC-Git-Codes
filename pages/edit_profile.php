<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../database/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login-user.php');
    exit();
}

$title = 'Edit profile Of ' . $_SESSION['user']['username'];
$css_link = '../assets/css/edit-user.css';



// Fonction pour valider l'URL de l'image
function isValidImageUrl($url, &$errors = []) {
    $errors = []; // Initialisation du tableau d'erreurs
    
    if (empty($url)) {
        return true; // URL vide autorisée
    }

    // 1. Validation basique de l'URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $errors[] = "L'URL fournie n'est pas une URL valide";
        return false;
    }

    // 2. Vérification de l'extension uniquement
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
    $path = parse_url($url, PHP_URL_PATH);
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    
    if (empty($extension)) {
        $errors[] = "L'URL ne contient pas d'extension de fichier";
        return false;
    }
    
    if (!in_array($extension, $allowedExtensions)) {
        $errors[] = "Extension .$extension non autorisée. Utilisez: " . implode(', ', $allowedExtensions);
        return false;
    }

    return true;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $bio = trim($_POST['bio']);
    $userId = $_SESSION['user']['id'];
    $profile_url = trim($_POST['profile_url']);

    // Validation des données
    $errors = [];

    // Validation de l'username
    if (empty($username)) {
        $errors[] = "Username requiered";
    } elseif (strlen($username) < 3) {
        $errors[] = "The username must be at least 3 characters long";
    }

    // Validation de l'email
    if (empty($email)) {
        $errors[] = "Email requiered";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide";
    }

    // Validation de l'URL de profil
    if (!empty($profile_url) && !isValidImageUrl($profile_url)) {
        $errors[] = "URL is invalide. Formats accepted: jpg, png, gif, webp, bmp";
    }

    // Si pas d'erreurs, procéder à la mise à jour
    if (empty($errors)) {
        try {
            // Construction de la requête SQL dynamiquement
            $sql = "UPDATE users SET username = :username, email = :email, bio = :bio, profile_url = :profile_url";
            $params = [
                ':username' => $username,
                ':email' => $email,
                ':bio' => $bio,
                ':profile_url' => !empty($profile_url) ? $profile_url : null,
                ':id' => $userId
            ];

            // Gestion du mot de passe (si fourni)
            if (!empty($password)) {
                if (strlen($password) < 8) {
                    $errors[] = "The password must be at least 8 characters long";
                } else {
                    $sql .= ", password = :password";
                    $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
                }
            }

            // Finalisation de la requête
            $sql .= " WHERE id = :id";

            // Exécution de la requête
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            // Mettre à jour les données en session
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['bio'] = $bio;
            $_SESSION['user']['profile_url'] = $profile_url;

            $_SESSION['success'] = "Profile updated successfuly!";
            header("Location: ../user.php");
            exit();

        } catch (PDOException $e) {
            // Gestion des erreurs SQL (comme email/username déjà existant)
            if ($e->getCode() == '23000') {
                $errors[] = "Ce nom d'utilisateur ou email est déjà utilisé";
            } else {
                error_log("Erreur de base de données: " . $e->getMessage());
                $errors[] = "Une erreur est survenue lors de la mise à jour";
            }
        }
    }

    // Si erreurs, les stocker en session pour affichage
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_data'] = $_POST;
        header("Location: edit_profile.php");
        exit();
    }
}


?>

<?php
$title = 'Account of : ' . $_SESSION['user']['username'];
$favicon_96_link = '../assets/images/favicon-96x96.png';
$faviconSvg_link = '../assets/images/favicon.svg';
$faviconIco_link = '../assets/images/favicon.ico';
$apple_icon_link_180 = '../assets/images/apple-touch-icon.png';
$css_link_url = '../assets/css/edit-user.css';
$index_link = '../index.php';

?>


<!DOCTYPE html>
<html lang="en">
<?php include '../includes/headPageTrue.php'; ?>
<body>
    <?php include '../includes/headerPageTrue.php'; ?>
    <main>
        <div class="container">
            <div class="list-of-links">
                <div class="list-of-links-title">
                    <h1>Settings</h1>
                </div>

                <!-- ------------ Now the links ------------- -->

                <div class="links-container">
                    <ul class="ul-links">
                        <li onclick="changeInformation();"><i class="fa-solid fa-circle-info"></i>  Informations</li>
                        <li onclick="themeOfUser();"> Theme</li>
                        <li onclick="window.location.href = '../notifications_users.php?id=<?= $_SESSION['user']['id'] ?>'"><i class="fa-solid fa-bell"></i>  Nofications</li>
                        <li onclick="toggleSavedCode();"><i class="fa-solid fa-bookmark"></i>  Saved Codes</li>
                        <li onclick="window.location.href = './contact.php'"><i class="fa-solid fa-address-book"></i>  Contact Us</li>
                        <li onclick="window.location.href = './about-us.php'"><i class="fa-solid fa-address-card"></i>  About US</li>
                        <li onclick="window.location.href = './socials.php'"><i class="fa-solid fa-globe"></i>  Our Socials Medias</li>
                        <li onclick="window.location.href = '../contact_users.php?id=<?= $_SESSION['user']['id'] ?>'"><i class="fa-solid fa-question"></i>  Help</li>
                        <li onclick="Deconnexion();" class="red"><i class="fa-solid fa-right-from-bracket"></i>  Deconnexion</li>
                        <li onclick="DeleteAccount();"  class="red"><i class="fa-solid fa-user-slash"></i>  Delete Account</li>
                    </ul>
                </div>

                <!-- -----The pages ------ -->
                <div class="information-to-change " style="overflow-y: auto;">
                    <div class="closePanel">
                        <div class="width"></div>
                        <div class="close">
                            <i class="fa-solid fa-close" onclick="closePanel();"></i>
                        </div>
                    </div>

                    <!-- Now The information of user -->

                    <div class="info-user-container">
                        <div class="info-user-title">
                            <h1>Change informations</h1>
                        </div>

                        <!-- the forms -->

                        <div class="user-info-form-container">
                            <form action="edit_profile.php" method="post">
                                <div class="user-image">
                                    <div class="image" style="background-image: url(<?= $_SESSION['user']['profile_url'] ?? '../images/default_admin.jpg' ?>);"></div>
                                </div>
                                <div class="input-url">
                                    <label for="profile_url">Change Profile Image : </label>
                                    <input type="url" id="profile_url" name="profile_url" 
                                        value="<?php echo htmlspecialchars($_SESSION['user']['profile_url'] ?? ''); ?>" 
                                        placeholder="Enter image URL (jpg, png, gif)">
                                    <small style="color: #f87676;"><i class="fa-solid fa-triangle-exclamation" style="color: red;"></i> Leave empty if you wanna keep the actual image!</small>
                                    <small style="color: #f87676;"><i class="fa-solid fa-triangle-exclamation" style="color: red;"></i>We only accept (jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp').</small>
                                </div>

                                <!-- The rest of inputs -->

                                <div class="container">
                                    <div class="username">
                                        <label for="username">Username :</label>
                                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['user']['username']); ?>" required>
                                    </div>
                                    <div class="email">
                                        <label for="email">Email :</label>
                                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" required>
                                    </div>
                                    <div class="password">
                                        <label for="password">Password :</label>
                                        <input type="password" id="password" name="password">
                                    </div>
                                    <div class="bio">
                                        <label for="bio">bio :</label>
                                        <textarea id="bio" name="bio" required><?php $bio = $_SESSION['user']['bio'] ?? 'No bio found.';echo nl2br(trim(htmlspecialchars($bio)));?></textarea>
                                    </div>
                                    <div class="button-submit">
                                        <button type="submit"><i class="fa-regular fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="saved-code">
                    <div class="close-saved-code">
                        <div class="width-code"></div>
                        <div class="close-code-saved" onclick="toggleSavedCode();">
                            <i class="fa-solid fa-close"></i>
                        </div>
                    </div>

                    <!-- NOW THE CODE SAVED BY THIS USER -->

                    <?php 
                        $stmt = $pdo->prepare("
                            SELECT codes.*, saved.saved_at 
                            FROM saved 
                            INNER JOIN codes ON saved.code_id = codes.id 
                            WHERE saved.user_id = ? 
                            ORDER BY saved.saved_at DESC
                        ");
                        $stmt->execute([$_SESSION['user']['id']]);
                        $saved = $stmt->fetchAll();


                        $countSavedCode = count($saved);
                    ?>

                    <div class="saved-codes-containers">
                        <div class="saved-codes-list">
                            <?php if($countSavedCode > 0 ) : ?>
                                <?php foreach( $saved as $save) :?>
                                    <div class="saved-code-item" onclick="window.location.href = '../code-details.php?id=<?= $save['id'] ?>'">
                                        <div class="code-title">
                                            <?= $save['title'] ?>
                                        </div>
                                        <div class="saved-code-date">
                                            <?= $save['saved_at'] ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No code saved.</p>
                            <?php endif ; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script src="../assets/js/theme-no-button.js"></script>

    <script>
        function Deconnexion(){
            let confirmation = confirm('You going to be deconnected, Are you Sur ?');

            if(confirmation){
                window.location.href = '../logout-user.php';
            }
        }

        function DeleteAccount(){
            let DeleteConfirmation = confirm('ARE YOU SUR YOU WANT TO DELETE YOU\'RE ACCOUNT?')

            if(!DeleteConfirmation){
                window.location.reload();
            }else{
                let confirmation = confirm('THIS ACTION IS IRREVERSIBLE! ' + '\n' + 'FOR THE LAST TIME, YOU SUR ABOUT THAT?');

                if(!confirmation){
                    window.location.reload();
                }else{
                    let userReason = prompt('TELL US WHY YOU LEAVE!');
                    
                    if(userReason.lenght < 0){
                        window.location.reload();
                    }else{
                        localStorage.setItem('Reason of leaving AGC-Git-Codes by <?php echo $_SESSION['user']['username'] ?>', userReason);
                        window.location.href = 'delete_account.php';
                    }
                }
            }
        }

        function themeOfUser(){
            let ThemeStorage = localStorage.getItem('theme');

            console.log(ThemeStorage);

            let message;
            let body = document.body;

            if(ThemeStorage == 'light'){
                message = confirm('THE THEME WILL CHANGE TO DARK, YOU\'RE SUR?')

                if(message){
                    body.classList.remove('light');
                    body.classList.add('dark');
                    localStorage.setItem('theme', 'dark');

                    window.location.reload();
                }
            }

            if(ThemeStorage == 'dark'){
                message = confirm('THE THEME WILL CHANGE TO LIGHT, YOU\'RE SUR?')

                if(message){
                    body.classList.add('light');
                    body.classList.remove('dark');
                    localStorage.setItem('theme', 'light');

                    window.location.reload();
                }
            }
        }

        function changeInformation(){
            const panelInformation = document.querySelector('.information-to-change');
            panelInformation.classList.toggle('active');
        }

        function closePanel(){
            const panelInformation = document.querySelector('.information-to-change');
            panelInformation.classList.toggle('active');   
        }

        function toggleSavedCode(){
            let containerCodes = document.querySelector('.saved-code');
            containerCodes.classList.toggle('active');
        }
    </script>
</body>
</html>
