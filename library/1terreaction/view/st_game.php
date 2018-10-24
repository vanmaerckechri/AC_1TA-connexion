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
            <a href="index.php?action=disco" class="disconnect">X</a>
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
                     <button id="backToSolarSystem" class="menuButton disabled_v2">Planète</button>
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
                <h2>Thème(s)</h2>
            <?php
               /* foreach ($allThemes as $key => $theme)
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
                }*/
            ?>
                <div id="backToLocalBgContainer" class="backToLocalBgContainer previousContainer"><img class="previous" src="assets/img/previous_dark.svg" alt="boutton page précédente"></div>
            </div>
        </div>
        <div id="step_questions" class="disabled">
            <div id="themeBackgroundContainer" class="themeBackgroundContainer">
                <div id="questionIntro" class="questionIntro"></div>
                <img id="themeBackground" class="themeBackground" src="">
                <div id="questionButton01" class="questionButton"></div>
                <div id="questionButton02" class="questionButton"></div>
                <div id="questionButton03" class="questionButton"></div>
                <div id="questionContainer" class="questionContainer disabled_v2">
                    <p id="question" class="question">cvm</p>
                    <textarea id="openQuestionTextArea" class="openQuestionTextArea disabled"></textarea> 
                    <div id="propositionsContainer" class="propositionsContainer">
                        <div class="propositionContainer">
                            <div class="propositionImgContainer">
                                <img id="" class="proposition" src="" alt="">
                            </div>
                            <p class="propositionText"></p>
                        </div>
                        <div class="propositionContainer propositionContainerMid">
                            <div class="propositionImgContainer">
                                <img id="" class="proposition" src="" alt="">
                            </div>
                            <p class="propositionText"></p>
                        </div>
                        <div class="propositionContainer">
                            <div class="propositionImgContainer">
                                <img id="" class="proposition" src="" alt="">
                            </div>
                            <p class="propositionText"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="backOnPreviousQuestionButton" class="backOnPreviousQuestionButton previousContainer disabled_v2">
                <img class="backOnPreviousQuestionImg previous" src="assets/img/previous_darkfill.svg">
            </div>
            <a class="buttonDefault abandonGame" href="index.php?action=game&video=false">abandonner</a>
        </div>
        <?php 
            if ($GLOBALS["playerHaveStats"] === true)
            {
        ?>
            <div class="step_scores statsLocalAverageContainer disabled_v2">
                    <h3>Moyennes:</h3>
                    <div class="statsContainer titleEnv">
                        <div class="statsBar envi"><?=$gameInfos->playerStats["stats_envi"]?></div>
                        <p>Env.</p>
                    </div>
                    <div class="statsContainer titleSante">
                        <div class="statsBar sante"><?=$gameInfos->playerStats["stats_sante"]?></div>
                        <p>Santé</p>
                    </div>
                    <div class="statsContainer titleSocial">
                        <div class="statsBar social"><?=$gameInfos->playerStats["stats_social"]?></div>
                        <p>Social</p>
                    </div>

            </div>
        <?php
            }
            if ($activeScoreTab === true)
            {
        ?>
            <div class="step_scores statsLocalPreviousGameContainer disabled_v2">
                <h3><?=$currentTheme?>:</h3>
                <div class="statsContainer titleEnv">
                    <div class="statsBar envi"><?=$statsEnvThemefromLastGame?></div>
                    <p>Env.</p>
                </div>
                <div class="statsContainer titleSante">
                    <div class="statsBar sante"><?=$statsSanThemefromLastGame?></div>
                    <p>Santé</p>
                </div>
                <div class="statsContainer titleSocial">
                    <div class="statsBar social"><?=$statsSoThemefromLastGame?></div>
                    <p>Social</p>
                </div>
            </div>
        <?php
            }
            else
            {
        ?>
                <p id="homeSms" class="homeSms">Bienvenue chez toi!</p>
        <?php
            }
        ?>
        <div id="flsContainer" class="flsContainer disabled">
        </div>
    </div>
<?php
$content = ob_get_clean();
ob_start();
?>
    <script>
        let gameInfos = <?=json_encode($gameInfos)?>;
        let allThemesActivation = <?=json_encode($allThemes)?>;
        let activeScoreTab = false;
        <?php
        if ($activeScoreTab === true)
        {
            ?>activeScoreTab = true;<?php
        }
        ?>

    </script>

    <script type= "text/javascript" src="assets/js/dgl_game-engine.js"></script>
    <script type= "text/javascript" src="assets/js/fls_game-engine.js"></script>
    <script type= "text/javascript" src="assets/js/questions.js"></script>
    <script type= "text/javascript" src="assets/js/game.js"></script>
    <script type= "text/javascript" src="assets/js/stats.js"></script>
    <script type= "text/javascript" src="assets/js/createDomElem.js"></script>
<?php
$script = ob_get_clean();

require('./view/st_template.php');
