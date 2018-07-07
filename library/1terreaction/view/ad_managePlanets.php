<?php

$title = "1TerreAction - Gestion des Planètes";

ob_start();
?>
    <div class="ui">
        <div class="freeClassroomsList">
            <h3 class="freeClassroomsTitle">
                Veuillez choisissez l'une de vos classes!
                <span class="close">X</span>
            </h3>
        <?php
        foreach ($freeClassrooms as $freeCr)
        {
            ?>
            <div class="freeClassroom">
                <p class="freeClassroomName"><?=$freeCr['name']?></p>
                <p class="freeClassroomId"><?=$freeCr['id']?></p>
            </div>
            <?php
        }
        ?>
        </div>
        <p class="planetName"></p>
    </div>
<?php
$content = ob_get_clean();

ob_start();
?>
    <script>
        let planetNamesList = [];
    <?php
    foreach ($planetList as $planet) 
    {
    ?>
        planetNamesList.push("<?=$planet['name']?>");
    <?php
    }
    ?>
        planetNamesList.push("Créer une Nouvelle Planète");
    </script>
    <script src="assets/library/three.js"></script>
    <script src="assets/library/OrbitControls.js"></script>                     
    <script src="assets/js/solarsystem.js"></script>
<?php
$script = ob_get_clean();

require('./view/ad_template.php');