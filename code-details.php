<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();

require 'database/db.php';

if (!isset($_GET['id'])) {
    die("Code not found!");
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM codes WHERE id = ?");
$stmt->execute([$id]);
$code = $stmt->fetch();

$title = $code['title'];
$favicon_96_link = 'assets/images/favicon-96x96.png';
$faviconSvg_link = 'assets/images/favicon.svg';
$faviconIco_link = 'assets/images/favicon.ico';
$apple_icon_link_180 = 'assets/images/apple-touch-icon.png';
$index_link = 'index.php';
$description = $code['description'];

$css_code = $code['css_code'] ?? 'null';
$js_code = $code['js_code'] ?? 'null';


$code_id = $_GET['id']; // ID du code affiché
$stmt = $pdo->prepare("SELECT c.*, u.username, u.profile_url 
                       FROM comments c 
                       LEFT JOIN users u ON c.user_id = u.id
                       WHERE c.code_id = ? AND c.parent_id IS NULL 
                       ORDER BY c.created_at DESC");
$stmt->execute([$code_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);


$current_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$encoded_url = urlencode($current_url);
$encoded_title = urlencode($title);


?>

<?php 

$is_saved = false;
if (isset($_SESSION['user'])) {
    $stmt = $pdo->prepare("SELECT * FROM saved WHERE user_id = ? AND code_id = ?");
    $stmt->execute([$_SESSION['user']['id'], $code['id']]);
    $is_saved = $stmt->fetch() ? true : false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="assets/css/code-detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="description" content="<?= $description ?>">
    <meta name="keywords" content="Javasript, html, css, php, MySQL ..., code, coding, burgermenu, grid css, flexbox,Github, GitLab, website">
    <meta name="author" content="ANSANM">
    <link rel="canonical" href="https://www.Ansanm-Git-code.great-site.net">

<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-html.min.js"></script>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- the important part -->

    <link rel="icon" type="image/png" href=<?= $favicon_96_link ?> sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href=<?= $faviconSvg_link ?>/>
    <link rel="shortcut icon" href=<?= $faviconIco_link ?> />
    <link rel="apple-touch-icon" sizes="180x180" href=<?= $apple_icon_link_180 ?> />
    <meta name="apple-mobile-web-app-title" content="Ansanm-Git-Code" />
    <link rel="manifest" href="manifest.json"/>
</head>
<body>
    <!-- The header -->
    <?php include 'includes/headerPageTrue.php'; ?>

    <!-- The main -->

    <main>
        <div class="container">

            <div class="switchButton-codesContainer">
                
                <!-- for the buttons of the codes -->
                <div class="switchButton-container">
                    <div class="aboveTheHeader"></div>
                    <div class="buttons-switch-container">
                        <div class="html-btn-container">
                            <button class="html-btn active">HTML</button>
                        </div>
                        <div class="css-btn-container">
                            <button class="css-btn">css</button>
                        </div>
                        <div class="js-btn-container">
                            <button class="js-btn">JS</button>
                        </div>
                    </div>
                    <div class="falseAboveHeader"></div>
                </div>

                <div class="codes-container-all">

                    <div class="codes-container-html active">
                        <div class="code-item-container">
                            <div class="html-codes">
                                <div class="html-code-header">
                                    <div class="language-tag-span" style="height: 100%; display: flex; align-items: center;">
                                        <span class="language-tag">HTML</span>
                                    </div>
                                    <div class="copy-btn-container">
                                        <button class="copy-btn" onclick="copyCodeHtml(this);">
                                            <i class="fa-regular fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>

                                <!-- Now the html code -->

                                <div class="code-container">
                                    <pre><code class="language-html"><?= htmlspecialchars(trim($code['html_code'])) ?></code></pre>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="codes-container-css">
                        <div class="code-item-container">
                            <div class="css-codes">
                                <div class="css-code-header">
                                    <div class="language-tag-span" style="height: 100%; display: flex; align-items: center;">
                                       <span class="language-tag">CSS</span>
                                    </div>
                                    <div class="copy-btn-container">
                                        <button class="copy-btn" onclick="copyCodeCss(this);">
                                            <i class="fa-regular fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>

                                <!-- Now the css code -->

                                <div class="code-container">
                                    <pre><code class="language-css"><?= htmlspecialchars(trim($code['css_code'])) ?></code></pre>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="codes-container-js">
                        <div class="code-item-container">
                            <div class="js-codes">
                                <div class="js-code-header">
                                    <div class="language-tag-span" style="height: 100%; display: flex; align-items: center;">
                                         <span class="language-tag">JS</span>
                                    </div>
                                    <div class="copy-btn-container">
                                        <button class="copy-btn" onclick="copyCodeJs(this);">
                                            <i class="fa-regular fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>

                                <!-- Now the js code -->

                                <div class="code-container">
                                    <pre><code class="language-js"><?= htmlspecialchars(trim($code['js_code'])) ?></code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Now the comment section -->

            <!-- To share the code -->

            <div class="share-code"
            style="
                width: 100%;
                padding: clamp(3px, 3.5vw, 6px);
                display: flex;
                justify-content: center;
                gap: clamp(3.5px, 3.5vw, 7px);
                flex-direction: column;
                border-top: 2px solid #17fff3;
                border-bottom: 2px solid #17fff3;
                margin: clamp(8px, 3.5vw, 13px) 0;
            "
            >
                <div class="share-code-title"
                style=" 
                    width: 100%;
                    padding: clamp(2.4px, 3.5vw, 5px);
                    display: flex;
                    align-items : center;
                    justify-content: center;
                "
                >
                <h3
                style="font-size: clamp(17px, 3.5vw, 22px);"
                >Share the code</h3>
            </div>
                <div class="share-code-item"
                style="
                    display: flex;
                    align-items: center;
                    justify-content: space-around;
                    padding: clamp(6px, 3.5vw, 5px);
                "
                >
                    <div class="facebook">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encoded_url ?>" target="_blank">
                            <i class="fa-brands fa-facebook" 
                            style="
                                font-size: clamp(17px, 3.5vw, 22px);
                                font-weight: bold;
                                color: #17fff3;
                                cursor: pointer;
                            " ></i>
                        </a>
                    </div>
                    <div class="twitter">
                        <a href="https://twitter.com/intent/tweet?url=<?= $encoded_url ?>&text=<?= $encoded_title ?>" target="_blank">
                            <i class="fa-brands fa-x-twitter"
                            style="
                                font-size: clamp(17px, 3.5vw, 22px);
                                font-weight: bold;
                                color: #17fff3;
                            "   
                            ></i>
                        </a>
                    </div>
                    <div class="whatsapp">
                        <a href="https://wa.me/?text=<?= $encoded_title ?>%20<?= $encoded_url ?>" target="_blank">
                            <i class="fa-brands fa-whatsapp"
                            style="
                                font-size: clamp(17px, 3.5vw, 22px);
                                font-weight: bold;
                                color: #17fff3;
                            "           
                            ></i>
                        </a>
                    </div>
                    <div class="linkedIn">
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $encoded_url ?>&title=<?= $encoded_title ?>" target="_blank">
                            <i class="fa-brands fa-linkedin-in"
                            style="
                                font-size: clamp(17px, 3.5vw, 22px);
                                font-weight: bold;
                                color: #17fff3;
                            "
                            ></i>
                        </a>
                    </div>
                    <div class="snapchat">
                        <a href="https://www.snapchat.com/scan?attachmentUrl=<?= $encoded_url ?>" target="_blank">
                            <i class="fa-brands fa-snapchat"
                            style="
                                font-size: clamp(17px, 3.5vw, 22px);
                                font-weight: bold;
                                color: #17fff3;
                            "
                            ></i>
                        </a>
                    </div>
                    <div class="instagram" style="cursor: pointer;">
                        <span onclick="copyShareLink()">
                            <i class="fa-brands fa-instagram" 
                            style="
                                font-size: clamp(17px, 3.5vw, 22px);
                                font-weight: bold;
                                color: #17fff3;
                            "
                            ></i>
                        </span>
                    </div>
                </div>
            </div>

            <script>
                function copyShareLink() {
                    navigator.clipboard.writeText("<?= $current_url ?>").then(() => {
                        alert("Link copied successfully!");
                    });
                }
            </script>


            <?php if(isset($_SESSION['admin_logged_in']) && isset($_SESSION['admin_logged_in']) == 1) : ?>
                <!-- the button to edit  -->

                <!-- This for the admin -->

                <form action="edit_codes.php" method="post" id="form-to-edit">
                <input type="hidden" name="code_id" id="code_id" value="<?= $code_id ?>">
                <button type="submit" id="edit-code-admin" style="padding: clamp(3.8px, 3.5vw, 7px); border-radius: 5px; font-weight: bold; color: gold; border: 2px solid gold; background: var(--btn-primary);">Edit the code</button>
                </form>
            <?php endif; ?>

            <?php if(isset($_SESSION['user'])): ?>
                <span>Save the code  :   </span><button id="save-btn" data-code-id="<?= $code['id'] ?>" style="color: <?= $is_saved ? 'gold' : 'white' ?>; font-size:24px; border:none; background:none; cursor:pointer;"><i class="fa-solid fa-bookmark"></i></button>
            <?php elseif(isset($_SESSION['admin_logged_in'])) : ?>
                <button id="save-btn" data-code-id="<?= $code['id'] ?>" style="display: none;"><i class="fa-solid fa-bookmark"></i></button>
            <?php endif; ?>

            <div class="container-info-code-and-comments-section">
                <div class="code-info-primary-container">
                    <div class="code-title-h1">
                        <h1><?= $code['title']; ?></h1>
                    </div>
                    <div class="code-description-p">
                        <p><?= nl2br(htmlspecialchars($code['description'])); ?></p>
                    </div>
                </div>
                <br />
                <h3 style="font-size: clamp(18px, 3.5vw, 22px); text-decoration: underline; font-weight: bold;">Comments section</h3>

                <div class="button-show-comment-fill-form">
                    <div class="button-show">
                        <button id="show-comment-fill-form" onclick="showFormFill();">Click to add a comment</button>
                    </div>
                </div>

                <?php if(isset($_SESSION['user'])): ?>
                    <div class="add-comment-container hide">
                        <div class="add-comment-form ">
                            <form action="add_comment.php" method="POST">
                                <input type="hidden" name="code_id" value="<?= htmlspecialchars($code_id)?>">
                                <textarea name="content" id="content" required placeholder="Add a comment ..."></textarea>
                                <button type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="add-comment-container hide" style="
                        width: 100%;
                        

                        ">
                        <div class="add-comment-form" style="
                        width: 100%;
                        display: flex;
                        justify-content: center;
                        gap: clamp(2px, 3.5vw, 3.5px);
                        flex-direction: column;
                        ">
                            <textarea name="content" id="content" placeholder="Add a comment..." required style="
                            width: 100%;
                            "></textarea>
                            <button type="submit" onclick="commentFunction();" style="
                            width: 100%;
                            ">Submit</button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- --------------------------  ---------------------------- -->
                    <?php
                        $commentsNumber = count($comments);
                    ?>

                <div class="container-comments-all">
                    <?php if($commentsNumber > 0 ) : ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comments-container" style=" margin-bottom:10px;">
                                <div class="comments-img-name">
                                    <img src="<?= $comment['profile_url'] ?? 'images/default_admin.jpg' ?>" onclick="window.location.href = '<?= $comment['profile_url'] ?? 'images/default_admin.jpg' ?>'">
                                    <strong id="bottom"><?= htmlspecialchars($comment['username']) ?></strong>
                                </div>
                                <p style="margin-left: 10px; margin-top: 8px;" id="comment-id=<?= $comment['id']; ?>"><em><?= nl2br(htmlspecialchars($comment['content'])) ?></em></p>
                                <p class="date" style="margin-left: 10px;"><?= nl2br(htmlspecialchars($comment['created_at'])) ?></p>

                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $comment['user_id']): ?>
                                    <form method="post" action="delete_comment.php" style="margin-top:5px; margin-left: 10px;">
                                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                        <input type="hidden" name="code_id" value="<?= $code_id ?>">
                                        <button type="submit" id="delete-btn">Delete</button>
                                    </form>
                                <?php endif; ?>

                                <?php
                                $rep = $pdo->prepare("SELECT * FROM comments WHERE parent_id = ?");
                                $rep->execute([$comment['id']]);
                                $admin_reply = $rep->fetch(PDO::FETCH_ASSOC);
                                ?>

                                <?php  if ($admin_reply && is_array($admin_reply)) : ?>
                                    <div class="reply-comments-admin">
                                        <strong style="color: white; font-weight: bold; font-size: clamp(17px, 3.5vw, 20px); text-decoration: underline;">Réponse de l'Admin </strong>:
                                        <p style="margin-left: 9px;" id="reply-comment-id=<?= $admin_reply['id']; ?>"><em><?= nl2br(htmlspecialchars($admin_reply['content'])) ?></em></p>
                                        <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']['id'] == 1): ?>
                                            <form method="post" action="delete_comment.php">
                                                <input type="hidden" name="comment_id" value="<?= $admin_reply['id'] ?>">
                                                <button type="submit" id="delete-btn-admin">Delete</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>   
                                <?php elseif (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']['id'] == 1) : ?>
                                    <form method="post" action="reply_comment.php" style="margin-left:20px;">
                                        <input type="hidden" name="parent_id" value="<?= $comment['id'] ?>">
                                        <input type="hidden" name="code_id" value="<?= $code_id ?>">
                                        <textarea name="content" rows="2" placeholder="Répondre à ce commentaire" required id="textarea-admin"></textarea><br>
                                        <button type="submit" id="reply-comment-btn">Reply</button>
                                    </form>
                                <?php endif ; ?>   
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No comments posted.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php
                $admin_connected_not_comment = isset($_SESSION['admin_logged_in']); 

                if($admin_connected_not_comment){
                    echo '<script>';
                    echo 'document.querySelector(\'.add-comment-container\').style.display = \'none\';';
                    echo '</script>';
                }
            ?>
        </div>
    </main>

    <!-- All the scripts -->
    <script src="assets/js/code-details.js" defer></script>
    <script src="assets/js/theme-no-button.js" defer></script>

    <script>
        const codesContainer = document.querySelector('.codes-container-all');
        const buttonsContainer = document.querySelector('.switchButton-container');

        let buttonsContainerHeight = buttonsContainer.offsetHeight;

        codesContainer.style.marginTop = `${buttonsContainerHeight + 12}px`;

        const falseHeader = document.querySelector('.aboveTheHeader');
        const TrueHeader = document.querySelector('header');

        let TrueHeaderWidth = TrueHeader.offsetWidth;

        falseHeader.style.width = `${TrueHeaderWidth}px`; 
    </script>

    <script>
        function commentFunction() {
            if(confirm('You must be logged in to comment. Redirect to login page?')) {
                window.location.href = './auth/login-user.php';
            }
        }
    </script>

    <script>
        document.getElementById('save-btn').addEventListener('click', function () {
            const codeId = this.dataset.codeId;
            const button = this;

            fetch('toggle_save.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `code_id=${codeId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.style.color = data.saved ? 'gold' : 'white';
                } else {
                    alert(data.message || 'An error occurred.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>

</body>
</html>