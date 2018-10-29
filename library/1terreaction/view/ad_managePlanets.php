<?php

$title = "1TerreAction - Gestion des Planètes";

// Free Classroom List
ob_start();
?>
    <div class="ui">
        <div class="planetInfosContainer disabled">
            <div class="planetDeleteContainer">
                <div class="previousContainer">
                    <div class="previous">
                        <img src="assets/img/previous_darkfill.svg" alt="retour à l'écran précédent">
                        Retour
                    </div>
                </div>
            </div>
            <div id="themeButtonsContainer" class="themeButtonsContainer">
                <span id="planetOnOffButton" class="onOffButton"></span>
            </div>
            <div id="planetInfosTitleContainer" class="planetInfosTitleContainer">
                <div class="planetInfosTitleDummy"></div>
                <h3 id="planetInfosTitle" class="planetInfosTitle"></h3>
            </div>
            <div class="freeClassroomsContainer disabled">
            <?php
            foreach ($freeClassrooms as $freeCr)
            {
                ?>
                <div class="freeClassroom">
                    <a class="freeClassroomName" href="admin.php?action=createplanet&idcr=<?=$freeCr['id']?>"><?=htmlspecialchars($freeCr['name'], ENT_QUOTES)?></a>
                </div>
                <?php
            }
            ?>
            </div>
        </div>
        <div class="leaveGameButtonContainer">
            <a class="buttonDefault leaveGameButton leaveGameButtonAdmin" href="../../library.php">Quitter le Jeu</a>
        </div>
        <?php
            if (!isset($planetList) || empty($planetList))
            {
                ?><div class="clickHere"><p>Cliquez ici pour lier l'une de vos classes à une planète</p><img src="assets/img/arrow_bot.svg" alt=""></div><?php
            }
        ?>
        <div class="buttonsContainer">
            <button id="aboutUsButton" class="buttonDefault aboutUsButton">À Propos</button>
            <button id="adminTutoButton" class="buttonDefault adminTutoButton">Déroulement</button>
            <button id="dossierPeda" class="buttonDefault dossierPeda">Dossier Pédagogique</button>
            <?php
            {/*
                if (isset($planetList) && !empty($planetList));
                {
                    ?><a id="testGame" class="buttonDefault testGame" href="./admin.php?action=game&idCr=<?=$planetList[0]['id_classroom']?>">Tester le Jeu</a><?php
                }*/
            }?>
        </div>
        <div id="step_scores" class="step_scores step_scoresAdmin statsPlanetContainer">
            <div class="statsContainer titleEnv">
                <div id="stats_environnement" class="statsBar envi"></div>
                <p>Env.</p>
            </div>
            <div class="statsContainer titleSante">
                <div id="stats_sante" class="statsBar sante"></div>
                <p>Santé</p>
            </div>
            <div class="statsContainer titleSocial">
                <div id="stats_social" class="statsBar social"></div>
                <p>Social</p>
            </div>
        </div>
        <div class="planetNameContainer">
            <span id="planetName" class="planetName"></span>
        </div>
        <div id="uiMainMenuStudent" class="uiMainMenuStudent disabled">
            <div id="modalContent">
            </div>
        </div>
    </div>
<?php
$content = ob_get_clean();
// JAVASCRIPT
    // transmit information from planets to javascript
$planetList = json_encode($planetList);
$studentsInfos = json_encode($studentsInfos);
$themeActivationList = json_encode($themeActivationList);
ob_start();
?>
    <script>
        let planetsList = <?=$planetList?>;
        let studentsInfos = <?=$studentsInfos?>;
        let themeActivationList = <?=$themeActivationList?>;
        let freeClassroomLength = <?=count($freeClassrooms)?>;
        planetsList.push({name: "Créer une Nouvelle Planète"});
    </script>
    <script src="assets/threejs/three.js"></script>
    <script src="assets/threejs/OrbitControls.js"></script>
    <script src="assets/js/tools.js"></script>    
    <script src="assets/js/stats.js"></script>
    <script src="assets/js/questions.js"></script>             
    <script src="assets/js/ad_managePlanets.js"></script>
<?php
$script = ob_get_clean();

require('./view/ad_template.php');