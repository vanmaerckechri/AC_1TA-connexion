<?php

if (count($freeClassrooms) > 0)
{
    $freeClassroomsTitle = "Veuillez choisir l'une de vos classes!";
}
else
{
    $freeClassroomsTitle = "Aucune classe disponible!"; 
}

$title = "1TerreAction - Gestion des Planètes";

ob_start();
?>
    <div class="ui">
        <div class="freeClassroomsList disabled">
            <h3 class="freeClassroomsTitle">
                <?=$freeClassroomsTitle?>
            </h3>
        <?php
        foreach ($freeClassrooms as $freeCr)
        {
            ?>
            <div class="freeClassroom">
                <a class="freeClassroomName" href="admin.php?action=createplanet&idcr=<?=$freeCr['id']?>"><?=$freeCr['name']?></a>
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