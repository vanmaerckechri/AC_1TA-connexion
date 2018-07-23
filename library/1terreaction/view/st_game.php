<?php

$title = "1TerreAction - En Jeu";

// Free Classroom List
ob_start();
?>
    <div class="headerProfile">
        <div class="appTitle">1TerreAction</div>
        <div class="profile">
            <?=$_SESSION['nickname']?>
            <a href="admin.php?action=disco" class="disconnect">X</a>
        </div>
    </div>
    <div id="main">
        <div id="step_localBackground">
            <div class="localBgContainer">
                <img class="localBg localBgAir" src="<?=$localBgAir_imgSrc?>" alt="ciel faisant parti du parallax">
                <img class="localBg localBgWater" src="<?=$localBgWater_imgSrc?>" alt="eau faisant partie du parallax">
                <img class="localBg localBgForest" src="<?=$localBgForest_imgSrc?>" alt="arbres faisant partis du parallax">
                <img class="localBg localBgAirFilter" src="<?=$localBgAirFilter_imgSrc?>" alt="air filtre faisant parti du parallax">
            </div>
            <div class="menu">
                <div class="menuButtonContainer">
                     <button id="mainMenuButton" class="menuButton">Menu</button>
                </div>
                <div class="menuButtonContainer disabled">
                     <button id="backToSolarSystem" class="menuButton">Système Solaire</button>
                </div>
                <div class="menuButtonContainer disabled">
                    <button id="launchThemesMenuButton" class="menuButton">Jouer</button>
                </div>
            </div>
        </div>
        <div id="step_chooseTheme" class="disabled">
            <div id="themesContainer" class="themesContainer">
                <h2>Thèmes</h2>
            <?php
                foreach ($allThemes as $key => $theme)
                {
                    $unLockedClass = "unlocked";
                    if ($key >= $gameInfos->playerStats['unlocked_theme'])
                    {
                        $unLockedClass = "";
                        $theme = "<img src='assets/img/locked.png'>";
                    }
                ?>
                    <div class="theme <?=$unLockedClass?>"><?=$theme?></div>
                <?php
                }
            ?>
                <div class="previousContainer"><img id="backToLocalBg" class="previous" src="assets/img/previous_dark.svg" alt="boutton page précédente"></div>
            </div>
        </div>
        <div id="step_questions">
        </div>
        <div id="step_breakGame">
        </div>
    </div>
<?php
$content = ob_get_clean();
ob_start();
?>
    <script>
        let test = <?=json_encode($gameInfos)?>;        
    </script>

    <script src="assets/js/game.js"></script>
<?php
$script = ob_get_clean();

require('./view/st_template.php');
