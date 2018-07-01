<?php
	$classList = Classrooms::displayRooms();
    $pageName = "Gestion des Classes";
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
	    <form class="<?=$form_createOpen?>" action="admin.php?action=createClassroom" method="post">
            <label for="newStudentNickname">Nom de la Classe</label>
            <input class="newStudentNick formInput" type="text" name="newClassName" autofocus>
            <input class="formButton" type="submit" value="Enregistrer">
        </form>
	<?php 
	$tools = ob_get_clean();
	// CLASSROOMS LIST!
	ob_start();
	?>
	<p class="sms"><?=$_SESSION['smsAlert']['default']?></p>
	<form class="list" action="admin.php?action=renameClassroom" method="post">
		<?php
		foreach ($classList as $key => $row)
		{
			$name = htmlspecialchars($row['name'], ENT_QUOTES);
		?>
			<div id="classroom<?=$key?>" class="listElementsContainer">
				<input class="listElementDeleteCheck" type="checkbox" name="classrooms[]" value="<?=$row['id']?>">
				<a class='listElementName' href='admin.php?action=manageThisClassroom&idcr=<?=$row['id']?>'><?=$name?></a>
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
            let detectDeleteElement = "classroom";
            let deleteElementPartofLink = "";
        </script>
        <script src="scripts/admin_tools.js"></script>
    <?php $script = ob_get_clean();?>

<?php require('./view/ad_template.php'); ?>