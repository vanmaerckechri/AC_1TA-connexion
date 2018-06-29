<?php
	$classList = Classrooms::displayRooms();
    $pageName = "Gestion des Classes";

    ob_start();
	?>
		<div class="tools">
            <div style="width: 90px;"></div>
            <div>
    	    	<button id = "addClass" class="formButton">Ajouter</button>
    	    	<button id = "delete" class="formButton">Effacer</button>
            </div>
            <div style="width: 90px;"></div>
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
		$name = htmlspecialchars($row['name'], ENT_QUOTES);
	?>
		<div class="button_listContent">
			<input class="formInput" type="checkbox" name="classrooms[]" value="<?=$row['id']?>">
			<a class='classroomsAndStudents' href='admin.php?action=manageThisClassroom&idcr=<?=$row['id']?>'><?=$name?></a>
            <div class="button_rename">
                <img src="assets/icons/rename.svg" alt="icone pour renommer une classe">
                <p><?=$row['id']?></p>
                <p><?=$name?></p>
            </div>
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
            // Delete
    		let button_deleteClassrooms = document.querySelector('#delete');
    		let confirmDeleteSelectedClassrooms = function()
    		{
                let selectedClassrooms = document.querySelector('.list');
    			let confirm = prompt('ATTENTION! Cette opération est irréversible! Tous les élèves appartenant à la classe seront eux aussi effacés. Pour valider la suppression, veuillez écrire: "supprimer"!');
    			if (confirm == "supprimer" || confirm == "SUPPRIMER")
    			{
    				selectedClassrooms.action = 'admin.php?action=deleteClassrooms';
    				selectedClassrooms.submit();
    			}
    		}
    		button_deleteClassrooms.addEventListener('click', confirmDeleteSelectedClassrooms, false);

            // Insert
    		let button_addClass = document.querySelector('#addClass');
    		let chooseNewClassName = function()
    		{
                let selectedClassrooms = document.querySelector('.list');
    			let newname = prompt("Veuillez entrer un nom pour votre nouvelle classe");
    			console.log(newname);
    			if (newname != "" && newname != null)
    			{
    				selectedClassrooms.action = 'admin.php?action=createClassroom';
    				selectedClassrooms.innerHTML += "<input type='text' class='newClassName' name='newClassName'>";
    				document.querySelector('.newClassName').value = newname;
    				selectedClassrooms.submit();
    			}
    		}
    		button_addClass.addEventListener('click', chooseNewClassName, false);

            // Update
            let button_rename = document.querySelectorAll('.button_rename');
            let startRenameTool = function(event)
            {
                let selectedClassrooms = document.querySelector('.list');
                let classroomInfos = event.target.querySelectorAll('p');
                let classroomId = classroomInfos[0].innerHTML;
                let classroomName = classroomInfos[1].innerHTML;

                let rename = prompt('Veuillez entrer le nouveau nom de votre classe', classroomName);
                if (rename != "" && rename != null)
                {
                    selectedClassrooms.action = 'admin.php?action=renameClassroom';
                    selectedClassrooms.innerHTML += "<input type='number' name='idClassroom' value="+classroomId+">";
                    selectedClassrooms.innerHTML += "<input type='text' class='renameClassroom' name='renameClassroom'>";
                    document.querySelector('.renameClassroom').value = rename;
                    selectedClassrooms.submit();
                }
            }
            for (let i = button_rename.length - 1; i >= 0; i--)
            {
                button_rename[i].addEventListener('click', startRenameTool, true);
            }

    	}, false);
        </script>
    <?php $script = ob_get_clean();?>

<?php require('./view/ad_template.php'); ?>