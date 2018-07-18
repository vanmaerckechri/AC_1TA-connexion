<?php

$title = "1TerreAction - En Jeu";

// Free Classroom List
ob_start();
?>
    <div class="headerProfile">
        <div class="profile">
            <?=$_SESSION['nickname']?>
            <a href="admin.php?action=disco" class="disconnect">X</a>
        </div>
    </div>
    <div id="main">
        <div id="step_localBackground" class="localBgContainer">
            <img class="localBg localBgAir" src="<?=$localBgAir_imgSrc?>" alt="ciel faisant parti du parallax">
            <img class="localBg localBgWater" src="<?=$localBgWater_imgSrc?>" alt="eau faisant partie du parallax">
            <img class="localBg localBgForest" src="<?=$localBgForest_imgSrc?>" alt="arbres faisant partis du parallax">
            <img class="localBg localBgAirFilter" src="<?=$localBgAirFilter_imgSrc?>" alt="air filtre faisant parti du parallax">
        </div>
        </div>
        <div id="step_chooseTheme" class="themesContainer">
        <?php
            foreach ($allThemes as $key => $theme)
            {
                $lockedClass = "";
                if ($key >= $gameInfos['unlocked_theme'])
                {
                    $lockedClass = "locked";
                    $theme = "veroullé";
                }
            ?>
                <div class="theme <?=$lockedClass?>"><?=$theme?></div>
            <?php
            }
        ?>
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
    <script src="assets/js/game.js"></script>
<?php
$script = ob_get_clean();

require('./view/st_template.php');
