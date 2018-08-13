<?php

$title = "1TerreAction - En Jeu";

// Free Classroom List
ob_start();
?>
    <div id="headerProfile" class="headerProfile">
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
            </div>
            <div class="menu">
                <div class="menuButtonContainer">
                     <button id="backToSolarSystem" class="menuButton disabled_v2">Système Solaire</button>
                </div>
                <div class="menuButtonContainer">
                    <button id="launchThemesMenuButton" class="menuButton disabled_v2">Jouer</button>
                </div>
                <div class="menuButtonContainer">
                     <button id="mainMenuButton" class="menuButton">Menu</button>
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
                    <div class="themeButton <?=$unLockedClass?>">
                        <div class="theme"><?=$theme?></div>
                    </div>
                <?php
                }
            ?>
                <div class="previousContainer"><img id="backToLocalBg" class="previous" src="assets/img/previous_dark.svg" alt="boutton page précédente"></div>
            </div>
        </div>
        <div id="step_questions" class="disabled">
            <div id="themeBackgroundContainer" class="themeBackgroundContainer">
                <img id="themeBackground" class="themeBackground" src="">
                <div id="questionButton01" class="questionButton"></div>
                <div id="questionButton02" class="questionButton"></div>
                <div id="questionButton03" class="questionButton"></div>
            </div>
            <div id="questionContainer" class="questionContainer disabled">
                <p id="question" class="question"></p>
                <div id="propositionsContainer" class="propositionsContainer">
                    <button class="proposition"><img id="" src="" alt=""></button>
                    <button class="proposition"><img id="" src="" alt=""></button>
                    <button class="proposition"><img id="" src="" alt=""></button>
                </div>
            </div>
        </div>
        <div id="step_breakGame">
        </div>
    </div>
<?php
$content = ob_get_clean();
ob_start();
?>
    <script>
        let gameInfos = <?=json_encode($gameInfos)?>;

    </script>

    <script src="assets/js/questions.js"></script>
    <script src="assets/js/game.js"></script>
<?php
$script = ob_get_clean();

require('./view/st_template.php');
