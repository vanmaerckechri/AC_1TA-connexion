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
    <div class="ui uiMainMenuStudent">
         <div class="step_scores statsPlanetContainer disabled_v2">
            <div class="statsContainer titleEnv">Env.
                <div class="statsBar envi"><?=$planetStats["stats_environnement"]?></div>
            </div>
            <div class="statsContainer titleSante">Santé
                <div class="statsBar sante"><?=$planetStats["stats_sante"]?></div>
            </div>
            <div class="statsContainer titleSocial">Social
                <div class="statsBar social"><?=$planetStats["stats_social"]?></div>
            </div>
        </div>
        <a class="leaveGameButton" href="../../library.php">Quitter le Jeu</a>
    </div>
<?php
$content = ob_get_clean();
ob_start();
?>
    <script>
        let planetStats = <?=json_encode($planetStats)?>;
    </script>
    <script src="assets/library/three.js"></script>
    <script src="assets/library/OrbitControls.js"></script>
    <script src="assets/js/st_mainmenu.js"></script>
    <script src="assets/js/stats.js"></script>
<?php
$script = ob_get_clean();

require('./view/st_template.php');
