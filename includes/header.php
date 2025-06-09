

 <header>
    <div class="header-container">
        <div class="header-logo-container-burger-open-icon">
            <div class="burger-icon-container">
                <div class="burger-icon" 
                
                <?php if(isset($_SESSION['user']) || isset($_SESSION['admin_logged_in'])) : ?>
                    onclick="toggleHeaderName();"
                <?php elseif(!isset($_SESSION['user']) && !isset($_SESSION['admin_logged_in'])) : ?>
                    onclick="toggleHeaderNameNoConnexion();"
                <?php endif; ?>

                title="Click to see magic"
                >
                    <div class="btn-primary">
                        <span class="burger one"></span>
                        <span class="burger two"></span>
                        <span class="burger three"></span>
                </div>
                </div>
            </div>
            <div class="header-logo-container-image-logo-name hide">
                <div class="header-image-logo-name">
                    <div class="logo-image"></div>
                    <div class="logo-name">AGC-Git-Codes</div>
                </div>
            </div>
        </div>
        
        <!-- If user or the owner is not connected -->

        <?php if(!isset($_SESSION['user']) && !isset($_SESSION['admin_logged_in'])): ?>
            <div class="info-website-container">
                <div class="info-stand-up">
                    <div class="contact-container" title="Contact" style="padding: 0 0 12px 0;" onclick="window.location.href = 'pages/contact.php'"><div class="btn-primary"><i class="fa-solid fa-id-badge"></i></div></div>
                    <div class="about-container" title="About Us" style="padding: 0 0 12px 0;" onclick="window.location.href = 'pages/about-us.php'"><div class="btn-primary"><i class="fa-solid fa-address-card"></i></div></div>
                    <div class="social-container" title="Socials Medias"  onclick="window.location.href = 'pages/socials.php'" style="padding: 0 0 12px 0;"><div class="btn-primary"><i class="fa-solid fa-globe"></i></div></div>
                    <div class="social-container" title="Click to Search Codes" style="padding: 0 0 12px 0;"><div class="btn-primary" onclick="searchFunction();"><i class="fa-solid fa-search"></i></div></div>
                    <div class="social-container" title="Change the theme color"><div class="btn-primary" id="theme-btn"><i class="fa-solid fa-moon"></i></div></div>
                </div>
                <div class="span-name-icon-container hide">
                    <div class="info-span-stand-up">
                        <div class="info-name contact"><span>Contact</span></div>
                    </div>
                    <div class="info-span-stand-up">
                        <div class="info-name about-us"><span>About us</span></div>
                    </div>
                    <div class="info-span-stand-up" >
                        <div class="info-name social"><span>Socials Medias</span></div>
                    </div>
                    <div class="info-span-stand-up" >
                        <div class="info-name search"><span>Search</span></div>
                    </div>
                    <div class="info-span-stand-up" >
                        <div class="info-name theme"><span>Theme</span></div>
                    </div>
                </div>
            </div>
            <div class="header-profile-no-connexion" title="Your profile">
                <div class="profile-link-no-connexion"  onclick="window.location.href = 'user.php'">
                    <div class="btn-primary">
                        <i class="fa-solid fa-circle-user"></i>
                    </div>
                </div>
                <div class="profile-no-connexion-span-name-container hide" >
                    <div class="span-name-profile-no-connexion">
                        <span>Profile</span>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="navigation-container">
                <div class="navigation-links">
                    <div class="links-stand-up">
                        <div class="home" onclick="window.location.href = './index.php'" title="Home">
                            <div class="btn-primary">
                                <i class="fa-solid fa-home"></i>
                            </div>
                        </div>
                        <div class="search" onclick="searchFunction();" title="Click to search codes">
                            <div class="btn-primary">
                                <i class="fa-solid fa-search"></i>
                            </div>
                        </div>

                        <?php if(isset($_SESSION['user'])) : ?>

                            <div class="message" onclick="window.location.href = './contact_users.php?id=<?= $_SESSION['user']['id']?>'" title="If you need help, text AGC">
                                <div class="btn-primary">
                                    <i class="fa-solid fa-message"></i>
                                </div>
                            </div>

                            <div class="notification" onclick="window.location.href = './notifications_users.php?id=<?= $_SESSION['user']['id'] ?>'" title="Your notifications"
                            style="position: relative;"
                            >
                                <div class="btn-primary">
                                    <i class="fa-solid fa-bell"></i>
                                </div>
                                <div class="notification-count" 
                                style="
                                position: absolute;
                                top: -5px;
                                left: -5px;
                                background: linear-gradient(to right, purple, blue);
                                height: 25px;
                                width: 25px;
                                border-radius: 50%;
                                border: 2px solid #fff;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                "
                                ><span style="color: #17fff3; font-weight: bold;"><?= $count ?></span></div>
                            </div>
                            <div class="logout" onclick="DeconnexionUser();" title="Logout">
                                <div class="btn-primary">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </div>
                            </div>
                        <?php elseif(isset($_SESSION['admin_logged_in'])): ?>
                            <div class="message" onclick="window.location.href = './contact_admin.php'" title="Your messages Admin">
                                <div class="btn-primary">
                                    <i class="fa-solid fa-message"></i>
                                </div>
                            </div>

                            <div class="notification" onclick="window.location.href = './notifications_admin.php'" title="Your notifications admin"
                            style="position: relative;"
                            >
                                <div class="btn-primary">
                                    <i class="fa-solid fa-bell"></i>
                                </div>
                                <div class="notification-count"
                                style="
                                position: absolute;
                                top: -5px;
                                left: -5px;
                                background: linear-gradient(to right, purple, blue);
                                height: 25px;
                                width: 25px;
                                border-radius: 50%;
                                border: 2px solid #fff;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                "
                                ><span style="color: #17fff3; font-weight: bold;"><?= $count ?></span></div>
                            </div>
                            <div class="logout" onclick="DeconnexionAdmin();" title="Logout Admin">
                                <div class="btn-primary">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="theme">
                            <div class="btn-primary" id="theme-btn" title="Change the theme">
                                <i class="fa-solid fa-moon"></i>
                            </div>
                        </div>
                        <?php if(isset($_SESSION['user'])) : ?>
                            <div class="setting" onclick="window.location.href = './pages/edit_profile.php?id=<?= $_SESSION['user']['id'] ?>'" title="Settings">
                                <div class="btn-primary">
                                    <i class="fa-solid fa-gear"></i>
                                </div>
                            </div>
                        <?php elseif(isset($_SESSION['admin_logged_in'])) : ?>
                            <div class="setting" style="display: none;">
                                <div class="btn-primary">
                                    <i class="fa-solid fa-gear"></i>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- for the name of the buttons -->

                    <div class="links-stand-up-name hide">
                        <div class="home-span"><span>Home</span></div>
                        <div class="search-span"><span>Search</span></div>
                        <div class="message-span"><span>Messages</span></div>
                        <div class="notification-span"><span>Notifications</span></div>
                        <div class="logout-span"><span>Logout</span></div>
                        <div class="theme-span"><span>Theme</span></div>
                        <div class="setting-span"
                        
                        <?php if(isset($_SESSION['admin_logged_in'])) : ?>
                           style ="display : none;"
                        <?php else : ?>
                           style="display: block;"
                        <?php endif; ?>
                        ><span>Settings</span></div>
                    </div>
                </div>
            </div>
            <?php if(isset($_SESSION['user'])) : ?>
                <div class="header-profile" title="Your profile">
                    <div class="profile-link">
                        <div class="btn-primary" onclick="window.location.href = './user.php'">
                            <i class="fa-solid fa-circle-user"></i>
                        </div>
                    </div>
                    
                    <!-- profile name -->
                    <div class="profile-name hide">
                        <div class="span-name">
                            <span>Profile</span>
                        </div>
                    </div>
                </div>
            <?php elseif($_SESSION['admin_logged_in']) : ?>
                <div class="header-profile" title="Your admin profile">
                    <div class="profile-link">
                        <div class="btn-primary" onclick="window.location.href = './admin.php'">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                    </div>
                    
                    <!-- profile name -->
                    <div class="profile-name hide">
                        <div class="span-name">
                            <span>Profile</span>
                        </div>
                    </div>
                </div>
            <?php endif ; ?>
        <?php endif; ?>

    </div>

    <!-- for the search -->
        <div class="overlay-search" id="overlay-search">
            <div class="input-btn">
                <div class="input">
                    <input type="search" id="search" placeholder="make a search...">
                </div>
                <div class="btn-i" onclick="searchFunction();"><i class="fa-solid fa-close"></i></div>
            </div>
            <div class="results">
                <div id="result"></div>
            </div>
        </div>
 </header>

 <script>
        function DeconnexionUser(){
            let confirmation = confirm('You going to be deconnected, Are you Sur ?');

            if(confirmation){
                window.location.href = './logout-user.php';
            }
        }
        function DeconnexionAdmin(){
            let confirmation = confirm('You going to be deconnected, Are you Sur ?');

            if(confirmation){
                window.location.href = './logout.php';
            }
        }
 </script>