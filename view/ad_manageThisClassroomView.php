<?php
    $pageName = "Gestion de la Classe";

    ob_start();
	?>
		<div class="tools">
	    	<button id = "addStudents">Ajouter des élèves</button>
	        <button id = "deleteStudents">Effacer les elèves sélectionnés</button>
	        <button id = "updateStudents">Modifier les élèves sélectionnés</button>
	    </div>
	<?php 
	$tools = ob_get_clean();

	$studentsList = Classrooms::displayThisRoom($_GET['idcr']);

    ob_start();
		?>
		<form class="list" action="" method="post">
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
    		let deleteStudents = document.querySelector('#deleteStudents');
    		let selectedStudents = document.querySelector('.list');
    		let confirmDeleteSelectedStudents = function()
    		{
    			let confirm = prompt('Si vous êtes sûre de vouloir supprimer les élèves sélectionnés, écrivez: "supprimer"');
    			if (confirm == "supprimer" || confirm == "SUPPRIMER")
    			{
    				selectedStudents.action = 'admin.php?action=deleteStudents&idcr=<?=$_GET['idcr']?>';
    				selectedStudents.submit();
    			}
    		}
    		deleteStudents.addEventListener('click', confirmDeleteSelectedStudents, false);
    	}, false);
        </script>
    <?php $script = ob_get_clean();?>

<?php require('./view/ad_template.php'); ?>