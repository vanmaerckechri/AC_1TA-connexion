<?php
	$result = Classrooms::displayThisRoom($_GET['idcr']);
	$studentsList = $result[0];
	$className = $result[1];

    if (isset($createStudent))
    {
        $createStudentClass = "createStudent";
        $buttonStatus = "formButton toolFocus";
    }
    else
    {
        $createStudentClass = "createStudent hide";
        $buttonStatus = "formButton";
    }

    $pageName = "Gestion de la Classe: <span class='classname'>".$className."</span>";

    ob_start();
	?>
		<div class="tools">
            <button id="create" class="<?=$buttonStatus?>">Ajouter des Élèves</button>
	        <button id="delete" class="formButton">Effacer les Élèves Sélectionnés</button>
	        <button id="updateStudents" class="formButton">Modifier les Élèves Sélectionnés</button>
	    </div>
        <form class="<?=$createStudentClass?>" action="admin.php?action=addStudents&idcr=<?=$_GET['idcr']?>" method="post">
            <label for="newStudentNickname">Nom d'utilisateur</label>
            <input class="newStudentNick formInput" type="text" name="newStudentNickname">
            <label for="newStudentPassword">Mot de Passe</label>
            <input class="formInput" type="text" name="newStudentPassword">
            <input class="formButton" type="submit" value="Enregistrer">
        </form>
	<?php 
	$tools = ob_get_clean();

    ob_start();
		?>
		<form id="deleteList" class="list" action="" method="post">
	<?php
		foreach ($studentsList  as $row)
		{
	?>
           	<div class="button_listContent">
           		<input class="formInput" type="checkbox" name="students[]" value="<?=$row['id']?>">
           		<a class='classroomsAndStudents' href="admin.php?action=manageModifyStudents&idst=<?=$row['id']?>"><?=$row['nickname']?></a>
           	</div>
	<?php
	}
	?>
		</form>
        <p><?=$_SESSION['smsAlert']['default']?></p>
	<?php
	$content = ob_get_clean();

    ob_start();
	?>
        <script>
    	window.addEventListener('load', function()
    	{
    		let deleteStudents = document.querySelector('#delete');
    		let selectedStudents = document.querySelector('#deleteList');
    		let confirmDeleteSelectedStudents = function()
    		{
    			let confirm = prompt('ATTENTION! Cette opération est irréversible! Pour valider la suppression, veuillez écrire: "supprimer"!');
    			if (confirm == "supprimer" || confirm == "SUPPRIMER")
    			{
    				selectedStudents.action = 'admin.php?action=deleteStudents&idcr=<?=$_GET['idcr']?>';
    				selectedStudents.submit();
    			}
    		}
    		deleteStudents.addEventListener('click', confirmDeleteSelectedStudents, false);

            let addStudents = document.querySelector('#create');
            let addStudentsForm = document.querySelector('.createStudent');
            let addStudentsManagement = function()
            {
                addStudentsForm.classList.toggle("hide");
                addStudents.classList.toggle("toolFocus");
            }
            addStudents.addEventListener('click', addStudentsManagement, false);

    	}, false);
        </script>
    <?php $script = ob_get_clean();?>

<?php require('./view/ad_template.php'); ?>