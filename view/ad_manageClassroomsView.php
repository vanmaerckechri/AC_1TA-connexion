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
    	    	<button id="button_create" class="formButton">Ajouter</button>
    	    	<button id="delete" class="formButton">Effacer</button>
            </div>
            <div style="width: 90px;"></div>
	    </div>
	    <form class="<?=$form_createOpen?>" action="admin.php?action=createClassroom" method="post">
            <label for="newStudentNickname">Nom d'utilisateur</label>
            <input class="newStudentNick formInput" type="text" name="newClassName" autofocus>
            <input class="formButton" type="submit" value="Enregistrer">
        </form>
	<?php 
	$tools = ob_get_clean();
	// CLASSROOMS LIST!
	ob_start();
	?>
	<p><?=$_SESSION['smsAlert']['default']?></p>
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
    	window.addEventListener('load', function()
    	{
            // DELETE SCRIPT!
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

    		// CREATE SCRIPT!
            let createButton = document.querySelector('#button_create');
            let createForm = document.querySelector('.form_create');
            let openCreateTool = function()
            {
                createForm.classList.toggle("hide");
                createButton.classList.toggle("toolFocus");
                // autofocus
                if (createForm.classList != "hide")
                {
                    let inputNewStudentNickname = document.querySelector('.newStudentNick');
                    inputNewStudentNickname.focus();
                }
            }
            createButton.addEventListener('click', openCreateTool, false);

            // RENAME SCRIPT!
            let button_rename = document.querySelectorAll('.buttonRename');
            let openRenameTool = function(event)
            {
            	let selectedClassrooms = document.querySelector('.list');
            	let lastClassroomContainer;
            	// Return to Normal Last Open Field
            		// Remove Rename Field if Already Exist
            	if (document.querySelector('.listElementRename'))
            	{
            		let lastElementRename = document.querySelector('.listElementRename');
            		lastElementParentRename = lastElementRename.parentElement;
            		lastClassroomContainer = lastElementParentRename.parentElement;
            		lastElementParentRename.removeChild(lastElementRename);
            	}
            		// Remove Submit if Already Exist
            	if (document.querySelector('#submit'))
            	{
            		let lastSubmit = document.querySelector('#submit');
            		let lastSubmitParent = lastSubmit.parentElement;
            		lastSubmitParent.removeChild(lastSubmit);
            	}
            		// Display Static Name
            	let elementNames = document.querySelectorAll('.list a');
            	for (let i = elementNames.length - 1; i >= 0; i--)
            	{
            		if (elementNames[i].classList.contains('hide'))
            		{
            			elementNames[i].classList.remove('hide');
            			elementNames[i].classList.add('listElementName');
            		}
            	}
            	
            	// Create Rename Field
            	let buttonRename = event.target;
            	let classroomContainer = buttonRename.parentElement;
            	if (lastClassroomContainer != classroomContainer)
            	{
	            	let classroomName = classroomContainer.querySelector('a').innerHTML;
	            	let classroomId = classroomContainer.querySelector(".listElementDeleteCheck").value;
	            		// Name Field
	  				let listElementRenameContainer = document.createElement("div"); 
	  				listElementRenameContainer.setAttribute("class", "listElementRenameContainer");
	  				let listElementRename = document.createElement("input"); 
					listElementRename.setAttribute("type", "text");
					listElementRename.setAttribute("name", "renameClassroom");
					listElementRename.setAttribute("value", classroomName);
					listElementRename.setAttribute("class", "listElementRename");
					listElementRenameContainer.appendChild(listElementRename);
	            		// classroomId Field
	  				let listElementId = document.createElement("input"); 
					listElementId.setAttribute("type", "hidden");
					listElementId.setAttribute("name", "idClassroom");
					listElementId.setAttribute("value", classroomId);
					listElementRenameContainer.appendChild(listElementId);
					classroomContainer.insertBefore(listElementRenameContainer, buttonRename);
						// submit
	  				let submit = document.createElement("input"); 
					submit.setAttribute("type", "submit");
					submit.setAttribute("value", "Enregistrer");
					submit.setAttribute("id", "submit");
					classroomContainer.appendChild(submit);

					let elementName = classroomContainer.querySelector('.listElementName');
					elementName.classList.toggle("hide");
					document.querySelector('.listElementRename').focus();
				}
            }
            for (let i = button_rename.length - 1; i >= 0; i--)
            {
                button_rename[i].addEventListener('click', openRenameTool, false);
            }

    	}, false);
        </script>
    <?php $script = ob_get_clean();?>

<?php require('./view/ad_template.php'); ?>