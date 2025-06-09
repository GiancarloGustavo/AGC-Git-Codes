<?php
session_start();

$title = 'About AGC-Git-Codes';
$favicon_96_link = '../assets/images/favicon-96x96.png';
$faviconSvg_link = '../assets/images/favicon.svg';
$faviconIco_link = '../assets/images/favicon.ico';
$apple_icon_link_180 = '../assets/images/apple-touch-icon.png';
$css_link_url = '../assets/css/pages.css';
$index_link = '../index.php';

?>


<!DOCTYPE html>
<html lang="en">
<?php include '../includes/headPageTrue.php'; ?>
<body>
    <?php include "../includes/headerPageTrue.php" ?>

    <main>
        <div class="container">
            <div class="page-title">
                <h1>About Us</h1>
            </div>

            <!-- The informations Now -->

            <section class="page-container-info">
                <div class="page-container-title">
                    <h4>Our goals</h4>
                </div>

                <!-- The list of infos -->

                <div class="lists">
                    <ol class="list-item">
                        <li>Make coding lover like us code better.</li>
                        <li>Become a pro with in programming.</li>
                        <li>Feel free to Us our codes in their projects.</li>
                        <li>Enjoy with our codes while visiting.</li>
                        <li>So, welcome to you 'Lucky human', 'cause our website will be a paradise for you!</li>
                    </ol>
                </div>
            </section>

            <!-- For the little footer -->

            <section class="footer">
                <div class="footer-item">
                    <span class="copyright">© copyright Git-Code 2025</span>
                </div>
            </section>
        </div>
    </main>
    
    <!-- All the scripts files -->
    <script src="../assets/js/theme-no-button.js" defer></script>
</body>
</html>