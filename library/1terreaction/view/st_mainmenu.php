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
            <a href="index.php?action=disco" class="disconnect">X</a>
        </div>
    </div>
    <div id="uiMainMenuStudent" class="ui uiMainMenuStudent">
        <div class="buttonsContainer">
            <button id="aboutUsButton" class="buttonDefault aboutUsButton">À Propos</button>
        </div>
        <div class="clickHere"><p>Clique sur ta planète pour continuer</p><img src="assets/img/arrow_bot.svg" alt=""></div>
        <div class="step_scores statsPlanetContainer disabled_v2">
        <?php
            if ($GLOBALS["planetHaveStats"] == 0)
            {
        ?>
            <div class="statsContainer titleEnv">
                <div class="statsBar envi"><?=$planetStats["stats_environnement"]?></div>
                <p>Env.</p>
            </div>
            <div class="statsContainer titleSante">
                <div class="statsBar sante"><?=$planetStats["stats_sante"]?></div>
                <p>Santé</p>
            </div>
            <div class="statsContainer titleSocial">
                <div class="statsBar social"><?=$planetStats["stats_social"]?></div>
                <p>Social</p>
            </div>
        <?php
            }
        ?>
            <a class="leaveGameButton" href="../../library.php">Quitter le Jeu</a>
        </div>
    </div>
<?php
$content = ob_get_clean();
ob_start();
?>
    <script>
        let themeVideo = <?=json_encode($themeVideo)?>;
        let planetStats = <?=json_encode($planetStats)?>;
    </script>
    <script src="assets/threejs/three.js"></script>
    <script src="assets/threejs/OrbitControls.js"></script>
    <script src="assets/js/st_mainmenu.js"></script>
    <script src="assets/js/stats.js"></script>
<?php
$script = ob_get_clean();

require('./view/st_template.php');
