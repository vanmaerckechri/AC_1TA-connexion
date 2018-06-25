<?php
        $pageName = "Gestion de la Classe";
        ob_start();
?>
        <button id = "addStudents">Ajouter des élèves</button>
        <button id = "deleteStudents">Effacer les elèves sélectionnés</button>
        <button id = "updateStudents">Modifier les élèves sélectionnés</button>
        <?=Classrooms::displayThisRoom($_GET['idcr']);?>
        <script>
        	window.addEventListener('load', function()
        	{
        		let deleteStudents = document.querySelector('#deleteStudents');
        		let selectedStudents = document.querySelector('#studentsListForm');
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
<?php $content = ob_get_clean(); ?>

<?php require('./view/ad_template.php'); ?>