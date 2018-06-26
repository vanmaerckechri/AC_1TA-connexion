<?php
	$classList = Classrooms::displayRooms();
    $pageName = "Gestion des Classes";

    ob_start();
	?>
		<div class="tools">
	    	<button id = "addStudents">Ajouter une Classe</button>
	    	<button id = "delete">Effacer les Classes Sélectionnées</button>
	    </div>
	<?php 
	$tools = ob_get_clean();

	ob_start();

    ob_start();

	?>
	<form class="list" action="" method="post">
	<?php
	foreach ($classList as $row)
	{
	?>
		<div class="button_listContent">
			<input class="formInput" type="checkbox" name="classrooms[]" value="<?=$row['id']?>">
			<a class='classroomsAndStudents' href='admin.php?action=manageThisClassroom&idcr=<?=$row['id']?>'><?=$row['name']?></a>
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
    		let selectedStudents = document.querySelector('.list');
    		let confirmDeleteSelectedStudents = function()
    		{
    			let confirm = prompt('ATTENTION! Cette opération est irréversible! Tous les élèves appartenant à la classe seront eux aussi éffacés. Pour valider la suppression, veuillez écrire: "supprimer"!');
    			if (confirm == "supprimer" || confirm == "SUPPRIMER")
    			{
    				selectedStudents.action = 'admin.php?action=deleteClassrooms';
    				selectedStudents.submit();
    			}
    		}
    		deleteStudents.addEventListener('click', confirmDeleteSelectedStudents, false);
    	}, false);
        </script>
    <?php $script = ob_get_clean();?>

<?php require('./view/ad_template.php'); ?>