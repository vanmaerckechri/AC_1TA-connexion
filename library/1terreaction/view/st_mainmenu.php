<?php

$title = "1TerreAction - Main Menu";

// Free Classroom List
ob_start();
?>
    <div id="container">
    </div>
    <div class="headerProfile">
        <div class="appTitle">1TerreAction</div>
        <div class="profile">
            <div id="avatarContainer" class="avatarContainer">
                <?=$avatarContent?>
            </div>
            <?=$_SESSION['nickname']?>
            <a href="admin.php?action=disco" class="disconnect">X</a>
        </div>
    </div>
    <div class="ui">
        <a href="../../library.php">Quitter le Jeu</a>
    </div>
<?php
$content = ob_get_clean();
ob_start();
?>
    <script src="assets/library/three.js"></script>
    <script src="assets/library/OrbitControls.js"></script>
    <script src="assets/js/st_mainmenu.js"></script>
<?php
$script = ob_get_clean();

require('./view/st_template.php');
