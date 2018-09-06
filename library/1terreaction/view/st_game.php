<?php

$title = "1TerreAction - En Jeu";

// Free Classroom List
ob_start();
?>
    <div id="headerProfile" class="headerProfile">
        <div class="appTitle">1TerreAction</div>
        <div class="profile">
            <div id="avatarContainer" class="avatarContainer">
                <?=$avatarContent?>
            </div>
            <?=$_SESSION['nickname']?>
            <a href="admin.php?action=disco" class="disconnect">X</a>
        </div>
    </div>
    <div id="main">
        <div id="step_localBackground">
            <div class="localBgContainer">
                <img class="localBg localBgAir" src="<?=$localBgAir_imgSrc?>" alt="ciel faisant parti du parallax">
            </div>
            <div id="mainMenuContainer" class="menu">
                <div class="menuButtonContainer">
                    <button id="launchThemesMenuButton" class="menuButton disabled_v2">Jouer</button>
                </div>
                <div class="menuButtonContainer">
                     <button id="backToSolarSystem" class="menuButton disabled_v2">Système Solaire</button>
                </div>
                <div class="menuButtonContainer">
                     <button id="leaveGame" class="menuButton disabled_v2">Quitter</button>
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
                    if ($key >= $gameInfos->playerGameInfos['unlocked_theme'])
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
                <div id="questionIntro" class="questionIntro"></div>
                <img id="themeBackground" class="themeBackground" src="">
                <div id="questionButton01" class="questionButton"></div>
                <div id="questionButton02" class="questionButton"></div>
                <div id="questionButton03" class="questionButton"></div>
                <div id="questionContainer" class="questionContainer disabled">
                <p id="question" class="question">cvm</p>
                <textarea id="openQuestionTextArea" class="openQuestionTextArea disabled"></textarea> 
                <div id="propositionsContainer" class="propositionsContainer">
                    <img id="" class="proposition" src="" alt="">
                    <img id="" class="proposition" src="" alt="">
                    <img id="" class="proposition" src="" alt="">
                </div>
            </div>
            </div>
            <a class="buttonDefault abandonGame" href="index.php?action=game">abandonner</a>
        </div>
        <?php
            if ($activeScoreTab == true)
            {
        ?>
                <div id="step_scores" class="step_scores">
                    <div id="scoresContainer" class="scoresContainer">
                        <button id="scoresContainerClose" class="scoresContainerClose">X</button>
                        <h2>Scores</h2>
                        <div id="scoresTitleContainer" class="scoresTitleContainer">
                            <h3 class="scoreTitle">Statistiques</h3>
                            <div class="scoreTitle titleEnv">Env.</div>
                            <div class="scoreTitle titleSante">Santé</div>
                            <div class="scoreTitle titleSocial">Social</div>
                        </div>

                        <div id="scoresThisGameContainer" class="scoresThisGameContainer">
                            <h3>Pour cette Partie:</h3>
                            <div class="envi"><?=round($statsEnvAverage, 2)?></div>
                            <div class="sante"><?=round($statsSanAverage, 2)?></div>
                            <div class="social"><?=round($statsSoAverage, 2)?></div>
                        </div>

                        <div id="scoresAllThemesContainer" class="scoresAllThemesContainer">
                            <h3>Pour Tous les Thèmes:</h3>
                            <?php
                            foreach ($gameInfos->playerStats as $key => $value)
                            {
                                $value = round($value, 2);
                                $class = "";
                                if ($key == "stats_envi")
                                {
                                    $class = "envi";
                                }
                                else if ($key == "stats_sante")
                                {
                                    $class = "sante";                  
                                }
                                else if ($key == "stats_social")
                                {
                                    $class = "social";                  
                                }
                                ?>
                                <div class="<?=$class?>"><?=$value?></div>
                                <?php
                            }
                            ?>
                        </div>

                        <div id="scoresThisPlanetContainer" class="scoresThisPlanetContainer">
                            <h3>Pour la Planète:</h3>
                            <?php
                            foreach ($gameInfos->planetStats as $key => $value)
                            {
                                $value = round($value, 2);
                                $class = "";
                                if ($key == "stats_environnement")
                                {
                                    $class = "envi";
                                }
                                else if ($key == "stats_sante")
                                {
                                    $class = "sante";                  
                                }
                                else if ($key == "stats_social")
                                {
                                    $class = "social";                  
                                }
                                ?>
                                <div class="<?=$class?>"><?=$value?></div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
        <?php
            }
        ?>
        <div id="pacmanContainer" class="pacmanContainer disabled">
        </div>
    </div>
<?php
$content = ob_get_clean();
ob_start();
?>
    <script>
        let gameInfos = <?=json_encode($gameInfos)?>;
    </script>
    
    <script type= "text/javascript" src="assets/js/questions.js"></script>
    <script type= "text/javascript" src="assets/js/game.js"></script>
    <script type= "text/javascript" src="assets/js/ecoman_game-maps.js"></script>
    <script type= "text/javascript" src="assets/js/ecoman_game-player.js"></script>
    <script type= "text/javascript" src="assets/js/ecoman_game-ghosts.js"></script>
    <script type= "text/javascript" src="assets/js/ecoman_game-pathfinder.js"></script>
    <script type= "text/javascript" src="assets/js/ecoman_game-engine.js"></script>
<?php
$script = ob_get_clean();

require('./view/st_template.php');
