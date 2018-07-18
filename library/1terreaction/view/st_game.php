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
        <div class="localBgContainer">
            <img class="localBg localBgWater" src="<?=$localBgWater_imgSrc?>" alt="eau faisant partie du parallax">
            <img class="localBg localBgForest" src="<?=$localBgForest_imgSrc?>" alt="arbres faisant partis du parallax">
            <img class="localBg localBgAir" src="<?=$localBgAir_imgSrc?>" alt="ciel faisant parti du parallax">
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
