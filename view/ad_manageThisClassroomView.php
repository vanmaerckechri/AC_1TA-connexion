<?php
    $result = Classrooms::displayThisRoom($_GET['idcr']);
    $studentsList = $result[0];
    $className = htmlspecialchars($result[1], ENT_QUOTES);
    $pageName = "Gestion des Élèves de la Classe: <span class='classname'>".$className."</span>";
    // CREATE!
    if (isset($form_createOpen))
    {
        $form_createOpen = "form_create";
        $buttonStatus = "formButton toolFocus";
    }
    else
    {
        $form_createOpen = "form_create hide";
        $buttonStatus = "formButton";
    }
    ob_start();
    ?>
        <div class="tools">
            <div style="width: 90px;"></div>
            <div>
                <button id="button_create" class="<?=$buttonStatus?>">Ajouter</button>
                <button id="delete" class="formButton">Effacer</button>
            </div>
            <div style="width: 90px;"></div>
        </div>
        <form class="<?=$form_createOpen?>" action="admin.php?action=addStudents&idcr=<?=$_GET['idcr']?>" method="post">
            <label for="newStudentNickname">Nom d'utilisateur</label>
            <input class="newStudentNick formInput" type="text" name="newStudentNickname" autofocus>
            <p><?=$_SESSION['smsAlert']['nickname']?></p>
            <label for="newStudentNickname">Mot de Passe</label>
            <input class="newStudentNick formInput" type="text" name="newStudentPassword">
            <p><?=$_SESSION['smsAlert']['password']?></p>
            <input class="formButton" type="submit" value="Enregistrer">
        </form>
    <?php 
    $tools = ob_get_clean();
    // STUDENTS LIST!
    ob_start();
    ?>
    <p class="sms"><?=$_SESSION['smsAlert']['default']?></p>
    <form class="list" action="admin.php?action=renameClassroom" method="post">
    <?php
    foreach ($studentsList as $key => $row)
    {
        $name = htmlspecialchars($row['nickname'], ENT_QUOTES);
        $pwd = htmlspecialchars($row['password'], ENT_QUOTES);
    ?>
        <div id="classroom<?=$key?>" class="listElementsContainer">
            <input class="listElementDeleteCheck" type="checkbox" name="students[]" value="<?=$row['id']?>">
            <p class='listElementName'><?=$name?></p>
            <p class='pwd' style="display: none;"><?=$pwd?></p>
            <div class="buttonRename">
                <img src="assets/icons/rename.svg" alt="icone pour renommer une classe">
            </div>
        </div>
    <?php
    }
    ?>
    </form>
    <?php
    $content = ob_get_clean();

    ob_start();
    ?>
        <script>
            let detectDeleteElement = "students";
            let deleteElementPartofLink = "deleteStudents&idcr="+<?=$_GET['idcr']?>;
        </script>
        <script src="scripts/admin_tools.js"></script>
    <?php $script = ob_get_clean();?>

<?php require('./view/ad_template.php'); ?>