window.addEventListener('load', function()
{	
	// VALIDATION WINDOW!
	let validate = function(form, action, text)
	{
		// Create Modal
		let modalContainer = document.createElement("div"); 
		modalContainer.setAttribute("class", "modalContainer");

		let validationBox = document.createElement("div");
		validationBox.setAttribute("class", "validationBox");

		let textContainer = document.createElement("p");

		let buttonYes = document.createElement("button");
		buttonYes.setAttribute("id", "validationYes");
		buttonYes.setAttribute("class", "formButton");
		buttonYes.appendChild(document.createTextNode("OUI"));

		let buttonNo = document.createElement("button");
		buttonNo.setAttribute("id", "validationNo");
		buttonNo.setAttribute("class", "formButton");
		buttonNo.appendChild(document.createTextNode("NON"));

		validationBox.appendChild(textContainer);
		validationBox.appendChild(buttonYes);
		validationBox.appendChild(buttonNo);
		modalContainer.appendChild(validationBox);

		document.body.appendChild(modalContainer);
		textContainer.innerHTML = text;

		let validateYes = function()
		{
			modalContainer.remove();
    		form.action = 'admin.php?action='+action;
    		form.submit();
    	}
		let validateNo = function()
		{
			modalContainer.remove();
		}

		buttonYes.addEventListener('click', validateYes, false);
		buttonNo.addEventListener('click', validateNo, false);
	}
	// CLOSE TOOLS!
	let closeTools = function(close)
	{
		let lastClassroomContainerResult;
		if (close == "create")
		{
			// Hide Create Form
			if (!createForm.classList.contains('hide'))
			{
			    createForm.classList.toggle("hide");
			    createButton.classList.toggle("toolFocus");	
			}
		}
		if (close == "rename" || close == "create")
		{
        	// Remove Rename Field if Already Exist
        	if (document.querySelector('.listElementRename'))
        	{
        		let lastElementRename = document.querySelector('.listElementRename');
        		lastElementParentRename = lastElementRename.parentElement;
        		lastClassroomContainer = lastElementParentRename.parentElement;
        		lastElementParentRename.removeChild(lastElementRename);
        		lastClassroomContainerResult = lastClassroomContainer;
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
        }
        return lastClassroomContainerResult;
	}
    // DELETE SCRIPT!
	let button_deleteClassrooms = document.querySelector('#delete');
	let confirmDeleteSelectedClassrooms = function()
	{
		closeTools("create");
        let selectedClassrooms = document.querySelector('.list');
        let listElementsContainer = document.querySelectorAll('.listElementDeleteCheck');
        for (let i = listElementsContainer.length - 1; i >= 0; i--)
        {
        	if (listElementsContainer[i].checked == true)
        	{
        		validate(selectedClassrooms, 'deleteClassrooms', '<span class="smsAlert"> ATTENTION! Cette opération est irréversible! Tous les élèves appartenant à la classe seront eux aussi effacés!</span>');
   				return;
        	}
        }
        let smsContainer = document.querySelector('.sms');
        smsContainer.innerHTML = '<span class="smsAlert">Vous n\'avez rien Sélectionné!</span>';
	}
	button_deleteClassrooms.addEventListener('click', confirmDeleteSelectedClassrooms, false);

	// CREATE SCRIPT!
    let createButton = document.querySelector('#button_create');
    let createForm = document.querySelector('.form_create');
    let openCreateTool = function()
    {            	
    	closeTools("rename");
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
    	let lastClassroomContainer = closeTools("create");
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