<?php

$title = "1TerreAction - Gestion des Planètes";

// Free Classroom List
ob_start();
?>
    <div class="ui">
        <div class="planetInfosContainer disabled">
            <div class="planetDeleteContainer">
                <div class="previousContainer">
                    <img class="previous" src="assets/img/previous_light.svg" alt="retour à l'écran précédent">
                </div>
            </div>
            <h3 class="planetInfosTitle">
            </h3>
            <div class="freeClassroomsContainer disabled">
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
        </div>
        <p class="planetName"></p>
    </div>
<?php
$content = ob_get_clean();
// JAVASCRIPT
    // transmit information from planets to javascript
$planetList = json_encode($planetList);
$studentsList = json_encode($studentsList);
ob_start();
?>
    <script>
        let planetsList = <?=$planetList?>;
        let studentsList = <?=$studentsList?>;
        let freeClassroomLength = <?=count($freeClassrooms)?>;
        planetsList.push({name: "Créer une Nouvelle Planète"});
    </script>
    <script src="assets/library/three.js"></script>
    <script src="assets/library/OrbitControls.js"></script>
    <script src="assets/js/tools.js"></script>                  
    <script src="assets/js/solarsystem.js"></script>
<?php
$script = ob_get_clean();

require('./view/ad_template.php');