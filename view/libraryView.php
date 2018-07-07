<?php

$pageName = "Ludothèque ";

ob_start();

$tools = ob_get_clean();

ob_start();
?>
<div class="libElemContainer">
<?php
foreach ($libraryList as $libElem)
{
?>
    <a class="libElemLink" href="library/<?=$libElem['folder']?>/index.html">
        <h3 class="libElemTitle">
            <?=$libElem['name']?>
        </h3>
        <img class="libElemCover" src="library/<?=$libElem['folder']?>/cover.jpg" alt="vignette pour <?=$libElem['name']?>">
        <div class="libElemDescription">
            <p><strong>Description: </strong><?=$libElem['description']?></p>
        </div>
    </a>
<?php
}
?>
</div>
<?php
$content = ob_get_clean();

ob_start();
$script = ob_get_clean();

require('./view/ad_template.php');