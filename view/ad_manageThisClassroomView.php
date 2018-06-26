<?php
	$result = Classrooms::displayThisRoom($_GET['idcr']);
	$studentsList = $result[0];
	$className = $result[1];

    $pageName = "Gestion de la Classe: <span class='classname'>".$className."</span>";

    ob_start();
	?>
		<div class="tools">
	    	<button id = "addStudents">Ajouter des Élèves</button>
	        <button id = "delete">Effacer les Élèves Sélectionnés</button>
	        <button id = "updateStudents">Modifier les Élèves Sélectionnés</button>
	    </div>
	<?php 
	$tools = ob_get_clean();

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
    		let deleteStudents = document.querySelector('#delete');
    		let selectedStudents = document.querySelector('.list');
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
    	}, false);
        </script>
    <?php $script = ob_get_clean();?>

<?php require('./view/ad_template.php'); ?>